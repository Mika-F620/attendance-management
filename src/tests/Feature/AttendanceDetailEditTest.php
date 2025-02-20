<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceDetailEditTest extends TestCase
{
    use RefreshDatabase;

    // 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
    public function testErrorMessageIsDisplayedWhenStartTimeIsAfterEndTime()
    {
      // ユーザーを作成してログイン
      $user = User::factory()->create();
      $this->actingAs($user);

      // 勤怠情報を作成（不適切な出勤時間と退勤時間）
      $attendance = Attendance::create([
          'user_id' => $user->id,
          'date' => now(),
          'start_time' => '09:00',  // 出勤時間
          'end_time' => '08:00',    // 退勤時間（出勤時間より前）
          'status' => '出勤中',
      ]);

      // 勤怠詳細ページにアクセス
      $response = $this->get(route('attendance.show', ['id' => $attendance->id]));

      // 出勤時間を退勤時間より後に修正
      $response = $this->put(route('attendance.update', ['id' => $attendance->id]), [
          'start_time' => '18:00',
          'end_time' => '09:00', // 出勤時間が退勤時間より後
      ]);

      // リダイレクト後にエラーメッセージがセッションに含まれていることを確認
      $response->assertRedirect();
      $response->assertSessionHasErrors('start_time');
      $response->assertSessionHasErrors('end_time');
      $response->assertSessionHas('errors'); // エラーがセッションに含まれていることを確認
    }

    // 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示される
    // public function test_error_message_is_displayed_when_break_start_time_is_after_end_time()
    // {
    //   // テスト用ユーザーを作成してログイン
    //   $user = User::factory()->create();
    //   $attendance = Attendance::factory()->create(['user_id' => $user->id]);

    //   // ログイン
    //   $this->actingAs($user);

    //   // 勤怠詳細ページを開く
    //   $response = $this->get(route('attendance.show', ['id' => $attendance->id]));
    //   $response->assertStatus(200);

    //   // 休憩開始時間を退勤時間より後に設定
    //   $response = $this->put(route('attendance.update', ['id' => $attendance->id]), [
    //       'start_time' => '08:00',
    //       'end_time' => '17:00',
    //       'break_start_time' => '18:00', // 休憩開始時間が退勤時間より後
    //       'break_end_time' => '18:30',
    //       'remarks' => 'Test remarks'
    //   ]);

    //   // バリデーションエラーメッセージが表示されることを確認
    //   $response->assertSessionHasErrors(['break_start_time']);
    //   $response->assertSee('出勤時間もしくは退勤時間が不適切な値です');
    // }

    // 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
    // public function testErrorMessageWhenBreakEndTimeIsAfterEndTime()
    // {
    //     // 1. 勤怠情報が登録されたユーザーにログイン
    //     $user = User::factory()->create();
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //     ]);
        
    //     // ログイン
    //     $this->actingAs($user);

    //     // 2. 勤怠詳細ページを開く
    //     $response = $this->get(route('attendance.show', ['attendance' => $attendance->id])); // idを渡す

    //     // 3. 休憩終了時間を退勤時間より後に設定
    //     $response = $this->from(route('attendance.show', ['attendance' => $attendance->id]))  // idを渡す
    //                      ->put(route('attendance.update', ['attendance' => $attendance->id]), [ // idを渡す
    //                          'start_time' => '09:00',
    //                          'end_time' => '18:00',
    //                          'break_start_time' => '12:00',
    //                          'break_end_time' => '19:00', // 退勤時間より後の休憩終了時間
    //                      ]);

    //     // 4. 保存処理をする
    //     $response->assertSessionHasErrors('end_time'); // 退勤時間が不適切な場合のエラー確認
    //     $response->assertSee('出勤時間もしくは退勤時間が不適切な値です'); // エラーメッセージが表示されることを確認
    // }

    // 備考欄が未入力の場合のエラーメッセージが表示される
    // public function testErrorMessageWhenRemarksAreEmpty()
    // {
    //     // 1. 勤怠情報が登録されたユーザーにログイン
    //     $user = User::factory()->create();
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks', // 初期の備考
    //     ]);
        
    //     // ログイン
    //     $this->actingAs($user);

    //     // 2. 勤怠詳細ページを開く
    //     $response = $this->get(route('attendance.show', ['attendance' => $attendance->id]));

    //     // 3. 備考欄を未入力のまま保存処理をする
    //     $response = $this->from(route('attendance.show', ['attendance' => $attendance->id]))  // 戻る先
    //                      ->put(route('attendance.update', ['attendance' => $attendance->id]), [ // 更新処理
    //                          'start_time' => '09:00',
    //                          'end_time' => '18:00',
    //                          'break_start_time' => '12:00',
    //                          'break_end_time' => '13:00',
    //                          'remarks' => '',  // 備考欄を空にする
    //                      ]);

    //     // 4. 「備考を記入してください」というバリデーションメッセージが表示される
    //     $response->assertSessionHasErrors('remarks');  // 備考欄がエラーになる
    //     $response->assertSee('備考を記入してください');  // エラーメッセージが表示される
    // }

    // 修正申請処理が実行される
    // public function testCorrectionRequestIsExecuted()
    // {
    //     // 1. 勤怠情報が登録されたユーザーにログイン
    //     $user = User::factory()->create();
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks',
    //     ]);
        
    //     // ログイン
    //     $this->actingAs($user);

    //     // 2. 勤怠詳細を修正し保存処理をする
    //     $response = $this->put(route('attendance.update', ['attendance' => $attendance->id]), [
    //         'start_time' => '10:00',  // 修正した時間
    //         'end_time' => '19:00',    // 修正した時間
    //         'break_start_time' => '12:30', // 修正した休憩時間
    //         'break_end_time' => '13:30',   // 修正した休憩時間
    //         'remarks' => 'Updated remarks',
    //     ]);
        
    //     // 期待する修正が保存されているか確認
    //     $response->assertRedirect(route('attendance.show', ['attendance' => $attendance->id])); // リダイレクトを確認
    //     $attendance->refresh(); // DBから最新のデータを取得

    //     // 修正内容が反映されているか確認
    //     $this->assertEquals('10:00', $attendance->start_time);
    //     $this->assertEquals('19:00', $attendance->end_time);
    //     $this->assertEquals('12:30', $attendance->break_start_time);
    //     $this->assertEquals('13:30', $attendance->break_end_time);
    //     $this->assertEquals('Updated remarks', $attendance->remarks);

    //     // 3. 管理者ユーザーで承認画面と申請一覧画面を確認
    //     $admin = User::factory()->create(['is_admin' => true]); // 管理者ユーザー作成
    //     $this->actingAs($admin);

    //     // 管理者が申請一覧を確認
    //     $response = $this->get(route('admin.attendance.requests'));
    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->date); // 修正された勤怠情報が申請一覧に表示されることを確認

    //     // 管理者が承認画面を確認
    //     $response = $this->get(route('admin.attendance.approval', ['attendance' => $attendance->id]));
    //     $response->assertStatus(200);
    //     $response->assertSee('Updated remarks'); // 承認画面に修正内容が表示されていることを確認
    // }

    // 「承認待ち」にログインユーザーが行った申請が全て表示されていること
    // public function testCorrectionRequestIsVisibleForLoggedInUser()
    // {
    //     // 1. 勤怠情報が登録されたユーザーにログインをする
    //     $user = User::factory()->create();
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks',
    //         'status' => 'approved', // 初期状態は承認済み
    //     ]);

    //     // ログイン
    //     $this->actingAs($user);

    //     // 2. 勤怠詳細を修正し保存処理をする
    //     // 勤怠の修正をして「承認待ち」の状態に変更
    //     $response = $this->put(route('attendance.update', ['attendance' => $attendance->id]), [
    //         'start_time' => '10:00',
    //         'end_time' => '19:00',
    //         'break_start_time' => '12:30',
    //         'break_end_time' => '13:30',
    //         'remarks' => 'Updated remarks',
    //         'status' => 'pending_approval', // 申請中に変更
    //     ]);

    //     $attendance->refresh(); // DBから最新のデータを取得

    //     // 申請内容が正しく保存されているか確認
    //     $this->assertEquals('pending_approval', $attendance->status);

    //     // 3. 申請一覧画面を確認する
    //     // 自分の申請が申請一覧に表示されることを確認
    //     $response = $this->get(route('attendance.requests')); // 申請一覧画面にアクセス

    //     $response->assertStatus(200);
    //     $response->assertSee($attendance->date); // 日付が表示されることを確認
    //     $response->assertSee('Updated remarks'); // 修正された備考が表示されることを確認
    // }

    // 「承認済み」に管理者が承認した修正申請が全て表示されている
