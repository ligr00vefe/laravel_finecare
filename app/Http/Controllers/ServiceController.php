<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type="exist", Request $request)
    {
        $match = [
            "exist" => "existList",
            "new" => "newList"
        ];

        $page = $request->input("page") ?? 1;

        $get = Service::get($type, $page);

        return view("service.{$match[$type]}", [ "page"=>$page, "type" => $type, "lists" => $get['lists'], "paging" => $get['paging'] ]);
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

    public function voucher($page=1, Request $request)
    {
        if ($request->input("test") == "xptmxm") {
        }
        $find = Service::find($request, $page);

        return view("service.voucher", [ "page"=>$page, "lists"=>$find['lists'] ?? [], "paging"=>$find['paging']->cnt ?? 0 ]);
    }


    public function upload()
    {
        return view("service.upload");
    }

//    public function manage($page)
//    {
//        $get = Service::get("new", $page);
//        return view("service.manage", [ "page"=>$page, "lists"=>[], "paging" => $get['paging'] ]);
//    }
//
//    public function manage_create()
//    {
//        return view("service.create");
//    }

    public function excel_upload(Request $request)
    {
        $upload = Service::upload($request);
        session()->flash("uploadMsg", "전자바우처 내역을 업로드했습니다. 성공내역 {$upload['succCnt']}건, 실패내역 {$upload['errCnt']}건");
        return redirect("/service/voucher/upload");
    }

    public function download(Request $request)
    {
        switch($request->input("type"))
        {
            case "basic":
                return Storage::download("docs/voucher_basic_form.xlsx");
                exit;
                break;
            case "new":
                return Storage::download("./docs/voucher_basic_form.xlsx");
                break;
            default:
                session()->flash("uploadMsg", "잘못된 유형입니다");
                break;
        }
    }

}
