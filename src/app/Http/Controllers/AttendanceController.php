<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;  // Carbonを使って日付と時間を操作します

class AttendanceController extends Controller
{
  public function show()
{
    // 現在の日付と時間を取得
    $today = Carbon::now();

    // 日本語ロケールを設定
    $today->locale('ja');  // 日本語ロケールを設定して、日本語の曜日を取得

    // 日本語の曜日を表示
    $weekday = $today->isoFormat('ddd');  // 日本語の曜日を完全に取得（例: 月曜日）

    // ステータスの取得（デフォルト: 勤務外）
    $status = '勤務外';  // 初期状態は勤務外に設定
    $attendance = Attendance::where('user_id', Auth::id())
                             ->where('date', $today->format('Y-m-d'))
                             ->first();

    if ($attendance) {
        $status = $attendance->status;  // データベースのステータスを使用
    }

    return view('attendance', ['today' => $today, 'weekday' => $weekday, 'status' => $status]);  // ビューに渡す
}




  // 出勤処理
  public function startWork(Request $request)
{
    // すでに出勤情報がある場合は処理をスキップ
    $existingAttendance = Attendance::where('user_id', Auth::id())
                                      ->where('date', now()->format('Y-m-d'))
                                      ->first();

    if ($existingAttendance) {
        return redirect()->route('attendance')->with('status', '出勤中');
    }

    // 出勤時刻を保存
    $attendance = new Attendance();
    $attendance->user_id = Auth::id();
    $attendance->date = now()->format('Y-m-d');
    $attendance->start_time = now()->format('H:i'); // 現在の時刻を出勤時刻として保存
    $attendance->status = '出勤中';  // ステータスを「出勤中」に設定
    $attendance->save();

    return redirect()->route('attendance')->with('status', '出勤中');
}




  // 休憩入処理
  public function startRest(Request $request)
    {
        $attendance = Attendance::where('user_id', Auth::id())
                                ->where('date', now()->format('Y-m-d'))
                                ->first();

        if ($attendance && $attendance->status == '出勤中') {
            // 休憩開始時刻を保存
            $attendance->status = '休憩中';
            $attendance->break_start_time = now()->format('H:i'); // 休憩開始時刻を保存
            $attendance->save();

            return redirect()->route('attendance')->with('status', '休憩中');
        }

        return redirect()->route('attendance')->with('status', '出勤していません');
    }

    // 休憩戻し処理
    public function endRest(Request $request)
    {
        $attendance = Attendance::where('user_id', Auth::id())
                                ->where('date', now()->format('Y-m-d'))
                                ->first();

        if ($attendance && $attendance->status == '休憩中') {
            // 休憩終了時刻を保存
            $attendance->status = '出勤中';
            $attendance->break_end_time = now()->format('H:i'); // 休憩終了時刻を保存
            $attendance->save();

            return redirect()->route('attendance')->with('status', '出勤中');
        }

        return redirect()->route('attendance')->with('status', '休憩中ではありません');
    }

  // 退勤処理
  public function endWork(Request $request)
{
    $attendance = Attendance::where('user_id', Auth::id())
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    // 退勤する条件を満たしている場合
    if ($attendance && $attendance->status == '出勤中') {
        $attendance->status = '退勤済';  // ステータスを「退勤済」に変更
        $attendance->end_time = now()->format('H:i');  // 退勤時間を保存
        $attendance->save();

        // リダイレクト先のビューで表示を更新
        return redirect()->route('attendance')->with('status', '退勤済');
    }

    return redirect()->route('attendance')->with('status', '休憩中か出勤していません');
}


}
