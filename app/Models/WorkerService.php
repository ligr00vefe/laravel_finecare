<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkerService extends Model
{
    use HasFactory;

    public static function get($request)
    {

        $user_id = User::get_user_id();
        $from_date = date("Y-m-d", strtotime($request->input("from_date")));
        $end_date = date("Y-m-d", strtotime($request->input("to_date")));

        $query = DB::table("voucher_logs")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", ">=", $from_date)
            ->where("target_ym", "<=", $end_date);

        $paging = $query->count();
        $lists = $query->get();


        $total = [
            "NATION" => [
                "DAY" => [],
                "TIME" => 0,
                "PRICE" => 0,
            ],
            "CITY" => [
                "DAY" => [],
                "TIME" => 0,
                "PRICE" => 0,
            ],
            "PROVINCE" => [
                "DAY" => [],
                "TIME" => 0,
                "PRICE" => 0,
            ],
        ];

        $providers = [];

        $bt = BusinessTypes::get();

        foreach ($lists as $list)
        {
            $btype = "NATION";

            if (!isset($providers[$list->provider_key])) {
                $providers[$list->provider_key] = $total;
            }

            if (in_array($list->business_type, $bt['nation'])) {
                $btype = "NATION";
            } else if (in_array($list->business_type, $bt['sido'])) {
                $btype = "CITY";
            } else if (in_array($list->business_type, $bt['zibang'])) {
                $btype = "PROVINCE";
            } else {
                $btype = "NATION";
            }

            $providers[$list->provider_key][$btype]['DAY'][] = date("Y-m-d", strtotime($list->service_start_date_time));
            $providers[$list->provider_key][$btype]['TIME'] += $list->payment_time;
            $providers[$list->provider_key][$btype]['PRICE'] += $list->confirm_pay;
        }

//        pp($providers);

        return [ "providers" => $providers, "lists" => $lists, "paging" => $paging ];
    }


    public static function calendar_reload($request)
    {

        $date = $request->input("date") ? date("Y-m-d", strtotime($request->input("date")."-01")) : "";
        $provider = $request->input("helper_id") ?? "";

        $user_id = User::get_user_id();
        $lists = [];
        $sort = [];
        $helpers = [];

        if ($request->input("member_id") == "") {
            return [ "lists"=>$lists, "sort"=>[], "helpers"=>$helpers ];
        }

        if ($request->input("type") == "activity_time")
        {
            $lists = DB::table("voucher_logs")
                ->where("user_id", "=", $user_id)
                ->where("target_key", "=", $request->input("provider_key"))
                ->when($date, function ($query, $date) {
                    return $query->whereRaw("service_start_date_time >= ? AND service_start_date_time <= LAST_DAY(?)", [ $date, $date ]);
                })
                ->when($provider, function($query, $provider) {
                    return $query->whereRaw("provider_key = ?", [ $provider ]);
                })
                ->orderByDesc("service_start_date_time")
                ->get();
        }

        else if ($request->input("type") == "activity_kind")
        {
            $lists = DB::table("voucher_logs")
                ->select(DB::raw("target_key, provider_key, ANY_VALUE(service_start_date_time) as service_start_date_time, 
                SEC_TO_TIME(sum(TIME_TO_SEC(social_activity_support))) AS sas_total, SEC_TO_TIME(sum(TIME_TO_SEC(physical_activity_support))) AS pas_total, SEC_TO_TIME(sum(TIME_TO_SEC(housekeeping_activity_support))) AS has_total, SEC_TO_TIME(sum(TIME_TO_SEC(etc_service))) AS es_total,
                SEC_TO_TIME(sum(TIME_TO_SEC(social_activity_support) + TIME_TO_SEC(physical_activity_support) + TIME_TO_SEC(housekeeping_activity_support) + TIME_TO_SEC(etc_service))) AS total,
                ANY_VALUE(business_type) as business_type,
                ANY_VALUE(payment_time) as payment_time
                "))
                ->where("user_id", "=", $user_id)
                ->where("target_key", "=", $request->input("member_id"))
                ->when($date, function ($query, $date) {
                    return $query->whereRaw("service_start_date_time >= ? AND service_start_date_time <= LAST_DAY(?)", [ $date, $date ]);
                })
                ->when($provider, function($query, $provider) {
                    return $query->whereRaw("provider_key = ?", [ $provider ]);
                })
                ->orderByDesc("service_start_date_time")
                ->groupByRaw("target_key, provider_key")
                ->get();
        }

        $helpers = DB::table("voucher_logs")
            ->select(DB::raw("provider_key, ANY_VALUE(provider_name) as provider_name, ANY_VALUE(provider_birth) as provider_birth"))
            ->where("user_id", "=", $user_id)
            ->where("target_key", "=", $request->input("member_id"))
            ->when($date, function ($query, $date) {
                return $query->whereRaw("service_start_date_time >= ? AND service_start_date_time <= LAST_DAY(?)", [ $date, $date ]);
            })
            ->groupByRaw("provider_key")
            ->get();

        $time_total = 0;

        foreach($lists as $key=>$list)
        {
            $date = date("Y-m-d", strtotime($list->service_start_date_time));
            $time_total += $list->payment_time;

            $business_type = DB::table("business_types")
                ->where("name", "=", $list->business_type)
                ->first();

            $list->type1 = 0;
            $list->type2 = 0;
            if (!$business_type) $list->type1 = $list->payment_time;
            if ($business_type->type == 1) $list->type1 = $list->payment_time;
            else if ($business_type->type == 2) $list->type2 = $list->payment_time;

            if (!isSet($sort[$date])) {
                $sort[$date] = [];
            }

            array_push($sort[$date], $list);
        }


        return [
            "lists" => $lists,
            "sort"=> $sort,
            "helpers" => $helpers,
            "total" => $time_total
        ];

    }
}