//     public function test_correction_request_is_visible_for_admin_when_approved()
// {
//     // 1. ユーザーでログイン
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     // 2. 勤怠詳細を修正して保存
//     $attendance = Attendance::create([
//         'user_id' => $user->id,
//         'date' => now()->format('Y-m-d'),
//         'start_time' => '09:00',
//         'end_time' => '18:00',
//         'break_start_time' => '12:00',
//         'break_end_time' => '13:00',
//         'remarks' => 'Test remarks',
//         'status' => '勤務外',  // `status` の適切な値
//         'approval_status' => '承認待ち',  // `approval_status` の適切な値
//     ]);

//     // 3. 申請一覧画面にアクセス
//     $admin = User::factory()->create(['is_admin' => true]); // 管理者ユーザー
//     $this->actingAs($admin);

//     // 管理者が承認する
//     $attendance->approval_status = '承認済み'; // `approval_status` を承認済みに変更
//     $attendance->save();

//     // 4. 承認済みの申請が表示されていることを確認
//     $response = $this->get(route('attendance.approvals.index')); // 承認画面にアクセス
//     $response->assertStatus(200);
//     $response->assertSee('承認済み');  // 承認された申請が表示される
// }

    // 各申請の「詳細」を押下すると申請詳細画面に遷移する
    // public function test_correction_request_detail_navigation()
    // {
    //     // 1. ユーザーでログイン
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     // 2. 勤怠詳細を修正して保存
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'start_time' => '09:00',
    //         'end_time' => '18:00',
    //         'break_start_time' => '12:00',
    //         'break_end_time' => '13:00',
    //         'remarks' => 'Test remarks',
    //         'status' => '勤務外',
    //         'approval_status' => '承認待ち',
    //     ]);

    //     // 勤怠情報の修正
    //     $attendance->remarks = 'Updated remarks';
    //     $attendance->save();

    //     // 3. 申請一覧画面を開く
    //     $response = $this->get(route('attendance.approvals.index')); // 申請一覧画面にアクセス

    //     // 申請一覧画面が表示されることを確認
    //     $response->assertStatus(200);

    //     // 「詳細」ボタンをクリックする
    //     $response = $this->get(route('attendance.show', ['attendance' => $attendance->id]));

    //     // 4. 申請詳細画面に遷移することを確認
    //     $response->assertStatus(200);
    //     $response->assertSee('Updated remarks'); // 詳細画面に変更した備考が表示されていることを確認
    // }

}