<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			$table->string('lastname')->nullable()->comment('фамилия');
            $table->string('name')->comment('имя');
			$table->string('middlename')->nullable()->comment('отчество');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
			$table->string('role')->default('admin');
			$table->integer('city_id')->index()->default(0)->comment('город');
			$table->integer('location_id')->index()->default(0)->comment('локация');
			$table->string('phone', 50)->nullable()->comment('телефон');
			$table->date('birthdate')->nullable()->comment('дата рождения');
			$table->string('position')->nullable()->comment('должность');
			$table->boolean('is_reserved')->default(0)->comment('признак запасного сотрудника');
			$table->boolean('is_official')->default(0)->comment('признак официального трудоустройства');
			$table->tinyInteger('enable')->index()->default(1);
			$table->text('data_json')->nullable()->comment('дополнительная информация');
            $table->rememberToken();
            $table->timestamps();
			$table->softDeletes();
        });
	
		$users = [];

		$users[] = [
			'name' => 'Dmitry',
			'email' => 'webmanage@inbox.ru',
			'password' => '',
			'role' => User::ROLE_SUPERADMIN,
			'remember_token' => '',
		];
		$users[] = [
			'name' => 'Anton',
			'email' => 'anton.s@dream-aero.com',
			'password' => '',
			'role' => User::ROLE_SUPERADMIN,
			'remember_token' => '',
		];
		$users[] = [
			'name' => 'Victoria',
			'email' => 'viktoria.ch@dream.aero',
			'password' => '',
			'role' => User::ROLE_SUPERADMIN,
			'remember_token' => '',
		];

		foreach ($users as $item) {
			$user = new User();
			$user->name = $item['name'];
			$user->email = $item['email'];
			$user->password = $item['password'];
			$user->role = $item['role'];
			$user->remember_token = $item['remember_token'];
			$user->save();
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
