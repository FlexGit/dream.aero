<?php

namespace App\Jobs;

use App\Jobs\QueueExtension\ReleaseHelperTrait;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendFlightInvitationEmail extends Job implements ShouldQueue {
	use InteractsWithQueue, SerializesModels, ReleaseHelperTrait;

	protected $event;

	public function __construct(Event $event) {
		$this->event = $event;
	}
	
	/**
	 * @return int|void
	 */
	public function handle() {
		$city = $this->event->city;
		if (!$city) return null;

		$contractor = $this->event->contractor;
		if (!$contractor) return null;
		
		$deal = $this->event->deal;
		if (!$deal) return null;
		
		$location = $this->event->location;
		if (!$location) return null;
		
		$simulator = $this->event->simulator;
		if (!$simulator) return null;
		
		$dealEmail = $deal->email ?? '';
		$dealName = $deal->name ?? '';
		$contractorEmail = $contractor->email ?? '';
		$contractorName = $contractor->name ?? '';
		if (!$dealEmail && !$contractorEmail) {
			return null;
		}
		
		$simulatorAlias = $simulator->alias ?? '';
		if (!$simulatorAlias) return null;
		
		$messageData = [
			'name' => $dealName ?: $contractorName,
			'flightDate' => $this->event->start_at ?? '',
			'location' => $location,
			'simulator' => $simulator,
			'city' => $city,
		];
		
		$recipients = /*$bcc = */[];
		$recipients[] = $dealEmail ?: $contractorEmail;
		/*if ($city->email) {
			$bcc[] = $city->email;
		}*/
		
		$subject = env('APP_NAME') . ': Flight Invitation';

		Mail::send(['html' => "admin.emails.send_flight_invitation"], $messageData, function ($message) use ($subject, $recipients/*, $bcc*/) {
			/** @var \Illuminate\Mail\Message $message */
			$message->subject($subject);
			$message->to($recipients);
			/*$message->bcc($bcc);*/
		});
		
		$failures = Mail::failures();
		if ($failures) {
			\Log::debug($failures);
			return null;
		}
		
		$this->event->flight_invitation_sent_at = Carbon::now()->format('Y-m-d H:i:s');
		$this->event->save();
	}
}
