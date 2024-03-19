<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [

    ];

    public static function get_user_id()
    {
        return User::where("remember_token", "=", session()->get("user_token"))->first("id")->id;
    }

    public static function login($request)
    {
        Cookie::queue("account_id", "", 1);
        Cookie::queue("available_date", "", 1);

        $getUser = DB::table("users")->where("account_id", "=", $request->input("id"))->first();

        if (!$getUser) {
            $request->session()->flash("msg", "아이디가 존재하지 않거나 비밀번호가 틀렸습니다.");
            $request->session()->flash("type", "warning");
            $request->session()->forget("user_token");
            return false;
        }

        if (!password_verify($request->input("password"), $getUser->password)) {
            $request->session()->flash("msg", "아이디가 존재하지 않거나 비밀번호가 틀렸습니다.");
            $request->session()->flash("type", "warning");
            $request->session()->forget("user_token");
            return false;
        }

        if (!self::isExpire($getUser)) {
            $request->session()->flash("msg", "사용기간이 만료되었습니다.");
            $request->session()->flash("type", "warning");
            $request->session()->forget("user_token");
            return false;
        }

        $token = bin2hex(random_bytes(32));

        DB::table("users")->where("account_id", $request->input("id"))->update([ "remember_token" => $token ]);
        $request->session()->put(["user_token" => $token]);
        Cookie::queue("account_id", $getUser->account_id, 120);

        return true;
    }

    // 기간끝났는지 체크
    public static function isExpire($user) : bool
    {
        if ($user->level >= 8) return true;

        $last_payment = DB::table("user_goods_lists")
            ->where("user_id", "=", $user->id)
            ->orderByDesc("id")
            ->first() ?? false;

        if (!$last_payment) return false;

        $now = new \DateTime(date("Y-m-d", strtotime(now())));
        $end_date = new \DateTime($last_payment->end_date);

        $isExpire =  $now < $end_date;
        if (!$isExpire) return false;

        Cookie::queue("available_date", $last_payment->end_date, 120);
        return true;

    }


}
