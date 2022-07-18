<?php

namespace App\Models;

use App\Services\HelpFunctions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use \Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $event_type тип события
 * @property int $contractor_id Контрагент
 * @property int $deal_id сделка
 * @property int $deal_position_id позиция сделки
 * @property int $city_id город, в котором будет осуществлен полет
 * @property int $location_id локация, на которой будет осуществлен полет
 * @property int $flight_simulator_id авиатренажер, на котором будет осуществлен полет
 * @property int $user_id
 * @property \datetime|null $start_at дата и время начала события
 * @property \datetime|null $stop_at дата и время окончания события
 * @property int $extra_time дополнительное время
 * @property int $is_repeated_flight признак повторного полета
 * @property int $is_unexpected_flight признак спонтанного полета
 * @property string|null $notification_type способ оповещения контрагента о полете
 * @property int $pilot_assessment оценка пилота
 * @property int $admin_assessment оценка админа
 * @property \datetime|null $simulator_up_at дата и время подъема платформы
 * @property \datetime|null $simulator_down_at дата и время опускания платформы
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Contractor|null $contractor
 * @property-read \App\Models\Deal|null $deal
 * @property-read \App\Models\DealPosition|null $dealPosition
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\FlightSimulator|null $simulator
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Query\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAdminAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereContractorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDealPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereExtraTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFlightSimulatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsRepeatedFlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsUnexpectedFlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNotificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePilotAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSimulatorDownAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSimulatorUpAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStopAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Event withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_notified
 * @property \datetime|null $flight_invitation_sent_at
 * @property string|null $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFlightInvitationSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUuid($value)
 * @property int $shift_admin_id
 * @property int $shift_pilot_id
 * @property string|null $description
 * @property int $pilot_id фактический пилот
 * @property int $test_pilot_id пилот тестового полета
 * @property int $employee_id сотрудник, осуществивший полет
 * @property-read \App\Models\User|null $employee
 * @property-read \App\Models\User|null $pilot
 * @property-read \App\Models\User|null $testPilot
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePilotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereShiftAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereShiftPilotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTestPilotId($value)
 * @property string|null $promocode_sent_at дата и время последней отправки промокода контрагенту
 * @property int $text_pilot_id тестовый полет пилота
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePromocodeSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTextPilotId($value)
 */
class Event extends Model
{
	use HasFactory, SoftDeletes, RevisionableTrait;
	
	const ATTRIBUTES = [
		'event_type' => 'Event type',
		'contractor_id' => 'Contractor',
		'deal_id' => 'Deal',
		'deal_position_id' => 'Deal position',
		'city_id' => 'City',
		'location_id' => 'Location',
		'flight_simulator_id' => 'Simulator',
		'user_id' => 'User',
		'start_at' => 'Start at',
		'stop_at' => 'Stop at',
		'extra_time' => 'Extra time',
		'description' => 'Description',
		'is_repeated_flight' => 'Repeat flight',
		'is_unexpected_flight' => 'Spontaneous flight',
		'notification_type' => 'Notification method',
		'is_notified' => 'Contractor is notified of the flight',
		'pilot_assessment' => 'Pilot assessment',
		'admin_assessment' => 'Admin assessment',
		'simulator_up_at' => 'Platform lifting time',
		'simulator_down_at' => 'Platform lowering time',
		'flight_invitation_sent_at' => 'Flight invitation sent at',
		'pilot_id' => 'Real pilot',
		'uuid' => 'Uuid',
		'data_json' => 'Extra info',
		'created_at' => 'Created',
		'updated_at' => 'Updated',
		'deleted_at' => 'Deleted',
	];

	protected $revisionForceDeleteEnabled = true;
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['uuid', 'data_json'];
	
	const EVENT_SOURCE_DEAL = 'deal';
	const EVENT_SOURCE_CALENDAR = 'calendar';

	const EVENT_TYPE_DEAL = 'deal';
	const EVENT_TYPE_DEAL_PAID = 'deal_paid';
	const EVENT_TYPE_SHIFT_ADMIN = 'shift_admin';
	const EVENT_TYPE_SHIFT_PILOT = 'shift_pilot';
	const EVENT_TYPE_BREAK = 'break';
	const EVENT_TYPE_CLEANING = 'cleaning';
	const EVENT_TYPE_TEST_FLIGHT = 'test_flight';
	const EVENT_TYPE_USER_FLIGHT = 'user_flight';
	const EVENT_TYPES = [
		self::EVENT_TYPE_DEAL => 'Deal not paid',
		self::EVENT_TYPE_DEAL_PAID => 'Deal paid',
		self::EVENT_TYPE_SHIFT_ADMIN => 'Admin shift',
		self::EVENT_TYPE_SHIFT_PILOT => 'Pilot shift',
		self::EVENT_TYPE_BREAK => 'Break',
		self::EVENT_TYPE_CLEANING => 'Cleaning',
		self::EVENT_TYPE_TEST_FLIGHT => 'Test flight',
		self::EVENT_TYPE_USER_FLIGHT => 'Employee',
	];
	
