<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property int $city_id город
 * @property int $location_id локация
 * @property bool $enable
 * @property string|null $remember_token
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\City $city
 * @property-read \App\Models\Location $location
 * @property string|null $lastname
 * @property string|null $middlename
 * @property string|null $data_json
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddlename($value)
 * @property \Illuminate\Support\Carbon|null $birthdate дата рождения
 * @property string|null $phone Телефон
 * @property string|null $position должность
 * @property int $is_reserved признак резервного сотрудника
 * @property int $is_official признак официального трудоустройства
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsOfficial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsReserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePosition($value)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable;

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
	const ROLE_PILOT = 'pilot';
    const ROLES = [
    	self::ROLE_SUPERADMIN => 'Superadmin',
		self::ROLE_ADMIN => 'Admin',
		self::ROLE_PILOT => 'Pilot',
	];
 
	/**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
		'lastname',
        'name',
		'middlename',
        'email',
        'password',
		'role',
		'city_id',
		'location_id',
		'phone',
		'birthdata',
		'position',
		'is_reserved',
		'is_official',
		'enable',
		'data_json',
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
		'birthdate' => 'datetime',
		'enable' => 'boolean',
		'data_json' => 'array',
    ];
	
	/**
	 * @return bool
	 */
	public function isSuperAdmin()
	{
		return $this->role == self::ROLE_SUPERADMIN;
	}

	/**
	 * @return bool
	 */
	public function isAdmin()
	{
		return $this->role == self::ROLE_ADMIN;
	}
	
	/**
	 * @return bool
	 */
	public function isAdminOrHigher()
	{
		return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPERADMIN]);
	}

	/**
	 * @return bool
	 */
	public function isPilot()
	{
		return $this->role == self::ROLE_PILOT;
	}

	public function city()
	{
		return $this->belongsTo(City::class, 'city_id', 'id');
	}
	
	public function location()
	{
		return $this->belongsTo(Location::class, 'location_id', 'id');
	}

	/**
	 * @return string
	 */
	public function fio()
	{
		return $this->lastname . ' ' . $this->name . ' ' . $this->middlename;
	}
	
	/**
	 * @return string
	 */
	public function fioFormatted()
	{
		if (!$this->lastname && $this->name) {
			return $this->name;
		}
		
		return $this->lastname . ($this->name ? ' ' . mb_substr($this->name, 0, 1) . '.' : '') . ($this->middlename ? ' ' . mb_substr($this->middlename, 0, 1) . '.' : '');
	}
	
	/**
	 * @return array
	 */
	public function format()
	{
		$data = $this->data_json ?? [];

		return [
			'id' => $this->id,
			'fullname' => $this->fio(),
			'instagram' => array_key_exists('instagram', $data) ? $data['instagram'] : null,
		];
	}
}
