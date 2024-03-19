<?php

namespace App\Http\Controllers;

use App\Models\WorkerWorkList;
use Illuminate\Http\Request;

class WorkerWorkListController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input("page") ?? 1;
        $get = WorkerWorkList::get($request);
        return view("worker.worklist", [ "page"=> $page, "lists" => $get['lists'], "paging" => $get['paging'], "total" => $get['total'] ] );
    }

    public function reload(Request $request)
    {
        return response()->json(WorkerWorkList::reload($request));
    }
}
