<?php

namespace App\Models;

use App\Classes\Custom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Worker;


class Salary extends Model
{
    use HasFactory;

    public static $user_id, $check;


    public static function calcTotal($total, $voucher)
    {
        // 합계
        // 바우처 국비
        $total['VOUCHER_NATION_DAY'] += count($voucher['Voucher']['COUNTRY']['DAY']);
        $total['VOUCHER_NATION_PAYMENT'] += $voucher['Voucher']['COUNTRY']['PAYMENT_TOTAL'];
        $total['VOUCHER_NATION_PAYMENT_EXTRA'] += $voucher['Voucher']['COUNTRY']['PAYMENT_EXTRA'];
        $total['VOUCHER_NATION_TIME'] += $voucher['Voucher']['COUNTRY']['TIME_TOTAL'];
        $total['VOUCHER_NATION_TIME_HOLIDAY'] += $voucher['Voucher']['COUNTRY']['TIME_HOLIDAY'];
        $total['VOUCHER_NATION_TIME_NIGHT'] += $voucher['Voucher']['COUNTRY']['TIME_NIGHT'];

        // 바우처 도비
        $total['VOUCHER_CITY_DAY'] += Custom::count_duplicate_keys_in_array($voucher['Voucher']['CITY']['DAY'], $voucher['Voucher']['COUNTRY']['DAY']);
        $total['VOUCHER_CITY_PAYMENT'] += $voucher['Voucher']['CITY']['PAYMENT_TOTAL'];
        $total['VOUCHER_CITY_PAYMENT_EXTRA'] += $voucher['Voucher']['CITY']['PAYMENT_EXTRA'];
        $total['VOUCHER_CITY_TIME'] += $voucher['Voucher']['CITY']['TIME_TOTAL'];
        $total['VOUCHER_CITY_TIME_HOLIDAY'] += $voucher['Voucher']['CITY']['TIME_HOLIDAY'];
        $total['VOUCHER_CITY_TIME_NIGHT'] += $voucher['Voucher']['CITY']['TIME_NIGHT'];

        // 바우처 합계
        $total['VOUCHER_DAY'] += Custom::array_merge_with_duplicate_keys($voucher['Voucher']['COUNTRY']['DAY'], $voucher['Voucher']['CITY']['DAY']);
        $total['VOUCHER_PAYMENT'] += $voucher['Voucher']['COUNTRY']['PAYMENT_TOTAL'] + $voucher['Voucher']['CITY']['PAYMENT_TOTAL'];
        $total['VOUCHER_PAYMENT_EXTRA'] += $voucher['Voucher']['COUNTRY']['PAYMENT_EXTRA'] + $voucher['Voucher']['CITY']['PAYMENT_EXTRA'];
        $total['VOUCHER_TIME'] += $voucher['Voucher']['COUNTRY']['TIME_TOTAL'] + $voucher['Voucher']['CITY']['TIME_TOTAL'];
        $total['VOUCHER_TIME_HOLIDAY'] += $voucher['Voucher']['COUNTRY']['TIME_HOLIDAY'] + $voucher['Voucher']['CITY']['TIME_HOLIDAY'];
        $total['VOUCHER_TIME_NIGHT'] += $voucher['Voucher']['COUNTRY']['TIME_NIGHT'] + $voucher['Voucher']['CITY']['TIME_NIGHT'];

        // 바우처 승인금액 분리
        $total['VOUCHER_DETACH_PAYMENT_BASIC'] += $voucher['Voucher']['COUNTRY']['PAYMENT_NORMAL'] + $voucher['Voucher']['CITY']['PAYMENT_NORMAL'];
        $total['VOUCHER_DETACH_PAYMENT_HOLIDAY'] += $voucher['Voucher']['COUNTRY']['PAYMENT_HOLIDAY'] + $voucher['Voucher']['CITY']['PAYMENT_HOLIDAY'];
        $total['VOUCHER_DETACH_PAYMENT_NIGHT'] += $voucher['Voucher']['COUNTRY']['PAYMENT_NIGHT'] + $voucher['Voucher']['CITY']['PAYMENT_NIGHT'];
        $total['VOUCHER_DETACH_PAYMENT_DIFF'] +=
            ($voucher['Voucher']['COUNTRY']['PAYMENT_TOTAL'] + $voucher['Voucher']['CITY']['PAYMENT_TOTAL'])
            - ($voucher['Voucher']['COUNTRY']['PAYMENT_NORMAL'] + $voucher['Voucher']['CITY']['PAYMENT_NORMAL']
                + $voucher['Voucher']['COUNTRY']['PAYMENT_HOLIDAY'] + $voucher['Voucher']['CITY']['PAYMENT_HOLIDAY']
                + $voucher['Voucher']['COUNTRY']['PAYMENT_NIGHT'] + $voucher['Voucher']['CITY']['PAYMENT_NIGHT']);



        // 바우처 반납승인내역 국비반납
        $total['RETURN_NATION_DAY'] += count($voucher['Return']['COUNTRY']['DAYS']) ?? 0;
        $total['RETURN_NATION_PAYMENT'] += $voucher['Return']['COUNTRY']['PAYMENT_TOTAL'] ?? 0;
        $total['RETURN_NATION_TIME'] += $voucher['Return']['COUNTRY']['TIME_TOTAL'];

        // 바우처 반납승인내역 도비반납
        $total['RETURN_CITY_DAY'] += count($voucher['Return']['CITY']['DAYS']);
        $total['RETURN_CITY_PAYMENT'] += $voucher['Return']['CITY']['PAYMENT_TOTAL'];
        $total['RETURN_CITY_TIME'] += $voucher['Return']['CITY']['TIME_TOTAL'];

        // 바우처 반납승인내역 합계
        $total['RETURN_DAY'] += count($voucher['Return']['COUNTRY']['DAYS']) + count($voucher['Return']['CITY']['DAYS']);
        $total['RETURN_PAYMENT'] += $voucher['Return']['COUNTRY']['PAYMENT_TOTAL'] + $voucher['Return']['CITY']['PAYMENT_TOTAL'];
        $total['RETURN_TIME'] += $voucher['Return']['COUNTRY']['TIME_TOTAL'] + $voucher['Return']['CITY']['TIME_TOTAL'];




        // 바우처상 지급합계
        $total['VOUCHER_PAY_TOTAL'] += $voucher['Payment'];

        // 제공자 법정 지급항목 시뮬레이션
        $total['STANDARD_BASIC_TIME'] += $voucher['Standard']['TIME_BASIC'];
        $total['STANDARD_BASIC_PAYMENT'] += $voucher['Standard']['PAY_BASIC'];
        $total['STANDARD_OVER_TIME'] += $voucher['Standard']['TIME_OVERTIME'];
        $total['STANDARD_OVER_PAYMENT'] += $voucher['Standard']['PAY_OVERTIME'];
        $total['STANDARD_HOLIDAY_TIME'] += $voucher['Standard']['TIME_HOLIDAY'];
        $total['STANDARD_HOLIDAY_PAYMENT'] += $voucher['Standard']['PAY_HOLIDAY'];
        $total['STANDARD_NIGHT_TIME'] += $voucher['Standard']['TIME_NIGHT'];
        $total['STANDARD_NIGHT_PAYMENT'] += $voucher['Standard']['PAY_NIGHT'];

        $total['STANDARD_WEEKLY_TIME'] += $voucher['Standard']['ALLOWANCE_WEEK_TIME'] ?? 0;
        $total['STANDARD_WEEKLY_PAYMENT'] += $voucher['Standard']['ALLOWANCE_WEEK_PAY'] ?? 0;

        $total['STANDARD_YEARLY_TIME'] += $voucher['Standard']['ALLOWANCE_YEAR_TIME'] + $voucher['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME'] ?? 0;
        $total['STANDARD_YEARLY_PAYMENT'] += $voucher['Standard']['ALLOWANCE_YEAR_PAY'] + $voucher['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR'] ?? 0;


        $total['STANDARD_WORKERS_DAY_TIME'] += $voucher['Standard']['WORKERS_DAY_TIME'];
        $total['STANDARD_WORKERS_DAY_PAYMENT'] += $voucher['Standard']['WORKERS_DAY_PAY'];

        $total['STANDARD_WORKERS_DAY_TIME'] += $voucher['Standard']['WORKERS_DAY_TIME'];
        $total['STANDARD_WORKERS_DAY_PAYMENT'] += $voucher['Standard']['WORKERS_DAY_PAY'];

        $total['PUBLIC_HOLIDAY_TIME'] += $voucher['Standard']['PUBLIC_HOLIDAY_TIME'];
        $total['PUBLIC_HOLIDAY_PAY'] += $voucher['Standard']['PUBLIC_HOLIDAY_PAY'];

        // 적용합계
        $total['STANDARD_PAYMENT'] += $voucher['Standard']['PAY_TOTAL'];

        // 법정제수당(또는차액)
        $total['PAYMENT_DIFF'] += $voucher['Payment'] - $voucher['Standard']['PAY_TOTAL'];


        // 급여공제 근로자
        $total['TAX_WORKER_NATION'] += $voucher['Tax']['WORKER_NATIONAL'];
        $total['TAX_WORKER_HEALTH'] += $voucher['Tax']['WORKER_HEALTH'];
        $total['TAX_WORKER_EMPLOY'] += $voucher['Tax']['WORKER_EMPLOY'];
        $total['TAX_WORKER_GABGEUNSE'] += $voucher['Tax']['CLASS_A_WAGE'];
        $total['TAX_WORKER_RESIDENT'] += $voucher['Tax']['RESIDENT_TAX'];

        $total['TAX_WORKER_TOTAL'] += $voucher['WorkerTaxTotal'];

        $total['PAYMENT_TAX_SUB'] += ($voucher['Payment'] - $voucher['WorkerTaxTotal']);


        // 급여공제 기관(사용자)
        $total['COMPANY_INCOME'] += $voucher['CompanyBusinessTotal'];
        $total['TAX_COMPANY_NATION'] += $voucher['Tax']['COMPANY_NATIONAL'];
        $total['TAX_COMPANY_HEALTH'] += $voucher['Tax']['COMPANY_HEALTH'];
        $total['TAX_COMPANY_EMPLOY'] += $voucher['Tax']['COMPANY_EMPLOY'];
        $total['TAX_COMPANY_INDUSTRY'] += $voucher['Tax']['COMPANY_INDUSTRY'];

        // 퇴직적립금
        $total['RETIREMENT'] += $voucher['Retirement'];

        // 반납승인
        $total['CONFIRM_RETURN'] += ($voucher['Return']['COUNTRY']['PAYMENT_TOTAL'] + $voucher['Return']['CITY']['PAYMENT_TOTAL']) * 0.236;

        // 사업주부담합계
        $total['TAX_COMPANY_TOTAL'] += $voucher['CompanyTaxTotal'];

        // 차감 사업주 수익
        $total['PAYMENT_COMPANY_TAX_SUB'] += ($voucher['CompanyBusinessTotal'] - $voucher['CompanyTaxTotal']);

        return $total;
    }


