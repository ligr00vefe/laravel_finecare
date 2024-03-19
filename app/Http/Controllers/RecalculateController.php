<?php

namespace App\Http\Controllers;

use App\Models\HelperInsurance;
use App\Models\Tax;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecalculateController extends Controller
{
    public function index(Request $request)
    {

        $from_date = $request->input("from_date") ?? date("Y-m-d");
        $user_id = User::get_user_id();

        $lists = DB::table("view_payment_total")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", "=", $from_date)
            ->get();

        return view("recalculate.index", [ "lists" => false ]);
    }


    public function store(Request $request)
    {
        $user_id = User::get_user_id();
        $from_date = date("Y-m-d", strtotime($request->input("from_date")));

        $tax_nation = $request->input("tax_nation_selector");
        $tax_health = $request->input("tax_health_selector");
        $tax_employ = $request->input("tax_employ_selector");
        $tax_industry = $request->input("tax_industry_selector");
        $tax_gabgeunse = $request->input("tax_gabgeunse_selector");

        $week_pay_selector = $request->input("week_pay_selector") ?? 5;
        $year_pay_selector = $request->input("year_pay_selector") ?? 5;
        $one_year_less_annual_pay_selector = $request->input("one_year_less_annual_pay_selector") ?? 5;
        $workers_day_allowance_day_selector = $request->input("workers_day_allowance_day_selector") ?? 5;

        $voucher_pay_total = $request->input("voucher_pay_total") ?? 1;

        $conditions = DB::table("payment_conditions")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", "=", $from_date)
            ->first();

        $lists = DB::table("view_payment_total")
            ->where("target_ym", "=", $from_date)
            ->where("user_id", "=", $user_id)
            ->get();

        $pay_per_hour = $request->input("pay_per_hour") ?? 0;
        $voucher_holiday_pay_fixing = $request->input("voucher_holiday_pay_fixing") ?? 0;


        foreach ($lists as $key => $list) {

            // 바우처상지급합계
            $voucher_payment = calcVoucherPayment($voucher_pay_total, $voucher_holiday_pay_fixing, $list, $pay_per_hour);
            $lists[$key]->voucher_payment = $voucher_payment;

            // 근로기준법 급여들 구하기.
            $calcStandardPayments = calcStandardPayment($list, $request);

            // 근로기준법 지급총액
            $standardPaymentTotal = $calcStandardPayments['standardPayment'];



//            $bosu_percentage_apply_check = HelperInsurance::percentageApplyCheck($key);
            $percentage_check = DB::table("helper_insurance")
                    ->where("user_id", $user_id)
                    ->where("target_key", "=", $list->provider_key)
                    ->first() ?? false;

            $bosu_percentage_apply = true; // 보수월액 비율적용이라면 false가 된다

            if (isset($percentage_check->percentage_apply) && $percentage_check->percentage_apply == 1) {
                $lists[$key]->tax_voucher_national_price = $percentage_check->national_ins_bosu_price;
                $lists[$key]->tax_standard_national_price = $percentage_check->national_ins_bosu_price;

                $lists[$key]->tax_voucher_health_price = $percentage_check->health_ins_bosu_price;
                $lists[$key]->tax_standard_health_price = $percentage_check->health_ins_bosu_price;

                $lists[$key]->tax_voucher_employ_price = $percentage_check->employ_ins_bosu_price;
                $lists[$key]->tax_standard_employ_price = $percentage_check->employ_ins_bosu_price;

                $lists[$key]->tax_voucher_industry_price = $percentage_check->industry_ins_bosu_price;
                $lists[$key]->tax_standard_industry_price = $percentage_check->industry_ins_bosu_price;
                $bosu_percentage_apply = false;
            }

            $tax_voucher_percentage = Tax::percentage_normal($key, $request, $voucher_payment);
            $tax_standard_percentage = Tax::percentage_normal($key, $request, $standardPaymentTotal);


            if ($tax_nation == "percentage" && $bosu_percentage_apply) {
                $lists[$key]->tax_voucher_national_price = $tax_voucher_percentage['national_pension'];
                $lists[$key]->tax_standard_national_price = $tax_standard_percentage['national_pension'];
            }

            if ($tax_nation == "pay") {
                // 보수월액이 "", null, 0이 아닐때 보수월액이 보험에 적용된다
                if (isset($percentage_check->national_ins_bosu_price)
                    && $percentage_check->national_ins_bosu_price != ""
                    && $percentage_check->national_ins_bosu_price != 0
                    && $percentage_check->national_ins_bosu_price != null) {
                    $lists[$key]->tax_voucher_national_price = $percentage_check->national_ins_bosu_price;
                    $lists[$key]->tax_standard_national_price = $percentage_check->national_ins_bosu_price;
                } else {
                    $nation_tax_exist = DB::table("edi_national_pension_logs")
                        ->where("user_id", "=", $user_id)
                        ->where("target_id","=", $list->provider_key)
                        ->orderByDesc("created_at")
                        ->first()->personal_contribute_price ?? null;

                    if (!$nation_tax_exist) {
                        $nation_tax_exist = DB::table("national_pension_logs")
                            ->where("user_id", "=", $user_id)
                            ->where("target_id", "=", $list->provider_key)
                            ->orderByDesc("created_at")
                            ->first()->insurance_fee ?? null;
                    }

                    $lists[$key]->tax_voucher_national_price = $nation_tax_exist;
                    $lists[$key]->tax_standard_national_price = $nation_tax_exist;
                }

            }

            if ($tax_health == "percentage" && $bosu_percentage_apply) {
                $lists[$key]->tax_voucher_health_price = $tax_voucher_percentage['health'];
                $lists[$key]->tax_standard_health_price = $tax_standard_percentage['health'];
            }

            if ($tax_health == "pay") {
                // 보수월액이 "", null, 0이 아닐때 보수월액이 보험에 적용된다
                if (isset($percentage_check->health_ins_bosu_price)
                    && $percentage_check->health_ins_bosu_price != ""
                    && $percentage_check->health_ins_bosu_price != 0
                    && $percentage_check->health_ins_bosu_price != null) {
                    $lists[$key]->tax_voucher_health_price = $percentage_check->health_ins_bosu_price;
                    $lists[$key]->tax_standard_health_price = $percentage_check->health_ins_bosu_price;
                } else {
                    $health_tax_exist = DB::table("edi_health_ins_logs")
                            ->where("user_id", "=", $user_id)
                            ->where("target_id","=", $list->provider_key)
                            ->orderByDesc("created_at")
                            ->first()->total_notice_ins_price ?? null;

                    if (!$health_tax_exist) {
                        $health_tax_exist = DB::table("health_ins_logs")
                                ->where("user_id", "=", $user_id)
                                ->where("target_id", "=", $list->provider_key)
                                ->orderByDesc("created_at")
                                ->first()->notice_insurance_price ?? null;
                    }

                    $lists[$key]->tax_voucher_health_price = $health_tax_exist;
                    $lists[$key]->tax_standard_health_price = $health_tax_exist;
                }

            }


            if ($tax_employ == "percentage" && $bosu_percentage_apply) {
                $lists[$key]->tax_voucher_employ_price = $tax_voucher_percentage['employ_worker'];
                $lists[$key]->tax_voucher_employ_company_price = $tax_voucher_percentage['employ_company'];
                $lists[$key]->tax_standard_employ_price = $tax_standard_percentage['employ_worker'];
                $lists[$key]->tax_standard_employ_company_price = $tax_standard_percentage['employ_company'];
            }

            if ($tax_employ == "pay") {
                // 보수월액이 "", null, 0이 아닐때 보수월액이 보험에 적용된다
                if (isset($percentage_check->employ_ins_bosu_price)
                    && $percentage_check->employ_ins_bosu_price != ""
                    && $percentage_check->employ_ins_bosu_price != 0
                    && $percentage_check->employ_ins_bosu_price != null) {
                    $lists[$key]->tax_voucher_employ_price = $percentage_check->employ_ins_bosu_price;
                    $lists[$key]->tax_voucher_employ_company_price = $percentage_check->employ_ins_bosu_price;
                    $lists[$key]->tax_standard_employ_price = $percentage_check->employ_ins_bosu_price;
                    $lists[$key]->tax_standard_employ_company_price = $percentage_check->employ_ins_bosu_price;

                } else {
                    $employ_tax_exist = DB::table("edi_employment_ins_logs")
                            ->where("user_id", "=", $user_id)
                            ->where("target_id","=", $list->provider_key)
                            ->orderByDesc("created_at")
                            ->first() ?? null;

                    if ($employ_tax_exist) {
                        $employ_tax_exist_worker = $employ_tax_exist->total_worker_unemploy_benefit;
                        $employ_tax_exist_company = $employ_tax_exist->total_owner_goan_ins_price;
                    } else {
                        $employ_tax_exist = DB::table("employment_ins_logs")
                                ->where("user_id", "=", $user_id)
                                ->where("target_id", "=", $list->provider_key)
                                ->orderByDesc("created_at")
                                ->first() ?? null;
                        if ($employ_tax_exist) {
                            $employ_tax_exist_worker = $employ_tax_exist->insurance_fee;
                            $employ_tax_exist_company = $employ_tax_exist->monthly_bosu_price;
                        }
                    }

                    $lists[$key]->tax_voucher_employ_price = $employ_tax_exist_worker;
                    $lists[$key]->tax_voucher_employ_company_price = $employ_tax_exist_company;
                    $lists[$key]->tax_standard_employ_price = $employ_tax_exist_worker;
                    $lists[$key]->tax_standard_employ_company_price = $employ_tax_exist_company;
                }

            }



            if ($tax_industry == "percentage" && $bosu_percentage_apply) {
                $lists[$key]->tax_voucher_industry_price = $tax_voucher_percentage['industry'] ?? 0;
                $lists[$key]->tax_voucher_industry_company_price = $tax_voucher_percentage['industry'] ?? 0;
                $lists[$key]->tax_standard_industry_price = $tax_voucher_percentage['industry'] ?? 0;
                $lists[$key]->tax_standard_industry_company_price = $tax_voucher_percentage['industry'] ?? 0;
            }

            if ($tax_industry == "pay") {

                // 보수월액이 "", null, 0이 아닐때 보수월액이 보험에 적용된다
                if (isset($percentage_check->industry_ins_bosu_price)
                    && $percentage_check->industry_ins_bosu_price != ""
                    && $percentage_check->industry_ins_bosu_price != 0
                    && $percentage_check->industry_ins_bosu_price != null) {
                    $lists[$key]->tax_voucher_industry_price = $percentage_check->industry_ins_bosu_price;
                    $lists[$key]->tax_standard_industry_price = $percentage_check->industry_ins_bosu_price;

                } else {
                    $industry_tax_exist = DB::table("edi_industry_ins_logs")
                            ->where("user_id", "=", $user_id)
                            ->where("target_id","=", $list->provider_key)
                            ->orderByDesc("created_at")
                            ->first() ?? null;

                   if (!$industry_tax_exist) {
                       $industry_tax_exist = DB::table("industry_ins_logs")
                               ->where("user_id", "=", $user_id)
                               ->where("target_id", "=", $list->provider_key)
                               ->orderByDesc("created_at")
                               ->first() ?? null;
                   }

                    if ($industry_tax_exist) {
                        $industry_tax_exist_worker = $industry_tax_exist->insurance_fee;
                        $industry_tax_exist_company = $industry_tax_exist->monthly_bosu_price;
                    }


                    $lists[$key]->tax_voucher_industry_price = $industry_tax_exist_worker ?? 0;
                    $lists[$key]->tax_voucher_industry_company_price = $industry_tax_exist_company ?? 0;
                    $lists[$key]->tax_standard_industry_price = $industry_tax_exist_worker ?? 0;
                    $lists[$key]->tax_standard_industry_company_price = $industry_tax_exist_company ?? 0;
                }

            }


            if ($tax_gabgeunse == "percentage" && $bosu_percentage_apply) {
                $lists[$key]->tax_voucher_gabgeunse_price = $tax_voucher_percentage['gabgeunse'];
                $lists[$key]->tax_standard_gabgeunse_price = $tax_standard_percentage['gabgeunse'];
            }

            if ($tax_gabgeunse == "pay") {
                $pay_gabgeunse = Worker::getGabgeunse($list->provider_key);
                $lists[$key]->tax_voucher_gabgeunse_price = $pay_gabgeunse;
                $lists[$key]->tax_standard_gabgeunse_price = $pay_gabgeunse;
            }

            // 바우처 공제 합계
            $lists[$key]->tax_voucher_total = $lists[$key]->tax_voucher_national_price
                + $lists[$key]->tax_voucher_health_price
                + $lists[$key]->tax_voucher_employ_price
                + $lists[$key]->tax_voucher_gabgeunse_price
                + round($lists[$key]->tax_voucher_gabgeunse_price * 0.1);

            // 근로기준법 공제 합계
            $lists[$key]->tax_standard_total = $lists[$key]->tax_standard_national_price
                + $lists[$key]->tax_standard_health_price
                + $lists[$key]->tax_standard_employ_price
                + $lists[$key]->tax_standard_gabgeunse_price
                + round($lists[$key]->tax_standard_gabgeunse_price * 0.1);


            // 사업주 퇴직연금
            $lists[$key]->retirement_voucher = Tax::retirement($request, $key, $voucher_payment) ?? 0;
            $lists[$key]->retirement_standard = Tax::retirement($request, $key, $standardPaymentTotal) ?? 0;


            //  반납승인(사업주. 반납승인금액 * 0.236)
            $lists[$key]->company_return_confirm =  removeComma($list->voucher_return_total_pay) * 0.236;

            // 사업주부담합계 바우처
            $lists[$key]->company_return_pay_total_voucher = $lists[$key]->tax_voucher_total
                + $lists[$key]->retirement_voucher
                + $lists[$key]->company_return_confirm;

            // 사업주부담합계 근로기준법
            $lists[$key]->company_return_pay_total_standard = $lists[$key]->tax_standard_total
                + $lists[$key]->retirement_standard
                + $lists[$key]->company_return_confirm;


            // 주휴시간
            $lists[$key]->standard_weekly_time = $list->standard_basic_time / $week_pay_selector;

            // 연차시간
            $lists[$key]->standard_yearly_time = $list->standard_basic_time / (365/7/12) / $year_pay_selector * 15 / 12;

            // 근로자의날시간
            $lists[$key]->standard_workers_day_time = $list->standard_basic_time / $workers_day_allowance_day_selector;


        }



        return view("recalculate.index", [
            "lists" => $lists,
            "request" => $request->input(),
            "conditions" => $conditions,
            "pay_per_hour" => $request->input("pay_per_hour") ?? 0,
            "voucher_pay_total" => $request->input("voucher_pay_total") ?? 1,
            "voucher_holiday_pay_fixing" => $request->input("voucher_holiday_pay_fixing") ?? 0,
            "pay_hour" => $request->input("pay_hour") ?? 0,
            "pay_over_time" => $request->input("pay_over_time") ?? 0,
            "pay_holiday" => $request->input("pay_holiday") ?? 0,
            "pay_night" => $request->input("pay_night") ?? 0,
            "week_pay_apply_check" => $request->input("week_pay_apply_check") ?? 0,
            "week_pay_apply_type" => $request->input("week_pay_apply_type") ?? 0,
            "week_pay_selector" => $request->input("week_pay_selector") ?? 0,

            "year_pay_apply_check" => $request->input("year_pay_apply_check") ?? 0,
            "year_pay_apply_type" => $request->input("year_pay_apply_type") ?? "",
            "year_pay_selector" => $request->input("year_pay_selector") ?? 5,
            "public_allowance_day_selector" => $request->input("public_allowance_day_selector") ?? 5,

            "workers_day_allowance_day_selector" => $request->input("workers_day_allowance_day_selector") ?? 5,


            "one_year_less_annual_pay_selector" => $request->input("one_year_less_annual_pay_selector") ?? 5,


            "workers_day_allowance_check" => $request->input("workers_day_allowance_check") ?? 0,

        ]);

    }


}
