<?php

// app/Http/Controllers/Admin/StaffController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;  // Userモデルを使用
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // スタッフ一覧ページ
    public function index()
    {
        // 全てのユーザーを取得（管理者以外のユーザーのみ）
        $staffs = User::where('role', 'user')->get();

        // ビューにデータを渡して表示
        return view('admin.staff.list', compact('staffs'));
    }
}
