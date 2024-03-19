<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Social;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function insurance($page=1)
    {
        return view("social.insurance", [ "page"=>$page ]);
    }

    public function collect($type=1)
    {

        $title = "";
        $download = "";

        switch($type)
        {
            case 1:
                $title = "국민연금";
                $download = "통합징수포탈_국민연금양식.csv";
                break;
            case 2:
                $title = "건강보험";
                $download = "통합징수포탈_건강보험양식.csv";
                break;
            case 3:
                $title = "산재보험";
                $download = "통합징수포탈_산재보험양식.csv";
                break;
            case 4:
                $title = "고용보험";
                $download = "통합징수포탈_고용보험양식.csv";
                break;
        }

        return view("social.collectUploads", [ "type" => $type, "title" => $title, "download" => $download ]);

    }

    public function collect_list($type=1, $page=1)
    {
        $title = "";
        switch($type)
        {
            case "np":
                $title = "국민연금";
                break;
            case "health":
                $title = "건강보험";
                break;
            case "ind":
                $title = "산재보험";
                break;
            case "emp":
                $title = "고용보험";
                break;
        }

        $get = Social::get($type, $page);

        return view("social.collectList", [ "title"=> $title, "page" => $page, "lists" => $get['lists'], "paging" => $get['paging'] ]);
    }

    public function edi_upload($type=1)
    {
        $title = "";
        switch($type)
        {
            case 1:
                $title = "국민연금";
                break;
            case 2:
                $title = "건강보험";
                break;
            case 3:
                $title = "산재보험";
                break;
            case 4:
                $title = "고용보험";
                break;
        }

        return view("social.ediUpload", [ "type" => $type, "title" => $title ]);
    }

    public function edi_list($type=1, Request $request)
    {
        $page = $request->input("page") ?? 1;

        $title = "";
        switch($type)
        {
            case "np":
                $title = "국민연금";
                break;
            case "health":
                $title = "건강보험";
                break;
            case "ind":
                $title = "산재보험";
                break;
            case "emp":
                $title = "고용보험";
                break;
        }

        $get = Social::edi_get($type, $page, $request);

        return view("social.ediList", ["type"=>$type, "page"=>$page, "title"=>$title, "lists"=>$get['lists'], "paging"=>$get['paging'] ]);
    }


    public function collect_excel($type, Request $request)
    {

        $title = "";
        $table = "";

        switch ($type)
        {
            case "1":
                $title = "국민연금";
                $table = "national_pension_logs";
                break;
            case "2":
                $title = "건강보험";
                $table = "health_ins_logs";
                break;
            case "3":
                $title = "산재보험";
                $table = "industry_ins_logs";
                break;
            case "4":
                $title = "고용보험";
                $table = "employment_ins_logs";
                break;
            default:
                break;
        }

        $insert = Social::upload($table, $request);

        if ($insert) {
            session()->flash("uploadMsg", "{$title} 내역을 업로드했습니다. 성공내역 {$insert['succCnt']}건, 실패내역 {$insert['errCnt']}건");
        }

        return redirect("/social/collect/upload/{$type}");

    }



    public function edi_upload_action($type, Request $request)
    {

        $title = "";
        $table = "";

        switch ($type)
        {
            case "1":
                $title = "국민연금";
                $table = "edi_national_pension_logs";
                break;
            case "2":
                $title = "건강보험";
                $table = "edi_health_ins_logs";
                break;
            case "3":
                $title = "산재보험";
                $table = "edi_industry_ins_logs";
                break;
            case "4":
                $title = "고용보험";
                $table = "edi_employment_ins_logs";
                break;
            default:
                break;
        }

        $insert = Social::edi_upload($table, $request);

        if ($insert) {
            session()->flash("uploadMsg", "{$title} 내역을 업로드했습니다. 성공내역 {$insert['succCnt']}건, 실패내역 {$insert['errCnt']}건");
        }

        return redirect("/social/EDI/upload/{$type}");

    }

}
