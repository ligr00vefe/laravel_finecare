<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VoucherPayment extends Model
{
    use HasFactory;
    protected $table = "voucher_payment_logs";

    public static function get($year)
    {
        $year = date("Y", strtotime($year));

        return DB::table("voucher_payment_logs")
            ->where("dateY", "=", $year)
            ->first()->voucher_payment;
    }
}
