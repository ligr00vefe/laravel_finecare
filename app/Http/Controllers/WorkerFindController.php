<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\WorkerFind;
use Illuminate\Http\Request;

class WorkerFindController extends Controller
{

    public function index(Request $request)
    {
        $page = $request->input("page");

        $get = WorkerFind::get($request);
        $locations = Location::get();

        return view("worker.find", [
            "districts" => $request->input("districts"),
            "city" => $request->input("city"),
            "married_check" => $request->input("married_check"),
            "academic_career" => $request->input("academic_career"),
            "age" => $request->input("age"),
            "healthy" => $request->input("healthy"),
            "gender" => $request->input("gender"),
            "has_car" => $request->input("has_car"),
            "possible_service" => $request->input("possible_service"),
            "member_age_type" => $request->input("member_age_type"),
            "hope_work_time" => $request->input("hope_work_time"),
            "lists" => $get['lists'] ?? [],
            "paging" => $get['paging'] ?? 0,
            "page" => $page ?? 1,
            "locations" => $locations,
            "location_1" => $request->input("location_1") ?? false,
            "location_2" => $request->input("location_2") ?? false,
            "location_3" => $request->input("location_3") ?? false,
        ]);
    }


}
