<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminUser extends Model
{
    use HasFactory;

    public static function get($request)
    {
        $page = $request->input("page") ?? 1;
        $offset = ((int)$request->input("page") - 1) * 15;

        $filter = $request->input("filter");
        $keyword = $request->input("keyword");

        if ($filter != "") {
            $filter_arr = [ "account_id" ];
            if (!in_array($filter, $filter_arr)) {
                session()->flash("error", "잘못된 접근입니다");
                return [ "code" => 2 ];
            }
        }


        $lists = DB::table("users")
            ->where("deleteCheck", "=", 0)
            ->when($filter, function ($query, $filter) use ($keyword) {
                return $query->whereRaw("{$filter} LIKE ?", [ "%{$keyword}%" ]);
            })
            ->orderByDesc("id")
            ->offset($offset)
            ->limit("20")
            ->get();

        $paging = DB::table("users")
            ->select(DB::raw("count(id) as cnt"))
            ->when($filter, function ($query, $filter) use ($keyword) {
                return $query->whereRaw("{$filter} LIKE ?", [ "%{$keyword}%" ]);
            })
            ->first("cnt")->cnt;

        return [ "code" => 1, "lists" => $lists, "paging" => $paging ];
    }

    public static function create($request)
    {
        if (!$request->input("account_id") || $request->input("account_id") == "") {
            session()->flash("error", "아이디가 잘못됐습니다.");
            return false;
        }

        if (DB::table("users")->where("account_id", "=", $request->input('account_id'))->exists()) {
            session()->flash("error", "이미 존재하는 아이디입니다.");
            return false;
        }

        if (DB::table('users')->where('email', '=', $request->input('email'))->exists()) {
            session()->flash('error', '이미 존재하는 이메일입니다.');
            return false;
        }

        if ($request->input("password") != $request->input("password2")) {
            session()->flash("error", "비밀번호가 일치하지 않습니다. 비밀번호를 확인해주세요");
            return false;
        }

        $insert = [
            "account_id" => $request->input("account_id"),
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => password_hash($request->input("password"), PASSWORD_BCRYPT),
            "level" => $request->input("level"),
            "company_name" => $request->input("company_name"),
            "tel" => $request->input("tel"),
            "fax" => $request->input("fax"),
            "phone" => $request->input("phone"),
            "license" => $request->input("license"),
            "address" => $request->input("address"),
            "memo" => $request->input("memo"),
            "ip" => $request->input("ip"),
            "resign" => $request->input("resign"),
            "created_at" => now(),
        ];

        if (DB::table("users")->insert($insert)) {
            return true;
        } else {
            session()->flash("error", "오류가 발생했습니다. 다시 시도해 주세요");
            return false;
        }
    }

    public static function change($request, $id)
    {
        if ($request->input("password") != "" &&
            $request->input("password") != $request->input("password2")) {
            session()->flash("error", "비밀번호가 일치하지 않습니다. 비밀번호를 확인해주세요");
            return false;
        }

        $update = [
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "level" => $request->input("level"),
            "company_name" => $request->input("company_name"),
            "tel" => $request->input("tel"),
            "fax" => $request->input("fax"),
            "phone" => $request->input("phone"),
            "license" => $request->input("license"),
            "address" => $request->input("address"),
            "memo" => $request->input("memo"),
            "ip" => $request->input("ip"),
            "resign" => $request->input("resign"),
            "created_at" => now(),
        ];

        if ($request->input("password") != "") {
            $update['password'] = password_hash($request->input("password"), PASSWORD_BCRYPT);
        }

        $action = DB::table("users")
            ->where("id", "=", $id)
            ->update($update);

        if ($action) {
            return true;
        } else {
            session()->flash("error", "오류가 발생했습니다. 다시 시도해 주세요");
            return false;
        }
    }
}
