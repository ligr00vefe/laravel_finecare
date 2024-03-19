<?php
/**
 * Created by PhpStorm.
 * User: BARAEM_programer2
 * Date: 2021-02-02
 * Time: 오후 6:10
 */

namespace App\Classes;



use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Custom
{

    // 콤마 제거해서 리턴하고 배열이라면 배열안에 숫자 다 컴마 제거하고 더해서 리턴
    public static function removeCommaWithInteger($data)
    {
        if (!is_array($data))
        {
            return (int) Number_format(str_replace ( "," , '' , $data));
        }
        else
        {
            $integer = 0;
            foreach ($data as $i)
            {
                $integer += (int) str_replace ( "," , '' , $i);
            }

            return number_format($integer);
        }
    }

    public static function dateNullExcept($date)
    {
        $return = "";
        if ($date) {
            if ($date != "1970-01-01 00:00:00" && $date != "1970-01-01") {
                $return = date("Y-m-d", strtotime($date));
            }
        }

        return $return;
    }

    public static function userid_to_companyname($id) {
        return User::find($id)->company_name;
    }

    public static function filesizeKB($path)
    {
        return round(filesize($path) / 1000);
    }


    public static function provider_key_to_resign_date($key)
    {
        return DB::table("workers")
                ->where("target_id", "=", $key)
                ->first()->resign_date ?? "";
    }

    // 지원사 키로 조인데이트 리턴
    public static function provider_key_to_join_date($key)
    {
        return DB::table("workers")
            ->where("target_id", "=", $key)
            ->first()->join_date ?? "제공자정보없음";
    }


    // 2021-xx-xx, 2021-xx-xx <--같은 날짜형태 스트링을 콤마로 나누고 중복날짜를 빼서 몇개인지 리턴
    public static function DATE_COUNT_CONCAT_FORMAT($date)
    {
        return count(array_unique(explode(",", $date)));
    }


    // 계약시작일, 계약종료일 계산해서 현재 상태 리턴한다
    public static function contact_status($start, $end=null)
    {
        if (!$end) return "이용중";
        if ($end == "1970-01-01 00:00:00") return "이용중";
        if ($start == "1970-01-01 00:00:00") return "";
        if (strtotime($end) <= strtotime(now())) return "종료";
        if(strtotime($start) >= strtotime(now())) return "이용 전";
        return "이용중";
    }

    // 주민번호 뒷자리 첫 숫자로 성별 리턴
    public static function rsno_to_gender($rsno)
    {
        $gender = substr($rsno, 7, 1);

        switch ($gender)
        {
            case "1" :
            case "3" :
                return "남자";
                break;
            case "2" :
            case "4" :
                return "여자";
                break;
            default:
                break;
        }
    }

    // 주민번호 911128-1 형태를 91-11-28 로 리턴
    public static function rsno_to_birth($rsno)
    {
        return substr($rsno,0,2) . "-" . substr($rsno, 2, 2) . "-" . substr($rsno, 4, 2);
    }

    // 시간:분 형태 여러개를 받아서 다 더해서 리턴
    public static function timeAdd(...$args)
    {


        $hour = 0;
        $minute = 0;

        foreach ($args as $arg)
        {
            $hour1 = (int) substr($arg, 0, 2);

            if ($hour1 == 0 || !is_numeric($hour1)) {
                continue;
            }

            $minute1 = substr($arg, 3, 2);

            $hour += $hour1;
            $minute += $minute1;
        }

        if ($minute >= 60) {
            $minute_to_hour = round($minute / 60);

            $hour += $minute_to_hour;
            $minute = round($minute % 60);
        }

        if ($hour < 10) {
            $hour = "0".$hour;
        }

        if ($minute < 10) {
            $minute = "0".$minute;
        }

        return $hour.":".$minute;

    }

    // 홍길동920101 -> 홍길동(91-01-01) 형태로 변경
    public static function key_format_divide($target_key)
    {
        $birth = preg_replace("/[^0-9]*/s", "", $target_key);
        $name = str_replace($birth, "", $target_key);

        $birth = substr($birth, 0, 2) . "-" . substr($birth, 2, 2) . "-" . substr($birth, 4);
        return $name . "({$birth})";
    }

    // 67:02 같은 시간 포맷을 만들어 리턴함. 단, NaN이 넘어온다면 00:00으로 리턴
    public static function time_format($time)
    {
        if (substr($time, 0, 3) == "NaN") {
            return "00:00";
        }

        return substr($time, 0, 5);
    }


    // 나이구하기. $type == 1 이면 만 나이, 2면 한국 나이
    public static function getAge($date, $type=1)
    {
        if ($type == 1)
        {
            $birth_time   = strtotime($date);
            $now          = date('Ymd');
            $birthday     = date('Ymd' , $birth_time);
            $age           = floor(($now - $birthday) / 10000);
        }
        else
        {
            $birth_time   = strtotime($date);
            $now          = date('Y');
            $birthday     = date('Y' , $birth_time);
            $age           = $now - $birthday + 1 ;
        }

        return $age;
    }

    public static function inOfficeCheck($provider_key)
    {
        $is = Worker::where("target_id","=",$provider_key)->first();
        if ($is) return "";
        if (!isset($is->resign_date) || $is->resign_date == "") return "재직 중";
        $now = strtotime(date("Y-m-d"));
        $resign = strtotime($is->resign_date);
        if ($now >= $resign) return "퇴사";
    }

    public static function calcLongevity($start, $end="")
    {
        if ($end == "정보없음") {
            $end = now();
        }

        if ($end == "") {
            $end = now();
        }

        $start = date("Y-m-d", strtotime($start));

        if ($start == "1970-01-01") {
            return "";
        }

        $date1 = new \DateTime($start);
        $date2 = new \DateTime($end);
        $interval = $date1->diff($date2);
        return $interval->format("%y년 %m개월");
    }

    public static function regexOnlyStr($str="")
    {
        if ($str == "") return "";
        return preg_replace("/[0-9]*/s", "", $str);
    }

    public static function regexOnlyNumber($str="")
    {
        if ($str == "") return "";
        return preg_replace("/[^0-9]*/s", "", $str);
    }


    // 두 배열의 중복키가 있을 경우 그만큼 $a의 렝쓰를 마이너스 해준다.
    // 국비, 시도비 근무일수 같은 날 있으면 국비에만 몰아줘야 한다. 즉, $a와 $b 배열의 중복키만큼 $a의 렝쓰는 뺀다
    public static function count_duplicate_keys_in_array($a, $b)
    {
        $count = count($a);
        foreach ($a as $key => $val)
        {
            if (array_key_exists($key, $b)) {
                $count--;
            }
        }

        return $count;
    }


    public static function array_merge_with_duplicate_keys(...$args)
    {
        return count(array_replace_recursive($args[0], $args[1]));
    }

    public static function setTotal()
    {
        return [

            "VOUCHER_NATION_DAY" => 0,
            "VOUCHER_NATION_PAYMENT" => 0,
            "VOUCHER_NATION_PAYMENT_EXTRA" => 0,
            "VOUCHER_NATION_TIME" => 0,
            "VOUCHER_NATION_TIME_HOLIDAY" => 0,
            "VOUCHER_NATION_TIME_NIGHT" => 0,

            "VOUCHER_CITY_DAY" => 0,
            "VOUCHER_CITY_PAYMENT" => 0,
            "VOUCHER_CITY_PAYMENT_EXTRA" => 0,
            "VOUCHER_CITY_TIME" => 0,
            "VOUCHER_CITY_TIME_HOLIDAY" => 0,
            "VOUCHER_CITY_TIME_NIGHT" => 0,

            "VOUCHER_DAY" => 0,
            "VOUCHER_PAYMENT" => 0,
            "VOUCHER_PAYMENT_EXTRA" => 0,
            "VOUCHER_TIME" => 0,
            "VOUCHER_TIME_HOLIDAY" => 0,
            "VOUCHER_TIME_NIGHT" => 0,

            "VOUCHER_DETACH_PAYMENT_BASIC" => 0,
            "VOUCHER_DETACH_PAYMENT_HOLIDAY" => 0,
            "VOUCHER_DETACH_PAYMENT_NIGHT" => 0,
            "VOUCHER_DETACH_PAYMENT_DIFF" => 0,

            "RETURN_NATION_DAY" => 0,
            "RETURN_NATION_PAYMENT" => 0,
            "RETURN_NATION_TIME" => 0,

            "RETURN_CITY_DAY" => 0,
            "RETURN_CITY_PAYMENT" => 0,
            "RETURN_CITY_TIME" => 0,

            "RETURN_DAY" => 0,
            "RETURN_PAYMENT" => 0,
            "RETURN_TIME" => 0,

            "VOUCHER_PAY_TOTAL" => 0,

            "STANDARD_BASIC_TIME" => 0,
            "STANDARD_BASIC_PAYMENT" => 0,
            "STANDARD_OVER_TIME" => 0,
            "STANDARD_OVER_PAYMENT" => 0,
            "STANDARD_HOLIDAY_TIME" => 0,
            "STANDARD_HOLIDAY_PAYMENT" => 0,
            "STANDARD_NIGHT_TIME" => 0,
            "STANDARD_NIGHT_PAYMENT" => 0,
            "STANDARD_WEEKLY_TIME" => 0,
            "STANDARD_WEEKLY_PAYMENT" => 0,

            "STANDARD_YEARLY_TIME" => 0,
            "STANDARD_YEARLY_PAYMENT" => 0,

            "STANDARD_WORKERS_DAY_TIME" => 0,
            "STANDARD_WORKERS_DAY_PAYMENT" => 0,

            "PUBLIC_HOLIDAY_TIME" => 0,
            "PUBLIC_HOLIDAY_PAY" => 0,

            "STANDARD_PAYMENT" => 0,
            "PAYMENT_DIFF" => 0,

            "TAX_WORKER_NATION" => 0,
            "TAX_WORKER_HEALTH" => 0,
            "TAX_WORKER_EMPLOY" => 0,
            "TAX_WORKER_GABGEUNSE" => 0,
            "TAX_WORKER_RESIDENT" => 0,

            "TAX_WORKER_TOTAL" => 0,
            "PAYMENT_TAX_SUB" => 0,

            "COMPANY_INCOME" => 0,
            "TAX_COMPANY_NATION" => 0,
            "TAX_COMPANY_HEALTH" => 0,
            "TAX_COMPANY_EMPLOY" => 0,
            "TAX_COMPANY_INDUSTRY" => 0,

            "RETIREMENT" => 0,
            "CONFIRM_RETURN" => 0,
            "TAX_COMPANY_TOTAL" => 0,
            "PAYMENT_COMPANY_TAX_SUB" => 0,

        ];

    }

    public static function regCheck($start, $end)
    {
        $now = date("Y-m-d");
        $start = strtotime($start);
        $end = strtotime($end);
        $now = strtotime($now);
        if ($start <= $now && $end >= $now) {
            return true;
        }

        return false;
    }


    // 한달의 일월화수목금토가 몇개나 있는지 배열로 리턴
    public static function week_day_count($date)
    {
        $date = date("Y-m-d", strtotime($date));
        $day_count = date('t', strtotime($date));
        $ym = date("Y-m", strtotime($date));

        // 일월화수목금토 개수
        $arr = [ 0, 0, 0, 0, 0, 0, 0 ];

        foreach (range(1, $day_count) as $day)
        {
            $ymd = date("Y-m-d", strtotime($ym . "-{$day}"));
            $yoil = date('w', strtotime($ymd));
            $arr[$yoil]++;
        }

        // 요일 수, 당월에 마지막일(당월의 날짜수)
        return [ "yoil" => $arr, "day_count" => $day_count ];
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

}
