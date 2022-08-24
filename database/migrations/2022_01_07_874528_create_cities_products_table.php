<?php

use App\Models\City;
use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\HelpFunctions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities_products', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id')->nullable(false)->index();
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->unsignedBigInteger('city_id')->nullable(false)->index();
			$table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
			$table->integer('availability')->default(0)->comment('наличие');
			$table->float('purchase_price')->default(0)->comment('цена закупки продукта');
			$table->float('price')->default(0)->comment('базовая цена продукта');
			$table->integer('currency_id')->default(0)->index()->comment('валюта');
			$table->integer('discount_id')->default(0)->index()->comment('скидка на продукт');
			$table->boolean('is_hit')->default(0)->comment('является ли продукт хитом продаж');
			$table->boolean('is_active')->default(true)->index()->comment('признак активности');
			$table->text('data_json')->nullable()->comment('дополнительная информация');
			$table->timestamps();
        });
	
		$regular = HelpFunctions::getEntityByAlias(ProductType::class,ProductType::REGULAR_ALIAS);
		$ultimate = HelpFunctions::getEntityByAlias(ProductType::class,ProductType::ULTIMATE_ALIAS);
		$courses = HelpFunctions::getEntityByAlias(ProductType::class,ProductType::COURSES_ALIAS);
		$services = HelpFunctions::getEntityByAlias(ProductType::class,ProductType::SERVICES_ALIAS);
	
		$city = HelpFunctions::getEntityByAlias(City::class, City::DC_ALIAS);
		$currency = HelpFunctions::getEntityByAlias(Currency::class, Currency::USD_ALIAS);
	
		$items = [];
	
		$items[] = [
			'name' => 'Regular 15',
			'public_name' => 'Regular 15',
			'alias' => 'regular_15',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 15,
			'price' => 0,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/clock30.png"}',
		];
		$items[] = [
			'name' => 'Regular 30',
			'public_name' => 'GO-AROUND',
			'alias' => 'regular_30',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 30,
			'price' => 175,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/clock60.png"}',
		];
		$items[] = [
			'name' => 'Regular 60',
			'public_name' => 'SHORT-HAUL',
			'alias' => 'regular_60',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 60,
			'price' => 285,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/clock90.png"}',
		];
		$items[] = [
			'name' => 'Regular 90',
			'public_name' => 'MEDIUM-HAUL',
			'alias' => 'regular_90',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 90,
			'price' => 390,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/clock120.png"}',
		];
		$items[] = [
			'name' => 'Regular 120',
			'public_name' => 'LONG-HAUL',
			'alias' => 'regular_120',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 120,
			'price' => 495,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/clock180.png"}',
		];
		$items[] = [
			'name' => 'Regular 180',
			'public_name' => 'ULTRA LONG-HAUL',
			'alias' => 'regular_180',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 180,
			'price' => 695,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
		$items[] = [
			'name' => 'Ultimate 30',
			'public_name' => 'GO-AROUND',
			'alias' => 'ultimate_30',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 30,
			'price' => 215,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
		$items[] = [
			'name' => 'Ultimate 60',
			'public_name' => 'SHORT-HAUL',
			'alias' => 'ultimate_60',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 60,
			'price' => 335,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
		$items[] = [
			'name' => 'Ultimate 90',
			'public_name' => 'MEDIUM-HAUL',
			'alias' => 'ultimate_90',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 90,
			'price' => 450,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
		$items[] = [
			'name' => 'Ultimate 120',
			'public_name' => 'LONG-HAUL',
			'alias' => 'ultimate_120',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 120,
			'price' => 585,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
		$items[] = [
			'name' => 'Ultimate 180',
			'public_name' => 'ULTRA LONG-HAUL',
			'alias' => 'ultimate_180',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 180,
			'price' => 825,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
		$items[] = [
			'name' => 'BASIC PACKAGE',
			'public_name' => 'BASIC PACKAGE',
			'alias' => 'basic',
			'product_type_id' => $courses ? $courses->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 360,
			'price' => 1390,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/kurs.png"}',
		];
		$items[] = [
			'name' => 'ADVANCED PACKAGE',
			'public_name' => 'ADVANCED PACKAGE',
			'alias' => 'advanced',
			'product_type_id' => $courses ? $courses->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 360,
			'price' => 2490,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/kurs.png"}',
		];
		$items[] = [
			'name' => 'KID’S PILOT SCHOOL',
			'public_name' => 'KID’S PILOT SCHOOL',
			'alias' => 'kids_school',
			'product_type_id' => $courses ? $courses->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 360,
			'price' => 1290,
			'currency_id' => $currency->id ?? 0,
			'data_json' => '{"icon_file_path":"product\/icon\/kurs.png"}',
		];
		$items[] = [
			'name' => 'Video',
			'public_name' => 'Video',
			'alias' => 'video',
			'product_type_id' => $services ? $services->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 0,
			'price' => 10,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
		$items[] = [
			'name' => 'Photo',
			'public_name' => 'Photo',
			'alias' => 'photo',
			'product_type_id' => $services ? $services->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 0,
			'price' => 5,
			'currency_id' => $currency->id ?? 0,
			'data_json' => null,
		];
	
		foreach ($items as $item) {
			$product = new Product();
			$product->name = $item['name'];
			$product->public_name = $item['public_name'];
			$product->alias = $item['alias'];
			$product->product_type_id = $item['product_type_id'];
			$product->duration = $item['duration'];
			$product->data_json = $item['data_json'];
			$product->save();

			$product->cities()->attach($city->id, [
				'availability' => 0,
				'purchase_price' => 0,
				'price' => $item['price'],
				'currency_id' => $item['currency_id'],
			]);
		}
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities_products');
    }
}
