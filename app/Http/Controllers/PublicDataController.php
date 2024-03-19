<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PublicDataController extends Controller
{
    public function special()
    {
        $response = Http::get('http://apis.data.go.kr/B090041/openapi/service/SpcdeInfoService/getRestDeInfo?'
            .'ServiceKey=0uFBsPAvdo7B7DD9qw9EpS%2FX14TMRt5nMctpFu4ZIRYa17dJqDzwmIAa52l0xK49yfnOsVpmKsYDmTUctmT0Sw%3D%3D'
            .'&pageNo=1'
            .'&numOfRows=100'
            .'&solYear=2021'
        );

//        dd($response);
        $load_string = simplexml_load_string($response->body());
        pp($load_string);

        foreach ($load_string->body->items->item as $item) {
            $fulldate = date("Y-m-d", strtotime($item->locdate));
            $year = date("Y", strtotime($item->locdate));
            $month = date("m", strtotime($item->locdate));
            $day = date("d", strtotime($item->locdate));

            DB::table("holiday_lists")
                ->updateOrInsert(
                    [ "full_date" => $fulldate ],
                    [ "year" => $year, "month" => $month, "day" => $day, "admin_id" => 1, "comment" => $item->dateName ]
                );
        }


    }
}
