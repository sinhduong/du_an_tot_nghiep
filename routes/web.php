<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\Client\HomeController;

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
    // ->middleware(['auth', 'admin'])
    // ->middleware(['auth','verified'])
    ->as('admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('admins/dashboard');
        })->name('dashboard');

        Route::prefix('room-types') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('room_types.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [RoomTypeController::class, 'index'])->name('index'); // Danh sách loại phòng
                Route::get('/create', [RoomTypeController::class, 'create'])->name('create'); // Form thêm mới
                Route::post('/store', [RoomTypeController::class, 'store'])->name('store'); // Lưu loại phòng
                Route::get('{id}/edit', [RoomTypeController::class, 'edit'])->name('edit'); // Form chỉnh sửa
                Route::put('{id}/update', [RoomTypeController::class, 'update'])->name('update'); // Cập nhật
                Route::delete('{id}/destroy', [RoomTypeController::class, 'destroy'])->name('destroy'); // Xóa loại phòng
                Route::get('/trashed', [RoomTypeController::class, 'trashed'])->name('trashed'); // Danh sách phòng đã xóa mềm
                Route::patch('/{id}/restore', [RoomTypeController::class, 'restore'])->name('restore'); // Khôi phục phòng đã xóa mềm
                Route::delete('/{id}/force-delete', [RoomTypeController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn

            });

        Route::prefix('rooms')
            ->as('rooms.')
            ->group(function () {
                Route::get('/', [RoomController::class, 'index'])->name('index');
                Route::get('/create', [RoomController::class, 'create'])->name('create');
                Route::post('/store', [RoomController::class, 'store'])->name('store');
                Route::get('{id}/edit', [RoomController::class, 'edit'])->name('edit');
                Route::put('{id}/update', [RoomController::class, 'update'])->name('update');
                Route::delete('{id}/destroy', [RoomController::class, 'destroy'])->name('destroy');
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
    });






// client

Route::get('/', [HomeController::class, 'index'])->name('home');
