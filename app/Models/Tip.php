<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\Tip
 *
 * @property int $id
 * @property int $deal_id сделка
 * @property float $amount сумма
 * @property int $currency_id валюта
 * @property int $city_id город
 * @property \Illuminate\Support\Carbon|null $received_at дата получения
 * @property int $admin_id
 * @property int $pilot_id
 * @property string|null $source источник
 * @property int $user_id пользователь
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $admin
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\Deal|null $deal
 * @property-read \App\Models\User|null $pilot
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newQuery()
 * @method static \Illuminate\Database\Query\Builder|Tip onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip wherePilotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Tip withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Tip withoutTrashed()
 * @mixin \Eloquent
 */
class Tip extends Model
{
	use HasFactory, SoftDeletes, RevisionableTrait;
	
	const ATTRIBUTES = [
		'deal_id' => 'Deal',
		'amount' => 'Amount',
		'currency_id' => 'Currency',
		'city_id' => 'City',
		'received_at' => 'Received',
		'admin_id' => 'Admin',
		'pilot_id' => 'Pilot',
		'source' => 'Source',
		'user_id' => 'User',
		'created_at' => 'Created',
		'updated_at' => 'Updated',
		'deleted_at' => 'Deleted',
	];

	protected $revisionForceDeleteEnabled = true;
	protected $revisionCreationsEnabled = true;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'deal_id',
		'amount',
		'currency_id',
		'city_id',
		'received_at',
		'admin_id',
		'pilot_id',
		'source',
		'user_id',
	];
	
	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'received_at' => 'date:Y-m-d',
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
		'deleted_at' => 'datetime:Y-m-d H:i:s',
	];
	
	const CARD = 'card';
	const CASH = 'cash';
	const VENMO = 'venmo';
	const ETIP = 'etip';
	const SOURCES = [
		self::CARD => 'Credit card',
		self::CASH => 'Cash',
		self::VENMO => 'Venmo',
		self::ETIP => 'eTip',
	];
	
	public function deal()
	{
		return $this->belongsTo(Deal::class);
	}
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function currency()
	{
		return $this->hasOne(Currency::class, 'id', 'currency_id');
	}
	
	public function city()
	{
		return $this->belongsTo(City::class);
	}
	
	public function admin()
	{
		return $this->belongsTo(User::class);
	}
	
	public function pilot()
	{
		return $this->belongsTo(User::class);
	}
}
