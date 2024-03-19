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
use phpDocumentor\Reflection\Types\Mixed_;

class LocationUpload implements ToCollection , WithMultipleSheets, WithCalculatedFormulas
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

        $transaction = DB::transaction(function () use ($collection)
        {

            unset($collection[0]);

            $insert = true;

            foreach ($collection as $key => $collect) {

                $insert = DB::table("locations")
                    ->updateOrInsert(
                        [ "serial" => $collect[0], "ADMCD" => $collect[1] ],
                        [ "ADMNM" => $collect[2], "X" => $collect[3], "Y" => $collect[4] ]
                    );

                if (!$insert) return $insert;

            }

            return $insert;

        });


        return $transaction;
    }
}
