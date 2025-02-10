<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;


class AdminAttendanceController extends Controller
{
    // 勤怠一覧ページ
    public function index()
    {
        // 管理者用に勤怠データを取得
        $attendances = Attendance::all();  // 必要に応じて条件を追加

        // ビューにデータを渡して表示
        return view('admin.attendance.list', compact('attendances'));
    }
}
