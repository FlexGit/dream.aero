<?php

namespace App\Http\Controllers;

use App\Exports\ContractorSelfMadePayedDealsReportExport;
use App\Models\Bill;
use App\Models\City;
use App\Models\Content;
use App\Models\Event;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Repositories\CityRepository;
use App\Repositories\PaymentRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\HelpFunctions;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller {
	private $request;
	private $cityRepo;
	private $paymentRepo;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, CityRepository $cityRepo, PaymentRepository $paymentRepo) {
		$this->request = $request;
		$this->cityRepo = $cityRepo;
		$this->paymentRepo = $paymentRepo;
	}
	
	public function personalSellingIndex()
	{
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			abort(404);
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'report-personal-selling');
		
		return view('admin.report.personal-selling.index', [
			'page' => $page,
		]);
	}
	
	public function personalSellingGetListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		
		$dateFromAt = $this->request->filter_date_from_at ?? '';
		$dateToAt = $this->request->filter_date_to_at ?? '';
		$isExport = filter_var($this->request->is_export, FILTER_VALIDATE_BOOLEAN);
		
		if (!$dateFromAt && !$dateToAt) {
			$dateFromAt = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
			$dateToAt = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		
		$bills = Bill::where('user_id', '!=', 0)
			->where('created_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
			->where('created_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
			//->whereRelation('status', 'statuses.alias', '=', Bill::PAYED_STATUS)
			->get();
		
		$billItems = $paymentMethodSumItems = $dealIds = [];
		$totalSum = 0;
		foreach ($bills as $bill) {
			if (!isset($billItems[$bill->location_id])) {
				$billItems[$bill->location_id] = [];
			}
			if (!isset($billItems[$bill->location_id][$bill->user_id])) {
				$billItems[$bill->location_id][$bill->user_id] = [
					'bill_count' => 0,
					'bill_sum' => 0,
					'payed_bill_count' => 0,
					'payed_bill_sum' => 0,
					'deal_ids' => [],
					'deal_count' => 0,
					'deal_sum' => 0,
				];
			}
			if (!isset($paymentMethodSumItems[$bill->payment_method_id])) {
				$paymentMethodSumItems[$bill->payment_method_id] = 0;
			}
			
			// кол-во счетов
			++$billItems[$bill->location_id][$bill->user_id]['bill_count'];
			// сумма счетов
			$billItems[$bill->location_id][$bill->user_id]['bill_sum'] += $bill->total_amount;
			if ($bill->status && $bill->status->alias == Bill::PAYED_STATUS) {
				// кол-во оплаченных счетов
				++$billItems[$bill->location_id][$bill->user_id]['payed_bill_count'];
				// сумма оплаченных счетов
				$billItems[$bill->location_id][$bill->user_id]['payed_bill_sum'] += $bill->total_amount;
				// сумма оплаченных счетов конкретного способа оплаты
				$paymentMethodSumItems[$bill->payment_method_id] += $bill->total_amount;
				$totalSum += $bill->total_amount;
			}
			$deal = $bill->deal;
			if ($deal && !in_array($bill->deal_id, $billItems[$bill->location_id][$bill->user_id]['deal_ids'])) {
				$billItems[$bill->location_id][$bill->user_id]['deal_ids'][] = $deal->id;
				// кол-во сделок
				++$billItems[$bill->location_id][$bill->user_id]['deal_count'];
				// сумма сделок
				$billItems[$bill->location_id][$bill->user_id]['deal_sum'] += $deal->totalAmount();
			}
		}
		
		$shiftItems = [];
		$shifts = Event::where('event_type', Event::EVENT_TYPE_SHIFT_ADMIN)
			->where('start_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
			->where('start_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
			->get();
		foreach ($shifts as $shift) {
			if (!isset($shiftItems[$shift->user_id])) {
				$shiftItems[$shift->user_id] = 0;
			}
			++$shiftItems[$shift->user_id];
		}
		
		$userItems = [];
		$users = User::where('enable', true)
			->where('role', User::ROLE_ADMIN)
			->orderBy('lastname')
			->orderBy('name')
			->orderBy('middlename')
			->get();
		foreach ($users as $user) {
			if (!$user->location_id) continue;
			
			$userItems/*[$user->location_id]*/[] = [
				'id' => $user->id,
				'fio' => $user->fioFormatted(),
				'role' => User::ROLES[$user->role],
			];
		}
		
		$cities = $this->cityRepo->getList($this->request->user());
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		$data = [
			'billItems' => $billItems,
			'paymentMethodSumItems' => $paymentMethodSumItems,
			'totalSum' => $totalSum,
			'shiftItems' => $shiftItems,
			'userItems' => $userItems,
			'cities' => $cities,
			'paymentMethods' => $paymentMethods,
			'currencyName' => $city->currency ? $city->currency->name : '',
		];
		
		/*$reportFileName = '';
		if ($isExport) {
			$reportFileName = 'report-personal-selling-' . $user->id . '-' . date('YmdHis') . '.xlsx';
			$exportResult = Excel::store(new PersonalSellingReportExport($data), 'report/' . $reportFileName);
			if (!$exportResult) {
				return response()->json(['status' => 'error', 'reason' => 'В данный момент невозможно выполнить операцию, повторите попытку позже!']);
			}
		}*/
		
		$VIEW = view('admin.report.personal-selling.list', $data);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW/*, 'fileName' => $reportFileName*/]);
	}
	
	public function unexpectedRepeatedIndex()
	{
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			abort(404);
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'report-unexpected-repeated');
		
		return view('admin.report.unexpected-repeated.index', [
			'page' => $page,
		]);
	}
	
	public function unexpectedRepeatedGetListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		
		$dateFromAt = $this->request->filter_date_from_at ?? '';
		$dateToAt = $this->request->filter_date_to_at ?? '';
		$isExport = filter_var($this->request->is_export, FILTER_VALIDATE_BOOLEAN);
		
		if (!$dateFromAt && !$dateToAt) {
			$dateFromAt = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
			$dateToAt = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		
		$events = Event::where('event_type', Event::EVENT_TYPE_DEAL)
			->where('created_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
			->where('created_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
			//->whereRelation('status', 'statuses.alias', '=', Bill::PAYED_STATUS)
			->get();
		
		$eventItems = [];
		foreach ($events as $event) {
			if (!isset($eventItems[$event->location_id])) {
				$eventItems[$event->location_id] = [];
			}
			if (!isset($eventItems[$event->location_id][$event->flight_simulator_id])) {
				$eventItems[$event->location_id][$event->flight_simulator_id] = [
					'is_unexpected_flight' => 0,
					'is_repeated_flight' => 0,
				];
			}
			
			if ($event->is_unexpected_flight) {
				++$eventItems[$event->location_id][$event->flight_simulator_id]['is_unexpected_flight'];
			}
			if ($event->is_repeated_flight) {
				++$eventItems[$event->location_id][$event->flight_simulator_id]['is_repeated_flight'];
			}
		}
		
		//$cities = $this->cityRepo->getList($this->request->user());
		$cities = City::where('id', $city->id)
			->get();
		
		$data = [
			'eventItems' => $eventItems,
			'cities' => $cities,
		];
		
		/*$reportFileName = '';
		if ($isExport) {
			$reportFileName = 'report-personal-selling-' . $user->id . '-' . date('YmdHis') . '.xlsx';
			$exportResult = Excel::store(new PersonalSellingReportExport($data), 'report/' . $reportFileName);
			if (!$exportResult) {
				return response()->json(['status' => 'error', 'reason' => 'В данный момент невозможно выполнить операцию, повторите попытку позже!']);
			}
		}*/
		
		$VIEW = view('admin.report.unexpected-repeated.list', $data);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW/*, 'fileName' => $reportFileName*/]);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function contractorSelfMadePayedDealsIndex()
	{
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			abort(404);
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'report-contractor-self-made-payed-deals');
		
		return view('admin.report.contractor-self-made-payed-deals.index', [
			'page' => $page,
		]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
	 */
	public function contractorSelfMadePayedDealsGetListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		
		$dateFromAt = $this->request->filter_date_from_at ?? '';
		$dateToAt = $this->request->filter_date_to_at ?? '';
		$isExport = filter_var($this->request->is_export, FILTER_VALIDATE_BOOLEAN);
		
		if (!$dateFromAt && !$dateToAt) {
			$dateFromAt = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
			$dateToAt = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		//\DB::connection()->enableQueryLog();
		$bills = Bill::where('user_id', 0)
			->whereRelation('paymentMethod', 'payment_methods.alias', '=', PaymentMethod::ONLINE_ALIAS)
			->whereRelation('status', 'statuses.alias', '=', Bill::PAYED_STATUS)
			->where('payed_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
			->where('payed_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
			->get();
		//\Log::debug(\DB::getQueryLog());
		$items = [];
		foreach ($bills as $bill) {
			$deal = $bill->deal;
			if ($deal->bills->count() > 1) continue;
			
			$contractor = $bill->contractor;
			if ($contractor && $contractor->email == env('DEV_EMAIL')) continue;
			
			if (!isset($items[$bill->location_id])) {
				$items[$bill->location_id] = [
					'bill_count' => 0,
					'bill_amount_sum' => 0,
				];
			}
			++$items[$bill->location_id]['bill_count'];
			$items[$bill->location_id]['bill_amount_sum'] += $bill->total_amount;
		}

		$cities = City::where('id', $city->id)
			->get();
		
		$data = [
			'items' => $items,
			'cities' => $cities,
		];
		
		$reportFileName = '';
		if ($isExport) {
			$reportFileName = 'report-contractor-self-made-payed-deals-' . $user->id . '-' . date('YmdHis') . '.xlsx';
			$exportResult = Excel::store(new ContractorSelfMadePayedDealsReportExport($data), 'report/' . $reportFileName);
			if (!$exportResult) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
			}
		}
		
		$VIEW = view('admin.report.contractor-self-made-payed-deals.list', $data);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW, 'fileName' => $reportFileName]);
	}
	
	/**
	 * @param $fileName
	 * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function getExportFile($fileName)
	{
		$user = Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		return Storage::disk('private')->download('report/' . $fileName);
	}
}