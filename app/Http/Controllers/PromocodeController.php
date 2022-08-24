<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Discount;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Models\Promocode;

class PromocodeController extends Controller
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
		return view('admin.promocode.index', [
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
		
		$user = Auth::user();
		$city = $user->city;
		
		$promocodes = Promocode::whereNull('type')
			->whereRelation('cities', 'city_id', '=', $city->id)
			->orderBy('number')
			->get();
		
		$VIEW = view('admin.promocode.list', ['promocodes' => $promocodes]);

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
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$promocode = Promocode::find($id);
		if (!$promocode) return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
		
		$cities = City::orderBy('name')
			->get();
		
		$discounts = Discount::where('is_active', true)
			->orderBy('is_fixed')
			->orderBy('value')
			->get();
		
		$promocodeCityIds = $promocode->cities()->pluck('cities.id')->toArray();
		
		$VIEW = view('admin.promocode.modal.edit', [
			'promocode' => $promocode,
			'promocodeCityIds' => $promocodeCityIds,
			'cities' => $cities,
			'discounts' => $discounts,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function add()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$cities = City::orderBy('name')
			->get();
		
		$discounts = Discount::where('is_active', true)
			->orderBy('is_fixed')
			->orderBy('value')
			->get();
		
		$VIEW = view('admin.promocode.modal.add', [
			'cities' => $cities,
			'discounts' => $discounts,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$promocode = Promocode::find($id);
		if (!$promocode) return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
		
		$VIEW = view('admin.promocode.modal.show', [
			'promocode' => $promocode,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
	 */
	public function confirm($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$promocode = Promocode::find($id);
		if (!$promocode) return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
		
		$VIEW = view('admin.promocode.modal.delete', [
			'promocode' => $promocode,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$rules = [
			'number' => [
				'required',
				'max:255',
			],
			'discount_id' => [
				'required',
				'numeric',
				'min:0',
				'not_in:0',
			],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'number' => 'Number',
				'discount_id' => 'Discount',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$promocode = new Promocode();
		$promocode->number = $this->request->number;
		$promocode->discount_id = $this->request->discount_id;
		$promocode->is_active = $this->request->is_active;
		if ($this->request->active_from_at_date) {
			$promocode->active_from_at = Carbon::parse($this->request->active_from_at_date . ' ' . $this->request->active_from_at_time)->format('Y-m-d H:i:s');
		}
		if ($this->request->active_to_at_date) {
			$promocode->active_to_at = Carbon::parse($this->request->active_to_at_date . ' ' . $this->request->active_to_at_time)->format('Y-m-d H:i:s');
		}
		$data = $promocode->data_json;
		$promocode->data_json = $data;
		if (!$promocode->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		$promocode->cities()->sync((array)$city->id);
		
		return response()->json(['status' => 'success', 'message' => 'Promocode was successfully created']);
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
		
		$user = Auth::user();
		$city = $user->city;

		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$promocode = Promocode::find($id);
		if (!$promocode) return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
		
		$rules = [
			'number' => [
				'required',
				'max:255',
			],
			'discount_id' => [
				'required',
				'numeric',
				'min:0',
				'not_in:0',
			],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'number' => 'Number',
				'discount_id' => 'Discount',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		//$data = [];
		
		$promocode->number = $this->request->number;
		$promocode->discount_id = $this->request->discount_id;
		$promocode->is_active = $this->request->is_active;
		if ($this->request->active_from_at_date) {
			$promocode->active_from_at = Carbon::parse($this->request->active_from_at_date . ' ' . $this->request->active_from_at_time)->format('Y-m-d H:i:s');
		}
		if ($this->request->active_to_at_date) {
			$promocode->active_to_at = Carbon::parse($this->request->active_to_at_date . ' ' . $this->request->active_to_at_time)->format('Y-m-d H:i:s');
		}
		$data = $promocode->data_json;
		$promocode->data_json = $data;
		if (!$promocode->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		$promocode->cities()->sync((array)$city->id);
		
		return response()->json(['status' => 'success', 'message' => 'Promocode was successfully saved']);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$promocode = Promocode::find($id);
		if (!$promocode) return response()->json(['status' => 'error', 'reason' => trans('main.error.промокод-не-найден')]);
		
		if (!$promocode->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Promocode was successfully deleted']);
	}
}
