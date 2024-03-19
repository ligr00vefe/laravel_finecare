<?php

namespace App\Imports;

use App\Models\HelperDetailsSecond;
use App\Models\ServiceExtra;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HelperDetailsSecondImport implements ToCollection , WithMultipleSheets, WithCalculatedFormulas
{

    public $data, $type;

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }


    public function collection(Collection $collection)
    {

        unset($collection[0], $collection[1]);

        $transaction = DB::transaction(function () use ($collection) {

            $user_id = User::get_user_id();

            // 갱신이면 다 지우기
            if ($this->type == "renew") {
                HelperDetailsSecond::where("user_id", "=", $user_id)->delete();
            }

            foreach ($collection as $key => $collect) {

                if (!$collect[0]) continue;

                $target_id = $collect[0] . substr($collect[1], 0, 6);

                $insert = HelperDetailsSecond::firstOrCreate(
                    ["user_id" => $user_id, "target_id" => $target_id ],
                    [
                        "name" => $collect[0],
                        "rsNo" => $collect[1],
                        "regNo" => $collect[2],
                        "regdate" => date("Y-m-d", strtotime($collect[3])),
                        "regEndDate" => "",
                        "join_date" => date("Y-m-d", strtotime($collect[4])),
                        "resign_date" => date("Y-m-d", strtotime($collect[5])),
                        "phone" => $collect[6],
                        "tel" => $collect[7],
                        "address" => $collect[10],
                        "bank_name" => $collect[11],
                        "bank_account_number" => $collect[12],
                        "depositary_stock" => $collect[13],
                        "license_info" => $collect[14],
                        "email" => $collect[15],
                        "crime_check" => $collect[16],
                        "national_pension" => $collect[17],
                        "national_pension_monthly" => $collect[18],
                        "health_insurance" => $collect[19],
                        "health_insurance_monthly" => $collect[20],
                        "long_term_care_insurance_reduction" => $collect[21],
//                        "employment_insurance" => $collect[22],
                        "employment_insurance_monthly" => $collect[22],
                        "employment_insurance_after_65age" => $collect[23],
                        "industrial_accident_insurance" => $collect[24],
                        "industrial_accident_insurance_monthly" => $collect[25],
                    ]
                );

                if (!$insert) return false;
            }

            return true;

        });


        return $transaction;
    }

}
