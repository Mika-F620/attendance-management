<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    // 勤怠詳細画面の「名前」がログインユーザーの氏名になっている
    public function testAttendanceDetailPageShowsLoggedInUserName()
    {
      // ログインしたユーザーを作成
      $user = User::factory()->create();
      $this->actingAs($user);

      // 勤怠情報を作成
      $attendance = Attendance::create([
          'user_id' => $user->id,
          'date' => now()->format('Y-m-d'),
          'status' => '出勤中',
      ]);

      // 勤怠詳細ページにアクセス
      $response = $this->get(route('attendance.show', ['id' => $attendance->id])); // idパラメータを渡す

      // ステータスコードが200であり、ログインユーザーの名前が表示されているか確認
      $response->assertStatus(200);
      $response->assertSee($user->name); // ユーザー名が表示されていることを確認
    }

    // 「出勤・退勤」にて記されている時間がログインユーザーの打刻と一致している
    public function testAttendanceDetailPageShowsCorrectWorkTimes()
    {
      // ユーザーを作成してログイン
      $user = User::factory()->create();
      $this->actingAs($user);

      // 勤怠情報を作成
      $startTime = '09:00';
      $endTime = '18:00';
      $attendance = Attendance::create([
          'user_id' => $user->id,
          'date' => now(),
          'start_time' => $startTime,  // 出勤時間
          'end_time' => $endTime,      // 退勤時間
          'status' => '出勤中',
      ]);

      // 勤怠詳細ページにアクセス
      $response = $this->get(route('attendance.show', ['id' => $attendance->id]));

      // ステータスコードが200であり、出勤・退勤時間が正しいか確認
      $response->assertStatus(200);
      $response->assertSee($startTime);  // 出勤時間が表示されていることを確認
      $response->assertSee($endTime);    // 退勤時間が表示されていることを確認
    }

    // 「休憩」にて記されている時間がログインユーザーの打刻と一致している
    public function testAttendanceDetailPageShowsCorrectBreakTime()
    {
      // ユーザーを作成してログイン
      $user = User::factory()->create();
      $this->actingAs($user);

      // 勤怠情報を作成（休憩時間を含む）
      $startTime = '09:00';
      $endTime = '18:00';
      $breakStartTime = '12:00';
      $breakEndTime = '12:30';
      $attendance = Attendance::create([
          'user_id' => $user->id,
          'date' => now(),
          'start_time' => $startTime,  // 出勤時間
          'end_time' => $endTime,      // 退勤時間
          'break_start_time' => $breakStartTime,  // 休憩開始時間
          'break_end_time' => $breakEndTime,      // 休憩終了時間
          'status' => '出勤中',
      ]);

      // 勤怠詳細ページにアクセス
      $response = $this->get(route('attendance.show', ['id' => $attendance->id]));

      // ステータスコードが200であり、休憩時間が正しいか確認
      $response->assertStatus(200);
      $response->assertSee($breakStartTime);  // 休憩開始時間が表示されていることを確認
      $response->assertSee($breakEndTime);    // 休憩終了時間が表示されていることを確認
    }
}