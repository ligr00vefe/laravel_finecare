<?php

namespace App\Imports;

use App\Models\Helpers;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class WorkersImport implements ToCollection , WithMultipleSheets, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */

    public $type;

    public function collection(Collection $collection)
    {
        unset($collection[0]);

        $transaction = DB::transaction(function () use ($collection) {

            $user_id = User::get_user_id();

            // 갱신이면 다 지우기
            if ($this->type == "renew") {
                Helpers::where("user_id", "=", $user_id)->delete();
            }

            foreach ($collection as $collect) {
                $target_key = $collect[1] . substr($collect[2], 0, 6);

                $insert = Helpers::firstOrCreate(
                    ["user_id" => $user_id, "target_key" => $target_key],
                    [
                        "name" => $collect[1],
                        "birth" => $collect[2],
                        "target_id" => $collect[3],
                        "target_payment_id" => $collect[4],
                        "card_number" => $collect[5],
                        "agency_name" => $collect[6],
                        "business_number" => $collect[7],
                        "sido" => $collect[8],
                        "sigungu" => $collect[9],
                        "business_division" => $collect[10],
                        "business_types" => $collect[11],
                        "tel" => $collect[12],
                        "phone" => $collect[13],
                        "address" => $collect[14],
                        "etc" => $collect[15],
                        "contract" => $collect[16],
                        "contract_start_date" => isset($collect[17]) ? date("Y-m-d", strtotime($collect[17])) : "",
                        "contract_end_date" => isset($collect[18]) ? date("Y-m-d", strtotime($collect[18])) : "",
                        "contract_date" => $collect[19],
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
