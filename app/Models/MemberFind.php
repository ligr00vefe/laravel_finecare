<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberFind extends Model
{
    use HasFactory;


    public static function search($request) : array
    {

        $user_id = User::get_user_id();
        $possible_service = $request->input("possible_service") ?? false;
        $member_gender = $request->input("member_gender") ?? false;
        $member_age_type = $request->input("member_age_type") ?? false;
        $member_trauma_disorder_check = $request->input("member_trauma_disorder_check") ?? false;

        $location_1 = $request->input("location_1") ?? false;
        $location_2 = $request->input("location_2") ?? false;
        $location_3 = $request->input("location_3") ?? false;

        $get = DB::table("clients")
            ->join("client_details", "clients.target_key", "=", "client_details.target_key", "left outer")
            ->select("clients.*")
            ->where("clients.user_id", "=", $user_id)
            ->when($member_gender, function ($query, $member_gender) {
                if (in_array("any", $member_gender)) {
                    return $query;
                }

                $conditions = "";
                if (in_array("female", $member_gender)) {
                    $conditions = "((clients.rsNo LIKE '%______-2%' OR clients.rsNo LIKE '%______-4%') ";
                }
                if (in_array("male", $member_gender)) {
                    if ($conditions == "") $conditions .= "( ";
                    else $conditions .= "OR ";
                    $conditions .= "(clients.rsNo LIKE '%______-1%' OR clients.rsNo LIKE '%______-3%') ";
                }

                if ($conditions != "") $conditions .= ") ";
                return $query->whereRaw($conditions);

            })
            ->when($member_age_type, function ($query, $member_age_type) {

                if (in_array("무관", $member_age_type)) {
                    return $query;
                }

                $having = "CEIL( (CAST(REPLACE(CURRENT_DATE, '-', '') AS UNSIGNED) -
                    CAST(if(SUBSTR(clients.rsNo, 8, 1) = '1' OR SUBSTR(clients.rsNo,8,1) = '2',
                    CONCAT('19', SUBSTR(clients.rsNo, 1, 2), SUBSTR(clients.rsNo, 3, 4)), CONCAT('20', SUBSTR(clients.rsNo, 1, 2), SUBSTR(clients.rsNo, 3, 4))) AS UNSIGNED)) / 10000) + 1 ";

                $conditions = "";

                if (in_array("아동", $member_age_type)) {
                    $conditions = "({$having} < 9) ";
                }
                if (in_array("청소년", $member_age_type)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "({$having} >= 9 AND {$having} <= 19) ";
                }
                if (in_array("성인", $member_age_type)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "({$having} >= 20 AND {$having} < 65) ";
                }

                if (in_array("노인", $member_age_type)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "{$having} >= 65 ";
                }

                return $query->havingRaw($conditions);

            })
            ->when($location_1, function ($query, $location_1) use ($location_2, $location_3) {
                /* 주소 검색 */
                if ($location_3) {
                    return $query->where("clients.address", "like", "%{$location_3}%");
                }

                if ($location_2) {
                    return $query->where("clients.address", "like", "%{$location_2}%");
                }

                return $query->where("clients.address", "like", "%{$location_1}%");

            })
            ->when($member_trauma_disorder_check, function ($query, $member_trauma_disorder_check) {
                return $query->where("client_details.wasang_check", "!=", "Y");
            });



        // count() 안되서 변경 21.03.05
        $paging = count($get->get());
        $lists = $get->paginate(15);

        return [ "lists" => $lists, "paging" => $paging ];
    }
}
