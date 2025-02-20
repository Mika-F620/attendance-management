<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInfoAdminViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // 管理者ユーザーが全一般ユーザーの「氏名」「メールアドレス」を確認できる
    // public function test_all_users_name_and_email_are_displayed_for_admin()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 一般ユーザーの作成
    //     $user1 = User::factory()->create();  // 一般ユーザー1
    //     $user2 = User::factory()->create();  // 一般ユーザー2

    //     // 3. スタッフ一覧ページを開く
    //     $response = $this->get(route('users.index'));  // ユーザー一覧ページのURL

    //     // 期待される結果：全ての一般ユーザーの氏名とメールアドレスが表示されている
    //     $response->assertStatus(200);
    //     $response->assertSee($user1->name); // ユーザー1の氏名が表示される
    //     $response->assertSee($user1->email); // ユーザー1のメールアドレスが表示される
    //     $response->assertSee($user2->name); // ユーザー2の氏名が表示される
    //     $response->assertSee($user2->email); // ユーザー2のメールアドレスが表示される
    // }

    // ユーザーの勤怠情報が正しく表示される
    // public function test_user_attendance_info_is_displayed_correctly_for_admin()
    // {
    //     // 1. 管理者ユーザーでログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 選択したユーザーを作成
    //     $user = User::factory()->create();  // 一般ユーザーを作成

    //     // そのユーザーの勤怠情報を作成
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 選択したユーザーの勤怠一覧ページを開く
    //     $response = $this->get(route('attendance.index', ['user' => $user->id]));  // 勤怠一覧のURL

    //     // 4. 勤怠情報が正確に表示されることを確認
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->start_time);
    //     $response->assertSee($attendance->end_time);
    //     $response->assertSee($attendance->remarks);
    //     $response->assertSee($attendance->status);
    //     $response->assertSee($attendance->approval_status);
    // }

    // 「前月」を押下した時に表示月の前月の情報が表示される
    // public function test_previous_month_attendance_info_is_displayed()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠情報を作成（前月）
    //     $previousMonth = now()->subMonth()->format('Y-m-d');
    //     $user = User::factory()->create();  // 一般ユーザーを作成
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => $previousMonth,
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 勤怠一覧ページを開く
    //     $response = $this->get(route('attendance.index'));

    //     // 期待される結果：前月の勤怠情報が表示されている
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->start_time);
    //     $response->assertSee($attendance->end_time);
    //     $response->assertSee($attendance->remarks);
    //     $response->assertSee($previousMonth); // 前月の日付が表示されていることを確認
    // }

    // 「翌月」を押下した時に表示月の前月の情報が表示される
    // public function test_next_month_attendance_info_is_displayed()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠情報を作成（翌月）
    //     $nextMonth = now()->addMonth()->format('Y-m-d');
    //     $user = User::factory()->create();
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => $nextMonth,
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Next month test',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 勤怠一覧画面を開く
    //     $response = $this->get(route('attendance.index')); // 勤怠一覧画面のURL

    //     // 4. 翌月の情報が表示されていることを確認
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->start_time);
    //     $response->assertSee($attendance->end_time);
    //     $response->assertSee($attendance->remarks);
    // }

    // 「詳細」を押下すると、その日の勤怠詳細画面に遷移する
    // public function user_can_see_attendance_detail_for_selected_day()
    // {
    //     // 1. 管理者ユーザーでログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠情報を作成
    //     $attendance = Attendance::create([
    //         'user_id' => $admin->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 勤怠一覧ページを開く
    //     $response = $this->get(route('attendance.index')); // ここでルート名を確認する

    //     // 4. 「詳細」ボタンを押下する
    //     $response->assertStatus(200);
    //     $response->assertSee('詳細'); // 詳細ボタンが表示されていることを確認

    //     // クリックした詳細ページに遷移するリンクを確認
    //     $response->assertSee(route('attendance.show', $attendance->id));

    //     // 5. 詳細画面が遷移したか確認
    //     $response = $this->get(route('attendance.show', $attendance->id));
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->start_time);  // 出勤時間が表示されているか
    //     $response->assertSee($attendance->end_time);    // 退勤時間が表示されているか
    // }
}
