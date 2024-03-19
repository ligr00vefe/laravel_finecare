<?php

use Illuminate\Support\Facades\DB;

function rsno_to_birth($rsno)
{
    return substr($rsno,0,2) . "-" . substr($rsno, 2, 2) . "-" . substr($rsno, 4, 2);
}

// 숫자만 추출 (소수점 버림)
function extractNumber($str)
{
    if (!$str) return 0;
    if ($str == 0 || $str == "0") return 0;

    if (strpos($str, ".") !== false) {
        $str = explode(".", $str)[0];
    }

    return preg_replace("/[^0-9]*/s", "", $str);
}

// 숫자 혹은 숫자가 포함된 문자를 number_format으로 만든다 (소수점은 지워버린다)
function num($num) : string
{
    if (!is_numeric($num)) {
        if (strpos($num, ".") !== false) {
            $num = explode(".", $num)[0];
        }

        $num = preg_replace("/[^0-9]*/s", "", $num);
    }

    $num =  (int) $num;
    return number_format($num);
}


function nullCheck($var=null, $msg="적용")
{
    if (!$var) return "";

    return $msg;
}


function applyTypeConvertKor($var=null)
{
    if (!$var || $var == "") return "선택안함";

    if ($var=="all") return "전체적용";
    else if ($var == "basic60") return "기본시간 60시간 이상 적용";
    else if ($var == "basic65") return "기본시간 65시간 이상 적용";
}


function applyTypeWorkDay($var=null)
{
    if (!$var || $var == "") return "선택안함";

    if ($var == "5") return "주 5일";
    else if ($var == "6") return "주 6일";
}


// 콤마 제거해서 리턴하고 배열이라면 배열안에 숫자 다 컴마 제거하고 더해서 리턴
function removeComma($data) : int
{
    if (!is_array($data))
    {
        return (int) str_replace ( "," , '' , $data);
    }
    else
    {
        $integer = 0;
        foreach ($data as $i)
        {
            $integer += (int) str_replace ( "," , '' , $i);
        }

        return $integer;
    }
}


function return_array_payment_total()
{
    return [
        "nation_day_count" => 0,
        "nation_confirm_payment" => 0,
        "nation_add_payment" => 0,
        "nation_total_time" => 0,
        "nation_holiday_time" => 0,
        "nation_night_time" => 0,
        "city_day_count" => 0,
        "city_confirm_payment" => 0,
        "city_add_payment" => 0,
        "city_total_time" => 0,
        "city_holiday_time" => 0,
        "city_night_time" => 0,
        "voucher_total_day_count" => 0,
        "voucher_total_confirm_payment" => 0,
        "voucher_total_confirm_payment_add" => 0,
        "voucher_total_time" => 0,
        "voucher_total_time_holiday" => 0,
        "voucher_total_time_night" => 0,
        "voucher_detach_payment_basic" => 0,
        "voucher_detach_payment_holiday" => 0,
        "voucher_detach_payment_night" => 0,
        "voucher_detach_payment_difference" => 0,
        "voucher_return_nation_day_count" => 0,
        "voucher_return_nation_pay" => 0,
        "voucher_return_nation_time" => 0,
        "voucher_return_city_day_count" => 0,
        "voucher_return_city_pay" => 0,
        "voucher_return_city_time" => 0,
        "voucher_return_total_day_count" => 0,
        "voucher_return_total_pay" => 0,
        "voucher_return_total_time" => 0,
        "voucherPaymentFromPaymentVouchers" => 0,
        "standard_basic_time" => 0,
        "standard_basic_payment" => 0,
        "standard_over_time" => 0,
        "standard_over_payment" => 0,
        "standard_holiday_time" => 0,
        "standard_holiday_payment" => 0,
        "standard_night_time" => 0,
        "standard_night_payment" => 0,
        "standard_weekly_time" => 0,
        "standard_weekly_payment" => 0,
        "standard_yearly_time" => 0,
        "standard_yearly_payment" => 0,
        "standard_workers_day_time" => 0,
        "standard_workers_day_payment" => 0,
        "standard_public_day_time" => 0,
        "standard_public_day_payment" => 0,
        "StandardPaymentFromStandardTable" => 0,
        "voucher_sub_standard_payment" => 0,
        "voucherPaymentFromStandardTable" => 0,
        "tax_nation_pension" => 0,
        "tax_health" => 0,
        "tax_employ" => 0,
        "tax_gabgeunse" => 0,
        "tax_joominse" => 0,
        "tax_total" => 0,
        "tax_sub_payment" => 0,
        "bank_name" => 0,
        "bank_number" => 0,
        "company_income" => 0,
        "tax_company_nation" => 0,
        "tax_company_health" => 0,
        "tax_company_employ" => 0,
        "tax_company_industry" => 0,
        "tax_company_retirement" => 0,
        "tax_company_return_confirm" => 0,
        "tax_company_tax_total" => 0,
        "company_payment_result" => 0,

    ];
}


