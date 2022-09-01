<?php

namespace App\Imports;

use App\Models\Bill;
use App\Models\Certificate;
use App\Models\Contractor;
use App\Models\Deal;
use App\Models\Product;
use App\Models\Promocode;
use App\Models\Status;
use App\Services\HelpFunctions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;

class DealImport implements OnEachRow, WithProgressBar
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

		$cityId = (trim($row[3]) == 'Dubai') ? 2 : 1;
		$locationId = (trim($row[3]) == 'Dubai') ? 1 : 2;
		$currencyId = (trim($row[3]) == 'Dubai') ? 2 : 1;
		$amount = (trim($row[8]) && trim($row[8]) != 'NULL') ? trim($row[8]) : 0;
		$isPaid = trim($row[10]);
		$isCertificatePurchase = (!trim($row[7]) || trim($row[7]) == 'sert') ? 1 : 0;
		$createAt = trim($row[12]);
		if ($createAt) {
			$createAt = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($createAt))->format('Y-m-d H:i:s');
		}
		
		$promocodeNumber = trim($row[14]);
		$promocode = null;
		if ($promocodeNumber && $promocodeNumber != 'NULL') {
			$promocode =  Promocode::where('number', $promocodeNumber)->first();
		}
		
		$contractor = Contractor::where('email', trim($row[1]))->first();
		if (!$contractor) {
			$contractor = new Contractor();
			$contractor->name = (trim($row[0]) != 'NULL') ? trim($row[0]) : null;
			$contractor->email = (trim($row[1]) != 'NULL') ? trim($row[1]) : null;
			$contractor->phone = (trim($row[2]) != 'NULL') ? trim($row[2]) : null;
			$contractor->city_id = $cityId;
			$contractor->created_at = $createAt;
			$contractor->updated_at = $createAt;
			$contractor->save();
		}

		$productAlias = str_replace(' ', '_', mb_strtolower(trim(str_replace('min', '', $row[6]))));
		switch ($productAlias) {
			case 30:
				$productAlias = 'regular_30';
			break;
			case 60:
				$productAlias = 'regular_60';
			break;
			case 90:
				$productAlias = 'regular_90';
			break;
			case 120:
				$productAlias = 'regular_120';
			break;
		}
		
		$validity = ($productAlias == 'basic') ? 12 : 6;
		$product = HelpFunctions::getEntityByAlias(Product::class, $productAlias);
		if (!$product) return null;
		
		$certificateCreatedStatus = HelpFunctions::getEntityByAlias(Status::class, 'certificate_created');
		
		if ($isCertificatePurchase) {
			$certificate = new Certificate();
			$certificate->status_id = $certificateCreatedStatus ? $certificateCreatedStatus->id : 0;
			$certificate->city_id = $cityId;
			$certificate->product_id = $product ? $product->id : 0;
			$certificate->expire_at = Carbon::parse($createAt)->addMonths($validity)->format('Y-m-d H:i:s');
			$certificate->created_at = $createAt;
			$certificate->updated_at = $createAt;
			$certificate->save();
			
			$certificate->number = (trim($row[5]) != 'NULL') ? trim($row[5]) : null;
			$certificate->updated_at = $createAt;
			$certificate->save();
		} else {
			$certificate = null;
		}
		
		$dealCreatedStatus = HelpFunctions::getEntityByAlias(Status::class, 'deal_created');
		$dealConfirmedStatus = HelpFunctions::getEntityByAlias(Status::class, 'deal_confirmed');
		
		$deal = new Deal();
		$deal->status_id = $isPaid ? ($dealConfirmedStatus ? $dealConfirmedStatus->id : 0) : ($dealCreatedStatus ? $dealCreatedStatus->id : 0);
		$deal->contractor_id = $contractor ? $contractor->id : 0;
		$deal->city_id = $cityId;
		$deal->location_id = $locationId;
		$deal->flight_simulator_id = 1;
		$deal->name = trim($row[0]);
		$deal->email = trim($row[1]);
		$deal->phone = trim($row[2]);
		$deal->product_id = $product ? $product->id : 0;
		$deal->certificate_id = ($isCertificatePurchase && $certificate) ? $certificate->id : 0;
		$deal->total_amount = $amount;
		$deal->currency_id = $currencyId;
		$deal->is_certificate_purchase = $isCertificatePurchase;
		$deal->promocode_id = $promocode ? $promocode->id : 0;
		$deal->data_json = [
			'discount' => (trim($row[9]) && trim($row[9]) != 'NULL') ? trim($row[9]) : null,
		];
		$deal->created_at = $createAt;
		$deal->updated_at = $createAt;
		$deal->save();
		
		$bill = new Bill();
		$bill->contractor_id = $contractor ? $contractor->id : 0;
		$bill->deal_id = $deal ? $deal->id : 0;
		$bill->payment_method_id = 4;
		$bill->status_id = $isPaid ? 11 : 10;
		$bill->total_amount = $amount;
		$bill->currency_id = $currencyId;
		$bill->city_id = $cityId;
		$bill->location_id = $locationId;
		$bill->user_id = (trim($row[13]) == '1') ? 1 : 0;
		$bill->payed_at = $isPaid ? $createAt : null;
		$bill->created_at = $createAt;
		$bill->updated_at = $createAt;
		$bill->data_json = [
			'pay_id' => trim($row[4]) ?? null,
			'onlinepay' => (trim($row[13]) != '1') ? trim($row[13]) : null,
			'payment_response' => trim($row[11]) ?? null,
		];
		$bill->save();
    }
}
