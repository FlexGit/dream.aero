<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TipsReportExport implements FromView, ShouldAutoSize
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function view(): View
	{
		return view('admin.report.tips.export', $this->data);
	}
	
	public function array(): array
	{
		return $this->data;
	}
}
