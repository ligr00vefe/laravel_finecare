<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Addon extends Model
{
    use HasFactory;

    public static function statistics($request)
    {
        $user_id = User::get_user_id();
        $year = $request->input("from_date");

        $date1 = date("Y-m-d", strtotime($year."-01-01"));
        $date2 = date("Y-m-d", strtotime($year."-12-31"));

        $dates = [
            "-01-01", "-02-01", "-03-01", "-04-01", "-05-01", "-06-01", "-07-01", "-08-01", "-09-01", "-10-01", "-11-01", "-12-01"
        ];

        $worker_count = [];
//
//        foreach ($dates as $date)
//        {
//            $date1 = date("Y-m-d", strtotime($year.$date));
//            $get = DB::table("workers")
//                ->selectRaw("MONTH(`regdate`) AS `date`, ANY_VALUE(regdate), COUNT(id) ")
//                ->where("user_id", "=", $user_id)
//                ->whereRaw("join_date >= ? AND ")
//                ->get();
//
//            $worker_count[$date] = "";
//
//        }

    }
}
