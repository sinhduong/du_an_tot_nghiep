<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ContactsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\StaffRoleController;
use App\Http\Controllers\Admin\StaffShiftController;
use App\Http\Controllers\Admin\ServicePlusController;
use App\Http\Controllers\Admin\IntroductionController;
use App\Http\Controllers\Admin\SaleRoomTypeController;
use App\Http\Controllers\Admin\StaffAttendanceController;
use App\Http\Controllers\Admin\RoomTypePromotionController;
use App\Http\Controllers\Admin\RulesAndRegulationController;
use App\Http\Controllers\Client\BookingController as ClientBookingController;

// client


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
    ->middleware('auth')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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

        Route::prefix('room-types-promotion')
            ->as('room_types_promotion.')
            ->group(function () {
                Route::get('/', [RoomTypePromotionController::class, 'index'])->name('index');
                Route::get('/create', [RoomTypePromotionController::class, 'create'])->name('create');
                Route::post('/store', [RoomTypePromotionController::class, 'store'])->name('store');
                Route::get('{id}/edit', [RoomTypePromotionController::class, 'edit'])->name('edit');
                Route::get('{id}/show', [RoomTypePromotionController::class, 'show'])->name('show');
                Route::put('{id}/update', [RoomTypePromotionController::class, 'update'])->name('update');
                Route::delete('{id}/destroy', [RoomTypePromotionController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('rooms')
            ->as('rooms.')
            ->group(function () {
                Route::get('/', [RoomController::class, 'index'])->name('index');
                Route::get('/create', [RoomController::class, 'create'])->name('create');
                Route::post('/store', [RoomController::class, 'store'])->name('store');
                Route::get('{room}/edit', [RoomController::class, 'edit'])->name('edit');
                Route::get('/booked', [RoomController::class, 'bookedRooms'])->name('booked'); // list phòng đã đặt
                Route::get('{id}/show', [RoomController::class, 'show'])->name('show');
                Route::put('{room}/update', [RoomController::class, 'update'])->name('update');
                Route::delete('{room}/destroy', [RoomController::class, 'destroy'])->name('destroy'); // Xóa
                Route::get('/trashed', [RoomController::class, 'trashed'])->name('trashed'); // Danh sách đã xóa mềm
                Route::patch('/{room}/restore', [RoomController::class, 'restore'])->name('restore'); // Khôi phục khi đã xóa mềm
                Route::delete('/{room}/force-delete', [RoomController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn
            });

        Route::prefix('bookings')
            ->as('bookings.')
            ->group(function () {
                Route::get('/', [BookingController::class, 'index'])->name('index');
                Route::get('/create', [BookingController::class, 'create'])->name('create');
                Route::post('/store', [BookingController::class, 'store'])->name('store');
                Route::post('/checkin/store', [BookingController::class, 'storeCheckIn'])->name('checkin.store');
                Route::get('{id}/show', [BookingController::class, 'show'])->name('show');
                Route::post('{id}/service-plus', [BookingController::class, 'updateServicePlus'])->name('service_plus.update');
                Route::get('{id}/edit', [BookingController::class, 'edit'])->name('edit');
                Route::put('{id}/update', [BookingController::class, 'update'])->name('update');
                Route::delete('{id}/destroy', [BookingController::class, 'destroy'])->name('destroy');
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

            });

        Route::prefix('amenities') // Đặt tên theo số nhiều chuẩn RESTful
            ->as('amenities.') // Tên route để sử dụng dễ dàng trong view/controller
            ->group(function () {
                Route::get('/', [AmenityController::class, 'index'])->name('index'); // Danh sách loại phòng
                Route::get('/create', [AmenityController::class, 'create'])->name('create'); // Form thêm mới
                Route::post('/store', [AmenityController::class, 'store'])->name('store'); // Lưu loại phòng
                Route::get('{id}/show', [AmenityController::class, 'show'])->name('show'); // Form chỉnh sửa
                Route::get('{id}/edit', [AmenityController::class, 'edit'])->name('edit'); // Form chỉnh sửa
                Route::put('{id}/update', [AmenityController::class, 'update'])->name('update'); // Cập nhật
                Route::delete('{id}/destroy', [AmenityController::class, 'destroy'])->name('destroy'); // Xóa loại phòng
                Route::get('/trashed', [AmenityController::class, 'trashed'])->name('trashed'); // Danh sách phòng đã xóa mềm
                Route::patch('/{id}/restore', [AmenityController::class, 'restore'])->name('restore'); // Khôi phục phòng đã xóa mềm
                Route::delete('/{id}/force-delete', [AmenityController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn
            });

        Route::prefix('bookings')
            ->as('bookings.')
            ->group(function () {
                Route::get('/', [BookingController::class, 'index'])->name('index');
                Route::get('/create', [BookingController::class, 'create'])->name('create');
                Route::post('/store', [BookingController::class, 'store'])->name('store');
                Route::get('{id}/show', [BookingController::class, 'show'])->name('show');
                Route::get('{id}/edit', [BookingController::class, 'edit'])->name('edit');
                Route::put('{id}/update', [BookingController::class, 'update'])->name('update');
                Route::delete('{id}/destroy', [BookingController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('systems')
            ->as('systems.')
            ->group(function () {
                Route::get('/', [SystemController::class, 'index'])->name('index');
                Route::get('/create', [SystemController::class, 'create'])->name('create');
                Route::post('/store', [SystemController::class, 'store'])->name('store');
                Route::get('{id}/show', [SystemController::class, 'show'])->name('show');
                Route::get('{id}/edit', [SystemController::class, 'edit'])->name('edit');
                Route::put('{id}/update', [SystemController::class, 'update'])->name('update');
                Route::delete('{id}/destroy', [SystemController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('contacts')
        ->as('contacts.')
        ->group(function () {
            Route::get('/contacts', [ContactsController::class, 'index'])->name('index');
            Route::get('/contacts/{contact}', [ContactsController::class, 'show'])->name('show');
            Route::post('/contacts/{contact}/reply', [ContactsController::class, 'reply'])->name('reply');
        });

        Route::prefix('services') // Đặt tên theo số nhiều chuẩn RESTful
        ->as('services.') // Tên route để sử dụng dễ dàng trong view/controller
        ->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('index'); // Danh sách loại phòng
            Route::get('/create', [ServiceController::class, 'create'])->name('create'); // Form thêm mới
            Route::post('/store', [ServiceController::class, 'store'])->name('store'); // Lưu loại phòng
            Route::get('{id}/show', [ServiceController::class, 'show'])->name('show'); // Form chỉnh sửa
            Route::get('{id}/edit', [ServiceController::class, 'edit'])->name('edit'); // Form chỉnh sửa
            Route::put('{id}/update', [ServiceController::class, 'update'])->name('update'); // Cập nhật
            Route::delete('{id}/destroy', [ServiceController::class, 'destroy'])->name('destroy'); // Xóa loại phòng
            Route::get('/trashed', [ServiceController::class, 'trashed'])->name('trashed'); // Danh sách phòng đã xóa mềm
            Route::patch('/{id}/restore', [ServiceController::class, 'restore'])->name('restore'); // Khôi phục phòng đã xóa mềm
            Route::delete('/{id}/force-delete', [ServiceController::class, 'forceDelete'])->name('forceDelete'); // Xóa vĩnh viễn
        });

        Route::prefix('service_plus') // Đặt tên theo số nhiều chuẩn RESTful
        ->as('service_plus.') // Tên route để sử dụng dễ dàng trong view/controller
        ->group(function () {
            Route::get('/', [ServicePlusController::class, 'index'])->name('index'); // Danh sách loại phòng
            Route::get('/create', [ServicePlusController::class, 'create'])->name('create'); // Form thêm mới
            Route::post('/store', [ServicePlusController::class, 'store'])->name('store'); // Lưu loại phòng
            Route::get('{id}/show', [ServicePlusController::class, 'show'])->name('show'); // Form chỉnh sửa
            Route::get('{id}/edit', [ServicePlusController::class, 'edit'])->name('edit'); // Form chỉnh sửa
            Route::put('{id}/update', [ServicePlusController::class, 'update'])->name('update'); // Cập nhật
            Route::delete('{id}/destroy', [ServicePlusController::class, 'destroy'])->name('destroy'); // Xóa loại phòng
        });

        Route::resource('promotions', PromotionController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('abouts', AboutController::class);
        Route::resource('introductions', IntroductionController::class);
        Route::resource('policies', PolicyController::class);
        Route::resource('banners', BannerController::class);
        Route::resource('faqs', FaqController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('sale-room-types', SaleRoomTypeController::class);
    });




// client

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/room/{id}', [HomeController::class, 'show'])->name('room.details');
Route::get('/room', [HomeController::class, 'room_view'])->name('room.view');

Route::get('/cau-hoi-thuong-gap', [HomeController::class, 'faqs'])->name('faqs');
Route::get('/dich-vu', [HomeController::class, 'services'])->name('services');
Route::get('/gioi-thieu', [HomeController::class, 'introductions'])->name('introductions');
// Route::post('/sendmail', [HomeController::class, 'sendmail']);
Route::get('/lien-he-voi-chung-toi', [HomeController::class, 'contacts'])->name('contacts');
Route::post('/lien-he-voi-chung-toi', [HomeController::class, 'send'])->name('contact.send');

// Route::resource('services', ServiceController::class);
Route::prefix('bookings')
    ->as('bookings.')
    // ->middleware('auth') // Nếu client cần đăng nhập
    ->group(function () {
        Route::get('/', [ClientBookingController::class, 'index'])->name('index');
        Route::get('/create', [ClientBookingController::class, 'create'])->name('create');
        Route::post('/confirm', [ClientBookingController::class, 'confirm'])->name('confirm'); // Chuyển từ create sang confirm
        Route::post('/store', [ClientBookingController::class, 'store'])->name('store'); // Lưu dữ liệu từ confirm
        Route::get('{id}/returnVnpay',           [ClientBookingController::class, 'returnVnpay'])->name('return.vnpay');
        Route::get('{id}/show', [ClientBookingController::class, 'show'])->name('show');
        Route::get('{id}/edit', [ClientBookingController::class, 'edit'])->name('edit');
        Route::put('{id}', [ClientBookingController::class, 'update'])->name('update');
        Route::delete('{id}/destroy', [ClientBookingController::class, 'destroy'])->name('destroy');
        Route::post('/check-promotion', [ClientBookingController::class, 'checkPromotion'])->name('check-promotion');

        Route::get('/payment/callback', [ClientBookingController::class, 'paymentCallback'])->name('payment.callback');
        Route::get('/success', [ClientBookingController::class, 'success'])->name('success');
    });

Route::get('payments', [HomeController::class, 'paymentsList'])->name('payments.lists');
