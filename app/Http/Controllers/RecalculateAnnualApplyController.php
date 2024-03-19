<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecalculateAnnualApplyController extends Controller
{
    public function index(Request $request)
    {
        $user_id = User::get_user_id();
        $target_ym = $request->input("target_ym");
        $provider_name = $request->input("provider_name") ?? null;
        $provider_birth = $request->input("provider_birth") ?? null;
        $provider_key = false;
        if ($provider_name && $provider_birth) {
            $provider_key = $provider_name . $provider_birth;
        }

        $recalculates = DB::table("day_off_recalculate_records")
            ->where("user_id","=",$user_id)
            ->where("year", "=", date("Y", strtotime($target_ym)))
            ->when($provider_name, function ($query, $provider_name) {
                return $query->where("provider_key", "like", "%{$provider_name}%");
            })
            ->when($provider_key, function ($query, $provider_key){
                return $query->where("provider_key", "=", $provider_key);
            })
            ->get();

        $lists = [];

        foreach ($recalculates as $recalc)
        {
            $calculate = DB::table("view_payment_total")
                ->selectRaw("provider_key, sum(replace(standard_yearly_payment, ',', '')) as sumOffDayPayment,
                group_concat(date_format(target_ym, '%m') order by id) as payment_month")
                ->where("user_id", "=", $recalc->user_id)
                ->where("provider_key", "=", $recalc->provider_key)
                ->whereBetween("target_ym", [date("Y-m-d", strtotime($target_ym . "-01-01")), date("Y-m-d", strtotime($target_ym."12-31"))])
                ->groupBy("provider_key")
                ->first();

            $lists[$recalc->provider_key]['recalc'] = $recalc;
            $lists[$recalc->provider_key]['calc'] = $calculate;
        }


        return view("recalculate.annualApply.index", [
            "lists" => $lists,
            "target_ym" => $target_ym,
            "provider_name" => $provider_name,
            "provider_birth" => $provider_birth
        ]);
    }
}
