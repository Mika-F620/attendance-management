<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;  // Carbonを使って日付と時間を操作します

class AttendanceController extends Controller
{
  // 出勤処理
  public function startWork(Request $request)
  {
    $attendance = new Attendance();
    $attendance->user_id = Auth::id();
    $attendance->date = now()->format('Y-m-d');
    $attendance->start_time = now()->format('H:i');
    $attendance->status = '出勤';
    $attendance->save();

    return response()->json(['message' => '出勤しました']);
  }

  public function show()
  {
    // 現在の日付と時間を取得
    $today = Carbon::now();

    // 日本語ロケールを設定
    $today->locale('ja');  // ここで日本語ロケールを設定

    // 日本語の曜日を表示
    $weekday = $today->isoFormat('ddd'); // 日本語曜日の取得

    return view('attendance', ['today' => $today, 'weekday' => $weekday]);  // ビューに渡す
  }
}
