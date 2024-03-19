<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecalculateDiffController extends Controller
{
    public function index(Request $request)
    {
        $user_id = User::get_user_id();
        $from_date = $request->input("from_date") ?? false;
        $provider_name = $request->input("provider_name") ?? false;
        $provider_birth = $request->input("provider_birth") ?? false;


        $lists = [];

        if ($from_date)
        {
            $from_date = date("Y-m-d", strtotime($from_date));
            $payment_lists = DB::table("view_payment_total")
                ->where("user_id", "=", $user_id)
                ->when($from_date, function ($query, $from_date) {
                    return $query->where("target_ym", "=", $from_date);
                })
                ->when($provider_name, function($query, $provider_name) use ($provider_birth) {
                    $provider_key = $provider_name;
                    if ($provider_birth) {
                        $provider_key = $provider_name . $provider_birth;
                    }

                    return $query->where("provider_key", "like", "%{$provider_key}%");

                })
                ->get();

            $recalculate_lists = DB::table("view_recalculate_payment_total")
                ->where("user_id", "=", $user_id)
                ->when($from_date, function($query, $from_date) {
                    return $query->where("target_ym", "=", $from_date);
                })
                ->when($provider_name, function($query, $provider_name) use ($provider_birth) {
                    $provider_key = $provider_name;
                    if ($provider_birth) {
                        $provider_key = $provider_name . $provider_birth;
                    }

                    return $query->where("provider_key", "like", "%{$provider_key}%");

                })
                ->get();


            foreach ($payment_lists as $list) {

                $lists[$list->provider_key] = [];
                $lists[$list->provider_key]['payment_list'] = $list;
                foreach ($recalculate_lists as $value)
                {
                    if ($value->provider_key == $list->provider_key) {
                        $lists[$list->provider_key]['recalculate_list'] = $value;
                    }
                }

                $lists[$list->provider_key]['payment_diff'] = new \stdClass();

                // 국비 승인금액
                $lists[$list->provider_key]['payment_diff']->nation_confirm_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->nation_confirm_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->nation_confirm_payment);

                // 국비 가산금액
                $lists[$list->provider_key]['payment_diff']->nation_add_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->nation_add_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->nation_add_payment);

                // 시도비 승인금액
                $lists[$list->provider_key]['payment_diff']->city_confirm_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->city_confirm_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->city_confirm_payment);

                // 시도비 가산금액
                $lists[$list->provider_key]['payment_diff']->city_add_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->city_add_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->city_add_payment);

                // 승인합계 승인금액
                $lists[$list->provider_key]['payment_diff']->voucher_total_confirm_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucher_total_confirm_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_total_confirm_payment);

                // 승인합계 가산금액
                $lists[$list->provider_key]['payment_diff']->voucher_total_confirm_payment_add =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucher_total_confirm_payment_add)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_total_confirm_payment_add);

                // 반납승인내역 국비반납
                $lists[$list->provider_key]['payment_diff']->voucher_return_nation_pay =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucher_return_nation_pay)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_return_nation_pay);

                // 반납승인내역 시도비반납
                $lists[$list->provider_key]['payment_diff']->voucher_return_city_pay =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucher_return_city_pay)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_return_city_pay);

                // 반납승인내역 합계 승인금액
                $lists[$list->provider_key]['payment_list']->voucher_return_total_pay =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucher_return_nation_pay)
                    + extractNumber($lists[$list->provider_key]['payment_list']->voucher_return_city_pay);

                // 반납승인내역 합계 재정산금액
                $lists[$list->provider_key]['recalculate_list']->voucher_return_total_pay =
                    extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_return_nation_pay)
                    + extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_return_city_pay);

                // 반납승인내역 합계 차액
                $lists[$list->provider_key]['payment_diff']->voucher_return_total_pay =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucher_return_total_pay)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_return_total_pay);

                // 바우처상 지급합계 차액
                $lists[$list->provider_key]['payment_diff']->voucher_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucherPaymentFromStandardTable)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->voucherPaymentFromStandardTable);

                // 근로기준법 기본급
                $lists[$list->provider_key]['payment_diff']->standard_basic_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_basic_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_basic_payment);

                // 근로기준법 연장
                $lists[$list->provider_key]['payment_diff']->standard_over_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_over_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_over_payment);

                // 근로기준법 휴일
                $lists[$list->provider_key]['payment_diff']->standard_holiday_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_holiday_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_holiday_payment);

                // 근로기준법 야간
                $lists[$list->provider_key]['payment_diff']->standard_night_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_night_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_night_payment);

                // 근로기준법 주휴
                $lists[$list->provider_key]['payment_diff']->standard_weekly_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_weekly_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_weekly_payment);

                // 근로기준법 연차
                $lists[$list->provider_key]['payment_diff']->standard_yearly_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_yearly_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_yearly_payment);

                // 근로기준법 근로자의날
                $lists[$list->provider_key]['payment_diff']->standard_workers_day_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_workers_day_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_workers_day_payment);

                // 근로기준법 공휴일기본지급
                $lists[$list->provider_key]['payment_diff']->standard_public_day_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->standard_public_day_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->standard_public_day_payment);

                // 근로기준법 적용합계
                $lists[$list->provider_key]['payment_diff']->standard_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->StandardPaymentFromStandardTable)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->StandardPaymentFromStandardTable);


                // 근로기준법 법정제수당 또는 차액
                $lists[$list->provider_key]['payment_diff']->voucher_sub_standard_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->voucher_sub_standard_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->voucher_sub_standard_payment);


                // 제공자 국민연금
                $lists[$list->provider_key]['payment_diff']->tax_nation_pension =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_nation_pension)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_nation_pension);

                // 제공자 건강보험
                $lists[$list->provider_key]['payment_diff']->tax_health =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_health)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_health);

                // 제공자 고용보험
                $lists[$list->provider_key]['payment_diff']->tax_employ =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_employ)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_employ);

                // 제공자 갑근세
                $lists[$list->provider_key]['payment_diff']->tax_gabgeunse =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_gabgeunse)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_gabgeunse);

                // 제공자 주민세
                $lists[$list->provider_key]['payment_diff']->tax_joominse =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_joominse)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_joominse);

                // 제공자 공제합계
                $lists[$list->provider_key]['payment_diff']->tax_total =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_total)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_total);

                // 제공자 공제 차인지급액(세후금액)
                $lists[$list->provider_key]['payment_diff']->tax_sub_payment =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_sub_payment)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_sub_payment);



                // 제공자 사업수입
                $lists[$list->provider_key]['payment_diff']->company_income =
                    extractNumber($lists[$list->provider_key]['payment_list']->company_income)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->company_income);


                // 사업주 국민연금
                $lists[$list->provider_key]['payment_diff']->tax_company_nation =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_company_nation)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_company_nation);

                // 사업주 건강보험
                $lists[$list->provider_key]['payment_diff']->tax_company_health =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_company_health)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_company_health);

                // 사업주 고용보험
                $lists[$list->provider_key]['payment_diff']->tax_company_employ =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_company_employ)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_company_employ);

                // 사업주 산재보험
                $lists[$list->provider_key]['payment_diff']->tax_company_industry =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_company_industry)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_company_industry);

                // 사업주 퇴직적립금
                $lists[$list->provider_key]['payment_diff']->tax_company_retirement =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_company_retirement)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_company_retirement);

                // 사업주 반납승인(사업주)
                $lists[$list->provider_key]['payment_diff']->tax_company_return_confirm =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_company_return_confirm)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_company_return_confirm);

                // 사업주 부담합계
                $lists[$list->provider_key]['payment_diff']->tax_company_tax_total =
                    extractNumber($lists[$list->provider_key]['payment_list']->tax_company_tax_total)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->tax_company_tax_total);

                // 사업주 차감사업주 수익
                $lists[$list->provider_key]['payment_diff']->company_payment_result =
                    extractNumber($lists[$list->provider_key]['payment_list']->company_payment_result)
                    - extractNumber($lists[$list->provider_key]['recalculate_list']->company_payment_result);


            }

        }


        return view("recalculate.diff.index", [
            "lists" => $lists,
            "from_date" => $request->input("from_date"),
            "provider_name" => $request->input("provider_name"),
            "provider_birth" => $request->input("provider_birth")
        ]);
    }
}