    public static function decide($request, $data)
    {
        $user_id = User::get_user_id();
        $save = $request->input("save");
        $target_ym = date("Y-m-d", strtotime($data['payment_ym']));

        // 급여계산 조건 저장
        $conditions = new PaymentConditions;
        $conditions->user_id = $user_id;
        $conditions->target_ym = $target_ym;
        $conditions->public_officers_holiday_check = $data['public_officers_holiday_check'];
        $conditions->pay_per_hour = $data['pay_per_hour'];
        $conditions->pay_hour = $data['pay_hour'];
        $conditions->pay_over_time = $data['pay_over_time'];
        $conditions->pay_holiday = $data['pay_holiday'];
        $conditions->pay_holiday_over_time = $data['pay_holiday_over_time'];
        $conditions->pay_night = $data['pay_night'];
        $conditions->pay_annual = $data['pay_annual'];
        $conditions->pay_one_year_less_annual = $data['pay_one_year_less_annual'];
        $conditions->pay_public_holiday = $data['pay_public_holiday'];
        $conditions->pay_workers_day = $data['pay_workers_day'];
        $conditions->week_pay_apply_check = $data['week_pay_apply_check'];
        $conditions->week_pay_apply_type = $data['week_pay_apply_type'];
        $conditions->week_pay_selector = $data['week_pay_selector'];
        $conditions->year_pay_apply_check = $data['year_pay_apply_check'];
        $conditions->year_pay_apply_type = $data['year_pay_apply_type'];
        $conditions->year_pay_selector = $data['year_pay_selector'];
        $conditions->one_year_less_annual_pay_check = $data['one_year_less_annual_pay_check'];
        $conditions->one_year_less_annual_pay_type = $data['one_year_less_annual_pay_type'];
        $conditions->one_year_less_annual_pay_selector = $data['one_year_less_annual_pay_selector'];
        $conditions->public_allowance_check = $data['public_allowance_check'];
        $conditions->public_allowance_selector = $data['public_allowance_selector'];
        $conditions->public_allowance_day_selector = $data['public_allowance_day_selector'];
        $conditions->workers_day_allowance_check = $data['workers_day_allowance_check'];
        $conditions->workers_day_allowance_day_selector = $data['workers_day_allowance_day_selector'];
        $conditions->voucher_pay_total = $data['voucher_pay_total'];
        $conditions->voucher_holiday_pay_fixing = $data['voucher_holiday_pay_fixing'];
        $conditions->voucher_holiday_pay_hour_per_price = $data['pay_per_hour'] * 1.5;
        $conditions->retirement_saving_pay_type = $data['retirement_saving_pay_type'];
        $conditions->retirement_saving_pay_company_percentage = $data['retirement_saving_pay_company_percentage'];
        $conditions->tax_nation_selector = $data['tax_nation_selector'];
        $conditions->tax_health_selector = $data['tax_health_selector'];
        $conditions->tax_employ_selector = $data['tax_employ_selector'];
        $conditions->tax_industry_selector = $data['tax_industry_selector'];
        $conditions->tax_gabgeunse_selector = $data['tax_gabgeunse_selector'];
        $conditions->employ_tax_selector = $data['employ_tax_selector'];
        $conditions->industry_tax_percentage = $data['industry_tax_percentage'];


        $conditions->timetable_1 = $data['timetable_1'];
        $conditions->timetable_2 = $data['timetable_2'];
        $conditions->timetable_3 = $data['timetable_3'];
        $conditions->timetable_4 = $data['timetable_4'];


        if (!$conditions->save()) return false;

        $is = self::isDeleteCheck($user_id, $target_ym);
        if (!$is) return $is;

        $transaction = false;

        foreach ($data['provider_id'] as $i => $datum)
        {
            $transaction = DB::transaction(function () use ($user_id, $request, $i, $data, $conditions, $save, $target_ym) {

                // 급여계산 바우처 저장
                $voucher = new PaymentVouchers;
                $voucher->user_id = $user_id;
                $voucher->provider_key = $data['provider_key'][$i];
                $voucher->target_ym = $target_ym;
                $voucher->condition_id = $conditions->id;
                $voucher->is_register = $data['provider_id'][$i] != "" ? 1 : 0;
                $voucher->nation_day_count = $data['country_day_count'][$i];
                $voucher->nation_confirm_payment = $data['country_payment_total'][$i];
                $voucher->nation_add_payment = $data['country_payment_extra'][$i];
                $voucher->nation_total_time = $data['country_time_total'][$i];
                $voucher->nation_holiday_time = $data['country_time_holiday'][$i];
                $voucher->nation_night_time = $data['country_time_night'][$i];
                $voucher->city_day_count = $data['city_day_count'][$i];
                $voucher->city_confirm_payment = $data['city_payment_total'][$i];
                $voucher->city_add_payment = $data['city_payment_extra'][$i];
                $voucher->city_total_time = $data['city_time_total'][$i];
                $voucher->city_holiday_time = $data['city_time_holiday'][$i];
                $voucher->city_night_time = $data['city_time_night'][$i];
                $voucher->voucher_total_day_count = $data['voucher_total_day_count'][$i];
                $voucher->voucher_total_confirm_payment = $data['voucher_total_payment'][$i];
                $voucher->voucher_total_confirm_payment_add = $data['voucher_total_payment_extra'][$i];
                $voucher->voucher_total_time = $data['voucher_total_time'][$i];
                $voucher->voucher_total_time_holiday = $data['voucher_total_time_holiday'][$i];
                $voucher->voucher_total_time_night = $data['voucher_total_time_night'][$i];
                $voucher->voucher_detach_payment_basic = $data['voucher_detach_payment_normal'][$i];
                $voucher->voucher_detach_payment_holiday = $data['voucher_detach_payment_holiday'][$i];
                $voucher->voucher_detach_payment_night = $data['voucher_detach_payment_night'][$i];
                $voucher->voucher_detach_payment_difference = $data['voucher_detach_payment_diff'][$i];
                $voucher->voucher_etc_charge_city_time = 0;
                $voucher->voucher_etc_charge_city_pay = 0;
                $voucher->voucher_etc_charge_except_time = 0;
                $voucher->voucher_etc_charge_except_pay = 0;
                $voucher->voucher_etc_charge_total_time = 0;
                $voucher->voucher_etc_charge_total_pay = 0;
                $voucher->voucher_return_nation_day_count = $data['return_country_day_count'][$i];
                $voucher->voucher_return_nation_pay = $data['return_country_payment'][$i];
                $voucher->voucher_return_nation_time = $data['return_country_time'][$i];
                $voucher->voucher_return_city_day_count = $data['return_city_day_count'][$i];
                $voucher->voucher_return_city_pay = $data['return_city_payment'][$i];
                $voucher->voucher_return_city_time = $data['return_city_time'][$i];
                $voucher->voucher_return_total_day_count = $data['return_total_day_count'][$i];
                $voucher->voucher_return_total_pay = $data['return_total_payment'][$i];
                $voucher->voucher_return_total_time = $data['return_total_time'][$i];
                $voucher->voucher_payment = $data['voucher_payment_total'][$i];
                if (!$voucher->save()) return false;

                $standard = new PaymentStandards;
                $standard->user_id = $user_id;
                $standard->condition_id = $conditions->id;
                $standard->payment_id = $voucher->id;
                $standard->provider_key = $data['provider_key'][$i];
                $standard->target_ym = $target_ym;
                $standard->standard_basic_time = $data['standard_basic_time'][$i];
                $standard->standard_basic_payment = $data['standard_pay_basic'][$i];
                $standard->standard_over_time = $data['standard_time_overtime'][$i];
                $standard->standard_over_payment = $data['standard_pay_overtime'][$i];
                $standard->standard_holiday_time = $data['standard_time_holiday'][$i];
                $standard->standard_holiday_payment = $data['standard_pay_holiday'][$i];
                $standard->standard_night_time = $data['standard_time_night'][$i];
                $standard->standard_night_payment = $data['standard_pay_night'][$i];
                $standard->standard_weekly_time = $data['standard_time_weekly'][$i];
                $standard->standard_weekly_payment = $data['standard_pay_weekly'][$i];
                $standard->standard_yearly_time = $data['standard_time_yearly'][$i];
                $standard->standard_yearly_payment = $data['standard_pay_yearly'][$i];
                $standard->standard_workers_day_time = $data['standard_time_workers_day'][$i];
                $standard->standard_workers_day_payment = $data['standard_pay_workers_day'][$i];
                $standard->standard_public_day_time = $data['standard_time_public_rest'][$i];
                $standard->standard_public_day_payment = $data['standard_pay_public_rest'][$i];
                $standard->standard_payment = $data['standard_total_pay'][$i];
                $standard->voucher_sub_standard_payment = $data['standard_pay_diff'][$i];
                $standard->standard_bojeon = 0;
                $standard->standard_jaboodam = 0;
                $standard->standard_jaesoodang = 0;
                $standard->standard_bannap = 0;
                $standard->voucher_payment = $data['voucher_payment_total'][$i];
                if (!$standard->save()) return false;

                $tax = new PaymentTaxs;
                $tax->user_id = $user_id;
                $tax->condition_id = $conditions->id;
                $tax->payment_id  = $voucher->id;
                $tax->provider_key = $data['provider_key'][$i];
                $tax->target_ym = $target_ym;
                $tax->save_selector = $save;
                $tax->selected_payment = $save == "voucher" ? $data['voucher_payment_total'][$i] : $data['standard_total_pay'][$i];

                $tax->tax_nation_pension = $save == "voucher" ? $data['worker_nation'][$i] : $data['worker_nation_standard'][$i];
                $tax->tax_health = $save == "voucher" ? $data['worker_health'][$i] : $data['worker_health_standard'][$i];
                $tax->tax_employ = $save == "voucher" ? $data['worker_employ'][$i] : $data['worker_employ_standard'][$i];
                $tax->tax_gabgeunse = $save == "voucher" ? $data['worker_gabgeunse'][$i] : $data['worker_gabgeunse_standard'][$i];
                $tax->tax_joominse = $save == "voucher" ? $data['worker_juminse'][$i] : $data['worker_juminse_standard'][$i];
                $tax->tax_joominse = $save == "voucher" ? $data['worker_juminse'][$i] : $data['worker_juminse_standard'][$i];

                $tax->tax_gunbo = 0;
                $tax->tax_yearly = 0;
                $tax->tax_bad_income = 0;
                $tax->tax_etc_1 = 0;
                $tax->tax_etc_2 = 0;

                $tax->tax_total = $save == "voucher" ? $data['worker_tax_total'][$i] : $data['worker_tax_total_standard'][$i];

                $tax->tax_sub_payment = $save == "voucher" ? $data['worker_tax_after'][$i] : $data['worker_tax_after_standard'][$i];
                $tax->bank_name = $data['worker_bank'][$i];
                $tax->bank_number = $data['worker_bank_number'][$i];

                $tax->company_income = $save == "voucher" ? $data['company_business_pay_total'][$i] : $data['company_business_pay_total_standard'][$i];
                $tax->tax_company_nation = $save == "voucher" ? $data['company_tax_nation'][$i] : $data['company_tax_nation_standard'][$i];
                $tax->tax_company_health = $save == "voucher" ? $data['company_tax_health'][$i] : $data['company_tax_health_standard'][$i];
                $tax->tax_company_employ = $save == "voucher" ? $data['company_tax_employ'][$i] : $data['company_tax_employ_standard'][$i];
                $tax->tax_company_industry = $save == "voucher" ? $data['company_tax_industry'][$i] : $data['company_tax_industry_standard'][$i];
                $tax->tax_company_retirement = $save == "voucher" ? $data['company_requirement'][$i] : $data['company_requirement_standard'][$i];
                $tax->tax_company_return_confirm = $data['company_return_total'][$i];

                $tax->tax_company_tax_total = $save == "voucher" ? $data['company_tax_total'][$i] : $data['company_tax_total_standard'][$i];
                $tax->company_payment_result = $save == "voucher" ? $data['company_sub_payment'][$i] : $data['company_sub_payment_standard'][$i];
                if (!$tax->save()) return false;


                // 급여계산 회원정보저장
                $worker_info = new PaymentWorkersInfo;
                $worker_info->condition_id = $conditions->id;
                $worker_info->user_id = $user_id;
                $worker_info->payment_id = $voucher->id;
                $worker_info->target_ym = $target_ym;
                $worker_info->provider_name = $data['provider_name'][$i];
                $worker_info->provider_key = $data['provider_key'][$i];
                $worker_info->provider_reg_check = $data['provider_reg_check'][$i] != "" ? 1 : 0;
                $worker_info->join_date = $data['join_date'][$i] ?: "";
                $worker_info->resign_date = $data['resign_date'][$i] ?: "";
                $worker_info->nation_ins = $data['national_pension'][$i] ?: "";
                $worker_info->health_ins = $data['health_insurance'][$i] ?: "";
                $worker_info->employ_ins = $data['employment_insurance'][$i] ?: "";
                $worker_info->retirement = $data['retirement_pay_contract'][$i] ?: "";
                $worker_info->year_rest_count = $data['year_rest_count'][$i] ?: "";
                $worker_info->dependents = Worker::getDependents($data['provider_key'][$i]);
                return $worker_info->save();

            });

            if (!$transaction) break;
        }

        return $transaction;
    }


