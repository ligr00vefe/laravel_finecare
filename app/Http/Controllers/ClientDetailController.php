<?php

namespace App\Http\Controllers;

use App\Imports\ClientDetailImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClientDetailController extends Controller
{
    public function store(Request $request)
    {
        $upload = $request->file("detail_excel_upload")->store("upload/member");

        $import = new ClientDetailImport;
        $import->type = $request->input("detail_upload_type");
        Excel::import($import, $upload);
        if ($import)
        {
            return redirect()->route("member.list.index")->with("msg", "성공적으로 업로드하였습니다");
        }
        else
        {
            return back()->withErrors("업로드에 실패했습니다. 다시 시도해주세요.");
        }
    }
}
