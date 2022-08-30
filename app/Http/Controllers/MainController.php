<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Promo;
use App\Models\Promocode;
use App\Services\HelpFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Content;
use App\Models\Location;
use App\Models\FlightSimulator;
use App\Models\ProductType;
use App\Models\Product;
use App\Models\User;
use Validator;
use App\Repositories\PromocodeRepository;

class MainController extends Controller
{
	private $request;
	private $promocodeRepo;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, PromocodeRepository $promocodeRepo)
	{
		$this->request = $request;
		$this->promocodeRepo = $promocodeRepo;
	}
	
	/**
	 * @param null $cityAlias
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function home($cityAlias = null)
	{
		if ($cityAlias && !in_array($cityAlias, City::ALIASES)) {
			abort(404);
		}
		
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		// "Наша команда"
		$users = User::where('enable', true)
			->whereIn('city_id', [$city->id, 0])
			->whereIn('role', [User::ROLE_ADMIN, User::ROLE_PILOT])
			->orderBy('name')
			->get();

		// Отзывы
		$reviewParentContent = HelpFunctions::getEntityByAlias(Content::class, Content::REVIEWS_TYPE);
		if ($reviewParentContent) {
			$reviews = Content::where('is_active', true)
				->where('parent_id', $reviewParentContent->id)
				->latest()
				->limit(10)
				->get();
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'home_' . $city->alias);

		return view('home', [
			'users' => $users,
			'reviews' => $reviews,
			'page' => $page ?? new Content,
			'city' => $city,
			'cityAlias' => $cityAlias,
		]);
	}
	
	/**
	 * @param null $productAlias
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getBookingModal($productAlias = null)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		if ($productAlias) {
			$product = Product::where('alias', $productAlias)
				->first();
		} else {
			// Продукты "Regular"
			$products = $city->products()
				->whereHas('productType', function ($query) {
					return $query->where('alias', ProductType::REGULAR_ALIAS);
				})
				->orderBy('duration')
				->get();
		}
		
		// Локации
		$locations = $city->locations;
		
		// Праздники
		$holidays = Deal::HOLIDAYS;

		$VIEW = view('modal.booking', [
			'city' => $city,
			'product' => $product ?? '',
			'products' => $products ?? [],
			'locations' => $locations,
			'holidays' => $holidays,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCertificateModal()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$productAlias = $this->request->product_alias ?? '';
		
		if ($productAlias) {
			$product = Product::where('alias', $productAlias)
				->first();
		} else {
			$products = $city->products()
				->orderBy('product_type_id')
				->orderBy('duration')
				->get();
		}
		
		$activePromocodes = $this->promocodeRepo->getList($city, true, false);
		
		$VIEW = view('modal.certificate', [
			'city' => $city,
			'product' =>$product ?? null,
			'products' => $products ?? [],
			'activePromocodes' => $activePromocodes,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $productAlias
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCertificateBookingModal($productAlias)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$product = Product::where('alias', $productAlias)
			->first();
		
		$VIEW = view('modal.certificate-booking', [
			'product' => $product,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function promocodeVerify()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$number = $this->request->promocode ?? '';
		if (!$number) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.не-передан-промокод')]);
		}

		$locationId = $this->request->location_id ?? 0;
		$simulatorId = $this->request->simulator_id ?? 0;
		
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);

		$date = date('Y-m-d H:i:s');

		//\DB::connection()->enableQueryLog();
		$promocode = Promocode::whereRaw('lower(number) = "' . mb_strtolower($number) . '"')
			->whereRelation('cities', 'cities.id', '=', $city->id)
			->where('is_active', true)
			->where(function ($query) use ($date) {
				$query->where('active_from_at', '<=', $date)
					->orWhereNull('active_from_at');
			})
			->where(function ($query) use ($date) {
				$query->where('active_to_at', '>=', $date)
					->orWhereNull('active_to_at');
			});
		if ($locationId) {
			$promocode = $promocode->whereIn('location_id', [$locationId, 0]);
		}
		if ($simulatorId) {
			$promocode = $promocode->whereIn('flight_simulator_id', [$simulatorId, 0]);
		}
		$promocode = $promocode->first();
		//\Log::debug(\DB::getQueryLog());
		if (!$promocode) {
			return response()->json(['status' => 'error', 'reason' => 'Please enter a valid promo code']);
		}
		
		return response()->json(['status' => 'success', 'message' => trans('main.modal-booking.промокод-применен'), 'uuid' => $promocode->uuid]);
	}
	
	/**
	 * @param null $cityAlias
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function about($cityAlias = null)
	{
		if ($cityAlias && !in_array($cityAlias, City::ALIASES)) {
			abort(404);
		}
		
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$flightSimulators = FlightSimulator::where('is_active', true)
			->get();
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'about-simulator_' . $city->alias);
		
		return view('about', [
			'flightSimulators' => $flightSimulators,
			'page' => $page ?? new Content,
			'city' => $city,
			'cityAlias' => $cityAlias,
		]);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function giftFlight()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);

		$page = HelpFunctions::getEntityByAlias(Content::class, 'gift-sertificates_' . $city->alias);
		
		return view('gift-flight', [
			'page' => $page ?? new Content,
			'city' => $city,
			'cityAlias' => $cityAlias,
		]);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function flightTypes()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);

		$page = HelpFunctions::getEntityByAlias(Content::class, 'flight-options_' . $city->alias);
		
		return view('flight-types', [
			'page' => $page ?? new Content,
			'city' => $city,
			'cityAlias' => $cityAlias,
		]);
	}

	/**
	 * @param null $simulator
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function instruction($simulator = null)
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);

		if ($simulator && $simulator == 'boeing-737-ng') {
			$page = HelpFunctions::getEntityByAlias(Content::class, 'instruction-737-ng');

			return view('instruction-737-ng', [
				'page' => $page ?? new Content,
				'city' => $city,
				'cityAlias' => $cityAlias,
			]);
		}

		if ($simulator && $simulator == 'airbus-a320') {
			$page = HelpFunctions::getEntityByAlias(Content::class, 'instruction-a320');

			return view('instruction-a320', [
				'page' => $page ?? new Content,
				'city' => $city,
				'cityAlias' => $cityAlias,
			]);
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'instruction');
		
		return view('instruction', [
			'page' => $page ?? new Content,
			'city' => $city,
			'cityAlias' => $cityAlias,
		]);
	}

	/**
	 * @param null $cityAlias
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function contacts($cityAlias = null)
	{
		if ($cityAlias && !in_array($cityAlias, City::ALIASES)) {
			abort(404);
		}
		
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$locations = Location::where('is_active', true)
			->where('city_id', $city->id)
			->orderByRaw("FIELD(alias, 'afi') DESC")
			->orderByRaw("FIELD(alias, 'veg') DESC")
			->orderBy('name')
			->get();
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'contacts_' . $city->alias);
		
		return view('contacts', [
			'locations' => $locations,
			'page' => $page ?? new Content,
			'city' => $city,
			'cityAlias' => $cityAlias,
		]);
	}
	
	/**
	 * @param null $cityAlias
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function price($cityAlias = null)
	{
		if ($cityAlias && !in_array($cityAlias, City::ALIASES)) {
			abort(404);
		}
		
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$productTypes = ProductType::where('is_active', true)
			->orderBy('name')
			->get();
		
		$cityProducts = $city->products;
		
		$products = [];
		foreach ($productTypes as $productType) {
			$products[mb_strtoupper($productType->alias)] = [];
			
			foreach ($productType->products ?? [] as $product) {
				foreach ($cityProducts ?? [] as $cityProduct) {
					if ($product->id != $cityProduct->id) continue;
					
					$basePrice = $price = $cityProduct->pivot->price;
					if ($cityProduct->pivot->discount) {
						$price = $cityProduct->pivot->discount->is_fixed ? ($basePrice - $cityProduct->pivot->discount->value) : ($basePrice - $basePrice * $cityProduct->pivot->discount->value / 100);
					}
					
					$pivotData = json_decode($cityProduct->pivot->data_json, true);
					
					$products[mb_strtoupper($productType->alias)][$product->alias] = [
						'id' => $product->id,
						'name' => $product->name,
						'public_name' => $product->public_name,
						'alias' => $product->alias,
						'duration' => $product->duration,
						'base_price' => $basePrice,
						'price' => $price,
						'currency' => $cityProduct->pivot->currency ? $cityProduct->pivot->currency->name : '$',
						'is_hit' => (bool)$cityProduct->pivot->is_hit,
						'icon_file_path' => (is_array($product->data_json) && array_key_exists('icon_file_path', $product->data_json)) ? $product->data_json['icon_file_path'] : '',
					];
				}
			}
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'prices_' . $city->alias);
		
		return view('price', [
			'productTypes' => $productTypes,
			'products' => $products,
			'page' => $page ?? new Content,
			'city' => $city,
			'cityAlias' => $cityAlias,
		]);
	}
	
	public function getCityListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$cityAlias = $this->request->session()->get('cityAlias', City::DC_ALIAS);
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias);

		$cities = City::where('is_active', true)
			->get();

		$VIEW = view('city.list', [
			'cities' => $cities,
			'city' => $city,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function changeCity()
	{
		$cityAlias = $this->request->alias ?? '';
		
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?? City::DC_ALIAS);
		if (!$city) {
			return response()->json(['status' => 'error', 'reason' => 'Город не найден']);
		}
		
		$this->request->session()->put('cityId', $city->id);
		$this->request->session()->put('cityAlias', $city->alias);
		$this->request->session()->put('cityName', $city->name);
		$this->request->session()->put('cityPhone', $city->phone ? $city->phoneFormatted() : '');
		
		return response()->json(['status' => 'success', 'cityAlias' => $city->alias]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function reviewCreate()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$rules = [
			'name' => 'required',
			'body' => 'required|min:3',
		];

		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Имя',
				'body' => 'Текст отзыва',
			]);
		if (!$validator->passes()) {
			$errors = [];
			$validatorErrors = $validator->errors();
			foreach ($rules as $key => $rule) {
				foreach ($validatorErrors->get($key) ?? [] as $error) {
					$errors[$key] = $error;
				}
			}
			return response()->json(['status' => 'error', 'errors' => $errors]);
		}

		$reviewParentContent = HelpFunctions::getEntityByAlias(Content::class, Content::REVIEWS_TYPE);
		if (!$reviewParentContent) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}

		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);

		$name = trim(strip_tags($this->request->name));
		$body = trim(strip_tags($this->request->body));

		$content = new Content();
		$content->title = $name ?? '';
		$content->alias = (string)\Webpatser\Uuid\Uuid::generate();
		$content->preview_text = $body ?? '';
		$content->parent_id = $reviewParentContent->id;
		$content->city_id = $city->id;
		$content->meta_title = 'Review by ' . $name . ' from ' . $city->name . ' | ' . Carbon::now()->format('m/d/Y');
		$content->meta_description = 'Review by ' . $name . ' from ' . $city->name . ' | ' . Carbon::now()->format('m/d/Y');
		$content->is_active = 0;
		if (!$content->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		//dispatch(new \App\Jobs\SendReviewEmail($name, $body));
		$job = new \App\Jobs\SendReviewEmail($name, $body);
		$job->handle();
		
		return response()->json(['status' => 'success']);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function oferta()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'oferta');
		
		return view('oferta', [
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function rules()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'rules_' . $city->alias);
		
		return view('rules', [
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function howToPay()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'how-to-pay');
		
		return view('how-to-pay', [
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}

	public function privateEvents()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'private-events_' . $city->alias);
		
		return view('private-events', [
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}
	
	public function flightBriefing()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'flight-briefing_' . $city->alias);
		
		return view('flight-briefing', [
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}

	/**
	 * @param $locationId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getSchemeModal($locationId)
	{
		$location = Location::find($locationId);
		
		$VIEW = view('modal.scheme', [
			'location' => $location,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $alias
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getInfoModal($alias)
	{
		$VIEW = view('modal.info', [
			'alias' => $alias,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $alias
	 * @param null $newsAlias
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function getNews($alias, $newsAlias = null)
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		if ($newsAlias) {
			$news = Content::where('alias', $newsAlias)
				->where('is_active', true)
				->whereIn('city_id', [$city->id, 0])
				->where('published_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
				->first();
			
			if (!$news) {
				abort(404);
			}
			
			return view('news-detail', [
				'news' => $news,
				'city' => $city,
				'cityAlias' => $cityAlias,
			]);
		} else {
			$parentNews = HelpFunctions::getEntityByAlias(Content::class, 'news');
		
			$news = Content::where('parent_id', $parentNews->id)
				->where('is_active', true)
				->whereIn('city_id', [$city->id, 0])
				->where('published_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
				->orderByDesc('published_at')
				->get();
			
			$page = HelpFunctions::getEntityByAlias(Content::class, 'news');
			
			return view('news-list', [
				'news' => $news,
				'city' => $city,
				'cityAlias' => $cityAlias,
				'page' => $page ?? new Content,
			]);
		}
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function setRating()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$contentId = $this->request->content_id ?? 0;
		$value = $this->request->value ?? 0;
		
		if (!$contentId || !$value) {
			return response()->json(['status' => 'error']);
		}
		
		$content = Content::find($contentId);
		if (!$content) {
			return response()->json(['status' => 'error']);
		}
		
		$ips = $content->rating_ips ?? [];
		if (in_array($_SERVER['REMOTE_ADDR'], $ips)) {
			return response()->json(['status' => 'error']);
		}
		
		$ratingValue = $content->rating_value;
		$ratingCount = $content->rating_count;
		$ips[] = $_SERVER['REMOTE_ADDR'];

		$content->rating_value = round(($ratingValue * $ratingCount + $value) / ($ratingCount + 1), 1);
		$content->rating_count = $ratingCount + 1;
		$content->rating_ips = $ips;
		if (!$content->save()) {
			return response()->json(['status' => 'error']);
		}
		
		return response()->json(['status' => 'success', 'rating_value' => $content->rating_value, 'rating_count' => $content->rating_count]);
	}
	
	/**
	 * @param null $alias
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function getPromos($alias = null)
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$date = date('Y-m-d');
		
		if ($alias) {
			$promo = Promo::where('alias', $alias)
				->where('is_active', true)
				->where('is_published', true)
				->whereIn('city_id', [$city->id, 0])
				->where(function ($query) use ($date) {
					$query->where('active_from_at', '<=', $date)
						->orWhereNull('active_from_at');
				})
				->where(function ($query) use ($date) {
					$query->where('active_to_at', '>=', $date)
						->orWhereNull('active_to_at');
				})
				->first();
			
			if (!$promo) {
				abort(404);
			}
			
			return view('promos-detail', [
				'promo' => $promo,
				'city' => $city,
				'cityAlias' => $cityAlias,
			]);
		} else {
			$promos = Promo::where('is_active', true)
				->where('is_published', true)
				->whereIn('city_id', [$city->id, 0])
				->where(function ($query) use ($date) {
					$query->where('active_from_at', '<=', $date)
						->orWhereNull('active_from_at');
				})
				->where(function ($query) use ($date) {
					$query->where('active_to_at', '>=', $date)
						->orWhereNull('active_to_at');
				})
				->latest()
				->get();
			
			$page = HelpFunctions::getEntityByAlias(Content::class, 'promos');
			
			return view('promos-list', [
				'promos' => $promos,
				'city' => $city,
				'cityAlias' => $cityAlias,
				'page' => $page ?? new Content,
			]);
		}
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function getGallery()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);

		$parentGallery = Content::where('alias', 'gallery')
			->where('city_id', $city->id)
			->where('is_active', true)
			->first();
		
		$gallery = Content::where('parent_id', $parentGallery->id)
			->where('is_active', true)
			->whereIn('city_id', [$city->id, 0])
			->where('published_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
			->latest()
			->get();
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'gallery');
		
		return view('gallery', [
			'gallery' => $gallery,
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function getReviews()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$parentReviews = Content::where('alias', 'reviews')
			->where('city_id', $city->id)
			->where('is_active', true)
			->first();
		
		//\DB::connection()->enableQueryLog();
		$reviews = Content::where('parent_id', $parentReviews->id)
			->where('is_active', true)
			->whereIn('city_id', [$city->id, 0])
			->where('published_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
			->latest()
			->get();
		//\Log::debug(\DB::getQueryLog());
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'reviews');
		
		return view('reviews-list', [
			'reviews' => $reviews,
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCallbackModal()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$VIEW = view('modal.callback');
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getReviewModal()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$VIEW = view('modal.review');
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function callback()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$rules = [
			'name' => 'required',
			'phone' => 'required',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => trans('main.modal-booking.имя'),
				'phone' => trans('main.modal-booking.телефон'),
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.проверьте-правильность-заполнения-полей-формы'), 'errors' => $validator->errors()]);
		}
		
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$name = trim(strip_tags($this->request->name));
		$phone = trim(strip_tags($this->request->phone));
		$comment = trim(strip_tags($this->request->comment ?? ''));
		
		$job = new \App\Jobs\SendCallbackEmail($name, $phone, $city, $comment);
		$job->handle();
		
		return response()->json(['status' => 'success']);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function question()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$rules = [
			'name' => 'required',
			'email' => 'required|email',
			'body' => 'required',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => trans('main.form-feedback.имя'),
				'email' => trans('main.form-feedback.email'),
				'body' => trans('main.form-feedback.текст'),
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.проверьте-правильность-заполнения-полей-формы'), 'errors' => $validator->errors()]);
		}
		
		$name = trim(strip_tags($this->request->name));
		$email = trim(strip_tags($this->request->email));
		$body = trim(strip_tags($this->request->body));
		
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		//dispatch(new \App\Jobs\SendQuestionEmail($name, $email, $body));
		$job = new \App\Jobs\SendQuestionEmail($name, $email, $body, $city);
		$job->handle();
		
		return response()->json(['status' => 'success']);
	}
	
	public function privacyPolicy()
	{
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'privacy-policy_' . $city->alias);
		
		return view('privacy-policy', [
			'city' => $city,
			'cityAlias' => $cityAlias,
			'page' => $page ?? new Content,
		]);
	}
}