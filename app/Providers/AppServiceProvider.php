<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Carbon::setLocale("ko");

        if (!defined("__IMG__")) {
            define("__IMG__", "/storage/img/");
        }

        if (!defined("__lib__")) {
            define("__lib__", "/storage/lib/");
        }

        if (!defineD("__URL__")) {
            define("__URL__", "http://ec2-13-209-189-71.ap-northeast-2.compute.amazonaws.com");
        }
        View::share("config", config("services.menu"));
        if (isset($_SERVER['REMOTE_ADDR'])) {
            define("__BRI__", $_SERVER['REMOTE_ADDR'] == "1.212.55.18");
        }
    }
}
