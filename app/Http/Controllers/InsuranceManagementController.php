<?php

namespace App\Http\Controllers;

use App\Models\HelperInsurance;
use App\Models\Helpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsuranceManagementController extends Controller
{
    public function index()
    {
        $user_id = User::get_user_id();
        $helpers = DB::table("helpers")
            ->join("helper_insurance", "helper_insurance.helper_id", "=", "helpers.id", "left outer")
            ->join("helper_details", "helper_details.target_key", "=", "helper_insurance.target_key", "left outer")
            ->selectRaw("helpers.*, 
            
            helper_insurance.national_ins_bosu_price,
            
            helper_insurance.health_ins_bosu_price,
            helper_insurance.long_rest_subtract,
            
            helper_insurance.employ_ins_bosu_price,
            helper_insurance.employ_65age_after,
            helper_insurance.percentage_apply,
            
            helper_insurance.industry_ins_bosu_price,
            helper_details.national_ins_check,
            helper_details.health_ins_check,
            helper_details.employ_ins_check,
            helper_details.industry_ins_check,
            helper_details.baesang_ins_check,
            helper_details.retire_added_check
            ")
            ->where("helpers.user_id", $user_id)
            ->where("helper_details.user_id", $user_id)
            ->get();

        return view("social.insurance.index", [
            "helpers" => $helpers,
        ]);
    }

    public function store(Request $request)
    {
        $user_id = User::get_user_id();
        $helpers = $request->input("check") ?? 0;
        $target_key = $request->input("target_key");
        $national_ins_check = $request->input("national_ins_check");
        $national_ins_bosu_price = $request->input("national_ins_bosu_price");
        $health_ins_check = $request->input("health_ins_check");
        $health_ins_bosu_price = $request->input("health_ins_bosu_price");
        $long_rest_subtract = $request->input("long_rest_subtract");
        $employ_ins_check = $request->input("employ_ins_check");
        $employ_ins_bosu_price = $request->input("employ_ins_bosu_price");
        $employ_65age_after = $request->input("employ_65age_after");
        $industry_ins_check = $request->input("industry_ins_check");
        $industry_ins_bosu_price = $request->input("industry_ins_bosu_price");
        $percentage_apply = $request->input("percentage_apply");

        $insert = true;

        if ($helpers)
        {
            $insert = DB::transaction(function () use (
                $user_id, $helpers, $target_key, $national_ins_check, $national_ins_bosu_price, $health_ins_check, $health_ins_bosu_price, $long_rest_subtract,
            $employ_ins_check, $employ_ins_bosu_price, $employ_65age_after, $industry_ins_check, $industry_ins_bosu_price, $percentage_apply
            ) {
                foreach ($helpers as $helper)
                {
                    $insert = DB::table("helper_insurance")
                        ->updateOrInsert(
                            [ "user_id" => $user_id, "helper_id" => $helper ],
                            [
                                "target_key" => $target_key[$helper],
                                "national_ins_check" => $national_ins_check[$helper],
                                "national_ins_bosu_price" => $national_ins_bosu_price[$helper],
                                "health_ins_check" => $health_ins_check[$helper],
                                "health_ins_bosu_price" => $health_ins_bosu_price[$helper],
                                "long_rest_subtract" => $long_rest_subtract[$helper] ?? 0,
                                "employ_ins_check" => $employ_ins_check[$helper],
                                "employ_ins_bosu_price" => $employ_ins_bosu_price[$helper],
                                "employ_65age_after" => $employ_65age_after[$helper] ?? 0,
                                "industry_ins_check" => $industry_ins_check[$helper],
                                "industry_ins_bosu_price" => $industry_ins_bosu_price[$helper],
                                "percentage_apply" => $percentage_apply[$helper] ?? 0
                            ]
                        );

                    if (!$insert) return false;

                    $insert = DB::table("helper_details")
                        ->updateOrInsert(
                            [ "user_id" => $user_id, "target_key" => $target_key[$helper] ],
                            [
                                "national_ins_check" => $national_ins_check[$helper],
                                "health_ins_check" => $health_ins_check[$helper],
                                "employ_ins_check" => $employ_ins_check[$helper],
                                "industry_ins_check" => $industry_ins_check[$helper],
                            ]
                        );
                    if (!$insert) return false;
                }

            });

        }

        if ($insert)
        {
            return redirect()->route("social.insurance")->with("msg", "사회보험 기입정보를 수정했습니다");
        }
        else
        {
            return redirect()->back()->withErrors("수정에 실패했습니다. 다시 시도해 주세요");
        }


    }
}