// 한글, 영문만 추출하기
function regexName($str)
{
    $pattern = '/([\xEA-\xED][\x80-\xBF]{2}|[a-zA-Z])+/';
    preg_match_all($pattern, $str, $match);
    return implode("", $match[0]);
}

// 미가입회원인지 체크하기. 0: 가입회원, 1:미가입
function regCheck($num)
{
    if ($num != 0 && $num != 1) return "미가입";
    return $num == 1 ? "미가입" : "";
}



// 바우처 지급합계 구하기. (바우처상지급합계선택, 휴일수당고정값일시값, 급여계산저장된값, 시간당인건비단가)
function calcVoucherPayment($voucher_pay_total, $voucher_holiday_pay_fixing, $list, $pay_per_hour)
{
    if ($voucher_pay_total == 1) {

        $voucher_payment = ((
                    $list->voucher_total_time
                    - $list->voucher_total_time_holiday
                    - $list->voucher_total_time_night
                ) * 14020 + $list->voucher_etc_charge_total_pay) * (10310/$pay_per_hour)
            +
            (
                + ($list->voucher_total_time_holiday + $list->voucher_total_time_night) * $voucher_holiday_pay_fixing
            );

    } else {

        $voucher_payment = ((
                    $list->voucher_total_time
                    - $list->voucher_total_time_holiday
                    - $list->voucher_total_time_night
                ) * 14020 + $list->voucher_etc_charge_total_pay)
            +
            (($list->voucher_total_time_holiday + $list->voucher_total_time_night) * $pay_per_hour) * 1.5;

    }

    return $voucher_payment;
}

