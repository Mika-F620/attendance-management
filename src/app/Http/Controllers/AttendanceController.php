<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\UpdateAttendanceRequest;
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

public function list(Request $request)
{
    // デフォルトで現在の月を表示（もし月がリクエストで来ていなければ）
    $month = $request->input('month', now()->format('Y-m'));

    // 前月ボタンが押された場合
    if ($request->has('previous')) {
        $month = Carbon::parse($month)->subMonth()->format('Y-m');  // 1ヶ月前
    }

    // 翌月ボタンが押された場合
    if ($request->has('next')) {
        $month = Carbon::parse($month)->addMonth()->format('Y-m');  // 1ヶ月後
    }

    // 月ごとに勤怠情報を取得
    $attendances = Attendance::where('user_id', Auth::id())
        ->whereMonth('date', Carbon::parse($month)->month)  // 月をフィルター
        ->whereYear('date', Carbon::parse($month)->year)   // 年をフィルター
        ->orderBy('date', 'desc')  // 日付順に並べる（降順）
        ->paginate(10);  // 1ページ10件に分けて表示

    // 選択した月をビューに渡す
    return view('attendance.list', [
        'attendances' => $attendances,
        'month' => $month
    ]);
}


public function showDetail($id)
{
    // 勤怠の詳細情報を取得
    $attendance = Attendance::where('user_id', Auth::id())  // ログイン中のユーザーに絞る
                            ->where('id', $id)  // 勤怠のIDを指定
                            ->firstOrFail();  // 存在しない場合は404エラー

    return view('attendance.show', compact('attendance'));  // ビューにデータを渡す
}

public function update(UpdateAttendanceRequest $request, $id)
    {
        // フォームリクエストでバリデーションが行われた後、処理が進みます

        // 勤怠情報を取得
        $attendance = Attendance::where('user_id', Auth::id())
                                ->where('id', $id)
                                ->firstOrFail();  // 存在しない場合は404エラー

        // 年と月日を分けて、適切にフォーマットする
        $year = str_replace('年', '', $request->input('date_year'));  // '年'を取り除く
        $date_day = $request->input('date_day');  // 月日（例: 10月2日）

        // 「月日」から「月」と「日」を取得
        $date_parts = explode('月', $date_day);  // 月の部分を取り出す
        $month = str_pad(trim($date_parts[0]), 2, '0', STR_PAD_LEFT);  // 月を2桁に補完（例: 09）
        $day = str_pad(trim(explode('日', $date_parts[1])[0]), 2, '0', STR_PAD_LEFT);  // 日を2桁に補完（例: 02）

        // 正しい形式に変換（'年'を取り除き、'月'と'日'も取り除いて結合）
        $date = $year . '-' . $month . '-' . $day;  // 例: 2024-02-10

        // 更新する勤怠情報の保存
        $attendance->date = $date;  // 年月日を更新
        $attendance->start_time = $request->input('start_time');
        $attendance->end_time = $request->input('end_time');
        $attendance->break_start_time = $request->input('break_start_time');
        $attendance->break_end_time = $request->input('break_end_time');
        $attendance->remarks = $request->input('remarks');  // 備考を保存

        $attendance->save();

        // 更新後、勤怠詳細ページへリダイレクト
        return redirect()->route('attendance.show', $attendance->id)->with('status', '勤怠情報が更新されました');
    }

}
