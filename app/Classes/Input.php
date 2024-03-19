<?php
/**
 * Created by PhpStorm.
 * User: BARAEM_programer2
 * Date: 2021-03-05
 * Time: 오전 10:24
 */

namespace App\Classes;


class Input
{

    public static function pageOn($default, $value)
    {
        // 기본값이 널이면서 파라미터도 널인경우. 즉, 전체보기 같은 경우 전체보기에 on
        if ($default == "" && ($value == "" || !$value)) {
            return "on";
        }

        if (!isset($value)) {
            return "";
        }

        if ($default == $value) {
            return "on";
        }
    }

    public static function select($value, $param)
    {
        if (!$value || $value == "") return "";
        return $value == $param ? "selected" : "";
    }

    // 배열일땐 배열안에 param이 있다면 checked 리턴, 배열이 아니면 arr과 param이 같다면 checked 리턴.
    public static function checked($value, $param)
    {
        if (!$value || $value == "") return "";

        if (is_array($value)) {
            return in_array($param, $value) ? "checked" : "";
        } else {
            return $value == $param ? "checked" : "";
        }
    }
}