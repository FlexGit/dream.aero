<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\NotificationContractor
 *
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationContractor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationContractor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationContractor query()
 * @mixin \Eloquent
 */
class NotificationContractor extends Pivot
{
	use HasFactory;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'notification_id',
		'contractor_id',
		'is_new',
	];
	
	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
		'updated_at' => 'datetime:Y-m-d H:i:s',
		'is_new' => 'boolean',
	];
}
