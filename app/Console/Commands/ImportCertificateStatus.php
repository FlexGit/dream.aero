<?php

namespace App\Console\Commands;

use App\Imports\CertificateStatusImport;
use Illuminate\Console\Command;

class ImportCertificateStatus extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'certificate_status:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import certificate statuses from old CRM';

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
		$this->output->title('Starting import');
		(new CertificateStatusImport)->withOutput($this->output)->import(storage_path('app/public/certificate_status.xlsx'));
		$this->output->success('Import successful');
	}
}
