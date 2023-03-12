<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaderRequest;
use App\Http\Requests\UpdateLeaderRequest;
use App\Models\Leader;

class LeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Leader $leader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leader $leader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaderRequest $request, Leader $leader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leader $leader)
    {
        //
    }
}
