<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Status;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
			$table->string('name')->comment('наименование');
			$table->string('alias')->comment('алиас');
			$table->string('type', 50)->default('')->index()->comment('тип сущности: контрагент, заказ, сделка, счет, платеж, сертификат');
			$table->integer('flight_time')->default(0)->comment('время налета');
			$table->integer('discount_id')->default(0)->index()->comment('скидка');
			$table->integer('sort')->default(0)->comment('сортировка');
			$table->boolean('is_active')->default(true)->index()->comment('признак активности');
			$table->text('data_json')->nullable()->comment('дополнительная информация');
            $table->timestamps();
			$table->softDeletes();
        });
        
        $statuses = [
			'deal' => [
				'deal_created' => [
					'name' => 'Created',
					'sort' => 10,
					'data' => [
						'color' => '#f0eed8',
					],
				],
				'deal_confirmed' => [
					'name' => 'Confirmed',
					'sort' => 20,
					'data' => [
						'color' => '#e9ffc9',
					],
				],
				'deal_pauseed' => [
					'name' => 'Paused',
					'sort' => 30,
					'data' => [
						'color' => '#d7baff',
					],
				],
				'deal_returned' => [
					'name' => 'Returned',
					'sort' => 40,
					'data' => [
						'color' => '#edc9ff',
					],
				],
				'deal_canceled' => [
					'name' => 'Canceled',
					'sort' => 50,
					'data' => [
						'color' => '#ffbdba',
					],
				],
			],
			'certificate' => [
				'certificate_created' => [
					'name' => 'Created',
					'sort' => 10,
					'data' => [
						'color' => '#f0eed8',
					],
				],
				'certificate_registered' => [
					'name' => 'Registered',
					'sort' => 20,
					'data' => [
						'color' => '#e9ffc9',
					],
				],
				'certificate_canceled' => [
					'name' => 'Annulated',
					'sort' => 30,
					'data' => [
						'color' => '#ffbdba',
					],
				],
				'certificate_returned' => [
					'name' => 'Returned',
					'sort' => 40,
					'data' => [
						'color' => '#edc9ff',
					],
				],
			],
			'bill' => [
				'bill_not_payed' => [
					'name' => 'Not paid',
					'sort' => 10,
					'data' => [
						'color' => '#f0eed8',
					],
				],
				'bill_payed' => [
					'name' => 'Paid',
					'sort' => 20,
					'data' => [
						'color' => '#e9ffc9',
					],
				],
			],
		];
        
        foreach ($statuses as $type => $statusItem) {
        	foreach ($statusItem as $alias => $item) {
				$status = new Status();
				$status->type = $type;
				$status->alias = $alias;
				$status->name = $item['name'];
				$status->sort = $item['sort'];
				$status->flight_time = $item['flight_time'] ?? 0;
				$status->discount_id = $item['discount_id'] ?? 0;
				$status->data_json = $item['data'] ?? null;
				$status->save();
			}
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
