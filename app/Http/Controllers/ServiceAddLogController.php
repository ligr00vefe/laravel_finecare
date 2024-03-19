<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AddServiceLog;
use Maatwebsite\Excel\Facades\Excel;

class ServiceAddLogController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file("excel")->store("upload/docs");
        $import = Excel::import(new AddServiceLog, $file);


        if ($import) {
            return redirect("/service/extra/list")->with("msg", "업로드했습니다");
        } else {
            return back()->with("error", "업로드에 실패했습니다/");
        }
    }
}