    public static function isDeleteCheck($user_id, $target_ym)
    {
        $target_ym = date("Y-m-d", strtotime($target_ym));

        $is = DB::table("payment_conditions")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", "=", $target_ym)
            ->first();

        if (!$is) return true;

        $transaction = false;

        $transaction = DB::transaction(function () use ($is, $target_ym, $user_id) {
            $conditions = PaymentConditions::find($is->id);

            $del = $conditions->delete();
            $vouchers = PaymentVouchers::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->delete();
            $workers_info = PaymentWorkersInfo::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->delete();
            $taxs = PaymentTaxs::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->delete();
            $standards = PaymentStandards::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->delete();


            if ($del) {
                return true;
            }

        });

        return $transaction;

    }

    public static function calc($request)
    {
        $from_date = date("Y-m-d", strtotime($request->input("from_date")."-01"));
        $timeDiff = [];

        $user_id = User::get_user_id();
        self::$user_id = $user_id;

        // 한 달의 일 수
        $weekday_count = date("t", strtotime($from_date));

        // 당월의 근무한 내역을 가져온다
        $work_lists = DB::table("voucher_logs")
            ->whereRaw("user_id = ?", [ $user_id ])
            ->whereRaw("confirm_date >= ?", [ $from_date ])
            ->whereRaw("confirm_date < DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY )", [ $from_date ])
            ->whereRaw("provider_key = ?", [ "조수경971111" ])
            ->get();


        // 사업유형 국가,광역,기초 목록 가져온다
        $get_business_types = DB::table("business_types")
            ->select(DB::raw("`name`, `type`"))
            ->get();

        // 사업유형 만들기
        $business_types = [
            "country" => [],
            "city" => [],
            "local" => []
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


        // 기본시급(원)
        $hourly_wage = $request->input("pay_hour");

        // 연장근로수당비율 input name명 실수..annual이지만 연장근로수당임
        $over_time_wage = ((0.5/100) * $request->input("pay_over_time")) * $hourly_wage;

        // 휴일수당 1.5의 몇% * 기본시급 ex) 1.5의 50% = 0.75 * 8590 = 6442.5
        $holiday_wage = ((1.5/100) * $request->input("pay_holiday")) * $hourly_wage;
        // 휴일수당이 설정안됐다면 기본지급액으로 설정
        if ($request->input("pay_holiday") == 0 || $request->input("pay_holiday") == "") {
            $holiday_wage = $hourly_wage;
        }

        // 공휴일연장수당 0.5배의 몇% * 기본시급
        $holiday_over_time_wage = ((0.5/100) * $request->input("pay_holiday_over_time")) * $hourly_wage;

        // 야간수당 0.5 몇% * 기본시급
        $night_time_wage = ((1.5/100) * $request->input("pay_night")) * $hourly_wage;

        // 연차수당 비율
        $annual_wage = $request->input("pay_annual") / 100;

        // 공휴일수당비율
        $public_holiday_wage = $request->input("pay_public_holiday") / 100;

        // 1년 미만자 연차수당 비율
        $one_less_annual_wage = $request->input("pay_one_year_less_annual") / 100;

        // 근로자의 날 수당 비율
        $workers_day_wage = $request->input("pay_workers_day");


        self::$check = $request->input("public_officers_holiday_check");

        foreach ($work_lists as $i => $list)
        {

            // 활동지원사 테이블에서 해당 지원사의 입사일을 가져온다
            $isWorker = Worker::getWorkerInfo([
                "user_id"=>$user_id,
                "provider_key"=>$list->provider_key
            ]);

            $join_date = "";
            $resign_date = "";
            if ($isWorker) {
                $join_date = $isWorker->join_date;
                $resign_date = $isWorker->resign_date;
            }



            // 활동지원사의 해당 월의 주휴일, 공휴일 목록 가져온다. (입사일보다 나중일자만 가져옴)
            $getHelperHoliday = HelperSchedules::getHelperHolidayAtMonth([
                "user_id" => $user_id,
                "provider_key" => $list->provider_key,
                "start_date_time" => $from_date,
                "check" => self::$check,
                "join_date" => $join_date
            ]);

            $holiday_list = [];
            if ($getHelperHoliday['code'] == 1) {
                $holiday_list = $getHelperHoliday['data'];
            }


            $start = new Carbon($list->service_start_date_time);
            $end = new Carbon($list->service_end_date_time);
            $gap = $start->diff($end);
            $time = calc_work_minute($gap);


            // 활동지원사 배열이 없을 경우 새로 만든다 ex) 홍길동871215 = [];
            if (!isset($timeDiff[$list->provider_key])) {

                $timeDiff[$list->provider_key] = self::setWorkData([
                    "list" => $list,
                    "holiday_list" => $holiday_list,
                    "join_date" => $join_date,
                    "resign_date" => $resign_date
                ]);

            }

            $timeDiff[$list->provider_key]['voucher_payment'] = self::CALC_VOUCHER_PAYMENT([
                "request" => $request,
                "lists" => $list,
                "start" => $list->service_start_date_time,
                "end" => $list->service_end_date_time,
                "business_types" => $business_types,
                "voucher_payment" => $timeDiff[$list->provider_key]['voucher_payment']
            ]);


            $work_once_time_total = (float) $list->payment_time;

            // 일한 총시간 합산하기
            $timeDiff[$list->provider_key]["total_hour"] += $work_once_time_total;
            // --- 일한 총시간 end


            $date_ymd = date("Y-m-d", strtotime($list->service_start_date_time));
            $date_range = $list->service_start_date_time . "~" .$list->service_end_date_time;



            /* 휴일, 공휴일 근무시간 계산하기 */
            /* 서비스 시작시간이 공휴일목록에 있는 날짜라면, */
            if (in_array($date_ymd, $holiday_list))
            {
                // 날짜 배열 만들기
                if (!isset($timeDiff[$list->provider_key]["holiday"][$date_ymd])) {
                    $timeDiff[$list->provider_key]["holiday"][$date_ymd] = [
                        "total" => 0,
                        "over_time" => 0,
                        "detail" => [],
                    ];
                }

                // 공휴일의 "결제시간" 값 다 더하기 (결제시간값이 절대적인건 아님)
                $timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] += $work_once_time_total;
                $timeDiff[$list->provider_key]["holiday"][$date_ymd]["detail"][] = [
                    "diff" => $work_once_time_total, // 몇시간 일했는지? 엑셀의 결제시간
                    "diff_calc" => $time, // 몇시간 일했는지를 계산한 값
                    "time_range" => $date_range, // 시작시간~종료시간
                    "id" => $list->id, // 해당자료의 디비 id
                    "social_activity_support_time" => $list->social_activity_support,
                    "physical_activity_support_time" => $list->physical_activity_support,
                    "housekeeping_activity_support_time" => $list->housekeeping_activity_support,
                    "etc_support_time" => $list->etc_service,
                    "business_type" => $list->business_type
                ];

                // 해당일의 공휴일 결제시간이 8시간이 넘었다면 넘은 결제시간만큼 over_time에 넣어줌. 마찬가지로 절대적인 거 아님.
                if ($timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] > 8) {
                    $timeDiff[$list->provider_key]["holiday"][$date_ymd]["over_time"] = $timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] - 8;
                }

                // 휴일 근무시간 계산해서 총합구하기
                $timeDiff[$list->provider_key]["holiday_time_total"] += $work_once_time_total;

                // 휴일 근무시간이 8시간이 넘는다면 휴일연장근무 실행
                if ($timeDiff[$list->provider_key]["holiday_time_total"] > 8) {

                    // 휴일근무시간 - 8시간해서 휴일연장근무시간 구하기
                    $timeDiff[$list->provider_key]["holiday_over_time_total"] = $timeDiff[$list->provider_key]["holiday_time_total"] - 8;

                    // 휴일연장근무 급여 구하기 (휴일연장시간 * ((0.5*비율) * 기본시급))
                    $timeDiff[$list->provider_key]["holiday_over_time_pay_total"] =
                        $timeDiff[$list->provider_key]["holiday_over_time_total"]
                        * $holiday_over_time_wage;
                }

                // 휴일근무 급여계산하기
                $timeDiff[$list->provider_key]["holiday_pay_total"] =
                    $timeDiff[$list->provider_key]["holiday_time_total"]
                    * $holiday_wage;

                // 휴일근무급여 + 휴일연장근무급여 구하기
                $timeDiff[$list->provider_key]["holiday_pay_add_over_time_pay_total"] =
                    $timeDiff[$list->provider_key]["holiday_pay_total"]
                    + $timeDiff[$list->provider_key]["holiday_over_time_pay_total"];


                // 광역,시도군구비인지 체크하기
                $btcheck = "";

                if (in_array($list->business_type, $business_types["country"]))
                {
                    $btcheck = "_COUNTRY";
                }
                else if (in_array($list->business_type, $business_types["city"]))
                {
                    $btcheck = "_CITY";
                }
                else if (in_array($list->business_type, $business_types["local"]))
                {
                    $btcheck = "_LOCAL";
                }

                if (!isset($timeDiff[$list->provider_key][$btcheck]["DAY_WORK"][$date_ymd])) {
                    $timeDiff[$list->provider_key][$btcheck]["DAY_WORK"][] = $date_ymd;
                }

                if ($btcheck != "") {

                    $total_add = ($work_once_time_total * $holiday_wage);
                    $time_holiday_extra = 0;
                    $holiday_basic = $work_once_time_total;
                    // 당일 휴일연장이 됐다면


                    if ($timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] > 8) {
//                    if ($holiday_over_check) {

                        // 토탈-한줄이 8이하라면 (8+한줄)-토탈=일반시간
                        if ($timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] - $work_once_time_total < 9) {
                            $holiday_basic = (8+$work_once_time_total) - $timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"];
                        }


                        // 당일 총합에서 8을 빼서
                        $cal_holiday_overtime = $timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] - 8;

