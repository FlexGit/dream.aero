<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class CashFlowBalanceReportExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle
{
	use Exportable;
	
	private $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	/**
	 * @return View
	 */
	public function view(): View
	{
		return view('admin.report.cash-flow.balance-export', $this->data);
	}
	
	/**
	 * @return array
	 */
	public function array(): array
	{
		return $this->data;
	}
	
	/**
	 * @return string
	 */
	public function title(): string
	{
		return 'Balance';
	}
	
	/**
	 * @param Cell $cell
	 * @param mixed $value
	 * @return bool
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function bindValue(Cell $cell, $value)
	{
		if (is_numeric($value)) {
			$cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
			
			return true;
		}
		
		// else return default behavior
		return parent::bindValue($cell, $value);
	}
}
