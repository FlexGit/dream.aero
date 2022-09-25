<?php

namespace App\Console\Commands;

use App\Models\Deal;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class SendFlightInvitationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flight_invitation_email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an E-mail to contractor with flight invitation';

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
    	$events = Event::where('event_type', Event::EVENT_TYPE_DEAL)
			->whereNull('flight_invitation_sent_at')
			->where('created_at', '>=', '2022-08-26 00:00:00')
			->latest()
			->limit(100)
			->get();
    	/** @var Event[] $events */
		foreach ($events as $event) {
			if (!$event->uuid) continue;
			
			/** @var Deal $deal */
			$deal = $event->deal;
			if (!$deal) continue;
			if (!$deal->is_certificate_purchase && $deal->certificate) continue;
			
			if ($deal->balance() < 0) continue;
   
			try {
				$job = new \App\Jobs\SendFlightInvitation($event);
				$job->handle();
			} catch (Throwable $e) {
				\Log::debug('500 - ' . $e->getMessage());
			
				return 0;
			}
		}
			
		$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - flight_invitation_email:send - OK');
    	
        return 0;
    }
}
