<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoom_amenityRequest;
use App\Http\Requests\UpdateRoom_amenityRequest;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\Room_amenity;

class RoomAmenityController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function room_index()
    {
        //
        $title = 'Danh Sách PHòng  ';
        // $room = Room::pluck('name','id')->all();
        $room = Room::orderBy('id', 'desc')->get();
        $room_rule = Amenity::orderBy('id', 'desc')->get();
        return view('admins.amenities.amenities-room.index', compact('title', 'room_rule','room'));
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
    public function store(StoreRoom_amenityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Room_amenity $room_amenity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room_amenity $room_amenity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoom_amenityRequest $request, Room_amenity $room_amenity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room_amenity $room_amenity)
    {
        //
    }
}
