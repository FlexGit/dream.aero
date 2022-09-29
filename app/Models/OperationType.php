<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\OperationType
 *
 * @property int $id
 * @property string $name наименование типа операции
 * @property string $alias алиас
 * @property bool $is_active признак активности
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType newQuery()
 * @method static \Illuminate\Database\Query\Builder|OperationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType query()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|OperationType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OperationType withoutTrashed()
 * @mixin \Eloquent
 */
class OperationType extends Model
{
    use HasFactory, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'name',
		'alias',
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
	];
}
