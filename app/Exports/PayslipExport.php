<?php

namespace App\Exports;

use http\Env\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Payslip;

class PayslipExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    protected $request;

    public function __construct($request)
    {

        $this->request = $request;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2    => ['font' => ['size' => 14]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 50,
            'C' => 50,
            'D' => 50,
        ];
    }

    public function view(): View
    {
        $from_date = $this->request['from_date'];
        $year = isset($from_date) ? date("Y", strtotime($from_date)) : "0000";
        $month = isset($from_date) ? date("m", strtotime($from_date)) : "00";
        $list = Payslip::get($this->request);

        return view("_excel.payslip", ["lists" => $list, "year"=>$year, "month"=>$month]);

    }
}
