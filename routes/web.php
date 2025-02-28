<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RulesAndRegulationController;
use App\Models\Room_amenity;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Client\HomeController;

use App\Http\Controllers\ReviewController;

use App\Models\Amenity;


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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';



Route::prefix('admin')

    ->as('admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('admins/dashboard');
        })->name('dashboard');

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


        Route::resource('promotions', PromotionController::class);
    });




// client

Route::get('/', [HomeController::class, 'index'])->name('home');
