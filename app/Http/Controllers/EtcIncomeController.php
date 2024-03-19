<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EtcIncome;

class EtcIncomeController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type, $page=1)
    {
        $match = [
            "fare" => "transportationList",
            "severe" => "severeList"
        ];

        $get = EtcIncome::get($page, $type);

        return view("etcIncome.{$match[$type]}", ["type"=>$type, "page"=>$page, "lists" => $get['lists'], "paging"=>$get['paging'] ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $match = [
            "fare" => "transportationUploads",
            "severe" => "severeUploads"
        ];

        return view("etcIncome.{$match[$type]}", [ "type"=> $type ]);
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

    public function fare_upload(Request $request)
    {
        $insert = EtcIncome::fare_upload($request);
        session()->flash("uploadMsg", "원거리교통비 내역을 업로드했습니다. 성공내역 {$insert['succCnt']}건, 실패내역 {$insert['errCnt']}건");
        return redirect("/etcIncome/fare/registration");
    }


    public function severe_upload(Request $request)
    {
        $insert = EtcIncome::severe_upload($request);
        session()->flash("uploadMsg", "중증가산수당 내역을 업로드했습니다. 성공내역 {$insert['succCnt']}건, 실패내역 {$insert['errCnt']}건");
        return redirect("/etcIncome/severe/registration");
    }


}
