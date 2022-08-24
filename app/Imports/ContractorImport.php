<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Contractor;
use App\Services\HelpFunctions;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;
use Throwable;

class ContractorImport implements OnEachRow, WithProgressBar
{
	use Importable;

	/**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
		$rowIndex = $row->getIndex();
		$row = $row->toArray();

		if ($rowIndex == 1) return null;

		$contractor = Contractor::where('email', trim($row[3]))->first();
		if ($contractor) {
			\Log::info('Duplicate E-mail: ' . trim($row[3]));
			return null;
		}

		try {
			\DB::beginTransaction();

			$city = HelpFunctions::getEntityByAlias(City::class, trim($row[1]));
			$cityId = $city ? $city->id : 0;
			
			$contractor = new Contractor();
			$contractor->name = trim($row[0]);
			$contractor->email = trim(mb_strtolower($row[3]));
			$contractor->phone = preg_replace('/[^0-9,]/', '', trim($row[4]));
			$contractor->city_id = $cityId;
			$contractor->is_subscribed = !(bool)($row[5] ?? 0);
			$contractor->save();

			\DB::commit();
		} catch (Throwable $e) {
			\DB::rollback();

			\Log::debug('500 - ' . $e->getMessage() . ' - ' . implode(' | ', $row));
		}
    }
}
