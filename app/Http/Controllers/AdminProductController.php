<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{



    public function index(Request $request)
    {
        $filter = $request->input("filter");
        $keyword = $request->input("keyword");

        $query = DB::table("payment_goods_lists")
            ->when($filter, function ($query, $filter) use ($keyword) {
                return $query->where($filter, "like", "%{$keyword}%");
            });

        $paging = $query->count();
        $lists = $query->paginate(15);

        return View("admin.product.manage", [
            "lists" => $lists,
            "paging" => $paging
        ]);
    }

    public function updateOne(Request $request, Goods $id)
    {

        $id->price = $request->input("price");
        $id->state = $request->input("state") ?? 0;


        if ($id->save())
        {
            return redirect()->route("admin.product.manage")->with("msg", "성공적으로 수정했습니다.");
        }
        else
        {
            return back()->with("error", "수정에 실패했습니다. 다시 시도해 주세요");
        }
    }


    public function update(Request $request)
    {
        $ids = explode(",", $request->input("ids"));
        $prices = array_filter(explode(",", $request->input("prices")));
        $states = array_filter(explode(",", $request->input("states")));


        $transaction = DB::transaction(function () use ($ids, $prices, $states, $request) {

            $error = false;

            foreach ($ids as $i => $id)
            {
                $goods = Goods::find($id);
                $goods->price = $prices[$i];
                $goods->state = $states[$i] ?? 0;
                if (!$goods->save()) $error = true;
                if (!$error) break;
            }

            return $error;

        });

        if (!$transaction)
        {
            return redirect()->route("admin.product.manage")->with("msg", "성공적으로 수정했습니다.");
        }
        else
        {
            return back()->with("error", "수정에 실패했습니다. 다시 시도해 주세요");
        }



    }

}
