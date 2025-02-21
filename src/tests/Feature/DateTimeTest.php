<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Date;
use App\Models\User; // Userモデルをインポート
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DateTimeTest extends TestCase
{
  use RefreshDatabase;

  /**
   * 現在の日時情報がUIと同じ形式で出力されているかのテスト
   *
   * @return void
   */

  // 勤務外の場合、勤怠ステータスが正しく表示される
  public function testAttendanceStatusDisplayedCorrectly()
  {
    // ステータスが勤務外のユーザーにログイン
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤怠情報を作成
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now(),
      'status' => '勤務外',  // 勤務外のステータス
    ]);

    // 勤怠打刻画面を開く
    $response = $this->actingAs($user)->get(route('attendance'));

    // ステータスが勤務外であることを確認
    $response->assertStatus(200)
            ->assertSee('勤務外');  // UIに勤務外と表示されているかを確認
  }

  // 勤務中の場合、勤怠ステータスが正しく表示される
  public function testAttendanceStatusDisplayedCorrectlyWhenWorking()
  {
    // ステータスが勤務中のユーザーにログイン
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤怠情報を作成（勤務中ステータス）
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now(),
      'status' => '出勤中',  // 勤務中のステータス
    ]);

    // 勤怠打刻画面を開く
    $response = $this->actingAs($user)->get(route('attendance'));

    // ステータスが「勤務中」であることを確認
    $response->assertStatus(200)
              ->assertSee('出勤中');  // UIに勤務中と表示されているかを確認
  }

  // 休憩中の場合、勤怠ステータスが正しく表示される
  public function testAttendanceStatusDisplayedCorrectlyWhenOnBreak()
  {
    // ステータスが休憩中のユーザーにログイン
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤怠情報を作成（休憩中ステータス）
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now(),
      'status' => '休憩中',  // 休憩中のステータス
    ]);

    // 勤怠打刻画面を開く
    $response = $this->actingAs($user)->get(route('attendance'));

    // ステータスが「休憩中」であることを確認
    $response->assertStatus(200)
              ->assertSee('休憩中');  // UIに休憩中と表示されているかを確認
  }

  // 退勤済の場合、勤怠ステータスが正しく表示される
  public function testAttendanceStatusDisplayedCorrectlyWhenOffWork()
  {
    // ステータスが退勤済のユーザーにログイン
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤怠情報を作成（退勤済ステータス）
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now(),
      'status' => '退勤済',  // 退勤済のステータス
    ]);

    // 勤怠打刻画面を開く
    $response = $this->actingAs($user)->get(route('attendance'));

    // ステータスが「退勤済」であることを確認
    $response->assertStatus(200)
              ->assertSee('退勤済');  // UIに退勤済と表示されているかを確認
  }

  // 休憩ボタンが正しく機能する
  public function testBreakButtonWorksCorrectly()
  {
    // ステータスが勤務中のユーザーを作成
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤務中の出勤情報を作成
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now()->format('Y-m-d'),
      'status' => '出勤中',
      'start_time' => now()->format('H:i'),  // 出勤時刻を設定
    ]);

    // ユーザーにログイン
    $this->actingAs($user);

    // 勤務中の場合、「休憩入」ボタンが表示されていることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩入');  // 休憩入ボタンが表示されていることを確認

    // 休憩入の処理を行う
    $this->post(route('start-rest'));  // 休憩入ボタンをクリック（POSTリクエスト）

    // ステータスが「休憩中」に変更されたことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('休憩中', $attendance->status);  // ステータスが「休憩中」になっていることを確認

    // 休憩後、ページに「休憩中」が表示されていることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩中');  // ステータスが「休憩中」に変更されていることを確認
  }

  // 休憩は一日に何回でもできる
  public function testMultipleBreaksInOneDay()
  {
    // ステータスが出勤中のユーザーを作成
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤務中の出勤情報を作成
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now()->format('Y-m-d'),
      'status' => '出勤中',
      'start_time' => now()->format('H:i'),  // 出勤時刻を設定
    ]);

    // ユーザーにログイン
    $this->actingAs($user);

    // 出勤中の場合、「休憩入」ボタンが表示されていることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩入');  // 休憩入ボタンが表示されていることを確認

    // 休憩入の処理を行う
    $this->post(route('start-rest'));  // 休憩入ボタンをクリック（POSTリクエスト）

    // ステータスが「休憩中」に変更されたことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('休憩中', $attendance->status);  // ステータスが「休憩中」になっていることを確認

    // 休憩から戻る処理を行う
    $this->post(route('end-rest'));  // 休憩戻ボタンをクリック（POSTリクエスト）

    // ステータスが「出勤中」に戻ったことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('出勤中', $attendance->status);  // ステータスが「出勤中」に戻ったことを確認

    // 休憩戻後、再度「休憩入」ボタンが表示されていることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩入');  // 再度休憩入ボタンが表示されていることを確認
  }

  // 出勤時刻が管理画面で確認できる休憩戻ボタンが正しく機能する
  public function testBreakReturnButtonFunctionality()
  {
    // ステータスが出勤中のユーザーを作成
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤務中の出勤情報を作成
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now()->format('Y-m-d'),
      'status' => '出勤中',
      'start_time' => now()->format('H:i'),  // 出勤時刻を設定
    ]);

    // ユーザーにログイン
    $this->actingAs($user);

    // 出勤中の場合、「休憩入」ボタンが表示されていることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩入');  // 休憩入ボタンが表示されていることを確認

    // 休憩入の処理を行う
    $this->post(route('start-rest'));  // 休憩入ボタンをクリック（POSTリクエスト）

    // ステータスが「休憩中」に変更されたことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('休憩中', $attendance->status);  // ステータスが「休憩中」になっていることを確認

    // 休憩戻ボタンが表示されることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩戻');  // 休憩戻ボタンが表示されていることを確認

    // 休憩戻の処理を行う
    $this->post(route('end-rest'));  // 休憩戻ボタンをクリック（POSTリクエスト）

    // ステータスが「出勤中」に戻ったことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('出勤中', $attendance->status);  // ステータスが「出勤中」に戻ったことを確認
  }

  // 休憩戻は一日に何回でもできる
  public function testBreakReturnCanBePerformedMultipleTimesInOneDay()
  {
    // ステータスが出勤中のユーザーを作成
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 勤務中の出勤情報を作成
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now()->format('Y-m-d'),
      'status' => '出勤中',
      'start_time' => now()->format('H:i'),  // 出勤時刻を設定
    ]);

    // ユーザーにログイン
    $this->actingAs($user);

    // 出勤中の場合、「休憩入」ボタンが表示されることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩入');  // 休憩入ボタンが表示されていることを確認

    // 休憩入の処理を行う
    $this->post(route('start-rest'));  // 休憩入ボタンをクリック（POSTリクエスト）

    // ステータスが「休憩中」に変更されたことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('休憩中', $attendance->status);  // ステータスが「休憩中」になっていることを確認

    // 休憩戻ボタンが表示されることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩戻');  // 休憩戻ボタンが表示されていることを確認

    // 休憩戻の処理を行う
    $this->post(route('end-rest'));  // 休憩戻ボタンをクリック（POSTリクエスト）

    // ステータスが「出勤中」に戻ったことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('出勤中', $attendance->status);  // ステータスが「出勤中」に戻ったことを確認

    // 再度、休憩入の処理を行う
    $this->post(route('start-rest'));  // 再度、休憩入ボタンをクリック（POSTリクエスト）

    // ステータスが再び「休憩中」に変更されたことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);
    $this->assertEquals('休憩中', $attendance->status);  // ステータスが「休憩中」に戻ったことを確認

    // 再度、休憩戻ボタンが表示されることを確認
    $response = $this->get(route('attendance'));
    $response->assertStatus(200);
    $response->assertSee('休憩戻');  // 再度、休憩戻ボタンが表示されていることを確認
  }

  // 退勤ボタンが正しく機能する
  public function testEndWorkButtonWorksCorrectly()
  {
    // ステータスが勤務中のユーザーを作成
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 出勤情報を作成
    $attendance = Attendance::create([
      'user_id' => $user->id,
      'date' => now()->format('Y-m-d'),
      'status' => '出勤中',  // 勤務中として設定
      'start_time' => now()->format('H:i'),  // 出勤時刻を設定
    ]);

    // ユーザーにログイン
    $this->actingAs($user);

    // 勤怠打刻画面を開く
    $response = $this->get(route('attendance'));  // 勤怠打刻ページを取得
    $response->assertStatus(200);  // 画面が正常に表示されていることを確認

    // 「退勤」ボタンが表示されていることを確認
    $response->assertSee('退勤');  // ボタンが画面に表示されていることを確認

    // 退勤処理を行う
    $response = $this->post(route('end-work'));  // 退勤ボタンをクリック（POSTリクエスト）

    // ステータスが「退勤済」に変更されたことを確認
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', now()->format('Y-m-d'))
                            ->first();

    $this->assertNotNull($attendance);  // 勤怠情報が存在することを確認
    $this->assertEquals('退勤済', $attendance->status);  // ステータスが「退勤済」になっていることを確認

    // 退勤後に再度画面を確認
    $response = $this->get(route('attendance'));  // 再度勤怠打刻ページを取得
    $response->assertSee('退勤済');  // ステータスが「退勤済」になっていることを確認
  }
}