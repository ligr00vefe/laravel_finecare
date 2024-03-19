<?php

namespace App\Imports;

use App\Models\ServiceExtra;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class WorkerTimetableImport implements ToCollection , WithMultipleSheets, WithCalculatedFormulas
{
    /**
     * @return array
     */

    public $data;

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    /**
     * @param Collection $collection
     * @throws \Exception
     */
    public function collection(Collection $collection)
    {
        unset($collection[0], $collection[1], $collection[2]);
        $user_id = User::get_user_id();

        $selected_date_cell = $collection[3][3] ?? false;

        if (!$selected_date_cell) {
            $this->data = false;
        }

//        $target_ym = Carbon::createFromFormat("Ym", $selected_date_cell)->format("Ym") ?? "";
        $target_ym = date("Ym", strtotime($selected_date_cell)) ?? false;

        $transaction = DB::transaction(function () use($collection, $user_id, $target_ym) {

            $insert = true;

            $year = substr($target_ym, 0, 4);
            $month = substr($target_ym, 4, 2);
            $target_ym = date("Y-m", strtotime($year ."-". $month));

            foreach ($collection as $collect)
            {
                if (!$collect[1] | $collect[1] == "" || !$collect[2] || $collect[2] == "") continue;

                $worker_id = $collect[1].substr($collect[2], 0, 6);
//                $target_ymd = Carbon::createFromFormat("Ym", $collection[3][3])->format("Y-m-d");
                $target_ymd = date("Y-m-d", strtotime($year . "-". $month));

                DB::table("helper_confirm_schedules")
                    ->where("user_id", "=", $user_id)
                    ->where("worker_id", "=", $worker_id)
                    ->whereRaw("(date > LAST_DAY(? - interval 1 month) AND date <= LAST_DAY(?))", [ $target_ymd, $target_ymd ])
                    ->delete();


                foreach ($collect as $key => $val)
                {
                    if ($key < 4) continue;
                    if ($key >= 35) break;

                    if ($val == 1) {

                        $insert = DB::table("helper_confirm_schedules")
                            ->insert([
                                "user_id" => $user_id,
                                "worker_id" => $worker_id,
                                "work_type" => "근무",
                                "date" => date("Y-m-d", strtotime($target_ym."-".($key-3))),
                            ]);

                        if (!$insert) return false;
                    }
                }

            }


            return $insert;

        });



        if ($transaction)
        {
            $this->data = $target_ym;
        }
        else
        {
            $this->data = false;
        }

    }
}
