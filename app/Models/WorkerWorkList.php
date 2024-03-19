<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkerWorkList extends Model
{
    use HasFactory;

    public static function get($request)
    {
        $term = $request->input("term") ?? false;

        $user_id = User::get_user_id();
        $from_date = date("Y-m-d", strtotime($request->input("from_date")));
        $query = DB::table("voucher_logs")
            ->selectRaw("
                provider_key,
                ANY_VALUE(voucher_logs.target_key) as target_key,
                ANY_VALUE(voucher_logs.id) as id,
                ANY_VALUE(voucher_logs.provider_name) as provider_name, 
                ANY_VALUE(voucher_logs.provider_birth) as provider_birth, 
                ANY_VALUE(sum(voucher_logs.payment_time)) as payment_time, 
                ANY_VALUE(voucher_logs.service_start_date_time) as service_start_date_time,
                ANY_VALUE(GROUP_CONCAT(DATE_FORMAT(voucher_logs.service_start_date_time, '%Y-%m-%d'))) AS dateConcat
            ")
            ->join("helpers", "helpers.target_key", "=", "voucher_logs.provider_key", "")
            ->where("voucher_logs.user_id", "=", $user_id)
            ->where("voucher_logs.target_ym", "=", $from_date)
            ->when($term, function ($query, $term) {
                return $query->where("voucher_logs.provider_name", "like", "%{$term}%");
            })
            ->groupBy("voucher_logs.provider_key");


        $totalCount = $query->get();
        $paging = count($query->get());
        $logs = $query->orderByRaw("ANY_VALUE(id) DESC")->paginate(15);


        $total = [
            "time_total" => 0,
            "day_total" => 0,
            "workers" => 0,
            "members" => [],
            "member_count" => 0
        ];

        $total['member_count'] = count(DB::table("voucher_logs")
            ->select("target_key")
            ->where("user_id", $user_id)
            ->where("target_ym", "=", $from_date)
            ->groupBy("target_key")
            ->get());

        foreach ($totalCount as $tt) {
            $total['time_total'] += $tt->payment_time;
            $total['day_total'] += count(array_unique(explode(",", $tt->dateConcat)));
            $total['workers']++;
//            $total['members'][] = $tt->target_key;
        }

//        pp($total['members']);


//        $total['member_count'] = count($total['members']);

        return [ "paging" => $paging, "lists" => $logs, "total" => $total ];
    }


    public static function reload($request)
    {
        $user_id = User::get_user_id();
        $provider_key = $request->input("key");
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));

        $query = DB::table("voucher_logs")
            ->selectRaw("ANY_VALUE(target_name) as target_name, ANY_VALUE(target_birth) as target_birth, ANY_VALUE(sum(payment_time)) as payment_time")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", "=", $target_ym)
            ->where("provider_key", "=", $provider_key)
            ->groupBy("target_key")
            ->get();

        return $query;
    }

}
