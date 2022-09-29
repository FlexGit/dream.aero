<?php

namespace App\Http\Controllers;

use App\Exports\CashFlowReportExport;
use App\Exports\ContractorSelfMadePayedDealsReportExport;
use App\Exports\TipsReportExport;
use App\Models\Bill;
use App\Models\City;
use App\Models\Content;
use App\Models\Deal;
use App\Models\Event;
use App\Models\Operation;
use App\Models\OperationType;
use App\Models\PaymentMethod;
use App\Models\ProductType;
use App\Models\Tip;
use App\Models\User;
use App\Repositories\CityRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductTypeRepository;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Services\HelpFunctions;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller {
	private $request;
	private $cityRepo;
	private $paymentRepo;
	private $productTypeRepo;
	
	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, CityRepository $cityRepo, PaymentRepository $paymentRepo, ProductTypeRepository $productTypeRepo) {
		$this->request = $request;
		$this->cityRepo = $cityRepo;
		$this->paymentRepo = $paymentRepo;
		$this->productTypeRepo = $productTypeRepo;
	}
	
	public function personalSellingIndex()
	{
		$user = Auth::user();
		
		if (!$user->isAdminOrHigher()) {
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
		
		//\DB::connection()->enableQueryLog();
		$bills = Bill::where('user_id', '!=', 0)
			->where('city_id', $city->id)
			->where(function ($query) use ($dateFromAt, $dateToAt) {
				$query->where('created_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
					->orWhere('payed_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'));
			})
			->where(function ($query) use ($dateFromAt, $dateToAt) {
				$query->where('created_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
					->orWhere('payed_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'));
			})
			->orderBy('created_at')
			->orderBy('payed_at')
			->get();
		if ($user->isAdmin()) {
			$bills = $bills->where('user_id', $user->id);
		}
		//\Log::debug(\DB::getQueryLog());
		
		$totalItems = $billItems = $paymentMethodSumItems = $dealIds = [];
		$totalSum = $i = 0;
		foreach ($bills as $bill) {
			if ($bill->payed_at && !Carbon::parse($bill->payed_at)->between(Carbon::parse($dateFromAt)->startOfDay(), Carbon::parse($dateToAt)->endOfDay())) {
				continue;
			}
			
			//\Log::debug($bill->number . ' - ' . $bill->user_id);
			
			$deal = $bill->deal;
			
			$billItems[$bill->user_id][$i] = [
				'bill_number' => $bill->number,
				'bill_status' => $bill->status ? $bill->status->name : '-',
				'bill_amount' => $bill->total_amount,
				'bill_payed_at' => $bill->payed_at ? $bill->payed_at->format('m/d/Y g:i A') : '-',
				/*'bill_location' => $bill->location ? $bill->location->name : '-',*/
				'deal_number' => $deal->number,
				'deal_status' => $deal->status ? $deal->status->name : '-',
				'bill_payment_method' => $bill->paymentMethod ? $bill->paymentMethod->name : '-',
			];
			
			if (!isset($totalItems[$bill->user_id])) {
				$totalItems[$bill->user_id] = [
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
			++$totalItems[$bill->user_id]['bill_count'];
			// сумма счетов
			$totalItems[$bill->user_id]['bill_sum'] += $bill->total_amount;
			if ($bill->status && $bill->status->alias == Bill::PAYED_STATUS) {
				// кол-во оплаченных счетов
				++$totalItems[$bill->user_id]['payed_bill_count'];
				// сумма оплаченных счетов
				$totalItems[$bill->user_id]['payed_bill_sum'] += $bill->total_amount;
				// сумма оплаченных счетов конкретного способа оплаты
				$paymentMethodSumItems[$bill->payment_method_id] += $bill->total_amount;
				$totalSum += $bill->total_amount;
			}
			if ($deal && !in_array($bill->deal_id, $totalItems[$bill->user_id]['deal_ids'])) {
				$totalItems[$bill->user_id]['deal_ids'][] = $deal->id;
				// кол-во сделок
				++$totalItems[$bill->user_id]['deal_count'];
				// сумма сделок
				$totalItems[$bill->user_id]['deal_sum'] += $deal->totalAmount();
			}
			
			++$i;
		}
		
		//\Log::debug($billItems);
		
		$shiftItems = [];
		$shifts = Event::where('event_type', Event::EVENT_TYPE_SHIFT_ADMIN)
			->where('city_id', $city->id)
			->where('start_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
			->where('start_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
			->get();
		if ($user->isAdmin()) {
			$shifts = $shifts->where('user_id', $user->id);
		}
		foreach ($shifts as $shift) {
			if (!isset($shiftItems[$shift->user_id])) {
				$shiftItems[$shift->user_id] = 0;
			}
			++$shiftItems[$shift->user_id];
		}
		
		$userItems = [];
		$users = User::where('enable', true)
			->where('city_id', $city->id)
			->where('role', User::ROLE_ADMIN, User::ROLE_SUPERADMIN)
			->orderBy('lastname')
			->orderBy('name')
			->orderBy('middlename')
			->get();
		if ($user->isAdmin()) {
			$users = $users->where('id', $user->id);
		}
		foreach ($users as $user) {
			$userItems[] = [
				'id' => $user->id,
				'fio' => $user->fioFormatted(),
				'role' => User::ROLES[$user->role],
				'city_name' => $user->city ? $user->city->name : '',
			];
		}
		
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(false);
		
		$data = [
			'billItems' => $billItems,
			'totalItems' => $totalItems,
			'paymentMethodSumItems' => $paymentMethodSumItems,
			'totalSum' => $totalSum,
			'shiftItems' => $shiftItems,
			'userItems' => $userItems,
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
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function cashFlowIndex()
	{
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			abort(404);
		}
		
		$types = OperationType::where('is_active', true)
			->orderBy('name')
			->get();
		$products = $this->productTypeRepo->getActualProductList($user, true);
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(true);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'report-cash-flow');
		
		return view('admin.report.cash-flow.index', [
			'types' => $types,
			'products' => $products,
			'paymentMethods' => $paymentMethods,
			'page' => $page,
		]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
	 */
	public function cashFlowGetListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		$location = $user->location;
		
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(true);
		
		$dateFromAt = $this->request->filter_date_from_at ?? '';
		$dateToAt = $this->request->filter_date_to_at ?? '';
		$paymentMethodId = $this->request->filter_payment_method_id ?? 0;
		$operationType = $this->request->filter_operation_type ?? '';
		$productId = $this->request->filter_product_id ?? 0;
		$operationTypeId = $this->request->filter_operation_type_id ?? 0;
		$isExport = filter_var($this->request->is_export, FILTER_VALIDATE_BOOLEAN);
		
		if (!$dateFromAt && !$dateToAt) {
			$dateFromAt = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
			$dateToAt = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		
		$carbonDays = CarbonPeriod::create($dateFromAt, $dateToAt)->toArray();
		
		$days = $periods = [];
		foreach ($carbonDays as $carbonDay) {
			$days[] = $carbonDay->format('Y-m-d');
			$periods[] = $carbonDay->format('Y') . '-' . $carbonDay->format('m');
		}
		$periods = array_unique($periods);
		
		$months = [
			'01' => 'Jan',
			'02' => 'Feb',
			'03' => 'Mar',
			'04' => 'Apr',
			'05' => 'May',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Aug',
			'09' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec',
		];
		
		$items = [];
		
		//\DB::connection()->enableQueryLog();
		if (!$operationType || $operationType == 'expenses') {
			// операции
			$operations = Operation::orderBy('operated_at')
				->where('operated_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
				->where('operated_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
				->where('city_id', $city->id)
				->where('location_id', $location->id);
			if ($paymentMethodId) {
				$operations = $operations->where('payment_method_id', $paymentMethodId);
			}
			if ($operationType) {
				if ($operationTypeId) {
					$operations = $operations->where('operation_type_id', $operationTypeId);
				} else {
					$operations = $operations->has('operationType');
				}
			}
			$operations = $operations->get();
			foreach ($operations as $operation) {
				/** @var Operation $operation */
				$items[Carbon::parse($operation->operated_at)->format('Ym')][Carbon::parse($operation->operated_at)->endOfDay()->timestamp][] = [
					'type' => 'Expenses',
					'payment_method' => $operation->paymentMethod ? $operation->paymentMethod->name : '',
					'amount' => $operation->amount,
					'currency' => $operation->currency ? $operation->currency->name : '',
					'extra' => $operation->operationType ? $operation->operationType->name : '' . isset($operation->data_json['comment']) ? ' ' . $operation->data_json['comment'] : '',
				];
			}
		}
		
		if (!$operationType || in_array($operationType, ['deals', 'taxes'])) {
			// сделки
			$deals = Deal::oldest()
				->where('created_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
				->where('created_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
				->where('city_id', $city->id)
				->where('location_id', $location->id);
			if ($paymentMethodId) {
				$deals = $deals->whereHas('bills', function ($query) use ($paymentMethodId) {
					return $query->where('bills.payment_method_id', $paymentMethodId);
				});
			}
			if ($operationType) {
				if ($operationType == 'taxes') {
					$deals = $deals->whereHas('product', function ($query) {
						return $query->whereRelation('productType', 'product_types.alias', '=', 'tax');
					});
				} elseif ($operationType == 'deals') {
					if ($productId) {
						$deals = $deals->where('product_id', $productId);
					}
					else {
						$deals = $deals->has('product');
					}
				}
			}
			$deals = $deals->get();
			foreach ($deals as $deal) {
				/** @var Deal $deal */
				if (!$deal->total_amount) continue;
				if ($deal->balance() < 0) continue;
				
				$product = $deal->product;
				$productType = $product ? $product->productType : null;
				$promo = $deal->promo;
				$promocode = $deal->promocode;
				$bills = $deal->bills;
				
				$extra = [];
				/*$extra[] = $isExport ? $deal->number : '<a href="' . url('deal/' . $deal->id) . '" target="_blank">' . $deal->number . '</a>';*/
				$extra[] = $deal->is_certificate_purchase ? 'Voucher' : ($deal->certificate ? 'Flight by Voucher' : 'Flight');
				$extra[] = $deal->certificate ? $deal->certificate->number : '';
				$extra[] = $product->name;
				$extra[] = $promo ? ($promo->name . ($promo->discount ? ' ' . $promo->discount->valueFormatted() : '')) : '';
				$extra[] = $promocode ? ($promocode->number . ($promocode->discount ? ' ' . $promocode->discount->valueFormatted() : '')) : '';
				
				$paymentMethodNames = [];
				foreach ($bills as $bill) {
					/** @var Bill $bill */
					$paymentMethodNames[] = $bill->paymentMethod ? $bill->paymentMethod->name : '';
				}
				$paymentMethodNames = array_unique($paymentMethodNames);
				
				$items[Carbon::parse($deal->created_at)->format('Ym')][Carbon::parse($deal->created_at)->endOfDay()->timestamp][] = [
					'type' => ($productType && $productType->alias == ProductType::TAX_ALIAS) ? 'Tax' : 'Deal',
					'payment_method' => implode(' / ', $paymentMethodNames),
					'amount' => $deal->total_amount,
					'currency' => $deal->currency ? $deal->currency->name : '',
					'extra' => implode(' ', array_filter($extra)),
				];
			}
		}

		if (!$operationType || $operationType == 'tips') {
			// типсы
			$tips = Tip::orderBy('received_at')
				->where('received_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
				->where('received_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
				->where('city_id', $city->id)
				->where('location_id', $location->id);
			if ($paymentMethodId) {
				$tips = $tips->where('payment_method_id', $paymentMethodId);
			}
			$tips = $tips->get();
			foreach ($tips as $tip) {
				/** @var Tip $tip */
				$items[Carbon::parse($tip->received_at)->format('Ym')][Carbon::parse($tip->received_at)->endOfDay()->timestamp][] = [
					'type' => 'Tips',
					'payment_method' => $tip->paymentMethod ? $tip->paymentMethod->name : '',
					'amount' => $tip->amount,
					'currency' => $tip->currency ? $tip->currency->name : '',
					'extra' => '',
				];
			}
		}
		
		ksort($items);
		array_walk($items, 'ksort');
		
		$balanceItems = [];
		foreach ($paymentMethods as $paymentMethod) {
			if ($paymentMethodId && $paymentMethodId != $paymentMethod->id) continue;
			
			$balanceItems[Carbon::parse($dateFromAt)->endOfDay()->timestamp][$paymentMethod->alias] = $this->getBalanceOnDate(Carbon::parse($dateFromAt)->endOfDay()->timestamp, $paymentMethod->alias, $operationType, $operationTypeId, $productId);
			$balanceItems[Carbon::parse($dateToAt)->endOfDay()->timestamp][$paymentMethod->alias] = $this->getBalanceOnDate(Carbon::parse($dateToAt)->endOfDay()->timestamp, $paymentMethod->alias, $operationType, $operationTypeId, $productId);
		}
		//\Log::debug(\DB::getQueryLog());
		
		$data = [
			'items' => $items,
			'balanceItems' => $balanceItems,
			'paymentMethods' => $paymentMethods,
			'dateFromAtTimestamp' => Carbon::parse($dateFromAt)->endOfDay()->timestamp,
			'dateToAtTimestamp' => Carbon::parse($dateToAt)->endOfDay()->timestamp,
			'currency' => $city->currency ? $city->currency->name : '',
			'days' => $days,
			'months' => $months,
			'periods' => $periods,
		];
		
		//\Log::debug($data);
		
		$reportFileName = '';
		if ($isExport) {
			$reportFileName = 'report-cash-flow-' . $user->id . '-' . date('YmdHis') . '.xlsx';
			$exportResult = Excel::store(new CashFlowReportExport($data), 'report/' . $reportFileName);
			if (!$exportResult) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
			}
		}

		$VIEW = view('admin.report.cash-flow.list', $data);
		
		return response()->json(['status' => 'success', 'html' => (string)$VIEW, 'fileName' => $reportFileName]);
	}
	
	/**
	 * @param $date
	 * @param string $paymentMethodAlias
	 * @return mixed
	 */
	public function getBalanceOnDate($timestamp, $paymentMethodAlias, $operationType, $operationTypeId = 0, $productId = 0)
	{
		$user = Auth::user();
		$city = $user->city;
		$location = $user->location;
		
		$billSum = 0;
		if (!$operationType || in_array($operationType, ['deals', 'taxes'])) {
			// инвойсы
			$billSum = Bill::where('payed_at', '<', Carbon::parse($timestamp)->startOfDay())
				->where('city_id', $city->id)
				->where('location_id', $location->id)
				->whereRelation('status', 'statuses.alias', '=', Bill::PAYED_STATUS)
				->whereRelation('paymentMethod', 'payment_methods.alias', '=', $paymentMethodAlias)
				->whereHas('deal', function ($query) {
					return $query->whereRelation('status', 'statuses.alias', '=', Deal::CONFIRMED_STATUS);
				});
			if ($operationType) {
				if ($operationType == 'taxes') {
					$billSum = $billSum->whereHas('deal', function ($query) use ($operationType) {
						return $query->whereHas('product', function ($query) use ($operationType) {
							return $query->whereRelation('productType', 'product_types.alias', '=', $operationType);
						});
					});
				} else if ($operationType == 'deals') {
					if ($productId) {
						$billSum = $billSum->whereHas('deal', function ($query) use ($productId) {
							return $query->where('product_id', $productId);
						});
					}
					else {
						$billSum = $billSum->whereHas('deal', function ($query) {
							return $query->has('product');
						});
					}
				}
			}
			$billSum = $billSum->sum('total_amount');
		}
		
		$operationSum = 0;
		if (!$operationType || $operationType == 'expenses') {
			//операции
			$operationSum = Operation::where('operated_at', '<', Carbon::parse($timestamp)->startOfDay())
				->where('city_id', $city->id)
				->where('location_id', $location->id)
				->whereRelation('paymentMethod', 'payment_methods.alias', '=', $paymentMethodAlias);
			if ($operationType) {
				if ($operationTypeId) {
					$operationSum = $operationSum->where('operation_type_id', $operationTypeId);
				} else {
					$operationSum = $operationSum->has('operationType');
				}
			}
			$operationSum = $operationSum->sum('amount');
		}
		
		$tipSum = 0;
		if (!$operationType || $operationType == 'tips') {
			// типсы
			$tipSum = Tip::where('received_at', '<', Carbon::parse($timestamp)->startOfDay())
				->where('city_id', $city->id)
				->where('location_id', $location->id)
				->whereRelation('paymentMethod', 'payment_methods.alias', '=', $paymentMethodAlias)
				->sum('amount');
		}
		
		return $billSum + $operationSum + $tipSum;
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function tipsIndex()
	{
		$user = Auth::user();
		
		if (!$user->isSuperAdmin()) {
			abort(404);
		}
		
		$city = $user->city;
		$users = User::where('enable', true)
			->where('city_id', $city->id)
			->whereIn('role', [User::ROLE_ADMIN, User::ROLE_PILOT])
			->get();
		$paymentMethods = $this->paymentRepo->getPaymentMethodList(true);
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'report-tips');
		
		return view('admin.report.tips.index', [
			'users' => $users,
			'paymentMethods' => $paymentMethods,
			'page' => $page,
		]);
	}
	
	public function tipsGetListAjax()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		$city = $user->city;
		$location = $user->location;
		$currency = $city ? $city->currency : null;
		
		$dateFromAt = $this->request->filter_date_from_at ?? '';
		$dateToAt = $this->request->filter_date_to_at ?? '';
		$isExport = filter_var($this->request->is_export, FILTER_VALIDATE_BOOLEAN);
		
		if (!$dateFromAt && !$dateToAt) {
			$dateFromAt = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
			$dateToAt = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		}
		
		$items = [];
		$tips = Tip::orderBy('received_at')
			->where('received_at', '>=', Carbon::parse($dateFromAt)->startOfDay()->format('Y-m-d H:i:s'))
			->where('received_at', '<=', Carbon::parse($dateToAt)->endOfDay()->format('Y-m-d H:i:s'))
			->where('city_id', $city->id)
			->where('location_id', $location->id)
			->get();
		foreach ($tips as $tip) {
			/** @var Tip $tip */
			if (!isset($items[$tip->admin_id])) $items[$tip->admin_id] = 0;
			if (!isset($items[$tip->pilot_id])) $items[$tip->pilot_id] = 0;
			$items[$tip->admin_id] += $tip->amount / 2;
			$items[$tip->pilot_id] += $tip->amount / 2;
		}
		
		$users = User::where('city_id', $city->id)
			->whereIn('role', [User::ROLE_ADMIN, User::ROLE_PILOT])
			->get();
		$userItems = [];
		foreach ($users as $user) {
			$userItems[$user->id]['fio'] = $user->fio();
			$userItems[$user->id]['role'] = isset(User::ROLES[$user->role]) ? User::ROLES[$user->role] : '';
		}
		
		$data = [
			'items' => $items,
			'userItems' => $userItems,
			'currency' => $currency,
		];
		
		$reportFileName = '';
		if ($isExport) {
			$reportFileName = 'report-tips-' . $user->id . '-' . date('YmdHis') . '.xlsx';
			$exportResult = Excel::store(new TipsReportExport($data), 'report/' . $reportFileName);
			if (!$exportResult) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
			}
		}
		
		$VIEW = view('admin.report.tips.list', $data);
		
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