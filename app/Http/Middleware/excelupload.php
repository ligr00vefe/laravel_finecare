<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class excelupload
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
        // 허용할 파일 확장자
        $extensions = ["xlsx", "xls", "csv"];

        if($request->file()) {

            foreach ($request->file() as $key => $val) {

                $filename = $val->getClientOriginalName();
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                if (!in_array(strtolower($ext), $extensions)) {
                    session()->flash("attack_detected", "지원하지 않는 파일형식입니다.");
                    return redirect("/member/main/all/1");
                }

            }

        }
        return $next($request);
    }
}
