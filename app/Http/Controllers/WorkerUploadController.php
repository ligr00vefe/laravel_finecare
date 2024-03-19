<?php

namespace App\Http\Controllers;

use App\Imports\HelperDetailsSecondImport;
use App\Imports\WorkerDetailsImport;
use App\Imports\WorkersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WorkerUploadController extends Controller
{
    public function store(Request $request)
    {
        $upload = $request->file("basic_excel_upload")->store("upload/member");

        $import = new WorkersImport;
        $import->type = $request->input("upload_type_basic");
        Excel::import($import, $upload);
        if ($import)
        {
            return redirect("/worker/add/batch")->with("msg", "성공적으로 업로드하였습니다");
        }
        else
        {
            return back()->withErrors("업로드에 실패했습니다. 다시 시도해주세요.");
        }
    }


    public function detail(Request $request)
    {
        $upload = $request->file("detail_excel_upload")->store("upload/member");

        $import = new WorkerDetailsImport();
        $import->type = $request->input("detail_upload_type");
        Excel::import($import, $upload);
        if ($import)
        {
            return redirect("/worker/add/batch")->with("msg", "성공적으로 업로드하였습니다");
        }
        else
        {
            return back()->withErrors("업로드에 실패했습니다. 다시 시도해주세요.");
        }
    }

    public function detail_second(Request $request)
    {
        $upload = $request->file("detail_excel_upload2_2")->store("upload/member");

        $import = new HelperDetailsSecondImport();
        $import->type = $request->input("detail_upload_type_2_1");
        Excel::import($import, $upload);
        if ($import)
        {
            return redirect("/worker/add/batch")->with("msg", "성공적으로 업로드하였습니다");
        }
        else
        {
            return back()->withErrors("업로드에 실패했습니다. 다시 시도해주세요.");
        }
    }

}
