<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('наименование способа оплаты');
			$table->string('alias')->comment('алиас');
			$table->boolean('is_active')->default(true)->index()->comment('признак активности');
            $table->timestamps();
			$table->softDeletes();
        });
	
		$items = [
			'0' => [
				'name' => 'Cash',
				'alias' => 'cash',
			],
			'1' => [
				'name' => 'Credit card',
				'alias' => 'card',
			],
			'2' => [
				'name' => 'Bank',
				'alias' => 'bank',
			],
			'3' => [
				'name' => 'Online',
				'alias' => 'online',
			],
		];
	
		foreach ($items as $item) {
			$paymentMethod = new PaymentMethod();
			$paymentMethod->name = $item['name'];
			$paymentMethod->alias = $item['alias'];
			$paymentMethod->save();
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
