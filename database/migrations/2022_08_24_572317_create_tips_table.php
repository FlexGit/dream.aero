<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
			$table->integer('deal_id')->default(0)->index()->comment('сделка');
			$table->float('amount')->default(0)->comment('сумма');
			$table->integer('currency_id')->default(0)->index()->comment('валюта');
			$table->integer('city_id')->default(0)->index()->comment('город');
			$table->date('received_at')->nullable()->comment('дата получения');
			$table->string('source', 25)->nullable()->comment('источник');
			$table->integer('user_id')->default(0)->index()->comment('пользователь');
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tips');
    }
}
