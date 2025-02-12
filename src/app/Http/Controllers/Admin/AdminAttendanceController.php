<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;


class AdminAttendanceController extends Controller
{
    // 勤怠一覧ページ
    public function index(Request $request)
    {
        // 現在の日付を取得（デフォルトは今日の日付）
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        // 日付ごとに勤怠データを取得
        $attendances = Attendance::whereDate('date', $date)  // 特定の日付に絞る
                                ->orderBy('date', 'desc')
                                ->paginate(10);  // 1ページに10件表示

        // ビューにデータを渡して表示
        return view('admin.attendance.list', compact('attendances', 'date'));
    }
}
