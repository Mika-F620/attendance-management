<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // ダミーデータ作成
        $users = User::where('role', 'user')->get();  // 一般ユーザーを取得

        foreach ($users as $user) {
            // 各ユーザーに対して複数の勤怠データを作成
            Attendance::create([
                'user_id' => $user->id,
                'date' => '2025-02-18',
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_start_time' => '12:00',
                'break_end_time' => '13:00',
                'status' => '退勤済',
                'remarks' => '定時退勤',
                'approval_status' => '承認待ち',  // 初期状態は承認待ち
            ]);

            Attendance::create([
                'user_id' => $user->id,
                'date' => '2025-02-19',
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_start_time' => '12:00',
                'break_end_time' => '13:00',
                'status' => '退勤済',
                'remarks' => '定時退勤',
                'approval_status' => '承認済み',  // 2日目は承認済み
            ]);
        }
    }
}
