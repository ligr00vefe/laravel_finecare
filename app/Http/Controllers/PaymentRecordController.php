<?php

namespace App\Http\Controllers;

use App\Classes\Custom;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentRecordController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->input("from_date") ?? date("Y-m-d");
        $user_id = User::get_user_id();
        $condition = DB::table("payment_conditions")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", "=", date("Y-m-d", strtotime($from_date)))
            ->first();

        $lists = DB::table("view_payment_total")
            ->where("user_id", "=", $user_id)
            ->where("target_ym", "=", date("Y-m-d", strtotime($from_date)))
            ->get();

        /* 합계 */
        $total = return_array_payment_total();

        foreach ($lists as $list)
        {
            // 합계구하기

            foreach ($list as $key => $val)
            {
                if (in_array($key, [
                    "id", "user_id", "provider_key", "target_ym", "save_selector", "bank_name", "bank_number", "provider_name", "join_date", "resign_date",
                    "nation_ins", "health_ins", "industry_ins", "employ_ins", "retirement", "created_at", "updated_at"
                ])) {
                    continue;
                }

                if ($key == "year_rest_count" && $val == "정보없음") continue;

                if (!isset($total[$key])) continue;
                $total[$key] += removeComma($val);
            }

        }


        return view("record.payment.index", [
            "lists" => $lists,
            "condition" => $condition,
            "total" => $total
        ]);
    }
}
