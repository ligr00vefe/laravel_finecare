<?php

namespace App\Http\Controllers;

use App\Classes\Builder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecalcSaveController extends Controller
{
    public function index(Request $request)
    {
        $user_id = User::get_user_id();

        $lists = json_decode($request->input("lists"));
        $conditions = json_decode($request->input("conditions")); // 급여계산할때 저장된 조건
        $request = json_decode($request->input("request")); // 재정산할때 선택한 재정산 조건

        $condition = new Builder();
        $condition->table("recalculate_conditions")
            ->upsert([
                "user_id" => $user_id,
                "target_ym" => $conditions->target_ym,
                ],
                [
                    "public_officers_holiday_check" => $conditions->public_officers_holiday_check,
                    "pay_per_hour" => $request->pay_per_hour,
                    "pay_hour" => $request->pay_hour,
                    "pay_over_time" => $request->pay_over_time,
                    "pay_holiday" => $request->pay_holiday,
                    "pay_holiday_over_time" => $request->pay_holiday_over_time,
                    "pay_night" => $request->pay_night,
                    "pay_annual" => $request->pay_annual,
                    "pay_one_year_less_annual" => $request->pay_one_year_less_annual,
                    "pay_public_holiday" => $request->pay_public_holiday,
                    "pay_workers_day" => $request->pay_workers_day,
                    "week_pay_apply_check" => $request->week_pay_apply_check,
                    "week_pay_apply_type" => $request->week_pay_apply_type,
                    "week_pay_selector" => $request->week_pay_selector,
                    "year_pay_apply_check" => $request->year_pay_apply_check,
                    "year_pay_apply_type" => $request->year_pay_apply_type,
                    "year_pay_selector" => $request->year_pay_selector,
                    "one_year_less_annual_pay_check" => $request->one_year_less_annual_pay_check,
                    "one_year_less_annual_pay_type" => $request->one_year_less_annual_pay_type,
                    "one_year_less_annual_pay_selector" => $request->one_year_less_annual_pay_selector,
                    "public_allowance_check" => $request->public_allowance_check,
                    "public_allowance_selector" => $request->public_allowance_selector,
                    "public_allowance_day_selector" => $conditions->public_allowance_day_selector,
                    "workers_day_allowance_check" => $request->workers_day_allowance_check,
                    "workers_day_allowance_day_selector" => $request->workers_day_allowance_day_selector,
                    "voucher_pay_total" => $request->voucher_pay_total,
                    "voucher_holiday_pay_fixing" => $request->voucher_holiday_pay_fixing,
                    "voucher_holiday_pay_hour_per_price" => $request->pay_per_hour * 1.5,
                    "retirement_saving_pay_type" => $request->retirement_saving_pay_type,
                    "retirement_saving_pay_company_percentage" => $request->retirement_saving_pay_company_percentage ?? null,
                    "tax_nation_selector" => $request->tax_nation_selector,
                    "tax_health_selector" => $request->tax_health_selector,
                    "tax_employ_selector" => $request->tax_employ_selector,
                    "tax_industry_selector" => $request->tax_industry_selector,
                    "tax_gabgeunse_selector" => $request->tax_gabgeunse_selector,
                    "employ_tax_selector" => $request->employ_tax_selector,
                    "industry_tax_percentage" => $request->industry_tax_percentage
                ]
            );

        $condition_id = $condition->id;

        $pay_per_hour = $request->pay_per_hour; // 시간당인건비단가
        $pay_hour = $request->pay_hour; // 기본시급
        $pay_over_time = $request->pay_over_time;
        $pay_holiday = $request->pay_holiday;
        $pay_night = $request->pay_night;
        $week_pay_apply_check = $request->week_pay_apply_check;
        $week_pay_apply_type = $request->week_pay_apply_type;
        $year_pay_apply_check = $request->year_pay_apply_check;
        $year_pay_apply_type = $request->year_pay_apply_type;
        $workers_day_allowance_check = $request->workers_day_allowance_check;

        foreach ($lists as $list) {

            $bool_week_pay = false;
            if ($week_pay_apply_check)
            {
                if ($week_pay_apply_type == "all") $bool_week_pay = true;
                else if ($week_pay_apply_type == "basic60" && $list->standard_weekly_time >= 60) $bool_week_pay = true;
                else if ($week_pay_apply_type == "basic65" && $list->standard_weekly_time >= 65) $bool_week_pay = true;
            }

            $bool_year_pay = false;
            if ($year_pay_apply_check)
            {
                if ($year_pay_apply_type == "all") $bool_year_pay = true;
                else if ($year_pay_apply_type == "basic60" && $list->standard_yearly_time >= 60) $bool_year_pay = true;
                else if ($year_pay_apply_type == "basic65" && $list->standard_yearly_time >= 65) $bool_year_pay = true;
            }


            $payment_id = DB::table("recalculate_standards")
                ->insertGetId([
                    "user_id" => $user_id,
                    "target_ym" => $conditions->target_ym,
                    "provider_key" => $list->provider_key,
                    "condition_id" => $condition_id,
                    "standard_basic_time" => $list->standard_basic_time,
                    "standard_basic_payment" => $standard_basic_payment = $list->standard_basic_time * $pay_hour,
                    "standard_over_time" => $list->standard_over_time,
                    "standard_over_payment" => $standard_overtime_payment = ($list->standard_over_time * ($pay_hour * 1.5)) * ($pay_over_time/100),
                    "standard_holiday_time" => $list->standard_holiday_time,
                    "standard_holiday_payment" => $standard_holiday_payment = ($list->standard_holiday_time * ($pay_hour * 1.5)) * ($pay_holiday/100),
                    "standard_night_time" => $list->standard_night_time,
                    "standard_night_payment" => $standard_night_payment = $list->standard_night_time * ($pay_hour * 1.5) * ($pay_night/100),
                    "standard_weekly_time" => $list->standard_weekly_time,
                    "standard_weekly_payment" => $standard_weekly_payment = $bool_week_pay ? $list->standard_weekly_time * $pay_hour : 0,
                    "standard_yearly_time" => $list->standard_yearly_time,
                    "standard_yearly_payment" => $standard_yearly_payment = $bool_year_pay ? $list->standard_yearly_time * $pay_hour : 0,
                    "standard_workers_day_time" => $list->standard_workers_day_time,
                    "standard_workers_day_payment" => $standard_workers_day_payment = $workers_day_allowance_check == 1 ? $list->standard_workers_day_time * ($pay_hour * 1.5) : 0,
                    "standard_public_day_time" => $list->standard_public_day_time,
                    "standard_public_day_payment" => $standard_public_day_payment = $list->standard_public_day_time * $pay_hour,
                    "standard_payment" => $standard_payment = $standard_basic_payment + $standard_overtime_payment + $standard_holiday_payment + $standard_night_payment + $standard_weekly_payment +
                        $standard_yearly_payment + $standard_workers_day_payment + $standard_public_day_payment,
                    "voucher_sub_standard_payment" => $list->voucher_payment - $standard_payment,
                    "standard_bojeon" => $list->standard_bojeon,
                    "standard_jaboodam" => $list->standard_jaboodam,
                    "standard_jaesoodang" => $list->standard_jaesoodang,
                    "standard_bannap" => $list->standard_bannap,
                    "voucher_payment" => $list->voucher_payment,
                ]);

            DB::table("recalculate_taxs")
                ->insert([
                    "user_id" => $user_id,
                    "condition_id" => $condition_id,
                    "payment_id" => $payment_id,
                    "provider_key" => $list->provider_key,
                    "target_ym" => $conditions->target_ym,
                    "save_selector" => $list->save_selector,
                    "selected_payment" => $selected_payment =  $list->save_selector == "voucher" ? $list->voucher_payment : $list->standard_payment,
                    "tax_nation_pension" => $tax_nation_pension = $list->save_selector == "voucher" ? $list->tax_voucher_national_price : $list->tax_standard_national_price,
                    "tax_health" => $tax_health = $list->save_selector == "voucher" ? $list->tax_voucher_health_price : $list->tax_standard_health_price,
                    "tax_employ" => $tax_employ = $list->save_selector == "voucher" ? $list->tax_voucher_employ_price : $list->tax_standard_employ_price,
                    "tax_gabgeunse" => $tax_gabgeunse = $list->save_selector == "voucher" ? $list->tax_voucher_gabgeunse_price : $list->tax_standard_gabgeunse_price,
                    "tax_joominse" => $tax_joominse = $list->save_selector == "voucher" ? $list->tax_voucher_gabgeunse_price * 0.1 : $list->tax_standard_gabgeunse_price * 0.1,
                    "tax_gunbo" => $tax_gunbo = $list->tax_gunbo,
                    "tax_yearly" => $tax_yearly = $list->tax_yearly,
                    "tax_bad_income" => $tax_bad_income = $list->tax_bad_income,
                    "tax_etc_1" => $tax_etc_1 = $list->tax_etc_1,
                    "tax_etc_2" => $tax_etc_2 = $list->tax_etc_2,
                    "tax_total" => $tax_total = $tax_nation_pension + $tax_health + $tax_employ + $tax_gabgeunse + $tax_joominse
                        + $tax_gunbo + $tax_yearly + $tax_bad_income + $tax_etc_1 + $tax_etc_2,
                    "tax_sub_payment" => $selected_payment - $tax_total,
                    "bank_name" => $list->bank_name,
                    "bank_number" => $list->bank_number,
                    "company_income" => $list->company_income, /* 제대로 된것 아님. 제대로 된 계산법 물어봐야 함. */
                    "tax_company_nation" => $list->save_selector == "voucher" ? $list->tax_voucher_national_price : $list->tax_standard_national_price,
                    "tax_company_health" => $list->save_selector == "voucher" ? $list->tax_voucher_health_price : $list->tax_standard_health_price,
                    "tax_company_employ" => $list->save_selector == "voucher" ? $list->tax_voucher_employ_price : $list->tax_standard_employ_price,
                    "tax_company_industry" => $list->save_selector == "voucher" ? $list->tax_voucher_industry_company_price : $list->tax_standard_industry_company_price,
                    "tax_company_retirement" => $list->save_selector == "voucher" ? $list->retirement_voucher : $list->retirement_standard,
                    "tax_company_return_confirm" => $list->company_return_confirm,
                    "tax_company_tax_total" => $list->save_selector == "voucher" ? $list->company_return_pay_total_voucher : $list->company_return_pay_total_standard,
                    "company_payment_result" => $list->company_payment_result, /* 제대로 된거 아님. 사업수입 계산방법 물어봐야함 */
                ]);


            DB::table("recalculate_vouchers")
                ->insert([
                    "user_id" => $user_id,
                    "condition_id" => $condition_id,
                    "target_ym" => $conditions->target_ym,
                    "provider_key" => $list->provider_key,
                    "is_register" => $list->is_register,
                    "nation_day_count" => $list->nation_day_count,
                    "nation_confirm_payment" => $nation_confirm_payment = ($list->nation_total_time * $pay_per_hour + ($pay_per_hour / 2 * ($list->nation_holiday_time + $list->nation_night_time))),
                    "nation_add_payment" => $nation_add_payment = $pay_per_hour / 2 * ($list->nation_holiday_time + $list->nation_night_time),
                    "nation_total_time" => $list->nation_total_time,
                    "nation_holiday_time" => $list->nation_holiday_time,
                    "nation_night_time" => $list->nation_night_time,
                    "city_day_count" => $list->city_day_count,
                    "city_confirm_payment" => $city_confirm_payment = $list->city_total_time * $pay_per_hour + ($pay_per_hour / 2 * ($list->city_holiday_time + $list->city_night_time)),
                    "city_add_payment" => $city_add_payment = $pay_per_hour / 2 * ($list->city_holiday_time + $list->city_night_time),
                    "city_total_time" => $list->city_total_time,
                    "city_holiday_time" => $list->city_holiday_time,
                    "city_night_time" => $list->city_night_time,
                    "voucher_total_day_count" => $list->voucher_total_day_count,
                    "voucher_total_confirm_payment" => $nation_confirm_payment + $city_confirm_payment,
                    "voucher_total_confirm_payment_add" => $nation_add_payment + $city_add_payment,
                    "voucher_total_time" => $list->voucher_total_time,
                    "voucher_total_time_holiday" => $list->voucher_total_time_holiday,
                    "voucher_total_time_night" => $list->voucher_total_time_night,
                    "voucher_detach_payment_basic" => $nation_confirm_payment - $nation_add_payment,
                    "voucher_detach_payment_holiday" => ($list->nation_holiday_time + $list->city_holiday_time) * ($pay_per_hour / 2),
                    "voucher_detach_payment_night" => ($list->nation_night_time + $list->city_night_time) * ($pay_per_hour / 2),
                    "voucher_detach_payment_difference" => $list->voucher_detach_payment_difference,
                    "voucher_etc_charge_city_time" => $list->voucher_etc_charge_city_time,
                    "voucher_etc_charge_city_pay" => $list->voucher_etc_charge_city_pay,
                    "voucher_etc_charge_except_time" => $list->voucher_etc_charge_except_time,
                    "voucher_etc_charge_except_pay" => $list->voucher_etc_charge_except_pay,
                    "voucher_etc_charge_total_time" => $list->voucher_etc_charge_total_time,
                    "voucher_etc_charge_total_pay" => $list->voucher_etc_charge_total_pay,
                    "voucher_return_nation_day_count" => $list->voucher_return_nation_day_count,
                    "voucher_return_nation_pay" => $list->voucher_return_nation_pay,
                    "voucher_return_nation_time" => $list->voucher_return_nation_time,
                    "voucher_return_city_day_count" => $list->voucher_return_city_day_count,
                    "voucher_return_city_pay" => $list->voucher_return_city_pay,
                    "voucher_return_city_time" => $list->voucher_return_city_time,
                    "voucher_return_total_day_count" => $list->voucher_return_total_day_count,
                    "voucher_return_total_pay" => $list->voucher_return_total_pay,
                    "voucher_return_total_time" => $list->voucher_return_total_time,
                    "voucher_payment" => $list->voucher_payment
                ]);

            DB::table("recalculate_workers_info")
                ->insert([
                    "user_id" => $user_id,
                    "payment_id" => $payment_id,
                    "condition_id" => $condition_id,
                    "provider_name" => $list->provider_name,
                    "target_ym" => $conditions->target_ym,
                    "provider_key" => $list->provider_key,
                    "provider_reg_check" => $list->provider_reg_check,
                    "join_date" => $list->join_date,
                    "resign_date" => $list->resign_date,
                    "nation_ins" => $list->nation_ins,
                    "health_ins" => $list->health_ins,
                    "employ_ins" => $list->employ_ins,
                    "retirement" => $list->retirement,
                    "year_rest_count" => $list->year_rest_count,
                    "dependents" => $list->dependents,
                ]);
        }

        return redirect("/recalc")->with("msg", "급여 재정산 내역을 저장했습니다");
    }
}
