<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkerOff;
use App\Models\WorkerService;
use Illuminate\Http\Request;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;


class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type, $page=1)
    {
        $type_arr = ["all", "reg", "use", "termi", "cancel"];
        if (!in_array($type, $type_arr)) {
            http_response_code(404);
            return "404 not found...";
        }

        $get = Worker::get($page, $type);

        return view("worker.index", ["type" => $type, "page" => $page, "lists" => $get['lists'], "paging" => $get['paging']->cnt ]);
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


    public function store(Request $request)
    {
        $add = Worker::add($request);

        if ($add)
        {
            return redirect("/worker/add/one")->with("msg", "활동지원사를 추가하거나 수정했습니다.");
        }
        else
        {
            return back()->with("error", "등록에 실패했습니다. 다시 시도해 주세요.");
        }
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


    public function pay($page=1, Request $request)
    {
        $get = Worker::pay($page, $request);

        return view("worker.monthlyPay", ["page"=>$page, "lists"=>$get['lists'], "paging"=>$get['paging'] ]);
    }

    public function cal($type="hour", $page=1)
    {
        $match = [
            "hour" => "workHourList"
        ];

        return view("worker.{$match[$type]}", ["type"=>$type, "page"=>$page ]);
    }

    public function allowance($page=1)
    {
        return view("worker.allowance", [ "page"=>$page ]);
    }

    public function retiring($type, $page=1)
    {
        $match = [
            "total" => "retiringTotal"
        ];

        return view("worker.{$match[$type]}", ["type"=> $type, "page"=>$page]);
    }

    public function work($page=1)
    {
        return view("worker.worklist", [ "page"=> $page ] );
    }

    public function off(Request $request)
    {
        $page = $request->input("page");
        $lists = WorkerOff::get($request);
        return view("worker.workOffList", [ "page"=> $page, "lists" => $lists['lists'], "paging" => $lists['paging'] ]);
    }

    public function date($page=1)
    {
        return view("worker.workDateList", [ "page"=> $page ] );
    }


    public function payment(Request $request)
    {
        $user_id = User::get_user_id();

        $year = date("Y", strtotime($request->input("from_date")));
        $from_date = date("Y-m-d", strtotime($year."-01-01"));
        $end_date = date("Y-m-d", strtotime($year."-12-31"));

        $page = $request->input("page");
        $get = DB::table("voucher_logs")
            ->selectRaw("provider_key, ANY_VALUE(provider_name) as provider_name,
             ANY_VALUE(date_format(target_ym, '%m-%d')) as ym,
             ANY_VALUE(provider_birth) as provider_birth, target_ym, MONTH(`target_ym`) AS `date`, count(id) as count")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", ">=", $from_date)
            ->where("target_ym", "<=", $end_date)
            ->where("payment_type","=","소급결제")
            ->groupBy("provider_key", "target_ym")
            ->get();

        $alls = DB::table("voucher_logs")
            ->selectRaw("
             ANY_VALUE(date_format(target_ym, '%m-%d')) as ym,
             ANY_VALUE(provider_birth) as provider_birth, target_ym, MONTH(`target_ym`) AS `date`, count(id) as count")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", ">=", $from_date)
            ->where("target_ym", "<=", $end_date)
            ->groupBy("target_ym")
            ->get();






        $lists = [];

        $denominator = [
            "01-01" => 0,
            "02-01" => 0,
            "03-01" => 0,
            "04-01" => 0,
            "05-01" => 0,
            "06-01" => 0,
            "07-01" => 0,
            "08-01" => 0,
            "09-01" => 0,
            "10-01" => 0,
            "11-01" => 0,
            "12-01" => 0
        ];

        $month_total = [
            "01-01" => 0,
            "02-01" => 0,
            "03-01" => 0,
            "04-01" => 0,
            "05-01" => 0,
            "06-01" => 0,
            "07-01" => 0,
            "08-01" => 0,
            "09-01" => 0,
            "10-01" => 0,
            "11-01" => 0,
            "12-01" => 0
        ];

        $total = 0;

        foreach ($get as $key => $val)
        {
            if (!isset($lists[$val->provider_key])) {
                $lists[$val->provider_key] = [];
            }

            if (!isset($val->total)) {
                $val->total = 0;
            }

            $val->total += $val->count;
            $lists[$val->provider_key][] = $val;
            $month_total[$val->ym] += $val->count;
            $total += $val->count;
        }

        $all_arr = [];

        $all_payment_total = 0;

        foreach ($alls as $all)
        {
            if (!isset($all_arr[$all->ym])) {
                $all_arr[$all->ym] = $all;
            }

            $all_payment_total += $all->count;
        }

        $all_sogeup_payment_total = 0;


        foreach ($month_total as $key => $total)
        {

            if (!isset($all_arr[$key])) continue;

            $denominator[$key] = round($total / $total * 100);
            $all_sogeup_payment_total += $total;
        }

        return view("worker.monthlyPayment", [
            "page"=> $page,
            "year" => $year,
            "lists" => $lists,
            "month_total" => $month_total,
            "denominator" => $denominator,
            "all_sogeup_payment_total" => $all_sogeup_payment_total,
            "all_payment_total" => $all_payment_total,
        ]);
    }

    public function service(Request $request)
    {
        $page = $request->input("page");
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));
        $user_id = User::get_user_id();

        $lists = DB::table("voucher_logs")
            ->selectRaw("provider_key, ANY_VALUE(provider_name) as provider_name, ANY_VALUE(provider_birth) as provider_birth")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", "=", $target_ym)
            ->groupBy("provider_key")
            ->get();

        return view("worker.serviceOffer", [ "page" => $page, "lists" => $lists ]);
    }

    public function workers(Request $request)
    {
        $page = $request->input("page") ?? 1;
        $from_date = $request->input("from_date");
        $to_date = $request->input("to_date");
        $term = $request->input("term");

        $user_id = User::get_user_id();
        $get = DB::table("helpers")
            ->where("user_id", "=", $user_id)
            ->when($from_date, function ($query, $from_date) {
                return $query->where("contract_start_date", ">=", $from_date);
            })
            ->when($to_date, function ($query, $to_date) {
                return $query->where("contract_end_date", "<=", $to_date);
            })
            ->when($term, function ($query, $term) {
                return $query->where("name", "like", $term);
            });

        $paging = $get->count();
        $lists = $get->paginate(15);

        return view("worker.workers", [ "page" => $page, "lists"=>$lists, "paging"=>$paging ]);
    }

    public function add($type, $page=1)
    {

        $match = [
            "one" => "addOne",
            "batch" => "batch"
        ];

        return view("worker.{$match[$type]}", [ "type"=>$type, "page" => $page ]);
    }



    public function batch_basic(Request $request)
    {
        $upload = Worker::batch($request);

        $return_msg = "업로드를 완료했습니다. 성공: {$upload['succCnt']}건, 실패: {$upload['errCnt']}건";
        session()->flash("batch_msg", $return_msg);

        return redirect("/worker/add/batch");
    }


    public function calendar_reload(Request $request)
    {
        $user_id = User::get_user_id();
        $provider_key = $request->input("provider_key");
        $target_key = $request->input("target_key");
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));

        $get = DB::table("voucher_logs")
            ->selectRaw(
            "provider_key,
            ANY_VALUE(payment_time) as payment_time,
            ANY_VALUE(service_start_date_time) as service_start_date_time,
            ANY_VALUE(service_end_date_time) as service_end_date_time,
            ANY_VALUE(target_key) as target_key"
            )
            ->where("user_id", "=", $user_id)
            ->where("provider_key", "=", $provider_key)
            ->where("target_ym", "=", $target_ym)
            ->when($target_key, function ($query, $target_key) {
                return $query->where("target_key","=",$target_key);
            })
            ->groupByRaw("provider_key, service_start_date_time, target_key")
            ->orderByRaw("provider_key, service_start_date_time")
            ->get();

        $getAll = DB::table("voucher_logs")
            ->select("target_key")
            ->where("user_id", "=", $user_id)
            ->where("provider_key", "=", $provider_key)
            ->where("target_ym", "=", $target_ym)
            ->when($target_key, function ($query, $target_key) {
                return $query->where("target_key","=",$target_key);
            })
            ->groupByRaw("target_key")
            ->get();

        $timetable_from_db = DB::table("helper_confirm_schedules")
            ->selectRaw("worker_id, work_type, GROUP_CONCAT(DATE_FORMAT(helper_confirm_schedules.`date`, '%d')) as days")
            ->where("user_id", "=", $user_id)
            ->whereRaw("(`date` > LAST_DAY('2021-01-01' - interval 1 month) AND `date` <= LAST_DAY('2021-01-01') )")
            ->when($target_key, function ($query, $target_key) {
                return $query->where("worker_id", "=", $target_key);
            })
            ->groupBy("worker_id", "work_type")
            ->get();

        $timetable = [];

        foreach ($timetable_from_db as $key => $val) {

            if (!isset($timetable[$val->worker_id])) {
                $timetable[$val->worker_id] = [
                    "work" => [],
                    "off" => []
                ];
            }

            $days_int_only = array_map(function($day) { return (int) $day; }, explode(",", $val->days));

            if ($val->work_type == "근무") {
                $timetable[$val->worker_id]['work'] = $days_int_only;
            }

            if ($val->work_type == "비번") {
                $timetable[$val->worker_id]['off'] = $days_int_only;
            }

        }

        return response([ "data" => $get, "members" => $getAll, "timetable" => $timetable ]);
    }


    public function render_calendar(Request $request)
    {
        $week_list = [ "일", "월", "화", "수", "목", "금", "토" ];

        $type = $request->input("type");

        $getYM = $request->input('from_date') ?? date("Y-m");

        $day =  date("Y-m-d", strtotime(date("Y-m", strtotime($getYM))."-01"));
        $year = date("Y", strtotime($day));
        $month = date("m", strtotime($day));
        $week_day = date("w", strtotime($day));
        $dayWeek = $week_list[$week_day];
        $endDay = date("t", strtotime($day));
        $lastMonthEndDay = date("t", strtotime($day. "-1 day"));
        $logs = json_decode($request->input("schedule"), true);
        $timetable = json_decode($request->input("timetable"), true);
        $schedule = [];
        $time_total = 0;


        foreach ($logs as $key => $log) {

            if (!isset($schedule[$log['provider_key']])) {
                $schedule[$log['provider_key']] = [
                    "date" => [],
                    "time" => [],
                    "payment_time" => [],
                    "total_time" => [],
                    "holiday_work" => [],
                    "holiday_off" => [],
                ];
            }

            $date = date("Y-m-d", strtotime($log['service_start_date_time']));

            if (!isset($schedule[$log['provider_key']]['date'][$date])) {
                $schedule[$log['provider_key']]['date'][] = $date;
            }

            if (isset($timetable[$log['provider_key']])) {
                $schedule[$log['provider_key']]['holiday_work'] = $timetable[$log['provider_key']]['work'];
                $schedule[$log['provider_key']]['holiday_off'] = $timetable[$log['provider_key']]['off'];
            }

            if (!isset($schedule[$log['provider_key']]['time'][$date])) {
                $schedule[$log['provider_key']]['time'][$date] = [];
            }

            if (!isset($schedule[$log['provider_key']]['total_time'][$date])) {
                $schedule[$log['provider_key']]['total_time'][$date] = 0;
            }

            $schedule[$log['provider_key']]['time'][$date][] = date("H:i", strtotime($log['service_start_date_time']))."~".date("H:i", strtotime($log['service_end_date_time']));
            $schedule[$log['provider_key']]['payment_time'][] = $log['payment_time'];
            $schedule[$log['provider_key']]['total_time'][$date] += $log['payment_time'];

            $time_total += $log['payment_time'];

        }

        return view("worker.service_calendar", [
            "type" => $type,
            "schedule"=> $schedule,
            "provider_key" => $request->input("provider_key"),
            "time_total" => $time_total,
            "year" => $year,
            "month" => $month,
            "week_day" => $week_day,
            "dayWeek" => $dayWeek,
            "endDay" => $endDay,
            "lastMonthEndDay" => $lastMonthEndDay,
        ]);
    }

}
