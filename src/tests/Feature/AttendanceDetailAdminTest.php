<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceDetailAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // 勤怠詳細画面に表示されるデータが選択したものになっている
    // public function test_attendance_detail_shows_correct_data_for_admin()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠情報を作成
    //     $user = User::factory()->create();  // 一般ユーザー
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

    //     // 3. 勤怠詳細ページを開く
    //     $response = $this->get(route('attendance.show', ['attendance' => $attendance->id]));  // IDを渡す

    //     // 4. 勤怠詳細画面の内容が選択した情報と一致することを確認
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->start_time);   // 出勤時間が表示されているか確認
    //     $response->assertSee($attendance->end_time);     // 退勤時間が表示されているか確認
    //     $response->assertSee($attendance->remarks);      // 備考が表示されているか確認
    //     $response->assertSee($attendance->status);       // 勤務ステータスが表示されているか確認
    // }


    // 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
    // public function test_start_time_after_end_time_displays_error_message()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠詳細ページを開く
    //     $user = User::factory()->create();  // 一般ユーザー
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

    //     // 3. 出勤時間を退勤時間より後に設定
    //     $attendance->start_time = '19:00';  // 出勤時間が退勤時間より後に設定
    //     $attendance->end_time = '18:00';    // 退勤時間を変更せずに保持

    //     // 4. 保存処理をする（エラーメッセージを期待）
    //     $response = $this->post(route('attendance.update', ['attendance' => $attendance->id]), $attendance->toArray());

    //     // 期待する結果：出勤時間もしくは退勤時間が不適切な値であるためエラーメッセージが表示される
    //     $response->assertSessionHasErrors(['start_time']);  // 'start_time'フィールドにエラーがあることを確認
    //     $response->assertSessionHasErrors(['end_time']);    // 'end_time'フィールドにエラーがあることを確認
    // }

    // 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示される
    // public function test_break_start_time_after_end_time_displays_error_message()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠詳細ページを開く
    //     $user = User::factory()->create();  // 一般ユーザー
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

    //     // 3. 休憩開始時間を退勤時間より後に設定
    //     $attendance->break_start_time = '19:00';  // 休憩開始時間を退勤時間後に設定
    //     $attendance->break_end_time = '13:00';    // 休憩終了時間をそのままに

    //     // 4. 保存処理をする（エラーメッセージを期待）
    //     $response = $this->post(route('attendance.update', ['attendance' => $attendance->id]), $attendance->toArray());

    //     // 期待する結果：休憩開始時間が退勤時間より後になっているためエラーメッセージが表示される
    //     $response->assertSessionHasErrors(['break_start_time']);  // 'break_start_time'フィールドにエラーがあることを確認
    // }

    // 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
    // public function test_break_end_time_after_end_time_displays_error_message()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠詳細ページを開く
    //     $user = User::factory()->create();  // 一般ユーザー
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

    //     // 3. 休憩終了時間を退勤時間より後に設定
    //     $attendance->break_end_time = '19:00';  // 休憩終了時間を退勤時間後に設定

    //     // 4. 保存処理をする（エラーメッセージを期待）
    //     // 適切なIDパラメータを渡してURLを修正
    //     $response = $this->post(route('attendance.update', $attendance), $attendance->toArray());

    //     // 期待する結果：休憩終了時間が退勤時間より後になっているためエラーメッセージが表示される
    //     $response->assertSessionHasErrors(['break_end_time']);  // 'break_end_time'フィールドにエラーがあることを確認
    // }

    // 備考欄が未入力の場合のエラーメッセージが表示される
    // public function test_remarks_are_required_for_attendance_update()
    // {
    //     // 1. 管理者ユーザーにログイン
    //     $admin = User::factory()->create(['role' => 'admin']);
    //     $this->actingAs($admin);

    //     // 2. 勤怠詳細ページを開く
    //     $user = User::factory()->create();  // 一般ユーザー
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

    //     // 3. 備考欄を未入力のまま保存処理をする
    //     $attendance->remarks = '';  // 備考欄を空に設定

    //     // 4. 保存処理をする
    //     $response = $this->post(route('attendance.update', $attendance->id), $attendance->toArray());

    //     // 期待される結果：備考欄が空なので「備考を記入してください」のエラーメッセージが表示される
    //     $response->assertSessionHasErrors('remarks');  // 'remarks'フィールドにエラーがあることを確認
    // }

    
}
