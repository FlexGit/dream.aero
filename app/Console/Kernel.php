<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Commands\SendCertificateEmail::class,
		Commands\SendFlightInvitationEmail::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		// запуск демона для обработки задач из очереди
		$filePath = storage_path('logs/queue_worker.log');
		$schedule->command('queue:work --daemon')
			->everyMinute()
			/*->withoutOverlapping()*/
			->runInBackground()
			->appendOutputTo($filePath)
			->emailOutputOnFailure(env('DEV_EMAIL'));
		
		// перезапуск демона очереди (чтобы изменения в коде были применены)
		$schedule->command('queue:restart')
			->everyFiveMinutes()
			->runInBackground()
			->appendOutputTo($filePath)
			->emailOutputOnFailure(env('DEV_EMAIL'));
		
		// отправка контрагенту сертификата на полет
		$filePath = storage_path('logs/commands/certificate_email.log');
		$schedule->command('certificate_email:send')
			->everyFiveMinutes()
			->runInBackground()
			->appendOutputTo($filePath)
			->emailOutputOnFailure(env('DEV_EMAIL'));
		
		// отправка контрагенту приглашения на полет
		$filePath = storage_path('logs/commands/flight_invitation_email.log');
		$schedule->command('flight_invitation_email:send')
			->everyFiveMinutes()
			->runInBackground()
			->appendOutputTo($filePath)
			->emailOutputOnFailure(env('DEV_EMAIL'));
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__.'/Commands');

		require base_path('routes/console.php');
	}
}
