<?php

namespace App\Exports;

use App\Models\Payslip;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RecalcOffDayPay implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    protected $request;

    public function __construct($request)
    {

        $this->request = $request;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['size' => 14, 'align' => 'center' ] , 'text' => ['align' => 'center']],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 20,
            'R' => 20,
            'S' => 20,
            'T' => 20,
            'U' => 20,
        ];
    }

    public function view(): View
    {
        $data = $this->request->input("data");
        $lists = json_decode($data);

        return view("_excel.recalcOffPay", [
            "lists" => $lists
        ]);

    }
}
