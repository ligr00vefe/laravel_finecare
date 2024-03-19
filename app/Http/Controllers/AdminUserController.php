<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\UserGoodsLists;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lists = AdminUser::get($request);
        $goods = Goods::all();

        if ($lists['code'] == 2) {
            return back();
        }
        return view("admin.user.index", [ "lists" => $lists['lists'], "paging" => $lists['paging'], "goods" => $goods ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.user.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->input());
        $create = AdminUser::create($request);

        if ($create)
        {
            session()->flash("success", "아이디를 생성했습니다.");
            return redirect()->route("admin.user");
        }
        else
        {
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        return view("admin.userCreate", [ "data" => User::get($id) ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view("admin.user.create", [
            "id"=>$id,
            "user" => User::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = AdminUser::change($request, $id);
        if ($update)
        {
            session()->flash("success", "회원정보를 수정했습니다.");
            return redirect()->route("admin.user");
        }
        else
        {
            back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::find($id);
        $delete->deleteCheck = 1;
        $delete->delete_date = date("Y-m-d H:i:s");

        if ($delete->save()) {
            return json_encode(["code"=>1, "msg"=>"success...!", "data" =>[]]);
        } else {
            return json_encode(["code"=>2, "msg"=>"fail...!", "data" =>[]]);
        }
    }


    public function goods(Request $request)
    {
        $user_id = $request->input("id");
        $goods = Goods::find($request->input("goods_id"));

        $now = date("Y-m-d");

        $start_date = DB::table("user_goods_lists")
            ->where("user_id", "=", $user_id)
            ->where("end_date", ">=", $now)
            ->orderByDesc("end_date")
            ->first()->end_date ?? "";

        if ($start_date == "") {
            $start_date = $now;
        }

        $end_date = date("Y-m-d", strtotime($start_date . "+ {$goods->period} days"));


        $userGoods = new UserGoodsLists;
        $userGoods->user_id = $user_id;
        $userGoods->goods_id = $goods->id;
        $userGoods->start_date = $start_date;
        $userGoods->end_date = $end_date;
        $userGoods->day_count = $goods->period;
        $userGoods->payment_type = $request->input("payment_type");
        $userGoods->payment_date = $request->input("payment_date");

        if ($userGoods->save())
        {
            return redirect()->route("admin.user")->with("msg", "회원에게 상품을 적용했습니다");
        }
        else
        {
            return back()->with("error", "적용하는데 실패했습니다. 다시 시도해 주세요.");
        }

    }

}
