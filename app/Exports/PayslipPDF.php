<?php

namespace App\Exports;

use App\Models\Payslip;
use FontLib\Font;
use PDF;

class PayslipPDF{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }


    public function createPDF(){
        $from_date = $this->request['from_date'];
        $name = $this->request['name'];
        $birth = $this->request['birth'];
        $year = isset($from_date) ? date("Y", strtotime($from_date)) : "0000";
        $month = isset($from_date) ? date("m", strtotime($from_date)) : "00";



        $lists = Payslip::get($this->request);

        $pdf = PDF::loadView('salary.pdf.payslip', ["lists" => $lists, "year"=>$year, "month"=>$month, "from_date"=>$from_date, "name"=>$name, "birth"=>$birth, "provider_key"=>$lists['provider_key']]);


        return $pdf->download('급여명세서.pdf');
    }

}
