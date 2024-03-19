<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EDI extends Model
{
    use HasFactory;


    // 산재보험 내역
    public static function get_industry_ins($provider_key)
    {
        $user_id = User::get_user_id();

        $pay = 0;

        $edi = DB::table("edi_industry_ins_logs")
            ->where("user_id", "=", $user_id)
            ->where("target_id", "=", $provider_key)
            ->orderByDesc("id")
            ->limit(1)
            ->first();

        if (!$edi) {
            $ins = DB::table("industry_ins_logs")
                ->where("user_id", "=", $user_id)
                ->where("target_id", "=", $provider_key)
                ->orderByDesc("id")
                ->limit(1)
                ->first();

            $pay = $ins->insurance_fee ?? 0;
        } else {
            $pay = $edi->total_ins_price ?? 0;
        }

        return $pay;
    }


    // 고용보험 내역
    public static function get_employment_ins($provider_key)
    {
        $user_id = User::get_user_id();

        $personal = 0;
        $company = 0;

        $edi = DB::table("edi_employment_ins_logs")
            ->where("user_id", "=", $user_id)
            ->where("target_id", "=", $provider_key)
            ->orderByDesc("id")
            ->limit(1)
            ->first() ?? null;

        if (!$edi) {
            $ins = DB::table("employment_ins_logs")
                    ->where("user_id", "=", $user_id)
                    ->where("target_id", "=", $provider_key)
                    ->orderByDesc("id")
                    ->limit(1)
                    ->first() ?? null;

            $personal = $ins->insurance_fee ?? 0;
            $company = $ins->insurance_fee ?? 0;
        } else {
            $personal = $ins->total_worker_unemploy_benefit ?? 0;
            $company = $ins->total_owner_goan_ins_price ?? 0;
        }

        return [ "personal" => $personal, "company" => $company ];
    }

    // 건강보험 내역
    public static function get_health_ins($provider_key)
    {
        $user_id = User::get_user_id();

        $pay = 0;

        $edi = DB::table("edi_health_ins_logs")
            ->where("user_id", "=", $user_id)
            ->where("target_id", "=", $provider_key)
            ->orderByDesc("id")
            ->first();

        if (!$edi) {
            $ins = DB::table("health_ins_logs")
                ->where("user_id", "=", $user_id)
                ->where("target_id", "=", $provider_key)
                ->orderByDesc("id")
                ->first();

            $pay = $ins->notice_insurance_price2 ?? 0;
        } else {
            $pay = $edi->total_notice_ins_price ?? 0;
        }

        return $pay;
    }

    // 가장 최신 edi 국민연금내역을 가져온다 ( 날짜값이 없기때문...)
    public static function get_national_pension($provider_key)
    {
        $user_id = User::get_user_id();

        $fee = 0;

        $edi = DB::table("edi_national_pension_logs")
            ->where("user_id", "=", $user_id)
            ->where("target_id", "=", $provider_key)
            ->orderByDesc("id")
            ->limit(1)
            ->first();

        if (!$edi) {
            $edi = DB::table("national_pension_logs")
                ->where("user_id", "=", $user_id)
                ->where("target_id", "=", $provider_key)
                ->orderByDesc("id")
                ->limit(1)
                ->first();

            $fee = $edi->insurance_fee ?? 0;

        } else {
            $fee = $edi->personal_contribute_price ?? 0;
        }

        return $fee;
    }

}
