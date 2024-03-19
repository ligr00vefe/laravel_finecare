<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\Builder;

class HelperWorkOffController extends Controller
{

    public function allow($request)
    {
        $referer = request()->headers->get('referer');
        $allowUris = [ "recalcAnnual" ];
        $allowCheck = false;

        foreach ($allowUris as $uri)
        {
            if (strpos($referer, $uri) !== false) {
                $allowCheck = true;
            }

            if ($allowCheck) break;
        }

        if (!$allowCheck) return response()->json([
            "code" => 99,
            "msg" => "잘못된 접근입니다.",
            "data" => false
        ]);

        return true;
    }


    public function index(Request $request)
    {

        if (!$request->ajax()) return redirect("/");
        $uriValidate = $this->allow($request);
        if (!$uriValidate) return redirect("/");

        $user_id = User::get_user_id();
        $page = $request->input("page");
        $type = $request->input("type");
        $year = $request->input("year");

        $postPerPage = 10;
        $provider_key = $request->input("provider_key");
        $query = DB::table("helper_off_day_lists")
            ->where("user_id","=",$user_id)
            ->where("provider_key","=",$provider_key)
            ->when($year, function ($query, $year) {
                return $query->whereBetween("off_day", [$year."-01-01", $year."-12-31"]);
            })
            ->orderByDesc("off_day");

        $pagination = ceil($query->count() / $postPerPage);
        $lists = $query->simplePaginate($postPerPage);
        $html = "";

        if ($type == "list")
        {
            $html = view("recalculate.annual.reload.list", [
                "offDays" => $lists,
                "pagination" => $pagination,
                "page" => $page,
                "provider_key" => $provider_key
            ]);
        }
        else if ($type == "paging")
        {
            $html = view("recalculate.annual.reload.pagination", [
                "offDays" => $lists,
                "pagination" => $pagination,
                "page" => $page,
                "provider_key" => $provider_key
            ]);
        }


        return response($html);

    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect("/");
        $uriValidate = $this->allow($request);
        if (!$uriValidate) return redirect("/");

        $user_id = User::get_user_id();
        $provider_key = $request->input("provider_key");
        $off_day = $request->input("date");

        $builder = new Builder();
        $insert = $builder->table("helper_off_day_lists")
            ->dupsert([
                "user_id" => $user_id,
                "provider_key" => $provider_key,
                "off_day" => $off_day
            ]);

        if (isset($insert) && $insert['insert'])
        {
            return response()->json([
                "code" => 1,
                "msg" => "success...!",
                "data" => $insert['id']
            ]);
        }
        else
        {
            return response()->json([
                "code" => 2,
                "msg" => "duplicated!!",
                "data" => $insert
            ]);
        }

    }


    public function destroy(Request $request)
    {
        if (!$request->ajax()) return redirect("/");
        $uriValidate = $this->allow($request);
        if (!$uriValidate) return redirect("/");

        $user_id = User::get_user_id();
        $id = $request->input("id");

        $delete = DB::table("helper_off_day_lists")
            ->where("user_id", "=", $user_id)
            ->where("id", "=", $id)
            ->delete();

        if ($delete) {
            return response()->json([
                "code" => 1,
                "msg" => "success...!",
                "data" => $delete
            ]);
        }

    }

}
