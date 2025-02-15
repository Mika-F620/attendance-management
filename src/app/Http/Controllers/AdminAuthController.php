<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\AdminLoginRequest;

class AdminAuthController extends Controller
{
    // 管理者ログインフォームの表示
    public function loginForm()
    {
        return view('auth.admin.login');  // admin/login.blade.php のビューを表示
    }

    // 管理者ログイン処理
    public function login(AdminLoginRequest $request)
    {
        // ログインに必要な情報を取得（login_identifier → emailに変更）
        $credentials = $request->only('email', 'password');  // 'login_identifier' を 'email' に変更

        // 管理者認証（usersテーブルを使う）
        if (Auth::guard('admin')->attempt($credentials)) {
            // 管理者がログインした場合、roleが'admin'であるか確認
            $user = Auth::guard('admin')->user();  // 管理者用のユーザー情報を取得

            if ($user->role == 'admin') {
                // ログイン成功後、管理者用のダッシュボードに遷移
                return redirect()->route('admin.attendance.list');
            }
        }

        return back()->withErrors(['login_error' => 'ログイン情報が登録されていません。']);
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