                        // 데이터에서 일한시간 보다 연장시간이 더 크다면 데이터에서 일한시간 전체에 휴일연장적용
                        if ($cal_holiday_overtime >= $work_once_time_total) {
                            $time_holiday_extra = $work_once_time_total;
                            $total_holiday_ext_add = ($time_holiday_extra * $holiday_over_time_wage);
                            $total_add += $total_holiday_ext_add;

                        // 연장인데 일한시간 < 연장시간이라면 총합-8=연장시간, 일한시간-연장시간=기본시간
                        } else {
                            $time_holiday_extra = $cal_holiday_overtime;
                            $total_holiday_ext_add = ($time_holiday_extra * $holiday_over_time_wage);
                            $total_add += $total_holiday_ext_add;
                        }
                    }
//                    else {
//
//                        if ($timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] + $work_once_time_total > 8) {
//                            $holiday_basic =
//                                -$timeDiff[$list->provider_key]["holiday"][$date_ymd]["total"] + 8;
//                        }
//
//                    }

                    $timeDiff[$list->provider_key][$btcheck]["PAYMENT_TOTAL"] +=
                        ($work_once_time_total * $holiday_wage)
                        + $time_holiday_extra * $holiday_over_time_wage;
                    $timeDiff[$list->provider_key][$btcheck]["PAYMENT_EXTRA"] +=
                        ($work_once_time_total * $holiday_wage)
                        + $time_holiday_extra * $holiday_over_time_wage;
                    $timeDiff[$list->provider_key][$btcheck]["PAYMENT_HOLIDAY"] += $work_once_time_total * $holiday_wage;
                    $timeDiff[$list->provider_key][$btcheck]["PAYMENT_HOLIDAY_EXTRA"] += $time_holiday_extra * $holiday_over_time_wage;
                    $timeDiff[$list->provider_key][$btcheck]["PAYMENT_HOLIDAY_TOTAL"] +=
                        $work_once_time_total * $holiday_wage
                        + ($time_holiday_extra * $holiday_over_time_wage);

                    $timeDiff[$list->provider_key][$btcheck]["TIME_HOLIDAY"] += $work_once_time_total;
                    $timeDiff[$list->provider_key][$btcheck]["TIME_HOLIDAY_EXTRA"] += $time_holiday_extra;

                }

            }

            /* 평일 근로 구하기 */
            else
            {

                // 평일+평일연장+평일야간 구하기
                $calcNightShift = self::CALC_WEEK_DAY_WORK_INFO($list->service_start_date_time, $list->service_end_date_time, $list->add_price);
                $nightShiftCheck = $calcNightShift['code'];
                $nightShiftData = $calcNightShift['data'];

                // 서비스 시작시간이 있다면
                if ($nightShiftCheck)
                {

                    if (!isset($timeDiff[$list->provider_key]["week_day"][$date_ymd])) {

                        $timeDiff[$list->provider_key]["week_day"][$date_ymd] = [
                            "payment_time_total" => 0,
                            "payment_time_over_total" => 0,
                            "night_shift_total" => 0, // 야간 토탈
                            "day_shift_total" => 0, // 일반 토탈
                            "over_time_total" => 0, // 연장 토탈 (하루 총합하면 바껴야 한다)
                            "detail" => [],
                        ];
                    }

                    // 해당일 평일근로 결제시간으로 총합구하기
                    $timeDiff[$list->provider_key]["week_day"][$date_ymd]["payment_time_total"] += $list->payment_time;

                    // 해당일 계산한 야간시간 총합구하기
                    $timeDiff[$list->provider_key]["week_day"][$date_ymd]["night_shift_total"] += half_round($nightShiftData['nightShift']);

                    // 해당일 계산한 일반근무 총합구하기
                    $timeDiff[$list->provider_key]["week_day"][$date_ymd]["day_shift_total"] += half_round($nightShiftData['dayShift']);

                    // 해당일 계산한 연장시간 총합구하기
//                    $timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] += half_round($nightShiftData['overTime']);

                    // 평일근로 결제시간 총합이 8시간이라면 연장시간으로 계산하기
                    if ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["payment_time_total"] > 8) {
                        $timeDiff[$list->provider_key]["week_day"][$date_ymd]["payment_time_over_total"] = $timeDiff[$list->provider_key]["week_day"][$date_ymd]["payment_time_total"] - 8;
                    }


                    // 계산한 주간근로시간 디테일 배열 만들기
                    $timeDiff[$list->provider_key]["week_day"][$date_ymd]["detail"][] = [
                        "payment_time" => $list->payment_time, // 해당자료의 결제시간
                        "night_shift_time" => $nightShiftData['nightShift'], // 해당자료의 야근시간
                        "day_shift_time" => $nightShiftData['dayShift'], // 해당자료의 주간시간
                        "daily_over_time" => $nightShiftData['overTime'], // 해당자료의 연장근무시간
                        "time_range" => $date_range, // 해당자료의 시간범위
                        "id" => $list->id, // 해당자료의 디비 id
                        "social_activity_support_time" => $list->social_activity_support,
                        "physical_activity_support_time" => $list->physical_activity_support,
                        "housekeeping_activity_support_time" => $list->housekeeping_activity_support,
                        "etc_support_time" => $list->etc_service,
                        "business_type" => $list->business_type
                    ];

                    // 계산한 기본시간, 기본급여 계산
                    $timeDiff[$list->provider_key]['basic_time_total'] +=
                        $timeDiff[$list->provider_key]["week_day"][$date_ymd]["day_shift_total"] > 8
                            ? 8
                            : $timeDiff[$list->provider_key]["week_day"][$date_ymd]["day_shift_total"];
                    $timeDiff[$list->provider_key]['basic_pay_total'] = $timeDiff[$list->provider_key]['basic_time_total'] * $hourly_wage;

                    // 계산한 야간시간, 야간급여 계산
                    $timeDiff[$list->provider_key]['night_time_total'] += $timeDiff[$list->provider_key]["week_day"][$date_ymd]["night_shift_total"];
                    $timeDiff[$list->provider_key]['night_pay_total'] = $timeDiff[$list->provider_key]['night_time_total'] * $night_time_wage;


                    if ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["day_shift_total"] > 8) {
                        // 계산한 연장근로시간, 연장근로급여계산

                        // 이미 오버타임에 존재하는 시간이 있다면 중복계산되는걸 방지. 가장마지막오버타임시간-기존오버타임시간
                        if ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] != 0) {
                            $timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] =
                                ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["day_shift_total"] - 8)
                                - $timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"];
                        } else {
                            $timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] =
                                $timeDiff[$list->provider_key]["week_day"][$date_ymd]["day_shift_total"] - 8;
                        }

                        $timeDiff[$list->provider_key]['over_time_time_total'] += $timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"];
                        $timeDiff[$list->provider_key]['over_time_pay_total'] = $timeDiff[$list->provider_key]['over_time_time_total'] * $over_time_wage;

                    }


                    // 광역,시도군구비인지 체크하기
                    $btcheck = "";

                    if (in_array($list->business_type, $business_types["country"]))
                    {
                        $btcheck = "_COUNTRY";
                    }
                    else if (in_array($list->business_type, $business_types["city"]))
                    {
                        $btcheck = "_CITY";
                    }
                    else if (in_array($list->business_type, $business_types["local"]))
                    {
                        $btcheck = "_LOCAL";
                    }

                    if (!isset($timeDiff[$list->provider_key][$btcheck]["DAY_WORK"][$date_ymd])) {
                        $timeDiff[$list->provider_key][$btcheck]["DAY_WORK"][] = $date_ymd;
                    }

                    if ($btcheck != "") {

                        $timeDiff[$list->provider_key][$btcheck]["PAYMENT_NORMAL"] +=
                            ($nightShiftData['dayShift'] * $hourly_wage);

                        $timeDiff[$list->provider_key][$btcheck]["PAYMENT_NORMAL_EXTRA"] +=
                            ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] * $over_time_wage);

                        $timeDiff[$list->provider_key][$btcheck]["PAYMENT_NORMAL_TOTAL"] +=
                            ($nightShiftData['dayShift'] * $hourly_wage)
                            + ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] * $over_time_wage);

                        $timeDiff[$list->provider_key][$btcheck]["PAYMENT_NIGHT"] +=
                            $nightShiftData['nightShift'] * $night_time_wage;

                        $timeDiff[$list->provider_key][$btcheck]["PAYMENT_EXTRA"] +=
                            $nightShiftData['nightShift'] * $over_time_wage
                            + ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] * $over_time_wage);

                        $timeDiff[$list->provider_key][$btcheck]["PAYMENT_TOTAL"] +=
                            ($nightShiftData['dayShift'] * $hourly_wage)
                            + ($timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"] * $over_time_wage)
                            + $nightShiftData['nightShift'] * $night_time_wage;


                        $timeDiff[$list->provider_key][$btcheck]["TIME_TOTAL"] +=
                            $nightShiftData['dayShift']
                            + $timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"]
                            + $nightShiftData['nightShift'];

                        $timeDiff[$list->provider_key][$btcheck]["TIME_NORMAL"] +=
                            $nightShiftData['dayShift'];

                        if ($list->business_type == "장애인활동지원") {
//                            echo half_round($nightShiftData['dayShift'])."<br>";
                        }

                        $timeDiff[$list->provider_key][$btcheck]["TIME_NORMAL_EXTRA"] +=
                            $timeDiff[$list->provider_key]["week_day"][$date_ymd]["over_time_total"];

                        $timeDiff[$list->provider_key][$btcheck]["TIME_NIGHT"] +=
                            $nightShiftData['nightShift'];

                    }
                }
            }
            // -- 공휴일, 평일 계산 end
        }


        $week_length = get_find_weeks_in_month(date("Y-m-d", strtotime($request->input("from_date"))))['week'];

        foreach ($timeDiff as $key => $diff) {

            $weekday_count = date("t", strtotime($from_date));

            /* ----------------------- 평일에 근무한 시간 다 합치기 ----------------------- */
            $week_day_time_total = 0;

            foreach ($diff['week_day'] as $i => $weekdays) {
                $week_day_time_total +=
                    $weekdays['night_shift_total']
                    + $weekdays['day_shift_total']
                    + $weekdays['over_time_total'];
            }
            $timeDiff[$key]['week_day_time_total'] = $week_day_time_total;
            // ----------------------- 평일에 근무한 시간 다 합치기 end -----------------------


            /* ----------------------- 주휴수당 구하기 ----------------------- */
            if ($request->input("week_pay_apply_check") == 1) {

                $weekly_allowance_check = false;

                switch ($request->input("week_pay_apply_type")) {

                    // 모든 지원사에게 적용
                    case "all" :
                        $weekly_allowance_check = true;
                        break;

                    // 월 60시간 이상 일한 지원사에게 적용
                    case "basic60" :
                        if ($diff['basic_time_total'] >= 60) {
                            $weekly_allowance_check = true;
                        }
                        break;

                    // 월 65시간 이상 일한 지원사에게 적용
                    case "basic65" :
                        if ($diff['basic_time_total'] >= 65) {
                            $weekly_allowance_check = true;
                        }
                        break;
                    default:
                        $weekly_allowance_check = false;
                        break;
                }

                if ($weekly_allowance_check) {

                    $weekly_weekday = $request->input("week_pay_selector");

                    $timeDiff[$key]['pay_weekly_allowance'] =
                        ($timeDiff[$key]['week_day_time_total']
                        - $timeDiff[$key]['over_time_time_total'])
                        / $weekly_weekday
                        * $hourly_wage;

                }

            }
            /* ----------------------- 주휴수당 구하기 end ----------------------- */

            /* ----------------------- 연차수당 구하기 ----------------------- */
            $now = now();
            $join_date = now();
            if ($diff['join_date'] != "") {
                // 현재는 지원사-서비스이용내역 연동이 되는 데이터가 없기때문에 임시 입사일을 줬다
                $join_date = new Carbon($diff['join_date']);
//                $join_date = new Carbon("2016-01-01 22:11:22");
            }

            // 입사한지 1년이 지났는지 확인
            $join_less_than_1_year_check = $join_date->diff($now)->format("%Y");

            $annual_leave_compare = false;

            // 1년이 지났다면 true
            if ($join_less_than_1_year_check >= 1) {
                $annual_leave_compare = true;
            }

            // 입사한 지 1년이 지났다면 - 연차수당계산
            if ($annual_leave_compare) {

                $anuual_allowance_check = false;

                // 연차수당 계산 적용이라면
                if ($request->input("year_pay_apply_check") == 1) {

                    switch ($request->input("year_pay_apply_type")) {
                        case "all" :
                            $anuual_allowance_check = true;
                            break;
                        case "basic60" :
                            if ($diff['basic_time_total'] >= 60) {
                                $anuual_allowance_check = true;
                            }
                            break;
                        case "basic65" :
                            if ($diff['basic_time_total'] >= 65) {
                                $anuual_allowance_check = true;
                            }
                            break;
                        default:
                            $anuual_allowance_check = false;
                            break;
                    }
                }

                // 시간 선택에 해당한다면 연차수당 계산해주기
                if ($anuual_allowance_check) {

                    $annual_weekday = $request->input("year_pay_selector");

                    // 연차수당 = 기본시간
                    $timeDiff[$key]['pay_annual_paid_allowance'] = (
                            ($timeDiff[$key]['basic_time_total']
                                / (365 / 7 / 12) / $annual_weekday * 15 / 12)
                            * $hourly_wage) * $annual_wage;
                }

            }
            // 1년 미만자일 경우 - 연차수당 구하기
            else if (!$annual_leave_compare) {

                $annual_allowance_check = false;

                // 1년 미만자 연차 수당 계산 적용에 체크했다면
                if ($request->input("one_year_less_annual_pay_check") == 1) {

                    switch ($request->input("one_year_less_annual_pay_type")) {
                        case "all" :
                            $annual_allowance_check = true;
                            break;
                        case "basic60" :
                            if ($diff['basic_time_total'] >= 60) {
                                $annual_allowance_check = true;
                            }
                            break;
                        case "basic65" :
                            if ($diff['basic_time_total'] >= 65) {
                                $annual_allowance_check = true;
                            }
                            break;
                        default:
                            $annual_allowance_check = false;
                            break;
                    }


                    // 1년 미만자 연차 계산하기
                    if ($annual_allowance_check) {

                        // 주5일, 주6일 부분
                        $one_less_annual_weekday = $request->input("one_year_less_annual_pay_selector");

                        // 1년 미만자 연차수당 = 기본시간/(365/7/12)/5or6*11/12 * 시급 * 비율
                        $timeDiff[$key]['pay_annual_paid_allowance'] =
                            ((($timeDiff[$key]['basic_time_total'] / (365 / 7 / 12) / $one_less_annual_weekday * 11 / 12)
                            * $hourly_wage) / 100) * $one_less_annual_wage;
                    }
                }
            }
            /* ----------------------- 연차수당 구하기 end ----------------------- */


            // 공휴일수당, 근로자의날 수당 날짜계산할때 시작일 설정(20xx-xx-01)
            $public_day_start_date = $from_date;

            // 검색날짜보다 입사일이 더 최근이라면
            if (strtotime($public_day_start_date) < strtotime($diff['join_date'])) {
                // 시작일이 1일이 아니라 입사일로 설정.
                $public_day_start_date = date("Y-m-d", strtotime($diff['join_date']));

                // 월말일 설정
                $last_day = date("Y-m-d", strtotime($request->input("from_date")."-{$weekday_count}"));

                // 시작일과 월말일 며칠차이인지
                $weekday_count = carbon_date_diff($public_day_start_date, $last_day)->d + 1;
            }


            /* 주5일, 주6일 계산할때 사용 */
            // 한달의 토요일이 몇번인지
            $allSaturday = total_weekday_count_at_month(
                date("d", strtotime($public_day_start_date)),
                date("m", strtotime($public_day_start_date)),
                date("Y", strtotime($public_day_start_date)),
                6
            );

            // 한달의 일요일이 몇번인지
            $allSunday = total_weekday_count_at_month(
                date("d", strtotime($public_day_start_date)),
                date("m", strtotime($public_day_start_date)),
                date("Y", strtotime($public_day_start_date)),
                7
            );

            // 공휴일 혹은 주휴일이 토요일과 겹친 수
            $duplicateSaturdayHoliday = workers_holiday_not_weekend($diff['provider_holiday_list'], 1);
            $duplicateSundayHoliday = workers_holiday_not_weekend($diff['provider_holiday_list'], 2);


            /* ------------------- 공휴일, 주휴일 출근안해도 받는 기본 수당 구하기 ------------------- */
            $public_holiday_allownace_compare = false;

            // 공휴일 수당 계산에 체크 했다면
            if ($request->input("public_allowance_check") == 1) {
                $public_holiday_allownace_compare = true;
            }

            // 반드시 평일에 1시간 이상 근무한 이력이 있어야 함. 오류남
            if ($timeDiff[$key]['basic_time_total'] <= 0) {
                $public_holiday_allownace_compare = false;
            }

            // 공휴일 수당 계산 적용됐다면
            if ($public_holiday_allownace_compare)
            {
                // 주 6일이라면 한달의 일요일만 제외
                if ($request->input("public_allowance_day_selector") == 6) {
                    $allSaturday = 0;
                    $duplicateSaturdayHoliday = 0;
                }

                // 한달의 주5일, 주6일 선택에 따른 평일 수
                // 한달 일수 - ( 토요일 수 + 일요일 수) - ( 공휴일+주휴일 - 공휴일주휴일인데 토요일 수 - 공휴일주휴일인데 일요일 수 )
                $weekday_count = $weekday_count - ($allSaturday + $allSunday)
                    - (count($diff['provider_holiday_list']) - $duplicateSaturdayHoliday - $duplicateSundayHoliday);
                $timeDiff[$key]['holiday_basic_not_work_weekday_count'] = $weekday_count;

                // 한달의 평균 근로 시간 = 평일근로시간총합 / 평일일수
                $timeDiff[$key]['week_day_daily_work_average'] =
                    $timeDiff[$key]['basic_time_total'] / $weekday_count;

                // 공휴일수당 = (기본시간 * 기본시급) * 공휴일 수 / 비율
                $timeDiff[$key]['holiday_basic_not_work'] =
                    ((($timeDiff[$key]['week_day_daily_work_average'] * $hourly_wage)
                    * count($diff['provider_holiday_list']))) * $public_holiday_wage;
            }
             /* ------------------- 공휴일 출근안해도 받는 기본수당 end ------------------- */


            /* ------------------- 근로자의 날 수당계산 ------------------- */
            $workers_day_check = false;

            // 근로자의 날 수당계산 적용 체크되있다면
            if ($request->input("workers_day_allowance_check") == 1) {
                $workers_day_check = true;
            }

            // 5월달이 아니면 false
            if (date("m", strtotime($from_date)) != 5) {
                $workers_day_check = false;
            }

            if ($workers_day_check)
            {

                // 주 6일이라면 한달의 토요일 제외
                 if ($request->input("workers_day_allowance_day_selector") == 6) {
                    $allSaturday = 0;
                     $duplicateSaturdayHoliday = 0;
                }

                // 한달의 주5일, 주6일 선택에 따른 평일 수
                // 한달 일수 - ( 토요일 수 + 일요일 수) - ( 공휴일+주휴일 - 공휴일주휴일인데 토요일 수 - 공휴일주휴일인데 일요일 수 )
                $weekday_count = $weekday_count - ($allSaturday + $allSunday)
                    - (count($diff['provider_holiday_list']) - $duplicateSaturdayHoliday - $duplicateSundayHoliday);
                $timeDiff[$key]['weekday_count'] = $weekday_count;

                $timeDiff[$key]['workers_day_weekday_count'] = $weekday_count;

                // 한달의 평균 근로 시간 = 평일근로시간총합 / 평일일수(주5,주6선택)
                $timeDiff[$key]['week_day_daily_work_average'] =
                    $timeDiff[$key]['basic_time_total'] / $weekday_count;

                // 근로자의 날 = (기본시간 * 기본시급) * 공휴일 수 / 비율
                $timeDiff[$key]['workers_day_allowance'] =
                    ((($timeDiff[$key]['week_day_daily_work_average'] * $hourly_wage)
                            * count($diff['provider_holiday_list'])) / 100) * $workers_day_wage;

            }

            /* ------------------- 근로자의 날 수당계산 end ------------------- */


            $timeDiff[$key]['calc_time_basic'] =
                $diff['_COUNTRY']['TIME_NORMAL']
                + $diff['_COUNTRY']['TIME_NIGHT']
                + $diff['_CITY']['TIME_NORMAL']
                + $diff['_CITY']['TIME_NIGHT'];

            $timeDiff[$key]['calc_payment_basic'] = $timeDiff[$key]['calc_time_basic'] * get_wage_data()['minimum_wage'];

            $timeDiff[$key]['calc_time_extra'] =
                $diff['_COUNTRY']['TIME_NORMAL_EXTRA']
                + $diff['_CITY']['TIME_NORMAL_EXTRA'];

            $timeDiff[$key]['calc_payment_extra'] =
                $diff['_COUNTRY']['TIME_NORMAL_EXTRA']
                + $diff['_CITY']['TIME_NORMAL_EXTRA'];


            // 적용 합계
            $timeDiff[$key]['_PAY_'] =
                $timeDiff[$key]['calc_payment_basic']
                + ($timeDiff[$key]['over_time_time_total'] * $request->input("pay_hour") * (1.5/100 * $request->input('pay_over_time')))
                + ($timeDiff[$key]['holiday_time_total'] * $request->input("pay_hour") * (1.5/100 * $request->input('pay_holiday')))
                + ($timeDiff[$key]['night_time_total'] * $request->input('pay_hour') * (0.5/100*$request->input('pay_night')))
                + $timeDiff[$key]['pay_weekly_allowance']
                + $timeDiff[$key]['pay_annual_paid_allowance'];

            // 근로기준법 지급총액구하기
            $timeDiff[$key]['TOTAL_PAYMENT'] = $timeDiff[$key]['voucher_payment']['_COUNTRY']['PAYMENT_TOTAL']
                + $timeDiff[$key]['voucher_payment']['_CITY']['PAYMENT_TOTAL'];

            // 갑근세 구하기
            $CLASS_A_WAGES = self::CALC_CLASS_A_WAGES([
                "payment" =>  $timeDiff[$key]['TOTAL_PAYMENT'],
                "target_id" => $key
            ]);

            $timeDiff[$key]['class_a_wages'] = $CLASS_A_WAGES['data'];
            $timeDiff[$key]['resident_tax'] = round($timeDiff[$key]['class_a_wages'] * 0.1, 1);
        }


        return [ "diff"=>$timeDiff /*"week_length"=>count($week_length)*/ ];
    }


    private static function CALC_VOUCHER_PAYMENT($datas)
    {
        $request = $datas["request"];
        $list = $datas["lists"];
        $business_types = $datas["business_types"];
        $voucher_payment = $datas["voucher_payment"];
        $start = $datas['start'];
        $end = $datas['end'];


        $calType = $request->input("voucher_pay_total") == 1 ? "fix" : "proportion";

        // 국가에서 정한 시간당단가
        $public_price_per_hour = 13500;
        $basic_payment_percentage = 0.764; // 기본지급비율 76.4%

        $bt = "";

        if (in_array($list->business_type, $business_types["country"])) $bt = "_COUNTRY";
        else if (in_array($list->business_type, $business_types["city"])) $bt = "_CITY";
        else if (in_array($list->business_type, $business_types["local"])) $bt = "_LOCAL";

        $voucher_payment[$bt]['DAY'][date("Y-m-d", strtotime($list->confirm_date))] = 1;

//        echo $voucher_payment[$bt]['TIME_NORMAL'] . "첫<br>";
        $voucher_payment[$bt]['TIME_NORMAL'] += (float) $list->payment_time; // 결제시간

        if ($list->add_price != 0) {

            $calc = self::CALC_WEEK_DAY_WORK_INFO($start, $end, $list->add_price);

            if (self::is_holiday([
                "user_id" => self::$user_id,
                "list" => $list,
                "start_date" => $list->service_start_date_time,
                "end_date" => $list->service_end_date_time
            ])) {
                $voucher_payment[$bt]['TIME_HOLIDAY'] += (float) $calc['data']['nightShift']; // 휴일시간...구해야한다
            }

            $voucher_payment[$bt]['TIME_NIGHT'] += (float) $calc['data']['nightShift']; // 휴일시간...구해야한다
        }

        $voucher_payment[$bt]['TIME_TOTAL'] += (float) $list->payment_time;
        $voucher_payment[$bt]['PAYMENT_NORMAL'] += $list->confirm_pay; // 승인금액
//        $divide[$bt]['PAYMENT_EXTRA'] += $list->add_price; // 가산금액
        $voucher_payment[$bt]['PAYMENT_EXTRA_BEFORE_CALC'] += $list->add_price; // 가산금액
//        $divide[$bt]['PAYMENT_TOTAL'] += (float) $list->confirm_pay; // 가산금은 승인금액에 포함됨
        if ($list->return_sort != "") {
            $voucher_payment[$bt]['RETURN_DAY'][date("Y-m-d", strtotime($list->confirm_date))] = 1;
            $voucher_payment[$bt]['RETURN_TIME'] += $list->payment_time;
            $voucher_payment[$bt]['RETURN_PRICE'] += $list->confirm_pay;
        }

        switch ($calType)
        {
            case "fix":
                // 휴일수당 고정값
                $percentage = ($request->input("voucher_holiday_pay_fixing") / $public_price_per_hour) * 100;
                $voucher_payment[$bt]['PAYMENT_EXTRA'] += ($list->add_price / 100) * $percentage;
                $voucher_payment[$bt]['PAYMENT_TOTAL'] +=
                    (($list->add_price / 100) * $percentage)
                    + $list->confirm_pay;

                // (총시간-가산시간) * 해당년도바우처단가 + 기타청구합계금액 * 기본지급비율 + ((가산시간) * 휴일수당고정액)
                $voucher_payment[$bt]['PAYMENT_RESULT'] =
                    round(($voucher_payment[$bt]['TIME_TOTAL'] - 0 - 0)
                    * $public_price_per_hour + 0)
                    * $basic_payment_percentage
                    + (($voucher_payment[$bt]['TIME_HOLIDAY'] + $voucher_payment[$bt]['TIME_NIGHT'])
                    * $request->input("voucher_holiday_pay_fixing"));
                break;
            case "proportion":
                // 휴일수당 비례값 : 퍼센티지구해서 전체값에 퍼센트 적용
                $percentage = $request->input("voucher_holiday_pay_hour_per_price") / $public_price_per_hour * 100;
                $voucher_payment[$bt]['PAYMENT_TOTAL'] = ($voucher_payment[$bt]['PAYMENT_NORMAL'] / 100) * $percentage;
                break;
        }

        return $voucher_payment;
    }


    // 파라미터 Y-m-d H:i:s 까지
    public static function CALC_WEEK_DAY_WORK_INFO($start, $end, $add_price=0)
    {
        $start = date("Y-m-d H:i:s", strtotime($start));
        $end = date("Y-m-d H:i:s", strtotime($end));

        if (strtotime($start) >= strtotime($end))
        {
            return [ "code" => false, "msg" => "ERROR_CODE::start time is bigger...", "data"=> [] ];
        }

        $start_date_check = date("Y-m-d", strtotime($start));
        $end_date_check = date("Y-m-d", strtotime($end));

        // 1이면 시작일이 전날 10~12시사이, 2면 시작일이 다음날 0~6시
        $day_check = 1;

        $first_day_start_date_time = date("Y-m-d H:i:s", strtotime($start_date_check." 00:00:00"));
        $first_day_end_date_time = date("Y-m-d H:i:s", strtotime($start_date_check." 06:00:00"));

        // 시작일이 00시보다 크고 06시보다 작을때 => 다음날 스타트
        if (strtotime($start) >= strtotime($first_day_start_date_time)
            && strtotime($start) <= strtotime($first_day_end_date_time)
        )
        {
            $day_check = 2;
        }


        $start_date_time = strtotime($start);
        $end_date_time = strtotime($end);

        $night_day = $start_date_check;
        $night_next_day = date("Y-m-d", strtotime($night_day . "+1 days"));
        $night_prev_day = date("Y-m-d", strtotime($night_day . "-1 days"));

        if ($day_check == 2) {
            $night_next_day = $start_date_check;
        }

        $night_start_date_time = date("Y-m-d H:i:s", strtotime($night_day. " 22:00:00"));
        $night_end_date_time = date("Y-m-d H:i:s", strtotime($night_next_day. " 06:00:00"));

        if ($day_check == 2) {
            $night_start_date_time = date("Y-m-d H:i:s", strtotime($night_prev_day. " 22:00:00"));
        }

        // 나이트근무인지 아닌지 체크하기
        $check = false;
        $nightShift = 0;
        $dayShift = 0;
        $overTime = 0;

        $start_carbon = new \Illuminate\Support\Carbon($start);
        $end_carbon = new \Illuminate\Support\Carbon($end);

        // 가산금액이 0이 아닐때 => 즉, 연장 혹은 야간이 포함된 경우
        if ($add_price != 0) {
            // (오후10시 < 시작시간 && 다음날오전6시 >= 종료시간) => 야간시작 ~ 야간종료
            if (strtotime($night_start_date_time) <= strtotime($start)
                && strtotime($night_end_date_time) >= strtotime($end))
            {
                $check = true;
                $nightShift = time_diff_return_hour($start, $end);
            }

            // (오후10 > 시작시작 && 오후10 < 종료시간) => 평일시작 ~ 평일 끝
            else if (strtotime($night_start_date_time) > strtotime($start)
                && strtotime($night_start_date_time) < strtotime($end))
            {
                $check = true;

                // 오후10시보다 작은 경우니까 시작시간과 오후10시의 차이를 구한다 = 일반근로시간
                $dayShift = time_diff_return_hour($start, $night_start_date_time);

                // 오전6시보다 야간종료시간이 적다면 22시~야간종료시간 차이 구하기
                if (strtotime($end) <= strtotime($night_end_date_time)) {
                    $nightShift = time_diff_return_hour($night_start_date_time, $end);

                    // 오전 6시 < 야간종료시간 이면 22시~6시 = 야간근무시간, 6시~야간종료시간 += 주간근무시간(연장근로 생각해야하는지?)
                } else {
                    // 22시~6시 = 무조건 8시간
                    $nightShift = time_diff_return_hour($night_start_date_time, $night_end_date_time);

                    // 이 경우엔 야간은 무조건 8시간이기 때문에 6시 이후에도 더 일을하게되면 연장근무가 된다
                    // 연장근무로 계산하면될 지, 그냥 일반으로 계산할지 물어보기
//                    $dayShift += time_diff_return_hour($night_end_date_time, $end);
//                $overTime = time_diff_return_hour($night_end_date_time, $end);

                }
            }

            // (오후10시 <= 시작시간 && 다음날오전6시 > 시작시간 && 오후10시 < 종료시간)
            else if (strtotime($night_start_date_time) <= strtotime($start)
                && strtotime($night_end_date_time) > strtotime($start)
                && strtotime($night_start_date_time) < strtotime($end))
            {
                $check = true;

                // 종료시간 <= 오전6시면 일반근무없음
                if (strtotime($end) <= strtotime($night_end_date_time)) {
                    $nightShift = time_diff_return_hour($start, $end);

                    // 종료시간 > 오전6시면 오전6시~종료시간이 일반근무
                } else {
                    $nightShift = time_diff_return_hour($start, $night_end_date_time);
                    $dayShift = time_diff_return_hour($night_end_date_time, $end);

                    // 만약 야간근무시간이 최대시간인 8시간이라면 야간종료~종료시간까지는 연장근무시간
                    if ($nightShift >= 8) {
//                    $overTime = time_diff_return_hour($night_end_date_time, $end);
                        // 야간이 8시간이 아니라면 그냥 일반근무시간
                    } else {
                        $dayShift = time_diff_return_hour($night_end_date_time, $end);

                        // 야간+일반시간이 8시가 넘는다면 넘은 시간만큼 연장근무시간, 일반근무=일반근무-연장근무
                        if ($nightShift + $dayShift > 8) {
//                        $overTime = ($nightShift + $dayShift) - 8;
//                        $dayShift -= $overTime;
                        }
                    }
                }
            }

            // 평일일때 당일06시 <= 시작시간 <= 당일 22시, 당일 06시 <= 종료시간 <= 당일 22시
//        else if (strtotime($start_date_check." 22:00:00") >= strtotime($start)
//            && strtotime($start_date_check . "06:00:00") <= strtotime($start)
//            && strtotime($start_date_check." 22:00:00") >= strtotime($end)
//            && strtotime($start_date_check." 06:00:00") <= strtotime($end)
//        )
            else
            {
                $dayShift = time_diff_return_hour($start, $end);
                if ($dayShift > 8) {
//                $overTime = $dayShift - 8;
//                $dayShift -= $overTime;
                }
            }

        // 가산금액이 0일때 => 즉, 오로지 평일근무
        } else {

            // 오후10시보다 작은 경우니까 시작시간과 오후10시의 차이를 구한다 = 일반근로시간
            $dayShift = time_diff_return_hour($start, $end);

        }


        return [
            "code" => 1,
            "msg" => "success...!",
            "data" => [
                "nightShift" => $nightShift,
                "dayShift" => $dayShift,
                "overTime" => $overTime
            ]
        ];
    }


    public static function setWorkData($data) {

        $list = $data['list'];
        $holiday_list = $data['holiday_list'];
        $join_date = $data['join_date'];
        $resign_date = $data['resign_date'];

        return [

            "TOTAL_PAYMENT" => 0, // 근로기준법 지급총액구하기

            "class_a_wages" => 0, // 갑근세
            "resident_tax" => 0, // 주민세

            "voucher_payment" => [

                "_COUNTRY" => [
                    "DAY" => [],
                    "TIME_TOTAL" => 0,
                    "TIME_NORMAL" => 0,
                    "TIME_HOLIDAY" => 0,
                    "TIME_NIGHT" => 0,
                    "TIME_EXTRA" => 0,
                    "PAYMENT_TOTAL" => 0,
                    "PAYMENT_NORMAL" => 0,
                    "PAYMENT_EXTRA" => 0,
                    "PAYMENT_EXTRA_BEFORE_CALC" => 0,
                    "PAYMENT_RESULT" => 0,
                    "RETURN_PRICE" => 0,
                    "RETURN_DAY" => [],
                    "RETURN_TIME" => 0,
                ],
                "_CITY" => [
                    "DAY" => [],
                    "TIME_TOTAL" => 0,
                    "TIME_NORMAL" => 0,
                    "TIME_HOLIDAY" => 0,
                    "TIME_NIGHT" => 0,
                    "TIME_EXTRA" => 0,
                    "PAYMENT_TOTAL" => 0,
                    "PAYMENT_NORMAL" => 0,
                    "PAYMENT_EXTRA" => 0,
                    "PAYMENT_EXTRA_BEFORE_CALC" => 0,
                    "PAYMENT_RESULT" => 0,
                    "RETURN_PRICE" => 0,
                    "RETURN_DAY" => [],
                    "RETURN_TIME" => 0,
                ],
                "_LOCAL" => [
                    "DAY" => [],
                    "TIME_TOTAL" => 0,
                    "TIME_NORMAL" => 0,
                    "TIME_HOLIDAY" => 0,
                    "TIME_NIGHT" => 0,
                    "TIME_EXTRA" => 0,
                    "PAYMENT_TOTAL" => 0,
                    "PAYMENT_NORMAL" => 0,
                    "PAYMENT_EXTRA" => 0,
                    "PAYMENT_EXTRA_BEFORE_CALC" => 0,
                    "PAYMENT_RESULT" => 0,
                    "RETURN_PRICE" => 0,
                    "RETURN_DAY" => [],
                    "RETURN_TIME" => 0,
                ],
            ],

            "provider_holiday_list" => $holiday_list,
            "weekday_count"=>0,

            "provider_key" => $list->provider_key,
            "provider_name" => $list->provider_name ?? "",
            "provider_birth" => $list->provider_birth ?? "",
            "provider_reg_check" => $join_date != "" ? "Y" : "N",
            "join_date" => $join_date, // 입사일
            "resign_date" => $resign_date,
            "holiday" => [], // 당월의 공휴일에 일한 기록

            "total_hour" => 0,
            "week_day" => [], // 평일 근무
            "week_day_time_total" => 0, // 평일근무시간합계 (연장,야근 다 포함)
            "week_day_pay_total" => 0, // 평일급여합계 (연장,야근 다 포함하지만 기본시급으로 계산)
            "week_day_work_count" => 0, // 평일근무한 날짜합계 (연장,야근 다 포함)
            "week_day_daily_work_average" => 0, // 평일 하루 근무 시간 평균

            "_PAY_" => 0, // 공휴일(휴일연장)+평일(야근,연장) 총합

            "basic_time_total" => 0, // 기본시간 합계
            "basic_pay_total" => 0, // 기본시간 급여 합계

            "over_time_time_total" => 0, // 평일연장근로시간
            "over_time_pay_total" => 0, // 평일연장근로급여

            "holiday_time_total" => 0, // 공휴일 근무 시간 총합
            "holiday_pay_total" => 0, // 공휴일 일반급여 총합
            "holiday_basic_not_work" => 0, // 공휴일 근무안해도 주는 공휴일기본급여
            "holiday_count" => 0, // 이번달에 공휴일에 몇번 일했는지

            "holiday_over_time_total" => 0, // 휴일 연장 근로 시간 합계
            "holiday_over_time_pay_total" => 0, // 휴일 연장 근로 급여 합계

            "holiday_pay_add_over_time_pay_total" => 0, // 휴일근무 + 휴일연장근무 급여 총합

            "pay_weekly_allowance" => 0, // 주휴수당
            "pay_annual_paid_allowance" => 0, // 연차수당
            "workers_day_allowance" => 0, // 근로자의 날 수당

            "night" => [],
            "night_time_total" => 0,
            "night_pay_total" => 0,

            "week_time_average" => 0, // 주 평균 활동시간

            "_COUNTRY" => [ // 국비
                "DAY_WORK" => [],
                "PAYMENT_TOTAL" => 0, // 승인금액
                "PAYMENT_NORMAL" => 0, // 기본급
                "PAYMENT_NORMAL_EXTRA" => 0, // 기본연장급여
                "PAYMENT_NORMAL_TOTAL" => 0, // 기본급 + 기본연장급
                "PAYMENT_HOLIDAY" => 0, // 휴일급여
                "PAYMENT_HOLIDAY_EXTRA" => 0, // 휴일연장급여
                "PAYMENT_HOLIDAY_TOTAL" => 0, // 휴일급여+휴일연장급여
                "PAYMENT_NIGHT" => 0, // 야간급여
                "PAYMENT_EXTRA" => 0, // 가산금
                "TIME_TOTAL" => 0, // 총 시간
                "TIME_NORMAL" => 0, // 기본시간
                "TIME_NORMAL_EXTRA" => 0, // 평일연장시간
                "TIME_HOLIDAY" => 0, // 휴일 시간
                "TIME_HOLIDAY_EXTRA" => 0, // 휴일연장시간
                "TIME_NIGHT" => 0, // 야간 시간
            ],
            "_CITY" => [ // 도비,시군구비  * 현재는 두개 다 합쳐놓음
                "DAY_WORK" => [],
                "PAYMENT_TOTAL" => 0, // 승인금액
                "PAYMENT_NORMAL" => 0, // 기본급
                "PAYMENT_NORMAL_EXTRA" => 0, // 기본연장급여
                "PAYMENT_NORMAL_TOTAL" => 0, // 기본급 + 기본연장급
                "PAYMENT_HOLIDAY" => 0, // 휴일급여
                "PAYMENT_HOLIDAY_EXTRA" => 0, // 휴일연장급여
                "PAYMENT_HOLIDAY_TOTAL" => 0, // 휴일급여+휴일연장급여
                "PAYMENT_NIGHT" => 0, // 야간급여
                "PAYMENT_EXTRA" => 0, // 가산금
                "TIME_TOTAL" => 0, // 총 시간
                "TIME_NORMAL" => 0, // 기본시간
                "TIME_NORMAL_EXTRA" => 0, // 평일연장시간
                "TIME_HOLIDAY" => 0, // 휴일 시간
                "TIME_HOLIDAY_EXTRA" => 0, // 휴일연장시간
                "TIME_NIGHT" => 0, // 야간 시간
            ],

            "_LOCAL" => [ // 시군구비
                "DAY_WORK" => [],
                "PAYMENT_TOTAL" => 0, // 승인금액
                "PAYMENT_NORMAL" => 0, // 기본급
                "PAYMENT_NORMAL_EXTRA" => 0, // 기본연장급여
                "PAYMENT_NORMAL_TOTAL" => 0, // 기본급 + 기본연장급
                "PAYMENT_HOLIDAY" => 0, // 휴일급여
                "PAYMENT_HOLIDAY_EXTRA" => 0, // 휴일연장급여
                "PAYMENT_HOLIDAY_TOTAL" => 0, // 휴일급여+휴일연장급여
                "PAYMENT_NIGHT" => 0, // 야간급여
                "PAYMENT_EXTRA" => 0, // 가산금
                "TIME_TOTAL" => 0, // 총 시간
                "TIME_NORMAL" => 0, // 기본시간
                "TIME_NORMAL_EXTRA" => 0, // 평일연장시간
                "TIME_HOLIDAY" => 0, // 휴일 시간
                "TIME_HOLIDAY_EXTRA" => 0, // 휴일연장시간
                "TIME_NIGHT" => 0, // 야간 시간
            ],

            // 제공자 법정 지급항목 시뮬레이션
            "calc_time_basic" => 0, // 기본급 시간 (평일+평일야간)
            "calc_payment_basic" => 0, // 기본급 금액 (시간*최저임금)

            "calc_time_extra" => 0, // 연장수당 시간 (휴일+평일야간)
            "calc_payment_extra" => 0, // 연장수당 금액 (평일+평일야간)



            "weekly_activity_time_average" => 0, // 주 평균 활동시간(시간)

            "basic_pay" => 0, // 기본급(원)

            "annual_leave_allowance" => 0, // 연월차수당
            "statutory_allowance" => 0,
            "bojeon_allowance" => 0,

            "payment_total" => 0, // 결제금액합계(원)

            "holiday_8_over_allowance" => 0, // 휴일8시간 초과수당(원)
            "public_holiday_allowance" => 0, // 공휴일수당
            "severe_add_allowance" => 0, // 중증가산수당
            "long_distance_extra_pay" => 0, // 원거리교통비

            "national_pension" => 0, // 국민연금
            "public_health" => 0, // 건강보험
            "long_term_care_ins" => 0, // 장기요양보험
            "employ_ins" => 0, // 고용보험,
            "income_tax" => 0, // 소득세
            "local_income_tax" => 0, // 지방소득세

            "dongle_price" => 0, // 동글이금액?
            "truth_salary" => 0, // 실급여액
            "save_retiring_allowance" => 0, // 퇴직적립금

        ];
    }

    // 갑근세 구하기
    public static function CALC_CLASS_A_WAGES($data)
    {
        $target_id = $data['target_id'];
        $payment = $data['payment'];

        $provider_info = DB::table("workers")
            ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
            ->where("workers.user_id", "=", self::$user_id)
            ->where("workers.target_id", "=", $target_id)
            ->first();


        if (!$provider_info) {
            return [ "code" => 3, "msg" => "활동지원사 정보가 없습니다", "data" => 0 ];
        }

        // 지급총액의 세율을 간이세액표에서 부양가족수에 따라 가져온다
        $getSimplifiedTax = self::getSimplifiedTax([
            "familyCount" => $provider_info->dependents <= 11 ?: 11,
            "payment" => $payment
        ]);

        $class_a_wages = $getSimplifiedTax * 1; // 1은 갑근세율100%. 회원마다 다를 수 있다. 나중에 적용하기

        return [
            "code" => 1,
            "msg" => "success...!",
            "data" => $class_a_wages
        ];
    }


    // 간이세액표
    public static function getSimplifiedTax($data)
    {
        $familyCount = $data['familyCount'] ?? 1;

        $payment = $data['payment'];

        $columns = [
            "0번인덱스비우기^_^",
            "dependents1",
            "dependents2",
            "dependents3",
            "dependents4",
            "dependents5",
            "dependents6",
            "dependents7",
            "dependents8",
            "dependents9",
            "dependents10",
            "dependents11",
        ];

        $list = DB::table("simplified_tax_table")
            ->select()
            ->whereRaw("moreThan <= ? AND under > ?", [ $payment, $payment ])
            ->first();

        $list = collect($list)->toArray();
        return $list[$columns[$familyCount]];
    }

    public static function is_holiday($data)
    {
        $user_id = $data['user_id'];
        $list = $data['list'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $check = self::$check;


        $isWorker = Worker::getWorkerInfo([
            "user_id"=>$user_id,
            "provider_key"=>$list->provider_key
        ]);

        // 활동지원사의 해당 월의 주휴일, 공휴일 목록 가져온다. (입사일보다 나중일자만 가져옴)
        $getHelperHoliday = HelperSchedules::getHelperHolidayAtMonth([
            "user_id" => $user_id,
            "provider_key" => $list->provider_key,
            "start_date_time" => $start_date,
            "check" => $check,
            "join_date" => $isWorker->join_date
        ]);

        $holiday_list = [];
        if ($getHelperHoliday['code'] == 1) {
            $holiday_list = $getHelperHoliday['data'];
        }

        if (in_array(date("Y-m-d", strtotime($start_date)), $holiday_list)) {
            return true;
        }

        if (in_array(date("Y-m-d", strtotime($end_date)), $holiday_list)) {
            return true;
        }

        return false;
    }



}
