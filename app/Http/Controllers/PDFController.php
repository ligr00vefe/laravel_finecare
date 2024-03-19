<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PayslipPDF;
use PDF;

class PDFController extends Controller
{
    public function payslip(Request $request){

        $payslip = new PayslipPDF($request);

        return $payslip->createPDF();
    }
}
