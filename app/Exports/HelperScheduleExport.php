<?php

namespace App\Exports;

use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HelperScheduleExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function styles(Worksheet $sheet)
    {
//        return [
//            2    => ['font' => ['size' => 14]],
//        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 22,
            'D' => 22,
            'E' => 5,
            'F' => 5,
            'G' => 5,
            'H' => 5,
            'I' => 5,
            'J' => 5,
            'K' => 5,
            'L' => 5,
            'M' => 5,
            'N' => 5,
            'O' => 5,
            'P' => 5,
            'Q' => 5,
            'R' => 5,
            'S' => 5,
            'T' => 5,
            'U' => 5,
            'V' => 5,
            'W' => 5,
            'X' => 5,
            'Y' => 5,
            'Z' => 5,
            'AA' => 5,
            'AB' => 5,
            'AC' => 5,
            'AD' => 5,
            'AE' => 5,
            'AF' => 5,
            'AG' => 5,
            'AH' => 5,
            'AI' => 5,
            'AJ' => 5,
            'AK' => 15,
        ];
    }

    public function view(): View
    {

        $from_date = $this->request->input("from_date");
        $to_date = $this->request->input("to_date");

        $lists = DB::table("helpers")
            ->where("user_id", "=", User::get_user_id())
            ->when($from_date, function ($query, $from_date) {
                return $query->where("contract_start_date", ">=", $from_date);
            })
            ->when($to_date, function ($query, $to_date) {
                return $query->where("contract_end_date", "<=", $to_date);
            })
            ->get();

        return view("_excel.schedule", [ "lists" => $lists ]);
    }

}
