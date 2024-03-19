<?php

namespace App\Http\Controllers;

use App\Imports\LocationUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LocationUploadController extends Controller
{
    // https://github.com/vuski/admdongkor/blob/master/%ED%86%B5%EA%B3%84%EC%B2%ADMDIS%EC%9D%B8%EA%B5%AC%EC%9A%A9_%ED%96%89%EC%A0%95%EA%B2%BD%EA%B3%84%EC%A4%91%EC%8B%AC%EC%A0%90/coordinate_UTMK_%EC%9D%B4%EB%A6%84%ED%8F%AC%ED%95%A8.tsv
    // 구글스프레드시트로 IMPORTHTML 로 파싱해서 가져오고 있음


    public function upload(Request $request)
    {
        $import = new LocationUpload();
        Excel::import($import, "storage/docs/대한민국행정구역(함수제거).xlsx");
        if ($import)
        {
            echo "success..!";
        }
        else
        {
            echo "failed...";
        }
    }
}
