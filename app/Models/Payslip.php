<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payslip extends Model
{
    use HasFactory;

    public static function get($request)
    {

        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));


        if($target_ym === null || $target_ym === '' || $target_ym === "undefined")
        {
            return false;
        }

        $name = $request->input("name") ?? false;

        $birth = $request->input("birth") ?? false;

        $multiple_check = 2; // 2는 다중검색

        // 이름이나 생년월일이 있다면 단일검색
        if($name || $birth)
        {
            $multiple_check = 1;
        }

        $provider_key = $name.$birth;

        $user_id = User::get_user_id();


        if($multiple_check === 1){

            $workers_info = PaymentWorkersInfo::where("user_id", "=", $user_id)
                ->where("target_ym", "=", $target_ym)
                ->when($provider_key, function ($query, $provider_key) {
                    return $query->where("provider_key", "like", "%{$provider_key}%");
                })
            ;

            $vouchers = PaymentVouchers::where("user_id", "=", $user_id)
                ->where("target_ym", "=", $target_ym)
                ->when($provider_key, function ($query, $provider_key){
                    return $query->where("provider_key", "like", "%{$provider_key}%");
                })
            ;

            $standards = PaymentStandards::where("user_id", "=", $user_id)
                ->where("target_ym", "=", $target_ym)
                ->when($provider_key, function ($query, $provider_key) {
                    return $query->where("provider_key", "like", "%{$provider_key}%");
                })
            ;

            $taxs = PaymentTaxs::where("user_id", "=", $user_id)
                ->where("target_ym", "=", $target_ym)
                ->when($provider_key, function ($query, $provider_key){
                    return $query->where("provider_key", "like", "%{$provider_key}%");
                })
            ;
        }
        else //정확히 같은 순서대로 안 가져오고 중간에 하나 빠져서 순서 섞일수도 있으므로 조인
        {
            $payslip = DB::table("view_payment_total")
                ->where("user_id", $user_id)
                ->where("target_ym", $target_ym)
                ->get();
        }

        $confirm_pay = array();
        if ($multiple_check === 1) { // 단일검색
            $workers_info = $workers_info->first();
            $vouchers = $vouchers->first();
            $standards = $standards->first();
            $taxs = $taxs->first();
            $type = $taxs->save_selector ?? "voucher";

            if(isset($workers_info)){
                $confirm_pay = [
                    "pay_basic" => $type == "voucher" ? $vouchers->voucher_detach_payment_basic : $standards->standard_basic_payment,
                    "time_basic" => $type == "voucher"
                        ? $vouchers->voucher_total_time - $vouchers->voucher_total_time_holiday - $vouchers->voucher_total_time_night
                        : $standards->standard_basic_time,
                    "pay_holiday" => $type == "voucher" ? $vouchers->voucher_detach_payment_holiday : $standards->standard_holiday_payment,
                    "time_holiday" => $type == "voucher" ? $vouchers->voucher_total_time_holiday : $standards->standard_holiday_time,
                    "pay_night" => $type == "voucher" ? $vouchers->voucher_detach_payment_night : $standards->standard_night_payment,
                    "time_night" => $type == "voucher" ? $vouchers->voucher_total_time_night : $standards->standard_night_time,
                    "time_total" => $vouchers->voucher_total_time,
                    "pay_total" => $type == "voucher"
                        ? $vouchers->voucher_payment
                        : $standards->standards_payment
                ];
            }

        }
        else
        { //다중 검색

            foreach ($payslip as $key => $list)
            {
                $type = $list->save_selector ?? "voucher";
                $confirm_pay[] = [
                    "data" => $list,
                    "pay_basic" => $type == "voucher" ? $list->voucher_detach_payment_basic : $list->standard_basic_payment,
                    "time_basic" => $type == "voucher"
                        ? $list->voucher_total_time - $list->voucher_total_time_holiday - $list->voucher_total_time_night
                        : $list->standard_basic_time,
                    "pay_holiday" => $type == "voucher" ? $list->voucher_detach_payment_holiday : $list->standard_holiday_payment,
                    "time_holiday" => $type == "voucher" ? $list->voucher_total_time_holiday : $list->standard_holiday_time,
                    "pay_night" => $type == "voucher" ? $list->voucher_detach_payment_night : $list->standard_night_payment,
                    "time_night" => $type == "voucher" ? $list->voucher_total_time_night : $list->standard_night_time,
                    "time_total" => $list->voucher_total_time,
                    "pay_total" => $type == "voucher"
                        ? $list->voucherPaymentFromPaymentVouchers
                        : $list->voucherPaymentFromStandardTable
                ];
            }
        }

        return [
            "multiple_check" => $multiple_check,
            "voucher" => $vouchers ?? "",
            "worker" => $workers_info ?? "",
            "standard" => $standards ?? "",
            "tax" => $taxs ?? "",
            "payment" => $confirm_pay,
            "provider_key" => $provider_key ?? ""
        ];

    }
}
