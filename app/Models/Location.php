<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class Location extends Model
{
    use HasFactory;
    protected $table = "locations";


    public static function get($keyword=false, $depth=1) : Object
    {
        return DB::table("locations")
            ->selectRaw("SUBSTRING_INDEX(ADMNM, ' ', ?) AS name", [ $depth ])

            // 도시, 구, 군 등 작은 단위 모두
            ->when($keyword, function ($query, $keyword) {
                return $query->where("ADMNM", "LIKE", "%{$keyword}%")->where("ADMNM", "!=", $keyword);
            })
            // 특별시, 광역시, 시도일 때
            ->when(!$keyword, function ($query, $keyword) {
                return $query->whereRaw("NOT ADMNM LIKE '% %'");
            })
            ->where("ADMNM", "LIKE", "%{$keyword}%")
            ->groupBy("name")
            ->get();
    }

    public static function getDetail($keyword, $depth) : Object
    {
        return DB::table("locations")
            ->selectRaw("SUBSTRING_INDEX(ADMNM, ' ', ?) AS ADMNM", [ $depth ])
            ->where("ADMNM", "LIKE", "%{$keyword}%")
            ->groupByRaw("SUBSTRING_INDEX(ADMNM, ' ', ?) AS ADMNM", [ $depth ])
            ->get();
    }



}
