<?php

namespace App\Http\Controllers;

use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationDate;

use App\Models\Bill;
use App\Models\Certificate;
use App\Models\Content;
use App\Models\Contractor;
use App\Models\Currency;
use App\Models\DealPosition;
use App\Models\Event;
use App\Models\FlightSimulator;
use App\Models\PaymentMethod;
use App\Models\Promo;
use App\Models\Deal;
use App\Models\City;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Promocode;
use App\Models\Status;
use App\Models\User;
use App\Repositories\CityRepository;
use App\Repositories\ProductTypeRepository;
use App\Repositories\PromoRepository;
use App\Repositories\PromocodeRepository;
use App\Repositories\DealPositionRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\DealRepository;
use App\Repositories\StatusRepository;
use App\Services\AuthorizeNetService;
use App\Services\HelpFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;
use Throwable;

class DealController extends Controller
{
	private $request;
	private $cityRepo;
	private $promoRepo;
	private $promocodeRepo;
	private $productTypeRepo;
	private $positionRepo;
	private $dealRepo;
	private $statusRepo;
	private $paymentRepo;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, CityRepository $cityRepo, PromoRepository $promoRepo, PromocodeRepository $promocodeRepo, ProductTypeRepository $productTypeRepo, DealPositionRepository $positionRepo, DealRepository $dealRepo, StatusRepository $statusRepo, PaymentRepository $paymentRepo) {
		$this->request = $request;
		$this->cityRepo = $cityRepo;
		$this->promoRepo = $promoRepo;
		$this->promocodeRepo = $promocodeRepo;
		$this->productTypeRepo = $productTypeRepo;
		$this->positionRepo = $positionRepo;
		$this->dealRepo = $dealRepo;
		$this->statusRepo = $statusRepo;
		$this->paymentRepo = $paymentRepo;
	}
	
	/**
	 * @param null $dealId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index($dealId = null)
	{
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		/*$locationCount = $user->city ? $user->city->locations->count() : 0;

		if ($user->isSuperAdmin() || $locationCount > 1) {
			$cities = City::orderBy('name');
			if ($user->city_id) {
				$cities = $cities->where('id', $user->city_id);
			}
			$cities = $cities->get();
		} else {
			$cities = [];
		}*/
		
		
		$productTypes = ProductType::where('alias', '!=', 'services')
			->orderBy('name')
			->get();
		
		$statuses = Status::whereNotIn('type', [Status::STATUS_TYPE_CONTRACTOR])
			->orderby('type')
			->orderBy('sort')
			->get();
		$statusData = [];
		foreach ($statuses as $status) {
			$statusData[Status::STATUS_TYPES[$status->type]][] = [
				'id' => $status->id,
				'alias' => $status->alias,
				'name' => $status->name,
			];
		}
		
		if ($dealId) {
			$deal = Deal::find($dealId);
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'deals');
		
		return view(	'admin.deal.index', [
			'user' => $user,
			/*'cities' => $cities,*/
			'productTypes' => $productTypes,
			'statusData' => $statusData,
			/*'locationCount' => $locationCount,*/
			'deal' => $deal ?? null,
			'page' => $page,
		]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$city = $user->city;
		
		$id = $this->request->id ?? 0;
		
		$deals = Deal::where('city_id', $city->id)
			->orderBy('id', 'desc');
		if ($this->request->filter_status_id) {
			$deals = $deals->where(function ($query) {
				$query->whereIn('status_id', $this->request->filter_status_id)
					->orWhereRelation('positions', function ($query) {
						return $query->orWhereHas('certificate', function ($query) {
							return $query->whereIn('certificates.status_id', $this->request->filter_status_id);
						});
					})
					->orWhereHas('bills', function ($query) {
						return $query->whereIn('bills.status_id', $this->request->filter_status_id);
					});
			});
		}
		if ($this->request->filter_location_id) {
			$deals = $deals->whereHas('positions', function ($query) {
				return $query->whereIn('location_id', $this->request->filter_location_id);
			});
		}
		if ($this->request->filter_product_id) {
			$deals = $deals->whereHas('positions', function ($query) {
				return $query->whereIn('product_id', $this->request->filter_product_id);
			});
		}
		if ($this->request->filter_advanced) {
			if (in_array('with_promo', $this->request->filter_advanced)) {
				$deals = $deals->whereHas('positions', function ($query) {
					return $query->has('promo');
				});
			}
			if (in_array('with_promocode', $this->request->filter_advanced)) {
				$deals = $deals->whereHas('positions', function ($query) {
					return $query->has('promocode');
				});
			}
		}
		if ($this->request->search_doc) {
			$deals = $deals->where(function ($query) {
				$query->where('number', 'like', '%' . $this->request->search_doc . '%')
					->orWhere('uuid', $this->request->search_doc)
					->orWhere('name', 'like', '%' . $this->request->search_doc . '%')
					->orWhere('email', 'like', '%' . $this->request->search_doc . '%')
					->orWhere('phone', 'like', '%' . $this->request->search_doc . '%')
					->orWhere('id', $this->request->search_doc)
					->orWhereRelation('positions', function ($query) {
						return $query->where('number', 'like', '%' . $this->request->search_doc . '%')
							->orWhereHas('certificate', function ($query) {
								return $query->where('certificates.number', 'like', '%' . $this->request->search_doc . '%');
							});
					})
					->orWhereHas('bills', function ($q) {
						return $q->where('bills.number', 'like', '%' . $this->request->search_doc . '%');
					})
					->orWhereHas('contractor', function ($query) {
						return $query->where('name', 'like', '%' . $this->request->search_doc . '%')
							->orWhere('lastname', 'like', '%' . $this->request->search_doc . '%')
							->orWhere('email', 'like', '%' . $this->request->search_doc . '%')
							->orWhere('phone', 'like', '%' . $this->request->search_doc . '%')
							->orWhere('id', $this->request->search_doc);
					});
			});
		}
		/*if (!$user->isSuperAdmin() && $user->city) {
			$deals = $deals->where('city_id', $user->city->id);
		}*/
		if ($id) {
			$deals = $deals->where('id', '<', $id);
		}
		$deals = $deals->limit(20)->get();

		$data = [
			'deals' => $deals,
			'user' => $user,
		];
		
		$VIEW = view('admin.deal.list', $data);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addCertificate()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$cities = $this->cityRepo->getList($user);
		$products = $this->productTypeRepo->getActualProductList($user, true);
		$promos = $this->promoRepo->getList($user, true, true);
		$promocodes = $this->promocodeRepo->getList($user);
		$paymentMethods = $this->paymentRepo->getPaymentMethodList();
		
		$VIEW = view('admin.deal.modal.certificate.add', [
			'cities' => $cities,
			'products' => $products,
			'promos' => $promos,
			'promocodes' => $promocodes,
			'paymentMethods' => $paymentMethods,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addBooking()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$cities = $this->cityRepo->getList($user);
		$products = $this->productTypeRepo->getActualProductList($user, true);
		$promos = $this->promoRepo->getList($user, true, true);
		$promocodes = $this->promocodeRepo->getList($user);
		$paymentMethods = $this->paymentRepo->getPaymentMethodList();
		$employees = User::where('enable', true)
			->orderBy('lastname')
			->orderBy('name')
			->get();
		$pilots = User::where('enable', true);
		if ($user->city_id) {
			$pilots = $pilots->whereIn('city_id', [$user->city_id, 0]);
		}
		$pilots = $pilots->where('role', User::ROLE_PILOT)
			->orderBy('lastname')
			->orderBy('name')
			->get();

		$VIEW = view('admin.deal.modal.booking.add', [
			'cities' => $cities,
			'products' => $products,
			'promos' => $promos,
			'promocodes' => $promocodes,
			'paymentMethods' => $paymentMethods,
			'source' => $this->request->source ?? '',
			'flightAt' => $this->request->flight_at ?? '',
			'user' => $user,
			'locationId' => $this->request->location_id ?? 0,
			'simulatorId' => $this->request->simulator_id ?? 0,
			'employees' => $employees,
			'pilots' => $pilots,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addProduct()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$cities = $this->cityRepo->getList($user);
		$products = $this->productTypeRepo->getActualProductList($user, true, false, true);
		$promos = $this->promoRepo->getList($user, true, true);
		$promocodes = $this->promocodeRepo->getList($user);
		$paymentMethods = $this->paymentRepo->getPaymentMethodList();

		$VIEW = view('admin.deal.modal.product.add', [
			'cities' => $cities,
			'products' => $products,
			'promos' => $promos,
			'promocodes' => $promocodes,
			'paymentMethods' => $paymentMethods,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
	 */
	public function edit($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$deal = $this->dealRepo->getById($id);
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		$statuses = $this->statusRepo->getList(Status::STATUS_TYPE_DEAL);
		
		$VIEW = view('admin.deal.modal.edit', [
			'deal' => $deal,
			'statuses' => $statuses,
			'user' => $user,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function storeCertificate()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if ($user) {
			if (!$user->isAdminOrHigher()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
			}
		}
		
		if ($this->request->source == Deal::WEB_SOURCE) {
			$rules = [
				'product_id' => ['required', 'numeric', 'min:0|not_in:0'],
				'name' => ['required'],
				'email' => ['required', 'email'],
				'phone' => ['required'],
				'card_number' => ['required', new CardNumber],
				'expiration_date' => ['required', new CardExpirationDate('mY')],
				'card_name' => ['required'],
				'card_code' => ['required', new CardCvc($this->request->card_number)],
			];
			
			$validator = Validator::make($this->request->all(), $rules)
				->setAttributeNames([
					'product_id' => 'Flight option',
					'name' => 'Name',
					'email' => 'E-mail',
					'phone' => 'Phone number',
					'card_number' => 'Card number',
					'expiration_date' => 'Expiration date',
					'card_name' => 'Full name',
					'card_code' => 'CVC',
				]);
			if (!$validator->passes()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.проверьте-правильность-заполнения-полей-формы'), 'errors' => $validator->errors()]);
			}
		} else {
			$rules = [
				'name' => 'required',
				'email' => 'required|email',
				'phone' => 'required|valid_phone',
				'product_id' => 'required|numeric|min:0|not_in:0',
			];
			
			$validator = Validator::make($this->request->all(), $rules)
				->setAttributeNames([
					'name' => 'Name',
					'email' => 'E-mail',
					'phone' => 'Phone',
					'product_id' => 'Product',
				]);
			if (!$validator->passes()) {
				return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
			}
		}
		
		$cityId = $this->request->city_id;
		$productId = $this->request->product_id ?? 0;
		$promoId = $this->request->promo_id ?? 0;
		$promocodeId = $this->request->promocode_id ?? 0;
		$promocodeUuid = $this->request->promocode_uuid ?? '';
		$comment = $this->request->comment ?? '';
		$certificateExpireAt = $this->request->certificate_expire_at ?? null;
		$contractorId = $this->request->contractor_id ?? 0;
		$name = $this->request->name ?? '';
		$email = $this->request->email ?? '';
		$phone = $this->request->phone ?? '';
		$source = $this->request->source ?? '';
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		$isPaid = (bool)$this->request->is_paid;
		$cardNumber = $this->request->card_number ?? '';
		$expirationDate = $this->request->expiration_date ?? '';
		if ($expirationDate) {
			$expirationDate = mb_substr($expirationDate, 2, 4) . '-' . mb_substr($expirationDate, 0, 2);
		}
		$cardCode = $this->request->card_code ?? '';
		$birthday = $this->request->birthday ?? 0;
		$weekends = $this->request->weekends ?? 0;
		
		$product = Product::find($productId);
		if ($weekends) {
			$regularUltimateProductTypes = ProductType::whereIn('alias', [ProductType::REGULAR_ALIAS, ProductType::ULTIMATE_ALIAS])
				->pluck('id')
				->toArray();
			$product = Product::where('duration', $product->duration)
				->where('id', '!=', $product->id)
				->whereIn('product_type_id', $regularUltimateProductTypes)
				->first();
		}
		if (!$product) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$productType = $product->productType;
		if (!$productType) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$city = null;
		if ($cityId) {
			$city = City::find($cityId);
			if (!$city) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
			}
		}
		
		$cityProduct = $product->cities()->where('cities_products.is_active', true)->find($cityId ?: 1);
		if (!$cityProduct) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		if ($promoId) {
			$promo = Promo::find($promoId);
			if (!$promo) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.акция-не-найдена')]);
			}
		}
		
		if ($birthday) {
			$promo = HelpFunctions::getEntityByAlias(Promo::class, Promo::BIRTHDAY_ALIAS);
			$promoId = $promo->id;
		}
		
		if ($promocodeId) {
			$promocode = Promocode::find($promocodeId);
			if (!$promocode) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
			}
		}
		
		if ($promocodeUuid) {
			$promocode = HelpFunctions::getEntityByUuid(Promocode::class, $promocodeUuid);
			if (!$promocode) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
			}
		}
		
		if ($paymentMethodId) {
			$paymentMethod = PaymentMethod::where('is_active', true)
				->find($paymentMethodId);
			if (!$paymentMethod) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
			}
		}
		
		$data = [];
		if ($comment) {
			$data['comment'] = $comment;
		}
		
		if ($contractorId) {
			$contractor = Contractor::find($contractorId);
			if (!$contractor) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);
			}
		} elseif ($contractorEmail = $this->request->email ?? '') {
			$contractor = Contractor::whereRaw('LOWER(email) = (?)', [mb_strtolower($contractorEmail)])
				->first();
			if ($contractor && !in_array($source, [Contractor::WEB_SOURCE])) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-с-таким-e-mail-уже-существует')]);
			}
		}
		
		$amount = $product->calcAmount($contractorId, $cityId, $source, false, 0, $paymentMethodId, $promoId, $promocode->id ?? 0, 0, false, false, 0, true);
		$tax = round($amount * $productType->tax / 100, 2);
		$totalAmount = round($amount + $tax, 2);
		$currency = HelpFunctions::getEntityByAlias(Currency::class, Currency::USD_ALIAS);
		$certificatePeriod = ($productType->alias == ProductType::COURSES_ALIAS) ? 12 : 6;
		
		try {
			\DB::beginTransaction();

			if (!$contractor) {
				$contractor = new Contractor();
				$contractor->name = $name;
				$contractor->email = $email;
				$contractor->phone = $phone;
				$contractor->city_id = $cityId ?: $this->request->user()->city_id;
				$contractor->source = $source ?: Contractor::ADMIN_SOURCE;
				$contractor->user_id = $this->request->user()->id ?? 0;
				$contractor->save();
			}

			$certificate = new Certificate();
			$certificateStatus = HelpFunctions::getEntityByAlias(Status::class, Certificate::CREATED_STATUS);
			$certificate->status_id = $certificateStatus->id ?? 0;
			$certificate->city_id = $cityId ?? 0;
			$certificate->product_id = $product->id ?? 0;
			$certificate->expire_at = Carbon::parse($certificateExpireAt)->addMonths($certificatePeriod)->format('Y-m-d H:i:s');
			$certificate->save();
			
			$deal = new Deal();
			$dealStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::CONFIRMED_STATUS);
			$deal->status_id = $dealStatus->id ?? 0;
			$deal->contractor_id = $contractor->id ?? 0;
			$deal->city_id = $cityId ?: $this->request->user()->city_id;
			$deal->name = $name;
			$deal->phone = $phone;
			$deal->email = $email;
			$deal->source = $source ?: Deal::ADMIN_SOURCE;
			$deal->user_id = $this->request->user()->id ?? 0;
			$deal->save();
			
			$position = new DealPosition();
			$position->product_id = $product->id ?? 0;
			$position->certificate_id = $certificate->id ?? 0;
			$position->duration = $product->duration ?? 0;
			$position->amount = $amount;
			$position->tax = $tax;
			$position->total_amount = $totalAmount;
			$position->currency_id = $currency->id ?? 0;
			$position->city_id = $cityId ?: $this->request->user()->city_id;
			$position->promo_id = $promo->id ?? 0;
			$position->promocode_id = ($promocodeId || $promocodeUuid) ? $promocode->id : 0;
			$position->is_certificate_purchase = true;
			$position->source = $source ?: Deal::ADMIN_SOURCE;
			$position->user_id = $this->request->user()->id ?? 0;
			$position->data_json = !empty($data) ? $data : null;
			$position->save();

			$deal->positions()->save($position);
			
			if ($amount) {
				$onlinePaymentMethod = HelpFunctions::getEntityByAlias(PaymentMethod::class, Bill::ONLINE_PAYMENT_METHOD);
				$billStatus = HelpFunctions::getEntityByAlias(Status::class, Bill::NOT_PAYED_STATUS);
				$billPayedStatus = HelpFunctions::getEntityByAlias(Status::class, Bill::PAYED_STATUS);
				
				if ($source == Deal::WEB_SOURCE) {
					$billLocation = $city->getLocationForBill($product);
					$billLocationId = $billLocation->id;
				} else {
					$billLocationId = $user ? $user->location_id : 0;
				}

				$bill = new Bill();
				$bill->contractor_id = $contractor->id ?? 0;
				$bill->deal_id = $deal->id ?? 0;
				$bill->deal_position_id = $position->id ?? 0;
				$bill->location_id = $billLocationId ?? 0;
				$bill->payment_method_id = ($source == Deal::WEB_SOURCE) ? $onlinePaymentMethod->id : ($paymentMethodId ?? 0);
				$bill->status_id = ($isPaid && $paymentMethodId != $onlinePaymentMethod->id) ? $billPayedStatus->id : $billStatus->id;
				$bill->payed_at = ($isPaid && $paymentMethodId != $onlinePaymentMethod->id) ? Carbon::now()->format('Y-m-d H:i:s') : null;
				$bill->amount = $amount;
				$bill->tax = $tax;
				$bill->total_amount = $totalAmount;
				$bill->currency_id = $currency->id ?? 0;
				$bill->city_id = $cityId ?: $this->request->user()->city_id;
				$bill->user_id = $this->request->user()->id ?? 0;
				$bill->save();
				
				$deal->bills()->save($bill);
			}

			if ($promocodeId || $promocodeUuid) {
				$promocode->contractors()->save($contractor);
			}
			
			$paymentResponse = null;
			if (in_array($source, [Deal::WEB_SOURCE])) {
				$paymentResponse = AuthorizeNetService::payment($bill, $cardNumber, $expirationDate, $cardCode, $deal->email, $product->name);

				Log::channel('authorize')->info($paymentResponse);

				if ($paymentResponse['status'] == 'error') {
					\DB::rollback();

					return response()->json(['status' => 'error', 'reason' => $paymentResponse['original']['error_code'] . ': ' . $paymentResponse['original']['error_message']]);
				}
				
				$billData = [
					'payment' => [
						'status' => $paymentResponse['status'],
						'transaction_id' => $paymentResponse['transaction_id'],
						'transaction_code' => $paymentResponse['transaction_code'],
						'message_code' => $paymentResponse['message_code'],
						'auth_code' => $paymentResponse['auth_code'],
						'description' => $paymentResponse['description'],
					],
				];
				
				$bill->status_id = $billPayedStatus->id ?? 0;
				$bill->payed_at = Carbon::now();
				$bill->data_json = $billData;
				$bill->save();
				
				$certificate = $certificate->generateFile();
			}
			
			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();
			
			Log::debug('500 - Deal Certificate Store: ' . $e->getMessage());
			
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже'), 'error_code' => $e->getMessage()]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Your Voucher # <b>' . $certificate->number . '</b> has been successfully submitted.<br>Soon you will receive your Voucher by E-mail', 'paymentResponse' => $paymentResponse]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function storeBooking()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		$city = $user->city;
		
		if ($user) {
			if (!$user->isAdminOrHigher()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
			}
		}
		
		/*if ($this->request->source == Deal::WEB_SOURCE) {
			$rules = [
				'name' => 'required',
				'email' => 'required|email',
				'phone' => 'required',
				'product_id' => 'required',
				'location_id' => 'required',
				'flight_date_at' => 'required',
			];
			
			$validator = Validator::make($this->request->all(), $rules)
				->setAttributeNames([
					'name' => trans('main.modal-booking.имя'),
					'email' => trans('main.modal-booking.email'),
					'phone' => trans('main.modal-booking.телефон'),
					'product_id' => trans('main.modal-booking.выберите-продолжительность-полета'),
					'location_id' => trans('main.modal-booking.локация'),
					'flight_date_at' => trans('main.modal-booking.дата-полета'),
				]);
			if (!$validator->passes()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.проверьте-правильность-заполнения-полей-формы'), 'errors' => $validator->errors()]);
			}
		} else {*/
			switch ($this->request->event_type) {
				case Event::EVENT_TYPE_DEAL:
					$rules = [
						'name' => 'required',
						'email' => 'required|email',
						'phone' => 'required',
						'product_id' => 'required|numeric|min:0|not_in:0',
						/*'location_id' => 'required|numeric|min:0|not_in:0',*/
						'flight_date_at' => 'required|date',
						'flight_time_at' => 'required',
					];
					
					$validator = Validator::make($this->request->all(), $rules)
						->setAttributeNames([
							'name' => trans('main.modal-booking.имя'),
							'email' => trans('main.modal-booking.email'),
							'phone' => trans('main.modal-booking.телефон'),
							'product_id' => trans('main.modal-booking.выберите-продолжительность-полета'),
							/*'location_id' => trans('main.modal-booking.локация'),*/
							'flight_date_at' => 'Start date',
							'flight_time_at' => 'Start time',
						]);
				break;
				case Event::EVENT_TYPE_BREAK:
				case Event::EVENT_TYPE_CLEANING:
					$rules = [
						/*'location_id' => 'required|numeric|min:0|not_in:0',*/
						'flight_date_at' => 'required|date',
						'flight_time_at' => 'required',
						'flight_date_stop_at' => 'required|date',
						'flight_time_stop_at' => 'required',
					];
					
					$validator = Validator::make($this->request->all(), $rules)
						->setAttributeNames([
							/*'location_id' => trans('main.modal-booking.локация'),*/
							'flight_date_at' => 'Start date',
							'flight_time_at' => 'Start time',
							'flight_date_stop_at' => 'End date',
							'flight_time_stop_at' => 'End time',
						]);
				break;
				case Event::EVENT_TYPE_USER_FLIGHT:
					$rules = [
						/*'location_id' => 'required|numeric|min:0|not_in:0',*/
						'flight_date_at' => 'required|date',
						'flight_time_at' => 'required',
						'flight_date_stop_at' => 'required|date',
						'flight_time_stop_at' => 'required',
						'employee_id' => 'required|numeric|min:0|not_in:0',
					];
					
					$validator = Validator::make($this->request->all(), $rules)
						->setAttributeNames([
							/*'location_id' => trans('main.modal-booking.локация'),*/
							'flight_date_at' => 'Start date',
							'flight_time_at' => 'Start time',
							'flight_date_stop_at' => 'End date',
							'flight_time_stop_at' => 'End time',
							'employee_id' => 'Employee',
						]);
				break;
				case Event::EVENT_TYPE_TEST_FLIGHT:
					$rules = [
						/*'location_id' => 'required|numeric|min:0|not_in:0',*/
						'flight_date_at' => 'required|date',
						'flight_time_at' => 'required',
						'flight_date_stop_at' => 'required|date',
						'flight_time_stop_at' => 'required',
						'pilot_id' => 'required|numeric|min:0|not_in:0',
					];
					
					$validator = Validator::make($this->request->all(), $rules)
						->setAttributeNames([
							/*'location_id' => trans('main.modal-booking.локация'),*/
							'flight_date_at' => 'Start date',
							'flight_time_at' => 'Start time',
							'flight_date_stop_at' => 'End date',
							'flight_time_stop_at' => 'End time',
							'pilot_id' => 'Pilot',
						]);
				break;
			}
			if (!$validator->passes()) {
				return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
			}
		/*}*/
		
		/*$cityId = $this->request->city_id ?: $this->request->user()->city_id;*/
		$productId = $this->request->product_id ?? 0;
		$promoId = $this->request->promo_id ?? 0;
		$promocodeId = $this->request->promocode_id ?? 0;
		$promocodeUuid = $this->request->promocode_uuid ?? '';
		$comment = $this->request->comment ?? '';
		$amount = $this->request->amount ?? 0;
		$contractorId = $this->request->contractor_id ?? 0;
		$name = $this->request->name ?? '';
		$email = $this->request->email ?? '';
		$phone = $this->request->phone ?? '';
		$source = $this->request->source ?? '';
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		$flightAt = ($this->request->flight_date_at ?? '') . ' ' . ($this->request->flight_time_at ?? '');
		$flightAt = str_replace('/', '.', $flightAt);
		$flightAt = str_replace(',', '', $flightAt);
		$flightAt = str_replace(' AM', '', $flightAt);
		$flightAt = str_replace(' PM', '', $flightAt);
		$flightStopAt = ($this->request->flight_date_stop_at ?? '') . ' ' . ($this->request->flight_time_stop_at ?? '');
		/*$locationId = $this->request->location_id ?? 0;
		$simulatorId = $this->request->flight_simulator_id ?? 0;*/
		$certificateNumber = $this->request->certificate ?? '';
		$certificateUuid = $this->request->certificate_uuid ?? '';
		$isIndefinitely = $this->request->is_indefinitely ?? 0;
		$eventType = $this->request->event_type ?? '';
		$extraTime = (int)$this->request->extra_time ?? 0;
		$isRepeatedFlight = (bool)$this->request->is_repeated_flight ?? false;
		$isUnexpectedFlight = (bool)$this->request->is_unexpected_flight ?? false;
		/*$duration = $this->request->duration ?? 0;
		$isValidFlightDate = $this->request->is_valid_flight_date ?? 0;*/
		$employeeId = $this->request->employee_id ?? 0;
		$pilotId = $this->request->pilot_id ?? 0;
		$isPaid = (bool)$this->request->is_paid;
		
		/*if (!in_array($source, [Deal::WEB_SOURCE, Deal::MOB_SOURCE]) && in_array($eventType, Event::EVENT_TYPE_DEAL) && !$isValidFlightDate) {
			return response()->json(['status' => 'error', 'reason' => 'Некорректная дата и время начала полета']);
		}*/
		
		if (in_array($eventType, [Event::EVENT_TYPE_DEAL])) {
			$product = Product::find($productId);
			if (!$product) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
			}
			
			$productType = $product->productType;
			if (!$productType) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
			}
			
			if ($source != Deal::WEB_SOURCE && !$product->validateFlightDate($flightAt)) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.для-бронирования-полета-по-тарифу-regular-доступны-только-будние-дни')]);
			}
		}
		
		$location = Location::where('city_id', $city->id)
			->first();
		if (!$location) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.локация-не-найдена')]);
		}
		
		/*if (!$cityId) {
			$cityId = $location->city->id ?? 0;
		}

		$city = $this->cityRepo->getById($cityId);
		if (!$city) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		}*/
		
		if (in_array($eventType, [Event::EVENT_TYPE_DEAL])) {
			$cityProduct = $product->cities()->where('cities_products.is_active', true)->find($city->id);
			if (!$cityProduct) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
			}
		}
		
		$simulator = $location->simulators()->first();
		if (!$simulator) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.авиатренажер-не-найден')]);
		}

		if ($promoId) {
			$promo = Promo::find($promoId);
			if (!$promo) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.акция-не-найдена')]);
			}
		}
		
		if ($promocodeId) {
			$promocode = Promocode::find($promocodeId);
			if (!$promocode) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
			}
		}

		if ($promocodeUuid) {
			$promocode = HelpFunctions::getEntityByUuid(Promocode::class, $promocodeUuid);
			if (!$promocode) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
			}
		}
		
		if ($paymentMethodId) {
			$paymentMethod = PaymentMethod::where('is_active', true)
				->find($paymentMethodId);
			if (!$paymentMethod) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
			}
		}
		
		$certificateId = $certificateProductAmount = 0;
		if ($certificateNumber || $certificateUuid) {
			$date = date('Y-m-d');
			$certificateStatus = HelpFunctions::getEntityByAlias(Status::class, Certificate::CREATED_STATUS);
			
			// проверка сертификата на валидность
			if ($certificateNumber) {
				$certificate = Certificate::whereIn('city_id', [$city->id, 0])
					->where('number', $certificateNumber)
					->first();
			} elseif ($certificateUuid) {
				$certificate = HelpFunctions::getEntityByUuid(Certificate::class, $certificateUuid);
			}
			if (!$certificate) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.сертификат-не-найден')]);
			}
			/*if ($certificate->wasUsed()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.сертификат-уже-был-ранее-использован')]);
			}*/
			if (in_array($source, [Deal::WEB_SOURCE, Deal::MOB_SOURCE]) && !in_array($certificate->status_id, [$certificateStatus->id, 0])) {
				return response()->json(['status' => 'error', 'reason' => trans('main.Incorrect Certificate Status')]);
			}
			if (!in_array($certificate->product_id, [$product->id, 0]) && in_array($source, [Deal::WEB_SOURCE, Deal::MOB_SOURCE])) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-по-сертификату-не-совпадает-с-выбранным')]);
			}
			if ($certificate->expire_at && Carbon::parse($certificate->expire_at)->lt($date) && !$isIndefinitely) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.срок-действия-сертификата-истек')]);
			}
			$certificateId = $certificate->id;
			/*$certificateProduct = $certificate->product;
			if ($certificateProduct && $certificateProduct->alias != $product->alias) {
				$certificateCityProduct = $certificateProduct->cities()->where('cities_products.is_active', true)->find($cityId);
				if ($certificateCityProduct && $certificateCityProduct->pivot) {
					$certificateProductAmount = $certificateCityProduct->pivot->price;
				}
			}*/
		}
		
		if ($contractorId) {
			$contractor = Contractor::find($contractorId);
			if (!$contractor) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);
			}
		} elseif ($email) {
			$contractor = Contractor::whereRaw('LOWER(email) = ?', [mb_strtolower($email)])
				->first();
			if ($contractor && !in_array($source, [Contractor::WEB_SOURCE])) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-с-таким-e-mail-уже-существует')]);
			}
		}

		$data = [];
		if ($comment) {
			$data['comment'] = $comment;
		}
		
		$tax = $totalAmount = 0;
		if ($amount) {
			$tax = round($amount * $productType->tax / 100, 2);
			$totalAmount = round($amount + $tax, 2);
		}
		$currency = HelpFunctions::getEntityByAlias(Currency::class, Currency::USD_ALIAS);
		
		try {
			\DB::beginTransaction();

			switch ($eventType) {
				case Event::EVENT_TYPE_DEAL:
					if (!$contractor) {
						$contractor = new Contractor();
						$contractor->name = $name;
						$contractor->email = $email;
						$contractor->phone = $phone;
						$contractor->city_id = $city ? $city->id : 0;
						$contractor->source = $source ?: Contractor::ADMIN_SOURCE;
						$contractor->user_id = $user ? $user->id : 0;
						$contractor->save();
					}
					
					$deal = new Deal();
					$dealStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::CREATED_STATUS);
					$deal->status_id = $dealStatus->id;
					$deal->contractor_id = $contractor->id;
					$deal->city_id = $city ? $city->id : 0;
					$deal->name = $name;
					$deal->phone = $phone;
					$deal->email = $email;
					$deal->source = $source ?: Deal::ADMIN_SOURCE;
					$deal->user_id = $user ? $user->id : 0;
					$deal->save();
					
					$position = new DealPosition();
					$position->product_id = $product->id;
					$position->certificate_id = $certificateId;
					$position->duration = $product->duration;
					$position->amount = $amount;
					$position->tax = $tax;
					$position->total_amount = $totalAmount;
					$position->currency_id = $currency ? $currency->id : 0;
					$position->city_id = $city ? $city->id : 0;
					$position->location_id = $location->id;
					$position->flight_simulator_id = $simulator->id;
					$position->promo_id = $promo->id ?? 0;
					$position->promocode_id = ($promocodeId || $promocodeUuid) ? $promocode->id : 0;
					$position->flight_at = Carbon::parse($flightAt)->format('Y-m-d H:i');
					$position->source = $source ?: Deal::ADMIN_SOURCE;
					$position->user_id = $user ? $user->id : 0;
					$position->data_json = !empty($data) ? $data : null;
					$position->save();
				
					$deal->positions()->save($position);
				
					if ($amount) {
						$onlinePaymentMethod = HelpFunctions::getEntityByAlias(PaymentMethod::class, Bill::ONLINE_PAYMENT_METHOD);
						$billStatus = HelpFunctions::getEntityByAlias(Status::class, Bill::NOT_PAYED_STATUS);
						$billPayedStatus = HelpFunctions::getEntityByAlias(Status::class, Bill::PAYED_STATUS);
						
						if ($source == Deal::WEB_SOURCE) {
							$billLocationId = $location ? $location->id : 0;
						} else {
							$billLocationId = $user ? $user->location_id : 0;
						}
						
						$bill = new Bill();
						$bill->contractor_id = $contractor->id;
						$bill->deal_id = $deal->id;
						$bill->deal_position_id = $position->id;
						$bill->location_id = $billLocationId;
						$bill->payment_method_id = ($source == Deal::WEB_SOURCE) ? 0 : ($paymentMethodId ?? 0);
						$bill->status_id = ($isPaid && $paymentMethodId != $onlinePaymentMethod->id) ? $billPayedStatus->id : $billStatus->id;
						$bill->payed_at = ($isPaid && $paymentMethodId != $onlinePaymentMethod->id) ? Carbon::now()->format('Y-m-d H:i:s') : null;
						$bill->amount = $amount;
						$bill->tax = $tax;
						$bill->total_amount = $totalAmount;
						$bill->currency_id = $currency ? $currency->id : 0;
						$bill->user_id = $user ? $user->id : 0;
						$bill->save();
						
						$deal->bills()->save($bill);
					}

					// если сделка на бронирование по сертификату, то регистрируем сертификат
					if ($certificateId && $certificate) {
						$certificateStatus = HelpFunctions::getEntityByAlias(Status::class, Certificate::REGISTERED_STATUS);
						$certificate->status_id = $certificateStatus->id ?? 0;
						$certificate->save();
					}
				
					// если сделка создается из календаря, создаем сразу и событие
					if ($source == 'calendar') {
						// создаем новую карточку контрагента, если E-mail из заявки не совпадает E-mail
						// из карточки клиента, и пишем уже этого клиента в событие
						if ($email && $email != $contractor->email && $email != Contractor::ANONYM_EMAIL) {
							$contractor = new Contractor();
							$contractor->name = $name;
							$contractor->email = $email;
							$contractor->phone = $phone;
							$contractor->city_id = $city ? $city->id : 0;
							$contractor->source = $source ?: Contractor::ADMIN_SOURCE;
							$contractor->user_id = $user ? $user->id : 0;
							$contractor->save();
						}
						
						$event = new Event();
						$event->event_type = $eventType;
						$event->contractor_id = $contractor->id ?? 0;
						$event->deal_id = $deal->id ?? 0;
						$event->deal_position_id = $position->id ?? 0;
						$event->city_id = $city ? $city->id : 0;
						$event->location_id = $location->id ?? 0;
						$event->flight_simulator_id = $simulator->id ?? 0;
						$event->start_at = Carbon::parse($flightAt)->format('Y-m-d H:i');
						$event->stop_at = Carbon::parse($flightAt)->addMinutes($product->duration ?? 0)->format('Y-m-d H:i');
						$event->extra_time = $extraTime;
						$event->is_repeated_flight = $isRepeatedFlight;
						$event->is_unexpected_flight = $isUnexpectedFlight;
						$event->save();
						
						$position->event()->save($event);
					}

					if ($promocodeId || $promocodeUuid) {
						$promocode->contractors()->save($contractor);
					}
				break;
				case Event::EVENT_TYPE_BREAK:
				case Event::EVENT_TYPE_CLEANING:
				case Event::EVENT_TYPE_TEST_FLIGHT:
				case Event::EVENT_TYPE_USER_FLIGHT:
					$event = new Event();
					$event->event_type = $eventType;
					$event->city_id = $city ? $city->id : 0;
					$event->location_id = $location->id ?? 0;
					$event->flight_simulator_id = $simulator->id ?? 0;
					$event->user_id = $this->request->user()->id ?? 0;
					$event->employee_id = $employeeId;
					$event->test_pilot_id = $pilotId;
					$event->start_at = Carbon::parse($flightAt)->format('Y-m-d H:i');
					$event->stop_at = Carbon::parse($flightStopAt)->format('Y-m-d H:i');
					$event->save();
				break;
			}
			
			if (in_array($source,[Deal::WEB_SOURCE, Deal::MOB_SOURCE])) {
				$job = new \App\Jobs\SendDealEmail($deal);
				$job->handle();
			}
			
			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();

			Log::debug('500 - Deal Booking Store: ' . $e->getMessage());

			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}

		return response()->json(['status' => 'success']);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function storeProduct()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		$city = $user->city;
		
		if ($user) {
			if (!$user->isAdminOrHigher()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
			}
		}
		
		$rules = [
			'name' => 'required',
			'email' => 'required|email',
			'phone' => 'required|valid_phone',
			'product_id' => 'required|numeric|min:0|not_in:0',
			/*'city_id' => 'required|numeric|min:0|not_in:0',*/
		];

		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Имя',
				'email' => 'E-mail',
				'phone' => 'Телефон',
				'product_id' => 'Продукт',
				/*'city_id' => 'Город',*/
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		/*$cityId = $this->request->city_id ?: $this->request->user()->city_id;*/
		$productId = $this->request->product_id ?? 0;
		/*$promoId = $this->request->promo_id ?? 0;
		$promocodeId = $this->request->promocode_id ?? 0;*/
		$comment = $this->request->comment ?? '';
		$amount = $this->request->amount ?? 0;
		$contractorId = $this->request->contractor_id ?? 0;
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		$name = $this->request->name ?? '';
		$email = $this->request->email ?? '';
		$phone = $this->request->phone ?? '';
		$source = $this->request->source ?? '';
		$isPaid = (bool)$this->request->is_paid;
		
		$product = Product::find($productId);
		if (!$product) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$productType = $product->productType;
		if (!$productType) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$cityProduct = $product->cities()->where('cities_products.is_active', true)->find($city->id);
		if (!$cityProduct) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		if ($paymentMethodId) {
			$paymentMethod = PaymentMethod::where('is_active', true)
				->find($paymentMethodId);
			if (!$paymentMethod) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
			}
		}
		
		if ($contractorId) {
			$contractor = Contractor::find($contractorId);
			if (!$contractor) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);
			}
		} elseif ($email) {
			$contractor = Contractor::whereRaw('LOWER(email) = ?', [mb_strtolower($email)])
				->first();
			if ($contractor && !in_array($source, [Contractor::WEB_SOURCE])) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-с-таким-e-mail-уже-существует')]);
			}
		}
		
		$data = [];
		if ($comment) {
			$data['comment'] = $comment;
		}
		
		$tax = round($amount * $productType->tax / 100, 2);
		$totalAmount = round($amount + $tax, 2);
		
		$currency = HelpFunctions::getEntityByAlias(Currency::class, Currency::USD_ALIAS);
		
		try {
			\DB::beginTransaction();
			
			if (!$contractor) {
				$contractor = new Contractor();
				$contractor->name = $name;
				$contractor->email = $email;
				$contractor->phone = $phone;
				$contractor->city_id = $city ? $city->id : 0;
				$contractor->source = $source ?: Contractor::ADMIN_SOURCE;
				$contractor->user_id = $user ? $user->id : 0;
				$contractor->save();
			}

			$deal = new Deal();
			$dealStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::CREATED_STATUS);
			$deal->status_id = $dealStatus ? $dealStatus->id : 0;
			$deal->contractor_id = $contractor ? $contractor->id : 0;
			$deal->city_id = $city ? $city->id : 0;
			$deal->name = $name;
			$deal->phone = $phone;
			$deal->email = $email;
			$deal->user_id = $user ? $user->id : 0;
			$deal->source = $source ?: Deal::ADMIN_SOURCE;
			$deal->save();

			$position = new DealPosition();
			$position->product_id = $product->id ?? 0;
			$position->amount = $amount;
			$position->tax = $tax;
			$position->total_amount = $totalAmount;
			$position->currency_id = $cityProduct->pivot->currency_id ?? 0;
			$position->city_id = $city ? $city->id : 0;
			$position->source = Deal::ADMIN_SOURCE;
			$position->user_id = $user ? $user->id : 0;
			$position->source = $source ?: Deal::ADMIN_SOURCE;
			$position->data_json = !empty($data) ? $data : null;
			$position->save();

			$deal->positions()->save($position);
			
			if ($amount) {
				$onlinePaymentMethod = HelpFunctions::getEntityByAlias(PaymentMethod::class, Bill::ONLINE_PAYMENT_METHOD);
				$billStatus = HelpFunctions::getEntityByAlias(Status::class, Bill::NOT_PAYED_STATUS);
				$billPayedStatus = HelpFunctions::getEntityByAlias(Status::class, Bill::PAYED_STATUS);
				
				if ($source == Deal::WEB_SOURCE) {
					$billLocationId = /*$location ? $location->id : */0;
				} else {
					$billLocationId = $user->location_id;
				}
				
				$bill = new Bill();
				$bill->contractor_id = $contractor->id;
				$bill->deal_id = $deal->id;
				$bill->deal_position_id = $position->id;
				$bill->location_id = $billLocationId;
				$bill->payment_method_id = ($source == Deal::WEB_SOURCE) ? 0 : ($paymentMethodId ?? 0);
				$bill->status_id = ($isPaid && $paymentMethodId != $onlinePaymentMethod->id) ? $billPayedStatus->id : $billStatus->id;
				$bill->payed_at = ($isPaid && $paymentMethodId != $onlinePaymentMethod->id) ? Carbon::now()->format('Y-m-d H:i:s') : null;
				$bill->amount = $amount;
				$bill->tax = $tax;
				$bill->total_amount = $totalAmount;
				$bill->currency_id = $currency ? $currency->id : 0;
				$bill->user_id = $user ? $user->id : 0;
				$bill->save();
				
				$deal->bills()->save($bill);
			}
			
			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();

			Log::debug('500 - Position Product Store: ' . $e->getMessage());

			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}

		return response()->json(['status' => 'success']);
	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if ($user) {
			if (!$user->isAdminOrHigher()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
			}
		}
		
		$deal = Deal::find($id);
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		if ($deal->status && in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		$rules = [
			'name' => 'required',
			'email' => 'required|email',
			'phone' => 'required|valid_phone',
			'status_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'email' => 'E-mail',
				'phone' => 'Phone',
				'status_id' => 'Status',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$contractorId = $this->request->contractor_id ?? 0;
		//$cityId = $this->request->city_id ?? 0;
		$statusId = $this->request->status_id ?? 0;
		$name = $this->request->name ?? '';
		$email = $this->request->email ?? '';
		$phone = $this->request->phone ?? '';
		
		try {
			\DB::beginTransaction();
			
			$deal->status_id = $statusId;
			$deal->name = $name;
			$deal->email = $email;
			$deal->phone = $phone;
			if ($contractorId) {
				$deal->contractor_id = $contractorId;
			}
			$deal->save();
			
			if ($contractorId) {
				$bills = $deal->bills;
				foreach ($bills as $bill) {
					$bill->contractor_id = $contractorId;
					$bill->save();
				}
				$events = $deal->events;
				foreach ($events as $event) {
					$event->contractor_id = $contractorId;
					$event->save();
				}
			}
			
			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();
			
			Log::debug('500 - Deal Update: ' . $e->getMessage());
			
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function calcProductAmount()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		//\Log::debug($this->request);
		
		$productId = $this->request->product_id ?? 0;
		$contractorId = $this->request->contractor_id ?? 0;
		$promoId = $this->request->promo_id ?? 0;
		$promocodeId = $this->request->promocode_id ?? 0;
		$promocodeUuid = $this->request->promocode_uuid ?? '';
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		$locationId = $this->request->location_id ?? 0;
		$certificateNumber = $this->request->certificate ?? '';
		$certificateUuid = $this->request->certificate_uuid ?? '';
		$source = $this->request->source ?? 'admin';
		$flightDate = $this->request->flight_date ?? '';
		$isFree = $this->request->is_free ?? 0;
		$isCertificatePurchase = $this->request->is_certificate_purchase ?? 0;
		$birthday = $this->request->birthday ?? 0;
		$weekends = $this->request->weekends ?? 0;
		
		if ($this->request->city_id) {
			$cityId = $this->request->city_id ?? 0;
		} elseif ($this->request->location_id) {
			$location = Location::find($locationId);
			$cityId = $location->city ? $location->city->id : 0;
		} else {
			$cityId = 1;
		}
		
		$currency = Currency::USD_NAME;
		$city = City::find($cityId);
		if ($city) {
			$currency = $city->currency->name;
		}
		
		if (!$productId) {
			return response()->json([
				'status' => 'success',
				'amount' => 0,
				'baseAmount' => 0,
				'tax' => 0,
				'totalAmount' => 0,
				'currency' => $currency,
			]);
		}
		
		$product = Product::find($productId);
		if ($weekends) {
			$regularUltimateProductTypes = ProductType::whereIn('alias', [ProductType::REGULAR_ALIAS, ProductType::ULTIMATE_ALIAS])
				->pluck('id')
				->toArray();
			$product = Product::where('duration', $product->duration)
				->where('id', '!=', $product->id)
				->whereIn('product_type_id', $regularUltimateProductTypes)
				->first();
		}
		if (!$product) {
			return response()->json([
				'status' => 'error',
				'amount' => 0,
				'baseAmount' => 0,
				'tax' => 0,
				'totalAmount' => 0,
				'currency' => $currency,
				'reason' => trans('main.error.продукт-не-найден'),
			]);
		}
		
		$productType = $product->productType;

		if ($flightDate) {
			$flightDate = str_replace('/', '.', $flightDate);
		}
		
		// Если дата - выходный день или праздник, меняем Regular на Ultimate
		if ($flightDate && (in_array(date('w', strtotime(Carbon::parse($flightDate)->format('d.m.Y'))), [0, 6]) || in_array(Carbon::parse($flightDate)->format('d.m.Y'), Deal::HOLIDAYS))) {
			$product = Product::where('alias', ProductType::ULTIMATE_ALIAS . '_' . $product->duration)
				->first();
		}
		
		$cityProduct = $product->cities()->where('cities_products.is_active', true)->find($cityId);
		if (!$cityProduct || !$cityProduct->pivot) {
			return response()->json([
				'status' => 'error',
				'amount' => 0,
				'baseAmount' => 0,
				'tax' => 0,
				'totalAmount' => 0,
				'currency' => $currency,
				'reason' => trans('main.error.не-задана-базовая-стоимость-продукта'),
			]);
		}
		
		// базовая стоимость продукта
		$baseAmount = $cityProduct->pivot->price ?? 0;

		if ($promocodeUuid) {
			$promocode = HelpFunctions::getEntityByUuid(Promocode::class, $promocodeUuid);
			if ($promocode) {
				$promocodeId = $promocode->id;
			}
		}

		$certificateId = 0;
		if ($certificateNumber) {
			$certificate = Certificate::where('number', $certificateNumber)
				->first();
			$certificateId = $certificate ? $certificate->id : 0;
		} elseif ($certificateUuid) {
			$certificate = HelpFunctions::getEntityByUuid(Certificate::class, $certificateUuid);
			$certificateId = $certificate ? $certificate->id : 0;
		}
		
		if ($birthday) {
			$promo = HelpFunctions::getEntityByAlias(Promo::class, Promo::BIRTHDAY_ALIAS);
			$promoId = $promo->id;
		}

		$amount = $product->calcAmount($contractorId, $cityId, $source, $isFree, $locationId, $paymentMethodId, $promoId, $promocodeId, $certificateId, false, false, 0, $isCertificatePurchase);
		$tax = round($amount * $productType->tax / 100, 2);
		$totalAmount = round($amount + $tax, 2);
		
		return response()->json([
			'status' => 'success',
			'amount' => $amount,
			'baseAmount' => $baseAmount,
			'tax' => $tax,
			'totalAmount' => $totalAmount,
			'certificateUuid' => $certificateUuid,
			'certificateId' => $certificateId,
			'currency' => $currency,
			'product_name' => $product->name,
			'product_public_name' => $product->public_name,
			'product_duration' => $product->duration,
			'product_type_alias' => mb_strtoupper($productType->alias),
		]);
	}
	
	/**
	 * @param $source
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function dealWebhook($source)
	{
		$name = $this->request->name ?? null;
		$phone = $this->request->phone ?? null;
		$email = $this->request->email ?? null;
		$visit = $this->request->visit ?? null;
		$id = $this->request->id ?? null;
		$title = $this->request->title ?? null;
		$text = $this->request->text ?? null;
		$data = $this->request->data ?? null;
		$createdDate = $this->request->created_date ?? null;
		$user = $this->request->user ?? null;
		$token = $this->request->token ?? null;
		$action = $this->request->action ?? null;
		
		if (!$visit) {
			return response()->json([
				'status' => 'error',
				'order_id' => null,
			]);
		}
		
		$dealStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::CREATED_STATUS);

		$dataJson = [
			'id' => $id,
			'title' => $title,
			'text' => $text,
			'data' => $data,
			'createdDate' => $createdDate,
			'user' => $user,
			'token' => $token,
			'action' => $action,
		];
		
		$deal = new Deal();
		$deal->status_id = $dealStatus->id ?? 0;
		$deal->name = $name;
		$deal->phone = $phone;
		$deal->email = $email;
		$deal->source = $source ?? '';
		$deal->data_json = $dataJson;
		if (!$deal->save()) {
			return response()->json([
				'status' => 'error',
				'order_id' => null,
			]);
		}
		
		return response()->json([
			'status' => 'ok',
			'order_id' => $deal->id,
		]);
	}
	
	/**
	 * @param $source
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function dealExtendedWebhook($source)
	{
		$dealId = $this->request->leadId ?? 0;
		
		if (!$dealId) {
			return response()->json([
				'status' => 'error',
				'order_id' => null,
			]);
		}

		$deal = Deal::find($dealId);
		
		$number = $deal->number ?? null;
		$name = $deal->name ?? null;
		$phone = $deal->phone ?? null;
		$email = $deal->email ?? null;
		$title = $this->request->title ?? null;
		$text = $this->request->text ?? null;
		$action = $this->request->action ?? null;
		$user = $this->request->user ?? null;
		$token = $this->request->token ?? null;
		
		$dataJson = [
			'number' => $number,
			'title' => $title,
			'text' => $text,
			'user' => $user,
			'token' => $token,
			'action' => $action,
		];
		
		$dealStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::CREATED_STATUS);

		$deal = new Deal();
		$deal->status_id = $dealStatus->id ?? 0;
		$deal->name = $name;
		$deal->phone = $phone;
		$deal->email = $email;
		$deal->source = $source ?? '';
		$deal->data_json = $dataJson;
		if (!$deal->save()) {
			return response()->json([
				'status' => 'error',
				'order_id' => null,
			]);
		}
		
		return response()->json([
			'status' => 'ok',
			'order_id' => $deal->id,
		]);
	}
}
