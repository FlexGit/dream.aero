<?php

namespace App\Models;

use App\Services\HelpFunctions;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use \Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\Deal
 *
 * @property int $id
 * @property string|null $number номер
 * @property int $status_id статус
 * @property int $contractor_id контрагент
 * @property string $name имя
 * @property string $phone номер телефона
 * @property string $email e-mail
 * @property int $city_id город, в котором будет осуществлен полет
 * @property string|null $source источник
 * @property int $user_id пользователь
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bill[] $bills
 * @property-read int|null $bills_count
 * @property-read \App\Models\Certificate|null $certificate
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Contractor $contractor
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Promo|null $promo
 * @property-read \App\Models\Promocode|null $promocode
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\Status|null $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal newQuery()
 * @method static \Illuminate\Database\Query\Builder|Deal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCertificateSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereContractorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereFlightAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereInviteSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereIsCertificatePurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereIsUnified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePromoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePromocodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Deal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Deal withoutTrashed()
 * @mixin \Eloquent
 * @property int $flight_simulator_id
 * @property-read \App\Models\FlightSimulator|null $simulator
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereFlightSimulatorId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Event[] $events
 * @property-read int|null $events_count
 * @property string|null $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereUuid($value)
 * @property string|null $roistat номер визита Roistat
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereRoistat($value)
 * @property int $location_id
 * @property int $product_id
 * @property int $certificate_id
 * @property float $amount
 * @property float $tax
 * @property float $total_amount
 * @property int $currency_id
 * @property int $promo_id
 * @property int $promocode_id
 * @property bool $is_certificate_purchase
 * @property-read \App\Models\Currency|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereTotalAmount($value)
 */
class Deal extends Model
{
	use HasFactory, SoftDeletes, RevisionableTrait;
	
	const ATTRIBUTES = [
		'number' => 'Number',
		'status_id' => 'Status',
		'contractor_id' => 'Contractor',
		'city_id' => 'City',
		'location_id' => 'Location',
		'flight_simulator_id' => 'Simulator',
		'name' => 'Name',
		'phone' => 'Phone',
		'email' => 'E-mail',
		'product_id' => 'Product',
		'certificate_id' => 'Certificate',
		'amount' => 'Amount',
		'tax' => 'Tax',
		'total_amount' => 'Total amount',
		'currency_id' => 'Currency',
		'promo_id' => 'Promo',
		'promocode_id' => 'Promocode',
		'is_certificate_purchase' => 'Is certificate purchase',
		'source' => 'Source',
		'user_id' => 'User',
		/*'data_json' => 'Extra info',*/
		'created_at' => 'Created',
		'updated_at' => 'Updated',
		'deleted_at' => 'Deleted',
		'comment' => 'Comment',
		/*'uuid' => 'Uuid',*/
	];
	
	const CREATED_STATUS = 'deal_created';
	const IN_WORK_STATUS = 'deal_in_work';
	const CONFIRMED_STATUS = 'deal_confirmed';
	const PAUSED_STATUS = 'deal_paused';
	const RETURNED_STATUS = 'deal_returned';
	const CANCELED_STATUS = 'deal_canceled';
	const STATUSES = [
		self::CREATED_STATUS,
		self::IN_WORK_STATUS,
		self::CONFIRMED_STATUS,
		self::PAUSED_STATUS,
		self::RETURNED_STATUS,
		self::CANCELED_STATUS,
	];

	const ADMIN_SOURCE = 'admin';
	const CALENDAR_SOURCE = 'calendar';
	const WEB_SOURCE = 'web';
	const SOURCES = [
		self::ADMIN_SOURCE => 'Admin',
		self::CALENDAR_SOURCE => 'Calendar',
		self::WEB_SOURCE => 'Site',
	];
	
	const HOLIDAYS = [
		'11.11.2022',
		'24.11.2022',
		'25.11.2022',
		'25.12.2022',
		'26.12.2022',
		'01.01.2023',
		'02.01.2023',
		'16.01.2023',
		'20.02.2023',
		'29.05.2023',
		'19.06.2023',
		'04.07.2023',
		'04.09.2023',
		'09.10.2023',
		'10.11.2023',
		'11.11.2023',
		'23.11.2023',
		'25.12.2023',
	];
	
	const AMUSEMENT_TAX = 7;
	const SALES_TAX = 6;

	protected $revisionForceDeleteEnabled = true;
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['source', 'uuid'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'number',
		'status_id',
		'contractor_id',
		'product_id',
		'certificate_id',
		'city_id',
		'location_id',
		'flight_simulator_id',
		'name',
		'phone',
		'email',
		'amount',
		'tax',
		'total_amount',
		'currency_id',
		'promo_id',
		'promocode_id',
		'is_certificate_purchase',
		'user_id',
		'source',
		'uuid',
		'data_json',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
		'deleted_at' => 'datetime:Y-m-d H:i:s',
		'data_json' => 'array',
		'is_certificate_purchase' => 'boolean',
	];
	
