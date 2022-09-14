<?php

namespace App\Models;

use App\Services\HelpFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\Contractor
 *
 * @property int $id
 * @property string $name имя
 * @property string|null $lastname фамилия
 * @property \datetime|null $birthdate дата рождения
 * @property string|null $phone основной номер телефона
 * @property string $email основной e-mail
 * @property string|null $password пароль в md5
 * @property string|null $remember_token
 * @property int $city_id город, к которому привязан контрагент
 * @property int $discount_id скидка
 * @property int $user_id пользователь
 * @property bool $is_active признак активности
 * @property \datetime|null $last_auth_at дата последней по времени авторизации
 * @property string|null $source источник
 * @property string|null $uuid uuid
 * @property bool $is_subscribed подписан на рассылку
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Discount|null $discount
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor newQuery()
 * @method static \Illuminate\Database\Query\Builder|Contractor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereIsSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereLastAuthAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Contractor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Contractor withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Promocode[] $contractorPromocodes
 * @property-read int|null $contractor_promocodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Promocode[] $promocodes
 * @property-read int|null $promocodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bill[] $bills
 * @property-read int|null $bills_count
 */
class Contractor extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, SoftDeletes, RevisionableTrait;
	
	const ATTRIBUTES = [
		'name' => 'Name',
		'lastname' => 'Lastname',
		'birthdate' => 'Birthdate',
		'phone' => 'Phone',
		'email' => 'E-mail',
		'password' => 'Password',
		/*'city_id' => 'City',*/
		/*'discount_id' => 'Discount',*/
		/*'source' => 'Source',*/
		/*'data_json' => 'Extra info',*/
		'is_active' => 'Is active',
		'last_auth_at' => 'Last auth',
		'user_id' => 'User',
		/*'uuid' => 'Uuid',*/
		/*'is_subscribed' => 'Subscribed',*/
		'created_at' => 'Created',
		'updated_at' => 'Updated',
		'deleted_at' => 'Deleted',
	];
	
	CONST RESEND_CODE_INTERVAL = 25;
	CONST CODE_TTL = 5 * 60;
	
	const ADMIN_SOURCE = 'admin';
	const CALENDAR_SOURCE = 'calendar';
	const WEB_SOURCE = 'web';
	const SOURCES = [
		self::ADMIN_SOURCE => 'Admin',
		self::CALENDAR_SOURCE => 'Calendar',
		self::WEB_SOURCE => 'Web',
	];

	const ANONYM_EMAIL = 'anonym@dream.aero';
	
	protected $revisionForceDeleteEnabled = true;
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['password', 'source', 'data_json', 'last_auth_at', 'uuid', 'city_id', 'is_subscribed'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'name',
		'lastname',
		'birthdate',
		'phone',
		'email',
		'password',
		'city_id',
		/*'discount_id',*/
		'source',
		'data_json',
		'is_active',
		'last_auth_at',
		'user_id',
		'uuid',
		'is_subscribed',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
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
		'email_verified_at' => 'datetime',
		'last_auth_at' => 'datetime:Y-m-d H:i:s',
		'birthdate' => 'datetime:Y-m-d',
		'is_active' => 'boolean',
		'data_json' => 'array',
		'is_subscribed' => 'boolean',
	];
	
	public static function boot()
	{
		parent::boot();
		
		Contractor::created(function (Contractor $contractor) {
			$contractor->uuid = $contractor->generateUuid();
			$contractor->save();
		});

		Contractor::deleting(function (Contractor $contractor) {
			$contractor->tokens()->delete();
		});
	}
	
	public function city()
	{
		return $this->hasOne(City::class, 'id', 'city_id');
	}
	
	public function bills()
	{
		return $this->hasMany(Bill::class, 'contractor_id', 'id');
	}
	
	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
	
	public function contractorPromocodes()
	{
		return $this->belongsToMany(Promocode::class, 'contractors_promocodes', 'promocode_id', 'contractor_id')
			->using(ContractorPromocode::class)
			->withPivot(['was_used'])
			->withTimestamps();
	}
	
	public function promocodes()
	{
		return $this->belongsToMany(Promocode::class, 'cities_promocodes', 'city_id', 'promocode_id')
			->using(CityPromocode::class)
			->withTimestamps();
	}
	
	/*public function notifications()
	{
		return $this->belongsToMany(Notification::class, 'notifications_contractors', 'contractor_id', 'notification_id')
			->using(NotificationContractor::class)
			->withPivot(['is_new'])
			->withTimestamps();
	}*/
	
	/**
	 * @return string
	 * @throws \Exception
	 */
	public function generateUuid()
	{
		return (string)\Webpatser\Uuid\Uuid::generate();
	}
	
	/**
	 * @param $statuses
	 * @return int|mixed
	 */
	public function getBalance($statuses)
	{
		$dealReturnedStatusId = $dealCanceledStatusId = $billPayedStatusId = $certificateReturnedStatus = $certificateCanceledStatus = 0;
		foreach ($statuses ?? [] as $status) {
			if ($status->type == Status::STATUS_TYPE_DEAL && $status->alias == Deal::RETURNED_STATUS) {
				$dealReturnedStatusId = $status->id;
			}
			if ($status->type == Status::STATUS_TYPE_DEAL && $status->alias == Deal::CANCELED_STATUS) {
				$dealCanceledStatusId = $status->id;
			}
			if ($status->type == Status::STATUS_TYPE_BILL && $status->alias == Bill::PAYED_STATUS) {
				$billPayedStatusId = $status->id;
			}
			if ($status->type == Status::STATUS_TYPE_CERTIFICATE && $status->alias == Certificate::RETURNED_STATUS) {
				$certificateReturnedStatus = $status->id;
			}
			if ($status->type == Status::STATUS_TYPE_CERTIFICATE && $status->alias == Certificate::CANCELED_STATUS) {
				$certificateCanceledStatus = $status->id;
			}
		}
		if (!$dealReturnedStatusId || !$dealCanceledStatusId || !$billPayedStatusId) return 0;

		$dealSum = 0;
		$deals = Deal::whereNotIn('status_id', [$dealReturnedStatusId, $dealCanceledStatusId])
			->where('contractor_id', $this->id)
			->orWhereHas('certificate', function ($query) use ($certificateReturnedStatus, $certificateCanceledStatus) {
				return $query->whereNotIn('certificates.status_id', [$certificateReturnedStatus, $certificateCanceledStatus]);
			})
			->get();
		foreach ($deals as $deal) {
			$dealSum += $deal->amount;
		}

		$billSum = Bill::where('status_id', $billPayedStatusId)
			->where('contractor_id', $this->id)
			->sum('total_amount');

		return ($billSum - $dealSum);
	}

	/**
	 * @return string
	 */
	public function fio()
	{
		return trim(($this->name ?? '') . ' ' . ($this->lastname ?? ''));
	}
	
	/**
	 * @return string
	 */
	public function phoneFormatted()
	{
		$phoneCleared = preg_replace( '/[^0-9]/', '', $this->phone);
		//return '+' . mb_substr($phoneCleared, 0, 1) . ' (' . mb_substr($phoneCleared, 1, 3) . ') ' . mb_substr($phoneCleared, 4, 3) . '-' . mb_substr($phoneCleared, 7, 2) . '-' . mb_substr($phoneCleared, 9, 2);
		return '+' . $phoneCleared;
	}
	
	/**
	 * @return int
	 */
	public function paidBillsAmount()
	{
		$amount = 0;
		foreach ($this->bills as $bill) {
			if ($bill->status->alias != Bill::PAYED_STATUS) continue;
			
			$amount += $bill->total_amount;
		}
		
		return $amount;
	}
	
	/**
	 * @return mixed|null
	 */
	public function discount()
	{
		if ($this->email == self::ANONYM_EMAIL) {
			return new Discount([]);
		}
		
		$amount = $this->paidBillsAmount();
		if ($amount >= 500 && $amount < 1000) {
			return HelpFunctions::getEntityByAlias(Discount::class, Discount::DISCOUNT_5_ALIAS);
		}

		if ($amount >= 1000 && $amount < 1500) {
			return HelpFunctions::getEntityByAlias(Discount::class, Discount::DISCOUNT_10_ALIAS);
		}
		
		if ($amount >= 1500 && $amount < 2000) {
			return HelpFunctions::getEntityByAlias(Discount::class, Discount::DISCOUNT_15_ALIAS);
		}
		
		if ($amount >= 2000) {
			return HelpFunctions::getEntityByAlias(Discount::class, Discount::DISCOUNT_20_ALIAS);
		}
		
		return new Discount([]);
	}
}
