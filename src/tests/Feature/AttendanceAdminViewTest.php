<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AttendanceAdminViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // その日になされた全ユーザーの勤怠情報が正確に確認できる
    // public function test_all_users_attendance_info_is_displayed_for_admin()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']); // 'is_admin' から 'role' に変更
    //     $this->actingAs($admin);

    //     // 2. 勤怠情報を作成
    //     $user1 = User::factory()->create();  // 一般ユーザー1
    //     $user2 = User::factory()->create();  // 一般ユーザー2

    //     $attendance1 = Attendance::create([
    //         'user_id' => $user1->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks 1',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     $attendance2 = Attendance::create([
    //         'user_id' => $user2->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:30',
    //         'end_time' => '18:30',
    //         'break_start_time' => '12:30',
    //         'break_end_time' => '13:30',
    //         'remarks' => 'Test remarks 2',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 勤怠一覧画面を開く
    //     $response = $this->get(route('attendance.index'));  // 勤怠一覧画面のURL

    //     // 期待される結果：その日になされた全ユーザーの勤怠情報が表示されている
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance1->start_time);
    //     $response->assertSee($attendance1->end_time);
    //     $response->assertSee($attendance1->remarks);
    //     $response->assertSee($attendance2->start_time);
    //     $response->assertSee($attendance2->end_time);
    //     $response->assertSee($attendance2->remarks);
    // }

    // 遷移した際に現在の日付が表示される
    // public function test_current_date_is_displayed_on_attendance_list_page()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);  // 管理者ユーザー
    //     $this->actingAs($admin);

    //     // 2. 勤怠一覧画面を開く
    //     $response = $this->get(route('attendance.index'));  // 勤怠一覧画面のURL

    //     // 期待される結果：現在の日付が表示されていること
    //     $response->assertStatus(200);
    //     $response->assertSee(now()->format('Y/m/d'));  // 今日の日付（例: 2025/02/20）が表示されていることを確認
    // }

    // 「前日」を押下した時に前の日の勤怠情報が表示される
    // public function test_previous_day_attendance_info_is_displayed()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠情報を作成（前日）
    //     $previousDate = Carbon::yesterday()->format('Y-m-d');
    //     $user = User::factory()->create();
        
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => $previousDate,
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 勤怠一覧画面を開く
    //     $response = $this->get(route('attendance.index')); // 勤怠一覧画面のURL
        
    //     // 4. 「前日」ボタンを押したときの動作を確認
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->start_time);
    //     $response->assertSee($attendance->end_time);
    //     $response->assertSee($attendance->remarks);
    //     $response->assertSee('前日'); // 前日ボタンの表示確認
    // }

    // 「翌日」を押下した時に次の日の勤怠情報が表示される
    // public function test_next_day_attendance_info_is_displayed()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠情報を作成（翌日）
    //     $nextDay = Carbon::tomorrow()->format('Y-m-d'); // 翌日の日付を取得
    //     $user = User::factory()->create();  // 一般ユーザー

    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => $nextDay,
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks for next day',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 勤怠一覧画面を開く
    //     $response = $this->get(route('attendance.index'));

    //     // 4. 翌日ボタンを押す
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->start_time);  // 翌日の出勤時間が表示されているか確認
    //     $response->assertSee($attendance->end_time);  // 翌日の退勤時間が表示されているか確認
    //     $response->assertSee($attendance->remarks);   // 翌日の備考が表示されているか確認
    // }
}
