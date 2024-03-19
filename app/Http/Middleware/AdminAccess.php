<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!session()->get("user_token")) {
            $request->session()->flash("msg", "로그인 해주세요.");
            return redirect("login");
        }

        if (!DB::table("users")->where("remember_token", session()->get("user_token"))->first("id")) {
            $request->session()->flash("msg", "로그인 해주세요.");
            return redirect("login");
        }

        if (DB::table("users")->where("remember_token", session()->get("user_token"))->first("level")->level < 8) {
            $request->session()->flash("error", "접근 권한이 없습니다");
            return redirect("/member/main/all/1");
        }

        return $next($request);

    }
}
