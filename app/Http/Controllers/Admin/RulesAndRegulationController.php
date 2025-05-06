<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Storerules_and_regulationRequest;
use App\Http\Requests\Updaterules_and_regulationRequest;
use App\Models\Room;
use App\Models\Room_rar;
use App\Models\RoomType;
use App\Models\RulesAndRegulation;
use Illuminate\Support\Facades\DB;

class RulesAndRegulationController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:rules_and_regulations_list')->only(['index']);
        $this->middleware('permission:rules_and_regulations_create')->only(['create', 'store']);
        $this->middleware('permission:rules_and_regulations_update')->only(['edit', 'update']);
        $this->middleware('permission:rules_and_regulations_delete')->only(['destroy']);
        $this->middleware('permission:rules_and_regulations_trashed')->only(['trashed']);
        $this->middleware('permission:rules_and_regulations_restore')->only(['restore']);
        $this->middleware('permission:rules_and_regulations_force_delete')->only(['forceDelete']);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Danh Sách Quy Định ';
        $room_rule = RulesAndRegulation::orderBy('id', 'desc')->get();
        return view('admins.rule-regulation.index', compact('title', 'room_rule'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm Quy Định  ';
        $roomTypes = RoomType::all();

        return  view('admins.rule-regulation.create', compact('title', 'roomTypes'));
    }

    //     /**
    //      * Store a newly created resource in storage.
    //      */
    public function store(Storerules_and_regulationRequest $request)
    {

        try {
            DB::beginTransaction();
            $service = RulesAndRegulation::create($request->all());
            if ($request->has('roomTypes')) {
                $service->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.rule-regulations.index')->with('success', 'Thêm  quy định thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // Chuyển hướng về danh sách với thông báo thành công
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
        $title = 'Sửa Quy Định ';
        $roomTypes = RoomType::all();
        $rules = RulesAndRegulation::findOrfail($id);
        $selectedRoomTypes = $rules->roomTypes->pluck('id')->toArray();
        return  view('admins.rule-regulation.edit', compact('rules', 'title', 'roomTypes', 'selectedRoomTypes'));
    }

    //     /**
    //      * Update the specified resource in storage.
    //      */
    public function update(Updaterules_and_regulationRequest $request, string $id)
    {

        try {
            DB::beginTransaction();
            $service = RulesAndRegulation::findOrFail($id);

            $service->update($request->all());

            if ($request->has('roomTypes')) {
                $service->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.rule-regulations.index')->with('success', 'Cập nhật dịch vụ thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // Chuyển hướng về danh sách với thông báo thành công
    }


    //     /**
    //      * Remove the specified resource from storage.
    //      */
    public function destroy($id)
    {
        $room_type = RulesAndRegulation::findOrFail($id);
        $room_type->delete(); // Xóa mềm

        return redirect()->route('admin.rule-regulations.index')->with('success', 'Loại phòng đã được xóa mềm');
    }



    public function trashed()
    {
        $title = 'Loại quy định  đã xóa';
        $room_types = RulesAndRegulation::onlyTrashed()->get();
        return view('admins.rule-regulation.trashed', compact('title', 'room_types'));
    }

    public function restore($id)
    {
        $room_type = RulesAndRegulation::onlyTrashed()->findOrFail($id);
        $room_type->restore(); // Khôi phục
        return redirect()->route('admin.rule-regulations.index')->with('success', 'Khôi phục loại phòng thành công');
    }

    public function forceDelete($id)
    {
        $room_type = RulesAndRegulation::onlyTrashed()->findOrFail($id);
        $room_type->forceDelete(); // Xóa vĩnh viễn
        return redirect()->route('admin.rule-regulations.trashed')->with('success', 'Xóa vĩnh viễn loại phòng thành công');
    }

    // romm add

    public function room_index()
    {
        $title = 'Danh Sách Quy Định  ';
        // $room = Room::pluck('name','id')->all();
        $room = Room::orderBy('id', 'desc')->get();
        $room_rule = RulesAndRegulation::orderBy('id', 'desc')->get();
        return view('admins.rule-regulation.rule-room.index', compact('title', 'room_rule', 'room'));
    }
    public function create_room()
    {
        $title = 'Thêm Quy Tắc Vào Phòng';
        $room = Room::pluck('name', 'id')->all();
        $rule = RulesAndRegulation::pluck('name', 'id')->all();
        return  view('admins.rule-regulation.rule-room.create', compact('title', 'rule', 'room'));
    }
    public function room_store(Storerules_and_regulationRequest $request)
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
            $room->rules()->attach($data);
        }
        return redirect()->back()->with('success', 'Đã gán quy định cho các phòng thành công!');
        // Chuyển hướng về danh sách với thông báo thành công
        // return redirect()->route('admin.rule-regulations.index')->with('success', 'Thêm quy định phòng thành công');
    }
    // public function view_room( string $id){
    //     $title = 'Các Quy Định Của Phòng ' ;
    //     // $data = Room::with(['rules', 'rars'])->findOrFail($id);
    //     // $room_types =Room::findOrfail($id);
    //     // $room_rule = rules_and_regulation::orderBy('id', 'desc')->get();
    //     // // dd( $room_types) ;
    //     // dd( $data) ;
    //     // // dd( $room_rule) ;
    //     // $room_rar = Room_rar::findOrFail($id);
    //     // $rooms = Room::all();
    //     // $rules = Rules_and_regulation::all();
    //     // return view('admin.room_rars.edit', compact('room_rar', 'rooms', 'rules'));
    //     $room = Room::with(['rules'])->findOrFail($id);
    //     $room_rars =Room_rar::where('room_id', $id)->get(); // Lấy tất cả bản ghi từ `room_rars`
    //     // dd($room_rars);


    //     return view('admins.rule-regulation.rule-room.view',
    //     //  compact('title', 'room_rule' ,'data','room_types','room_rar', 'rooms', 'rules'));
    //     compact('title', 'room' ,'room_rars'));

    // }



    // // xóa bảng
    // public function destroy_room(string $id)
    // {
    //     // $room_type = rules_and_regulation::findOrFail($id);
    //     // $room_type->delete(); // Xóa mềm
    //     $room_rar = Room_rar::findOrFail($id);
    //     $room_rar->delete();
    //     return redirect()->route('admin.rule-regulations.room_index')->with('success', 'Loại phòng đã được xóa mềm');
    // }

    // public function trashed_room()
    // {
    //     $title = 'Loại phòng đã xóa';
    //     $room_rars = Room_rar::onlyTrashed()->get(); // ✅ Lấy dữ liệu đã xóa mềm
    //     return view('admins.rule-regulation.rule-room.trashed', compact('title', 'room_rars'));
    // }
    // public function restore_room($id)
    // {
    //     Room_rar::onlyTrashed()->findOrFail($id)->restore(); // ✅ Laravel tự động khôi phục
    //     return redirect()->route('admin.rule-regulations.room_index')->with('success', 'Khôi phục loại phòng thành công');
    // }

    // public function forceDelete_room($id)
    // {
    //     Room_rar::onlyTrashed()->findOrFail($id)->forceDelete(); // ✅ Xóa hẳn khỏi database
    //     return redirect()->route('admin.rule-regulations.trashed_room')->with('success', 'Xóa vĩnh viễn loại phòng thành công');
    // }
}
