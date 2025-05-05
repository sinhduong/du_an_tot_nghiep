<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // if (!Auth::check() || Auth::user()->role->name !== $role) {
        //     return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        // }

        if (!Auth::check()) {
            return redirect('/')->with('error', 'Vui lòng đăng nhập!');
        }

        if (!Auth::user()->hasRole($role)) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        }
        return $next($request);
    }
}
