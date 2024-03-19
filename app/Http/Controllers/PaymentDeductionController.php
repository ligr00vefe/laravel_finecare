<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentDeductionController extends Controller
{
    public function index(Request $request)
    {
        $user_id = User::get_user_id();
        $from_date = $request->input("from_date");

        $lists = [];

        if ($from_date)
        {
            $lists = DB::table("payment_taxs")
                ->where("user_id", $user_id)
                ->when($from_date, function ($query, $from_date) {
                    return $query->where("target_ym", "=", date("Y-m-d", strtotime($from_date)));
                })
                ->get();
        }



        return view("salary.deduction.index", [
            "lists" => $lists
        ]);
    }
}
