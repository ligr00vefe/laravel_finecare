<?php
/**
 * Created by PhpStorm.
 * User: BARAEM_programer2
 * Date: 2021-02-09
 * Time: 오후 2:26
 */

namespace App\Classes;


class Basic
{
    public static function calc($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);

        $startDateObject = date_create(date("Y-m-d H:i:s", $start));
        $endDateObject = date_create(date("Y-m-d H:i:s", $end));

        $result = date_diff($endDateObject, $startDateObject);
        

        // 시간을 분으로 다 계산해주기
        $h_to_m = $result->h * 60;
        $s_to_m = $result->s * 0.01;
        $minute = $result->i + $h_to_m + $s_to_m;
        
        // 한 줄이 8시간이 넘으면 8시간 고정
        if ($minute > 480) $minute = 480;

        // 시간형식으로 바꾸고 7.4 -> 시간만남기고 7 -> 시간형식-시간 0.4 -> 0.4*60 = 24 = 30분 -> 30/60 = 0.5 -> 7.5시간. 최종 7.5리턴.
        $hour_minute = round($minute/60, 2); // ex) 7.4
        $hour = floor($hour_minute); // ex) 7

        $minute = ($hour_minute - $hour) * 60; // ex) 0.4 * 60 = 24
        $minute = Custom::time($minute); // ex) 24는 15~45분 사이이므로 30분 => 30*60 => 0.5

        $hour = $hour + $minute; // ex ) 7.5

        return $hour;
    }
}