<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tax extends Model
{
    use HasFactory;


    public static function calc($request, $key, $voucher)
    {


        $retirement_pay_type = $request->input("retirement_saving_pay_type") ?? false;
        $tax_type = $request->input("tax_selector");

        $tax_nation_type = $request->input("tax_nation_selector");
        $tax_health_type = $request->input("tax_health_selector");
        $tax_employ_type = $request->input("tax_employ_selector");
        $tax_industry_type = $request->input("tax_industry_selector");
        $tax_gabgeunse_type = $request->input("tax_gabgeunse_selector");


        $voucher['Tax']['WORKER_NATIONAL'] = 0;
        $voucher['Tax']['WORKER_HEALTH'] = 0;
        $voucher['Tax']['WORKER_EMPLOY'] = 0;
        $voucher['Tax']['CLASS_A_WAGE'] = 0;
        $voucher['Tax']['RESIDENT_TAX'] = 0;
        $voucher['Retirement'] = 0;

        $voucher['Tax']['COMPANY_NATIONAL'] = 0;
        $voucher['Tax']['COMPANY_HEALTH'] = 0;
        $voucher['Tax']['COMPANY_INDUSTRY'] = 0;
        $voucher['Tax']['COMPANY_EMPLOY'] = 0;


        $voucher['Tax']['WORKER_NATIONAL_STANDARD'] = 0;
        $voucher['Tax']['WORKER_HEALTH_STANDARD'] = 0;
        $voucher['Tax']['WORKER_EMPLOY_STANDARD'] = 0;
        $voucher['Tax']['CLASS_A_WAGE_STANDARD'] = 0;
        $voucher['Tax']['RESIDENT_TAX_STANDARD'] = 0;

        $voucher['Tax']['COMPANY_NATIONAL_STANDARD'] = 0;
        $voucher['Tax']['COMPANY_HEALTH_STANDARD'] = 0;
        $voucher['Tax']['COMPANY_INDUSTRY_STANDARD'] = 0;
        $voucher['Tax']['COMPANY_EMPLOY_STANDARD'] = 0;
        $voucher['Retirement_STANDARD'] = 0;

        // 보수월액을 비율에도 적용할지 체크
        $bosu_percentage_apply_check = HelperInsurance::percentageApplyCheck($key);

        $tax_percentage = Tax::percentage($request, $key, $voucher['Payment'], $bosu_percentage_apply_check) ?? []; // 비율
        $tax_percentage_standard = Tax::percentage($request, $key, $voucher['Standard']['PAY_TOTAL'], $bosu_percentage_apply_check) ?? []; // 비율 스탠다드


        $tax_pay = Tax::tax($request, $key, $voucher['Payment']) ?? []; // 금액


        if ($tax_nation_type == "percentage") {
            $voucher['Tax']['WORKER_NATIONAL'] = $tax_percentage['WORKER_NATIONAL'] ?? 0;
            $voucher['Tax']['COMPANY_NATIONAL'] = $tax_percentage['COMPANY_NATIONAL'] ?? 0;
            $voucher['Tax']['WORKER_NATIONAL_STANDARD'] = $tax_percentage_standard['WORKER_NATIONAL'] ?? 0;
            $voucher['Tax']['COMPANY_NATIONAL_STANDARD'] = $tax_percentage_standard['COMPANY_NATIONAL'] ?? 0;
        } else if ($tax_nation_type == "pay") {
            $voucher['Tax']['WORKER_NATIONAL'] = $tax_pay['WORKER_NATIONAL'] ?? 0;
            $voucher['Tax']['COMPANY_NATIONAL'] = $tax_pay['COMPANY_NATIONAL'] ?? 0;
            $voucher['Tax']['WORKER_NATIONAL_STANDARD'] = $tax_pay['WORKER_NATIONAL'] ?? 0;
            $voucher['Tax']['COMPANY_NATIONAL_STANDARD'] = $tax_pay['COMPANY_NATIONAL'] ?? 0;
        }

        if ($tax_health_type == "percentage") {
            $voucher['Tax']['WORKER_HEALTH'] = $tax_percentage['WORKER_HEALTH'] ?? 0;
            $voucher['Tax']['COMPANY_HEALTH'] = $tax_percentage['COMPANY_HEALTH'] ?? 0;
            $voucher['Tax']['WORKER_HEALTH_STANDARD'] = $tax_percentage_standard['WORKER_HEALTH'] ?? 0;
            $voucher['Tax']['COMPANY_HEALTH_STANDARD'] = $tax_percentage_standard['COMPANY_HEALTH'] ?? 0;
        } else if ($tax_health_type == "pay") {
            $voucher['Tax']['WORKER_HEALTH'] = $tax_pay['WORKER_HEALTH'] ?? 0;
            $voucher['Tax']['COMPANY_HEALTH'] = $tax_pay['COMPANY_HEALTH'] ?? 0;
            $voucher['Tax']['WORKER_HEALTH_STANDARD'] = $tax_pay['WORKER_HEALTH'] ?? 0;
            $voucher['Tax']['COMPANY_HEALTH_STANDARD'] = $tax_pay['WORKER_HEALTH'] ?? 0;
        }

        if ($tax_employ_type == "percentage") {
            $voucher['Tax']['WORKER_EMPLOY'] = $tax_percentage['WORKER_EMPLOY'] ?? 0;
            $voucher['Tax']['COMPANY_EMPLOY'] = $tax_percentage['COMPANY_EMPLOY'] ?? 0;
            $voucher['Tax']['WORKER_EMPLOY_STANDARD'] = $tax_percentage_standard['WORKER_EMPLOY'] ?? 0;
            $voucher['Tax']['COMPANY_EMPLOY_STANDARD'] = $tax_percentage_standard['COMPANY_EMPLOY'] ?? 0;
        } else if ($tax_employ_type == "pay") {
            $voucher['Tax']['WORKER_EMPLOY'] = $tax_pay['WORKER_EMPLOY'] ?? 0;
            $voucher['Tax']['COMPANY_EMPLOY'] = $tax_pay['COMPANY_EMPLOY'] ?? 0;
            $voucher['Tax']['WORKER_EMPLOY_STANDARD'] = $tax_pay['WORKER_EMPLOY'] ?? 0;
            $voucher['Tax']['COMPANY_EMPLOY_STANDARD'] = $tax_pay['WORKER_EMPLOY'] ?? 0;
        }

        if ($tax_industry_type == "percentage") {
            $voucher['Tax']['COMPANY_INDUSTRY'] = $tax_percentage['COMPANY_INDUSTRY'] ?? 0;
            $voucher['Tax']['COMPANY_INDUSTRY_STANDARD'] = $tax_percentage_standard['COMPANY_INDUSTRY'] ?? 0;
        } else if ($tax_industry_type == "pay") {
            $voucher['Tax']['COMPANY_INDUSTRY'] = $tax_pay['COMPANY_INDUSTRY'] ?? 0;
            $voucher['Tax']['COMPANY_INDUSTRY_STANDARD'] = $tax_pay['COMPANY_INDUSTRY'] ?? 0;
        }

        if ($tax_gabgeunse_type == "percentage") {
            $voucher['Tax']['CLASS_A_WAGE'] = $tax_percentage['CLASS_A_WAGE'] ?? 0;
            $voucher['Tax']['RESIDENT_TAX'] = $tax_percentage['RESIDENT_TAX'] ?? 0;
            $voucher['Tax']['CLASS_A_WAGE_STANDARD'] = $tax_percentage_standard['CLASS_A_WAGE'] ?? 0;
            $voucher['Tax']['RESIDENT_TAX_STANDARD'] = $tax_percentage_standard['RESIDENT_TAX'] ?? 0;

        } else if ($tax_gabgeunse_type == "pay") {
            $voucher['Tax']['CLASS_A_WAGE'] = $tax_pay['CLASS_A_WAGE'] ?? 0;
            $voucher['Tax']['RESIDENT_TAX'] = $tax_pay['RESIDENT_TAX'] ?? 0;
            $voucher['Tax']['CLASS_A_WAGE_STANDARD'] = $tax_pay['CLASS_A_WAGE'] ?? 0;
            $voucher['Tax']['RESIDENT_TAX_STANDARD'] = $tax_pay['CLASS_A_WAGE'] ?? 0;
        }


        // 퇴직적립금 적립방식
        if ($retirement_pay_type) {
            $voucher['Retirement'] = Tax::retirement($request, $key, $voucher['Payment']) ?? 0;
            $voucher['Retirement_STANDARD'] = Tax::retirement($request, $key, $voucher['Standard']['PAY_TOTAL']) ?? 0;
        }



        // 활동지원사공제합계
        $voucher['WorkerTaxTotal']
            = $voucher['Tax']['WORKER_NATIONAL']
            + $voucher['Tax']['WORKER_HEALTH']
            + $voucher['Tax']['WORKER_EMPLOY']
            + $voucher['Tax']['CLASS_A_WAGE']
            + $voucher['Tax']['RESIDENT_TAX'];

        $voucher['WorkerTaxTotal_STANDARD']
            = $voucher['Tax']['WORKER_NATIONAL_STANDARD']
            + $voucher['Tax']['WORKER_HEALTH_STANDARD']
            + $voucher['Tax']['WORKER_EMPLOY_STANDARD']
            + $voucher['Tax']['CLASS_A_WAGE_STANDARD']
            + $voucher['Tax']['RESIDENT_TAX_STANDARD'];




        // 사업주공제합계(퇴직연금, 반납승인사업주 포함 )
        $voucher['CompanyTaxTotal']
            = $voucher['Tax']['COMPANY_NATIONAL']
            + $voucher['Tax']['COMPANY_HEALTH']
            + $voucher['Tax']['COMPANY_INDUSTRY']
            + $voucher['Tax']['COMPANY_EMPLOY']
            + $voucher['Retirement']
            + (($voucher['Return']['COUNTRY']['PAYMENT_TOTAL'] + $voucher['Return']['CITY']['PAYMENT_TOTAL']) * 0.236);


        // 사업주공제합계(퇴직연금, 반납승인사업주 포함 )
        $voucher['CompanyTaxTotal_STANDARD']
            = $voucher['Tax']['COMPANY_NATIONAL_STANDARD']
            + $voucher['Tax']['COMPANY_HEALTH_STANDARD']
            + $voucher['Tax']['COMPANY_INDUSTRY_STANDARD']
            + $voucher['Tax']['COMPANY_EMPLOY_STANDARD']
            + $voucher['Retirement_STANDARD']
            + (($voucher['Return']['COUNTRY']['PAYMENT_TOTAL'] + $voucher['Return']['CITY']['PAYMENT_TOTAL']) * 0.236);


        // 사업주 사업수입 (바우처합계+예외청구) - 근로기준법합계 (예외청구는 수기입력란이라 결과창에서 다시 계산되어야 한다)
        $voucher['CompanyBusinessTotal'] = ($voucher['Voucher']['COUNTRY']['PAYMENT_TOTAL']
                + $voucher['Voucher']['CITY']['PAYMENT_TOTAL'] + 0) - $voucher['Standard']['PAY_TOTAL'];

        // =(AB37+AP37)-BB37-IF($BS$1,BS37,0)
        $voucher['CompanyBusinessTotal_STANDARD'] = ($voucher['Voucher']['COUNTRY']['PAYMENT_TOTAL']
                + $voucher['Voucher']['CITY']['PAYMENT_TOTAL'] + 0) - $voucher['Standard']['PAY_TOTAL'];



        return $voucher;
    }

    // 지원사의 퇴직적립금 월 고정액 가져오기
    public static function retirement($request, $provider_key, $payment_voucher_total)
    {
        $pay = 0;
        $type = $request->input("retirement_saving_pay_type");
        switch ($type)
        {
            case "a" :
                $pay = round($payment_voucher_total / 12);
                break;
            case "b":
                $pay = round($payment_voucher_total * 0.083);
                break;
            case "company":
                $percent = $request->input("retirement_saving_pay_company_percentage");
                $pay = round($payment_voucher_total * $percent);
                break;
            case "fix":
                $pay = Worker::getRetirementPay($provider_key);
                break;
        }

        return $pay ?? 0;
    }

    public static function percentage_normal($provider_key, $request, $payment_total)
    {
        // 산재요율(기관. 기관만있음)
        $industry_tax_percentage = $request->input("industry_tax_percentage") ?? 0;

        // 고용보험료율(기관)
        $employ_company_type = $request->input("employ_tax_selector");
        $employ_percentage_add = 0;

        switch ($employ_company_type)
        {
            case "basic" :
                $employ_percentage_add = 0;
                break;
            case "150under" :
                $employ_percentage_add = 0.25;
                break;
            case "150over" :
                $employ_percentage_add = 0.45;
                break;
            case "1000under" :
                $employ_percentage_add = 0.65;
                break;
            case "1000over" :
                $employ_percentage_add = 0.85;
                break;
        }

        $national_pension_percentage = 4.50 / 100; // 국민연금료율
        $health_percentage = 3.43 / 100; // 건강보험료율
        $long_rest_percentage = 11.52 / 100; // 장기요양료율 사용X
        $employ_pension_worker_percentage = 0.80 / 100; // 고용보험료율 본인
        $employ_pension_company_percentage = (0.80+$employ_percentage_add) / 100; // 고용보험료율 기관
        $industry_percentage = $industry_tax_percentage / 100; // 산재보험료율 기관. 엑셀에서 0.7%입력되있고 실제로는 0.007로 계산되는걸로 보아 / 100이 맞는듯.

        $national_pension_tax = $payment_total * $national_pension_percentage; // 국민연금
        $health_tax = $payment_total * $health_percentage; // 건강보험
        $employ_pension_worker_tax = $payment_total * $employ_pension_worker_percentage; // 고용보험(본인)
        $employ_pension_company_tax = $payment_total * $employ_pension_company_percentage; // 고용보험(기관)
        $industry_tax = $payment_total * $industry_percentage; // 산재보험(기관만있음)



        // 갑근세
        $class_a_wages =  self::class_a_wages([
            "payment" => $payment_total,
            "target_id" => $provider_key
        ]);

        return [
            "national_pension" => $national_pension_tax,
            "health" => $health_tax,
            "employ_worker" => $employ_pension_worker_tax,
            "employ_company" => $employ_pension_company_tax,
            "industry" => $industry_tax,
            "gabgeunse" => $class_a_wages
        ];
    }



    public static function percentage($request, $provider_key, $payment_voucher_total, $bosuCheck)
    {

        if (isset($bosuCheck->percentage_apply) && $bosuCheck->percentage_apply == 1) {
            $national_pension_tax = $bosuCheck->national_ins_bosu_price;
            $health_tax = $bosuCheck->health_ins_bosu_price;
            $employ_pension_worker_tax = $bosuCheck->employ_ins_bosu_price;
        } else {
            // 산재요율(기관. 기관만있음)
            $industry_tax_percentage = $request->input("industry_tax_percentage") ?? 0;

            // 고용보험료율(기관)
            $employ_company_type = $request->input("employ_tax_selector");
            $employ_percentage_add = 0;

            switch ($employ_company_type)
            {
                case "basic" :
                    $employ_percentage_add = 0;
                    break;
                case "150under" :
                    $employ_percentage_add = 0.25;
                    break;
                case "150over" :
                    $employ_percentage_add = 0.45;
                    break;
                case "1000under" :
                    $employ_percentage_add = 0.65;
                    break;
                case "1000over" :
                    $employ_percentage_add = 0.85;
                    break;
            }

            $national_pension_percentage = 4.50 / 100; // 국민연금료율
            $health_percentage = 3.43 / 100; // 건강보험료율
            $long_rest_percentage = 11.52 / 100; // 장기요양료율 사용X
            $employ_pension_worker_percentage = 0.80 / 100; // 고용보험료율 본인
            $employ_pension_company_percentage = (0.80+$employ_percentage_add) / 100; // 고용보험료율 기관
            $industry_percentage = $industry_tax_percentage / 100; // 산재보험료율 기관. 엑셀에서 0.7%입력되있고 실제로는 0.007로 계산되는걸로 보아 / 100이 맞는듯.

            $national_pension_tax = $payment_voucher_total * $national_pension_percentage; // 국민연금
            $health_tax = $payment_voucher_total * $health_percentage; // 건강보험
            $employ_pension_worker_tax = $payment_voucher_total * $employ_pension_worker_percentage; // 고용보험(본인)
            $employ_pension_company_tax = $payment_voucher_total * $employ_pension_company_percentage; // 고용보험(기관)
            $industry_tax = $payment_voucher_total * $industry_percentage; // 산재보험(기관만있음)
        }




        // 갑근세
        $class_a_wages =  self::class_a_wages([
            "payment" => $payment_voucher_total,
            "target_id" => $provider_key
        ]);


        return [
            "WORKER_NATIONAL" => $national_pension_tax ?? 0,
            "COMPANY_NATIONAL" => $national_pension_tax ?? 0,
            "WORKER_HEALTH" => $health_tax ?? 0,
            "COMPANY_HEALTH" => $health_tax ?? 0,
            "COMPANY_INDUSTRY" => $industry_tax ?? 0,
            "WORKER_EMPLOY" => $employ_pension_worker_tax ?? 0,
            "COMPANY_EMPLOY" => $employ_pension_company_tax ?? 0,
            "CLASS_A_WAGE" => $class_a_wages ?? 0, // 갑근세
            "RESIDENT_TAX" => round($class_a_wages * 0.1, 1)
        ];

    }

    // 공제액 구하기. 시간정보 필요하면 $request가 필요함. 현재는 없음
    public static function tax($request, $provider_key, $payment_voucher_total)
    {
        $bosu = HelperInsurance::getOne($provider_key);

        if (isset($bosu)) {

            $national = $bosu->national_ins_bosu_price != 0 ? $bosu->national_ins_bosu_price : EDI::get_national_pension($provider_key);
            $health = $bosu->health_ins_bosu_price != 0 ? $bosu->health_ins_bosu_price : EDI::get_health_ins($provider_key);
            $industry = $bosu->industry_ins_bosu_price != 0 ? $bosu->industry_ins_bosu_price : EDI::get_industry_ins($provider_key);
            $employ = $bosu->employ_ins_bosu_price != 0 ? $bosu->employ_ins_bosu_price : EDI::get_employment_ins($provider_key);

        } else {

            $national = EDI::get_national_pension($provider_key);
            $health = EDI::get_health_ins($provider_key);
            $industry = EDI::get_industry_ins($provider_key);
            $employ = EDI::get_employment_ins($provider_key);

        }


        // 갑근세
        $gabgeunse = Worker::getGabgeunse($provider_key);

        return [
            "WORKER_NATIONAL" => $national ?? 0, // 국민연금본인
            "COMPANY_NATIONAL" => $national ?? 0, // 국민연금기관
            "WORKER_HEALTH" => $health ?? 0, // 건강보험본인
            "COMPANY_HEALTH" => $health ?? 0, // 건강보험기관
            "COMPANY_INDUSTRY" => $industry ?? 0, // 산재기관(산재는 기관만있음)
            "WORKER_EMPLOY" => $employ['personal'] ?? 0, // 고용본인
            "COMPANY_EMPLOY" => $employ['company'] ?? 0, // 고용기관
            "CLASS_A_WAGE" => $gabgeunse ?? 0, // 갑근세
            "RESIDENT_TAX" => round($gabgeunse * 0.1)
        ];

    }


    // 갑근세 구하기
    public static function class_a_wages($data)
    {
        $user_id = User::get_user_id();

        $target_id = $data['target_id'];
        $payment = $data['payment'];

        // 활동지원사의 갑근세요율을 가져온다
//        $provider_info = DB::table("workers")
//            ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
//            ->where("workers.user_id", "=", $user_id)
//            ->where("workers.target_id", "=", $target_id)
//            ->first();

        $provider_info = DB::table("helpers")->from("helpers as t1")
            ->leftJoin("helper_details_second", function ($join) {
                $join->on("t1.user_id", "=", "helper_details_second.user_id");
                $join->on("t1.target_key", "=", "helper_details_second.target_id");
            })
            ->where("t1.user_id", "=", $user_id)
            ->where("t1.target_key", "=", $target_id)
            ->first();

        $gabgeunse_percentage = $provider_info->gabgeunse_percentage ?? 100;
        $dependents = isset($provider_info->dependents) ?? 1;

//        if (!$provider_info) {
//            return 0;
//        }

        // 지급총액의 세율을 간이세액표에서 부양가족수에 따라 가져온다
        $getSimplifiedTax = self::getSimplifiedTax([
            "familyCount" => $dependents <= 11 ?: 11,
            "payment" => $payment
        ]);

        $class_a_wages = $getSimplifiedTax * ($gabgeunse_percentage/100); // 갑근세요율 정하기

        return $class_a_wages;
    }


    // 간이세액표 106만원 이하는 간이세액표에 없어서 0
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
        return $list[$columns[$familyCount]] ?? 0;
    }
}
