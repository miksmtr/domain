<?php

namespace App\Http\Controllers;

use App\Models\ServerSetting;
use Illuminate\Http\Request;

class ServerSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $server_settings = ServerSetting::all()->first();
        return view('server_setting.index', compact('server_settings'));

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
     * @param  \App\Models\ServerSetting  $serverSetting
     * @return \Illuminate\Http\Response
     */
    public function show(ServerSetting $serverSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServerSetting  $serverSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(ServerSetting $serverSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServerSetting  $serverSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServerSetting $serverSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServerSetting  $serverSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServerSetting $serverSetting)
    {
        //
    }
}
