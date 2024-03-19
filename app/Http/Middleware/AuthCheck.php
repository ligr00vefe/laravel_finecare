<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AuthCheck
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
        return $next($request);
    }
}
