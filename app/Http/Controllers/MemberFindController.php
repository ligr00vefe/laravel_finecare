<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\MemberFind;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberFindController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : View
    {
        $page = $request->input("page") ?? 1;
        $lists = MemberFind::search($request);
        $locations = Location::get();

        return view("member.find", [
            "possible_service" => $request->input("possible_service") ?? false,
            "member_gender" => $request->input("member_gender") ?? false,
            "member_age_type" => $request->input("member_age_type") ?? false,
            "member_trauma_disorder_check" => $request->input("member_trauma_disorder_check") ?? false,
            "page" => $page,
            "lists" => $lists['lists'],
            "paging" => $lists['paging'],
            "locations" => $locations,
            "location_1" => $request->input("location_1") ?? false,
            "location_2" => $request->input("location_2") ?? false,
            "location_3" => $request->input("location_3") ?? false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
