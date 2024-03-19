<?php


namespace App\Executes;


use App\Classes\Builder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DayOffReCalculateRecordsSave
{
    public function store(Request $request)
    {
        $transaction = DB::transaction(function () use ($request) {

            $user_id = User::get_user_id();
            $target_ym = $request->input("target_ym");
            $standard = $request->input("standard");
            $year_standard_date = $request->input("year_standard_date") ?? null;
            $data = $request->input("data") ?? false;

            $execute = true;

            if (!$data) {
                $execute = false;
                return back()->with("error", "데이터가 없습니다. 다시 시도해 주세요");
            }

            if (!$execute) return false;

            $records = json_decode($data);

            foreach ($records as $record)
            {
                $builder = new Builder();
                $execute = $builder->table("day_off_recalculate_records")
                    ->upsert([
                        "user_id" => $user_id,
                        "provider_key" => $record->provider_key,
                        "year" => date("Y", strtotime($target_ym))
                    ],
                        [
                            "target_ym" => date("Y-m-d", strtotime($target_ym)),
                            "join_date" => $record->join_date,
                            "resign_date" => $record->resign_date ?: null,
                            "standard" => $standard,
                            "year_standard_date" => $year_standard_date,
                            "use_off_day" => $record->year_off_day,
                            "total_off_day" => $record->off_day_total,
                            "off_day_price_daily" => $record->dailyPay,
                            "off_day_price_total" => $record->off_pay,
                            "less_than_one_year" => $record->less_than_one_year,
                        ]);

            }

            // 저장할 때 연차수당재정산이 변경된 점이 없는 사람은 무조건 인트0 리턴한다.
            // 즉, $execute가 인트0 일때 트랜잭션 실패
            return true;
        });


        if ($transaction)
        {
            return redirect("/recalcAnnual")->with("msg", "연차수당 재정산 내역을 저장하였습니다.");
        }
        else
        {
            return back()->with("error", "저장하는데 문제가 발생했습니다. 다시 시도해 주세요");
        }


    }
}
