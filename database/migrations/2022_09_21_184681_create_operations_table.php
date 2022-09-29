<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
			$table->integer('operation_type_id')->->default(0)->comment('тип операции');
			$table->integer('payment_method_id')->default(0)->comment('способ оплаты');
			$table->float('amount')->default(0)->comment('сумма');
			$table->integer('currency_id')->default(0)->index()->comment('валюта');
			$table->integer('city_id')->default(0)->index()->comment('город');
			$table->integer('location_id')->default(0)->index()->comment('локация');
			$table->date('operated_at')->nullable()->comment('дата операции');
			$table->integer('user_id')->default(0)->index()->comment('пользователь');
			$table->text('data_json')->nullable()->comment('дополнительная информация');
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
        Schema::dropIfExists('operations');
    }
}