// 근로기준법 지급합계 구하기.
function calcStandardPayment($list, $request)
{
    $pay_hour = $request->input("pay_hour");
    $pay_over_time = $request->input("pay_over_time");
    $pay_holiday = $request->input("pay_holiday");
    $pay_holiday_over_time = $request->input("pay_holiday_over_time");
    $pay_night = $request->input("pay_night");
    $pay_annual = $request->input("pay_annual");
    $pay_one_year_less_annual = $request->input("pay_one_year_less_annual");
    $pay_public_holiday = $request->input("pay_public_holiday");
    $pay_workers_day = $request->input("pay_workers_day");

    $week_pay_apply_check = $request->input("week_pay_apply_check") ?? null;
    $week_pay_apply_type = $request->input("week_pay_apply_type") ?? null;
    $year_pay_apply_check = $request->input("year_pay_apply_check") ?? null;
    $year_pay_apply_type = $request->input("year_pay_apply_type") ?? null;

    $basicPayment = $list->standard_basic_time * $pay_hour;
    $overtimePayment = $list->standard_over_time * ($pay_hour * 1.5) * ($pay_over_time/100);
    $holidayPayment = ($list->standard_holiday_time * ($pay_hour * 1.5)) * ($pay_holiday/100);

    $nightPayment = $list->standard_night_time * ($pay_hour * 1.5) * ($pay_night/100);

    $weekPayment = 0;

    // 주휴수당 계산하기
    if ($week_pay_apply_check)
    {
        if ($week_pay_apply_type == "all") {
            $weekPayment = $list->standard_weekly_time * $pay_hour;
        } else if ($week_pay_apply_type == "basic60" && $list->standard_weekly_time >= 60) {
            $weekPayment = $list->standard_weekly_time * $pay_hour;
        } else if ($week_pay_apply_type == "basic65" && $list->standard_weekly_time >= 65) {
            $weekPayment = $list->standard_weekly_time * $pay_hour;
        }
    }

    $yearPayment = 0;

    // 연차수당 계산하기
    if ($year_pay_apply_check)
    {
        if ($year_pay_apply_type == "all") {
            $yearPayment = $list->standard_yearly_time * $pay_hour * ($pay_annual/100);
        } else if ($year_pay_apply_type == "basic60" && $list->standard_yearly_time >= 60) {
            $yearPayment = $list->standard_yearly_time * $pay_hour * ($pay_annual/100);
        } else if ($year_pay_apply_type == "basic65" && $list->standard_yearly_time >= 65) {
            $yearPayment = $list->standard_yearly_time * $pay_hour * ($pay_annual/100);
        }
    }

    $workersDayPayment = ($list->standard_workers_day_time * ($pay_hour * 1.5)) * ($pay_workers_day/100);
    $publicDayPayment = ($list->standard_public_day_time * $pay_hour) * ($pay_public_holiday/100);

    $standardPayment = $basicPayment + $overtimePayment + $holidayPayment + $nightPayment + $weekPayment + $yearPayment + $workersDayPayment + $publicDayPayment;

    return [
        "standardPayment" => $standardPayment,
        "basicPayment" => $basicPayment,
        "overtimePayment" => $overtimePayment,
        "holidayPayment" => $holidayPayment,
        "nightPayment" => $nightPayment,
        "weekPayment" => $weekPayment,
        "yearPayment" => $yearPayment,
        "workersDayPayment" => $workersDayPayment,
        "publicDayPayment" => $publicDayPayment
    ];

}


function checked($name, $value, $return="checked")
{
    if (!$name || $name == "" || !$value || $value == "") return "";
    if ($name == $value) return $return;
}


// get 페이지네이션
if (! function_exists("pagination")) {

    function pagination($write_pages, $total_page, $add="")
    {

        $qstr = $_SERVER['QUERY_STRING'];
        $cur_page = $_REQUEST['page'] ?? 1;

        if ($qstr != "") {
            $qstr = "";
            foreach ($_REQUEST as $key=>$val) {
                if ($key == "page") continue;
                if ($qstr != "") $qstr .= "&";
                $qstr .= "{$key}={$val}";
            }
        }

        $pageAddress = explode("?", $_SERVER['REQUEST_URI'])[0];

        $url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $pageAddress . "?" . $qstr;

        $str = '';
        if ($cur_page > 1) {
            $str .= "<a href='{$url}&page=1{$add}' class='pg_page pg_start'></a>".PHP_EOL;
        }

        $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;

        $end_page = $start_page + $write_pages - 1;

        if ($end_page >= $total_page) $end_page = $total_page;

        if ($cur_page > 1) {
            $cur_page_sub = $cur_page-1;
            $str .= "<a href='{$url}&page={$cur_page_sub}{$add}' class='pg_page pg_prev'></a>".PHP_EOL;
        }

        if ($total_page > 1) {
            for ($k=$start_page;$k<=$end_page;$k++) {
                if ($cur_page != $k)
                    $str .= '<a href="'.$url.'&page='.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only"></span></a>'.PHP_EOL;
                else
                    $str .= '<span class="sound_only"></span><strong class="pg_current">'.$k.'</strong><span class="sound_only"></span>'.PHP_EOL;
            }
        }

        if ($total_page > $cur_page) $str .= '<a href="'.$url.'&page='.($cur_page+1).$add.'" class="pg_page pg_next"></a>'.PHP_EOL;

        if ($cur_page < $total_page) {
            $str .= '<a href="'.$url.'&page='.$total_page.$add.'" class="pg_page pg_end"></a>'.PHP_EOL;
        }

        if ($str)
            return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
        else
            return "";
    }

}




