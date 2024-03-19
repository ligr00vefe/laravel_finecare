<?php

namespace App\Imports;

use App\Models\ServiceExtra;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class AddServiceLog implements ToCollection , WithMultipleSheets, WithCalculatedFormulas
{
    /**
     * @return array
     */
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
        $user_id = User::get_user_id();

        unset($collection[0], $collection[1]);


        $service = false;

        foreach ($collection as $collect)
        {
            $ym = date("Y-m", strtotime($collect[4]));
            $target_ym = date("Y-m-d", strtotime($ym));

            $service = ServiceExtra::insert([
                "user_id" => $user_id,
                "target_ym" => $target_ym,
                "target_name" => $collect[0],
                "target_birth" => preg_replace("/[^0-9]*/s", "", $collect[1]),
                "provider_name" => $collect[2],
                "provider_birth" => preg_replace("/[^0-9]*/s", "", $collect[3]),
                "service_start_date_time" => $collect[4],
                "service_end_date_time" => $collect[5],
                "payment_time" => $collect[6],
                "confirm_pay" => $collect[7],
                "add_price" => $collect[8],
                "local_government_name" => $collect[9],
                "organization" => $collect[10],
                "etc" => $collect[11],
            ]);

            if (!$service) break;
        }


        return $service;
    }

}
