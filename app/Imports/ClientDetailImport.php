<?php

namespace App\Imports;

use App\Models\ClientDetails;
use App\Models\ServiceExtra;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClientDetailImport implements ToCollection , WithMultipleSheets, WithCalculatedFormulas
{

    public $type, $data;

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $collection)
    {

        $user_id = User::get_user_id();
        unset($collection[0], $collection[1]);

        $transaction = DB::transaction(function () use($collection, $user_id) {

            $error = true;


            foreach ($collection as $key => $detail)
            {

                if (!$detail[0]) continue;

                $client_key = trim($detail[0]) . substr(trim($detail[1]), 0, 6);


                $client_id = DB::table("clients")
                    ->where("user_id", "=", $user_id)
                    ->where("target_key", "=", $client_key)
                    ->first()->id ?? false;

                if (!$client_id) continue;


                $error = DB::table("client_details")
                ->updateOrInsert(
                    ["user_id" => $user_id, "target_key" => $client_key ],
                    [
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "name" => $detail[0],
                        "rsNo" => $detail[1],
                        "target_key" => $client_key,
                        "client_number" => $detail[2],
                        "regdate" => date("Y-m-d", strtotime($detail[3])),
                        "contract_start_date" => date("Y-m-d", strtotime($detail[4])),
                        "contract_end_date" => date("Y-m-d", strtotime($detail[5])),
                        "phone" => $detail[6],
                        "tel" => $detail[7],
                        "address" => $detail[8],
                        "email" => $detail[9],
                        "company" => $detail[10],
                        "bogun_time" => $detail[11],
                        "jijache_time" => $detail[12],
                        "etc_time" => $detail[13],
                        "other_experience" => $detail[14],
                        "income_check" => $detail[15],
                        "activity_grade" => $detail[16],
                        "activity_grade_old" => $detail[17],
                        "activity_grade_type" => $detail[18],
                        "income_decision_date" => $detail[19],
                        "self_charge_price" => $detail[20],
                        "main_disable_name" => $detail[21],
                        "main_disable_level" => $detail[22],
                        "main_disable_grade" => $detail[23],
                        "sub_disable_name" => $detail[24],
                        "sub_disable_level" => $detail[25],
                        "sub_disable_grade" => $detail[26],
                        "disease_name" => $detail[27],
                        "drug_info" => $detail[28],
                        "wasang_check" => $detail[29],
                        "marriage_check" => $detail[30],
                        "family_info" => $detail[31],
                        "protector_name" => $detail[32],
                        "protector_relation" => $detail[33],
                        "protector_phone" => $detail[34],
                        "protector_tel" => $detail[35],
                        "protector_address" => $detail[36],
                        "etc" => $detail[37],
                        "comment" => $detail[38],
                    ]);

                if (!$error) break;
            }


            return $error;

        });


        if ($transaction)
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}
