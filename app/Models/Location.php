<?php

namespace App\Models;

use App\Services\HelpFunctions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $name наименование
 * @property string $alias alias
 * @property int $city_id город, в котором находится локация
 * @property int $sort сортировка
 * @property array|null $data_json дополнительная информация
 * @property bool $is_active признак активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlightSimulator[] $simulator
 * @property-read int|null $simulator_count
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Query\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Location withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlightSimulator[] $simulators
 * @property-read int|null $simulators_count
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNameEn($value)
 * @property string|null $pay_account_number номер счета в платежной системе
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePayAccountNumber($value)
 * @property int $legal_entity_id юр.лицо, на которое оформлена локация
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLegalEntityId($value)
 */
class Location extends Model
{
	use HasFactory, SoftDeletes;
	
	const DC_LOCATION = 'dc';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'name',
		'alias',
		'city_id',
		'sort',
		'data_json',
		'is_active',
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
		'is_active' => 'boolean',
		'data_json' => 'array',
	];

	public function city()
	{
		return $this->belongsTo(City::class, 'city_id', 'id');
	}
	
	public function simulators()
	{
		return $this->belongsToMany(FlightSimulator::class, 'locations_flight_simulators', 'location_id', 'flight_simulator_id')
			->withPivot('data_json')
			->withTimestamps();
	}
	
	public function user()
	{
		return $this->hasMany(User::class, 'location_id', 'id');
	}
	
	/**
	 * @return int
	 */
	public function countBillsInCurrentMonth()
	{
		$onlinePaymentMethod = HelpFunctions::getEntityByAlias(PaymentMethod::class, PaymentMethod::ONLINE_ALIAS);
		if (!$onlinePaymentMethod) return 0;
		
		$billCount = Bill::where('payment_method_id', $onlinePaymentMethod->id)
			->where('location_id', $this->id)
			->where('user_id', 0)
			->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
			->count();
		
		return $billCount;
	}
}
