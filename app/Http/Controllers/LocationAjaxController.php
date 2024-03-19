<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationAjaxController extends Controller
{
    public function get(Request $request)
    {
        $keyword = $request->input("keyword") ?? false;
        $depth = $request->input("depth") ?? 1;
        $location = Location::get($keyword, $depth);

        return response($location);
    }
}
