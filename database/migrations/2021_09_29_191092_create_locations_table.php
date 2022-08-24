<?php

use App\Models\City;
use App\Models\Location;
use App\Models\LegalEntity;
use App\Services\HelpFunctions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('наименование локации');
			$table->string('alias', 50)->comment('alias');
			$table->integer('city_id')->default(0)->index()->comment('город, в котором находится локация');
			$table->integer('sort')->default(0)->comment('сортировка');
			$table->text('data_json')->nullable()->comment('дополнительная информация');
			$table->boolean('is_active')->default(true)->index()->comment('признак активности');
            $table->timestamps();
			$table->softDeletes();
        });
	
		$dcLegalEntity = HelpFunctions::getEntityByAlias(LegalEntity::class, LegalEntity::DC_ALIAS);
	
		$items = [];
	
		// Dubai
		$city = HelpFunctions::getEntityByAlias(City::class, City::UAE_ALIAS);
	
		$items[] = [
			'name' => 'Festival City Mall',
			'alias' => 'uae',
			'legal_entity_id' => 0,
			'city_id' => $city ? $city->id : 0,
			'sort' => 10,
			'data' => [
				'address' => 'Dubai Festival City Mall
Ground Floor, next to Centrepoint Dubai, UAE',
				'working_hours' => 'Sunday - Thursday - 10 AM - 12 PM<br>Thursday - Friday - 10 AM - 01 PM',
				'phone' => '',
				'email' => 'dubai@dream-aero.com',
				'map_link' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.4452858227983!2d55.3499096143887!3d25.221922936887697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f5d778cffffff%3A0xf49b6296189d22d5!2sDubai+Festival+City+Mall!5e0!3m2!1sen!2sru!4v1526977093496',
				'skype' => null,
				'whatsapp' => '',
				'scheme_file_path' => null,
				'pay_system' => [
					'alias' => null,
					'account_number' => null,
				],
			],
		];
	
		// Washington D.C.
		$city = HelpFunctions::getEntityByAlias(City::class, City::DC_ALIAS);
	
		$items[] = [
			'name' => 'WESTFIELD Montgomery Mall',
			'alias' => 'usa',
			'legal_entity_id' => $dcLegalEntity ? $dcLegalEntity->id : 0,
			'city_id' => $city ? $city->id : 0,
			'sort' => 10,
			'data' => [
				'address' => 'WESTFIELD Montgomery Mall<br>7101 Democracy Boulevard Store No. 3100 Bethesda MD 20817',
				'working_hours' => 'Sunday 11:00 AM to 7:00 PM<br>Monday - Thursday 12:00 PM to 8:00 PM<br>Friday,Saturday 11:00 AM to 8:00 PM',
				'phone' => '+1 240 224 48 85',
				'email' => 'dc@dream.aero',
				'map_link' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12398.45561714155!2d-77.1459884!3d39.0241201!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xa8ce96213e429d83!2sWestfield+Montgomery!5e0!3m2!1sen!2sru!4v1557729373131!5m2!1sen!2sru',
				'skype' => null,
				'whatsapp' => '00971505587151',
				'scheme_file_path' => 'scheme/dc_westfield.webp',
				'pay_system' => [
					'alias' => null,
					'account_number' => null,
				],
			],
		];

		foreach ($items as $item) {
			$location = new Location();
			$location->name = $item['name'];
			$location->alias = $item['alias'];
			$location->legal_entity_id = $item['legal_entity_id'];
			$location->city_id = $item['city_id'];
			$location->sort = $item['sort'];
			$location->data_json = $item['data'];
			$location->save();
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
