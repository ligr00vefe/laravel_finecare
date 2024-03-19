<?php

namespace App\Models;

use App\Classes\Custom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Holiday extends Model
{
    use HasFactory;
    protected $table = "holiday_lists";


    public static function get($data)
    {
        $target_ym = date("Y-m-d", strtotime($data['date']));

        $monthHoliday = DB::table("holiday_lists")
            ->select(DB::raw("group_concat(distinct(full_date)) as date")) // ex) 1월 1일이 2개 들어가도 중복제거처리 되도록 distinct
            ->whereRaw("full_date >= ? and full_date < DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY )",
                [ $target_ym, $target_ym ]
            )
            ->first();

        if (!$monthHoliday) return 0;
        $holidays = explode(",", $monthHoliday->date);

        return $holidays;
    }


    // 활동지원사의 공휴일유급휴일임금을 구해준다
    public static function calc($data)
    {
        $user_id = User::get_user_id();
        
        // 공휴일목록가져옴
        $holidays = self::get($data);
        $target_ym = date("Y-m-d", strtotime($data['date']));

        $type1 = $data['type1']; // 근무->근무
        $type2 = $data['type2']; // 미근무->미근무
        $type3 = $data['type3']; // 근무->미근무
        $type4 = $data['type4']; // 미근무->근무
        $dates = $data['dates']; // 근무일(배열)
        $provider_key = $data["provider_key"];
        $basic_time = $data['basic_time'];
        $week_selector = $data['week_selector'];
        $pay_hour = $data['pay_hour'];
        
        // 활동지원사목록에 있는 사람인지
        $isProvider = DB::table("helpers")
            ->where("user_id","=", $user_id)
            ->where("target_key", "=", $provider_key)
            ->exists();

        if (!$isProvider) return [ "pay" => 0, "time" => 0 ];

        // 한 달의 주5일, 주6일때 평일구하기
        $week = Custom::week_day_count($target_ym);
        $weekday = $week_selector == 5
            ? $week['yoil'][1] + $week['yoil'][2] + $week['yoil'][3] + $week['yoil'][4] + $week['yoil'][5]
            : $week['yoil'][1] + $week['yoil'][2] + $week['yoil'][3] + $week['yoil'][4] + $week['yoil'][5] + $week['yoil'][6];
        $basicPay = round($basic_time / $weekday); // 평일평균시간

        $public_pay = 0;


        foreach ($holidays as $holiday)
        {
            $schedule = DB::table("helper_confirm_schedules")
                ->join("helpers", "helpers.target_key", "=", "helper_confirm_schedules.worker_id", "left outer")
                ->select("helper_confirm_schedules.*")
                ->where("helper_confirm_schedules.user_id", "=", $user_id)
                ->where("helper_confirm_schedules.worker_id", "=", $provider_key)
                ->where("helper_confirm_schedules.date", "=", $holiday)
                ->whereRaw("helpers.contract_start_date < ?", $holiday)
                ->exists();


            // 해당 공휴일에 근무
            if ($schedule)
            {
                // 근무->근무가 지급이고 해당공휴일에 근무한사람. 즉, 근무->근무 지급
                if ($type1 == 1 && in_array($holiday, $dates)) {
                    $public_pay += $basicPay * $pay_hour;
                }
                // 근무->미근무가 지급이고 해당공휴일에 미근무한 사람. 즉, 근무->미근무 지급
                else if ($type3 == 1 && !in_array($holiday, $dates)) {
                    $public_pay += $basicPay * $pay_hour;
                }
            }

            // 해당 공휴일에 미근무
            else
            {
                // 미근무->미근무가 지급이고 해당공휴일에 미근무한사람. 즉, 미근무->미근무 지급
                if ($type2 == 1 && !in_array($holiday, $dates)) {
                    $public_pay += $basicPay * $pay_hour;
                }
                // 미근무->근무가 지급이고 해당공휴일에 근무한사람. 즉, 미근무->근무 지급
                else if ($type4 == 1 && in_array($holiday, $dates)) {
                    $public_pay += $basicPay * $pay_hour;
                }
            }

        }
        
        return [ "pay" => $public_pay, "time" => $basicPay ];

    }
}
