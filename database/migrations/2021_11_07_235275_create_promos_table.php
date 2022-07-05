<?php

use App\Models\Discount;
use App\Models\Promo;
use App\Services\HelpFunctions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
        Schema::create('promos', function (Blueprint $table) {
			$table->id();
            $table->string('name')->comment('наименование');
			$table->string('alias')->nullable()->comment('алиас');
			$table->integer('discount_id')->default(0)->index()->comment('скидка');
            $table->text('preview_text')->nullable()->comment('анонс');
            $table->text('detail_text')->nullable()->comment('описание');
			$table->integer('city_id')->default(0)->index()->comment('город, к которому относится акция');
			$table->boolean('is_published')->default(false)->index()->comment('для публикации');
			$table->boolean('is_active')->default(true)->index()->comment('признак активности');
			$table->timestamp('active_from_at')->nullable()->comment('дата начала активности');
			$table->timestamp('active_to_at')->nullable()->comment('дата окончания активности');
			$table->text('meta_title')->nullable()->comment('meta title');
			$table->text('meta_description')->nullable()->comment('meta description');
			$table->text('data_json')->nullable()->comment('дополнительная информация');
            $table->timestamps();
			$table->softDeletes();
        });
		
		$items = [];

		$discount = HelpFunctions::getEntityByAlias(Discount::class, Discount::DISCOUNT_5_ALIAS);
		$items[] = [
			'name' => 'Birthday',
			'discount_id' => $discount ? $discount->id : 0,
		];

		foreach ($items as $item) {
			$promo = new Promo();
			$promo->name = $item['name'];
			$promo->discount_id = $item['discount_id'];
			$promo->save();
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
	{
        Schema::drop('promos');
    }
}
