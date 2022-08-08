<?php

namespace App\Jobs;

use App\Jobs\QueueExtension\ReleaseHelperTrait;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendCallbackEmail extends Job implements ShouldQueue {
	use InteractsWithQueue, SerializesModels, ReleaseHelperTrait;

	protected $name;
	protected $phone;
	protected $city;
	protected $comment;

	public function __construct($name, $phone, $city, $comment) {
		$this->name = $name;
		$this->phone = $phone;
		$this->city = $city;
		$this->comment = $comment;
	}
	
	/**
	 * @return int|void
	 */
	public function handle() {
		$recipients = /*$bcc = */[];
		$recipients[] = $this->city->email ?: env('ADMIN_EMAIL');
		//$bcc[] = env('DEV_EMAIL');

		$messageData = [
			'name' => $this->name,
			'phone' => $this->phone,
			'comment' => $this->comment,
		];

		$subject = env('APP_NAME') . ': callback request';

		Mail::send(['html' => "admin.emails.send_callback"], $messageData, function ($message) use ($subject, $recipients/*, $bcc*/) {
			/** @var \Illuminate\Mail\Message $message */
			$message->subject($subject);
			$message->to($recipients);
			/*$message->bcc($bcc);*/
		});
	}
}
