<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminAuthController extends Controller
{
    // 管理者ログインフォームの表示
    public function loginForm()
    {
        return view('auth.admin.login');  // admin/login.blade.php のビューを表示
    }

    // 管理者ログイン処理
    public function login(Request $request)
    {
        // ログインに必要な情報を取得（login_identifier → emailに変更）
        $credentials = $request->only('email', 'password');  // 'login_identifier' を 'email' に変更

        // 管理者の認証
        if (Auth::attempt($credentials) && Auth::user()->role == 'admin') {
            // ログイン成功後、管理者は勤怠一覧へ遷移
            return redirect()->route('admin.attendance.list');
        }

        return back()->withErrors(['login_error' => '認証に失敗しました。']);
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();  // 管理者のセッションを削除
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');  // ログインページにリダイレクト
    }
}
