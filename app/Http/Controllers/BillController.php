<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\City;
use App\Models\Currency;
use App\Models\DealPosition;
use App\Models\PaymentMethod;
use App\Models\Deal;
use App\Models\ProductType;
use App\Models\Status;
use App\Services\HelpFunctions;
use App\Services\AuthorizeNetService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;
use Validator;
use Throwable;

class BillController extends Controller
{
	private $request;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
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
		
		$bill = Bill::find($id);
		if (!$bill) return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-не-найден')]);
		
		$statuses = Status::where('type', Status::STATUS_TYPE_BILL)
			->orderBy('sort')
			->get();
		
		$paymentMethods = PaymentMethod::where('is_active', true)
			->orderBy('name')
			->get();

		$currencies = Currency::get();
		
		$deal = $bill->deal;
		$positions = $deal->positions;

		$VIEW = view('admin.bill.modal.edit', [
			'bill' => $bill,
			'paymentMethods' => $paymentMethods,
			'statuses' => $statuses,
			'currencies' => $currencies,
			'positions' => $positions,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $dealId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function add($dealId)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$deal = Deal::find($dealId);
		if (!$deal) return response()->json(['status' => 'error', 'reason' => 'Сделка не найдена']);
		
		
		$amount = $deal->amount() - $deal->billPayedAmount()/* - $deal->scoreAmount()*/;
		
		$statuses = Status::where('type', Status::STATUS_TYPE_BILL)
			->where('alias', '!=', Bill::PAYED_PROCESSING_STATUS)
			->orderBy('sort')
			->get();

		$paymentMethods = PaymentMethod::where('is_active', true)
			->orderBy('name')
			->get();

		$currencies = Currency::get();
		
		$positions = $deal->positions;

		$VIEW = view('admin.bill.modal.add', [
			'deal' => $deal,
			'amount' => ($amount > 0) ? $amount : 0,
			'paymentMethods' => $paymentMethods,
			'statuses' => $statuses,
			'currencies' => $currencies,
			'positions' => $positions,
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

		$rules = [
			'deal_id' => 'required|numeric|min:0|not_in:0',
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
			'status_id' => 'required|numeric|min:0|not_in:0',
			'amount' => 'required|numeric|min:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'deal_id' => 'Deal',
				'payment_method_id' => 'Payment method',
				'status_id' => 'Status',
				'amount' => 'Amount',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$status = Status::find($this->request->status_id);
		if (!$status) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.статус-не-найден')]);
		}

		$deal = Deal::find($this->request->deal_id);
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		$position = DealPosition::where('deal_id', $this->request->deal_id)
			->first();
		if (!$position) return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
		
		if (!$deal->contractor) return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);
		
		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		$positionId = $this->request->position_id ?? 0;
		if ($positionId) {
			$position = DealPosition::find($positionId);
			if (!$position) return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
		}
		
		$paymentMethodId = $this->request->payment_method_id ?? 0;

		try {
			\DB::beginTransaction();
			
			$bill = new Bill();
			$bill->contractor_id = $deal->contractor->id ?? 0;
			$bill->deal_id = $deal->id ?? 0;
			$bill->deal_position_id = $position->id ?? 0;
			$bill->city_id = $city->id ?? 0;
			$bill->location_id = $this->request->user()->location_id ?? 0;
			$bill->payment_method_id = $paymentMethodId;
			$bill->status_id = $this->request->status_id ?? 0;
			$bill->total_amount = $this->request->amount ?? 0;
			$bill->currency_id = $this->request->currency_id ?? 0;
			$bill->user_id = $this->request->user()->id;
			if ($status->alias == Bill::PAYED_STATUS) {
				$bill->payed_at = Carbon::now()->format('Y-m-d H:i:s');
				
				// при выставлении даты оплаты генерим и дату окончания срока действия сертификата,
				// если это счет на позицию покупки сертификата
				$certificate = $position ? $position->certificate : null;
				if ($certificate) {
					$product = $certificate->product;
					$productType = $product->productType;
					$certificatePeriod = ($productType->alias == ProductType::COURSES_ALIAS) ? 12 : 6;
					$certificate->expire_at = Carbon::now()->addMonths($certificatePeriod)->format('Y-m-d H:i:s');
					$certificate->save();
				}
			}
			$bill->save();
			$deal->bills()->save($bill);
			
			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();
			
			Log::debug('500 - Bill Create: ' . $e->getMessage());
			
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
		
		$user = Auth::user();
		$city = $user->city;

		$bill = Bill::find($id);
		if (!$bill) return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-не-найден')]);
		
		$billStatus = $bill->status;
		if ($billStatus && $billStatus->alias == Bill::CANCELED_STATUS/* && !$user->isSuperAdmin()*/) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-в-текущем-статусе-недоступен-для-редактирования')]);
		}
		
		$deal = $bill->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS]) && !$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		$rules = [
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
			'status_id' => 'required|numeric|min:0|not_in:0',
			'amount' => 'required|numeric|min:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'payment_method_id' => 'Payment method',
				'status_id' => 'Status',
				'amount' => 'Amount',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$status = Status::find($this->request->status_id);
		if (!$status) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.статус-не-найден')]);
		}
		
		$positionId = $this->request->position_id ?? 0;
		if ($positionId) {
			$position = DealPosition::find($positionId);
			if (!$position) return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
		}
		
		if (in_array($bill->status->alias, [Bill::PAYED_STATUS, Bill::PAYED_PROCESSING_STATUS]) && in_array($bill->paymentMethod->alias, [PaymentMethod::ONLINE_ALIAS]) /*&& !$user->isSuperAdmin()*/) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.оплаченный-счет-со-способом-оплаты-онлайн-недоступен-для-редактирования')]);
		}

