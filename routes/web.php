<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
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
        Route::get('/dashboard', function () {
            return view('admins/dashboard');
        })->name('dashboard');

        Route::prefix('hotels')
            ->as('hotels.')
            ->group(function () {
                Route::get('/', [HotelController::class, 'index'])->name('index');
                Route::get('/create', [HotelController::class, 'create'])->name('create');
                Route::get('/{id}/detail', [HotelController::class, 'detail'])->name('detail');
                Route::get('/{id}/update', [HotelController::class, 'update'])->name('update');
                Route::get('/{id}/destroy', [HotelController::class, 'destroy'])->name('destroy');
            });
    });






// client

Route::get('/', [HomeController::class, 'index'])->name('home');
