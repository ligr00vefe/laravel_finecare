<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function index()
    {
        return view("auth.login");
    }

    public function login(Request $req)
    {
        $login = User::login($req);

        if (!$login) {
            return redirect("login");
        }

        return redirect("/dashboard");
    }

    public function logout(Request $request)
    {
        $request->session()->forget("user_token");
        Cookie::queue("account_id", "", 1);
        Cookie::queue("available_date", "", 1);
        $request->session()->flash("msg", "로그아웃 되었습니다.");
        $request->session()->flash("type", "primary");
        return redirect("/login");
    }

}
