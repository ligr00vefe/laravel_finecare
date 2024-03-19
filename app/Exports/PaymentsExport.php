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

class PaymentsExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
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

    public function view(): View
    {
        $_lists = json_decode($this->request->input("lists"));
        $etc_charge_time = explode("|", $this->request->input("etc_charge_time"));
        $etc_charge_pay = explode("|", $this->request->input("etc_charge_pay"));
        $except_charge_time = explode("|", $this->request->input("except_charge_time"));
        $except_charge_pay = explode("|", $this->request->input("except_charge_pay"));
        $bojeon = explode("|", $this->request->input("bojeon"));
        $jaboodam = explode("|", $this->request->input("jaboodam"));
        $jaesoodang = explode("|", $this->request->input("jaesoodang"));
        $bannap = explode("|", $this->request->input("bannap"));
        $gunbo_tax = explode("|", $this->request->input("gunbo_tax"));
        $year_total_tax = explode("|", $this->request->input("year_total_tax"));
        $bad_income_get = explode("|", $this->request->input("bad_income_get"));
        $etc_tax_1 = explode("|", $this->request->input("etc_tax_1"));
        $etc_tax_2 = explode("|", $this->request->input("etc_tax_2"));

        $lists = [];
        $base = [
            "Voucher" => [
                "COUNTRY" => [
                    "DAY" => []
                ],
                "CITY" => [
                    "DAY" => [],
                ],
            ],
            "Standard" => [
                "BASIC" => [],
                "HOLIDAY" => [],
            ],
            "Return" => [
                "COUNTRY" => [
                    "DAYS" => []
                ],
                "CITY" => [
                    "DAYS" => []
                ]
            ],
            "User" => [],
            "Tax" => [],

            "etc_charge_time" => 0,
            "etc_charge_pay" => 0,
            "except_charge_time" => 0,
            "except_charge_pay" => 0,
            "bojeon" => 0,
            "jaboodam" => 0,
            "jaesoodang" => 0,
            "bannap" => 0,
            "gunbo_tax" => 0,
            "year_total_tax" => 0,
            "bad_income_get" => 0,
            "etc_tax_1" => 0,
            "etc_tax_2" => 0,
            "tax_total" => 0,
        ];

        $i = 1;


        foreach ($_lists as $key => $val)
        {
            $lists[$key] = $base;

            $lists[$key]['Voucher']['COUNTRY']['DAY'] = (array) $val->Voucher->COUNTRY->DAY;
            $lists[$key]['Voucher']['COUNTRY']['TIME_TOTAL'] = $val->Voucher->COUNTRY->TIME_TOTAL;
            $lists[$key]['Voucher']['COUNTRY']['TIME_NORMAL'] = $val->Voucher->COUNTRY->TIME_NORMAL;
            $lists[$key]['Voucher']['COUNTRY']['TIME_EXTRA'] = $val->Voucher->COUNTRY->TIME_EXTRA;
            $lists[$key]['Voucher']['COUNTRY']['TIME_HOLIDAY'] = $val->Voucher->COUNTRY->TIME_HOLIDAY;
            $lists[$key]['Voucher']['COUNTRY']['TIME_NIGHT'] = $val->Voucher->COUNTRY->TIME_NIGHT;
            $lists[$key]['Voucher']['COUNTRY']['PAYMENT_TOTAL'] = $val->Voucher->COUNTRY->PAYMENT_TOTAL;
            $lists[$key]['Voucher']['COUNTRY']['PAYMENT_NORMAL'] = $val->Voucher->COUNTRY->PAYMENT_NORMAL;
            $lists[$key]['Voucher']['COUNTRY']['PAYMENT_EXTRA'] = $val->Voucher->COUNTRY->PAYMENT_EXTRA;
            $lists[$key]['Voucher']['COUNTRY']['PAYMENT_NIGHT'] = $val->Voucher->COUNTRY->PAYMENT_NIGHT;
            $lists[$key]['Voucher']['COUNTRY']['PAYMENT_HOLIDAY'] = $val->Voucher->COUNTRY->PAYMENT_HOLIDAY;

            $lists[$key]['Voucher']['CITY']['DAY'] = (array) $val->Voucher->CITY->DAY;
            $lists[$key]['Voucher']['CITY']['TIME_TOTAL'] = $val->Voucher->COUNTRY->TIME_TOTAL;
            $lists[$key]['Voucher']['CITY']['TIME_NORMAL'] = $val->Voucher->COUNTRY->TIME_NORMAL;
            $lists[$key]['Voucher']['CITY']['TIME_EXTRA'] = $val->Voucher->COUNTRY->TIME_EXTRA;
            $lists[$key]['Voucher']['CITY']['TIME_HOLIDAY'] = $val->Voucher->COUNTRY->TIME_HOLIDAY;
            $lists[$key]['Voucher']['CITY']['TIME_NIGHT'] = $val->Voucher->COUNTRY->TIME_NIGHT;
            $lists[$key]['Voucher']['CITY']['PAYMENT_TOTAL'] = $val->Voucher->COUNTRY->PAYMENT_TOTAL;
            $lists[$key]['Voucher']['CITY']['PAYMENT_NORMAL'] = $val->Voucher->COUNTRY->PAYMENT_NORMAL;
            $lists[$key]['Voucher']['CITY']['PAYMENT_EXTRA'] = $val->Voucher->COUNTRY->PAYMENT_EXTRA;
            $lists[$key]['Voucher']['CITY']['PAYMENT_NIGHT'] = $val->Voucher->COUNTRY->PAYMENT_NIGHT;
            $lists[$key]['Voucher']['CITY']['PAYMENT_HOLIDAY'] = $val->Voucher->COUNTRY->PAYMENT_HOLIDAY;

            $lists[$key]['Standard']['BASIC'] = (array) $val->Standard->BASIC;
            $lists[$key]['Standard']['HOLIDAY'] = (array) $val->Standard->HOLIDAY;
            $lists[$key]['Standard']['PAY_BASIC'] = $val->Standard->PAY_BASIC;
            $lists[$key]['Standard']['TIME_BASIC'] = $val->Standard->TIME_BASIC;
            $lists[$key]['Standard']['PAY_OVERTIME'] = $val->Standard->PAY_OVERTIME;
            $lists[$key]['Standard']['TIME_OVERTIME'] = $val->Standard->TIME_OVERTIME;
            $lists[$key]['Standard']['PAY_HOLIDAY'] = $val->Standard->PAY_HOLIDAY;
            $lists[$key]['Standard']['TIME_HOLIDAY'] = $val->Standard->TIME_HOLIDAY;
            $lists[$key]['Standard']['TIME_HOLIDAY_OVERTIME'] = $val->Standard->TIME_HOLIDAY_OVERTIME;
            $lists[$key]['Standard']['PAY_HOLIDAY_OVERTIME'] = $val->Standard->PAY_HOLIDAY_OVERTIME;
            $lists[$key]['Standard']['PAY_NIGHT'] = $val->Standard->PAY_NIGHT;
            $lists[$key]['Standard']['TIME_NIGHT'] = $val->Standard->TIME_NIGHT;
            $lists[$key]['Standard']['ALLOWANCE_WEEK_PAY'] = $val->Standard->ALLOWANCE_WEEK_PAY;
            $lists[$key]['Standard']['ALLOWANCE_YEAR_PAY'] = $val->Standard->ALLOWANCE_YEAR_PAY;
            $lists[$key]['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR'] = $val->Standard->ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR;
            $lists[$key]['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME'] = $val->Standard->ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME;
            $lists[$key]['Standard']['PUBLIC_HOLIDAY_PAY'] = $val->Standard->PUBLIC_HOLIDAY_PAY;
            $lists[$key]['Standard']['PUBLIC_HOLIDAY_TIME'] = $val->Standard->PUBLIC_HOLIDAY_TIME;
            $lists[$key]['Standard']['WORKERS_DAY_PAY'] = $val->Standard->WORKERS_DAY_PAY;
            $lists[$key]['Standard']['WORKERS_DAY_TIME'] = $val->Standard->WORKERS_DAY_TIME;
            $lists[$key]['Standard']['PAY_TOTAL'] = $val->Standard->PAY_TOTAL;
            $lists[$key]['Standard']['TIME_TOTAL'] = $val->Standard->TIME_TOTAL;
            $lists[$key]['Standard']['TIME_EXTRA'] = $val->Standard->TIME_EXTRA;
            $lists[$key]['Standard']['PAY_EXTRA'] = $val->Standard->PAY_EXTRA;
            $lists[$key]['Standard']['TIME_NORMAL'] = $val->Standard->TIME_NORMAL;
            $lists[$key]['Standard']['PAY_NORMAL'] = $val->Standard->PAY_NORMAL;
            $lists[$key]['Standard']['ALLOWANCE_WEEK_TIME'] = $val->Standard->ALLOWANCE_WEEK_TIME;
            $lists[$key]['Standard']['ALLOWANCE_YEAR_TIME'] = $val->Standard->ALLOWANCE_YEAR_TIME;

            $lists[$key]['Return']['CITY']['DAYS'] = (array) $val->Return->CITY->DAYS;
            $lists[$key]['Return']['CITY']['PAYMENT_TOTAL'] = $val->Return->CITY->PAYMENT_TOTAL;
            $lists[$key]['Return']['CITY']['TIME_TOTAL'] = $val->Return->CITY->TIME_TOTAL;
            $lists[$key]['Return']['CITY']['TIME_EXTRA'] = $val->Return->CITY->TIME_EXTRA;
            $lists[$key]['Return']['CITY']['TIME_NIGHT'] = $val->Return->CITY->TIME_NIGHT;
            $lists[$key]['Return']['CITY']['TIME_HOLIDAY'] = $val->Return->CITY->TIME_HOLIDAY;
            $lists[$key]['Return']['COUNTRY']['DAYS'] = (array) $val->Return->COUNTRY->DAYS;
            $lists[$key]['Return']['COUNTRY']['PAYMENT_TOTAL'] = $val->Return->COUNTRY->PAYMENT_TOTAL;
            $lists[$key]['Return']['COUNTRY']['TIME_TOTAL'] = $val->Return->COUNTRY->TIME_TOTAL;
            $lists[$key]['Return']['COUNTRY']['TIME_EXTRA'] = $val->Return->COUNTRY->TIME_EXTRA;
            $lists[$key]['Return']['COUNTRY']['TIME_NIGHT'] = $val->Return->COUNTRY->TIME_NIGHT;
            $lists[$key]['Return']['COUNTRY']['TIME_HOLIDAY'] = $val->Return->COUNTRY->TIME_HOLIDAY;

            $lists[$key]['Tax']['WORKER_NATIONAL'] = $val->Tax->WORKER_NATIONAL;
            $lists[$key]['Tax']['COMPANY_NATIONAL'] = $val->Tax->COMPANY_NATIONAL;
            $lists[$key]['Tax']['WORKER_HEALTH'] = $val->Tax->WORKER_HEALTH;
            $lists[$key]['Tax']['COMPANY_HEALTH'] = $val->Tax->COMPANY_HEALTH;
            $lists[$key]['Tax']['COMPANY_INDUSTRY'] = $val->Tax->COMPANY_INDUSTRY;
            $lists[$key]['Tax']['WORKER_EMPLOY'] = $val->Tax->WORKER_EMPLOY;
            $lists[$key]['Tax']['COMPANY_EMPLOY'] = $val->Tax->COMPANY_EMPLOY;
            $lists[$key]['Tax']['CLASS_A_WAGE'] = $val->Tax->CLASS_A_WAGE;
            $lists[$key]['Tax']['RESIDENT_TAX'] = $val->Tax->RESIDENT_TAX;

            $lists[$key]['Payment'] = $val->Payment;
            $lists[$key]['Retirement'] = $val->Retirement;
            $lists[$key]['WorkerTaxTotal'] = $val->WorkerTaxTotal;
            $lists[$key]['CompanyTaxTotal'] = $val->CompanyTaxTotal;


            $lists[$key]['User'] = (array) $val->User;


            $lists[$key]['etc_charge_time'] = is_numeric($etc_charge_time[$i]) ? $etc_charge_time[$i] : (float) $etc_charge_time[$i];
            $lists[$key]['etc_charge_pay'] = is_numeric($etc_charge_pay[$i]) ? $etc_charge_pay[$i] : (float) $etc_charge_pay[$i];
            $lists[$key]['except_charge_time'] = is_numeric($except_charge_time[$i]) ? $except_charge_time[$i] : (float) $except_charge_time[$i];
            $lists[$key]['except_charge_pay'] = is_numeric($except_charge_pay[$i]) ? $except_charge_pay[$i] : (float) $except_charge_pay[$i];
            $lists[$key]['bojeon'] = is_numeric($bojeon[$i]) ? $bojeon[$i] : (float) $bojeon[$i];
            $lists[$key]['jaboodam'] = is_numeric($jaboodam[$i]) ? $jaboodam[$i] : (float) $jaboodam[$i];
            $lists[$key]['jaesoodang'] = is_numeric($jaesoodang[$i]) ? $jaesoodang[$i] : (float) $jaesoodang[$i];
            $lists[$key]['bannap'] = is_numeric($bannap[$i]) ? $bannap[$i] : (float) $bannap[$i];
            $lists[$key]['gunbo_tax'] = is_numeric($gunbo_tax[$i]) ? $gunbo_tax[$i] : (float) $gunbo_tax[$i];
            $lists[$key]['year_total_tax'] = is_numeric($year_total_tax[$i]) ? $year_total_tax[$i] : (float) $year_total_tax[$i];
            $lists[$key]['bad_income_get'] = is_numeric($bad_income_get[$i]) ? $bad_income_get[$i] : (float) $bad_income_get[$i];
            $lists[$key]['etc_tax_1'] = is_numeric($etc_tax_1[$i]) ? $etc_tax_1[$i] : (float) $etc_tax_1[$i];
            $lists[$key]['etc_tax_2'] = is_numeric($etc_tax_2[$i]) ? $etc_tax_2[$i] : (float) $etc_tax_2[$i];



            // 사업주 사업합계
            $lists[$key]['CompanyBusinessTotal'] = ($val->Payment + $lists[$key]['etc_charge_pay'] + $lists[$key]['except_charge_pay']) - $val->Standard->PAY_TOTAL;

            // 공제합계
            $lists[$key]['WorkerTaxTotal'] +=
                $lists[$key]['bojeon']
                + $lists[$key]['jaboodam']
                + $lists[$key]['jaesoodang']
                + $lists[$key]['bannap']
                + $lists[$key]['gunbo_tax']
                + $lists[$key]['year_total_tax']
                + $lists[$key]['bad_income_get']
                + $lists[$key]['etc_tax_1']
                + $lists[$key]['etc_tax_2'];

            $i++;
        }



        return view("_excel.payments", [ "lists" => $lists ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'C' => 20,
            'D' => 30,
            'E' => 30,
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 30,
            'L' => 30,
            'M' => 30,
            'N' => 30,
            'O' => 30,
            'P' => 30,
        ];
    }
}
