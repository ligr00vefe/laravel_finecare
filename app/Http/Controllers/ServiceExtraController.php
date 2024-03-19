<?php

namespace App\Http\Controllers;

use App\Models\ServiceExtra;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceExtraController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input("page") ?? 1;
        $target_ym = $request->input("from_date");
        $term = $request->input("term");

        $user_id = User::get_user_id();
        $_query = ServiceExtra::where("user_id", "=", $user_id)
            ->when($target_ym, function ($query, $target_ym) {
                return $query->where("target_ym", "=", date("Y-m-d", strtotime($target_ym)));
            })
            ->when($term, function ($query, $term) {
                return $query->whereRaw("provider_name like ?", [ "%{$term}%" ]);
            })
            ->orderByDesc("id");

        $lists = $_query->paginate(15);
        $paging = $_query->selectRaw("count(*) as paging")->first()->paging ?? 0;

        return view("service.extra.index", [ "page"=>$page, "lists"=>$lists, "paging" => $paging ]);
    }

    public function create()
    {
        return view("service.extra.create", []);
    }

    public function store(Request $request)
    {
        $user_id = User::get_user_id();
        $extra = new ServiceExtra;
        $extra->user_id = $user_id;
        $extra->target_ym = date("Y-m-d", strtotime($request->input("target_ym")));
        $extra->target_name = $request->input("target_name");
        $extra->target_birth = $request->input("target_birth");
        $extra->provider_name = $request->input("provider_name");
        $extra->provider_birth = $request->input("provider_birth");
        $extra->service_start_date_time = $request->input("service_start_date_time");
        $extra->service_end_date_time = $request->input("service_end_date_time");
        $extra->payment_time = $request->input("payment_time");
        $extra->confirm_pay = $request->input("confirm_pay");
        $extra->add_price = $request->input("add_price");
        $extra->local_government_name = $request->input("local_government_name");
        $extra->organization = $request->input("organization");
        $extra->etc = $request->input("etc");
        if ($extra->save()) {
            session()->flash("msg", "저장되었습니다.");
            return redirect()->route("service.extra.list");
        } else {
            session()->flash("error", "문제가 발생했습니다. 다시 시도해 주세요");
            return redirect()->route("service.extra.create");
        }
    }



}
