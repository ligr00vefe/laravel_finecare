<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CrawlingController extends Controller
{
    public function location()
    {
        $guzzle = Http::get('https://github.com/vuski/admdongkor/blob/master/%ED%86%B5%EA%B3%84%EC%B2%ADMDIS%EC%9D%B8%EA%B5%AC%EC%9A%A9_%ED%96%89%EC%A0%95%EA%B2%BD%EA%B3%84%EC%A4%91%EC%8B%AC%EC%A0%90/coordinate_UTMK_%EC%9D%B4%EB%A6%84%ED%8F%AC%ED%95%A8.tsv');
        $filter = $guzzle->body();
//        dd($filter);
        $aa = preg_match("/<tbody>(.*?)<\/tbody>/s",
            $filter, $test);
        dd($test);
    }
}
