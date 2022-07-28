<?php

use App\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('наименование');
			$table->string('alias', 50)->comment('алиас');
			$table->string('email', 255)->comment('e-mail');
			$table->string('phone', 50)->comment('телефон');
			$table->integer('currency_id')->default(0)->index()->comment('валюта');
			$table->string('version', 25)->default('ru')->index()->comment('версия');
			$table->string('timezone', 50)->nullable()->comment('временная зона');
			$table->integer('sort')->default(0)->comment('сортировка');
			$table->boolean('is_active')->default(true)->index()->comment('признак активности');
			$table->text('data_json')->nullable()->comment('дополнительная информация');
            $table->timestamps();
			$table->softDeletes();
        });
	
		$cities = [
			'dc' => [
				'name' => 'Washington D.C.',
				'sort' => 10,
				'version' => 'en',
				'timezone' => 'America/New_York',
				'email' => 'dc@dream.aero',
				'phone' => '+1 240 224 48 85',
			],
			'uae' => [
				'name' => 'Dubai',
				'sort' => 20,
				'version' => 'en',
				'timezone' => 'Asia/Dubai',
				'email' => 'dubai@dream-aero.com',
				'phone' => '',
			],
		];
	
		foreach ($cities as $alias => $item) {
			$city = new City();
			$city->alias = $alias;
			$city->name = $item['name'];
			$city->sort = $item['sort'];
			$city->version = $item['version'];
			$city->timezone = $item['timezone'];
			$city->email = $item['email'];
			$city->phone = $item['phone'];
			$city->save();
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
