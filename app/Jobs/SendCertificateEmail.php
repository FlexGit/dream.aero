<?php

namespace App\Jobs;

use App\Jobs\QueueExtension\ReleaseHelperTrait;
use App\Models\Certificate;
use App\Models\City;
use App\Models\ProductType;
use App\Services\HelpFunctions;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Mail;

class SendCertificateEmail extends Job implements ShouldQueue {
	use InteractsWithQueue, SerializesModels, ReleaseHelperTrait;

	protected $certificate;

	public function __construct(Certificate $certificate) {
		$this->certificate = $certificate;
	}
	
	/**
	 * @return int|void
	 */
	public function handle() {
		$certificateFilePath = isset($this->certificate->data_json['certificate_file_path']) ? $this->certificate->data_json['certificate_file_path'] : '';
		$certificateFileExists = Storage::disk('private')->exists($certificateFilePath);

		// если файла сертификата по какой-то причине не оказалось, генерим его
		if (!$certificateFilePath || !$certificateFileExists) {
			$this->certificate = $this->certificate->generateFile();
			if (!$this->certificate) {
				return null;
			}
		}

		$position = $this->certificate->position;
		if (!$position) return null;
		
		$deal = $position->deal;
		if (!$deal) return null;
		
		$product = $position->product;
		if (!$product) return null;
		
		$productType = $product->productType;
		if (!$productType) return null;
		
		$contractor = $deal->contractor;
		if (!$contractor) return null;
		
		$dealEmail = $deal->email ?? '';
		$dealName = $deal->name ?? '';
		$dealCity = $deal->city;
		$contractorEmail = $contractor->email ?? '';
		$contractorName = $contractor->name ?? '';
		if (!$dealEmail && !$contractorEmail) {
			return null;
		}
		
		$city = $this->certificate->city;
		$certificateRulesFileName = 'RULES_' . mb_strtoupper($city->alias) . '.jpg';

		$messageData = [
			'certificate' => $this->certificate,
			'name' => $dealName ?: $contractorName,
			'city' => $dealCity ?? null,
		];
		
		$recipients = $bcc = [];
		$recipients[] = $dealEmail ?: $contractorEmail;
		if ($dealCity && $dealCity->email) {
			$bcc[] = $dealCity->email;
		}

		$subject = env('APP_NAME') . ': Flight Voucher';
		
		Mail::send(['html' => "admin.emails.send_certificate"], $messageData, function ($message) use ($subject, $recipients, $certificateRulesFileName, $bcc, $certificateFilePath) {
			/** @var \Illuminate\Mail\Message $message */
			$message->subject($subject);
			$message->attach(Storage::disk('private')->path($certificateFilePath));
			$message->attach(Storage::disk('private')->path('rule/' . $certificateRulesFileName));
			$message->attach(Storage::disk('private')->path('rule/RULES_MAIN.jpg'));
			$message->to($recipients);
			$message->bcc($bcc);
		});
		
		$failures = Mail::failures();
		if ($failures) {
			\Log::debug($failures);
			return null;
		}
		
		$this->certificate->sent_at = Carbon::now()->format('Y-m-d H:i:s');
		$this->certificate->save();
	}
}
