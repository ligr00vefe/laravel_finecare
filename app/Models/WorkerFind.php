<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkerFind extends Model
{
    use HasFactory;

    public static function get($request)
    {
        $user_id = User::get_user_id();

        $city = $request->input("city") ?? false;
        $married_check = $request->input("married_check") ?? false;
        $academic_career = $request->input("academic_career") ?? false;
        $age = $request->input("age") ?? false;
        $healthy = $request->input("healthy") ?? false;
        $gender = $request->input("gender") ?? false;
        $has_car = $request->input("has_car") ?? false;
        $possible_service = $request->input("possible_service") ?? false;
        $member_age_type = $request->input("member_age_type") ?? false;
        $hope_work_time = $request->input("hope_work_time") ?? false;

        $location_1 = $request->input("location_1") ?? false;
        $location_2 = $request->input("location_2") ?? false;
        $location_3 = $request->input("location_3") ?? false;


        $query = DB::table("helpers")
            ->join("helper_details", "helpers.target_key","=", "helper_details.target_key", "left outer")
            ->join("helper_details_second", "helper_details.target_key", "=", "helper_details_second.target_id", "left outer")
            ->selectRaw("helpers.*")
            ->where("helpers.user_id", "=", $user_id)
            ->when($city, function ($query, $city) {
                return $query->whereRaw("helpers.address like ?", [ "%{$city}%" ]);
            })
            ->when($gender, function ($query, $gender) {
                $conditions = "";

                if (in_array("male", $gender)) {
                    $conditions .= "((helpers.birth like '%______-1%' OR helpers.birth like '%______-3%') ";
                }

                if (in_array("female", $gender)) {
                    if ($conditions == "") $conditions .= "(";
                    else $conditions .= "OR ";
                    $conditions .= "(helpers.birth like '%______-2%' OR helpers.birth like '%______-4%') ";
                }

                if ($conditions != "") $conditions .= ")";
                return $query->whereRaw($conditions);

            })
            ->when($location_1, function ($query, $location_1) use ($location_2, $location_3) {
                /* 주소 검색 */
                if ($location_3) {
                    return $query->where("helpers.address", "like", "%{$location_3}%");
                }

                if ($location_2) {
                    return $query->where("helpers.address", "like", "%{$location_2}%");
                }

                return $query->where("helpers.address", "like", "%{$location_1}%");

            })
            ->when($age, function ($query, $age) {

                $having = "CEIL( (CAST(REPLACE(CURRENT_DATE, '-', '') AS UNSIGNED) -
                    CAST(if(SUBSTR(helpers.birth, 8, 1) = '1' OR SUBSTR(helpers.birth,8,1) = '2',
                    CONCAT('19', SUBSTR(helpers.birth, 1, 2), SUBSTR(helpers.birth, 3, 4)), CONCAT('20', SUBSTR(helpers.birth, 1, 2), SUBSTR(helpers.birth, 3, 4))) AS UNSIGNED)) / 10000) + 1 ";

                $conditions = "";

                if (in_array("20대", $age)) {
                    $conditions = "({$having} >= 20 AND {$having} <= 29) ";
                }
                if (in_array("30대", $age)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "({$having} >= 30 AND {$having} <= 39) ";
                }
                if (in_array("40대", $age)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "({$having} >= 40 AND {$having} <= 49) ";
                }

                if (in_array("50대", $age)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "({$having} >= 50 AND {$having} <= 59) ";
                }

                if (in_array("60대", $age)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "({$having} >= 60 AND {$having} <= 69) ";
                }

                if (in_array("70대", $age)) {
                    if ($conditions != "") $conditions .= " OR ";
                    $conditions .= "({$having} >= 70 AND {$having} <= 79) ";
                }

                return $query->havingRaw($conditions);

            });


