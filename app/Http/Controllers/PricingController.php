<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Discount;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use App\Models\City;

class PricingController extends Controller
{
	private $request;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index()
	{
		return view('admin.pricing.index', [
		]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getListAjax()
	{
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$city = $user->city;
		
		$products = Product::orderBy('product_type_id')
			->orderBy('duration')
			->get();
		
		$citiesProductsData = [];
		foreach ($products as $product) {
			$cityProduct = $city->products->find($product->id);
			if (!$cityProduct) continue;
			
			$citiesProductsData[$city->id][$product->id] = [
				'availability' => $cityProduct->pivot->availability,
				'purchase_price' => $cityProduct->pivot->purchase_price,
				'price' => $cityProduct->pivot->price,
				'currency' => $city->currency ? $city->currency->name : '',
				'is_hit' => $cityProduct->pivot->is_hit,
				'is_active' => $cityProduct->pivot->is_active,
				'data_json' => $cityProduct->pivot->data_json,
			];
			if ($cityProduct->pivot->discount) {
				$citiesProductsData[$city->id][$product->id]['discount'] = [
					'value' => $cityProduct->pivot->discount->value,
					'is_fixed' => $cityProduct->pivot->discount->is_fixed,
				];
			}
		}
		
		$VIEW = view('admin.pricing.list', [
			'city' => $city,
			'products' => $products,
			'citiesProductsData' => $citiesProductsData,
		]);

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $cityId
	 * @param $productId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit($cityId, $productId)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$city = City::find($cityId);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);

		$product = Product::find($productId);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);

		$cityProduct = $city->products()->find($productId);

		$cities = City::orderBy('name')
			->get();
		
		$products = Product::orderBy('name')
			->get();

		$discounts = Discount::where('is_active', true)
			->orderBy('is_fixed')
			->orderBy('value')
			->get();

		$currencies = Currency::get();

		$VIEW = view('admin.pricing.modal.edit', [
			'cityId' => $cityId,
			'product' => $product,
			'cities' => $cities,
			'products' => $products,
			'discounts' => $discounts,
			'currencies' => $currencies,
			'cityProduct' => $cityProduct ? $cityProduct->pivot : null,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $cityId
	 * @param $productId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function confirm($cityId, $productId)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$city = City::find($cityId);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		$product = Product::find($productId);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		
		$cityProduct = $city->products->find($productId);
		if (!$cityProduct) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-в-городе-не-найден', ['city_name' => $city->name])]);

		$VIEW = view('admin.pricing.modal.delete', [
			'city' => $city,
			'product' => $product,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $cityId
	 * @param $productId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($cityId, $productId)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$city = City::find($cityId);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		$product = Product::find($productId);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		
		$cityProduct = $city->products->find($productId);
		
		$rules = [
			'price' => 'required|numeric',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'price' => 'Amount',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		/*$data = $cityProduct ? json_decode($cityProduct->pivot->data_json, true) : [];*/

		$data = [
			'availability' => $this->request->availability ?? 0,
			'purchase_price' => $this->request->purchase_price ?? 0,
			'price' => $this->request->price ?? 0,
			'currency_id' => $this->request->currency_id ?? 0,
			'discount_id' => $this->request->discount_id ?? 0,
			'is_hit' => (bool)$this->request->is_hit,
			'is_active' => (bool)$this->request->is_active,
			/*'data_json' => json_encode($data, JSON_UNESCAPED_UNICODE),*/
		];
		
		if ($cityProduct) {
			$city->products()->updateExistingPivot($product->id, $data);
		} else {
			$city->products()->attach($product->id, $data);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Pricing was successfully saved']);
	}
	
	/**
	 * @param $cityId
	 * @param $productId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($cityId, $productId)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$city = City::find($cityId);
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		$product = Product::find($productId);
		if (!$product) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		
		$cityProduct = $city->products->find($productId);
		if (!$cityProduct) return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-в-городе-не-найден', ['city_name' => $city->name])]);
		
		if (!$city->products()->detach($product->id)) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Pricing was successfully deleted']);
	}
}
