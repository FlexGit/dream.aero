<?php

namespace App\Http\Controllers;

use App\Exports\CertificateExport;
use App\Models\Content;
use App\Models\Deal;
use App\Models\PaymentMethod;
use App\Services\HelpFunctions;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;
use Validator;
use App\Models\Certificate;
use App\Models\City;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Status;
use App\Repositories\CityRepository;
use Maatwebsite\Excel\Facades\Excel;

class CertificateController extends Controller
{
	private $request;
	private $cityRepo;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, CityRepository $cityRepo) {
		$this->request = $request;
		$this->cityRepo = $cityRepo;
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
		
		$city = $user->city;
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'certificate');
		
		return view('admin.certificate.index', [
			'page' => $page,
			'user' => $user,
			'city' => $city,
		]);
	}
	
	public function getListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();

		if (!$user->isAdminOrHigher()) {
			abort(404);
		}
		
		$city = $user->city;
		
		$dateFromAt = $this->request->filter_date_from_at ?? '';
		$dateToAt = $this->request->filter_date_to_at ?? '';
		$filterPaymentType = $this->request->filter_payment_type ?? '';
		$searchDoc = $this->request->search_doc ?? '';
		$id = $this->request->id ?? 0;
		$isExport = filter_var($this->request->is_export, FILTER_VALIDATE_BOOLEAN);
		
		if (!$dateFromAt && !$dateToAt) {
			$dateFromAt = Carbon::now()->subYear()->format('Y-m-d H:i:s');
			$dateToAt = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		
		$certificates = Certificate::where('created_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
			->where('created_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
			->where('city_id', $city->id)
			->latest();
		if ($searchDoc) {
			$certificates = $certificates->where('number', 'like', '%' . $searchDoc . '%');
		}
		if ($id) {
			$certificates = $certificates->where('id', '<', $id);
		}
		if (!$isExport) {
			$certificates = $certificates->limit(20);
		}
		$certificates = $certificates->get();
		
		$certificateItems = [];
		/** @var Certificate[] $certificates */
		foreach ($certificates as $certificate) {
			$deal = $certificate->deal;
			$dealBill = $deal ? $deal->firstBill() : null;
			if ($filterPaymentType && $dealBill) {
				if ($filterPaymentType == 'self_made' && $dealBill->user_id) continue;
				elseif ($filterPaymentType == 'admin_made' && !$dealBill->user_id) continue;
			}
			
			$dealProduct = $deal ? $deal->product : null;
			$dealBillStatus = ($dealBill && $dealBill->status) ? $dealBill->status : null;
			$dealBillPaymentMethod = ($dealBill && $dealBill->paymentMethod) ? $dealBill->paymentMethod : null;
			$certificateProduct = $certificate->product;
			$certificateStatus = $certificate->status ?? null;
			
			$comment = ($deal && isset($deal->data_json['comment']) && $deal->data_json['comment']) ? $deal->data_json['comment'] : '';
			
			$certificateItems[$certificate->id] = [
				'number' => $certificate->number,
				'created_at' => $certificate->created_at,
				'certificate_product_name' => $certificateProduct ? $certificateProduct->name : '',
				'deal_product_name' => $dealProduct ? $dealProduct->name : '',
				'deal_amount' => $deal ? $deal->amount : 0,
				'deal_tax' => $deal ? $deal->tax : 0,
				'deal_total_amount' => $deal ? $deal->total_amount : 0,
				'comment' => $comment,
				'expire_at' => $certificate->expire_at ? Carbon::parse($certificate->expire_at)->format('Y-m-d') : 'termless',
				'certificate_status_name' => $certificateStatus ? $certificateStatus->name : '',
				'bill_number' => $dealBill ? $dealBill->number : '',
				'bill_status_alias' => $dealBillStatus ? $dealBillStatus->alias : '',
				'bill_status_name' => $dealBillStatus ? $dealBillStatus->name : '',
				'bill_payment_method_name' => $dealBillPaymentMethod ? $dealBillPaymentMethod->name : '',
			];
		}
		
		$data = [
			'certificateItems' => $certificateItems,
			'city' => $city,
		];
		
		$reportFileName = '';
		if ($isExport) {
			$reportFileName = 'certificate-' . $user->id . '-' . date('YmdHis') . '.xlsx';
			$exportResult = Excel::store(new CertificateExport($data), 'report/' . $reportFileName);
			if (!$exportResult) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
			}
		}
		
		$VIEW = view('admin.certificate.list', $data);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW, 'fileName' => $reportFileName]);
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
		
		$certificate = Certificate::find($id);
		if (!$certificate) return response()->json(['status' => 'error', 'reason' => trans('main.error.сертификат-не-найден')]);
		
		$deal = $certificate->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		$statuses = Status::where('type', Status::STATUS_TYPE_CERTIFICATE)
			->orderBy('sort')
			->get();
		
		$VIEW = view('admin.certificate.modal.edit', [
			'certificate' => $certificate,
			'deal' => $deal,
			'statuses' => $statuses,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$certificate = Certificate::find($id);
		if (!$certificate) return response()->json(['status' => 'error', 'reason' => trans('main.error.сертификат-не-найден')]);
		
		$statuses = Status::where('type', Status::STATUS_TYPE_CERTIFICATE)
			->orderBy('sort')
			->get();
		
		$VIEW = view('admin.certificate.modal.show', [
			'certificate' => $certificate,
			'statuses' => $statuses,
		]);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
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
		
		$certificate = Certificate::find($id);
		if (!$certificate) return response()->json(['status' => 'error', 'reason' => trans('main.error.сертификат-не-найден')]);
		
		$rules = [
			'status_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'status_id' => 'Status',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$certificate->status_id = $this->request->status_id;
		if (!$certificate->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'message' => 'Voucher was successfully saved']);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendCertificate() {
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$rules = [
			'id' => 'required|numeric|min:0|not_in:0',
			'certificate_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'id' => 'Deal',
				'certificate_id' => 'Voucher',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$deal = Deal::find($this->request->id);
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		$contractor = $deal->contractor;
		if (!$contractor) return response()->json(['status' => 'error', 'reason' => trans('main.error.контрагент-не-найден')]);
		
		if (!$deal->email && !$contractor->email) return response()->json(['status' => 'error', 'reason' => 'Deal or Contractor E-mail not found']);
		
		if ($deal->balance() < 0) return response()->json(['status' => 'error', 'reason' => 'Deal balance should not be negative']);

		$certificate = Certificate::find($this->request->certificate_id);
		if (!$certificate) return response()->json(['status' => 'error', 'reason' => trans('main.error.сертификат-не-найден')]);
		
		/*$job = new \App\Jobs\SendCertificateEmail($certificate);
		$job->handle();*/
		
		$certificateFilePath = isset($certificate->data_json['certificate_file_path']) ? $certificate->data_json['certificate_file_path'] : '';
		$certificateFileExists = Storage::disk('private')->exists($certificateFilePath);
		
		// если файла сертификата по какой-то причине не оказалось, генерим его
		if (!$certificateFilePath || !$certificateFileExists) {
			$certificate = $certificate->generateFile();
			if (!$certificate) return response()->json(['status' => 'error', 'reason' => 'Certificate File generation failed']);
		}
		
		$contractor = $deal->contractor;
		if (!$contractor) return null;
		
		$dealEmail = $deal->email ?? '';
		$dealName = $deal->name ?? '';
		$dealCity = $deal->city;
		$contractorEmail = $contractor->email ?? '';
		$contractorName = $contractor->name ?? '';
		if (!$dealEmail && !$contractorEmail) return response()->json(['status' => 'error', 'reason' => 'Deal or Contractor E-mail not found']);
		
		$city = $certificate->city;
		$certificateRulesFileName = 'RULES_' . mb_strtoupper($city->alias) . '.jpg';
		
		$messageData = [
			'certificate' => $certificate,
			'name' => $dealName ?: $contractorName,
			'city' => $dealCity ?? null,
		];
		
		$recipients = $bcc = [];
		$recipients[] = $dealEmail ?: $contractorEmail;
		if ($dealCity && $dealCity->email) {
			$bcc[] = $dealCity->email;
		}
		
		$subject = env('APP_NAME') . ': Flight Voucher';
		
		Mail::send(['html' => "admin.emails.send_certificate"], $messageData, function ($message) use ($subject, $recipients, $certificateRulesFileName, $bcc, $certificateFilePath) {
			/** @var \Illuminate\Mail\Message $message */
			$message->subject($subject);
			$message->attach(Storage::disk('private')->path($certificateFilePath));
			$message->attach(Storage::disk('private')->path('rule/' . $certificateRulesFileName));
			$message->attach(Storage::disk('private')->path('rule/RULES_MAIN.jpg'));
			$message->to($recipients);
			$message->bcc($bcc);
		});
		
		$failures = Mail::failures();
		if ($failures) {
			\Log::debug($failures);
			return null;
		}
		
		$sentAt = Carbon::now()->format('Y-m-d H:i:s');
		$certificate->sent_at = $sentAt;
		$certificate->save();
		
		return response()->json(['status' => 'success', 'message' => 'Flight Voucher was successfully sent', 'sent_at' => $sentAt]);
	}
	
	/**
	 * @param $uuid
	 * @return \never|\Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function getCertificateFile($uuid)
	{
		$certificate = HelpFunctions::getEntityByUuid(Certificate::class, $uuid);
		if (!$certificate) {
			abort(404);
		}
		
		//$certificateFilePath = isset($certificate->data_json['certificate_file_path']) ? $certificate->data_json['certificate_file_path'] : '';
		//$certificateFileExists = Storage::disk('private')->exists($certificateFilePath);

		// если файла сертификата по какой-то причине не оказалось, генерим его
		//if (!$certificateFilePath || !$certificateFileExists) {
			$certificate = $certificate->generateFile();
			if (!$certificate) {
				abort(404);
			}
		//}
		
		$certificateFilePath = (is_array($certificate->data_json) && array_key_exists('certificate_file_path', $certificate->data_json)) ? $certificate->data_json['certificate_file_path'] : '';
		
		return Storage::disk('private')->download($certificateFilePath);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function search() {
		$q = $this->request->post('query');
		if (!$q) return response()->json(['status' => 'error', 'reason' => trans('main.error.нет-данных')]);
		
		$certificates = Certificate::where('number', 'like', '%' . $q . '%')
			->orderBy('number')
			->limit(env('LIST_LIMIT'))
			->get();
		
		$suggestions = [];
		/** @var Certificate[] $certificates */
		foreach ($certificates as $certificate) {
			$product = $certificate->product;
			$city = $certificate->city;
			$status = $certificate->status;
			
			$certificateInfo = $certificate->created_at->format('m-d-Y') . ($certificate->expire_at ? ' till ' . $certificate->expire_at->format('m-d-Y') : ' - termless') . ($product ? ' - ' . $product->duration . ' min (' . $product->name . ')' : '') . ($city ? '. ' . $city->name : '') . ($status ? '. ' . $status->name : '');
			
			$date = date('Y-m-d');
			
			$suggestions[] = [
				'value' => $certificate->number . ' [' . $certificateInfo . ']',
				'id' => $certificate->uuid,
				'data' => [
					'number' => $certificate->number,
					'is_overdue' => ($certificate->expire_at && Carbon::parse($certificate->expire_at)->lt($date)) ? true : false,
				],
			];
		}
		
		return response()->json(['suggestions' => $suggestions]);
	}
}