	const NOTIFICATION_TYPE_SMS = 'sms';
	const NOTIFICATION_TYPE_CALL = 'call';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'event_type',
		'contractor_id',
		'deal_id',
		'deal_position_id',
		'user_id',
		'extra_time',
		'city_id',
		'location_id',
		'flight_simulator_id',
		'start_at',
		'stop_at',
		'description',
		'is_repeated_flight',
		'is_unexpected_flight',
		'pilot_assessment',
		'admin_assessment',
		'simulator_up_at',
		'simulator_down_at',
		'notification_type',
		'is_notified',
		'flight_invitation_sent_at',
		'pilot_id',
		'test_pilot_id',
		'employee_id',
		'uuid',
		'data_json',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'start_at' => 'datetime:Y-m-d H:i:s',
		'stop_at' => 'datetime:Y-m-d H:i:s',
		'simulator_up_at' => 'datetime:Y-m-d H:i:s',
		'simulator_down_at' => 'datetime:Y-m-d H:i:s',
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
		'deleted_at' => 'datetime:Y-m-d H:i:s',
		'flight_invitation_sent_at' => 'datetime:Y-m-d H:i',
		'data_json' => 'array',
		'is_notified' => 'boolean',
	];
	
	public static function boot()
	{
		parent::boot();
		
		Event::created(function (Event $event) {
			$event->uuid = $event->generateUuid();
			if (!in_array($event->event_type, [Event::EVENT_TYPE_SHIFT_PILOT, Event::EVENT_TYPE_SHIFT_ADMIN])) {
				$user = \Auth::user();
				$event->user_id = $user ? $user->id : 0;
			}
			$event->save();
			
			if ($event->user) {
				$eventComment = new EventComment();
				$eventComment->name = 'Added ' . $event->user->fioFormatted();
				$eventComment->event_id = $event->id;
				$eventComment->created_by = $event->user_id;
				$eventComment->save();
			}
		
			if ($event->user && !in_array($event->event_type, [Event::EVENT_TYPE_SHIFT_PILOT, Event::EVENT_TYPE_SHIFT_ADMIN])) {
				$deal = $event->deal;
				if ($deal) {
					$createdStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::CREATED_STATUS);
					if ($deal->status_id == $createdStatus->id) {
						$inWorkStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::IN_WORK_STATUS);
						if ($inWorkStatus) {
							$deal->status_id = $inWorkStatus->id;
							$deal->save();
						}
					}
				}
			}
		});
		
		Event::saved(function (Event $event) {
			if (($event->getOriginal('start_at') && $event->start_at != $event->getOriginal('start_at')) || ($event->getOriginal('stop_at') && $event->stop_at != $event->getOriginal('stop_at'))) {
				$user = \Auth::user();
				
				$eventComment = new EventComment();
				$eventComment->name = 'Moved from ' . Carbon::parse($event->getOriginal('start_at'))->format('d.m.Y H:i') . ' - ' . Carbon::parse($event->getOriginal('stop_at'))->format('d.m.Y H:i') . ' to ' . Carbon::parse($event->start_at)->format('d.m.Y H:i') . ' - ' . Carbon::parse($event->stop_at)->format('d.m.Y H:i');
				$eventComment->event_id = $event->id;
				$eventComment->created_by = $user ? $user->id : 0;
				$eventComment->save();
			}
		});
		
		Event::deleting(function (Event $event) {
			$event->comments()->delete();
		});
	}
	
	public function contractor()
	{
		return $this->belongsTo(Contractor::class, 'contractor_id', 'id');
	}

	public function city()
	{
		return $this->belongsTo(City::class, 'city_id', 'id');
	}
	
	public function location()
	{
		return $this->belongsTo(Location::class, 'location_id', 'id');
	}

	public function simulator()
	{
		return $this->belongsTo(FlightSimulator::class, 'flight_simulator_id', 'id');
	}
	
	public function deal()
	{
		return $this->belongsTo(Deal::class, 'deal_id', 'id');
	}

	public function dealPosition()
	{
		return $this->belongsTo(DealPosition::class, 'deal_position_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
	
	public function pilot()
	{
		return $this->belongsTo(User::class, 'pilot_id', 'id');
	}
	
	public function testPilot()
	{
		return $this->belongsTo(User::class, 'test_pilot_id', 'id');
	}
	
	public function employee()
	{
		return $this->belongsTo(User::class, 'employee_id', 'id');
	}
	
	public function comments()
	{
		return $this->hasMany(EventComment::class, 'event_id', 'id')
			->latest();
	}
	
	/**
	 * @return string
	 * @throws \Exception
	 */
	public function generateUuid()
	{
		return (string)\Webpatser\Uuid\Uuid::generate();
	}
	
	/**
	 * @return $this|null
	 */
	public function generateFile()
	{
		$simulatorAlias = $this->simulator->alias ?? '';
		if (!$simulatorAlias) return null;

		$product = $this->dealPosition->product;
		if (!$product) return null;
		
		$productType = $product->productType;
		if (!$productType) return null;
		
		$location = $this->location;
		if (!$location) return null;
		
		$flightInvitationTemplateFileName = 'INVITE_' . $simulatorAlias . '.jpg';
		if (!Storage::disk('private')->exists('invitation/template/' . $flightInvitationTemplateFileName)) {
			return null;
		}
		
		$address = array_key_exists('address', $location->data_json) ? $location->data_json['address'] : '';
		$addressLength = mb_strlen($address);
		$phone = array_key_exists('phone', $location->data_json) ? $location->data_json['phone'] : '';
		
		$flightInvitationTemplateFilePath = Storage::disk('private')->path('invitation/template/' . $flightInvitationTemplateFileName);
		
		$flightInvitationFile = Image::make($flightInvitationTemplateFilePath)->encode('jpg');
		$fontPath = public_path('assets/fonts/GothamProRegular/GothamProRegular.ttf');
		
		$flightInvitationFile->text($this->start_at->format('d.m.Y H:i'), 840, 100, function ($font) use ($fontPath) {
			$font->file($fontPath);
			$font->size(24);
			$font->color('#000000');
		});
		$flightInvitationFile->text($product->duration ?? '-', 250, 890, function ($font) use ($fontPath) {
			$font->file($fontPath);
			$font->size(35);
			$font->color('#000000');
		});
		if ($addressLength > 50) {
			$addressTemp = HelpFunctions::wordWrapLimit($address, 50);
			$flightInvitationFile->text($addressTemp, 50, 1570, function ($font) use ($fontPath) {
				$font->file($fontPath);
				$font->size(24);
				$font->color('#ffffff');
			});
			$flightInvitationFile->text(HelpFunctions::wordWrapLimit($address, 50, mb_strlen($addressTemp) + 1), 50, 1600, function ($font) use ($fontPath) {
				$font->file($fontPath);
				$font->size(24);
				$font->color('#ffffff');
			});
			$flightInvitationFile->text($phone, 50, 1630, function ($font) use ($fontPath) {
				$font->file($fontPath);
				$font->size(24);
				$font->color('#ffffff');
			});
		} else {
			$flightInvitationFile->text($address, 50, 1600, function ($font) use ($fontPath) {
				$font->file($fontPath);
				$font->size(24);
				$font->color('#ffffff');
			});
			$flightInvitationFile->text($phone, 50, 1630, function ($font) use ($fontPath) {
				$font->file($fontPath);
				$font->size(24);
				$font->color('#ffffff');
			});
		}
		
		$flightInvitationFileName = $this->uuid . '.jpg';
		if (!$flightInvitationFile->save(storage_path('app/private/invitation/' . $flightInvitationFileName))) {
			return null;
		}
		
		$data = $this->data_json ?? [];
		$data['flight_invitation_file_path'] = 'invitation/' . $flightInvitationFileName;
		
		$this->data_json = $data;
		if (!$this->save()) {
			return null;
		}
		
		return $this;
	}
	
	/**
	 * @param $role
	 * @return int
	 */
	public function getAssessment($role)
	{
		switch ($role) {
			case User::ROLE_ADMIN:
				return $this->admin_assessment;
			break;
			case User::ROLE_PILOT:
				return $this->pilot_assessment;
			break;
			default:
				return 0;
		}
	}
	
	/**
	 * @param $assessment
	 * @return string
	 */
	public function getAssessmentState($assessment)
	{
		if ($assessment >= 9) return 'success';
		if ($assessment >= 7 && $assessment <= 8) return 'warning';
		if ($assessment >= 1 && $assessment <= 6) return 'danger';
		return '';
	}
	
	/**
	 * @return string
	 */
	public function getInterval()
	{
		return Carbon::parse($this->start_at)->format('Y-m-d H:i') . ' - ' .Carbon::parse($this->stop_at)->format('H:i');
	}
}
