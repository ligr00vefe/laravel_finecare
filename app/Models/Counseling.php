<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Counseling extends Model
{
    use HasFactory;
    protected $table = "counseling_logs";

    public static function get($type)
    {
        $page = $_GET['page'] ?? 1;

        $limit = $_GET['limit'] ?? 15;
        $offset = ((int)$page - 1) * 15;
        $now = date("Y-m-d");

        $term = $_GET['term'] ?? "";
        $from_date = $_GET['from_date'] ?? "";
        $to_date = $_GET['to_date'] ?? "";


        $user_id = DB::table("users")->where("remember_token", "=", session()->get("user_token"))->first("id")->id;

        $return = [];

        if ($type == "all")
        {
            $members = DB::table("clients")
                ->select(DB::raw("id, user_id, name, '이용자' as `table`, rsNo"))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                });

            $workers = DB::table("helpers")
                ->select(DB::raw("id, user_id, name, '활동지원사' as `table`, `birth` as rsNo"))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                })
                ->union($members)
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)
                ->get();

            $paging_members = DB::table("clients")
                ->select(DB::raw("count(*) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                })
                ->first("cnt")
                ->cnt;

            $paging_workers = DB::table("helpers")
                ->select(DB::raw("count(*) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                })
                ->first("cnt")
                ->cnt;

            $paging = $paging_members + $paging_workers;

            $return = [ "lists" => $workers, "paging" => $paging ];
        }

        else if ($type == "worker")
        {
            $workers = DB::table("helpers")
                ->select(DB::raw("id, user_id, name, '활동지원사' as `table`, `birth` as rsNo"))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                })
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)
                ->get();

            $paging = DB::table("helpers")
                ->select(DB::raw("count(*) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                })
                ->first("cnt")->cnt;

            $return = [ "lists" => $workers, "paging" => $paging ];
        }

        else if ($type == "member")
        {
            $members = DB::table("clients")
                ->select(DB::raw("id, user_id, name, '이용자' as `table`, rsNo, 'birth' "))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                })
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)
                ->get();

            $paging = DB::table("clients")
                ->select(DB::raw("count(*) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                    }

                })
                ->first("cnt")->cnt;

            $return = [ "lists" => $members, "paging" => $paging ];
        }

        return $return;
    }

    public static function target_info($type, $id)
    {

        $target = [];

        switch ($type)
        {

            case "member":
                $target = DB::table("clients as members")
                    ->where("members.id", "=", $id)
                    ->first();
                break;

            case "worker":
                $target = DB::table("helpers as workers")
                    ->where("workers.id", "=", $id)
                    ->first();
                break;

            default:
                break;

        }

        return $target;

    }


    public static function write($request)
    {
        $params = [];
        foreach ($request->input() as $key => $val)
        {
            if ($key == "_token") continue;
            $params[$key] = $val;
        }
        $params['user_id'] = DB::table("users")->where("remember_token", "=", session()->get("user_token"))->first("id")->id;;

        $insert = DB::table("counseling_logs")
            ->insertGetId($params);

        return $insert;
    }


    public static function logs($type, $page)
    {
        $offset = ((int)$page - 1) * 15;
        $now = date("Y-m-d");

        $term = $_GET['term'] ?? "";
        $from_date = $_GET['from_date'] ?? "";
        $to_date = $_GET['to_date'] ?? "";

        $limit = $_GET['limit'] ?? 15;

        $lists = [];
        $paging = [];

        $user_id = DB::table("users")->where("remember_token", "=", session()->get("user_token"))->first("id")->id;

        $logs = DB::table("counseling_logs")
            ->where("user_id", "=", $user_id)
            ->where(function($query) use($type, $term, $from_date, $to_date, $now) {

                if ($type == "member") {
                    $query->where("type", "=", "member");
                }

                if ($type == "worker") {
                    $query->where("type", "=", "worker");
                }

                if ($term != "") {
                    $query->where("title", "like", "%{$term}%");
                }

                if ($from_date != "") {
                    $query->whereRaw("from_date >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                }

                if ($to_date != "") {
                    $query->whereRaw("to_date <= LAST_DAY(?)", [ date("Y-m-d", strtotime($to_date)) ]);
                }

            })
            ->orderByDesc("id")
            ->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($logs as $key => $log)
        {
            $table = "";

            if ($log->type == "member") {
                $table = "members";
            } else {
                $table = "workers";
            }

            $logs[$key]->target_info = DB::table($table)
                ->where("id", "=", $log->target_id)
                ->first();
        }

        $paging = DB::table("counseling_logs")
            ->select(DB::raw("count(id) as cnt"))
            ->where("user_id", "=", $user_id)
            ->orderByDesc("id")
            ->first("cnt")->cnt;


        return [ "lists" => $logs, "paging" => $paging ];
    }


}
