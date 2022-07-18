<?php

namespace App\Console\Commands;

use App\Imports\DealImport;
use Illuminate\Console\Command;

class ImportDeal extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'deal:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import deals from old CRM';

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
		(new DealImport)->withOutput($this->output)->import(storage_path('app/public/modx_onlinepay.xlsx'));
		$this->output->success('Import successful');
	}
}
