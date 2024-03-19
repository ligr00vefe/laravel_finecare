<?php
/**
 * Created by PhpStorm.
 * User: BARAEM_programer2
 * Date: 2021-02-05
 * Time: 오후 4:09
 */

namespace App\Classes;


use App\Models\User;
use Illuminate\Support\Facades\DB;

class Holiday
{

    public static function getPublicHoliday($date=null)
    {
        $date = date("Y-m-d", strtotime($date));

        $holidays = DB::table("holiday_lists")
            ->selectRaw("full_date, ANY_VALUE(comment) as comment")
            ->whereRaw("(full_date > LAST_DAY(? - interval 1 month)
            AND full_date <= LAST_DAY(?) )", [ $date, $date ])
            ->groupBy("full_date")
            ->get();

        $lists = [];

        foreach ($holidays as $holiday)
        {
            $lists[$holiday->full_date] = $holiday->comment;
        }

        return $lists;
    }


    /**
     * 해당 일자가 휴일이 맞는지 체크
     * @param string $start = date format
     * @param int $worker_id = number format
     * @param int $type = number format, 1=공휴일적용, 2=적용안함.
     *
     * @return boolean
     * */
    public static function isHoliday($start, $worker_id, $type)
    {
        $user_id = User::get_user_id();
        $start = date("Y-m-d", strtotime($start));

        // 활동지원사의 스케줄에서 주휴일로 표시되어 있다면?
        $schedule = DB::table("helper_confirm_schedules")
            ->where("user_id", "=", $user_id)
            ->where("worker_id", "=", $worker_id)
            ->where("date", "=", $start)
            ->exists();

        if ($schedule) return true;

        $jan = [
            "2021-01-03",
            "2021-01-10",
            "2021-01-17",
            "2021-01-24",
            "2021-01-31",
        ];

        if (in_array($start, $jan)) return true;

        // 관공서공휴일 휴일적용체크되어있다면
        if ($type == 1) {
            $holiday = DB::table("holiday_lists")
                ->where("full_date", "=", $start)
                ->exists();

            if ($holiday) return true;
        }


        return false;
    }

    public static function calc($start, $end)
    {
        $startday = date("Y-m-d", strtotime($start));
        $endday = date("Y-m-d", strtotime($end));

        $start = strtotime($start);
        $nightstart0 = strtotime($startday . " 00:00:00");
        $nightstart1 = strtotime($startday . " 22:00:00");
        $nightstart2 = strtotime($startday . " 06:00:00");

        $end = strtotime($end);
        $nightend0 = strtotime($endday . " 00:00:00");
        $nightend1 = strtotime($endday . " 06:00:00");
        $nightend2 = strtotime($endday . " 22:00:00");



        if ($start > $nightstart1) { // 시작시간이 10시보다 크고
            if ($end < $nightend1) { // 종료시간이 종료일자의 6시보다 작다면 전체야간이므로 리턴 0
                return 0;
            }
            $start = $nightend1; // 종료시간이 종료일자의 6시보다 크기때문에 시작시간을 종료일자 6시로 ex) 11시시작 8시종료 => 6~8 2시간 휴일근무
        }

        else if ($start < $nightstart2) { // 시작시간이 6시이전이고
            if ($end < $nightend1) { // 종료시간도 6시이전이라면 전체 야간이므로 리턴 0
                return 0;
            }
            $start = $nightstart2;
        }


        if ($end < $nightend1) { // 종료가 종료날짜의 6시보다 작고
            if ($start < $nightstart1) { // 시작이 시작날짜의 22시보다 작다면
                $end = $nightstart1; // 종료를 시작날짜의 22시로 => 시작시간~22시 => 휴일근무시간
            }
        }
        else if ($end > $nightend2) { // 종료시간이 22시보다 크고
            if ($start > $nightstart1) { // 시작시간도 22시보다 크다면 전체야간이므로 리턴 0
                return 0;
            }
            $end = $nightend2; // 22시보다 크다면 22시로 맞춰준다. => 24시가 넘은 시간은 if에서 걸러줬다
        }


        $startDateObject = date_create(date("Y-m-d H:i:s", $start));
        $endDateObject = date_create(date("Y-m-d H:i:s", $end));


        $result = date_diff($endDateObject, $startDateObject);

        // 야간근무 시간을 분으로 다 계산해주기
        $h_to_m = $result->h * 60;
        $s_to_m = $result->s * 0.01;
        $minute = $result->i + $h_to_m + $s_to_m;

        // 시간형식으로 바꾸고 7.4 -> 시간만남기고 7 -> 시간형식-시간 0.4 -> 0.4*60 = 24 = 30분 -> 30/60 = 0.5 -> 7.5시간. 최종 7.5리턴.
        $hour_minute = round($minute/60, 2);
        $hour = floor($hour_minute);
        $minute = ($hour_minute - $hour) * 60;
        $minute = Custom::time($minute);

        $hour = $hour + $minute;


        return $hour;

    }
}
