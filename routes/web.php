<?php

use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ước mơ ra trường
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    // ->middleware(['auth', 'admin'])
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admins/dashboard');
        })->name('dashboard');
        Route::prefix('hotels')
            ->as('hotels.')
            ->group(function () {
                Route::get('/',[HotelController::class,'index'])->name('hotel.index');
                Route::get('/create',[HotelController::class,'create'])->name('hotel.create');
                Route::get('/{id}/detail',[HotelController::class,'detail'])->name('hotel.detail');
                Route::get('/{id}/update',[HotelController::class,'update'])->name('hotel.update');
                Route::get('/{id}/destroy',[HotelController::class,'destroy'])->name('hotel.destroy');
            });
    });






    // client

 Route::get('/',[HomeController::class,'index'])->name('trangChu');
