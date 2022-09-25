<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Deal;
use App\Models\Operation;
use App\Repositories\PaymentRepository;
use App\Services\HelpFunctions;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class OperationController extends Controller
{
	private $request;
	private $paymentRepo;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, PaymentRepository $paymentRepo) {
		$this->request = $request;
		$this->paymentRepo = $paymentRepo;
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index()
	{
		$user = Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			abort(404);
		}

		$types = Operation::TYPES;
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'operation');

		return view('admin.operation.index', [
			'types' => $types,
			'paymentMethods' => $paymentMethods,
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
		
		$filterOperatedAtFrom = $this->request->filter_operated_at_from ?? '';
		$filterOperatedAtTo = $this->request->filter_operated_at_to ?? '';
		$filterType = $this->request->filter_type ?? '';
		$filterPaymentMethodId = $this->request->filter_payment_method_id ?? 0;
		
		if (!$filterOperatedAtFrom && !$filterOperatedAtTo) {
			$filterOperatedAtFrom = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
			$filterOperatedAtTo = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		
		$operations = Operation::orderBy('operated_at')
			->where('operated_at', '>=', Carbon::parse($filterOperatedAtFrom)->startOfDay()->format('Y-m-d H:i:s'))
			->where('operated_at', '<=', Carbon::parse($filterOperatedAtTo)->endOfDay()->format('Y-m-d H:i:s'));
		if ($filterPaymentMethodId) {
			$operations = $operations->where('payment_method_id', $filterPaymentMethodId);
		}
		if ($filterType) {
			$operations = $operations->where('type', $filterType);
		}
		$operations = $operations->get();
		
		$types = Operation::TYPES;
		
		$VIEW = view('admin.operation.list', [
			'operations' => $operations,
			'types' => $types,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$operation = Operation::find($id);
		if (!$operation) return response()->json(['status' => 'error', 'reason' => 'Operation not found']);
		
		$types = Operation::TYPES;
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		$VIEW = view('admin.operation.modal.edit', [
			'operation' => $operation,
			'types' => $types,
			'paymentMethods' => $paymentMethods,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function add()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();

		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$types = Operation::TYPES;
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		$VIEW = view('admin.operation.modal.add', [
			'types' => $types,
			'paymentMethods' => $paymentMethods,
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
		
		$user = Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$operation = Operation::find($id);
		if (!$operation) return response()->json(['status' => 'error', 'reason' => 'Operation not found']);
		
		$VIEW = view('admin.operation.modal.delete', [
			'operation' => $operation,
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
		$location = $user->location;

		$rules = [
			'amount' => 'required|numeric|min:0|not_in:0',
			'operated_at' => 'required|date',
			'type' => 'required',
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'amount' => 'Amount',
				'operated_at' => 'Operation date',
				'type' => 'Type',
				'payment_method_id' => 'Payment method',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$amount = $this->request->amount ?? 0;
		$operatedAt = $this->request->operated_at ?? null;
		$type = $this->request->type ?? null;
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		$comment = $this->request->comment ?? null;
		
		$currency = $city->currency;
		
		$data = [];
		$data['comment'] = $comment;
		
		$operation = new Operation();
		$operation->amount = $amount;
		$operation->operated_at = Carbon::parse($operatedAt)->format('Y-m-d');
		$operation->type = $type;
		$operation->payment_method_id = $paymentMethodId;
		$operation->currency_id = $currency->id ?? 0;
		$operation->city_id = $city->id ?? 0;
		$operation->location_id = $location->id ?? 0;
		$operation->data_json = $data;
		$operation->user_id = $user->id ?? 0;
		if (!$operation->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Operation was successfully added']);
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

		$operation = Operation::find($id);
		if (!$operation) return response()->json(['status' => 'error', 'reason' => 'Operation not found']);
		
		$user = Auth::user();
		
		$rules = [
			'amount' => 'required|numeric|min:0|not_in:0',
			'operated_at' => 'required|date',
			'type' => 'required',
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'amount' => 'Amount',
				'operated_at' => 'Operation date',
				'type' => 'Type',
				'payment_method_id' => 'Payment method',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$amount = $this->request->amount ?? 0;
		$operatedAt = $this->request->operated_at ?? null;
		$type = $this->request->type ?? null;
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		$comment = $this->request->comment ?? null;
		
		$data = $operation->data_json;
		$data['comment'] = $comment;
		
		$operation->amount = $amount;
		$operation->operated_at = Carbon::parse($operatedAt)->format('Y-m-d');
		$operation->type = $type;
		$operation->payment_method_id = $paymentMethodId;
		$operation->data_json = $data;
		$operation->user_id = $user->id ?? 0;
		if (!$operation->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Operation was successfully saved']);
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$operation = Operation::find($id);
		if (!$operation) return response()->json(['status' => 'error', 'reason' => 'Operation not found']);
		
		if (!$operation->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Operation was successfully deleted']);
	}
}
