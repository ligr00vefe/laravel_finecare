<?php

namespace App\Models;

use App\Classes\Custom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiceExtra extends Model
{
    use HasFactory;
    protected $table = "service_extra_logs";
    protected $fillable = [ "user_id", ];

    public static function get($request, $key)
    {
        $user_id = User::get_user_id();
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));
        $name = Custom::regexOnlyStr($key);
        $birth = Custom::regexOnlyNumber($key);

        return DB::table("service_extra_logs")
                ->where("target_ym", "=", $target_ym)
                ->where("user_id", "=", $user_id)
                ->where("provider_name", "=", $name)
                ->where("provider_birth", "=", $birth)
                ->get();
    }


    public static function add($request, $key)
    {
        $user_id = User::get_user_id();
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));
        $name = Custom::regexOnlyStr($key);
        $birth = Custom::regexOnlyNumber($key);

        return DB::table("service_extra_logs")
            ->selectRaw("(if(sum(confirm_pay) != 0, SUM(confirm_pay), 0) + if(SUM(add_price) != 0, SUM(add_price), 0)) AS payment")
            ->where("target_ym", "=", $target_ym)
            ->where("user_id", "=", $user_id)
            ->where("provider_name", "=", $name)
            ->where("provider_birth", "=", $birth)
            ->first()->payment ?? 0;
    }

    public static function getDate($request, $key)
    {
        $user_id = User::get_user_id();
        $target_ym = date("Y-m-d", strtotime($request->input("from_date")));
        $name = Custom::regexOnlyStr($key);
        $birth = Custom::regexOnlyNumber($key);

    }

}
