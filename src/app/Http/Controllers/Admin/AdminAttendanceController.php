<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\User;

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

    // スタッフの勤怠詳細ページ
    public function staffAttendance($id, Request $request)
    {
        // 指定されたユーザーの情報を取得
        $staff = User::findOrFail($id);
        
        // 月のパラメータを取得（GETリクエストの"month"を使う）
        $month = $request->input('month', Carbon::now()->format('Y-m')); // デフォルトは現在の月

        // スタッフの勤怠データを取得（過去の勤怠データも含めて）
        $attendances = Attendance::where('user_id', $id)
                                  ->whereMonth('date', Carbon::parse($month)->month)
                                  ->whereYear('date', Carbon::parse($month)->year)
                                  ->orderBy('date', 'desc')
                                  ->paginate(10);  // 1ページ10件表示

        // ビューにスタッフ情報、勤怠データ、月情報を渡す
        return view('admin.attendance.staff.show', compact('staff', 'attendances', 'month'));
    }
}
