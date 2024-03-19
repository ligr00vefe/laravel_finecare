<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\Counseling;
use Illuminate\Support\Facades\DB;


class CounselingController extends Controller
{
    public function index($type="all", Request $request)
    {
        $get = Counseling::get($type);
        $page = $request->input("page") ?? 1;
        return view("counseling.index", [ "type"=>$type, "page"=> $page, "lists"=>$get['lists'], "paging"=>$get['paging'] ]);
    }

    public function create($type, $id)
    {
        $get = Counseling::target_info($type, $id);
        return view("counseling.create", [ "type" => $type, "id"=>$id, "target" => $get ]);
    }

    public function store(Request $request)
    {
        $insert_id = Counseling::write($request);
        return redirect("/counseling/log/view/{$insert_id}");
    }

    public function show($id)
    {
        $user_id = User::get_user_id();
        $get = Counseling::find($id);
        if ($get->type == "worker") {
            $get->target_info = Worker::find($get->target_id);
        } else {
            $get->target_info = DB::table("clients")
                ->where("id", "=", $get->target_id)
                ->first();
//            $get->target_info = Member::find($get->target_id);
        }

        return view("counseling.show", [ "get" => $get, "id" => $id ]);
    }

    public function log(Request $request)
    {
        $page = $request->input("page") ?? 1;
        $type = $_GET['type'] ?? "all";
        $get = Counseling::logs($type, $page);

        return view("counseling.list", [
            "type" => $type,
            "page" => $page,
            "lists" => $get['lists'],
            "paging" => $get['paging'],
            "from_date" => $request->input("from_date"),
            "end_date" => $request->input("end_date")
        ]);
    }

    public function edit($id)
    {
        $get = Counseling::find($id);
        $target_info = Counseling::target_info($get->type, $get->target_id);

        return view("counseling.create", [ "id"=>$id, "target" => $target_info, "type" => $get->type, "edit" => $get  ]);
    }

    public function update(Request $request)
    {
        $id = $request->input("id");

        if (!$id || $id == "") {
            return back()->with("error", "수정하는데 실패했습니다. 다시 시도해주세요");
        }

        $update = Counseling::find($id);

        if (empty($update)) {
            return back()->with("error", "수정하는데 실패했습니다. 다시 시도해주세요");
        }

        $update->writer = $request->input("writer");
        $update->category = $request->input("category");
        $update->way = $request->input("way");
        $update->from_date = $request->input("from_date");
        $update->from_date_time = $request->input("from_date_time");
        $update->to_date = $request->input("to_date");
        $update->to_date_time = $request->input("to_date_time");
        $update->title = $request->input("title");
        $update->content = $request->input("content");
        $update->result = $request->input("result");
        if ($update->save())
        {
            return redirect()->route("counseling.show", [ "id" => $id ])->with("msg", "수정했습니다");
        }
        else
        {
            return back()->with("error", "수정하는데 실패했습니다. 다시 시도해주세요");
        }
    }

    public static function delete($id)
    {
        $user_id = User::get_user_id();
        $delete = Counseling::find($id);
        if ($user_id == $delete->user_id) {
            if ($delete->delete()) {
                return redirect()->route("counseling.log", [ "page" => 1])->with("msg", "삭제했습니다");
            } else {
                return back()->with("error", "삭제하는데 실패했습니다. 다시 시도해 주세요");
            }
        } else {
            return back()->with("error", "삭제하는데 실패했습니다. 다시 시도해 주세요");
        }
    }

}
