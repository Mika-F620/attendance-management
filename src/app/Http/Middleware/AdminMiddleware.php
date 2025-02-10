<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 管理者かどうかを確認
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 管理者でない場合はログインページへリダイレクト
        return redirect()->route('admin.login');
    }
}
