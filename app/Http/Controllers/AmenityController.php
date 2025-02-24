<?php

namespace App\Http\Controllers;

use App\Models\amenity;
use App\Http\Requests\StoreamenityRequest;
use App\Http\Requests\UpdateamenityRequest;
use App\Models\Room;
use App\Models\Room_amenity;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Các Tiện Ích ';
        $room_rule = amenity::orderBy('id', 'desc')->get();
        return view('admins.amenities.index', compact('title', 'room_rule'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm Tiện Ích ';
        return  view('admins.amenities.create', compact('title'));
    }

    //     /**
    //      * Store a newly created resource in storage.
    //      */
    public function store(StoreamenityRequest $request)
    {
        if ($request->isMethod('POST')) {
            // Lấy dữ liệu từ request
            $data = $request->except('_token');

            // Thêm loại phòng vào database
            amenity::create($data);
        }

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('admin.amenities.index')->with('success', 'Thêm quy định phòng thành công');
    }


    //     /**
    //      * Display the specified resource.
    //      */
    //     public function show(RoomType $room_type)
    //     {
    //         //
    //     }

    //     /**
    //      * Show the form for editing the specified resource.
    //      */
    public function edit(string $id)
    {
        $title = 'Sửa loại tiện ích ';
        $room_types = amenity::findOrfail($id);
        return  view('admins.amenities.edit', compact('room_types', 'title'));
    }

    //     /**
    //      * Update the specified resource in storage.
    //      */
    public function update(UpdateamenityRequest $request, string $id)
    {
        // Tìm loại phòng theo ID, nếu không có sẽ báo lỗi 404
        $room_type = amenity::findOrFail($id);

        // Lấy dữ liệu từ request, loại bỏ _token và _method
        $data = $request->except('_token', '_method');

        // Cập nhật loại phòng
        $room_type->update($data);

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('admin.amenities.index')->with('success', 'Cập nhật loại phòng thành công');
    }


    //     /**
    //      * Remove the specified resource from storage.
    //      */
    public function destroy($id)
    {
        $room_type = amenity::findOrFail($id);
        $room_type->delete(); // Xóa mềm

        return redirect()->route('admin.amenities.index')->with('success', 'Loại phòng đã được xóa mềm');
    }



    public function trashed()
    {
        $title = 'Loại phòng đã xóa';
        $room_types = amenity::onlyTrashed()->get();
        return view('admins.amenities.trashed', compact('title', 'room_types'));
    }

    public function restore($id)
    {
        $room_type = amenity::onlyTrashed()->findOrFail($id);
        $room_type->restore(); // Khôi phục
        return redirect()->route('admin.amenities.index')->with('success', 'Khôi phục loại phòng thành công');
    }

    public function forceDelete($id)
    {
        $room_type = amenity::onlyTrashed()->findOrFail($id);
        $room_type->forceDelete(); // Xóa vĩnh viễn
        return redirect()->route('admin.amenities.trashed')->with('success', 'Xóa vĩnh viễn loại phòng thành công');
    }




    public function room_index()
    {
        $title = 'Danh Sách Tiện Ích Của  PHòng  ';
        // $room = Room::pluck('name','id')->all();
        $room = Room::orderBy('id', 'desc')->get();
        $room_rule = amenity::orderBy('id', 'desc')->get();
        return view('admins.amenities.amenities-room.index', compact('title', 'room_rule','room'));
    }
    public function create_room()
    {
        $title = 'Thêm Quy Tắc Vào Phòng';
        $room = Room::pluck('name','id')->all();
        $rule = amenity::pluck('name','id')->all();
        return  view('admins.amenities.amenities-room.create', compact('title','rule','room'));
    }
    public function room_store(StoreamenityRequest $request)
    {
        $roomIds = $request->room_ids;  // Lấy danh sách room_id
        $ruleIds = $request->rule_ids;  // Lấy danh sách rule_id

// Gán từng rule vào từng phòng và thêm timestamps
foreach ($roomIds as $roomId) {
    $room = Room::find($roomId);
    
    // Chuẩn bị dữ liệu cho bảng trung gian room_rars
    $data = [];
    foreach ($ruleIds as $ruleId) {
        $data[$ruleId] = [
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Thêm dữ liệu vào bảng trung gian với timestamps
    $room->amenities()->attach($data);
}
        return redirect()->back()->with('success', 'Đã gán quy định cho các phòng thành công!');
        // Chuyển hướng về danh sách với thông báo thành công
        // return redirect()->route('admin.rule-regulations.index')->with('success', 'Thêm quy định phòng thành công');
    }                   
    public function view_room( string $id){
        $title = 'Các Quy Định Của Phòng ' ;
        // $data = Room::with(['rules', 'rars'])->findOrFail($id);
        // $room_types =Room::findOrfail($id);
        // $room_rule = rules_and_regulation::orderBy('id', 'desc')->get();
        // // dd( $room_types) ; 
        // dd( $data) ; 
        // // dd( $room_rule) ; 
        // $room_rar = Room_rar::findOrFail($id);
        // $rooms = Room::all();
        // $rules = Rules_and_regulation::all();
        // return view('admin.room_rars.edit', compact('room_rar', 'rooms', 'rules'));
        $room = Room::with(['amenities'])->findOrFail($id);
        $room_rars = Room_amenity::where('room_id', $id)->with('amenity')->get(); 
        // dd($room->amenities);
        return view('admins.amenities.amenities-room.view',
        //  compact('title', 'room_rule' ,'data','room_types','room_rar', 'rooms', 'rules'));
        compact('title', 'room' ,'room_rars'));

    }



//     // xóa bảng 
    public function destroy_room(string $id)
    {
        // $room_type = rules_and_regulation::findOrFail($id);
        // $room_type->delete(); // Xóa mềm
        $room_rar = Room_amenity::findOrFail($id);
        $room_rar->delete();
        return redirect()->route('admin.amenities.room_index')->with('success', 'Loại phòng đã được xóa mềm');
    }

    public function trashed_room()
    {
        $title = 'Loại phòng đã xóa';
        $room_rars = Room_amenity::onlyTrashed()->get(); // ✅ Lấy dữ liệu đã xóa mềm
        return view('admins.amenities.amenities-room.trashed', compact('title', 'room_rars'));
    }
    public function restore_room($id)
    {
        Room_amenity::onlyTrashed()->findOrFail($id)->restore(); // ✅ Laravel tự động khôi phục
        return redirect()->route('admin.amenities.room_index')->with('success', 'Khôi phục loại phòng thành công');
    }

    public function forceDelete_room($id)
    {
        Room_amenity::onlyTrashed()->findOrFail($id)->forceDelete(); // ✅ Xóa hẳn khỏi database
        return redirect()->route('admin.amenities.trashed_room')->with('success', 'Xóa vĩnh viễn loại phòng thành công');
    }
}
