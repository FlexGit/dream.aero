<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name наименование продукта
 * @property string $alias алиас
 * @property int $product_type_id тип продукта
 * @property int $user_id пользователь
 * @property int $duration длительность полёта, мин.
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\ProductType|null $productType
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 * @property int $employee_id пилот
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEmployeeId($value)
 * @property string|null $public_name
 * @property bool $is_active
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePublicName($value)
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;
	
	const BASIC_ALIAS = 'basic';
	const ADVANCED_ALIAS = 'advanced';
	const EXPERT_ALIAS = 'expert';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'name',
		'public_name',
		'alias',
		'product_type_id',
		'user_id',
		'duration',
		'is_active',
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
		'is_active' => 'boolean',
	];
	
	public function productType()
	{
		return $this->hasOne(ProductType::class, 'id', 'product_type_id');
	}
	
	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
	
	public function cities()
	{
		return $this->belongsToMany(City::class, 'cities_products', 'product_id', 'city_id')
			->using(CityProduct::class)
			->withPivot(['availability', 'purchase_price', 'price', 'currency_id', 'discount_id', 'is_hit', 'is_active', 'data_json'])
			->withTimestamps();
	}
	
	/**
	 * @param $flightAt
	 * @return bool
	 */
	public function validateFlightDate($flightAt)
	{
		$alias = $this->productType->alias ?? null;
		if (!$alias) return false;

		$flightAt = Carbon::parse($flightAt)->format('d.m.Y');
		
		// Regular доступен для заказа только в будни
		if ($alias == ProductType::REGULAR_ALIAS && (in_array(date('w', strtotime($flightAt)), [0, 6]) || in_array($flightAt, Deal::HOLIDAYS))) return false;
		
		return true;
	}
	
	/**
	 * @param $contractorId
	 * @param $cityId
	 * @param string $source
	 * @param bool $isFree
	 * @param int $locationId
	 * @param int $paymentMethodId
	 * @param int $promoId
	 * @param int $promocodeId
	 * @param int $certificateId
	 * @param bool $isUnified
	 * @param bool $isAirlineMilesPurchase
	 * @param int $score
	 * @param bool $isCertificatePurchase
	 * @return float|int|mixed
	 */
	public function calcAmount($contractorId, $cityId, $source = 'api', $isFree = false, $locationId = 0, $paymentMethodId = 0, $promoId = 0, $promocodeId = 0, $certificateId = 0, $isUnified = false, $isAirlineMilesPurchase = false, $score = 0, $isCertificatePurchase = false)
	{
		if ($isFree) return 0;
		
		$certificateProductAmount = 0;
		if (!$isCertificatePurchase && $certificateId) {
			$date = date('Y-m-d');
			// проверка сертификата на валидность
			$certificate = Certificate::whereIn('city_id', [$cityId, 0])
				->whereIn('product_id', [$this->id, 0])
				->where(function ($query) use ($date) {
					$query->where('expire_at', '>=', $date)
						->orWhereNull('expire_at');
				})->find($certificateId);
			if ($certificate) return 0;
			
			$certificate = Certificate::find($certificateId);
			$certificateProduct = $certificate->product;
			if ($certificateProduct && $certificateProduct->alias != $this->alias) {
				$certificateCityProduct = $certificateProduct->cities()->where('cities_products.is_active', true)->find($cityId);
				if ($certificateCityProduct && $certificateCityProduct->pivot) {
					$certificateProductAmount = $certificateCityProduct->pivot->price;
				}
			}
		}
		
		$contractor = $contractorId ? Contractor::whereIsActive(true)->find($contractorId) : null;

		$promo = $promoId ? Promo::whereIsActive(true)->find($promoId) : null;

		$promocode = $promocodeId ? Promocode::find($promocodeId) : null;
		if ($promocode) {
			if (!$promocode->contractor_id) {
				$validPromocodeCity = $promocode->cities()->where('cities.id', '=', $cityId)->exists();
				if (!$validPromocodeCity) {
					$promocode = null;
				}
			}
		}

		$cityProduct = $this->cities()->where('cities_products.is_active', true)->find($cityId);
		if (!$cityProduct || !$cityProduct->pivot) return 0;

		// базовая стоимость продукта
		$amount = $cityProduct->pivot->price;
		
		// если это бронирование по Сертификату, выбран иной тариф, и стоимомть текущего тарифа больше,
		// то вычисляем разницу для доплаты
		$amount -= $certificateProductAmount;
		if ($amount < 0) $amount = 0;
		
		// скидка на продукт
		$discount = $cityProduct->pivot->discount ?? null;
		if ($discount) {
			$amount = $discount->is_fixed ? ($amount - $discount->value) : ($amount - $amount * $discount->value / 100);

			return ($amount > 0) ? round($amount, 2) : 0;
		}
		
		// скидка по промокоду/акции/клиента действует только для типов тарифов Regular и Ultimate
		if ($this->productType && in_array($this->productType->alias, [ProductType::REGULAR_ALIAS, ProductType::ULTIMATE_ALIAS])) {
			// скидка по промокоду
			if ($promocode) {
				$discount = $promocode->discount ?? null;
				if ($discount && (!$promocode->location_id || $promocode->location_id == $locationId)) {
					$amount = $discount->is_fixed ? ($amount - $discount->value) : ($amount - $amount * $discount->value / 100);
					
					return ($amount > 0) ? round($amount, 2) : 0;
				}
			}
			
			// скидка по указанной акции
			if ($promo) {
				$discount = $promo->discount ?? null;
				if ($discount) {
					$amount = $discount->is_fixed ? ($amount - $discount->value) : ($amount - $amount * $discount->value / 100);
					
					return ($amount > 0) ? round($amount, 2) : 0;
				}
			}

			$date = date('Y-m-d');

			$amounts = [];

			// активные акции для публикации со скидкой
			$promos = Promo::where('is_active', true)
				->where('is_published', true)
				->where('discount_id', '!=', 0)
				->where('alias', '!=', Promo::BIRTHDAY_ALIAS)
				->whereIn('city_id', [$cityId, 0])
				->where(function ($query) use ($date) {
					$query->where('active_from_at', '<=', $date)
						->orWhereNull('active_from_at');
				})
				->where(function ($query) use ($date) {
					$query->where('active_to_at', '>=', $date)
						->orWhereNull('active_to_at');
				})
				->orderByDesc('active_from_at')
				->get();
			foreach ($promos as $promo) {
				$discount = $promo->discount ?? null;
				if ($discount) {
					$amount = $discount->is_fixed ? ($amount - $discount->value) : ($amount - $amount * $discount->value / 100);
					$amounts[] = ($amount > 0) ? round($amount, 2) : 0;
				}
			}
			
			if ($amounts) {
				// применяем с наибольшей скидкой
				rsort($amounts);
				$amount = array_shift($amounts);
				return $amount;
			}

			// скидка контрагента
			if ($contractor) {
				$discount = $contractor->discount();
				$amount = $discount->is_fixed ? ($amount - $discount->value) : ($amount - $amount * $discount->value / 100);
			}
		}

		return ($amount > 0) ? round($amount, 2) : 0;
	}
}
