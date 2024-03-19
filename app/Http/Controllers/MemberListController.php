<?php

namespace App\Http\Controllers;

use App\Classes\Holiday;
use App\Models\Clients;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Service;
use Illuminate\Support\Facades\DB;


class MemberListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->input("page") ?? 1;
        $get = Clients::get($request);
        $lists = $get['lists'];
        $paging = $get['paging'];

        return view("member.list", [
            "lists" => $lists,
            "paging" => $paging,
            "page" => $page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type = "individual")
    {
        return view("member.{$type}", [
            "type" => $type,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $add = Member::add($request);
        session()->flash("msg", "이용자를 추가하거나 수정했습니다.");
        return redirect("/member/list");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        dd($request->input());
        $ids = $request->input("id");

        foreach ($ids as $i => $id) {

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function sort($type, $page = 1)
    {
        $type_arr = [
            "mng" => "mng",
            "mna" => "mna",
            "gna" => "gna",
            "lna" => "lna",
            "snt" => "snt",
        ];

        function show($msg)
        {
            echo "<pre>";
            print_r($msg);
            echo "</pre>";
        }

        $lists = Member::sort($type);
        $averageLists = Member::averageSort($type);


        if ($type == "mna") {
            // 연인원 데이터
            $averageTotalType1 = $lists['averageTotalType1'];
            $averageTotalType2 = $lists['averageTotalType2'];
            $averageLists = $lists['averageAges'];

            $totalType1 = $lists['totalType1'];
            $totalType2 = $lists['totalType2'];
            $lists = $lists['ages'];

        }

        if ($type == "gna" || $type == "lna") {
            $averageTotalType1 = $lists['averageTotalType1'];
            $averageLists = $lists['averageAges'];

            $totalType1 = $lists['totalType1'];
            $lists = $lists['ages'];
        }


        $columns = Member::$disables_key;


        return view("member.sort.{$type_arr[$type]}", [
            "page" => $page,
            "type" => $type,
            "lists" => $lists,
            "averageLists" => $averageLists ?? [],
            "columns" => $columns,
            "totalType1" => $totalType1 ?? [],
            "totalType2" => $totalType2 ?? [],
            "averageTotalType1" => $averageTotalType1 ?? [],
            "averageTotalType2" => $averageTotalType2 ?? [],
        ]);
    }

    public function service($page = 1, Request $request)
    {
        $get = Member::getAll($request);

        return view("member.serviceCheckList", ["page" => $page, "members" => $get['member']]);
    }

    public static function service_list(Request $request)
    {
        $get = Member::get_service_using($request);

        return view("member.serviceList", [
            "lists" => $get['lists'],
            "total" => $get['total'],
            "paging" => $get['paging'],
            "page" => $request->input("page") ?? 1
        ]);
    }


    public function basic_upload(Request $request)
    {
        $upload = Member::batch($request);

        $return_msg = "업로드를 완료했습니다. 성공: {$upload['succCnt']}건, 실패: {$upload['errCnt']}건";
        session()->flash("batch_msg", $return_msg);

        return redirect("/member/add/batch/");
    }


    public function excel_download()
    {
        Member::list_excel_download();
    }

    public function calendar_call(Request $request)
    {
        $get = Service::calendar_reload($request);
        $from_date = $request->input("from_date");

        return response($get);
    }


    public function calendar_reload(Request $request)
    {
        $week_list = ["일", "월", "화", "수", "목", "금", "토"];

        $type = $request->input("type");

        $from_date = date("Y-m-d", strtotime($request->input('from_date')));
        $getYM = $request->input('from_date') ?? date("Y-m");

        $day = date("Y-m-d", strtotime(date("Y-m", strtotime($getYM)) . "-01"));
        $year = date("Y", strtotime($day));
        $month = date("m", strtotime($day));
        $week_day = date("w", strtotime($day));
        $dayWeek = $week_list[$week_day];
        $endDay = date("t", strtotime($day));
        $lastMonthEndDay = date("t", strtotime($day . "-1 day"));
        $schedule = $request->input("schedule");
        $public_holiday = Holiday::getPublicHoliday($from_date);

        return view("member.service_calendar", [
            "type" => $type,
            "schedule" => $schedule,
            "member_id" => $request->input("member_id"),
            "helpers" => $request->input("helpers"),
            "time_total" => $request->input("time_total"),
            "year" => $year,
            "month" => $month,
            "week_day" => $week_day,
            "dayWeek" => $dayWeek,
            "endDay" => $endDay,
            "lastMonthEndDay" => $lastMonthEndDay,
            "public_holiday" => $public_holiday
        ]);
    }


}
