<?php

namespace App\Console\Commands\Roistat;

use App\Services\RoistatService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RoistatAddDeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roistat:add_deals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Roistat: Add Deals';

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
		$roistat = new RoistatService();
		$roistat->addDeals();
    
		$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - roistat:add_deals - OK');
    	
        return 0;
    }
}
