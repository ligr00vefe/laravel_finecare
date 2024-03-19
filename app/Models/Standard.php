<?php

namespace App\Models;

use App\Classes\Basic;
use App\Classes\Custom;
use App\Classes\Holiday;
use App\Classes\Night;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    public static function total($voucher, $key, $request)
    {
        $basic_pay = $request->input("pay_hour"); // 통상시급
        $basic_overtime_pay = $basic_pay * 1.5; // 연장시급
        $night_pay = $basic_pay * 0.5; // 야간시급. 0.5 주의 (아무리봐도 0.5 이상함. 0.5가 맞다 기본급시간에 야간시간이 포함됨 21.02.19)
        $holiday_pay = $basic_pay * 1.5; // 휴일시급
        $holiday_overtime_pay = $basic_pay * 0.5; // 휴일연장시급

        $pay_over_time_percentage = $request->input("pay_over_time") / 100; // 연장수당비율
        $pay_holiday_percentage = $request->input("pay_holiday") / 100; // 휴일수당비율
        $pay_holiday_overtime_percentage = $request->input("pay_holiday_over_time") / 100; // 휴일연장수당비율
        $pay_night_percentage = $request->input("pay_night") / 100; // 야간수당비율


//        $voucher['Standard']['TIME_NIGHT'] = $voucher['Voucher']['CITY']['TIME_NIGHT'] + $voucher['Voucher']['COUNTRY']['TIME_NIGHT'];
//        $voucher['Standard']['TIME_HOLIDAY'] = $voucher['Voucher']['CITY']['TIME_HOLIDAY'] + $voucher['Voucher']['COUNTRY']['TIME_HOLIDAY'];

//        $voucher['Standard']['TIME_NIGHT'] = $voucher['Voucher']['_STANDARD']['TIME_NIGHT'];
        $voucher['Standard']['TIME_HOLIDAY'] = $voucher['Voucher']['_STANDARD']['TIME_HOLIDAY'];


        foreach ($voucher['Standard']['BASIC'] as $key => $val)
        {
            if ($val['TIME_BASIC'] > 8) {
                $voucher['Standard']['TIME_OVERTIME'] += $val['TIME_BASIC'] - 8;
            }
        }

        // 법정기본시간 = 바우처승인합계총시간 + 기타청구총시간 - 법정휴일수당시간. 최대 174시간.
        // 21.03.25 연장시간 포함되고 있어서 연장시간만큼 마이너스 해주기
        $voucher['Standard']['TIME_BASIC'] =
            ($voucher['Voucher']['COUNTRY']['TIME_TOTAL'] + $voucher['Voucher']['CITY']['TIME_TOTAL'] - $voucher['Standard']['TIME_HOLIDAY']) <= 174
                ? ($voucher['Voucher']['COUNTRY']['TIME_TOTAL'] + $voucher['Voucher']['CITY']['TIME_TOTAL'] - $voucher['Standard']['TIME_HOLIDAY']) - $voucher['Standard']['TIME_OVERTIME']
                : 174;

        $voucher['Standard']['PAY_BASIC'] = $voucher['Standard']['TIME_BASIC'] * $basic_pay;

        $voucher['Standard']['PAY_OVERTIME'] = ($voucher['Standard']['TIME_OVERTIME'] * $basic_overtime_pay) * $pay_over_time_percentage;

        if ($voucher['Standard']['TIME_BASIC'] == 174) {
            $voucher['Standard']['TIME_NIGHT'] = 0;
        } else {
            // 법정야간시간 = 법정기본시간(야간포함) - 바우처기본시간(야간비포함)
            $voucher['Standard']['TIME_NIGHT'] =
                ($voucher['Standard']['TIME_BASIC'] - $voucher['Voucher']['COUNTRY']['TIME_NORMAL'] + $voucher['Voucher']['CITY']['TIME_NORMAL']) >= 0
                    ? ($voucher['Standard']['TIME_BASIC'] - $voucher['Voucher']['COUNTRY']['TIME_NORMAL'] + $voucher['Voucher']['CITY']['TIME_NORMAL'])
                    : 0;

        }


        $voucher['Standard']['PAY_NIGHT'] = ($voucher['Standard']['TIME_NIGHT'] * $night_pay) * $pay_night_percentage;

        // 법정연장금액 = 법정연장시간*기본시급*1.5
        // 엑셀 상에는 연장수당 불적용 항목이 있다. 불적용시 법정연장금액 = 법정연장시간 * 기본지급액
//        $voucher['Standard']['PAY_OVERTIME'] = ($voucher['Standard']['TIME_OVERTIME'] * $basic_overtime_pay) * $pay_over_time_percentage;

        // 휴일급여
//        $voucher['Standard']['PAY_HOLIDAY'] = ($voucher['Standard']['TIME_HOLIDAY'] * $holiday_pay) * $pay_holiday_percentage;
        $pay_holiday_min = $voucher['Standard']['TIME_HOLIDAY']-35 > 0 ? $voucher['Standard']['TIME_HOLIDAY']-35 : 0;
        $voucher['Standard']['PAY_HOLIDAY'] = ($voucher['Standard']['TIME_HOLIDAY']*1.5*$basic_pay) + ($pay_holiday_min*0.5*$basic_pay);


        // 휴일연장급여
        $voucher['Standard']['PAY_HOLIDAY_OVERTIME'] = ($voucher['Standard']['TIME_HOLIDAY_OVERTIME'] * $holiday_overtime_pay) * $pay_holiday_overtime_percentage;

        // 야간급여
        $voucher['Standard']['PAY_NIGHT'] = ($voucher['Standard']['TIME_NIGHT'] * $night_pay) * $pay_night_percentage;


        $voucher['Standard']['ALLOWANCE_WEEK_PAY'] = 0;
        $voucher['Standard']['ALLOWANCE_WEEK_TIME'] = 0;
        $voucher['Standard']['ALLOWANCE_YEAR_PAY'] = 0;
        $voucher['Standard']['ALLOWANCE_YEAR_TIME'] = 0;
        $voucher['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR'] = 0;
        $voucher['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME'] = 0;
        $voucher['Standard']['PUBLIC_HOLIDAY_PAY'] = 0;
        $voucher['Standard']['PUBLIC_HOLIDAY_TIME'] = 0;

        // 주휴수당 포함 체크했을 경우
        if ($request->input("week_pay_apply_check")) {
            $weekly_allowance = Standard::weekly_allowance($voucher, $basic_pay, $request);
            $voucher['Standard']['ALLOWANCE_WEEK_PAY'] = $weekly_allowance['pay'] ?? 0;
            $voucher['Standard']['ALLOWANCE_WEEK_TIME'] = $weekly_allowance['time'] ?? 0;
        }

        // 연차수당 구하기
        if ($request->input("year_pay_apply_check")) {
            $year_pay = Standard::year_allowance($key, $voucher, $basic_pay, $request);
            $voucher['Standard']['ALLOWANCE_YEAR_PAY'] = $year_pay['pay'] ?? 0;
            $voucher['Standard']['ALLOWANCE_YEAR_TIME'] = $year_pay['time'] ?? 0;
        }

        // 1년미만 연차수당 구하기
        if ($request->input("one_year_less_annual_pay_check")) {
            $yearly_pay = Standard::one_less_year_allowance($key, $voucher, $basic_pay, $request);
            $voucher['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR'] = $yearly_pay['pay'] ?? 0;
            $voucher['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME'] = $yearly_pay['time'] ?? 0;
        }

        // 공휴일의 유급휴일임금 계산 구하기 ---> 계산법 바뀌면서 안쓰게 됨.
//        if ($request->input("public_allowance_check")) {
//            $public_day_pay = Standard::public_holiday_allowance($key, $voucher, $basic_pay, $request);
//            $voucher['Standard']['PUBLIC_HOLIDAY_PAY'] = $public_day_pay['pay'] ?? 0;
//            $voucher['Standard']['PUBLIC_HOLIDAY_TIME'] = $public_day_pay['time'] ?? 0;
//        }

        // 근로자의 날 수당계산
        if ($request->input("workers_day_allowance_check")) {
            $workers_day_pay = Standard::workers_day_allowance($key, $voucher, $basic_pay, $request);
            $voucher['Standard']['WORKERS_DAY_PAY'] = $workers_day_pay['pay'] ?? 0;
            $voucher['Standard']['WORKERS_DAY_TIME'] = $workers_day_pay['time'] ?? 0;
        }

        // 적용합계 구하기
        $voucher['Standard']['PAY_TOTAL']
            = $voucher['Standard']['PAY_BASIC']
            + $voucher['Standard']['PAY_OVERTIME']
            + $voucher['Standard']['PAY_HOLIDAY']
            + $voucher['Standard']['PAY_HOLIDAY_OVERTIME']
            + $voucher['Standard']['PAY_NIGHT']
            + $voucher['Standard']['ALLOWANCE_WEEK_PAY']
            + $voucher['Standard']['ALLOWANCE_YEAR_PAY']
            + $voucher['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR']
//            + $voucher['Standard']['PUBLIC_HOLIDAY_PAY']
            + $voucher['Standard']['WORKERS_DAY_PAY'];


        return $voucher;
    }

    public static function get($request, $work_lists, $vouchers)
    {
        // 국공일 휴일적용, 평일적용
        $public_holiday_check = $request->input("public_officers_holiday_check");

        $helpers = self::createFormat($work_lists); // 활동지원사 각각에게 배열만들어주기
        $helpers = self::basicWork($work_lists, $helpers, $public_holiday_check); // 기본시간구하기
        $helpers = self::NightWork($work_lists, $helpers, $vouchers, $public_holiday_check); // 야근시간구하기
        $helpers = self::HolidayWork($work_lists, $helpers, $public_holiday_check); // 휴일시간+수당구하기

        // 기본시간, 야근시간, 휴일시간 구한걸로 연장시간 및 급여, 주휴수당, 연차수당 구하기
//        $helpers = self::payments($helpers, $request, $vouchers);

        // $helpers["김땡땡"]['Standard'][데이터]... 에서 Standard 배열을 없애주고 안에있던 데이터를 밖으로 ex) $helpers["김땡땡"]['Standard'][데이터] => $helpers['김땡땡'][데이터]
        $data = [];
        foreach ($helpers as $key => $helper)
        {
            $data[$key] = $helper['Standard'];
        }

        return $data;
    }


    public static function payments($helpers, $request, $vouchers)
    {

        $basic_pay = $request->input("pay_hour"); // 통상시급
        $basic_overtime_pay = $basic_pay * 1.5; // 연장시급
        $night_pay = $basic_pay * 0.5; // 야간시급. 0.5 주의 (아무리봐도 0.5 이상함. 0.5가 맞는듯? 기본급시간에 야간시간이 포함됨 21.02.19)
        $holiday_pay = $basic_pay * 1.5; // 휴일시급
        $holiday_overtime_pay = $basic_pay * 0.5; // 휴일연장시급

        // 계산된 시간으로 반복문 한번, 내부에서 기본시간, 연장시간, 야근시간, 휴일시간 각각 반복문 더 돌려준다
        foreach ($helpers as $helper => $data)
        {

            // 기본급여, 연장시간, 연장급여 구하기
//            $data = self::payments_basic_and_overtime($data, $basic_pay, $basic_overtime_pay, $vouchers[$helper]);

            // 야간수당 구하기
//            $data['Standard']['PAY_NIGHT'] = $data['Standard']['TIME_NIGHT'] * $night_pay;

            // 휴일수당, 휴일연장수당 구하기
//            $data = self::payments_holiday($data, $holiday_pay, $holiday_overtime_pay, $vouchers[$helper]);

//            // 주휴수당 포함 체크했을 경우
//            if ($request->input("week_pay_apply_check")) {
//                $weekly_allowance = self::weekly_allowance($data, $basic_pay, $request);
//                $data['Standard']['ALLOWANCE_WEEK_PAY'] = $weekly_allowance['pay'] ?? 0;
//                $data['Standard']['ALLOWANCE_WEEK_TIME'] = $weekly_allowance['time'] ?? 0;
//            }
//
//            // 연차수당 구하기
//            if ($request->input("year_pay_apply_check")) {
//                $year_pay = self::year_allowance($helper, $data, $basic_pay, $request);
//                $data['Standard']['ALLOWANCE_YEAR_PAY'] = $year_pay['pay'] ?? 0;
//                $data['Standard']['ALLOWANCE_YEAR_TIME'] = $year_pay['time'] ?? 0;
//            }
//
//            // 1년미만 연차수당 구하기
//            if ($request->input("one_year_less_annual_pay_check")) {
//                $yearly_pay = self::one_less_year_allowance($helper, $data, $basic_pay, $request);
//                $data['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR'] = $yearly_pay['pay'] ?? 0;
//                $data['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME'] = $yearly_pay['time'] ?? 0;
//            }
//
//            // 공휴일의 유급휴일임금 계산 구하기
//            if ($request->input("public_allowance_check")) {
//                $public_day_pay = self::public_holiday_allowance($helper, $data, $basic_pay, $request);
//                $data['Standard']['PUBLIC_HOLIDAY_PAY'] = $public_day_pay['pay'] ?? 0;
//                $data['Standard']['PUBLIC_HOLIDAY_TIME'] = $public_day_pay['time'] ?? 0;
//            }
//
//            // 근로자의 날 수당계산
//            if ($request->input("workers_day_allowance_check")) {
//                $workers_day_pay = self::workers_day_allowance($helper, $data, $basic_pay, $request);
//                $data['Standard']['WORKERS_DAY_PAY'] = $workers_day_pay['pay'] ?? 0;
//                $data['Standard']['WORKERS_DAY_TIME'] = $workers_day_pay['time'] ?? 0;
//            }
//
//            // 적용합계 구하기
//            $data['Standard']['PAY_TOTAL']
//                = $data['Standard']['PAY_BASIC']
//                + $data['Standard']['PAY_OVERTIME']
//                + $data['Standard']['PAY_HOLIDAY']
//                + $data['Standard']['PAY_HOLIDAY_OVERTIME']
//                + $data['Standard']['PAY_NIGHT']
//                + $data['Standard']['ALLOWANCE_WEEK_PAY']
//                + $data['Standard']['ALLOWANCE_YEAR_PAY']
//                + $data['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR']
//                + $data['Standard']['PUBLIC_HOLIDAY_PAY']
//                + $data['Standard']['WORKERS_DAY_PAY'];
//
//            $helpers[$helper] = $data;

        }

        return $helpers;

    }

    // 근로자의 날 수당계산
    public static function workers_day_allowance($helper, $data, $basic_pay, $request)
    {

        $from_date = $request->input("from_date");
        $year = date("Y", strtotime($from_date));
        $workers_day = strtotime($year."-05-01");
        $from_date = strtotime($from_date);

        // 찾는날짜가 5월이 아니면 return 0;
        if ($from_date != $workers_day) return 0;

        $join_date = strtotime(Worker::getJoinDate($helper));

        // 가입일이 근로자의날 보다 크다면 입사전이라 return 0
        if ($join_date > $workers_day) return 0;

        $time_basic = $data['Standard']['TIME_BASIC'];
        $x_days_a_week = $request->input("workers_day_allowance_day_selector");

        // 당월의 일월화수목금토, 날짜갯수를 받아온다.
        $yoil_count_arr = Custom::week_day_count($from_date);
        $yoil = $yoil_count_arr['yoil']; // 요일 배열. 순서: [ 일,월,화,수,목,금,토 ]
        $day_count = $yoil_count_arr['day_count']; // 당월의 날짜갯수 ex) 30, 31

        $weekend_count = $x_days_a_week == 5 ? $yoil[0] + $yoil[6] : $yoil[0]; // 주말일수 뺀다. 주5일이면 토일, 주6일이면 일
        $weekday = $day_count - $weekend_count; // 평일일수 = 날짜갯수 - 주말갯수

        $average_time = $time_basic / $weekday; // 공휴일시간(평일평균시간) = 전체시간 / 평일일수
        return [ "pay" => round($average_time * $basic_pay * 1), "time" => $average_time ]; // 평일시간*기본시급*근로자의날하루

    }


    // 공휴일의 유급휴일임금계산 => 공휴일은 관공서공휴일과 주휴일 두개뿐이다. 관공서공휴일은 적용여부로 포함여부가 선택된다.
    public static function public_holiday_allowance($helper, $data, $basic_pay, $request)
    {
        $type = $request->input("public_allowance_selector") ?? false;
        $time_basic = $data['Standard']['TIME_BASIC'];

        // 드물게 평일일한 시간이 없는 경우 리턴 0
        if ($time_basic == 0 || !$time_basic) return 0;

        if (!$type) return 0; // 선택안됐다면 0
        else if ($type == "basic60" && $time_basic < 60) return 0; // 기본시간 60시간선택, 65시간 안되는 사람은 0
        else if ($type == "basic65" && $time_basic < 65) return 0; // 기본시간 65시간선택, 65시간 안되는 사람은 0

        $from_date = $request->input("from_date");
        $public_holiday_check = $request->input("public_officers_holiday_check"); // 공휴일의 유급휴일임금 계산 체크여부
        $x_days_a_week = $request->input("public_allowance_day_selector"); // 주 5일인지 주 6일인지 체크
        $workers_day_check = $request->input("workers_day_allowance_check") ?? false;

        // 제공자의 가입일자를 가져온다. 공휴일은 제공자의 가입일자에 영향을 받는다. ex) 21.01.02에 가입했으면 21.01.01 공휴일 수당을 못받는다. 가입당일이 공휴일이면 받을 수 있음
        $join_date = Worker::getJoinDate($helper);

        // 제공자의 휴일을 가져온다. 국공일+제공자주휴일. check가 1이면 국공일도 가져오고 2면 국공일은 제외된다
        $holidays = HelperSchedules::getHelperHolidayAtMonth([
            "user_id" => User::get_user_id(),
            "provider_key" => $helper,
            "start_date_time" => $from_date,
            "check" => $public_holiday_check,
            "join_date" => $join_date,
            "workers_day" => $workers_day_check
        ]);

        if (count($holidays) == 0) return 0;

        // 당월의 일월화수목금토, 날짜갯수를 받아온다.
        $yoil_count_arr = Custom::week_day_count($from_date);
        $yoil = $yoil_count_arr['yoil']; // 요일 배열. 순서: [ 일,월,화,수,목,금,토 ]
        $day_count = $yoil_count_arr['day_count']; // 당월의 날짜갯수 ex) 30, 31

        $weekend_count = $x_days_a_week == 5 ? $yoil[0] + $yoil[6] : $yoil[0]; // 주말일수 뺀다. 주5일이면 토일, 주6일이면 일
        $weekday = $day_count - $weekend_count; // 평일일수 = 날짜갯수 - 주말갯수

        $average_time = $time_basic / $weekday; // 공휴일시간(평일평균시간) = 전체시간 / 평일일수
        return [ "pay" => round($average_time * $basic_pay * count($holidays)), "time" => $average_time ];
    }


    // 1년 미만자 연차수당구하기
    public static function one_less_year_allowance($provider_key, $data, $basic_pay, $request)
    {
        $from_date = $request->input("from_date");

        // 가입일이 1년이 넘으면 0
        if (Worker::first_year_check($provider_key, $from_date)) return 0;

        $type = $request->input("one_year_less_annual_pay_type") ?? false;
        $time_basic = $data['Standard']['TIME_BASIC'];

        if (!$type) return 0; // 선택안됐다면 0
        else if ($type == "basic60" && $time_basic < 60) return 0; // 기본시간 60시간선택, 65시간 안되는 사람은 0
        else if ($type == "basic65" && $time_basic < 65) return 0; // 기본시간 65시간선택, 65시간 안되는 사람은 0

        $weekCount = $request->input("one_year_less_annual_pay_selector");
        $time  = $time_basic / (365/7/12) / $weekCount * 11 / 12;
        return [ "pay" => round($time * $basic_pay), "time" => $time ];
    }


    // 연차수당 구하기
    public static function year_allowance($provider_key, $data, $basic_pay, $request)
    {
        $from_date = $request->input("from_date");

        // 가입일이 1년이 안되면 0
        if (!Worker::first_year_check($provider_key, $from_date)) return 0;


        $type = $request->input("year_pay_apply_type") ?? false;
        $time_basic = $data['Standard']['TIME_BASIC'];

        if (!$type) return 0; // 선택안됐다면 0
        else if ($type == "basic60" && $time_basic < 60) return 0; // 기본시간60시간선택, 65시간 안되는 사람은 0
        else if ($type == "basic65" && $time_basic < 65) return 0; // 기본시간65시간선택, 65시간 안되는 사람은 0

        $weekCount = $request->input("year_pay_selector");
        $time  = $time_basic / (365/7/12) / $weekCount * 15 / 12;


        return [ "pay" => round($time * $basic_pay), "time" => $time ];
    }



    // 주휴수당 구하기
    public static function weekly_allowance($data, $basic_pay, $request)
    {
        $type = $request->input("week_pay_apply_type") ?? false;
        $time_basic = $data['Standard']['TIME_BASIC'];

        if (!$type) return 0; // 선택안됐다면 0
        else if ($type == "basic60" && $time_basic < 60) return 0; // 기본시간60시간선택, 65시간 안되는 사람은 주휴수당 0
        else if ($type == "basic65" && $time_basic < 65) return 0; // 기본시간65시간선택, 65시간 안되는 사람은 주휴수당 0

        $weekCount = $request->input("week_pay_selector");

        // 주휴시간 = 기본시간 / 5 or 6 (주 5일, 주 6일 영향)
        $time  = $time_basic / $weekCount;
        return [ "pay" => $time * $basic_pay, "time" => $time ];
    }



    public static function createFormat($work_lists)
    {
        $helpers = [];
        $calc = [
            "PAY_BASIC" => 0,
            "TIME_BASIC" => 0,
            "PAY_OVERTIME" => 0,
            "TIME_OVERTIME" => 0,
            "BASIC" => [],

            "PAY_HOLIDAY" => 0,
            "TIME_HOLIDAY" => 0,
            "TIME_HOLIDAY_OVERTIME" => 0,
            "PAY_HOLIDAY_OVERTIME" => 0,
            "HOLIDAY" => [],

            "PAY_NIGHT" => 0,
            "TIME_NIGHT" => 0,

            "ALLOWANCE_WEEK_PAY" => 0, // 주휴수당

            "ALLOWANCE_YEAR_PAY" => 0, // 연차수당

            "ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR" => 0, // 1년 미만자 연차수당
            "ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME" => 0, // 1년 미만자 연차수당시간

            "PUBLIC_HOLIDAY_PAY" => 0, // 공휴일의 유급휴일임금 계산
            "PUBLIC_HOLIDAY_TIME" => 0, // 공휴일의 유급휴일임금 시간
            
            "WORKERS_DAY_PAY" => 0,
            "WORKERS_DAY_TIME" => 0,

            // 적용합계
            "PAY_TOTAL" => 0,

            // 이 아래에는 사용을 안합니다. 값이 있더라도 무시하세요. 2021.02.18
            "TIME_TOTAL" => 0,

            "TIME_EXTRA" => 0,
            "PAY_EXTRA" => 0,

            "TIME_NORMAL" => 0,
            "PAY_NORMAL" => 0,
        ];

        foreach ($work_lists as $list)
        {
            if (!isset($helpers[$list->provider_key]))
            {
                $helpers[$list->provider_key]['Standard'] = $calc;
            }
        }

        return $helpers;
    }


    // 기본시간 구하기
    public static function basicWork($work_lists, $helpers, $type)
    {

        // 평일 시간만 구하기
        foreach ($work_lists as $list)
        {
            // 날짜데이터 없으면 제외
            if ($list->service_start_date_time == "1970-01-01 00:00:00") continue;

            // 야간일때 제외
            if (Night::is_night([$list->service_start_date_time, $list->service_end_date_time])) continue;

            // 휴일일때 제외
            if (Holiday::isHoliday($list->service_start_date_time, $list->provider_key, $type)) continue;

            $basic = Basic::calc($list->service_start_date_time, $list->service_end_date_time);

//            $helpers[$list->provider_key]['Standard']['TIME_TOTAL'] += $basic;

            $date = date("Y-m-d", strtotime($list->service_start_date_time));

            if (!isset($helpers[$list->provider_key]['Standard']['BASIC'][$date])) {
                $helpers[$list->provider_key]['Standard']['BASIC'][$date] = [
                    'TIME_BASIC' => 0,
                    'TIME_BASIC_OVERTIME' => 0,
                    'PAY_BASIC' => 0,
                    'PAY_BASIC_OVERTIME' => 0,
                    'VOUCHER_TIME' => 0,
                    "id" => "",
                    "service_time" => $list->service_start_date_time . " ~ " . $list->service_end_date_time
                ];
            }


            $helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC'] += $basic;
            $helpers[$list->provider_key]['Standard']['BASIC'][$date]['VOUCHER_TIME'] += $list->payment_time;
            $helpers[$list->provider_key]['Standard']['BASIC'][$date]['test'] = $list->id;
        }

//        pp($helpers);

        return $helpers;

    }



    // 야간시간 구하기
    public static function NightWork($work_lists, $helpers, $vouchers, $type)
    {
        foreach ($work_lists as $list)
        {
            // 엑셀에서 법정시간은 바우처시간으로 구하고있다.
            // 따라서 바우처시간으로 구하겠음. 21.02.19
            $helpers[$list->provider_key]['Standard']['TIME_NIGHT'] =
                ($vouchers[$list->provider_key]['Voucher']['COUNTRY']['TIME_NIGHT']
                + $vouchers[$list->provider_key]['Voucher']['CITY']['TIME_NIGHT']);


            // 가산 금액이 있다면 휴일 혹은 야간(연장은 확인이 안된다. 한 행에 8시간이 넘는 기록은 현재까지 없음)
            if ($list->add_price == 0) continue;

            // 야간이 아니면 continue
            if (!Night::is_night([ $list->service_start_date_time, $list->service_end_date_time ])) continue;

            // 야간이어도 평일시간과 야간시간이 붙어있는 경우가 있기때문에 분리해야한다
            $night = Night::calc($list->service_start_date_time, $list->service_end_date_time);

//            $helpers[$list->provider_key]['Standard']['TIME_TOTAL'] += $night['basic_hour'] + $night['night_hour'];
//            $helpers[$list->provider_key]['Standard']['TIME_BASIC'] += $night['basic_hour'];
//            $helpers[$list->provider_key]['Standard']['TIME_NIGHT'] += $night['night_hour'];
//            $helpers[$list->provider_key]['Standard']['TIME_EXTRA'] += $night['night_hour'];

            $date = date("Y-m-d", strtotime($list->service_start_date_time));

            if (!isset($helpers[$list->provider_key]['Standard']['NIGHT'][$date])) {
                $helpers[$list->provider_key]['Standard']['NIGHT'][$date] = [
                    'TIME_NIGHT' => $night['night_hour'],
                    'VOUCHER_TIME' => $list->payment_time,
                    "id" => "",
                    "service_time" => $list->service_start_date_time . " ~ " . $list->service_end_date_time
                ];
            }

            // 휴일이라면 휴일시간도 더해준다
//            if (Holiday::isHoliday($list->service_start_date_time, $list->provider_key, $type)) {
//                $helpers[$list->provider_key]['Standard']['HOLIDAY'][$date]['TIME_HOLIDAY'];
//            }

            // 평일 시간이 있다면 평일 시간에 더 해준다
            if ($night['basic_hour'] != 0) {

                if (!isset($helpers[$list->provider_key]['Standard']['BASIC'][$date])) {
                    $helpers[$list->provider_key]['Standard']['BASIC'][$date] = [
                        'TIME_BASIC' => 0,
                        'TIME_BASIC_OVERTIME' => 0,
                        'PAY_BASIC' => 0,
                        'PAY_BASIC_OVERTIME' => 0,
                        'VOUCHER_TIME' => 0,
                        "id" => "",
                        "service_time" => ""
                    ];
                }


                // 당일 평일 시간이 8시간보다 작다면 기본시간에 더해준다
                if ($helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC'] < 8) {
                    $helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC'] += $night['basic_hour'];
//                    $addBasicTime = $night['basic_hour'];

                    // 평일 시간을 더해줬는데 8시간이 넘는다면 연장시간계산 해준다
                    if ($helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC'] > 8) {
//                        $addBasicTime = 8 - ($helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC'] - $night['basic_hour']);
                        $overtime = $helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC'] - 8;
                        $helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC_OVERTIME'] = $overtime;
                        $helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC'] = 8;
                    }

                // 당열 평일 시간이 8시간보다 크다면 이미 연장이므로 연장에만 더해준다
                } else {
                    $helpers[$list->provider_key]['Standard']['BASIC'][$date]['TIME_BASIC_OVERTIME'] += $night['basic_hour'];
                }

//                $helpers[$list->provider_key]['Standard']['TIME_BASIC'] += $addBasicTime;
//                $helpers[$list->provider_key]['Standard']['TIME_OVERTIME'] += $overtime;

            }

        }

        return $helpers;

    }


    // 휴일 구하기
    public static function HolidayWork($work_lists, $helpers, $type)
    {

        // 휴일
        foreach ($work_lists as $list)
        {
            
            // 가산금액이 0이면 다음으로
            if ($list->add_price == 0) continue;

            // 야간거르기 (휴일과 야간 모두 되는 경우 야간판정)
//            if (Night::is_night([$list->service_start_date_time, $list->service_end_date_time])) continue;

            // 휴일이 아니면 continue 우선 꺼놔야할 거 같다. 고객의 주휴일 목록이 없기때문에 로우데이터의 야간을 제외한 가산금액이있는 행과 결과가 달라진다
//            if (!Holiday::isHoliday($list->service_start_date_time, $list->provider_key, $type)) continue;

            $holiday = Holiday::calc($list->service_start_date_time, $list->service_end_date_time);

            $date = date("Y-m-d", strtotime($list->service_start_date_time));

            if (!isset($helpers[$list->provider_key]['Standard']['HOLIDAY'][$date])) {
                $helpers[$list->provider_key]['Standard']['HOLIDAY'][$date] = [
                    'TIME_HOLIDAY' => 0,
                    'TIME_HOLIDAY_OVERTIME' => 0,
                    'PAY_HOLIDAY' => 0,
                    'PAY_HOLIDAY_OVERTIME' => 0
                ];
            }

            // 엑셀에서는 법정시간을 바우처시간에 따르고 있다. 따라서 바우처시간으로 변경 21.02.19
            $helpers[$list->provider_key]['Standard']['HOLIDAY'][$date]['TIME_HOLIDAY'] += $list->payment_time;


//            $helpers[$list->provider_key]['Standard']['HOLIDAY'][$date]['TIME_HOLIDAY'] += $holiday;
//            $helpers[$list->provider_key]['Standard']['TIME_TOTAL'] += $holiday;
//            $helpers[$list->provider_key]['Standard']['TIME_EXTRA'] += $holiday;

        }

        return $helpers;

    }



    // 기본급여, 연장시간, 연장급여 구하기
    public static function payments_basic_and_overtime($data, $basic_pay, $basic_overtime_pay, $voucher)
    {

        // 기본급, 기본연장시간, 기본연장급여구하기
        foreach ($data['Standard']['BASIC'] as $day => $log)
        {

            // 엑셀에서는 바우처시간으로 법정시간도 구하고 있다.
            // 따라서 마찬가지로 법정시간을 바우처시간으로 구하기때문에 주석처리 21.02.19
//            $data['Standard']['TIME_BASIC'] += $log['TIME_BASIC']; // 기본시간 구하기
//            $data['Standard']['PAY_BASIC'] += $log['TIME_BASIC'] * $basic_pay; // 기본급 구하기

            $log['PAY_BASIC'] += $log['TIME_BASIC'] * $basic_pay;
//            $data['Standard']['BASIC'][$day] = $log;

            // 8시간 이하면 다음으로, 8시간 초과면 연장 계산하기
            if ($log['TIME_BASIC_OVERTIME'] == 0) continue;

            // 평일연장인 경우
            $overtime = $log['TIME_BASIC_OVERTIME'];

//            $log['TIME_BASIC'] = 8;
//            $log['TIME_BASIC_OVERTIME'] += $overtime;
//            $log['PAY_BASIC_OVERTIME'] += $overtime * $basic_overtime_pay;
//
//            $data['Standard']['BASIC'][$day] = $log;
//
////            $data['Standard']['TIME_BASIC'] -= $overtime;
//            $data['Standard']['TIME_OVERTIME'] += $log['TIME_BASIC_OVERTIME'];
//            $data['Standard']['PAY_OVERTIME'] += $log['TIME_BASIC_OVERTIME'] * $basic_overtime_pay;
        }

        // 엑셀에서는 바우처시간으로 법정시간도 구하고 있다.
        // 따라서 마찬가지로 법정시간을 바우처시간으로 구하겠다 21.02.19
        $time_total = $voucher['Voucher']['COUNTRY']['TIME_TOTAL'] + $voucher['Voucher']['CITY']['TIME_TOTAL'];
        $time_holiday = $voucher['Voucher']['COUNTRY']['TIME_HOLIDAY'] + $voucher['Voucher']['CITY']['TIME_HOLIDAY'];
//        $time_night = $voucher['Voucher']['COUNTRY']['TIME_NIGHT'] + $voucher['Voucher']['CITY']['TIME_NIGHT']; // 야간 시간은 기본시간에 포함된다

        $data['Standard']['TIME_BASIC'] = $time_total - ($time_holiday);
//        $data['Standard']['PAY_BASIC'] = $data['Standard']['TIME_BASIC'] * $basic_pay;


        return $data;
    }



    public static function payments_holiday($data, $holiday_pay, $holiday_overtime_pay, $voucher)
    {

        foreach ($data['Standard']['HOLIDAY'] as $day => $log)
        {

            // 휴일급여 구하기
            $data['Standard']['TIME_HOLIDAY'] += $log['TIME_HOLIDAY'];
            $data['Standard']['PAY_HOLIDAY'] += $log['TIME_HOLIDAY'] * $holiday_pay;

            $log['PAY_HOLIDAY'] = $log['TIME_HOLIDAY'] * $holiday_pay;

            $data['Standard']['HOLIDAY'][$day] = $log;

            // 8시간 이하면 넘어가기, 8시간 초과면 휴일연장 계산하기
            if ($log['TIME_HOLIDAY'] <= 8) continue;

            // 같은 배열안 연장수당 부분 채워주기
            $overtime = $log['TIME_HOLIDAY'] - 8;
            $log['TIME_HOLIDAY_OVERTIME'] = $overtime;
//            $log['PAY_HOLIDAY_OVERTIME'] = $overtime * $holiday_overtime_pay;
            $data['Standard']['HOLIDAY'][$day] = $log;

            $data['Standard']['TIME_HOLIDAY_OVERTIME'] += $overtime;
//            $data['Standard']['PAY_HOLIDAY_OVERTIME'] += $overtime * $holiday_overtime_pay;

        } /* foreach day end */

        return $data;
    }
}
