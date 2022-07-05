<?php

use App\Models\FlightSimulator;
use App\Models\Location;
use App\Services\HelpFunctions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsFlightSimulatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations_flight_simulators', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('location_id')->nullable(false)->index();
			$table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
			$table->unsignedBigInteger('flight_simulator_id')->nullable(false)->index();
			$table->foreign('flight_simulator_id')->references('id')->on('flight_simulators')->onDelete('cascade');
			$table->text('data_json')->nullable()->comment('дополнительная информация');
			$table->timestamps();
			$table->softDeletes();
        });
	
		$locations = Location::get();
	
		// 737 NG
		$flightSimulatorEntity = HelpFunctions::getEntityByAlias(FlightSimulator::class, FlightSimulator::ALIAS_737);
	
		foreach ($locations as $location) {
			$data = [];
		
			switch ($location->alias) {
				case 'dc':
					$data['events'] = [
						'shift_admin' => '#92E1C1',
						'shift_pilot' => '#92E1C1',
						'deal_paid' => '#92E1C1',
						'deal_notpaid' => '#7986CB',
						'note' => '#F6BF26',
					];
				break;
			}
			
			$location->simulators()->attach($flightSimulatorEntity->id, ['data_json' => json_encode($data, JSON_UNESCAPED_UNICODE)]);
		}
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations_flight_simulators');
    }
}
