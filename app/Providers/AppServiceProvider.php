<?php

namespace App\Providers;

use App\Models\System;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // Thiết lập locale cho ứng dụng Laravel
        app()->setLocale('vi');
        // Tự động truyền $systems vào tất cả các view trong thư mục 'clients'
        View::composer('clients.*', function ($view) {
            $systems = System::orderBy('id', 'desc')->first() ?? (object) ['logo' => null];
            $view->with('systems', $systems);
        });
    }
}
