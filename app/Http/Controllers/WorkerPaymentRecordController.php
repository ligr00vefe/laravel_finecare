<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkerPaymentRecordController extends Controller
{
    public function index(Request $request)
    {
        $user_id = User::get_user_id();
        $provider_key = $request->input("provider_name") . $request->input("birth");
        $target_y = $request->input("from_date") ?? false;

        $lists = DB::table("view_payment_total")
            ->where("user_id", "=", $user_id)
            ->where("provider_key", "=", $provider_key)
            ->when($target_y, function ($query, $target_y) {

                $start_date = date("Y-m-d", strtotime($target_y . "-01-01"));
                $end_date = date("Y-m-d", strtotime(($target_y+1) . "-01-01"));

                return $query->whereRaw("(target_ym >= ? AND target_ym < ?)", [ $start_date, $end_date ]);
            })
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


        return view("record.worker.payment.index", [
            "birth" => $request->input("birth") ?? null,
            "provider_name" => $request->input("provider_name") ?? null,
            "from_date" => $request->input("from_date") ?? null,
            "lists" => $lists,
            "total" => $total
        ]);
    }
}
