<?php

namespace App\Http\Controllers;

use App\Models\AdminPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {

        $goods_id = $request->input("goods_id");
        $payment_type = $request->input("payment_type");
        $from_date = $request->input("from_date");
        $to_date = $request->input("to_date");

        $auto_date = $request->input("auto_date");
        if ($auto_date) {
            $from_date = false;
            $to_date = false;
        }

        $query = DB::table("user_goods_lists")
            ->selectRaw("user_goods_lists.*, payment_goods_lists.name as goods_name, payment_goods_lists.price as goods_price, users.company_name, users.account_id")
            ->join("payment_goods_lists", "user_goods_lists.goods_id", "=", "payment_goods_lists.id")
            ->join("users", "user_goods_lists.user_id", "=", "users.id")
            ->when($goods_id, function ($query, $goods_id) {
                return $query->whereRaw("payment_goods_lists.id = ?", [ $goods_id ]);
            })
            ->when($payment_type, function ($query, $payment_type) {
                return $query->whereRaw("user_goods_lists.payment_type = ?", [ $payment_type ]);
            })
            ->when($from_date, function ($query, $from_date) {
                return $query->whereRaw("user_goods_lists.payment_date >= ?", [ $from_date ]);
            })
            ->when($to_date, function ($query, $to_date) {
                return $query->whereRaw("user_goods_lists.payment_date <= ?", [ $to_date ]);
            })
            ->when($auto_date, function ($query, $auto_date) {
                switch ($auto_date)
                {
                    case "1": // 오늘
                        return $query->whereRaw("DATE_FORMAT(user_goods_lists.payment_date, '%Y-%m-%d') = CURDATE()");
                        break;
                    case "2": // 어제
                        return $query->whereRaw("DATE_FORMAT(user_goods_lists.payment_date, '%Y-%m-%d') >= DATE_ADD(now(), interval -1 day)");
                        break;
                    case "3": // 이번주
                        return $query->whereRaw("YEARWEEK(user_goods_lists.payment_date) = YEARWEEK(now())");
                        break;
                    case "4": // 이번달
                        return $query->whereRaw("(user_goods_lists.payment_date > LAST_DAY(now() - interval 1 month) AND user_goods_lists.payment_date <= LAST_DAY(NOW()))");
                        break;
                    case "5": // 저번주. 안씀
                        return true;
                        break;
                    case "6": // 저번달
                        return $query->whereRaw("(user_goods_lists.payment_date > LAST_DAY(now() - interval 2 month) AND user_goods_lists.payment_date <= LAST_DAY(now() - interval 1 month))");
                        break;
                }
            })
            ->orderByDesc("user_goods_lists.id");

        $paging = $query->count();
        $lists = $query->paginate(15);

        return View("admin.payment.index", [
            "paging" => $paging,
            "lists" => $lists
        ]);
    }
}
