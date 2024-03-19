<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $from_date = $request->input("from_date") ?? false;
        $year = isset($from_date) ? date("Y", strtotime($request->input("from_date"))) : "0000";
        $month = isset($from_date) ? date("m", strtotime($request->input("from_date"))) : "00";
        if ($from_date)
        {
            $list = Payslip::get($request);
            if($list['multiple_check'] === 1)
            {
                $provider_key = $list['worker']->provider_key ?? "";
            }
            else
            {
                $provider_key = '';
            }
        }



        return View("salary.payslip.index", [
            "provider_key" => $provider_key ?? "",
            "list" => $list ?? [],
            "year" => $year,
            "month" => $month,
            "from_date" => $from_date ?? "",
            "name" => $request->input("name") ?? "",
            "birth" => $request->input("birth") ?? "",
            "multiple_check" => $list['multiple_check'] ?? null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
