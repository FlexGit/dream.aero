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
			$table->integer('price')->default(0)->comment('базовая цена продукта');
			$table->integer('currency_id')->default(0)->index()->comment('валюта');
			$table->integer('discount_id')->default(0)->index()->comment('скидка на продукт');
			$table->boolean('is_hit')->default(0)->comment('является ли продукт хитом продаж');
			$table->integer('score')->default(0)->comment('количество баллов, начисляемое клиенту по продукту');
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
			'name' => 'Regular 30',
			'alias' => 'regular_30',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 30,
			'price' => 175,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Regular 60',
			'alias' => 'regular_60',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 60,
			'price' => 285,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Regular 90',
			'alias' => 'regular_90',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 90,
			'price' => 390,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Regular 120',
			'alias' => 'regular_120',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 120,
			'price' => 495,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Regular 180',
			'alias' => 'regular_180',
			'product_type_id' => $regular ? $regular->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 180,
			'price' => 695,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Ultimate 30',
			'alias' => 'ultimate_30',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 30,
			'price' => 215,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Ultimate 60',
			'alias' => 'ultimate_60',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 60,
			'price' => 335,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Ultimate 90',
			'alias' => 'ultimate_90',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 90,
			'price' => 450,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Ultimate 120',
			'alias' => 'ultimate_120',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 120,
			'price' => 585,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'Ultimate 180',
			'alias' => 'ultimate_180',
			'product_type_id' => $ultimate ? $ultimate->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 180,
			'price' => 825,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 6,
			],
		];
		$items[] = [
			'name' => 'BASIC PACKAGE',
			'alias' => 'basic',
			'product_type_id' => $courses ? $courses->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 360,
			'price' => 1390,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 12,
			],
		];
		$items[] = [
			'name' => 'ADVANCED PACKAGE',
			'alias' => 'advanced',
			'product_type_id' => $courses ? $courses->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 360,
			'price' => 2490,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 12,
			],
		];
		$items[] = [
			'name' => 'KID’S PILOT SCHOOL',
			'alias' => 'kids_school',
			'product_type_id' => $courses ? $courses->id : 0,
			'city_id' => $city ? $city->id : 0,
			'duration' => 360,
			'price' => 1290,
			'currency_id' => $currency->id ?? 0,
			'data' => [
				'is_certificate_purchase_allow' => true,
				'certificate_period' => 12,
			],
		];
	
		foreach ($items as $item) {
			$product = new Product();
			$product->name = $item['name'];
			$product->alias = $item['alias'];
			$product->product_type_id = $item['product_type_id'];
			$product->duration = $item['duration'];
			$product->save();

			$product->cities()->attach($city->id, ['price' => $item['price'], 'currency_id' => $item['currency_id'], 'score' => $item['score'] ?? 0, 'data_json' => json_encode($item['data'], JSON_UNESCAPED_UNICODE)]);
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
