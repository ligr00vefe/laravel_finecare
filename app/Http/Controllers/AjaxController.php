<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function gabgeunse(Request $request)
    {
        $payment_voucher_total = $request->input("total_pay");
        $provider_key = $request->input("provider_key");

        $class_a_wages =  Tax::class_a_wages([
            "payment" => $payment_voucher_total,
            "target_id" => $provider_key
        ]);

        return response()->json([
            "gabgeunse" => $class_a_wages
        ]);
    }
}
