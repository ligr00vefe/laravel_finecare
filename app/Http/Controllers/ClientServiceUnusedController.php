<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientServiceUnusedController extends Controller
{

    public function index(Request $request)
    {
        $target_ym = $request->input("target_ym");

        $page = $request->input("page") ?? 1;

        $query = DB::table("clients")
            ->selectRaw("clients.*")
            ->where("user_id", "=", User::get_user_id())
            ->whereRaw("NOT EXISTS (SELECT * FROM voucher_logs WHERE user_id = ? AND target_key = clients.target_key AND target_ym = ?)", [ User::get_user_id(), date("Y-m-d", strtotime($target_ym)) ]);

        $paging = $query->count();
        $lists = $query->paginate();


//        $ch = curl_init();
//        $url = 'http://apis.data.go.kr/B090041/openapi/service/SpcdeInfoService/getRestDeInfo'; // URL
//        $queryParams = '?' . urlencode('ServiceKey') . '0uFBsPAvdo7B7DD9qw9EpS/X14TMRt5nMctpFu4ZIRYa17dJqDzwmIAa52l0xK49yfnOsVpmKsYDmTUctmT0Sw=='; // Service Key
//        $queryParams .= '&' . urlencode('solYear') . '=' . urlencode($year); // 연도
//        $queryParams .= '&' . urlencode('solMonth') . '=' . urlencode($month); // 월
//
//        curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($ch, CURLOPT_HEADER, FALSE);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        $xml = simplexml_load_string($response);
//        $json = json_encode($xml);
//        $data = json_decode($json,true);
//
//        for($i=1;$i<=12;$i++) {
//            $currentYear = date('Y');
//            $currentMonth = sprintf('%02d',$i);
//
//            $data = $data($currentYear,$currentMonth);
//
//            foreach($data[body][items] as $val) {
//                pp($val);
//            }
//        }

        return view("member.unused", [
            "lists" => $lists,
            "paging" => $paging,
            "page" => $page
        ]);
    }

}
