<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HelperSchedules extends Model
{
    use HasFactory;

    /*
     * data = [ user_id, provider_key, start_date_time ]
     * */
    public static function getHelperOffDay($data)
    {
        $monthCreate = date("Y-m", strtotime($data['start_date_time']));
        $month = date("Y-m-d", strtotime($monthCreate."-01"));

        return DB::table("helper_confirm_schedules")
            ->select("group_concat(`date`) as offDay")
            ->where("user_id", "=", $data['user_id'])
            ->where("target_id", "=", $data['provider_key'])
            ->where("work_type", "=", "비번")
            ->whereRaw("date >= ? and date < DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY )",
                [ $month, $month ]
            )
            ->get();
    }


    /*
     * 관공서 공휴일
     * data = [ user_id, provider_key, start_date_time, check:관공서공휴일적용여부, join_date:입사일, workers_day: 1  ]
     * */
    public static function getHelperHolidayAtMonth($data)
    {
        // 근로자의 날 수당을 전달받았으면 1, 아니면 false
        $data['workers_day'] = $data['workers_day'] ?? false;

        if ($data['join_date'] == "" || !$data['join_date']) {
            $data['join_date'] = "2020-11-01";
        }

        // 서비스 시작시간 정보가 없는 경우 리턴
        if ($data['start_date_time'] == "1970-01-01 00:00:00") {
            return [ "code"=>3, "msg"=>"start_date_time error...", "data"=>[] ];
        }

        // 시작일설정
        $monthCreate = date("Y-m", strtotime($data['start_date_time']));
        $month = date("Y-m-d", strtotime($monthCreate."-01"));
        $year = date("Y", strtotime($data['start_date_time']));

        // 입사일이 시작일보다 크다면 시작일을 입사일로
        if (strtotime($data['join_date']) >= strtotime($month)) {
            $month = date("Y-m-d", strtotime($data['join_date']));
        }

        // 지원사의 주휴일(비번) 목록 가져옴
        $helpersHoliday = DB::table("helper_confirm_schedules")
            ->select(DB::raw("group_concat(date) as date"))
            ->where("user_id", "=", $data['user_id'])
            ->where("worker_id", "=", $data['provider_key'])
            ->where("work_type", "=", "비번")
            ->whereRaw("date >= ? and date < DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY )",
                [ $month, $month ]
            )
            ->orderBy("date")
            ->first();

        $helpersHolidayArr = explode(",", $helpersHoliday->date);

        $monthHolidayArr = [];

        // 체크가 1이라면 관공서공휴일 적용. 공휴일 목록 가져옴
        if ($data['check'] == 1) {

            // 제외할 날짜. 근로자의 날은 평일로 친다.
            $except_arr = [ "{$year}-05-01" ];

            // 근로자의 날은 평일로 치는데 근로자의날수당계산을 체크하면 휴일로 치기때문에 제외에서 없어진다
            if ($data['workers_day']) {
                $except_arr = [];
            }

            $monthHoliday = DB::table("holiday_lists")
                ->select(DB::raw("group_concat(full_date) as date"))
                ->whereRaw("full_date >= ? and full_date < DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY )",
                    [ $month, $month ]
                )
                ->where(function($query) use($except_arr) {
                    // 제외날짜 제외하기 ex) 근로자의 날
                    foreach ($except_arr as $except)
                    {
                        $query->whereRaw("full_date != ?", [ $except ]);
                    }
                })
                ->first();

            $monthHolidayArr = explode(",", $monthHoliday->date);
        }


        // 배열 합치고 중복키없애고 빈 값 없애기
        $merge = array_filter(array_unique(array_merge($helpersHolidayArr, $monthHolidayArr)));
        
        // 배열 날짜순으로 재정렬
        array_multisort($merge);



        return $merge;
    }
}
