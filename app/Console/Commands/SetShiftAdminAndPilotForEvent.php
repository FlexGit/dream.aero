<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetShiftAdminAndPilotForEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin_pilot_event:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set admin and pilot for event';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		//\DB::connection()->enableQueryLog();
    	// проверяем все полеты за последний час без пилота
    	$events = Event::where('event_type', Event::EVENT_TYPE_DEAL)
			->where('stop_at', '>=', Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'))
			->where('stop_at', '<=', Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'))
			->get();
    	/** @var Event[] $events */
		foreach ($events as $event) {
			$city = $event->city;
			if (!$city) continue;

    		$location = $event->location;
    		if (!$location) continue;
		
			$simulator = $event->simulator;
			if (!$simulator) continue;
		
			// находим админа, который был на смене во время полета
			$shiftAdminEvent = Event::where('event_type', Event::EVENT_TYPE_SHIFT_ADMIN)
				->where('user_id', '!=', 0)
				->where('city_id', $city->id)
				->where('location_id', $location->id)
				->where('flight_simulator_id', $simulator->id)
				->where('start_at', '<=', Carbon::parse($event->start_at)->format('Y-m-d H:i:s'))
				->where('stop_at', '>=', Carbon::parse($event->stop_at)->format('Y-m-d H:i:s'))
				->first();
			
			// находим пилота, который был на смене во время полета
			$shiftPilotEvent = Event::where('event_type', Event::EVENT_TYPE_SHIFT_PILOT)
				->where('user_id', '!=', 0)
				->where('city_id', $city->id)
				->where('location_id', $location->id)
				->where('flight_simulator_id', $simulator->id)
				->where('start_at', '<=', Carbon::parse($event->start_at)->format('Y-m-d H:i:s'))
				->where('stop_at', '>=', Carbon::parse($event->stop_at)->format('Y-m-d H:i:s'))
				->first();
			
			$event->shift_admin_id = $shiftAdminEvent ? $shiftAdminEvent->user_id : 0;
			if (!$event->pilot_id) {
				$event->pilot_id = $shiftPilotEvent ? $shiftPilotEvent->user_id : 0;
			}
			$event->save();
		}
		//\Log::debug(\DB::getQueryLog());
		
		$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - admin_pilot_event:set - OK');
    	
        return 0;
    }
}
