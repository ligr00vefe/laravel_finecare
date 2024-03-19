<?php

namespace App\Imports;


use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MemberImport implements ToCollection
{

    public $data;
    public $type;

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $collection)
    {

        $user_id = User::get_user_id();
        unset($collection[0]);


        $transaction = DB::transaction(function () use($collection, $user_id) {

            $insert = false;
            foreach ($collection as $key => $client)
            {
                $target_key = $client[1] . substr($client[2], 0, 6);

                $insert = DB::table("clients")->insert([
                    "user_id" => $user_id,
                    "target_key" => $target_key,
                    "name" => $client[1],
                    "rsNo" => $client[2],
                    "target_id" => $client[3],
                    "business_type" => $client[4],
                    "grade" => $client[5],
                    "sigungu_name" => $client[6],
                    "service_sigungu_name" => $client[7],
                    "tel" => $client[8],
                    "phone" => $client[9],
                    "contract_status" => $client[10],
                    "contract_start_date" => $client[11],
                    "contract_end_date" => $client[12],
                    "contract_resign_reason" => $client[13],
                    "service_status" => $client[14],
                    "service_start_date" => $client[15],
                    "service_end_date" => $client[16],
                    "service_resign_date" => $client[17],
                    "target_helper" => $client[18],
                    "help_price_total" => $client[19],
                    "government_help_price" => $client[20],
                    "deductible" => $client[21],
                    "childbirth_date" => $client[22],
                    "leave_hospital_date" => $client[23],
                    "zip_code" => $client[24],
                    "service_address" => $client[25],
                    "address" => $client[26],
                    "address_detail" => $client[27],
                ]);

                if (!$insert) break;
            }


            return $insert;

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
