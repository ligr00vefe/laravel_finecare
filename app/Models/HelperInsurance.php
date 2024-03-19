<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HelperInsurance extends Model
{
    use HasFactory;
    protected $table = "helper_insurance";
    protected $guarded = [ "id", "created_at", "updated_at" ];


    public static function getOne($key)
    {
        $user_id = User::get_user_id();

        return DB::table("helper_insurance")
            ->where("user_id", $user_id)
            ->where("target_key", $key)
            ->first() ?? null;
    }


    // 비율계산적용 가져오기
    public static function percentageApplyCheck($key)
    {
        $user_id = User::get_user_id();

        $percentage_check = DB::table("helper_insurance")
            ->where("user_id", $user_id)
            ->where("target_key", $key)
            ->first() ?? false;


        if ($percentage_check && $percentage_check->percentage_apply == 1) {
            return $percentage_check;
        } else {
            return null;
        }

    }
}