	public static function boot() {
		parent::boot();
		
		Deal::created(function (Deal $deal) {
			$deal->number = $deal->generateNumber();
			$deal->uuid = (string)\Webpatser\Uuid\Uuid::generate();
			$deal->save();
			
			if ($deal->user_id) {
				$inWorkStatus = HelpFunctions::getEntityByAlias(Status::class, Deal::IN_WORK_STATUS);
				if ($inWorkStatus) {
					$deal->status_id = $inWorkStatus->id;
					$deal->save();
				}
			}
		});

		Deal::saved(function (Deal $deal) {
			if (!$deal->user_id && $deal->source == Deal::ADMIN_SOURCE) {
				$deal->user_id = Auth::user()->id;
				$deal->save();
			}
		});
	}
	
	public function contractor()
	{
		return $this->hasOne(Contractor::class, 'id', 'contractor_id');
	}
	
	public function product()
	{
		return $this->hasOne(Product::class, 'id', 'product_id');
	}
	
	public function currency()
	{
		return $this->hasOne(Currency::class, 'id', 'currency_id');
	}
	
	public function certificate()
	{
		return $this->hasOne(Certificate::class, 'id', 'certificate_id');
	}
	
	public function bills()
	{
		return $this->hasMany(Bill::class, 'deal_id', 'id');
	}
	
	public function firstBill()
	{
		return $this->hasMany(Bill::class, 'deal_id', 'id')
			->oldest()
			->first();
	}
	
	public function lastPaidBill()
	{
		return $this->hasMany(Bill::class, 'deal_id', 'id')
			->whereRelation('status', 'statuses.alias', '=', Bill::PAYED_STATUS)
			->latest()
			->first();
	}
	
	public function status()
	{
		return $this->hasOne(Status::class, 'id', 'status_id');
	}
	
	public function event()
	{
		return $this->hasOne(Event::class, 'deal_id', 'id');
	}
	
	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
	
	public function city()
	{
		return $this->hasOne(City::class, 'id', 'city_id');
	}
	
	public function location()
	{
		return $this->hasOne(Location::class, 'id', 'location_id');
	}
	
	public function simulator()
	{
		return $this->hasOne(FlightSimulator::class, 'id', 'flight_simulator_id');
	}
	
	public function promocode()
	{
		return $this->hasOne(Promocode::class, 'id', 'promocode_id');
	}
	
	public function promo()
	{
		return $this->hasOne(Promo::class, 'id', 'promo_id');
	}
	
	/**
	 * @return string
	 */
	public function generateNumber()
	{
		return 'D' . date('y') . sprintf('%05d', $this->id);
	}
	
	/**
	 * @return int
	 */
	public function amount()
	{
		return $this->amount;
	}

	/**
	 * @return int
	 */
	public function totalAmount()
	{
		return $this->total_amount;
	}
	
	/**
	 * @return int
	 */
	public function billPayedAmount()
	{
		$amount = 0;
		foreach ($this->bills ?? [] as $bill) {
			$status = $bill->status;
			if (!$status) continue;
			if ($bill->status->alias != Bill::PAYED_STATUS) continue;
			
			$amount += $bill->amount;
		}
		
		return round($amount, 2);
	}

	/**
	 * @return int
	 */
	public function billPayedTotalAmount()
	{
		$amount = 0;
		foreach ($this->bills ?? [] as $bill) {
			$status = $bill->status;
			if (!$status) continue;
			if ($bill->status->alias != Bill::PAYED_STATUS) continue;

			$amount += $bill->total_amount;
		}

		return round($amount, 2);
	}
	
	/**
	 * @return int
	 */
	public function billAmount()
	{
		$amount = 0;
		foreach ($this->bills ?? [] as $bill) {
			$status = $bill->status;
			if (!$status) continue;
			if ($bill->status->alias == Bill::CANCELED_STATUS) continue;
			
			$amount += $bill->amount;
		}
		
		return round($amount, 2);
	}

	/**
	 * @return int
	 */
	public function billTotalAmount()
	{
		$amount = 0;
		foreach ($this->bills ?? [] as $bill) {
			$status = $bill->status;
			if (!$status) continue;
			if ($bill->status->alias == Bill::CANCELED_STATUS) continue;
			
			$amount += $bill->total_amount;
		}
		
		return round($amount, 2);
	}

	/**
	 * @return int
	 */
	public function balance()
	{
		return round($this->billPayedTotalAmount() - $this->totalAmount(), 2);
	}
	
	/**
	 * @return int
	 */
	public function passiveBalance()
	{
		return round($this->billTotalAmount() - $this->totalAmount(), 2);
	}
	
	/**
	 * @return string
	 */
	public function phoneFormatted()
	{
		$phoneCleared = preg_replace( '/[^0-9]/', '', $this->phone);
		return '+' . mb_substr($phoneCleared, 0, 1) . ' (' . mb_substr($phoneCleared, 1, 3) . ') ' . mb_substr($phoneCleared, 4, 3) . '-' . mb_substr($phoneCleared, 7, 2) . '-' . mb_substr($phoneCleared, 9, 2);
	}
}
