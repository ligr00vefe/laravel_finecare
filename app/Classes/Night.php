<?php
/**
 * Created by PhpStorm.
 * User: BARAEM_programer2
 * Date: 2021-02-05
 * Time: 오후 3:35
 */

namespace App\Classes;


class Night
{

    public static function calc($start, $end)
    {
        $detachment = self::night_detachment($start, $end); // 야간에 낀 평일시간 구하기
        $nightTime = self::night_time($start, $end); // 야간시간만 구하기

        return [
            "basic_hour" => $detachment ?? 0,
            "night_hour" => $nightTime ?? 0
        ];

        // 야간시간+야간급여 구하는 방식에서 야간시간만 구하는 방식으로 바뀌면서 merge는 사용안하게 됐다. 2021.02.09
//        return Custom::night_merge($detachment, $nightTime, $basicpay);
    }


    public static function night_merge($basic, $night, $basic_pay)
    {
        // 야간수당 -> 기본시급*0.5 -> ex) 4295
        $night_pay = $basic_pay * 0.5;
        $basic_result = 0;
        $basic_hour = 0;
        $basic_minute = 0;
        $basic_remainder = 0;
        $basic_result = 0;

        $basic_hour = floor($basic / 60); // ex) 435 = 7.25 => 7시간
        $basic_minute = (($basic/60) - $basic_hour) * 60; // ex) 7.25 - 7 = 0.25 * 60 = 15분

        // 분이 45분 이상이라면 시간++ 하고 남는 시간 저장.
        if ($basic_minute >= 45) {
            $basic_hour++;
            $basic_remainder = $basic_minute - 45;

            // 분이 15~44 사이라면 시간에 +0.5하고 남는 시간 저장안함. 이 부분 물어보던가 해야함.
        } else if ($basic_minute >= 15 && $basic_minute < 45) {
            $basic_hour += 0.5;

            // 분이 15분미만이라면 그냥 버리고 남는시간 저장해서 야간 분에 더해주기
        } else if ($basic_minute < 15) {
            $basic_remainder = $basic_minute;
        }

        $basic_result = $basic_hour * $basic_pay;

        $night_hour = floor($night/60);


        $night_minute = ((($night/60) - $night_hour) * 60) + $basic_remainder;

        if ($night_minute >= 45) {
            $night_hour++;
        }
        else if ($night_minute >= 15 && $night_minute < 45) {
            $night_hour += 0.5;

        }

        $night_result = $night_hour * $night_pay;


        return [
            "basic" => $basic_result,
            "night" => $night_result,
            "merge" => $night_result + $basic_result,
            "basic_hour" => $basic_hour,
            "night_hour" => $night_hour
        ];
    }

    // 해당시간 구간에서 22시~6시 사이 시간만 구하기
    public static function night_time($start, $end)
    {
        // 시작날짜시간에서 시작날짜형태로 만들기 (해당날짜의 22시, 06시를 만들기 위함)
        $startday = date("Y-m-d", strtotime($start));
        // 시작날짜시간에서 시작날짜형태의 내일날짜를 만듬. 해당날짜의 다음날 06시를 만들기 위함
        $startdayadd1 = date("Y-m-d", strtotime($start . "+1 days"));
        $endday = date("Y-m-d", strtotime($end));

        $start = strtotime($start);
        $nightstart1 = strtotime($startday . " 22:00:00");
        $nightstart2 = strtotime($startday . " 06:00:00");
        $nightstart3 = strtotime($startdayadd1 . " 06:00:00");

        $end = strtotime($end);
        $nightend1 = strtotime($endday . " 06:00:00");
        $nightend2 = strtotime($endday . " 22:00:00");


        /*
         *  야간 시작시간 경우의 수
         *  1) 시작시간이 당일 22시보다 크고 내일 6시보다 작을때 => 순수 야간 ex) 22:30:00 ~
         *   => 서비스시작시간=야간시작시간
         *  2) 시작시간이 당일 6시보다 적을 때 => 오전부터 시작하는 순수 야간 ex) 02:00:00 ~
         *   => 서비스시작시간=야간시작시간
         *  3) 시작시간이 당일 22시보다 작고 당일 6시보다 클 때 => 평일오후가 낀 야간 21:57:00 ~
         *   => 서비스시작시간과 야간시작시간이 틀림!! 야간시작시간=당일오후10시로 바꿔줘야 함.
         * */

        $start_night_time = $start; // 야간시작시간설정

        // 3) 의 경우. 시작시간을 22시로
        if ($start <= $nightstart1 && $start >= $nightstart2) {
            $start_night_time = $nightstart1; // 시작시간을 22시로
        }


        /*
         * 야간 종료시간 경우의 수
         *  1) 종료시간이 당일 오전 6시보다 작고 00시보다 크다 => 순수 오전야간 ex) ~ 05:59:00
         *   => 서비스종료시간=야간종료시간
         *  2) 종료시간이 당일 10시보다 크고 12시보다 작다 => 순수 오후야간 ex) ~ 11:59:00
         *  => 서비스종료시간=야간종료시간
         *  3) 종료시간이 당일 6시보다 크고 밤10시보다 작다 => 평일 오전이 낀 야간 ex) ~ 08:00:00
         *  => 서비스종료시간과 야간종료시간이 틀림!! 야간종료시간=당일오전6시로 바꿔줘야 함.
         * */

        $end_night_time = $end;

        // 야간종료시간 경우의 수 3번 적용
        if ($end >= $nightend1 && $end <= $nightend2) {
            $end_night_time = $nightend1;
        }

        $startDateObject = date_create(date("Y-m-d H:i:s", $start_night_time));
        $endDateObject = date_create(date("Y-m-d H:i:s", $end_night_time));

        $result = date_diff($endDateObject, $startDateObject);

        // 야간근무 시간을 분으로 다 계산해주기
        $h_to_m = $result->h * 60;
        $s_to_m = $result->s * 0.01;
        $minute = $result->i + $h_to_m + $s_to_m;

        $hour_minute = round($minute/60, 2);
        $hour = floor($hour_minute);
        $minute = ($hour_minute - $hour) * 60;
        $minute = Custom::time($minute);

        $hour = $hour + $minute;

        return $hour;

    }

