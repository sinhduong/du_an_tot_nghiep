<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreroomRequest;
use App\Http\Requests\UpdateroomRequest;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title='Danh sách phòng';
        $rooms=Room::orderBy('id','desc')->get();
        return  view('admins.rooms.index',compact('rooms','title'));
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
    public function store(StoreroomRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(room $rooms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(room $rooms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateroomRequest $request, room $rooms)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(room $rooms)
    {
        //
    }
}
