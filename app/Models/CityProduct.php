<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CityProduct
 *
 * @property-read \App\Models\Discount $discount
 * @method static \Illuminate\Database\Eloquent\Builder|CityProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityProduct query()
 * @mixin \Eloquent
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property-read \App\Models\Currency|null $currency
 */
class CityProduct extends Pivot
{
	use HasFactory;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'product_id',
		'city_id',
		'availability',
		'purchase_price',
		'price',
		'currency_id',
		'discount_id',
		'is_hit',
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
		'is_hit' => 'boolean',
		'is_active' => 'boolean',
		'data_json' => 'array',
	];

	public function discount()
	{
		return $this->hasOne(Discount::class, 'id', 'discount_id');
	}

	public function currency()
	{
		return $this->hasOne(Currency::class, 'id', 'currency_id');
	}
}
