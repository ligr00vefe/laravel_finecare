<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecalculateAnnualController extends Controller
{
    public function index(Request $request) {

        $from_date = $request->input("from_date") ?? false;
        $provider_name = $request->input("provider_name") ?? false;
        $provider_birth = $request->input("provider_birth") ?? false;
        $calc_standard = $request->input("calc_standard") ?? false;
        $user_id = User::get_user_id();

        $lists = [];

        if ($from_date)
        {

            if (!$calc_standard) return redirect("/recalcAnnual")->with("error", "계산기준이 잘못됐습니다,");

            $lists = DB::table("helpers as t1")
                ->join("helper_details_second as t2", function ($join) {
                    $join->on("t1.user_id", "=", "t2.user_id");
                    $join->on("t1.target_key", "=", "t2.target_id");
                })
                ->select("t1.*", "t2.join_date", "t2.resign_date")
                ->where("t1.user_id", "=", $user_id)
                ->when($provider_name, function ($query, $provider_name) use ($provider_birth) {
                    $provider_key = $provider_name;
                    if ($provider_birth) $provider_key .= $provider_birth;
                    return $query->where("target_key", "like", "%{$provider_key}%");
                })
                ->get();



            foreach ($lists as $key => $list)
            {
                if (!$list->join_date || $list->join_date == ""  || $list->join_date == null) continue;

                $year = date("Y", strtotime($from_date));

                $offdays = DB::table("helper_off_day_lists")
                    ->selectRaw("ANY_VALUE(provider_key) as provider_key, DATE_FORMAT(off_day, '%m') as offs, count(id) as cnt")
                    ->where("user_id", "=", $user_id)
                    ->where("provider_key", "=", $list->target_key)
                    ->whereBetween("off_day", [$year."-01-01", $year."-12-31"])
                    ->groupBy("offs")
                    ->get();

                $list->offdays = [];
                $list->use_off_day = 0;

                // 1월에 x개, 2월에 x개 이런식으로 넣어주기 Ex) 1월에 연차 5개 사용 =>  offdays[1] = 5;
                foreach ($offdays as $off) {
                    $m = $off->offs * 1;
                    $list->offdays[$m] = $off->cnt;
                    $list->use_off_day += $off->cnt;
                }



                $pay_log = DB::table("view_payment_total")
                    ->where("user_id", "=", $user_id)
                    ->where("provider_key", "=", $list->target_key)
                    ->where("target_ym", "=", date("Y-m-d", strtotime($from_date)))
                    ->first();



                $time = isset($pay_log->voucher_total_time) ? removeComma($pay_log->voucher_total_time) : 0; // 한 달 근무 시간.
                $pay = isset($pay_log->selected_payment) ? removeComma($pay_log->selected_payment) : 0; // 한 달 급여
                $dayCount = isset($pay_log->voucher_total_day_count) ? removeComma($pay_log->voucher_total_day_count) : 0; // 한 달 일한 일 수
                $dailyTimeAverage = $time > 0 && $dayCount > 0 ? removeComma($time / $dayCount) : 0; // 하루평균근무시간 -> 시간 / 근무일수
                $timePay = $pay>0 && $time>0 ? $pay/$time : 0; // 시간급 -> 한달급여/한달근무시간
                $list->dailyPay = $timePay * $dailyTimeAverage ?? 0; // 1일 통상임금 --> 시간급 * 하루평균근무시간 = 통산임금 ==> 통상임금 * 남은연차일수 = 연차수당



                switch ($calc_standard)
                {
                    case "join_standard" :
                        $target_date = new \DateTime(date("Y-m-d", strtotime($from_date)));

                        $join_date = new \DateTime($list->join_date);
                        $diff = $target_date->diff($join_date);

                        if ($diff->days < 365) {
                            /*
                             * 1년 미만자 계산 방식
                             * : 한 달 개근하면 익월에 연차 1개를 지급 받음.
                             *   다만, 개근했다는 것을 알 수가 없음.
                             *   계획표에는 공휴일만 표시하기 때문임.
                             * */
                            $list->bool_less_than_one_year = true;
                            $list->bool_one_year = false;
                            $list->year_off_day = floor(($diff->days / 30) - 1);



                        } else {
                            // 1년 이상자(연차발생자)
                            $list->bool_less_than_one_year = false;
                            $list->bool_one_year = true;
                            $list->year_off_day = 15;

                        }

                        break;

                    case "year_standard":
                        $year_standard_date = $request->input("year_standard_date") ?? false;
                        $year = date("Y", strtotime($from_date));
                        $year_standard_date = date("Y-m-d", strtotime($year."-".$year_standard_date));

                        $target_date = new \DateTime($year_standard_date); // 회계연도
                        $join_date = new \DateTime($list->join_date);
                        $diff = $target_date->diff($join_date);

                        $first_day = date("Y-m-d", strtotime($year . "-01-01"));
                        $first_date = new \DateTime($first_day);

                        $last_day = date("Y-m-d", strtotime($year."-12-31"));
                        $last_date = new \DateTime($last_day);


                        if ($target_date > $join_date) {
                            // 1년 미만자 연차 계산법 + 올해 연차 15개 로 계산되어야 함

                            // 가입일이 올해 1월1일보다 이전이라면 가입일을 1월1일로 만든다. 연차가 15일 이상 안넘어간다.(연차계산은 1년씩 끊을 것이기 때문)
                            if ($first_date > $join_date) {
                                $join_date = $first_date;
                            }

                            $oneLessMonthDiff = $target_date->diff($join_date)->m;
                            $oneLessOffDay = 15 / 12 * $oneLessMonthDiff; // 회계년도 전의 연차일수 계산


                            $thisYearMonthDiff = $target_date->diff($last_date)->m; // 사용 안할 듯? 올해 연차는 무조건 15개니까
                            $thisYearOffDay = 15;  // 회계년도 지나면 15개 지급되는 연차일수

                            $list->bool_less_than_one_year = false;
                            $list->bool_one_year = true;
                            $list->year_off_day = $oneLessOffDay + $thisYearOffDay;


                        } else {
                            // 1년 미만자 연차 계산법만 적용 되어야 함
                            $oneLessOffDay = 0;
                            $target_date = new \DateTime(date("Y-m-d", strtotime($from_date)));

                            if ($target_date < $join_date) {
                                $thisYearMonthDiff = 0;
                            } else {
                                $thisYearMonthDiff = $join_date->diff($target_date)->m;
                            }

                            $thisYearOffDay = 15 / 12 * $thisYearMonthDiff;
                            $list->bool_less_than_one_year = true;
                            $list->bool_one_year = false;
                            $list->year_off_day = $oneLessOffDay + $thisYearOffDay;
                        }

                        break;

                }

                $list->off_day_total = floor($list->year_off_day); // 총 연차 수
                $list->year_off_day = floor($list->use_off_day); // 사용한 연차 수
                $list->off_pay = ($list->off_day_total - $list->year_off_day) * $list->dailyPay; // 남은 연차 * 연차 수당

            }

        }


        return view("recalculate.annual.index", [
            "lists" => $lists,
            "from_date" => $from_date,
            "provider_name" => $provider_name,
            "provider_birth" => $provider_birth,
            "calc_standard" => $calc_standard,
            "year_standard_date" => $request->input("year_standard_date") ?? ""
        ]);
    }
}
