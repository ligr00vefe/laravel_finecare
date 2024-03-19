<?php

namespace App\Http\Controllers;

use App\Classes\Holiday;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HelperServiceCalendarController extends Controller
{

    public function kind(Request $request)
    {
        $user_id = User::get_user_id();
        $provider_key = $request->input("provider_key");
        $target_key = $request->input("target_key");
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));

        $get = DB::table("voucher_logs")
            ->select(DB::raw("ANY_VALUE(target_key) as target_key,
                ANY_VALUE(provider_key) as provider_key,
                ANY_VALUE(service_start_date_time) as service_start_date_time,
                SEC_TO_TIME(sum(TIME_TO_SEC(social_activity_support))) AS sas_total,
                SEC_TO_TIME(sum(TIME_TO_SEC(physical_activity_support))) AS pas_total,
                SEC_TO_TIME(sum(TIME_TO_SEC(housekeeping_activity_support))) AS has_total,
                SEC_TO_TIME(sum(TIME_TO_SEC(etc_service))) AS es_total,
                SEC_TO_TIME(sum(TIME_TO_SEC(social_activity_support) + TIME_TO_SEC(physical_activity_support) + TIME_TO_SEC(housekeeping_activity_support) + TIME_TO_SEC(etc_service))) AS total,
                ANY_VALUE(business_type) as business_type,
                ANY_VALUE(payment_time) as payment_time
                "))
            ->where("user_id", "=", $user_id)
            ->where("provider_key", "=", $provider_key)
            ->when($target_ym, function ($query, $target_ym) {
                return $query->whereRaw("service_start_date_time >= ? AND service_start_date_time <= LAST_DAY(?)", [ $target_ym, $target_ym ]);
            })
            ->when($target_key, function($query, $target_key) {
                return $query->whereRaw("target_key = ?", [ $target_key ]);
            })
            ->orderByDesc("service_start_date_time")
            ->groupByRaw("service_start_date_time")
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

    public function time(Request $request)
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




        return response([ "data" => $get, "members" => $getAll, "timetable" => $timetable  ]);
    }


    public function kindRender(Request $request)
    {

        $week_list = [ "일", "월", "화", "수", "목", "금", "토" ];

        $type = $request->input("type");
        $provider_key = $request->input("provider_key");

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

            $_day = (int) date("d", strtotime($log['service_start_date_time']));

            if (!isset($schedule[$_day])) {
                $schedule[$_day] = [
                    "day" => $_day,
                    "social" => 0,
                    "physical" => 0,
                    "housekeeping" => 0,
                    "etc" => 0,
                    "total" => 0,
                ];
            }

            if ($log['sas_total'] != "00:00:00") {
                $schedule[$_day]['social'] += strtotime($log['sas_total']);
            }

            if ($log['pas_total'] != "00:00:00") {
                $schedule[$_day]['physical'] += strtotime($log['pas_total']);
            }

            if ($log['has_total'] != "00:00:00") {
                $schedule[$_day]['housekeeping'] += strtotime($log['has_total']);
            }

            if ($log['es_total'] != "00:00:00") {
                $schedule[$_day]['etc'] += strtotime($log['es_total']);
            }

            if ($log['total'] != "00:00:00") {
                $schedule[$_day]['total'] += strtotime($log['total']);
            }

            $time_total += $log['payment_time'];

        }


        $from_date = date("Y-m-d", strtotime($request->input("from_date"))) ?? date("Y-m-d");
        $public_holiday = Holiday::getPublicHoliday($from_date);


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
            "timetable" => isset($timetable[$provider_key]) ? $timetable[$provider_key] : [],
            "public_holiday" => $public_holiday
        ]);
    }



    public function timeRender(Request $request)
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

        $from_date = date("Y-m-d", strtotime($request->input("from_date"))) ?? date("Y-m-d");
        $public_holiday = Holiday::getPublicHoliday($from_date);


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
            "public_holiday" => $public_holiday
        ]);
    }

}
