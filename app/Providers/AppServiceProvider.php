<?php

namespace App\Providers;

use App\Models\Banner;
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
        
        // Cấu hình phân trang sử dụng Bootstrap
        \Illuminate\Pagination\Paginator::useBootstrap();
        
        // Tự động truyền $systems vào tất cả các view trong thư mục 'clients'
        View::composer('clients.*', function ($view) {
            $systems = System::orderBy('id', 'desc')->first() ?? (object) ['logo' => null];
            $banner = Banner::where('is_use', 1)->first();
            $view->with(compact('banner', 'systems'));
        });
    }
}
