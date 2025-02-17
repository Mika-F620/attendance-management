<?php

// app/Http/Controllers/Admin/StaffController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;  // Userモデルを使用
use App\Models\Attendance; // Attendanceモデルをインポート
use Illuminate\Http\Request;
use Carbon\Carbon;
use Response;

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

    // スタッフの勤怠情報CSV出力
    public function exportCsv(Request $request, $staffId)
    {
        // URLから月を取得、指定がなければ現在の月
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        // スタッフとその月の勤怠情報を取得
        $staff = User::findOrFail($staffId);
        $attendances = Attendance::where('user_id', $staffId)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->whereYear('date', Carbon::parse($month)->year)
            ->orderBy('date', 'asc')
            ->get();

        // CSVのヘッダー
        $csvHeader = ['日付', '出勤', '退勤', '休憩', '合計'];

        // CSVデータを準備
        $csvData = [];
        foreach ($attendances as $attendance) {
            $csvData[] = [
                Carbon::parse($attendance->date)->format('m/d (D)'),
                Carbon::parse($attendance->start_time)->format('H:i'),
                Carbon::parse($attendance->end_time)->format('H:i'),
                $attendance->break_start_time && $attendance->break_end_time
                    ? Carbon::parse($attendance->break_start_time)->diff(Carbon::parse($attendance->break_end_time))->format('%H:%I')
                    : '-',
                Carbon::parse($attendance->start_time)->diff(Carbon::parse($attendance->end_time))->format('%H:%I') ?? '-'
            ];
        }

        // CSV出力処理
        $filename = 'attendance_' . $staff->name . '_' . Carbon::parse($month)->format('Y_m') . '.csv';
        $handle = fopen('php://output', 'w');

        // 出力ヘッダー
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // ヘッダー行を出力
        fputcsv($handle, $csvHeader);

        // データ行を出力
        foreach ($csvData as $data) {
            fputcsv($handle, $data);
        }

        fclose($handle);
        exit();
    }
}
