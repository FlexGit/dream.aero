<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Tip;
use App\Models\User;
use App\Repositories\PaymentRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class TipController extends Controller
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
		$city = $user->city;
		
		$users = User::where('enable', true)
			->where('city_id', $city->id)
			->whereIn('role', [User::ROLE_ADMIN, User::ROLE_PILOT])
			->get();
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		return view('admin.tip.index', [
			'users' => $users,
			'paymentMethods' => $paymentMethods,
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
		
		$filterReceivedAtFrom = $this->request->filter_received_at_from ?? '';
		$filterReceivedAtTo = $this->request->filter_received_at_to ?? '';
		$filterUserId = $this->request->filter_user_id ?? 0;
		
		if (!$filterReceivedAtFrom && !$filterReceivedAtTo) {
			$filterReceivedAtFrom = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
			$filterReceivedAtTo = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		
		$tips = Tip::orderBy('received_at', 'desc')
			->where('received_at', '>=', Carbon::parse($filterReceivedAtFrom)->startOfDay()->format('Y-m-d H:i:s'))
			->where('received_at', '<=', Carbon::parse($filterReceivedAtTo)->endOfDay()->format('Y-m-d H:i:s'));
		if ($filterUserId) {
			$tips = $tips->where('admin_id', $filterUserId)
				->orWhere('pilot_id', $filterUserId);
		}
		$tips = $tips->get();
		
		$VIEW = view('admin.tip.list', [
			'tips' => $tips,
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
		
		$tip = Tip::find($id);
		if (!$tip) return response()->json(['status' => 'error', 'reason' => 'Tip not found']);
		
		$users = User::where('enable', true)
			->where('city_id', $city->id)
			->whereIn('role', [User::ROLE_ADMIN, User::ROLE_PILOT])
			->get();
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		$VIEW = view('admin.tip.modal.edit', [
			'tip' => $tip,
			'users' => $users,
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
		$city = $user->city;

		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$users = User::where('enable', true)
			->where('city_id', $city->id)
			->whereIn('role', [User::ROLE_ADMIN, User::ROLE_PILOT])
			->get();
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		$VIEW = view('admin.tip.modal.add', [
			'users' => $users,
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
		
		$tip = Tip::find($id);
		if (!$tip) return response()->json(['status' => 'error', 'reason' => 'Tips not found']);
		
		$VIEW = view('admin.tip.modal.delete', [
			'tip' => $tip,
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
			'received_at' => 'required|date',
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'amount' => 'Amount',
				'received_at' => 'Receiving date',
				'payment_method_id' => 'Payment method',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$amount = $this->request->amount ?? 0;
		$receivedAt = $this->request->received_at ?? null;
		$adminId = $this->request->admin_id ?? 0;
		$pilotId = $this->request->pilot_id ?? 0;
		$dealNumber = $this->request->deal_number ?? '';
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		
		$deal = null;
		if ($dealNumber) {
			$deal = Deal::where('number', $dealNumber)
				->first();
			if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		}
		
		$currency = $city->currency;
		
		$tip = new Tip();
		$tip->amount = $amount;
		$tip->received_at = Carbon::parse($receivedAt)->format('Y-m-d');
		$tip->admin_id = $adminId;
		$tip->pilot_id = $pilotId;
		$tip->deal_id = $deal ? $deal->id : 0;
		$tip->currency_id = $currency->id ?? 0;
		$tip->city_id = $city->id ?? 0;
		$tip->location_id = $location->id ?? 0;
		$tip->payment_method_id = $paymentMethodId;
		$tip->user_id = $user->id ?? 0;
		if (!$tip->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Tips was successfully added']);
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

		$tip = Tip::find($id);
		if (!$tip) return response()->json(['status' => 'error', 'reason' => 'Tips not found']);
		
		$user = Auth::user();
		
		$rules = [
			'amount' => 'required|numeric|min:0|not_in:0',
			'received_at' => 'required|date',
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'amount' => 'Amount',
				'received_at' => 'Receiving date',
				'payment_method_id' => 'Payment method',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$amount = $this->request->amount ?? 0;
		$receivedAt = $this->request->received_at ?? null;
		$adminId = $this->request->admin_id ?? 0;
		$pilotId = $this->request->pilot_id ?? 0;
		$dealNumber = $this->request->deal_number ?? '';
		$paymentMethodId = $this->request->payment_method_id ?? 0;
		
		$deal = null;
		if ($dealNumber) {
			$deal = Deal::where('number', $dealNumber)
				->first();
			if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		}
		
		$tip->amount = $amount;
		$tip->received_at = Carbon::parse($receivedAt)->format('Y-m-d');
		$tip->admin_id = $adminId;
		$tip->pilot_id = $pilotId;
		$tip->deal_id = $deal ? $deal->id : 0;
		$tip->user_id = $user->id ?? 0;
		$tip->payment_method_id = $paymentMethodId;
		if (!$tip->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Tips was successfully saved']);
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

		$tip = Tip::find($id);
		if (!$tip) return response()->json(['status' => 'error', 'reason' => 'Tips not found']);
		
		if (!$tip->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Tips was successfully deleted']);
	}
}
