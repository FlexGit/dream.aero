<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\City;
use App\Models\Content;
use App\Models\Deal;
use App\Models\DealPosition;
use App\Models\Event;
use App\Models\EventComment;
use App\Models\FlightSimulator;
use App\Models\Location;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Status;
use App\Models\User;
use App\Services\HelpFunctions;
use Auth;
use Carbon\Carbon;
use Doctrine\DBAL\Events;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Comment;
use Throwable;
use Validator;

class EventController extends Controller
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
	 * Календарь
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index()
	{
		$user = \Auth::user();
		
		$cities = $user->city
			? new Collection([$user->city])
			: City::orderBy('name')
			->get();
		
		$upcomingEvents = Event::where('event_type', Event::EVENT_TYPE_DEAL)
			->where('start_at', '>=', Carbon::now()->addDays(1)->startOfDay()->format('Y-m-d H:i:s'))
			->where('start_at', '<=', Carbon::now()->addDays(1)->endOfDay()->format('Y-m-d H:i:s'))
			->where('is_notified', false)
			->get();
		if (!$user->isSuperAdmin() && $user->city) {
			$upcomingEvents = $upcomingEvents->where('city_id', $user->city->id);
		}
		
		$page = HelpFunctions::getEntityByAlias(Content::class, 'calendar');
		
		return view('admin.event.index', [
			'cities' => $cities,
			'upcomingEvents' => $upcomingEvents,
			'user' => $user,
			'page' => $page,
		]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getListAjax()
	{
		$user = \Auth::user();
		
		$locations = Location::where('is_active', true)
			->with('simulators')
			->get();
		$locationData = [];
		foreach ($locations as $location) {
			foreach ($location->simulators as $simulator) {
				$locationData[$location->id][$simulator->id] = json_decode($simulator->pivot->data_json, true);
			}
		}

		$startAt = $this->request->start ?? '';
		$stopAt = $this->request->end ?? '';
		$cityId = $this->request->city_id ?? 0;
		$locationId = $this->request->location_id ?? 0;
		$simulatorId = $this->request->simulator_id ?? 0;
		
		$events = Event::whereDate('start_at', '>=', $startAt)
			->where(function ($query) use ($stopAt) {
				$query->whereDate('stop_at', '<=', $stopAt)
					->orWhereNull('stop_at');
			});
		if ($cityId) {
			$events = $events->where('city_id', $cityId);
		}
		if ($locationId) {
			$events = $events->where('location_id', $locationId);
		}
		if ($simulatorId) {
			$events = $events->where('flight_simulator_id', $simulatorId);
		}
		$events = $events
			->with(['dealPosition', 'user'])
			->get();

		$eventData = [];
		/** @var Event[] $events */
		foreach ($events as $event) {
			$data = isset($locationData[$event->location_id][$event->flight_simulator_id]) ? $locationData[$event->location_id][$event->flight_simulator_id] : [];

			$color = '#ffffff';
			$allDay = false;
			
			switch ($event->event_type) {
				case Event::EVENT_TYPE_DEAL:
					$deal = $event->deal;
					$balance = $deal ? $deal->balance() : 0;
					$position = $event->dealPosition;
					$product = $position ? $position->product : null;
					$certificate = $position ? $position->certificate : null;
					
					// контактное лицо
					$title = $deal ? $deal->name . ' ' . HelpFunctions::formatPhone($deal->phone) : '';
					// тариф
					$title .= $product ? ' ' . $product->name : '';
					// доп. время
					if ($event->extra_time) {
						$title .= '(+' . $event->extra_time . ')';
					}
					
					$amount = 0;
					$paymentMethodNames = [];
					$promo = $promocode = null;
					
					if ($certificate) {
						$certificateData = $certificate->data_json;

						// Сертификаты из старой системы
						if (isset($certificateData['amount'])) {
							$amount = $certificateData['amount'];
						} else {
							$certificatePurchasePosition = DealPosition::where('is_certificate_purchase', true)
								->where('certificate_id', $certificate->id)
								->first();
							if ($certificatePurchasePosition) {
								$amount = $certificatePurchasePosition->amount;
							}
						}
						
						// инфа о Сертификате
						$title .= '. Voucher: ' . $certificate->number . ' ' . Carbon::parse($certificate->created_at)->format('d.m.Y') . ' = ' . ($amount ?? '-') . '$' . (isset($certificateData['payment_method']) ? ' (' . $certificateData['payment_method'] . ')' : '');
					}
					
					if ($position) {
						$promo = $position->promo;
						$promocode = $position->promocode;
					}
					
					$bills = $deal->bills;
					foreach ($bills as $bill) {
						$paymentMethod = $bill->paymentMethod;
						if ($paymentMethod) {
							$paymentMethodNames[] = $paymentMethod->name;
						}
					}
					
					// сумма Сделки
					$title .= '. Deal amount ' . $deal->amount() . '$';
					
					// способы оплаты
					if ($paymentMethodNames) {
						$title .= '. ' . implode(', ', array_unique($paymentMethodNames));
					}
					
					// инфа об акции
					if (isset($promo)) {
						$title .= '. Promo: ' . $promo->name . ' (' . ($promo->discount ? $promo->discount->valueFormatted() : '') . ')';
					}
					// инфа о промокоде
					if (isset($promocode)) {
						$title .= '. Promo code: ' . $promocode->number . ' (' . ($promocode->discount ? $promocode->discount->valueFormatted() : '') . ')';
					}
					// время работы платформы от админа
					/*if ($event->simulator_up_at || $event->simulator_down_at) {
						$title .= '. Платформа: ' . ($event->simulator_up_at ? Carbon::parse($event->simulator_up_at)->format('H:i') : '') . ' - ' . ($event->simulator_down_at ? Carbon::parse($event->simulator_down_at)->format('H:i') : '');
					}*/
					// спонтанный полет
					if ($event->is_unexpected_flight) {
						$title .= '. Spontaneous';
					}
					// повторный полет
					if ($event->is_repeated_flight) {
						$title .= '. Repeated';
					}
					// описание
					if ($event->description) {
						$title .= '. ' . $event->description;
					}
					// инфа о прикрепленном к событию документе
					if (is_array($event->data_json) && array_key_exists('doc_file_path', $event->data_json) && $event->data_json['doc_file_path']) {
						$title .= '. Document attached';
					}
					
					$allDay = false;
					
					if ($data) {
						$color = ($balance >= 0) ? $data['deal_paid'] : $data['deal'];
					}
				break;
				case Event::EVENT_TYPE_SHIFT_ADMIN:
					$title = $event->start_at->format('H:i') . '-' . $event->stop_at->format('H:i') . ' ' . $event->user->fioFormatted();
					$allDay = true;
					if ($data && isset($data['shift_admin'])) {
						$color = $data['shift_admin'];
					}
				break;
				case Event::EVENT_TYPE_SHIFT_PILOT:
					$title = $event->start_at->format('H:i') . '-' . $event->stop_at->format('H:i') . ' ' . $event->user->fioFormatted();
					$allDay = true;
					if ($data && isset($data['shift_pilot'])) {
						$color = $data['shift_pilot'];
					}
				break;
				case Event::EVENT_TYPE_CLEANING:
					$title = Event::EVENT_TYPES[Event::EVENT_TYPE_CLEANING];
					$allDay = false;
					if ($data && isset($data[Event::EVENT_TYPE_CLEANING])) {
						$color = $data[Event::EVENT_TYPE_CLEANING];
					}
				break;
				case Event::EVENT_TYPE_BREAK:
					$title = Event::EVENT_TYPES[Event::EVENT_TYPE_BREAK];
					$allDay = false;
					if ($data && isset($data[Event::EVENT_TYPE_BREAK])) {
						$color = $data[Event::EVENT_TYPE_BREAK];
					}
				break;
				case Event::EVENT_TYPE_TEST_FLIGHT:
					$title = Event::EVENT_TYPES[Event::EVENT_TYPE_TEST_FLIGHT];
					$title .= $event->testPilot ? ' ' . $event->testPilot->fioFormatted() : '';
					$allDay = false;
					if ($data && isset($data[Event::EVENT_TYPE_TEST_FLIGHT])) {
						$color = $data[Event::EVENT_TYPE_TEST_FLIGHT];
					}
				break;
				case Event::EVENT_TYPE_USER_FLIGHT:
					$title = Event::EVENT_TYPES[Event::EVENT_TYPE_USER_FLIGHT];
					$title .= $event->employee ? ' ' . $event->employee->fioFormatted() : '';
					$allDay = false;
					if ($data && isset($data[Event::EVENT_TYPE_USER_FLIGHT])) {
						$color = $data[Event::EVENT_TYPE_USER_FLIGHT];
					}
				break;
			}
			
			$commentData = [];
			foreach ($event->comments ?? [] as $comment) {
				$userName = '';
				if ($comment->updated_by) {
					$userName = $comment->updatedUser->fio();
				} elseif ($comment->created_by) {
					$userName = $comment->createdUser->fio();
				}
				$commentData[] = [
					'name' => $comment->name,
					'user' => $userName,
					'date' => $comment->updated_at->format('d.m.Y H:i'),
					'wasUpdated' => ($comment->created_at != $comment->updated_at) ? 'edited' : 'created',
				];
			}
			
			$eventData[] = [
				'eventType' => $event->event_type,
				'className' => Carbon::parse($event->stop_at)->addMinutes($event->extra_time)->lt(Carbon::now()) ? 'fc-event-past' : '',
				'id' => $event->id,
				'title' => $title,
				'start' => Carbon::parse($event->start_at)->format('Y-m-d H:i'),
				'end' => !$allDay ? Carbon::parse($event->stop_at)->addMinutes($event->extra_time)->format('Y-m-d H:i') : '',
				'allDay' => $allDay,
				'backgroundColor' => $color,
				'borderColor' => $color,
				'notificationType' => $event->notification_type ?? '',
				'comments' => $commentData,
				'cityId' => $event->city_id,
				'locationId' => $event->location_id,
				'simulatorId' => $event->flight_simulator_id,
			];
		}
		
		return response()->json($eventData);
	}
	
	/**
	 * @param $positionId
	 * @param null $eventType
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function add($positionId, $eventType = null)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$cities = City::orderBy('name')
			->get();

		switch ($eventType) {
			case 'shift':
				$users = User::where('enable', true)
					->orderBy('lastname')
					->orderBy('name')
					->get();
				
				$VIEW = view('admin.event.modal.shift_add', [
					'users' => $users,
					'cities' => $cities,
					'cityId' => $this->request->city_id,
					'locationId' => $this->request->location_id,
					'simulatorId' => $this->request->simulator_id,
					'eventType' => $eventType,
					'eventDate' => $this->request->event_date,
				]);
			break;
			default:
				$position = DealPosition::find($positionId);
				if (!$position) return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
				
				$VIEW = view('admin.event.modal.add', [
					'position' => $position,
					'cities' => $cities,
				]);
			break;
		}

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @param $id
	 * @param bool $isShift
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit($id, $isShift = false)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$event = Event::find($id);
		if (!$event) return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		
		$cities = City::orderBy('name')
			->get();
		
		$isShift = filter_var($isShift, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if (!$isShift) {
			$commentData = [];
			foreach ($event->comments as $comment) {
				$userName = '';
				if ($comment->updated_by) {
					$userName = $comment->updatedUser->fio();
				} elseif ($comment->created_by) {
					$userName = $comment->createdUser->fio();
				}
				$commentData[] = [
					'id' => $comment->id,
					'name' => $comment->name,
					'user' => $userName,
					'date' => $comment->updated_at->format('d.m.Y H:i'),
					'wasUpdated' => ($comment->created_at != $comment->updated_at) ? 'edited' : 'created',
				];
			}
			
			$productTypes = ProductType::orderBy('name')
				->whereNotIn('alias', ['services'])
				->get();
			
			$shifts = Event::where('event_type', Event::EVENT_TYPE_SHIFT_PILOT)
				->where('city_id', $event->city_id)
				->where('location_id', $event->location_id)
				->where('flight_simulator_id', $event->flight_simulator_id)
				->where('start_at', '>=', Carbon::parse($event->start_at)->format('Y-m-d'))
				->where('stop_at', '<=', Carbon::parse($event->start_at)->addDay()->format('Y-m-d'))
				->orderBy('start_at')
				->get();

			$pilots = User::where('role', User::ROLE_PILOT)
				->orderBy('lastname')
				->orderBy('name')
				->get();
			
			$employees = User::where('enable', true)
				->orderBy('lastname')
				->orderBy('name')
				->get();
			
			$VIEW = view('admin.event.modal.edit', [
				'event' => $event,
				'comments' => $commentData,
				'productTypes' => $productTypes,
				'cities' => $cities,
				'pilots' => $pilots,
				'employees' => $employees,
				'shifts' => $shifts,
				'user' => $user,
			]);
		} else {
			$users = User::where('enable', true)
				->orderBy('lastname')
				->orderBy('name')
				->get();
			
			$VIEW = view('admin.event.modal.shift_edit', [
				'event' => $event,
				'users' => $users,
				'cities' => $cities,
			]);
		}

		return response()->json(['status' => 'success', 'html' => (string)$VIEW]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function store()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$eventType = $this->request->event_type ?? '';
		
		switch ($eventType) {
			case 'shift':
				$rules = [
					'user_id' => 'required',
				];
				$validator = Validator::make($this->request->all(), $rules)
					->setAttributeNames([
						'user_id' => 'User',
					]);
				if (!$validator->passes()) {
					return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
				}
			
				$userId = $this->request->user_id ?? 0;
				$cityId = $this->request->city_id ?? 0;
				$locationId = $this->request->location_id ?? 0;
				$simulatorId = $this->request->simulator_id ?? 0;
				
				if (!$locationId) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.локация-не-найдена')]);
				}
				
				if (!$cityId) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
				}
				
				if (!$simulatorId) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.авиатренажер-не-найден')]);
				}
				
				$shiftUser = 'shift_' . $this->request->shift_user;
				$startAt = $this->request->event_date . ' ' . $this->request->start_at;
				$stopAt = $this->request->event_date . ' ' . $this->request->stop_at;
				
				if (Carbon::parse($startAt)->gte(Carbon::parse($stopAt))) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.время-окончания-смены-должно-быть-больше-времени-начала')]);
				}
				
				//\DB::connection()->enableQueryLog();
				$existingEvent = Event::where('event_type', $shiftUser)
					->where('city_id', $cityId)
					->where('location_id', $locationId)
					->where('flight_simulator_id', $simulatorId)
					->where('start_at', '<', Carbon::parse($stopAt)->format('Y-m-d H:i'))
					->where('stop_at', '>', Carbon::parse($startAt)->format('Y-m-d H:i'))
					->first();
				//\Log::debug(\DB::getQueryLog());
				if ($existingEvent) {
					return response()->json(['status' => 'error', 'reason' => 'Intersection with shift ' . $existingEvent->user->fio()]);
				}
			break;
			default:
				$rules = [
					'start_at_date' => 'required_if:source,deal|date',
					'start_at_time' => 'required_if:source,deal',
				];
				$validator = Validator::make($this->request->all(), $rules)
					->setAttributeNames([
						'start_at_date' => 'Flight start date',
						'start_at_time' => 'Flight start time',
					]);
				if (!$validator->passes()) {
					return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
				}
				
				$position = DealPosition::find($this->request->position_id);
				if (!$position) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
				}
				
				if (!$product = $position->product) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
				}
				
				if (!$location = $position->location) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.локация-не-найдена')]);
				}
				
				if (!$city = $position->city) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
				}
				
				if (!$simulator = $position->simulator) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.авиатренажер-не-найден')]);
				}
				
				$startAt = $this->request->start_at_date . ' ' . $this->request->start_at_time;
				$stopAt = $this->request->start_at_date . ' ' . $this->request->start_at_time;
			break;
		}

		try {
			\DB::beginTransaction();
			
			switch ($eventType) {
				case 'shift':
					$event = new Event();
					$event->event_type = $shiftUser;
					$event->start_at = Carbon::parse($startAt)->format('Y-m-d H:i');
					$event->stop_at = Carbon::parse($stopAt)->format('Y-m-d H:i');
					$event->user_id = $userId;
					$event->city_id = $cityId;
					$event->location_id = $locationId;
					$event->flight_simulator_id = $simulatorId;
					$event->save();
				break;
				default:
					/*if (!$product->validateFlightDate(Carbon::parse($startAt)->format('Y-m-d H:i'))) {
						return response()->json(['status' => 'error', 'reason' => 'Для бронирования полета по тарифу Regular доступны только будние дни']);
					}*/
					
					$data = [
						'pilot_assessment' => $this->request->pilot_assessment ?? '',
						'admin_assessment' => $this->request->admin_assessment ?? '',
					];
					
					$event = new Event();
					$event->event_type = Event::EVENT_TYPE_DEAL;
					$event->contractor_id = ($position->deal && $position->deal->contractor) ? $position->deal->contractor->id : 0;
					$event->deal_id = $position->deal ? $position->deal->id : 0;
					$event->deal_position_id = $position->id ?? 0;
					$event->city_id = $city->id ?? 0;
					$event->location_id = $location->id ?? 0;
					$event->flight_simulator_id = $simulator->id ?? 0;
					$event->start_at = Carbon::parse($startAt)->format('Y-m-d H:i');
					$event->stop_at = Carbon::parse($stopAt)->addMinutes($product->duration ?? 0)->format('Y-m-d H:i');
					$event->extra_time = (int)$this->request->extra_time;
					$event->is_repeated_flight = (bool)$this->request->is_repeated_flight;
					$event->is_unexpected_flight = (bool)$this->request->is_unexpected_flight;
					$event->pilot_id = $this->request->pilot ?? 0;
					$event->user_id = $this->request->user()->id ?? 0;
					$event->data_json = $data;
					$event->save();
					
					$commentText = $this->request->comment ?? '';
					if ($commentText) {
						$user = Auth::user();
						
						$event->comments()->create([
							'name' => $commentText,
							'created_by' => $user->id ?? 0,
						]);
					}
				break;
			}

			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();
			
			\Log::debug('500 - Event Update: ' . $e->getMessage());
			
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}

		return response()->json(['status' => 'success']);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function update($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$rules = [
			'start_at_date' => 'required_if:source,deal|date',
			'start_at_time' => 'required_if:source,deal',
			'stop_at_date' => 'required_if:source,deal|date',
			'stop_at_time' => 'required_if:source,deal',
			'doc_file' => 'sometimes|image|max:5120|mimes:jpg,jpeg,png,webp',
		];

		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'start_at_date' => 'Flight start date',
				'start_at_time' => 'Flight start time',
				'stop_at_date' => 'Flight end date',
				'stop_at_time' => 'Flight end time',
				'doc_file' => 'Document photo',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}

		$event = Event::find($id);
		if (!$event) return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		
		$userId = $this->request->user_id ?? 0;
		$pilotId = $this->request->pilot_id ?? 0;
		$employeeId = $this->request->employee_id ?? 0;
		$locationId = $this->request->location_id ?? 0;
		$simulatorId = $this->request->flight_simulator_id ?? 0;
		$position = null;

		switch ($event->event_type) {
			case Event::EVENT_TYPE_DEAL:
				$position = DealPosition::find($event->deal_position_id);
				if (!$position) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
				}
				
				if (!$product = $position->product) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
				}
			break;
			case Event::EVENT_TYPE_TEST_FLIGHT:
				if (!$pilotId) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.пилот-обязательно-для-заполнения')]);
				}
			break;
			case Event::EVENT_TYPE_USER_FLIGHT:
				if (!$employeeId) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.сотрудник-обязательно-для-заполнения')]);
				}
			break;
			case Event::EVENT_TYPE_SHIFT_ADMIN:
			case Event::EVENT_TYPE_SHIFT_PILOT:
				/*if (!$event->user) {
					return response()->json(['status' => 'error', 'reason' => 'Пользователь не найден']);
				}*/
			
				$shiftUser = 'shift_' . $this->request->shift_user;
				$startAt = Carbon::parse($event->start_at)->format('Y-m-d') . ' ' . $this->request->start_at_time;
				$stopAt = Carbon::parse($event->stop_at)->format('Y-m-d') . ' ' . $this->request->stop_at_time;
				if (Carbon::parse($startAt)->gte(Carbon::parse($stopAt))) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.время-окончания-смены-должно-быть-больше-времени-начала')]);
				}
			
				//\DB::connection()->enableQueryLog();
				$existingEvent = Event::where('event_type', $shiftUser)
					->where('start_at', '<', Carbon::parse($stopAt)->format('Y-m-d H:i'))
					->where('stop_at', '>', Carbon::parse($startAt)->format('Y-m-d H:i'))
					->where('location_id', $event->location_id)
					->where('flight_simulator_id', $event->flight_simulator_id)
					->where('id', '!=', $event->id)
					->first();
				//\Log::debug(\DB::getQueryLog());
				if ($existingEvent) {
					return response()->json(['status' => 'error', 'reason' => 'Intersection with shift ' . $existingEvent->user->fio()]);
				}
			break;
		}
		
		if ($this->request->source == Event::EVENT_SOURCE_DEAL && $position) {
			if (!$location = $position->location) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.локация-не-найдена')]);
			}
			
			if (!$city = $position->city) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.город-не-найден')]);
			}
			
			if (!$simulator = $position->simulator) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.авиатренажер-не-найден')]);
			}
		}
		
		try {
			\DB::beginTransaction();
			switch ($event->event_type) {
				case Event::EVENT_TYPE_DEAL:
					if ($this->request->source == Event::EVENT_SOURCE_DEAL) {
						$startAt = Carbon::parse($this->request->start_at_date . ' ' . $this->request->start_at_time)->format('Y-m-d H:i');
						$stopAt = Carbon::parse($this->request->stop_at_date . ' ' . $this->request->stop_at_time)/*->addMinutes($product->duration ?? 0)*/->format('Y-m-d H:i');
						
						/*if (!$product->validateFlightDate($startAt)) {
							return response()->json(['status' => 'error', 'reason' => 'Для бронирования полета по тарифу Regular доступны только будние дни']);
						}*/
						
						$event->city_id = $city ? $city->id : 0;
						$event->location_id = $locationId ?: ($location ? $location->id : 0);
						$event->flight_simulator_id = $simulatorId ?: ($simulator ? $simulator->id : 0);
						$event->extra_time = (int)$this->request->extra_time;
						$event->is_repeated_flight = (bool)$this->request->is_repeated_flight;
						$event->is_unexpected_flight = (bool)$this->request->is_unexpected_flight;
						$event->notification_type = $this->request->notification_type;
						$event->pilot_assessment = (int)$this->request->pilot_assessment;
						$event->admin_assessment = (int)$this->request->admin_assessment;
						$event->simulator_up_at = $this->request->simulator_up_at ? Carbon::parse($this->request->start_at_date . ' ' . $this->request->simulator_up_at)->format('Y-m-d H:i') : null;
						$event->simulator_down_at = $this->request->simulator_down_at ? Carbon::parse($this->request->start_at_date . ' ' . $this->request->simulator_down_at)->format('Y-m-d H:i') : null;
						$event->pilot_id = $pilotId;
						$event->description = $this->request->description ?? null;
					} else if ($this->request->source == Event::EVENT_SOURCE_CALENDAR) {
						$startAt = Carbon::parse($this->request->start_at)->format('Y-m-d H:i');
						$stopAt = Carbon::parse($this->request->stop_at)->subMinutes($event->extra_time ?? 0)->format('Y-m-d H:i');
						
						/*if (!$product->validateFlightDate($startAt)) {
							return response()->json(['status' => 'error', 'reason' => 'Для бронирования полета по тарифу Regular доступны только будние дни']);
						}*/
					}
					// сброс отметки об уведомлении, если перенос на другой день
					if ($event->start_at->format('d') != Carbon::parse($startAt)->format('d')) {
						$event->notification_type = null;
					}
					$event->start_at = $startAt;
					$event->stop_at = $stopAt;
					if (isset($city)) {
						$event->city_id = $city->id;
					}
					if (isset($locationId) && $locationId) {
						$event->location_id = $locationId;
					} elseif (isset($location)) {
						$event->location_id = $location->id;
					}
					if (isset($simulatorId) && $simulatorId) {
						$event->flight_simulator_id = $simulatorId;
					} elseif (isset($simulator)) {
						$event->flight_simulator_id = $simulator->id;
					}
					
					$data = $event->data_json ?? [];
					if($file = $this->request->file('doc_file')) {
						$isFileUploaded = $file->move(storage_path('app/private/contractor/doc'), $file->getClientOriginalName());
						$data['doc_file_path'] = $isFileUploaded ? 'contractor/doc/' . $file->getClientOriginalName() : '';
					}

					if (isset($data)) {
						$event->data_json = $data;
					}
					$event->save();
					
					$commentId = $this->request->comment_id ?? 0;
					$commentText = $this->request->comment ?? '';
					$user = \Auth::user();
					if ($commentText) {
						if ($commentId) {
							$comment = $event->comments->find($commentId);
							if (!$comment) {
								return response()->json(['status' => 'error', 'reason' => trans('main.error.комментарий-не-найден')]);
							}
							$comment->name = $commentText;
							$comment->updated_by = $user->id ?? 0;
							$comment->save();
						}
						else {
							$event->comments()->create([
								'name' => $commentText,
								'created_by' => $user->id ?? 0,
							]);
						}
					}
				break;
				case Event::EVENT_TYPE_BREAK:
				case Event::EVENT_TYPE_CLEANING:
					$event->start_at = Carbon::parse($this->request->start_at_date . ' ' . $this->request->start_at_time)->format('Y-m-d H:i');
					$event->stop_at = Carbon::parse($this->request->stop_at_date . ' ' . $this->request->stop_at_time)->format('Y-m-d H:i');
					$event->save();
				break;
				case Event::EVENT_TYPE_USER_FLIGHT:
					$event->start_at = Carbon::parse($this->request->start_at_date . ' ' . $this->request->start_at_time)->format('Y-m-d H:i');
					$event->stop_at = Carbon::parse($this->request->stop_at_date . ' ' . $this->request->stop_at_time)->format('Y-m-d H:i');
					$event->employee_id = $employeeId;
					$event->save();
				break;
				case Event::EVENT_TYPE_TEST_FLIGHT:
					$event->start_at = Carbon::parse($this->request->start_at_date . ' ' . $this->request->start_at_time)->format('Y-m-d H:i');
					$event->stop_at = Carbon::parse($this->request->stop_at_date . ' ' . $this->request->stop_at_time)->format('Y-m-d H:i');
					$event->simulator_up_at = $this->request->simulator_up_at ? Carbon::parse($this->request->start_at_date . ' ' . $this->request->simulator_up_at)->format('Y-m-d H:i') : null;;
					$event->simulator_down_at = $this->request->simulator_down_at ? Carbon::parse($this->request->start_at_date . ' ' . $this->request->simulator_down_at)->format('Y-m-d H:i') : null;
					$event->test_pilot_id = $pilotId;
					$event->save();
				break;
				case Event::EVENT_TYPE_SHIFT_ADMIN:
				case Event::EVENT_TYPE_SHIFT_PILOT:
					$event->start_at = Carbon::parse($event->start_at)->format('Y-m-d') . ' ' . $this->request->start_at_time;
					$event->stop_at = Carbon::parse($event->stop_at)->format('Y-m-d') . ' ' . $this->request->stop_at_time;
					$event->user_id = $userId;
					$event->save();
				break;
			}

			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();

			\Log::debug('500 - Event Update: ' . $e->getMessage());

			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}

		return response()->json(['status' => 'success']);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function dragDrop($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$event = Event::find($id);
		if (!$event) return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		
		switch ($event->event_type) {
			case Event::EVENT_TYPE_DEAL:
				$position = DealPosition::find($event->deal_position_id);
				if (!$position) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
				}
				
				if (!$product = $position->product) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.продукт-не-найден')]);
				}
			break;
			case Event::EVENT_TYPE_SHIFT_ADMIN:
			case Event::EVENT_TYPE_SHIFT_PILOT:
				if (!$event->user) {
					return response()->json(['status' => 'error', 'reason' => trans('main.error.пользователь-не-найден')]);
				}
				
				$startAt = Carbon::parse($this->request->start_at)->format('Y-m-d') . ' ' . Carbon::parse($event->start_at)->format('H:i');
				$stopAt = Carbon::parse($this->request->start_at)->format('Y-m-d') . ' ' . Carbon::parse($event->stop_at)->format('H:i');

				$existingEvent = Event::where('event_type', $event->event_type)
					->where('start_at', '<', Carbon::parse($stopAt)->format('Y-m-d H:i'))
					->where('stop_at', '>', Carbon::parse($startAt)->format('Y-m-d H:i'))
					->where('location_id', $event->location_id)
					->where('flight_simulator_id', $event->flight_simulator_id)
					->where('id', '!=', $event->id)
					->first();
				if ($existingEvent) {
					return response()->json(['status' => 'error', 'reason' => 'Intersection with shift ' . $existingEvent->user->fio()]);
				}
			break;
		}
		
		try {
			\DB::beginTransaction();
			
			switch ($event->event_type) {
				case Event::EVENT_TYPE_DEAL:
					if ($this->request->source == Event::EVENT_SOURCE_DEAL) {
						$startAt = Carbon::parse($this->request->start_at_date . ' ' . $this->request->start_at_time)->format('Y-m-d H:i');
						$stopAt = Carbon::parse($this->request->start_at_date . ' ' . $this->request->start_at_time)->addMinutes($product->duration ?? 0)->format('Y-m-d H:i');
						
						/*if (!$product->validateFlightDate($startAt)) {
							return response()->json(['status' => 'error', 'reason' => 'Для бронирования полета по тарифу Regular доступны только будние дни']);
						}*/
					} elseif ($this->request->source == Event::EVENT_SOURCE_CALENDAR) {
						$startAt = Carbon::parse($this->request->start_at)->format('Y-m-d H:i');
						$stopAt = Carbon::parse($this->request->stop_at)->subMinutes($event->extra_time ?? 0)->format('Y-m-d H:i');
						
						/*if (!$product->validateFlightDate($startAt)) {
							return response()->json(['status' => 'error', 'reason' => 'Для бронирования полета по тарифу Regular доступны только будние дни']);
						}*/
					}
					// сброс отметки об уведомлении, если перенос на другой день
					if ($event->start_at->format('d') != Carbon::parse($startAt)->format('d')) {
						$event->notification_type = null;
					}
					$event->start_at = $startAt;
					$event->stop_at = $stopAt;
					$event->save();
				break;
				case Event::EVENT_TYPE_BREAK:
				case Event::EVENT_TYPE_CLEANING:
				case Event::EVENT_TYPE_TEST_FLIGHT:
				case Event::EVENT_TYPE_USER_FLIGHT:
					$startAt = Carbon::parse($this->request->start_at)->format('Y-m-d H:i');
					$stopAt = Carbon::parse($this->request->stop_at)->format('Y-m-d H:i');
					$event->start_at = $startAt;
					$event->stop_at = $stopAt;
					$event->save();
				break;
				case Event::EVENT_TYPE_SHIFT_ADMIN:
				case Event::EVENT_TYPE_SHIFT_PILOT:
					$event->start_at = Carbon::parse($this->request->start_at)->format('Y-m-d') . ' ' . Carbon::parse($event->start_at)->format('H:i');
					$event->stop_at = Carbon::parse($this->request->stop_at)->subDay()->format('Y-m-d') . ' ' . Carbon::parse($event->stop_at)->format('H:i');
					$event->save();
				break;
			}
			
			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();
			
			\Log::debug('500 - Event Update: ' . $e->getMessage());
			
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success']);
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
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$event = Event::find($id);
		if (!$event) return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		
		$flightInvitationFilePath = (is_array($event->data_json) && array_key_exists('flight_invitation_file_path', $event->data_json)) ? $event->data_json['flight_invitation_file_path'] : '';
		
		if (!$event->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		if ($flightInvitationFilePath) {
			Storage::disk('private')->delete($flightInvitationFilePath);
		}
		
		return response()->json(['status' => 'success']);
	}
	
	/**
	 * @param $id
	 * @param $commentId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteComment($id, $commentId)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$event = Event::find($id);
		if (!$event) return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		
		$comment = $event->comments->find($commentId);
		if (!$comment) return response()->json(['status' => 'error', 'reason' => trans('main.error.комментарий-не-найден')]);
		
		if (!$comment->delete()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'msg' => trans('main.success.комментарий-успешно-удален.')]);
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteDocFile($id)
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$event = Event::find($id);
		if (!$event) return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		
		$data = $event->data_json;
		if(is_array($data)
			&& array_key_exists('doc_file_path', $data)
			&& $data['doc_file_path']) {
			$data['doc_file_path'] = '';
			$event->data_json = $data;
			if (!$event->save()) {
				return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
			}

			return response()->json(['status' => 'success', 'msg' => trans('main.success.файл-успешно-удален')]);
		}
		
		return response()->json(['status' => 'error', 'reason' => trans('main.error.файл-не-найден')]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function notified()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$eventId = $this->request->event_id ?? 0;
		if (!$eventId) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.некорректные-параметры')]);
		}
		
		$event = Event::find($eventId);
		if (!$event) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		}
		
		$event->is_notified = true;
		if (!$event->save()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.повторите-позже')]);
		}
		
		return response()->json(['status' => 'success', 'msg' => trans('main.success.уведомление-по-событию-успешно-сохранено')]);
	}
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendFlightInvitation()
	{
		if (!$this->request->ajax()) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		$rules = [
			'id' => 'required|numeric|min:0|not_in:0',
			'event_id' => 'required|numeric|min:0|not_in:0',
		];
		
		$validator = Validator::make($this->request->all(), $rules)
			->setAttributeNames([
				'id' => 'Deal item',
				'event_id' => 'Event',
			]);
		if (!$validator->passes()) {
			return response()->json(['status' => 'error', 'reason' => $validator->errors()->all()]);
		}
		
		$position = DealPosition::find($this->request->id);
		if (!$position) return response()->json(['status' => 'error', 'reason' => trans('main.error.позиция-сделки-не-найдена')]);
		
		/** @var Deal $deal */
		$deal = $position->deal;
		if (!$deal) return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-не-найдена')]);
		
		if (in_array($deal->status->alias, [Deal::CANCELED_STATUS, Deal::RETURNED_STATUS])) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.сделка-недоступна-для-редактирования')]);
		}
		
		/** @var Bill $bill */
		/*$bill = $position->bill;
		if ($bill) {
			// если к позиции привязан счет, то он должен быть оплачен
			if ($bill->status->alias != Bill::PAYED_STATUS) {
				return response()->json(['status' => 'error', 'reason' => 'Счет ' . $bill->number . ' не оплачен']);
			}
			$amount = $bill->amount;
			if ($amount <= 0) {
				return response()->json(['status' => 'error', 'reason' => 'Некорректная сумма Счета ' . $bill->number]);
			}
		} else {
			// если к позиции не привязан счет, то проверяем чтобы вся сделка была оплачена
			$balance = $deal->balance();
			if ($balance < 0) return response()->json(['status' => 'error', 'reason' => 'Сделка не оплачена']);
		}*/
		
		$event = Event::find($this->request->event_id);
		if (!$event) return response()->json(['status' => 'error', 'reason' => trans('main.error.событие-не-найдено')]);
		
		//dispatch(new \App\Jobs\SendFlightInvitationEmail($event));
		$job = new \App\Jobs\SendFlightInvitationEmail($event);
		$job->handle();
		
		return response()->json(['status' => 'success', 'message' => trans('main.success.задание-на-отправку-приглашения-на-полет-принято')]);
	}
	
	/**
	 * @param $uuid
	 * @return \never|\Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function getFlightInvitationFile($uuid)
	{
		$event = HelpFunctions::getEntityByUuid(Event::class, $uuid);
		if (!$event) {
			abort(404);
		}
		
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}
		
		/*$flightInvitationFilePath = (is_array($event->data_json) && array_key_exists('flight_invitation_file_path', $event->data_json)) ? $event->data_json['flight_invitation_file_path'] : '';
		
		// если файла приглашения на полет по какой-то причине не оказалось, генерим его
		$flightInvitationFileExists = Storage::disk('private')->exists($flightInvitationFilePath);
		if (!isset($flightInvitationFilePath) || !$flightInvitationFileExists) {*/
			$event = $event->generateFile();
			if (!$event) {
				abort(404);
			}
		/*}*/
		
		$flightInvitationFilePath = (is_array($event->data_json) && array_key_exists('flight_invitation_file_path', $event->data_json)) ? $event->data_json['flight_invitation_file_path'] : '';
		
		return Storage::disk('private')->download($flightInvitationFilePath);
	}
	
	/**
	 * @param $uuid
	 * @return \Symfony\Component\HttpFoundation\StreamedResponse
	 */
	public function getDocFile($uuid)
	{
		$user = \Auth::user();
		
		if (!$user->isAdminOrHigher()) {
			return response()->json(['status' => 'error', 'reason' => trans('main.error.недостаточно-прав-доступа')]);
		}

		$event = HelpFunctions::getEntityByUuid(Event::class, $uuid);
		if (!$event) {
			abort(404);
		}
		
		$docFilePath = (is_array($event->data_json) && array_key_exists('doc_file_path', $event->data_json)) ? $event->data_json['doc_file_path'] : '';

		return Storage::disk('private')->download($docFilePath);
	}
}
