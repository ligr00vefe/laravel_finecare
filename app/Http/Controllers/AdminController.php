<?php

namespace App\Http\Controllers;


use App\Models\Goods;
use App\Models\QNA;
use App\Models\User;
use App\Models\UserGoodsLists;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $count = User::count();
        $new = User::whereRaw("YEARWEEK(created_at) = YEARWEEK(now())")->count();
        $users = User::orderByDesc("id")->limit(5)->get();
        $payments = UserGoodsLists::orderByDesc("id")->limit(3)->get();
        $qnas = QNA::orderByDesc("id")->limit(5)->get();

        foreach ($users as $user)
        {
            $user->payments_info = UserGoodsLists::where("user_id", "=", $user->id)
                ->where("end_date", ">=", date("Y-m-d"))
                ->orderByDesc("end_date")
                ->first();
        }

        foreach ($payments as $payment)
        {
            $payment->goods_info = $payment->goods;
            $payment->user_info = $payment->user;
        }

        return view("admin.index", [
            "count" => $count,
            "new" => $new,
            "users" => $users,
            "payments" => $payments,
            "qnas" => $qnas
        ]);
    }
}
