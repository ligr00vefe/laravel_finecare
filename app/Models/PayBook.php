<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBook extends Model
{
    use HasFactory;

    public static function get($request)
    {
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));
        $user_id = User::get_user_id();

//        $conditions = PaymentConditions::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->first();
        $workers_info = PaymentWorkersInfo::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->get();
        $vouchers = PaymentVouchers::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->get();
        $standards = PaymentStandards::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->get();
        $taxs = PaymentTaxs::where("user_id", "=", $user_id)->where("target_ym", "=", $target_ym)->get();

        $lists = [];

        foreach ($vouchers as $i => $voucher)
        {

            $key = $voucher->provider_key;
            $type = $taxs[$i]['save_selector'];

            $confirm_pay = [
                "pay_basic" => $type == "voucher" ? $voucher->voucher_detach_payment_basic : $standards[$i]->standard_basic_payment,
                "time_basic" => $type == "voucher"
                    ? $voucher->voucher_total_time - $voucher->voucher_total_time_holiday - $voucher->voucher_total_time_night
                    : $standards[$i]->standard_basic_time,
                "pay_holiday" => $type == "voucher" ? $voucher->voucher_detach_payment_holiday : $standards[$i]->standard_holiday_payment,
                "time_holiday" => $type == "voucher" ? $voucher->voucher_total_time_holiday : $standards[$i]->standard_holiday_time,
                "pay_night" => $type == "voucher" ? $voucher->voucher_detach_payment_night : $standards[$i]->standard_night_payment,
                "time_night" => $type == "voucher" ? $voucher->voucher_total_time_night : $standards[$i]->standard_night_time,
                "standard_weekly_time" => $standards[$i]->standard_weekly_time,
                "standard_weekly_payment" => $standards[$i]->standard_weekly_payment,
                "standard_yearly_time" => $standards[$i]->standard_yearly_time,
                "standard_yearly_payment" => $standards[$i]->standard_yearly_payment,
                "standard_workers_day_time" => $standards[$i]->standard_workers_day_time,
                "standard_workers_day_payment" => $standards[$i]->standard_workers_day_payment,
                "standard_public_day_time" => $standards[$i]->standard_public_day_time,
                "standard_public_day_payment" => $standards[$i]->standard_public_day_payment,
//                "standard_payment" => $standards[$i]->standard_payment,

                // 적용합계
                "payment_total" => $standards[$i]->standard_payment,
                // 법정제수당(차액)
                "voucher_sub_standard_payment" => $standards[$i]->voucher_sub_standard_payment,
                // 지급총액
                "voucher_payment" => $standards[$i]->voucher_payment,

            ];

            $lists[$key] = [
                "voucher" => $voucher,
                "worker" => $workers_info[$i],
                "standard" => $standards[$i],
                "tax" => $taxs[$i],
                "payment" => $confirm_pay
            ];
        }

        return [
            "lists" => $lists,
        ];
    }
}
