<?php

namespace App\Http\Controllers;

use App\Models\WorkerService;
use Illuminate\Http\Request;

class WorkerServiceController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input("page");
        $get = WorkerService::get($request);

        return View("worker.serviceList", [ "page" => $page, "lists" => $get['lists'], "providers" => $get['providers'] ]);
    }
}