    public static function time($time)
    {
        if ($time < 15)
        {
            // 0분
            return 0;
        }
        else if ($time >= 15 && $time < 45)
        {
            // 30분 => 0.5시간
            return 0.5;
        }
        else if ($time >= 45)
        {
            // 1시간 => 1시간
            return 1;
        }
    }


    // 야간시간에 끼인 평일시간 구하기
    public static function night_detachment($start, $end)
    {
        $startday = date("Y-m-d", strtotime($start));
        $endday = date("Y-m-d", strtotime($end));

        $start = strtotime($start);
        $nightstart1 = strtotime($startday . " 22:00:00");
        $nightstart2 = strtotime($startday . " 06:00:00");

        $end = strtotime($end);
        $nightend1 = strtotime($endday . " 06:00:00");
        $nightend2 = strtotime($endday . " 10:00:00");


        /*
         * 야간에 평일이 끼인 경우의 수
         * 1) 시작시간이 22시보다 작고 6시보다 클 때 ex) 시작시간 20시
         * 2) 종료시간이 6시보다 크고 22시보다 작을 때 ex) 종료시간 8시.
         *
         * 참고사항: 몇시간마다 활동지원사는 반드시 30분씩 휴식을 해야하므로 무조건 새로운 행이 기록된다.
         *           즉, 한 행에 오전5시에 시작해서  종료시간 오후 23시가 찍힌 데이터는 없을거라는 점. 그 부분은 제외 가능.
         * */


        // 시작시간이 22시보다 작고 6시보다 클 때 평일시간이 끼었다. ex) 시작시간 20:00:00 ~ 종료시간 24:00:00
        if ($start <= $nightstart1 && $start >= $nightstart2) {

            $start = date_create(date("Y-m-d H:i:s", $start));
            $nightstart1 = date_create(date("Y-m-d H:i:s", $nightstart1));
            $result = date_diff($nightstart1,$start);

            // 평일일한 시간을 분으로 다 계산해주기
            $h_to_m = $result->h * 60;
            $s_to_m = $result->s * 0.01;
            $minute = $result->i + $h_to_m + $s_to_m;

            $hour_minute = round($minute/60, 2);
            $hour = floor($hour_minute);
            $minute = ($hour_minute - $hour) * 60;
            $minute = Custom::time($minute);

            $hour = $hour + $minute;


            return $hour;

        }

        // 종료시간이 6시보다 크고 22시보다 작다면 평일시간이 끼었다. ex) 시작시간 4:00:00 ~ 종료시간 8:00:00
        if ($end >= $nightend1 && $end <= $nightend2) {
            $end = date_create(date("Y-m-d H:i:s", $end));
            $nightend1 = date_create(date("Y-m-d H:i:s", $nightend1));

            $result = date_diff($end,$nightend1);

            $h_to_m = $result->h * 60;
            $s_to_m = $result->s * 0.01;
            $minute = $result-> i + $h_to_m + $s_to_m;

            $hour_minute = round($minute/60, 2);
            $hour = floor($hour_minute);
            $minute = ($hour_minute - $hour) * 60;
            $minute = Custom::time($minute);

            $hour = $hour + $minute;

            return $hour;
        }

        return 0;
    }

    // 야간인지 확인하기. 10시보다 크다면 true, 6시보다 작다면 true
    public static function is_night($dates)
    {

        foreach ($dates as $key => $date)
        {

            if ($date == "1970-01-01 00:00:00") continue;

            $date = date("Y-m-d H:i:s", strtotime($date));
            $ymd = date("Y-m-d", strtotime($date));
            $nightStart = date("Y-m-d H:i:s", strtotime($ymd." 22:00:00"));
            $nightEnd = date("Y-m-d H:i:s", strtotime($ymd." 06:00:00"));

            if ($nightStart <= $date) {
                return true;
            }

            if ($nightEnd > $date) {
                return true;
            }
        }

        return false;

    }
}