<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberSerivce extends Model
{
    use HasFactory;

    public static function get($request)
    {
        $target_key = $request->input("key");
        $user_id = User::get_user_id();
        $logs = DB::table("voucher_logs")->where("user_id", "=", $user_id)->where("target_key", "=", $target_key)->get();

        $lists = [];

        foreach ($logs as $key => $log)
        {

            if (!isset($lists[$log->provider_key])) {
                $lists[$log->provider_key] = [
                    "name" => $log->provider_name,
                    "birth" => $log->provider_birth,
                    "time" => 0
                ];
            }

            $lists[$log->provider_key]['time'] += (float) $log->payment_time;

        }

        return $lists;

    }
}
