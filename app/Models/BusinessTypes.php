<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BusinessTypes extends Model
{
    use HasFactory;
    protected $table = "business_types";

    public static function get()
    {
        $nation = DB::table("business_types")
            ->selectRaw("group_concat(name) as names")
            ->where("type", "=", "1")
            ->first();


        $sido = DB::table("business_types")
            ->selectRaw("group_concat(name) as names")
            ->where("type","=","2")
            ->first();

        $zibang = DB::table("business_types")
            ->selectRaw("group_concat(name) as names")
            ->where("type", "=", "3")
            ->first();

        $nation = explode(",", $nation->names);
        $sido = explode(",", $sido->names);
        $zibang = explode(",", $zibang->names);

        return [ "nation" => $nation, "sido" => $sido, "zibang" => $zibang ];

    }
}