		$paymentMethodId = $this->request->payment_method_id ?? 0;
		$amount = $this->request->amount ?? 0;
		
		$bill->deal_position_id = $position->id ?? 0;
		$bill->payment_method_id = $paymentMethodId;
		$bill->status_id = $this->request->status_id ?? 0;
		$bill->total_amount = $amount;
		$bill->currency_id = $this->request->currency_id ?? 0;
		if ($status->alias == Bill::PAYED_STATUS && !$bill->payed_at) {
			$bill->payed_at = Carbon::now()->format('Y-m-d H:i:s');
			
			// при выставлении даты оплаты генерим и дату окончания срока действия сертификата,
			// если это счет на позицию покупки сертификата
			$position = $bill->position;
			$certificate = $position ? $position->certificate : null;
			$product = $certificate ? $certificate->product : null;
			$cityProduct = $product ? $product->cities()->where('cities_products.is_active', true)->find($city->id) : null;
			$dataJson = ($cityProduct && $cityProduct->pivot) ? json_decode($cityProduct->pivot->data_json, true) : [];
			
			$certificatePeriod = ($cityProduct && $position->is_certificate_purchase && array_key_exists('certificate_period', $dataJson)) ? $dataJson['certificate_period'] : 6;
			if ($certificate) {
				$certificate->expire_at = Carbon::now()->addMonths($certificatePeriod)->format('Y-m-d H:i:s');
				$certificate->save();
			}
		}
		if (!$bill->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
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

		$bill = Bill::find($id);
		if (!$bill) return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-не-найден')]);
		
		$billStatus = $bill->status;
		if ($billStatus && $billStatus->alias == Bill::CANCELED_STATUS/* && !$user->isSuperAdmin()*/) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-в-текущем-статусе-недоступен-для-редактирования')]);
		}
		
		$user = Auth::user();
		
		if ($user->isAdmin() && $user->location_id && $bill->location_id && $user->location_id != $bill->location_id) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$deal = $bill->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		if (in_array($bill->status->alias, [Bill::PAYED_STATUS, Bill::PAYED_PROCESSING_STATUS]) && in_array($bill->paymentMethod->alias, [PaymentMethod::ONLINE_ALIAS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.оплаченный-счет-со-способом-оплаты-онлайн-недоступен-для-редактирования')]);
		}

		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		if (!$bill->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}

		return response()->json(['status' => 'success']);
	}

	public function sendPayLink() {
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$rules = [
			'bill_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'bill_id' => 'Invoice',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$bill = Bill::find($this->request->bill_id);
		if (!$bill) return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-не-найден')]);
		
		if ($bill->amount <= 0) return response()->json(['status' => 'error', 'reason' => trans('main.error.сумма-счета-указана-некорректно')]);
		
		$deal = $bill->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		$contractor = $bill->contractor;
		if (!$contractor) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		$city = $contractor->city;
		if (!$city) return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		
		$email = $deal->email ?: $contractor->email;
		if (!$email) return response()->json(['status' => 'error', 'reason' => trans('main.error.e-mail-не-найден')]);
		
		$job = new \App\Jobs\SendPayLinkEmail($bill);
		$job->handle();
		
		return response()->json(['status' => 'success', 'message' => trans('main.success.задание-на-отправку-ссылки-на-оплату-принято')]);
	}
}
