<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\LegalEntity
 *
 * @property int $id
 * @property string $name наименование юр.лица
 * @property string $alias алиас
 * @property array|null $data_json дополнительная информация
 * @property bool $is_active признак активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|LegalEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|LegalEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LegalEntity withoutTrashed()
 * @mixin \Eloquent
 */
class LegalEntity extends Model
{
	use HasFactory, SoftDeletes;

	const DC_ALIAS = 'dc';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'name',
		'alias',
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
	
	/**
	 * @return array
	 */
	public function format()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'public_offer_file_path' => (is_array($this->data_json) && array_key_exists('public_offer_file_path', $this->data_json)) ? \URL::to('/upload/' . $this->data_json['public_offer_file_path']) : null,
		];
	}
}
