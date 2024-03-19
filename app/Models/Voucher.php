<?php

namespace App\Models;

use App\Classes\Basic;
use App\Classes\Custom;
use App\Classes\Holiday;
use App\Classes\Night;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class Voucher extends Model
{
    use HasFactory;

    public static $user_id;


    public static function total($voucher)
    {
        // 국비일수, 도비일수(국비 도비 겹치는 날 있는 경우 도비에서 일수만큼 빼준다)
        $voucher['Voucher']['COUNTRY']['DAY_COUNT'] = count($voucher['Voucher']['COUNTRY']['DAY']);
        $voucher['Voucher']['CITY']['DAY_COUNT'] = Custom::count_duplicate_keys_in_array($voucher['Voucher']['CITY']['DAY'], $voucher['Voucher']['COUNTRY']['DAY']);

        // 국비 휴일야간합계금액, 도비휴일야간합계금액
        $voucher['Voucher']['COUNTRY']['PAYMENT_EXTRA'] = $voucher['Voucher']['COUNTRY']['PAYMENT_NIGHT'] + $voucher['Voucher']['COUNTRY']['PAYMENT_HOLIDAY'];
        $voucher['Voucher']['CITY']['PAYMENT_EXTRA'] = $voucher['Voucher']['CITY']['PAYMENT_NIGHT'] + $voucher['Voucher']['CITY']['PAYMENT_HOLIDAY'];

        // 국비기본금액, 도비기본시간
        $voucher['Voucher']['COUNTRY']['PAYMENT_NORMAL'] = $voucher['Voucher']['COUNTRY']['PAYMENT_TOTAL'] - $voucher['Voucher']['COUNTRY']['PAYMENT_EXTRA'];
        $voucher['Voucher']['CITY']['PAYMENT_NORMAL'] = $voucher['Voucher']['CITY']['PAYMENT_TOTAL'] - $voucher['Voucher']['CITY']['PAYMENT_EXTRA'];

        // 국비 가산시간, 도비가산시간
        $voucher['Voucher']['COUNTRY']['TIME_EXTRA'] = $voucher['Voucher']['COUNTRY']['TIME_HOLIDAY'] + $voucher['Voucher']['COUNTRY']['TIME_NIGHT'];
        $voucher['Voucher']['CITY']['TIME_EXTRA'] = $voucher['Voucher']['CITY']['TIME_HOLIDAY'] + $voucher['Voucher']['CITY']['TIME_NIGHT'];

        // 국비 기본시간, 도비 기본시간
        $voucher['Voucher']['COUNTRY']['TIME_NORMAL'] = $voucher['Voucher']['COUNTRY']['TIME_TOTAL'] - $voucher['Voucher']['COUNTRY']['TIME_EXTRA'];
        $voucher['Voucher']['CITY']['TIME_NORMAL'] = $voucher['Voucher']['CITY']['TIME_TOTAL'] - $voucher['Voucher']['CITY']['TIME_EXTRA'];

        return $voucher;
    }

    // 바우처상 지급합계 (혹은 기본지급, 지급총액)
    public static function payment($request, $voucher)
    {
        $pay = 0;
        $from_date = $request->input("from_date") ?? "2020-11";

//        $voucher_payment = VoucherPayment::get($from_date);
        // 21.02.19 시간당인건비단가란이 추가되면서 바우처단가를 디비에서 불러오지않고 시간당인건비단가를 사용
        $voucher_payment = $request->input("pay_per_hour") ?? VoucherPayment::get($from_date);

        // 1: 휴일수당 고정값, 2: 휴일수당 비례값
        $type = $request->input("voucher_pay_total");

        /*
         * 바우처지급합계 고정값 구하기 -> 평일값은 고정. 가산금액만 비율영향받음.
         * 휴일수당고정값 / 국가지정바우처수가 = 고정값비율을 구함 ex) 10000 / 13500 = 0.74%
         * (국비가산금액 + 시도비가산금액) * 고정값비율 = 휴일수당
         * 국비승인금액 + 시도비승인금액 + 휴일수당 = 바우처지급합계 휴일수당 고정값
         *
         *
         * */
        if ($type == 1)
        {
            // 고정값
            $fixing = $request->input("voucher_holiday_pay_fixing");
            $base_payment = 10310; // 엑셀의 기본지급액
            $percentage = round($base_payment / $voucher_payment, 3);

            // (승인합계총시간-승인합계휴일시간-승인합계야간시간) * 바우처수가 + 기타청구금액) * 0.764 + (승인합계휴일시간 + 승인합계야간시간) * 휴일수당고정값
            $pay = ((($voucher['COUNTRY']['TIME_TOTAL'] + $voucher['CITY']['TIME_TOTAL']
                            - $voucher['COUNTRY']['TIME_HOLIDAY'] - $voucher['COUNTRY']['TIME_NIGHT']
                            - $voucher['CITY']['TIME_HOLIDAY'] - $voucher['CITY']['TIME_NIGHT']) * $voucher_payment + 0)
                * $percentage)
                + (($voucher['COUNTRY']['TIME_HOLIDAY'] + $voucher['COUNTRY']['TIME_NIGHT'] + $voucher['CITY']['TIME_HOLIDAY'] + $voucher['CITY']['TIME_NIGHT']) * $fixing);


            // 엑셀식으로 전환하면서 주석처리 21.02.19
//            $extra_pay = ($voucher['COUNTRY']['PAYMENT_NIGHT'] + $voucher['COUNTRY']['PAYMENT_HOLIDAY'] + $voucher['CITY']['PAYMENT_NIGHT'] + $voucher['COUNTRY']['PAYMENT_HOLIDAY']) * $percentage;
//            $pay = $voucher['COUNTRY']['PAYMENT_NORMAL'] + $voucher['CITY']['PAYMENT_NORMAL'] + $extra_pay;
//            $pay = ($voucher['COUNTRY']['PAYMENT_TOTAL'] + $voucher['CITY']['PAYMENT_TOTAL']) * $percentage;
        }

        /*
         * 바우처지급합계 비례값 구하기 -> 총금액에 비례값비율을 계산해주기
         * 시간당인건비단가 / 국가지정바우처수가 = 비례값비율을 구함 ex) 10000 / 13500 = 0.74%
         * 국비 + 시도비 * 비례값비율 = 바우처지급합계 휴일수당 비례값
         * @ 생각해야할 부분: 시간당인건비단가/바우처수가인지 시간당인건비단가*1.5/바우처수가인지 확인해봐야함 2021.02.04 질문안함
         * 시간당인건비단가(입력값) 은 바우처수가랑 똑같기때문에(...) 퍼센티지 곱하는게 의미가 없다. 즉, 그냥 야간+휴일일때 1.5배만 해주면 된다.
         * */
        else
        {
            $pay_per_hour = $request->input("pay_per_hour");
            $percentage = round($pay_per_hour / $voucher_payment, 2);
            $pay = ($voucher['COUNTRY']['PAYMENT_TOTAL'] + $voucher['CITY']['PAYMENT_TOTAL']) * $percentage;
        }


        return $pay;
    }


    public static function getReturn($request, $work_lists)
    {

        // 프롬데이트 가짜데이터
        $from_date = $request->input("from_date") ?? "2020-11";

        // 그 해의 바우처 단가를 가져와서 가산금액/바우처단가를 하면 가산시간을 구할 수 있다.
        $voucher_payment = VoucherPayment::get($from_date);


        // 국비, 도비의 정보를 가져온다.
        $business_types = self::business_types();

        $calc = [
            "COUNTRY" => [
                "DAYS" => [],
                "PAYMENT_TOTAL" => 0,
                "TIME_TOTAL" => 0,
                "TIME_EXTRA" => 0,
                "TIME_NIGHT" => 0,
                "TIME_HOLIDAY" => 0,
            ],
            "CITY" => [
                "DAYS" => [],
                "PAYMENT_TOTAL" => 0,
                "TIME_TOTAL" => 0,
                "TIME_EXTRA" => 0,
                "TIME_NIGHT" => 0,
                "TIME_HOLIDAY" => 0,
            ]
        ];

        $helpers = [];

        foreach ($work_lists as $list)
        {

            if (!isset($helpers[$list->provider_key]['Return'])) {
                $helpers[$list->provider_key]['Return'] = $calc;
            }

            // 날짜 공란 넘기기
//            if ($list->service_start_date_time == "1970-01-01 00:00:00") continue;

            // 반납건이 아니라면 넘기기
            if (trim($list->return_sort) == "") continue;

            $type = "";

            // 국비인지, 시도군구비 확인하기
            if (in_array($list->business_type, $business_types['country'])) {
                $type = "COUNTRY";
            } else if (in_array($list->business_type, $business_types['city'])) {
                $type = "CITY";
            }



            $time_extra = 0;

            // 가산 시간이 있다면 야간, 휴일 시간 구해줌
            if ($list->add_price != 0) {

                // 야간
                if (Night::is_night([ $list->service_start_date_time, $list->service_end_date_time ])) {
                    $time_extra = round($list->add_price / ($voucher_payment/2), 2);
                    $helpers[$list->provider_key]['Return'][$type]['TIME_NIGHT'] += $time_extra;

                // 휴일
                } else {
                    $helpers[$list->provider_key]['Return'][$type]['TIME_HOLIDAY'] += $list->add_price / ($voucher_payment/2);
                }

            }

            $date = date("Y-m-d", strtotime($list->confirm_date));

            $helpers[$list->provider_key]['Return'][$type]['DAYS'][$date] = 1;
            $helpers[$list->provider_key]['Return'][$type]['PAYMENT_TOTAL'] += $list->confirm_pay;
            $helpers[$list->provider_key]['Return'][$type]['TIME_TOTAL'] += $list->payment_time;
            $helpers[$list->provider_key]['Return'][$type]['TIME_EXTRA'] += $time_extra;

        }


        $data = [];
        foreach ($helpers as $key => $helper)
        {
            $data[$key] = $helper['Return'];
        }

        return $data;
    }

    public static function business_types()
    {
        // 사업유형 국가,광역,기초 목록 가져온다
        $get_business_types = DB::table("business_types")
            ->select(DB::raw("`name`, `type`"))
            ->get();

        // 사업유형 만들기
        $business_types = [
            "COUNTRY" => [],
            "CITY" => [],
            "local" => [] // 안씀
        ];

        foreach ($get_business_types as $key => $type)
        {
            switch ($type->type)
            {
                case 1:
                    $business_types["country"][] = trim($type->name);
                    break;
                case 2:
                    $business_types["city"][] = trim($type->name);
                    break;
                case 3:
                    $business_types["local"][] = trim($type->name);
                    break;
            }
        }

        return $business_types;
    }

    public static function getWorkData($request)
    {
        // 프롬데이트 가짜데이터
        $from_date = $request->input("from_date") ?? "2020-11";

        $from_date = date("Y-m-d", strtotime($from_date."-01"));

        $user_id = User::get_user_id();
        self::$user_id = $user_id;


        // 당월의 근무한 내역을 가져온다
        return DB::table("voucher_logs")
            ->whereRaw("user_id = ?", [ $user_id ])
            ->whereRaw("confirm_date >= ?", [ $from_date ])
            ->whereRaw("confirm_date < DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY )", [ $from_date ])
//            ->where("provider_key", "=", "김학희670326")
//            ->where("provider_key", "=", "이미숙670804")
//            ->where("provider_key", "=", "강미애660315")
//            ->where("provider_key", "=", "강춘미570916")
//            ->where("provider_key", "=","고현정741203")
//                ->where("provider_key", "=", "양은숙570425")
//                ->whereRaw("provider_key IN ")
//                ->whereRaw("provider_key in (?,?,?,?,?,?,?,?,?,?)", [ "양은숙570425",  "강춘미570916", "권재령590907", "김명자500727", "김복실661208", "고해경760130", "고현정741203",
//                "권진영720420", "김광용880107", "김복순590405" ])
            ->orderBy("provider_name")
            ->get();
    }

    public static function calc($request, $work_lists)
    {

        // 프롬데이트 가짜데이터
        $from_date = $request->input("from_date") ?? "2020-11";

        $from_date = date("Y-m-d", strtotime($from_date."-01"));
        $public_check = $request->input("public_officers_holiday_check");

        // 국비, 도비 정보 가져온다
        $business_types = self::business_types();

        $calc = [
            "COUNTRY" => [
                "TIME_TOTAL" => 0,
                "TIME_NORMAL" => 0,
                "TIME_EXTRA" => 0,
                "TIME_HOLIDAY" => 0,
                "TIME_NIGHT" => 0,
                "PAYMENT_TOTAL" => 0,
                "PAYMENT_NORMAL" => 0,
                "PAYMENT_EXTRA" => 0,
                "PAYMENT_NIGHT" => 0,
                "PAYMENT_HOLIDAY" => 0,
                "DAY" => [],
                "DAY_COUNT" => 0
            ],
            "CITY" => [
                "TIME_TOTAL" => 0,
                "TIME_NORMAL" => 0,
                "TIME_EXTRA" => 0,
                "TIME_HOLIDAY" => 0,
                "TIME_NIGHT" => 0,
                "PAYMENT_TOTAL" => 0,
                "PAYMENT_NORMAL" => 0,
                "PAYMENT_EXTRA" => 0,
                "PAYMENT_NIGHT" => 0,
                "PAYMENT_HOLIDAY" => 0,
                "DAY" => [],
                "DAY_COUNT" => 0
            ],
            "_STANDARD" => [
                "TIME_HOLIDAY" => 0,
                "TIME_NIGHT" => 0,
            ]
        ];

        $helpers = [];

        // 그 해의 바우처 단가를 가져와서 바우처단가/가산금액를 하면 가산시간을 구할 수 있다.
        $voucher_payment = $request->input("pay_per_hour") ?? VoucherPayment::get($from_date);



        foreach ($work_lists as $key => $list)
        {

            if (!isset($helpers[$list->provider_key]['Voucher'])) {
                $helpers[$list->provider_key]['Voucher'] = $calc;
            }

            // 반납건이라면 넘기기 ( 마이너스로 되어있기때문에 안넘겨도 된다)
//            if ($list->return_sort != "") continue;

            $type = "";


            // 국비인지, 시도군구비 확인하기
            if (in_array($list->business_type, $business_types['country'])) {
                $type = "COUNTRY";
            } else if (in_array($list->business_type, $business_types['city'])) {
                $type = "CITY";
            }

            // 서비스시작시간이 없는 자료가 있기때문에 승인시간으로 변경. 21.02.19
            $date = date("Y-m-d", strtotime($list->service_start_date_time));

            if (!isset($helpers[$list->provider_key]['Voucher'][$type]['DAY'][$date])) {
                $helpers[$list->provider_key]['Voucher'][$type]['DAY'][$date] = 1;
            }

            $helpers[$list->provider_key]['Voucher'][$type]['TIME_TOTAL'] += is_int($list->payment_time) ? $list->payment_time : (float) $list->payment_time;
            $helpers[$list->provider_key]['Voucher'][$type]['PAYMENT_TOTAL'] += is_int($list->confirm_pay) ? $list->confirm_pay : (float) $list->confirm_pay;

            // 날짜 공란 넘기기 (공란이어도 총 합계는 더해서 다음으로가야한다)
            if ($list->service_start_date_time == "1970-01-01 00:00:00") continue;

            $time_normal = 0;
            $time_extra = 0;
//            $pay_normal = 0;


            // 가산금액이 0이 아니라면 -> 휴일/야간
            if ($list->add_price != 0) {

                $night_check = Night::is_night([ $list->service_start_date_time, $list->service_end_date_time ]);
                $holiday_check = Holiday::isHoliday($list->service_start_date_time, $list->service_end_date_time, $public_check);

//                $pay_normal = $list->confirm_pay - $list->add_price;


                // 야간,휴일 둘 다 OK일땐 평일시간->휴일시간
                if ($night_check && $holiday_check) {
                    $helpers[$list->provider_key]['Voucher']['_STANDARD']['TIME_HOLIDAY'] += $list->add_price / ($voucher_payment/2);
                    $helpers[$list->provider_key]['Voucher']['_STANDARD']['TIME_NIGHT'] += Night::night_time($list->service_start_date_time, $list->service_end_date_time);
                }
                else if ($night_check && !$holiday_check) {
                    $helpers[$list->provider_key]['Voucher']['_STANDARD']['TIME_NIGHT'] += Night::night_time($list->service_start_date_time, $list->service_end_date_time);
                } else {
                    $helpers[$list->provider_key]['Voucher']['_STANDARD']['TIME_HOLIDAY'] += $list->add_price / ($voucher_payment/2);
                }



                // 야간인지 검사해서 야간이라면 야간시간에 포함. (야간은 평일+야간시간일 수 있으므로 평일시간, 야간시간 분리가 필요하다)
                if (Night::is_night([ $list->service_start_date_time, $list->service_end_date_time ])) {
                    $time_extra = round($list->add_price / ($voucher_payment/2), 2);
                    $helpers[$list->provider_key]['Voucher'][$type]['TIME_NIGHT'] += $time_extra;
                    $helpers[$list->provider_key]['Voucher'][$type]['PAYMENT_NIGHT'] += $list->add_price;

                    // 결제시간과 가산시간이 다르다면 평일시간이 끼어있다는 것. 평일시간 구해준다.
                    if ($list->payment_time != $time_extra) {
                        $time_normal = $list->payment_time - $time_extra;
                    }

                // 야간시간이 아니라면 휴일이므로 휴일에 추가. 휴일은 야간+휴일인 경우 밖에 없으므로 무조건 가산이다. 분리X
                } else {
                    $helpers[$list->provider_key]['Voucher'][$type]['TIME_HOLIDAY'] += $list->add_price / ($voucher_payment/2);
                    $helpers[$list->provider_key]['Voucher'][$type]['PAYMENT_HOLIDAY'] += $list->add_price;
                }



            } else {
                $time_normal = $list->payment_time;
//                $pay_normal = $list->confirm_pay;
            }


            $helpers[$list->provider_key]['Voucher'][$type]['TIME_NORMAL'] += $time_normal;
            $helpers[$list->provider_key]['Voucher'][$type]['TIME_EXTRA'] += $time_extra;
//            $helpers[$list->provider_key]['Voucher'][$type]['PAYMENT_NORMAL'] += $pay_normal; // 승인금액-가산금액으로 구하게 되므로 주석처리 21.02.19
            $helpers[$list->provider_key]['Voucher'][$type]['PAYMENT_EXTRA'] += $list->add_price;

        }

        return $helpers;
    }
}
