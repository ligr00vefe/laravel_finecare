<?php

namespace App\Imports;

use App\Models\HelperDetails;
use App\Models\Helpers;
use App\Models\User;
use App\Models\WorkerDetails;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class WorkerDetailsImport implements ToCollection
{
    public $type;

    public function collection(Collection $collection)
    {

        unset($collection[0]);

        $transaction = DB::transaction(function () use ($collection) {

            $user_id = User::get_user_id();

            // 갱신이면 다 지우기
            if ($this->type == "renew") {
                HelperDetails::where("user_id", "=", $user_id)->delete();
            }

            foreach ($collection as $collect) {
                $target_key = $collect[2] . substr($collect[3], 0, 6);

                $insert = HelperDetails::firstOrCreate(
                    ["user_id" => $user_id, "target_key" => $target_key ],
                    [
                        "register_check" => $collect[1],
                        "name" => $collect[2],
                        "birth" => $collect[3],
                        "target_key" => $collect[4],
                        "business_division" => $collect[5],
                        "business_type" => $collect[6],
                        "payment_price" => $collect[7],
                        "moment_payment_price" => $collect[8],
                        "work_time" => $collect[9],
                        "add_basic_pay" => $collect[10],
                        "add_week_pay" => $collect[11],
                        "add_year_pay" => $collect[12],
                        "etc_pay" => $collect[13],
                        "ins_business_assign" => $collect[14],
                        "retire_plus_price" => $collect[15],
                        "monthly_payment" => $collect[16],
                        "work_time_day" => $collect[17],
                        "time_per_price" => $collect[18],
                        "ins_check" => $collect[19],
                        "national_ins_check" => $collect[20],
                        "health_ins_check" => $collect[21],
                        "employ_ins_check" => $collect[22],
                        "industry_ins_check" => $collect[23],
                        "baesang_ins_check" => $collect[24],
                        "retire_added_check" => $collect[25],
                        "qualification_status" => $collect[26],
                        "target_id" => $collect[27],
                        "business_division_code" => $collect[28],
                        "business_type_code" => $collect[29],
                    ]
                );

                if (!$insert) return false;
            }

            return true;

        });


        return $transaction;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
}
