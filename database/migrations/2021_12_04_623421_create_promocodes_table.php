<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('number')->comment('промокод');
			$table->string('type')->comment('тип промокода	');
			$table->integer('contractor_id')->default(0)->index()->comment('контрагент, который может применить промокод');
			$table->integer('location_id')->default(0)->index()->comment('локация, на которой может быть применен промокод');
			$table->integer('flight_simulator_id')->default(0)->index()->comment('тип авиатренажера, на котором может быть применен промокод');
			$table->integer('discount_id')->default(0)->index()->comment('скидка');
			$table->boolean('is_active')->default(true)->index()->comment('признак активности');
			$table->timestamp('active_from_at')->nullable()->comment('дата начала активности');
			$table->timestamp('active_to_at')->nullable()->comment('дата окончания активности');
			$table->timestamp('sent_at')->nullable()->comment('дата и время отправки промокода контрагенту');
			$table->string('uuid')->nullable()->comment('uuid');
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
        Schema::dropIfExists('promocodes');
    }
}