// get 페이지네이션
if (! function_exists("pagination2")) {

    function pagination2($write_pages, $total_page, $add="")
    {

        $qstr = $_SERVER['QUERY_STRING'];
        $cur_page = $_REQUEST['page'] ?? 1;

        if ($qstr != "") {
            $qstr = "";
            foreach ($_REQUEST as $key=>$val) {
                if ($key == "page") continue;
                if ($qstr != "") $qstr .= "&";
                if (is_array($val)) {
                    foreach ($val as $idx => $_val) {
                        if ($qstr != "") $qstr .= "&";
                        $arrayTypeStr = urlencode("[]");
                        $qstr .= "{$key}{$arrayTypeStr}={$_val}";
                    }
                } else {
                    $qstr .= "{$key}={$val}";
                }
            }
        }

        $pageAddress = explode("?", $_SERVER['REQUEST_URI'])[0];

        $url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $pageAddress . "?" . $qstr;

        $str = '';
        if ($cur_page > 1) {
            $str .= "<a href='{$url}&page=1{$add}' class='pg_page pg_start'></a>".PHP_EOL;
        }

        $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;

        $end_page = $start_page + $write_pages - 1;

        if ($end_page >= $total_page) $end_page = $total_page;

        if ($cur_page > 1) {
            $cur_page_sub = $cur_page-1;
            $str .= "<a href='{$url}&page={$cur_page_sub}{$add}' class='pg_page pg_prev'></a>".PHP_EOL;
        }

        if ($total_page > 1) {
            for ($k=$start_page;$k<=$end_page;$k++) {
                if ($cur_page != $k)
                    $str .= '<a href="'.$url.'&page='.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only"></span></a>'.PHP_EOL;
                else
                    $str .= '<span class="sound_only"></span><strong class="pg_current">'.$k.'</strong><span class="sound_only"></span>'.PHP_EOL;
            }
        }

        if ($total_page > $cur_page) $str .= '<a href="'.$url.'&page='.($cur_page+1).$add.'" class="pg_page pg_next"></a>'.PHP_EOL;

        if ($cur_page < $total_page) {
            $str .= '<a href="'.$url.'&page='.$total_page.$add.'" class="pg_page pg_end"></a>'.PHP_EOL;
        }

        if ($str)
            return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
        else
            return "";
    }

}



function decimalFormat($num) {
    return number_format($num);
}


function pp (...$arr) {
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function convert_birth ($birth) {
    return $birth;
}

function print_business_license($num) {
    return substr($num, 0, 3) ."-". substr($num, 3, 2) ."-". substr($num, 5);
}

function regexp($type, $str) {
    $pattern = "";

    switch ($type)
    {
        case "한글":
            $pattern = '/([\xEA-\xED][\x80-\xBF]{2})+/'; // ㄱ ㄴ ㄷ ㄹ 이런건 안됨
            break;
        case "한글영어":
            $pattern = '/([\xEA-\xED][\x80-\xBF]{2}|[a-zA-Z])+/';
            break;
        case "숫자":
            $pattern = '/([0-9])+/';
            break;
    }

    preg_match_all($pattern, $str, $match);
    return implode('', $match[0]);

}

function convert_log_category($str) {
    switch ($str) {
        case "etc":
            return "특이사항";
            break;
    }
}

function convert_log_way($str) {
    switch ($str)
    {
        case "tel":
            return "유선";
            break;
    }
}


function last_created_at($table)
{
    $user_id = \App\Models\User::get_user_id();

    return DB::table($table)
        ->select("created_at")
        ->where("user_id","=", $user_id)
        ->orderByDesc("created_at")
        ->first()->created_at ?? "업로드 내역 없음";
}


function user_get_expiration_date()
{
    $user_id = \App\Models\User::get_user_id();

    $user_goods = DB::table("user_goods_lists")
        ->where("user_id", "=", $user_id)
        ->orderByDesc("id")
        ->first() ?? "";

    return $user_goods->end_date ?? "";
}


include("helper.add.php");
