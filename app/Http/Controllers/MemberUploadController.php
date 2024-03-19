<?php

namespace App\Http\Controllers;

use App\Imports\MemberImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MemberUploadController extends Controller
{
    public function store(Request $request)
    {
        $upload = $request->file("basic_excel_upload")->store("upload/member");

        $import = new MemberImport;
        $import->type = $request->input("upload_type_basic");
        Excel::import($import, $upload);
        if ($import)
        {
            return redirect()->route("member.list.index")->with("msg", "성공적으로 업로드하였습니다");
        }
        else
        {
            return redirect()->route("member.list.index")->withErrors("업로드에 실패했습니다. 다시 시도해주세요.");
        }
    }
}
