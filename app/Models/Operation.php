<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Venturecraft\Revisionable\RevisionableTrait;

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
		'type',
		'payment_method_id',
		'amount',
		'currency_id',
		'city_id',
		'location_id',
		'operated_at',
		'user_id',
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
	];
	
	const EXPENSE = 'expense';
	const REFUND = 'refund';
	const TYPES = [
		self::EXPENSE => 'Expenses',
		self::REFUND => 'Refund',
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
}
