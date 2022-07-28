<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
			$table->string('number')->nullable()->comment('номер');
			$table->integer('status_id')->default(0)->index()->comment('статус');
			$table->integer('city_id')->default(0)->index()->comment('город');
			$table->integer('product_id')->default(0)->index()->comment('продукт');
			$table->string('uuid')->nullable()->comment('uuid');
			$table->timestamp('expire_at')->nullable()->comment('срок окончания действия сертификата');
			$table->timestamp('sent_at')->nullable()->comment('время последней отправки Сертификата контрагенту');
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
        Schema::dropIfExists('certificates');
    }
}
