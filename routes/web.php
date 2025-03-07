<?php

use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\RulesAndRegulationController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StaffRoleController;
use App\Http\Controllers\StaffShiftController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ContactsController;
use App\Http\Controllers\Admin\IntroductionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';



Route::prefix('admin')

    ->as('admin.')
    // ->middleware(['auth', 'role:admin']) // Chỉ admin mới truy cập
    ->group(function () {
        Route::get('/', function () {
            return view('admins/dashboard');
        })->name('dashboard');

        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('room-types')
            ->as('room_types.')
            ->group(function () {
                Route::get('/', [RoomTypeController::class, 'index'])->name('index');
                Route::get('/create', [RoomTypeController::class, 'create'])->name('create');
                Route::post('/store', [RoomTypeController::class, 'store'])->name('store');
                Route::get('{id}/edit', [RoomTypeController::class, 'edit'])->name('edit');
                Route::get('{id}/show', [RoomTypeController::class, 'show'])->name('show');
                Route::put('{id}/update', [RoomTypeController::class, 'update'])->name('update');
                Route::post('{id}/delete-image', [RoomTypeController::class, 'deleteImage'])->name('delete-image');
                Route::delete('{id}/destroy', [RoomTypeController::class, 'destroy'])->name('destroy');
                Route::get('/trashed', [RoomTypeController::class, 'trashed'])->name('trashed');
                Route::patch('{id}/restore', [RoomTypeController::class, 'restore'])->name('restore');
                Route::delete('{id}/force-delete', [RoomTypeController::class, 'forceDelete'])->name('forceDelete');
            });

        Route::prefix('rooms')
            ->as('rooms.')
            ->group(function () {
                Route::get('/', [RoomController::class, 'index'])->name('index');
                Route::get('/create', [RoomController::class, 'create'])->name('create');
                Route::post('/store', [RoomController::class, 'store'])->name('store');
                Route::get('{room}/edit', [RoomController::class, 'edit'])->name('edit');
                Route::put('{room}/update', [RoomController::class, 'update'])->name('update');
                Route::delete('{room}/destroy', [RoomController::class, 'destroy'])->name('destroy'); // Xóa
                Route::get('/trashed', [RoomController::class, 'trashed'])->name('trashed'); // Danh sách đã xóa mềm
                Route::patch('/{room}/restore', [RoomController::class, 'restore'])->name('restore'); // Khôi phục khi đã xóa mềm
                Route::delete('/{room}/force-delete', [RoomController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn
            });

        Route::prefix('bookings')
            ->as('bookings.')
            ->group(function () {
                Route::get('/',                 [BookingController::class, 'index'])->name('index');
                Route::get('/create',           [BookingController::class, 'create'])->name('create');
                Route::post('/store',           [BookingController::class, 'store'])->name('store');
                Route::get('{id}/show',         [BookingController::class, 'show'])->name('show');
                Route::get('{id}/edit',         [BookingController::class, 'edit'])->name('edit');
                Route::put('{id}/update',       [BookingController::class, 'update'])->name('update');
                Route::delete('{id}/destroy',   [BookingController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('staffs') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('staffs.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [StaffController::class, 'index'])->name('index');
                Route::get('/create', [StaffController::class, 'create'])->name('create');
                Route::post('/store', [StaffController::class, 'store'])->name('store');
                Route::get('{staff}/show', [StaffController::class, 'show'])->name('show');
                Route::get('{staff}/edit', [StaffController::class, 'edit'])->name('edit');
                Route::put('{staff}/update', [StaffController::class, 'update'])->name('update');
                Route::delete('{staff}/destroy', [StaffController::class, 'destroy'])->name('destroy'); // Xóa
                Route::get('/trashed', [StaffController::class, 'trashed'])->name('trashed'); // Danh sách đã xóa mềm
                Route::patch('/{staff}/restore', [StaffController::class, 'restore'])->name('restore'); // Khôi phục khi đã xóa mềm
                Route::delete('/{staff}/force-delete', [StaffController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn

            });

            Route::prefix('staff_roles') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('staff_roles.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [StaffRoleController::class, 'index'])->name('index');
                Route::get('/create', [StaffRoleController::class, 'create'])->name('create');
                Route::post('/store', [StaffRoleController::class, 'store'])->name('store');
                Route::get('{staffRole}/show', [StaffRoleController::class, 'show'])->name('show');
                Route::get('{staffRole}/edit', [StaffRoleController::class, 'edit'])->name('edit');
                Route::put('{staffRole}/update', [StaffRoleController::class, 'update'])->name('update');
                Route::delete('{staffRole}/destroy', [StaffRoleController::class, 'destroy'])->name('destroy'); // Xóa
                Route::get('/trashed', [StaffRoleController::class, 'trashed'])->name('trashed'); // Danh sách đã xóa mềm
                Route::patch('/{staffRole}/restore', [StaffRoleController::class, 'restore'])->name('restore'); // Khôi phục khi đã xóa mềm
                Route::delete('/{staffRole}/force-delete', [StaffRoleController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn

            });

            Route::prefix('staff_shifts') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('staff_shifts.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [StaffShiftController::class, 'index'])->name('index');
                Route::get('/create', [StaffShiftController::class, 'create'])->name('create');
                Route::post('/store', [StaffShiftController::class, 'store'])->name('store');
                Route::get('{staffShift}/show', [StaffShiftController::class, 'show'])->name('show');
                Route::get('{staffShift}/edit', [StaffShiftController::class, 'edit'])->name('edit');
                Route::put('{staffShift}/update', [StaffShiftController::class, 'update'])->name('update');
                Route::delete('{staffShift}/destroy', [StaffShiftController::class, 'destroy'])->name('destroy'); // Xóa
                Route::get('/trashed', [StaffShiftController::class, 'trashed'])->name('trashed'); // Danh sách đã xóa mềm
                Route::patch('/{staffShift}/restore', [StaffShiftController::class, 'restore'])->name('restore'); // Khôi phục khi đã xóa mềm
                Route::delete('/{staffShift}/force-delete', [StaffShiftController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn

            });

        Route::prefix('staff_attendances') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('staff_attendances.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [StaffAttendanceController::class, 'index'])->name('index');
                Route::post('/check-in', [StaffAttendanceController::class, 'checkIn'])->name('check-in');
                Route::post('/check-out', [StaffAttendanceController::class, 'checkOut'])->name('check-out');
            });

        Route::prefix('reviews') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('reviews.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [ReviewController::class, 'index'])->name('index');
                Route::get('{review}/show', [ReviewController::class, 'show'])->name('show');
                Route::post('{review}/response', [ReviewController::class, 'response'])->name('response');
                Route::delete('{review}/destroy', [ReviewController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('rule-regulations') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('rule-regulations.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [RulesAndRegulationController::class, 'index'])->name('index'); // Danh sách loại phòng
                Route::get('/create', [RulesAndRegulationController::class, 'create'])->name('create'); // Form thêm mới
                Route::post('/store', [RulesAndRegulationController::class, 'store'])->name('store'); // Lưu loại phòng
                Route::get('{id}/edit', [RulesAndRegulationController::class, 'edit'])->name('edit'); // Form chỉnh sửa
                Route::put('{id}/update', [RulesAndRegulationController::class, 'update'])->name('update'); // Cập nhật
                Route::delete('{id}/destroy', [RulesAndRegulationController::class, 'destroy'])->name('destroy'); // Xóa loại phòng
                Route::get('/trashed', [RulesAndRegulationController::class, 'trashed'])->name('trashed'); // Danh sách phòng đã xóa mềm
                Route::patch('/{id}/restore', [RulesAndRegulationController::class, 'restore'])->name('restore'); // Khôi phục phòng đã xóa mềm
                Route::delete('/{id}/force-delete', [RulesAndRegulationController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn
                // Thêm Quy Tắc Vào Phòng
                Route::get('/create_room', [RulesAndRegulationController::class, 'create_room'])->name('create_room'); // Form thêm mới
                Route::get('/room_index', [RulesAndRegulationController::class, 'room_index'])->name('room_index'); // Form thêm mới
                Route::post('/room_store', [RulesAndRegulationController::class, 'room_store'])->name('room_store'); // Lưu loại phòng

                Route::get('{id}/view_room', [RulesAndRegulationController::class, 'view_room'])->name('view_room'); // Form thêm mới
                Route::delete('{id}/destroy_room', [RulesAndRegulationController::class, 'destroy_room'])->name('destroy_room'); // Xóa loại phòng
                Route::get('/trashed_room', [RulesAndRegulationController::class, 'trashed_room'])->name('trashed_room'); // Danh sách phòng đã xóa mềm
                Route::patch('/{id}/restore_room', [RulesAndRegulationController::class, 'restore_room'])->name('restore_room'); // Khôi phục phòng đã xóa mềm
                Route::delete('/{id}/force-delete_room', [RulesAndRegulationController::class, 'forceDelete_room'])->name('forceDelete_room'); // Xóa vĩnh viễn

            });
        Route::prefix('amenities') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('amenities.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [AmenityController::class, 'index'])->name('index'); // Danh sách loại phòng
                Route::get('/create', [AmenityController::class, 'create'])->name('create'); // Form thêm mới
                Route::post('/store', [AmenityController::class, 'store'])->name('store'); // Lưu loại phòng
                Route::get('{id}/edit', [AmenityController::class, 'edit'])->name('edit'); // Form chỉnh sửa
                Route::put('{id}/update', [AmenityController::class, 'update'])->name('update'); // Cập nhật
                Route::delete('{id}/destroy', [AmenityController::class, 'destroy'])->name('destroy'); // Xóa loại phòng
                Route::get('/trashed', [AmenityController::class, 'trashed'])->name('trashed'); // Danh sách phòng đã xóa mềm
                Route::patch('/{id}/restore', [AmenityController::class, 'restore'])->name('restore'); // Khôi phục phòng đã xóa mềm
                Route::delete('/{id}/force-delete', [AmenityController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn


                Route::get('/create_room', [AmenityController::class, 'create_room'])->name('create_room'); // Form thêm mới
                Route::get('/room_index', [AmenityController::class, 'room_index'])->name('room_index'); // Form thêm mới
                Route::post('/room_store', [AmenityController::class, 'room_store'])->name('room_store'); // Lưu loại phòng

                Route::get('{id}/view_room', [AmenityController::class, 'view_room'])->name('view_room'); // Form thêm mới
                Route::delete('{id}/destroy_room', [AmenityController::class, 'destroy_room'])->name('destroy_room'); // Xóa loại phòng
                Route::get('/trashed_room', [AmenityController::class, 'trashed_room'])->name('trashed_room'); // Danh sách phòng đã xóa mềm
                Route::patch('/{id}/restore_room', [AmenityController::class, 'restore_room'])->name('restore_room'); // Khôi phục phòng đã xóa mềm
                Route::delete('/{id}/force-delete_room', [AmenityController::class, 'forceDelete_room'])->name('forceDelete_room'); // Xóa vĩnh viễn
            });

        Route::prefix('bookings')
            ->as('bookings.')
            ->group(function () {
                Route::get('/',                 [BookingController::class, 'index'])->name('index');
                Route::get('/create',           [BookingController::class, 'create'])->name('create');
                Route::post('/store',           [BookingController::class, 'store'])->name('store');
                Route::get('{id}/show',         [BookingController::class, 'show'])->name('show');
                Route::get('{id}/edit',         [BookingController::class, 'edit'])->name('edit');
                Route::put('{id}/update',       [BookingController::class, 'update'])->name('update');
                Route::delete('{id}/destroy',   [BookingController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('services') // Quản lý dịch vụ khách sạn
            ->as('services.')
            ->group(function () {
                Route::get('/', [ServiceController::class, 'index'])->name('index'); // Danh sách dịch vụ
                Route::get('/create', [ServiceController::class, 'create'])->name('create'); // Form thêm mới
                Route::post('/store', [ServiceController::class, 'store'])->name('store'); // Lưu dịch vụ
                Route::get('{id}/edit', [ServiceController::class, 'edit'])->name('edit'); // Form chỉnh sửa
                Route::put('{id}/update', [ServiceController::class, 'update'])->name('update'); // Cập nhật
                Route::delete('{id}/destroy', [ServiceController::class, 'destroy'])->name('destroy'); // Xóa dịch vụ
                Route::get('/trashed', [ServiceController::class, 'trashed'])->name('trashed'); // Danh sách dịch vụ đã xóa mềm
                Route::patch('/{id}/restore', [ServiceController::class, 'restore'])->name('restore'); // Khôi phục dịch vụ đã xóa mềm
                Route::delete('/{id}/force-delete', [ServiceController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn
            });
        Route::resource('services', ServiceController::class);

        Route::resource('promotions', PromotionController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('abouts', AboutController::class);
        Route::resource('introductions', IntroductionController::class);
        Route::resource('faqs', FaqController::class);
        Route::resource('payments', PaymentController::class);

        Route::prefix('admin')->group(function () {
            Route::get('/contacts', [ContactsController::class, 'index'])->name('contacts.index');
            Route::get('/contacts/{contact}', [ContactsController::class, 'show'])->name('contacts.show');
            Route::post('/contacts/{contact}/reply', [ContactsController::class, 'reply'])->name('contacts.reply');
        });
    });




// client

Route::get('/', [HomeController::class, 'index'])->name('home');
