<?php
/**
 * Created by PhpStorm.
 * User: BARAEM_programer2
 * Date: 2021-03-22
 * Time: 오후 6:20
 */

namespace App\Classes;


use Illuminate\Support\Facades\DB;

class User
{


    public static function get_expiration_date($user_id)
    {
        $isCheck = DB::table("user_goods_lists")
            ->where("user_id", "=", $user_id)
            ->exists();
        if (!$isCheck) return 0;

        $start_date = date("Y-m-d");


        $end_date = DB::table("user_goods_lists")
            ->select("end_date")
            ->where("user_id", "=", $user_id)
            ->where("end_date", ">=", $start_date)
            ->orderByDesc("end_date")
            ->first()->end_date ?? $start_date;


        $date1 = new \DateTime($start_date);
        $date2 = new \DateTime($end_date);
        $interval = $date1->diff($date2);

        return $interval->days;
    }

}
