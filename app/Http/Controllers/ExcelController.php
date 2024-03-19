<?php

namespace App\Http\Controllers;

use App\Exports\ClientExport;
use App\Exports\HelperExport;
use App\Exports\HelperScheduleExport;
use App\Exports\RecalcOffDayPay;
use App\Exports\ServiceExtraExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use App\Exports\PayslipExport;

class ExcelController extends Controller
{

    public function payment(Request $request)
    {
        return Excel::download(new PaymentsExport($request), "파인케어_급여계산_". date("Y-m-d") .".xlsx");
    }

    public function helpers(Request $request)
    {
        return Excel::download(new HelperExport($request), "파인케어_활동지원사_". date("Y_m_d") .".xlsx");
    }

    public function schedule(Request $request)
    {
        return Excel::download(new HelperScheduleExport($request), "파인케어_활동지원사_계획표양식.xlsx");
    }

    public function payslip(Request $request)
    {
        return Excel::download(new PayslipExport($request), "파인케어_급여명세서_".date("Y_m_d").".xlsx");
    }

    public function recalcOffDayPay(Request $request)
    {
        return Excel::download(new RecalcOffDayPay($request), "파인케어_연차수당_재정산".date("Y_m_d").".xlsx");
    }

    public function clients(Request $request)
    {
        return Excel::download(new ClientExport($request), "파인케어_이용자_". date("Y_m_d") .".xlsx");
    }

    public function serviceExtra(Request $request)
    {
        return Excel::download(new ServiceExtraExport($request), "파인케어_추가서비스내역_". date("Y_m_d") .".xlsx");
    }


}
