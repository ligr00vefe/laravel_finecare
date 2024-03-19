<?php

namespace App\Http\Controllers;

use App\Imports\WorkerTimetableImport;
use App\Models\User;
use App\Models\WorkerSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WorkerTimetableController extends Controller
{

    public function index(Request $request)
    {

        $target_ym = Carbon::createFromFormat("Ym", $request->input("target_ym"))->format("Y-m-d");
        $targetDate = new Carbon($target_ym);
        $last_day = $targetDate->endOfMonth()->format("d");


        $query = DB::table("helper_confirm_schedules")
            ->join("workers", "workers.target_id", "=", "helper_confirm_schedules.worker_id", "left outer")
            ->selectRaw("helper_confirm_schedules.worker_id, GROUP_CONCAT(helper_confirm_schedules.`date`) as dates")
            ->whereRaw("(helper_confirm_schedules.date > LAST_DAY(? - interval 1 month) AND helper_confirm_schedules.date <= LAST_DAY(?) ) ", [ $target_ym, $target_ym ])
            ->where("helper_confirm_schedules.work_type", "=", "근무")
            ->groupBy("worker_id");

        $lists = $query->get();

        foreach ($lists as $i => $list)
        {
            $lists[$i]->dates_arr = explode(",", $list->dates);
            $lists[$i]->dates_d = array_map(function ($ymd) {
                return (int) date("d", strtotime($ymd));
            }, $list->dates_arr);
            $lists[$i]->dates_arr = explode(",", $list->dates);
        }

        return View("worker.timetable.index", [
            "lists" => $lists,
            "ym" => $targetDate->format("Y-m"),
            "last_day" => $last_day
        ]);
    }

    public function create()
    {
        $last_updated = DB::table("helper_confirm_schedules")
            ->select("created_at")
            ->orderByDesc("created_at")
            ->first()->created_at ?? "";

        return View("worker.timetable.create",[
            "last_updated" => date("Y-m-d", strtotime($last_updated))
        ]);
    }

    public function store(Request $request)
    {


        $filename = $request->file("timetable")->getClientOriginalName();
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $extArr = [ "xlsx" ];
        if (!in_array($ext, $extArr)) {
            return back()->withErrors("잘못된 확장자입니다");
        }

        $upload = $request->file("timetable")->store("upload/worker/timetable");
        $import = new WorkerTimetableImport;
        Excel::import($import, $upload);

        if ($import->data == "197001") {
            return back()->with("error", "근로월이 잘못 됐습니다.");
        }

        if ($import)
        {
            return redirect()->route("worker.timetable", [ "target_ym" => $import->data ])->with("msg", "업로드에 성공했습니다");
        }
        else
        {
            return back()->withErrors("업로드에 실패했습니다");
        }
    }


    public function delete(Request $request)
    {
        $user_id = User::get_user_id();
        $target_ym = date("Y-m-d", strtotime($request->input("target_ym")));

        $delete = DB::table("helper_confirm_schedules")
            ->where("user_id", "=", $user_id)
            ->whereRaw("(date > LAST_DAY(? - interval 1 month) AND date <= LAST_DAY(?) ) ", [ $target_ym, $target_ym ])
            ->delete();

        if ($delete)
        {
            return redirect()->route("worker.timetable.create")->with("msg", "업로드 내역을 지웠습니다. 다시 업로드해주세요");
        }
        else
        {
            return back()->withErrors("실패했습니다. 다시 시도해주세요");
        }

    }

}
