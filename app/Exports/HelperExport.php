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

class HelperExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
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
            'A' => 30,
            'B' => 30,
            'C' => 20,
            'D' => 30,
            'E' => 30,
            'F' => 50,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
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

        return view("_excel.helpers", [ "lists" => $lists ]);
    }
}
