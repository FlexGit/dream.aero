<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\PaymentMethod;
use App\Models\Deal;
use App\Models\ProductType;
use App\Models\Status;
use App\Services\HelpFunctions;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;
use Storage;
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
		
		$deal = $bill->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		$product = $deal->product;
		if (!$product) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$productType = $product->productType;
		if (!$productType) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$statuses = Status::where('type', Status::STATUS_TYPE_BILL)
			->orderBy('sort')
			->get();
		
		$paymentMethods = PaymentMethod::where('is_active', true)
			->orderBy('name')
			->get();

		$VIEW = view('admin.bill.modal.edit', [
			'bill' => $bill,
			'paymentMethods' => $paymentMethods,
			'statuses' => $statuses,
			'taxRate' => $productType->tax,
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
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		$city = $deal->city;
		if (!$city) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		}
		
		$product = $deal->product;
		if (!$product) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$productType = $product->productType;
		if (!$productType) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$amount = $deal->amount() - $deal->billAmount();
		$tax = round($amount * $productType->tax / 100, 2);
		$totalAmount = round($amount + $tax, 2);
		$currency = $city->currency;
		
		$statuses = Status::where('type', Status::STATUS_TYPE_BILL)
			->where('alias', '!=', Bill::PAYED_PROCESSING_STATUS)
			->orderBy('sort')
			->get();

		$paymentMethods = PaymentMethod::where('is_active', true)
			->orderBy('name')
			->get();

		$VIEW = view('admin.bill.modal.add', [
			'deal' => $deal,
			'amount' => $amount,
			'tax' => $tax,
			'taxRate' => $productType->tax,
			'totalAmount' => $totalAmount,
			'currency' => $currency,
			'paymentMethods' => $paymentMethods,
			'statuses' => $statuses,
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

		$rules = [
			'manual_amount' => 'required|numeric|min:0|not_in:0',
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
			'status_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'manual_amount' => 'Amount',
				'payment_method_id' => 'Payment method',
				'status_id' => 'Status',
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
		
		if (!$deal->contractor) return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);
		
		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		$product = $deal->product;
		if (!$product) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$productType = $product->productType;
		if (!$productType) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$city = $deal->city;
		if (!$city) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		}
		
		$cityProduct = $product->cities()->where('cities_products.is_active', true)->find($city->id);
		if (!$cityProduct) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$paymentMethodId = $this->request->payment_method_id ?? 0;

		try {
			\DB::beginTransaction();
			
			$bill = new Bill();
			$bill->contractor_id = $deal->contractor->id ?? 0;
			$bill->deal_id = $deal->id ?? 0;
			$bill->city_id = $city->id ?? 0;
			$bill->location_id = $this->request->user()->location_id ?? 0;
			$bill->payment_method_id = $paymentMethodId;
			$bill->status_id = $this->request->status_id ?? 0;
			$bill->amount = $this->request->amount ?? 0;
			$bill->tax = $this->request->tax ?? 0;
			$bill->total_amount = $this->request->total_amount ?? 0;
			$bill->currency_id = $this->request->currency_id ?? 0;
			$bill->user_id = $this->request->user()->id;
			if ($status->alias == Bill::PAYED_STATUS) {
				$bill->payed_at = Carbon::now()->format('Y-m-d H:i:s');
				
				// при выставлении даты оплаты генерим и дату окончания срока действия сертификата,
				// если это счет на позицию покупки сертификата
				$certificate = $deal->certificate;
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
			
			if ($productType->alias == ProductType::SERVICES_ALIAS && $cityProduct && $deal->balance() >= 0) {
				$city->products()->updateExistingPivot($product->id, [
					'availability' =>  --$cityProduct->pivot->availability,
				]);
			}
			
			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();
			
			\Log::debug('500 - Bill Create: ' . $e->getMessage());
			
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Invoice was successfully created']);
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

		$bill = Bill::find($id);
		if (!$bill) return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-не-найден')]);
		
		/*$billStatus = $bill->status;
		if ($billStatus && in_array($billStatus->alias, [Bill::PAYED_STATUS, Bill::CANCELED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-в-текущем-статусе-недоступен-для-редактирования')]);
		}*/
		
		$deal = $bill->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS]) && !$user->isSuperAdmin()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		$rules = [
			'manual_amount' => 'required|numeric|min:0|not_in:0',
			'payment_method_id' => 'required|numeric|min:0|not_in:0',
			'status_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'manual_amount' => 'Amount',
				'payment_method_id' => 'Payment method',
				'status_id' => 'Status',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$status = Status::find($this->request->status_id);
		if (!$status) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.статус-не-найден')]);
		}
		
		/*if (in_array($bill->status->alias, [Bill::PAYED_STATUS, Bill::PAYED_PROCESSING_STATUS]) && in_array($bill->paymentMethod->alias, [PaymentMethod::ONLINE_ALIAS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.оплаченный-счет-со-способом-оплаты-онлайн-недоступен-для-редактирования')]);
		}*/
		
		$product = $deal->product;
		if (!$product) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$productType = $product->productType;
		if (!$productType) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$city = $deal->city;
		if (!$city) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
		}

		$cityProduct = $product->cities()->where('cities_products.is_active', true)->find($city->id);
		if (!$cityProduct) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
		}
		
		$certificate = $deal->certificate;
		
		try {
			\DB::beginTransaction();
			
			$bill->payment_method_id = $this->request->payment_method_id ?? 0;
			$bill->amount = $this->request->amount ?? 0;
			$bill->tax = $this->request->tax ?? 0;
			$bill->total_amount = $this->request->total_amount ?? 0;
			$bill->status_id = $this->request->status_id ?? 0;
			if ($status->alias == Bill::PAYED_STATUS && !$bill->payed_at) {
				$bill->payed_at = Carbon::now()->format('Y-m-d H:i:s');
				
				// при выставлении даты оплаты генерим и дату окончания срока действия сертификата,
				// если это счет на позицию покупки сертификата
				if ($certificate) {
					$certificatePeriod = ($productType->alias == ProductType::COURSES_ALIAS) ? 12 : 6;
					$certificate->expire_at = Carbon::now()->addMonths($certificatePeriod)->format('Y-m-d H:i:s');
					$certificate->save();
				}
			} elseif ($status->alias == Bill::NOT_PAYED_STATUS && $bill->payed_at) {
				$bill->payed_at = null;
			}
			$bill->save();
			
			if ($productType->alias == ProductType::SERVICES_ALIAS && $cityProduct && $deal->balance() >= 0) {
				$city->products()->updateExistingPivot($product->id, [
					'availability' =>  --$cityProduct->pivot->availability,
				]);
			}

			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();
			
			\Log::debug('500 - Bill Update: ' . $e->getMessage());
			
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		
		return response()->json(['status' => 'success', 'message' => 'Invoice was successfully saved']);
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	/*public function delete($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}

		$bill = Bill::find($id);
		if (!$bill) return response()->json(['status' => 'error', 'reason' => trans('main.error.счет-не-найден')]);
		
		$billStatus = $bill->status;
		if ($billStatus && $billStatus->alias == Bill::CANCELED_STATUS) {
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
		
		return response()->json(['status' => 'success', 'message' => 'Invoice was successfully deleted']);
	}*/

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
		
		if ($bill->total_amount <= 0) return response()->json(['status' => 'error', 'reason' => trans('main.error.сумма-счета-указана-некорректно')]);
		
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
		
		/*$job = new \App\Jobs\SendPayLinkEmail($bill);
		$job->handle();*/
		
		$name = $deal->name ?: $contractor->name;
		$dealCity = $deal->city;
		
		$messageData = [
			'name' => $name,
			'city' => $city ?? null,
			'bill' => $bill,
		];
		
		$recipients = $bcc = [];
		$recipients[] = $email;
		if ($dealCity && $dealCity->email) {
			$bcc[] = $dealCity->email;
		}
		//$bcc[] = env('DEV_EMAIL');
		
		$subject = env('APP_NAME') . ': paylink';
		
		Mail::send('admin.emails.send_paylink', $messageData, function ($message) use ($recipients, $subject, $bcc) {
			/** @var \Illuminate\Mail\Message $message */
			$message->subject($subject);
			$message->to($recipients);
			$message->bcc($bcc);
		});
		
		$failures = Mail::failures();
		if ($failures) {
			return null;
		}
		
		$sentAt = Carbon::now()->format('Y-m-d H:i:s');
		
		$bill->link_sent_at = $sentAt;
		$bill->save();
		
		return response()->json(['status' => 'success', 'message' => 'Paylink was successfully sent', 'sent_at' => $sentAt]);
	}
	
	/**
	 * @param $uuid
	 * @param bool $print
	 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function getReceiptFile($uuid, $print = false)
	{
		$bill = HelpFunctions::getEntityByUuid(Bill::class, $uuid);
		if (!$bill) {
			abort(404);
		}
		
		$receiptFileName = $bill->number . '.pdf';

		$pdf = $bill->generateReceiptFile($receiptFileName);
		
		if ($print) {
			return response()->file(storage_path('app/private/receipt/' . $receiptFileName));
		}
		
		return Storage::disk('private')->download('receipt/' . $receiptFileName);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendReceipt()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$rules = [
			'id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'id' => 'Deal',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$bill = Bill::find($this->request->id);
		if (!$bill) return response()->json(['status' => 'error', 'reason' => 'Invoice not found']);
		
		$deal = $bill->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		$city = $bill->city;
		if (!$city) return response()->json(['status' => 'error', 'reason' => 'Invoice city not found']);
		
		$contractor = $bill->contractor;
		if (!$contractor) return response()->json(['status' => 'error', 'reason' => 'Invoice contractor not found']);
		
		$dealEmail = $deal->email ?? '';
		$dealName = $deal->name ?? '';
		$contractorEmail = $contractor->email ?? '';
		$contractorName = $contractor->name ?? '';
		if (!$dealEmail && !$contractorEmail) return response()->json(['status' => 'error', 'reason' => 'Deal or Contractor E-mail not found']);
		
		$receiptFileName = $bill->number . '.pdf';
		/*if (!Storage::disk('private')->exists('/receipt/' . $receiptFileName)) {*/
			$pdf = $bill->generateReceiptFile($receiptFileName);
		/*}*/
		
		$messageData = [
			'bill' => $bill,
			'name' => $dealName ?: $contractorName,
			'city' => $city,
		];
		
		$recipients = $bcc = [];
		$recipients[] = $dealEmail ?: $contractorEmail;
		if ($city->email) {
			$bcc[] = $city->email;
		}
		
		$subject = env('APP_NAME') . ': Invoice #' . $bill->number . ' Receipt';
		
		Mail::send(['html' => "admin.emails.send_invoice_receipt"], $messageData, function ($message) use ($subject, $recipients, $bcc, $receiptFileName) {
			/** @var \Illuminate\Mail\Message $message */
			$message->subject($subject);
			$message->attach(Storage::disk('private')->path('/receipt/' . $receiptFileName));
			$message->to($recipients);
			$message->bcc($bcc);
		});
		
		$failures = Mail::failures();
		if ($failures) {
			\Log::debug($failures);
			return null;
		}
		
		$sentAt = Carbon::now()->format('Y-m-d H:i:s');
		
		$bill->receipt_sent_at = $sentAt;
		$bill->save();
		
		return response()->json(['status' => 'success', 'message' => 'Invoice Receipt was successfully sent', 'sent_at' => $sentAt]);
	}
}