//        $query = DB::table("workers")
//            ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
//            ->where("workers.user_id", "=", $user_id)
//            ->when($city, function ($query, $city) {
//                return $query->whereRaw("workers.address like ?", [ "%{$city}%" ]);
//            })
//            ->when($married_check, function ($query, $married_check) {
//                $conditions = "";
//
//                if (in_array("미혼", $married_check)) {
//                    $conditions .= "((workers_detail.marriage_check = '미혼') ";
//                }
//
//                if (in_array("기혼", $married_check)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.marriage_check = '기혼') ";
//                }
//
//                if (in_array("기타", $married_check)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.marriage_check = '기타') ";
//                }
//
//                if ($conditions != "") $conditions .= ")";
//
//                return $query->whereRaw($conditions);
//
//            })
//            ->when($academic_career, function ($query, $academic_career) {
//                $conditions = "";
//
//                if (in_array("고졸이하", $academic_career)) {
//                    $conditions .= "((workers_detail.academic_career = '고졸이하') ";
//                }
//
//                if (in_array("대학교재학", $academic_career)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.academic_career = '대학교재학') ";
//                }
//
//                if (in_array("대졸이상", $academic_career)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.academic_career = '대졸이상') ";
//                }
//
//                if ($conditions != "") $conditions .= ")";
//
//                return $query->whereRaw($conditions);
//
//            })
//            ->when($age, function ($query, $age) {
//
//                $having = "CEIL( (CAST(REPLACE(CURRENT_DATE, '-', '') AS UNSIGNED) -
//                    CAST(if(SUBSTR(workers.rsNo, 8, 1) = '1' OR SUBSTR(workers.rsNo,8,1) = '2',
//                    CONCAT('19', SUBSTR(workers.rsNo, 1, 2), SUBSTR(workers.rsNo, 3, 4)), CONCAT('20', SUBSTR(workers.rsNo, 1, 2), SUBSTR(workers.rsNo, 3, 4))) AS UNSIGNED)) / 10000) + 1 ";
//
//                $conditions = "";
//
//                if (in_array("20대", $age)) {
//                    $conditions = "({$having} >= 20 AND {$having} <= 29) ";
//                }
//                if (in_array("30대", $age)) {
//                    if ($conditions != "") $conditions .= " OR ";
//                    $conditions .= "({$having} >= 30 AND {$having} <= 39) ";
//                }
//                if (in_array("40대", $age)) {
//                    if ($conditions != "") $conditions .= " OR ";
//                    $conditions .= "({$having} >= 40 AND {$having} <= 49) ";
//                }
//
//                if (in_array("50대", $age)) {
//                    if ($conditions != "") $conditions .= " OR ";
//                    $conditions .= "({$having} >= 50 AND {$having} <= 59) ";
//                }
//
//                if (in_array("60대", $age)) {
//                    if ($conditions != "") $conditions .= " OR ";
//                    $conditions .= "({$having} >= 60 AND {$having} <= 69) ";
//                }
//
//                if (in_array("70대", $age)) {
//                    if ($conditions != "") $conditions .= " OR ";
//                    $conditions .= "({$having} >= 70 AND {$having} <= 79) ";
//                }
//
//                return $query->havingRaw($conditions);
//
//            })
//            ->when($healthy, function ($query, $healthy) {
//                $conditions = "";
//
//                if (in_array("양호", $healthy)) {
//                    $conditions .= "((workers_detail.physical_condition = '양호') ";
//                }
//
//                if (in_array("보통", $healthy)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.physical_condition = '보통') ";
//                }
//
//                if (in_array("약함", $healthy)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.physical_condition = '약함') ";
//                }
//
//                if ($conditions != "") $conditions .= ")";
//
//                return $query->whereRaw($conditions);
//            })
//            ->when($gender, function ($query, $gender) {
//                $conditions = "";
//
//                if (in_array("남자", $gender)) {
//                    $conditions .= "((workers.rsNo like '%______-1%' OR workers.rsNo like '%______-3%') ";
//                }
//
//                if (in_array("여자", $gender)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers.rsNo like '%______-2%' OR workers.rsNo like '%______-4%') ";
//                }
//
//                if ($conditions != "") $conditions .= ")";
//                return $query->whereRaw($conditions);
//
//            })
//            ->when($has_car, function($query, $has_car) {
//                return $has_car == "예"
//                    ? $query->where("workers_detail.has_car", "=", 1)
//                    : $query->where("workers_detail.has_car", "=", 1);
//            })
//            ->when($possible_service, function($query, $possible_service) {
//                $conditions = "";
//
//                if (in_array("신체서비스", $possible_service)) {
//                    $conditions .= "((workers_detail.physical_service_check = 1)";
//                }
//
//                if (in_array("가사서비스", $possible_service)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.housekeeping_service_check = 1)";
//                }
//
//                if (in_array("사회활동서비스", $possible_service)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.social_service_check = 1)";
//                }
//
//                if ($conditions != "") $conditions .= ")";
//                return $query->whereRaw($conditions);
//
//            })
//            ->when($member_age_type, function ($query, $member_age_type) {
//                $conditions = "";
//
//                if (in_array("무관", $member_age_type)) {
//                    return $query;
//                }
//
//                if (in_array("아동", $member_age_type)) {
//                    $conditions .= "((workers_detail.service_age_group1 = 1) ";
//                }
//
//                if (in_array("청소년", $member_age_type)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.service_age_group2 = 1) ";
//                }
//
//                if (in_array("성인", $member_age_type)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.service_age_group3 = 1) ";
//                }
//
//                if (in_array("노인", $member_age_type)) {
//                    if ($conditions == "") $conditions .= "(";
//                    else $conditions .= "OR ";
//                    $conditions .= "(workers_detail.service_age_group4 = 1) ";
//                }
//
//                if ($conditions != "") $conditions .= ") ";
//                return $query->whereRaw($conditions);
//
//            });


        $paging = count($query->get());
        $lists = $query->paginate(15);

        return [ "paging" => $paging, "lists" => $lists ];

    }
}
