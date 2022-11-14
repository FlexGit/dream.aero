<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\Certificate;
use App\Models\Deal;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Throwable;

class ConvertTimezone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timezone:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Timezone';

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
		try {
			DB::beginTransaction();
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - START');
			
			DB::table('bills')
				->whereNotNull('payed_at')
				->update(['payed_at' => DB::RAW("convert_tz(payed_at, '+03:00', '-05:00')")]);
			
			DB::table('bills')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('bills')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('bills')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - bills');

			DB::table('certificates')
				->whereNotNull('expire_at')
				->update(['expire_at' => DB::RAW("convert_tz(expire_at, '+03:00', '-05:00')")]);
			
			DB::table('certificates')
				->whereNotNull('sent_at')
				->update(['sent_at' => DB::RAW("convert_tz(sent_at, '+03:00', '-05:00')")]);
			
			DB::table('certificates')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('certificates')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('certificates')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - certificates');
			
			DB::table('contents')
				->whereNotNull('published_at')
				->update(['published_at' => DB::RAW("convert_tz(published_at, '+03:00', '-05:00')")]);
			
			DB::table('contents')
				->whereNotNull('published_end_at')
				->update(['published_end_at' => DB::RAW("convert_tz(published_end_at, '+03:00', '-05:00')")]);
			
			DB::table('contents')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('contents')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('contents')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - contents');
			
			DB::table('deals')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('deals')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('deals')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - deals');
			
			DB::table('events')
				->whereNotNull('flight_invitation_sent_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);

			DB::table('events')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('events')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('events')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - events');
			
			DB::table('event_comments')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('event_comments')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('event_comments')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - event_comments');
			
			DB::table('operations')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('operations')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('operations')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - operations');
			
			DB::table('promocodes')
				->whereNotNull('active_from_at')
				->update(['active_from_at' => DB::RAW("convert_tz(active_from_at, '+03:00', '-05:00')")]);

			DB::table('promocodes')
				->whereNotNull('active_to_at')
				->update(['active_to_at' => DB::RAW("convert_tz(active_to_at, '+03:00', '-05:00')")]);

			DB::table('promocodes')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('promocodes')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('promocodes')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - promocodes');
			
			DB::table('promos')
				->whereNotNull('active_from_at')
				->update(['active_from_at' => DB::RAW("convert_tz(active_from_at, '+03:00', '-05:00')")]);
			
			DB::table('promos')
				->whereNotNull('active_to_at')
				->update(['active_to_at' => DB::RAW("convert_tz(active_to_at, '+03:00', '-05:00')")]);
			
			DB::table('promos')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('promos')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('promos')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - promo');
			
			DB::table('tips')
				->whereNotNull('created_at')
				->update(['created_at' => DB::RAW("convert_tz(created_at, '+03:00', '-05:00')")]);
			
			DB::table('tips')
				->whereNotNull('updated_at')
				->update(['updated_at' => DB::RAW("convert_tz(updated_at, '+03:00', '-05:00')")]);
			
			DB::table('tips')
				->whereNotNull('deleted_at')
				->update(['deleted_at' => DB::RAW("convert_tz(deleted_at, '+03:00', '-05:00')")]);
			
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - tips');
			
			DB::commit();
		} catch (Throwable $e) {
			$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - ' . $e->getMessage());
			
			\DB::rollback();
			
			return 0;
		}
		
		$this->info(Carbon::now()->format('Y-m-d H:i:s') . ' - timezone:convert - OK');
    	
        return 0;
    }
}
