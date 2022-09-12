<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Content;
use App\Models\Deal;
use App\Models\PaymentMethod;
use App\Models\Status;
use App\Services\AuthorizeNetService;
use App\Services\HelpFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Bill;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardExpirationDate;
use LVR\CreditCard\CardNumber;
use Validator;

class PaymentController extends Controller
{
	private $request;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}
	
	/**
	 * @param $uuid
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function payment($uuid)
	{
		$bill = Bill::where('uuid', $uuid)
			->where('location_id', '!=', 0)
			->first();
		if (!$bill) {
			abort(404);
		}
		
		if ($bill->total_amount <= 0) {
			abort(404);
		}
		
		$deal = $bill->deal;
		if (!$deal) {
			abort(404);
		}

		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			abort(404);
		}
		
		$cityAlias = $this->request->session()->get('cityAlias');
		$city = HelpFunctions::getEntityByAlias(City::class, $cityAlias ?: City::DC_ALIAS);
		$page = HelpFunctions::getEntityByAlias(Content::class, 'payment');

		$billStatus = $bill->status;
		if (!$billStatus || $billStatus->alias != Bill::NOT_PAYED_STATUS || $bill->payed_at != null) {
			return view('payment', [
				'bill' => $bill,
				'deal' => $deal,
				'page' => $page ?? new Content,
				'city' => $city,
				'html' => '',
				'error' => trans('main.pay.счет-оплачен'),
				'payType' => '',
			]);
		}
		
		$onlinePaymentMethod = HelpFunctions::getEntityByAlias(PaymentMethod::class, PaymentMethod::ONLINE_ALIAS);
		if ($bill->paymentMethod->alias != $onlinePaymentMethod->alias) {
			return view('payment', [
				'bill' => $bill,
				'deal' => $deal,
				'page' => $page ?? new Content,
				'city' => $city,
				'html' => '',
				'error' => trans('main.pay.счет-способ-оплаты'),
				'payType' => '',
			]);
		}

		return view('payment', [
			'bill' => $bill,
			'deal' => $deal,
			'page' => $page ?? new Content,
			'city' => $city,
			'html' => '',
			'error' => '',
			'payType' => $type ?? '',
		]);
	}
	
	public function paymentProceed()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$rules = [
			'card_number' => ['required', new CardNumber],
			'expiration_date' => ['required', new CardExpirationDate('mY')],
			'card_name' => ['required'],
			'card_code' => ['required', new CardCvc($this->request->card_number)],
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'card_number' => 'Card number',
				'expiration_date' => 'Expiration date',
				'card_name' => 'Full name',
				'card_code' => 'CVC',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.проверьте-правильность-заполнения-полей-формы'), 'errors' => $validator->errors()]);
		}
		
		$uuid = $this->request->uuid;
		$cardNumber = $this->request->card_number ?? '';
		$expirationDate = $this->request->expiration_date ?? '';
		if ($expirationDate) {
			$expirationDate = ((strlen($expirationDate) == 4) ? '20' : '') . mb_substr($expirationDate, 2, 4) . '-' . mb_substr($expirationDate, 0, 2);
		}
		$cardName = $this->request->card_name ?? '';
		$cardCode = $this->request->card_code ?? '';
		
		$billPayedStatus = HelpFunctions::getEntityByAlias(Status::class, Bill::PAYED_STATUS);

		$bill = HelpFunctions::getEntityByUuid(Bill::class, $uuid);
		if (!$bill) {
			return response()->json(['status' => 'error', 'reason' => 'Invoice not found']);
		}
		if ($bill->status_id == $billPayedStatus->id || $bill->payed_at) {
			return response()->json(['status' => 'error', 'reason' => 'Invoice has already been paid']);
		}
		
		$deal = $bill->deal;
		if (!$deal) {
			return response()->json(['status' => 'error', 'reason' => 'Deal not found']);
		}

		$description = '';
		if ($deal) {
			$product = $deal->product;
			if ($product) {
				$description = $product->name;
			}
		}
		
		$paymentResponse = AuthorizeNetService::payment($bill, $cardNumber, $expirationDate, $cardCode, $deal->email, $description);
		
		\Log::channel('authorize')->info($paymentResponse);
		
		if ($paymentResponse['status'] == 'error') {
			return response()->json(['status' => 'error', 'reason' => isset($paymentResponse['original']) ? $paymentResponse['original']['error_message'] : $paymentResponse['error_message']]);
		}
		
		$billData = $bill->data_json;
		$billData['payment'] = [
			'status' => $paymentResponse['status'],
			'transaction_id' => $paymentResponse['transaction_id'],
			'transaction_code' => $paymentResponse['transaction_code'],
			'message_code' => $paymentResponse['message_code'],
			'auth_code' => $paymentResponse['auth_code'],
			'description' => $paymentResponse['description'],
		];
		
		$bill->status_id = $billPayedStatus->id;
		$bill->payed_at = Carbon::now();
		$bill->data_json = $billData;
		if (!$bill->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Invoice # <b>' . $bill->number . '</b> has been successfully paid.', 'paymentResponse' => $paymentResponse]);
	}
}