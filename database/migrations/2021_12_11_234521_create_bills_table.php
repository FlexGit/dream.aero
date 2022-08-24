<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
			$table->string('number')->nullable()->comment('номер счета');
			$table->integer('contractor_id')->default(0)->index()->comment('контрагент');
			$table->integer('deal_id')->default(0)->index()->comment('сделка');
			$table->integer('payment_method_id')->default(0)->index()->comment('способ оплаты');
			$table->integer('status_id')->default(0)->index()->comment('статус');
			$table->float('amount')->default(0)->comment('сумма счета');
			$table->float('tax')->default(0)->comment('ставка налога');
			$table->float('total_amount')->default(0)->comment('итоговая стоимость');
			$table->integer('currency_id')->default(0)->index()->comment('валюта');
			$table->integer('city_id')->default(0)->index()->comment('город');
			$table->integer('location_id')->default(0)->index()->comment('локация, по которой выставлен счет');
			$table->string('uuid')->nullable()->comment('uuid');
			$table->timestamp('payed_at')->nullable()->comment('дата проведения платежа');
			$table->timestamp('link_sent_at')->nullable()->comment('дата отправки ссылки на оплату');
			$table->timestamp('receipt_sent_at')->nullable()->comment('дата отправки чека');
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
        Schema::dropIfExists('bills');
    }
}
