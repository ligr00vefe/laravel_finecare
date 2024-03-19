<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clients extends Model
{
    use HasFactory;
    protected $table = "clients";

    public static function get($request)
    {
        $user_id = User::get_user_id();
        $from_date = $request->input("from_date");
        $to_date = $request->input("to_date");
        $term = $request->input("term");

        $query = DB::table("clients")
//            ->join("client_details", "clients.target_key", "=", "client_details.target_key", "left outer")
            ->leftJoin("client_details", function ($join) {
                $join->on("client_details.user_id", "=", "clients.user_id");
                $join->on("client_details.target_key", "=", "clients.target_key");
            })
            ->selectRaw("clients.*, client_details.main_disable_name, client_details.main_disable_grade,
            client_details.main_disable_level, client_details.sub_disable_name, client_details.activity_grade, client_details.activity_grade_type,
            client_details.income_check, client_details.activity_grade, client_details.activity_grade_type
            ")
            ->where("clients.user_id", "=", $user_id)
            ->when($from_date, function ($query, $from_date) {
                return $query->where("clients.contract_start_date", ">=", $from_date);
            })
            ->when($to_date, function ($query, $to_date) {
                return $query->whereRaw("(clients.contract_start_date <= ? AND clients.contract_start_date != '1970-01-01')", [ $to_date ]);
            })
            ->when($term, function ($query, $term) {
                return $query->where("clients.name", "like", "%{$term}%");
            })
            ->orderBy("name");

        $paging = $query->count();
        $lists = $query->paginate();


        return [
            "lists" => $lists,
            "paging" => $paging,
        ];

    }

}
