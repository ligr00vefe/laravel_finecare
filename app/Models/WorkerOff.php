<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkerOff extends Model
{
    use HasFactory;

    public static  function get($request)
    {
        $user_id = User::get_user_id();
        $from_date = date("Y-m-d", strtotime($request->input("from_date")));
        $term = $request->input("term");

        $detach = DB::table("helpers")
            ->select("helpers.*")
            ->where("user_id", "=", $user_id)
            ->when($term, function($query, $term) {
                return $query->where("target_key", "like", "%{$term}%");
            })
            ->whereRaw("NOT EXISTS (SELECT * FROM voucher_logs WHERE user_id = ? AND provider_key = helpers.target_key AND target_ym = ?)", [
                $user_id, date("Y-m-d", strtotime($from_date))
            ]);

        $paging = $detach->count();
        $lists = $detach->paginate();


        // 급여계산으로 마지막 근무 월 찾기
        foreach ($lists as $key => $list)
        {
            $exist = DB::table("payment_workers_info")
                ->where("target_ym", "<", date("Y-m-d", strtotime($from_date)))
                ->where("user_id", $user_id)
                ->where("provider_key", $list->target_key)
                ->first();

            if ($exist) {
                $lists[$key]->last_exist = true;
                $from = Carbon::parse($exist->target_ym);
                $to = Carbon::parse($from_date);
                $last_work = $to->diffInMonths($from) . " 달";
                $lists[$key]->last_work = $exist->target_ym;
                $lists[$key]->rest_day = $last_work;
            } else {
                $lists[$key]->last_exist = false;
                $lists[$key]->last_work = false;
                $lists[$key]->rest_day = false;
            }
        }

        return [ "lists" => $lists, "paging" => $paging ];
    }
}
