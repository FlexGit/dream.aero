<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\Operation
 *
 * @property int $id
 * @property string|null $type тип операции
 * @property int $payment_method_id способ оплаты
 * @property float $amount сумма
 * @property int $currency_id валюта
 * @property int $city_id город
 * @property int $location_id локация
 * @property \Illuminate\Support\Carbon|null $operated_at дата операции
 * @property int $user_id пользователь
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Operation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereOperatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Operation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Operation withoutTrashed()
 * @mixin \Eloquent
 * @property array|null $data_json
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereDataJson($value)
 * @property int $operation_type_id тип операции
 * @property-read \App\Models\OperationType|null $operationType
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereOperationTypeId($value)
 */
class Operation extends Model
{
	use HasFactory, SoftDeletes, RevisionableTrait;
	
	const ATTRIBUTES = [
		'type' => 'Type',
		'payment_method_id' => 'Payment method',
		'amount' => 'Amount',
		'currency_id' => 'Currency',
		'city_id' => 'City',
		'location_id' => 'Location',
		'operated_at' => 'Operation date',
		'user_id' => 'User',
		'data_json' => 'Extra info',
		'created_at' => 'Created',
		'updated_at' => 'Updated',
		'deleted_at' => 'Deleted',
	];

	protected $revisionForceDeleteEnabled = true;
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['data_json'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'type',
		'payment_method_id',
		'amount',
		'currency_id',
		'city_id',
		'location_id',
		'operated_at',
		'user_id',
		'data_json'
	];
	
	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'operated_at' => 'date:Y-m-d',
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
		'deleted_at' => 'datetime:Y-m-d H:i:s',
		'data_json' => 'array',
	];

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
	
	public function location()
	{
		return $this->belongsTo(Location::class);
	}
	
	public function paymentMethod()
	{
		return $this->belongsTo(paymentMethod::class);
	}
	
	public function operationType()
	{
		return $this->belongsTo(OperationType::class);
	}
}
