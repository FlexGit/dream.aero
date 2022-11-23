<?php

namespace App\Imports;

use App\Models\Certificate;
use App\Models\Status;
use App\Services\HelpFunctions;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;

class CertificateStatusImport implements OnEachRow, WithProgressBar
{
	use Importable;

	/**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
		$row = $row->toArray();
		
		if (!trim($row[0])) {
			return null;
		}

		$certificateStatusCreated = HelpFunctions::getEntityByAlias(Status::class, Certificate::CREATED_STATUS);
		$certificateStatusRegistered = HelpFunctions::getEntityByAlias(Status::class, Certificate::REGISTERED_STATUS);

		$certificate = Certificate::where('number', trim($row[0]))
			->first();
		if (!$certificate) {
			\Log::debug(trim($row[0]));
			return null;
		}
		$certificate->status_id = (trim($row[0]) == Certificate::REGISTERED_STATUS) ? $certificateStatusRegistered->id : $certificateStatusCreated->id;
		$certificate->save();
    }
}
