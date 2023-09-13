<?php

namespace App\Http\Controllers;

use App\Services\HelpFunctions;
use Illuminate\Http\Request;
use Validator;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
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
		return view('admin.paymentMethod.index', [
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

		$paymentMethods = PaymentMethod::get();

		$VIEW = view('admin.paymentMethod.list', ['paymentMethods' => $paymentMethods]);

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
		
		$paymentMethod = PaymentMethod::find($id);
		if (!$paymentMethod) return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
		
		$VIEW = view('admin.paymentMethod.modal.edit', [
			'paymentMethod' => $paymentMethod,
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
		
		$VIEW = view('admin.paymentMethod.modal.add', [
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
		
		$paymentMethod = PaymentMethod::find($id);
		if (!$paymentMethod) return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
		
		$VIEW = view('admin.paymentMethod.modal.show', [
			'paymentMethod' => $paymentMethod,
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
		
		$paymentMethod = PaymentMethod::find($id);
		if (!$paymentMethod) return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
		
		$VIEW = view('admin.paymentMethod.modal.delete', [
			'paymentMethod' => $paymentMethod,
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
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$rules = [
			'name' => ['required', 'max:255', 'unique:payment_methods,name'],
			'alias' => ['required', 'min:3', 'max:50', 'unique:payment_methods,alias'],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'alias' => 'Alias',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$paymentMethod = new PaymentMethod();
		$paymentMethod->name = $this->request->name;
		$paymentMethod->alias = $this->request->alias;
		$paymentMethod->is_active = $this->request->is_active;
		if (!$paymentMethod->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Payment method was successfully created']);
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
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$paymentMethod = PaymentMethod::find($id);
		if (!$paymentMethod) return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
		
		if (HelpFunctions::isDemo($paymentMethod->created_at)) {
			return response()->json(['status' => 'error', 'reason' => 'Demo data cannot be updated']);
		}
		
		$rules = [
			'name' => ['required', 'max:255', 'unique:payment_methods,name,' . $id],
			'alias' => ['required', 'min:3', 'max:50', 'unique:payment_methods,alias,' . $id],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'name' => 'Name',
				'alias' => 'Alias',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$paymentMethod->name = $this->request->name;
		$paymentMethod->alias = $this->request->alias;
		$paymentMethod->is_active = $this->request->is_active;
		if (!$paymentMethod->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Payment method was successfully saved']);
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
		
		if (!$this->request->user()->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$paymentMethod = PaymentMethod::find($id);
		if (!$paymentMethod) return response()->json(['status' => 'error', 'reason' => trans('main.error.способ-оплаты-не-найден')]);
		
		if (HelpFunctions::isDemo($paymentMethod->created_at)) {
			return response()->json(['status' => 'error', 'reason' => 'Demo data cannot be deleted']);
		}
		
		if (!$paymentMethod->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Payment method was successfully deleted']);
	}
}
