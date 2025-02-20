<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceCorrectionAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // 承認待ちの修正申請が全て表示されている
    // public function pending_approval_correction_requests_are_displayed_for_admin()
    // {
    //     // 1. 管理者ユーザーでログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 修正申請のデータを作成
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
    //         'approval_status' => '承認待ち',  // 未承認の状態
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
    //         'approval_status' => '承認待ち',  // 未承認の状態
    //     ]);

    //     // 3. 修正申請一覧ページを開く
    //     $response = $this->get(route('attendance.correction.index'));  // 承認待ち修正申請一覧ページ

    //     // 期待される結果：全ユーザーの未承認の修正申請が表示される
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance1->remarks);  // 修正申請1が表示されていること
    //     $response->assertSee($attendance2->remarks);  // 修正申請2が表示されていること
    // }

    // 承認済みの修正申請が全て表示されている
    // public function test_approved_corrections_are_displayed_for_admin()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 修正申請情報を作成
    //     $user1 = User::factory()->create();  // 一般ユーザー1
    //     $user2 = User::factory()->create();  // 一般ユーザー2

    //     // 承認待ちの修正申請を作成
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

    //     // 承認済みの修正申請を作成
    //     $attendance2 = Attendance::create([
    //         'user_id' => $user2->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks 2',
    //         'status' => '出勤中',
    //         'approval_status' => '承認済み',  // 承認済みに設定
    //     ]);

    //     // 3. 承認済みのタブを開く
    //     $response = $this->get(route('attendance.correction.index')); // 承認申請一覧画面のURL

    //     // 期待される結果：承認済みの修正申請が表示される
    //     $response->assertStatus(200);
    //     $response->assertSee('承認済み');  // 承認済みの修正申請が表示されることを確認
    //     $response->assertSee($attendance2->remarks);  // 承認済みの修正申請が表示されることを確認
    //     $response->assertDontSee($attendance1->remarks);  // 承認待ちの修正申請は表示されないことを確認
    // }

    // 修正申請の詳細内容が正しく表示されている
    // public function test_correction_request_details_are_displayed_correctly_for_admin()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']); // 管理者ユーザー
    //     $this->actingAs($admin);

    //     // 2. 修正申請の詳細画面を開く
    //     $user = User::factory()->create();  // 一般ユーザー
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks for correction',
    //         'status' => '出勤中',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 3. 詳細画面にアクセス
    //     $response = $this->get(route('attendance.correction.show', $attendance->id));

    //     // 期待される結果：申請内容が正しく表示されている
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->date);
    //     $response->assertSee($attendance->start_time);
    //     $response->assertSee($attendance->end_time);
    //     $response->assertSee($attendance->remarks);
    // }

    // 修正申請の承認処理が正しく行われる
    // public function test_correction_request_is_approved_and_updated()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 修正申請を作成
    //     $user = User::factory()->create();
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

    //     // 3. 修正申請詳細画面で承認処理
    //     $response = $this->post(route('attendance.correction.approve', $attendance));

    //     // 期待される結果：修正申請が承認され、勤怠情報が更新されている
    //     $attendance->refresh();
    //     $this->assertEquals('承認済み', $attendance->status);
    //     $this->assertEquals('承認済み', $attendance->approval_status);

    //     // 承認後、修正申請詳細画面にリダイレクトされることを確認
    //     $response->assertRedirect(route('attendance.correction.show', $attendance));
    //     $response->assertSessionHas('success', '修正申請が承認されました');
    // }
}
