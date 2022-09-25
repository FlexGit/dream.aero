<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CashFlowReportExport implements WithMultipleSheets
{
	use Exportable;
	
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}
	
	/**
	 * @return array
	 */
	public function array(): array
	{
		return $this->data;
	}
	
	/**
	 * @return array
	 */
	public function sheets(): array
	{
		$sheets = [];

		$sheets[] = new CashFlowBalanceReportExport($this->data);
		foreach($this->data['periods'] as $period) {
			$sheets[] = new CashFlowPeriodReportExport($this->data, $period, $this->data['months']);
		}
		
		return $sheets;
	}
}
