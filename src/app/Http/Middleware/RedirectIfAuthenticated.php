<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // 管理者用のログインを確認
                if ($guard === 'admin') {
                    // 管理者ログイン後のリダイレクト先
                    return redirect()->route('admin.attendance.list');
                }

                // 一般ユーザー用のログインを確認
                return redirect(RouteServiceProvider::HOME);  // 一般ユーザー用のリダイレクト先
            }
        }

        return $next($request);
    }
}
