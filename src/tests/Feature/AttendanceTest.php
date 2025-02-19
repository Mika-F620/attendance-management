<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    // 自分が行った勤怠情報が全て表示されている
    // public function testAttendanceInfoIsVisibleForUser()
    // {
    //   // ユーザーを作成
    //   $user = User::factory()->create();

    //   // 勤怠情報を作成
    //   $attendance1 = Attendance::create([
    //       'user_id' => $user->id,
    //       'date' => Carbon::now()->format('Y-m-d'), // データベース用フォーマット
    //       'status' => '出勤中',
    //       'start_time' => Carbon::now()->format('H:i'),
    //   ]);

    //   $attendance2 = Attendance::create([
    //       'user_id' => $user->id,
    //       'date' => Carbon::now()->addDay()->format('Y-m-d'), // データベース用フォーマット
    //       'status' => '出勤中',
    //       'start_time' => Carbon::now()->addDay()->format('H:i'),
    //   ]);

    //   // ユーザーにログイン
    //   $this->actingAs($user);

    //   // 勤怠一覧ページを開く
    //   $response = $this->get(route('attendance.list'));

    //   // **ビューのフォーマットに合わせて比較**
    //   $this->assertEquals(Carbon::now()->format('m/d (D)'), $attendance1->dateFormatted);
    //   $this->assertEquals(Carbon::now()->addDay()->format('m/d (D)'), $attendance2->dateFormatted);

    //   // 勤怠情報がすべて表示されていることを確認
    //   $response->assertStatus(200);
    //   $response->assertSee(Carbon::now()->format('m/d (D)'));  // 02/19 (Wed) の形式で確認
    //   $response->assertSee(Carbon::now()->addDay()->format('m/d (D)'));  // 02/20 (Thu) の形式で確認
    // }

    // 勤怠一覧画面に遷移した際に現在の月が表示される
    public function testCurrentMonthIsDisplayedOnAttendanceListPage()
    {
      // ユーザーを作成しログイン
      $user = User::factory()->create();
      $this->actingAs($user);

      // 勤怠一覧ページを開く
      $response = $this->get(route('attendance.list'));

      // 現在の月を取得して、表示されているか確認
      $currentMonth = now()->format('Y/m'); // 例: '2025/02'
      
      // ページ内に現在の月が表示されていることを確認
      $response->assertStatus(200);  // ステータスコード 200 を確認
      $response->assertSee($currentMonth);  // 現在の月が表示されていることを確認
    }

    // 「前月」を押下した時に表示月の前月の情報が表示される
    // public function testPreviousMonthButtonShowsPreviousMonthInfo()
    // {
    //     // ユーザーを作成してログイン
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     // 今月の勤怠情報を登録
    //     $attendance1 = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'status' => '出勤中',
    //     ]);

    //     // 前月の勤怠情報を登録
    //     $attendance2 = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->subMonth()->format('Y-m-d'),
    //         'status' => '退勤済',
    //     ]);

    //     // 勤怠一覧ページを開く
    //     $response = $this->get(route('attendance.list'));

    //     // 現在月の勤怠情報が表示されていることを確認
    //     $response->assertStatus(200);
    //     $response->assertSee(now()->format('Y/m'));  // 現在の月（例：2025/02）
    //     $response->assertSee(now()->format('m/d (D)'));   // 現在月の日付

    //     // 「前月」ボタンを押す
    //     $response = $this->get(route('attendance.list', ['month' => now()->subMonth()->format('Y-m')]));

    //     // 前月の勤怠情報が表示されていることを確認
    //     $response->assertStatus(200);
    //     $response->assertSee(now()->subMonth()->format('Y/m'));  // 前月（例：2025/01）
    //     $response->assertSee(now()->subMonth()->format('m/d (D)'));   // 前月の日付
    // }

    // 「翌月」を押下した時に表示月の前月の情報が表示される
    public function testNextMonthButtonShowsNextMonthInfo()
    {
      // ユーザーを作成してログイン
      $user = User::factory()->create();
      $this->actingAs($user);

      // 今月の勤怠情報を登録
      $attendance1 = Attendance::create([
          'user_id' => $user->id,
          'date' => now()->format('Y-m-d'),
          'status' => '出勤中',
      ]);

      // 翌月の勤怠情報を登録
      $attendance2 = Attendance::create([
          'user_id' => $user->id,
          'date' => now()->addMonth()->format('Y-m-d'),
          'status' => '退勤済',
      ]);

      // 勤怠一覧ページを開く
      $response = $this->get(route('attendance.list'));

      // 現在月の勤怠情報が表示されていることを確認
      $response->assertStatus(200);
      $response->assertSee(now()->format('Y/m'));  // 現在の月（例：2025/02）

      // 「翌月」ボタンを押す
      $response = $this->get(route('attendance.list', ['month' => now()->addMonth()->format('Y-m')]));

      // 翌月の勤怠情報が表示されていることを確認
      $response->assertStatus(200);
      $response->assertSee(now()->addMonth()->format('Y/m'));  // 翌月（例：2025/03）
      // 日付を修正（例えば、表示形式が '03/19' の場合）
      $response->assertSee(now()->addMonth()->format('m/d'));  // 翌月の日付
    }

    // 「詳細」を押下すると、その日の勤怠詳細画面に遷移する
    // public function testAttendanceDetailPageIsAccessible()
    // {
    //     // ユーザーを作成してログイン
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     // 勤怠情報を作成
    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'date' => now()->format('Y-m-d'),
    //         'status' => '出勤中',
    //     ]);

    //     // 勤怠一覧ページを開く
    //     $response = $this->get(route('attendance.list'));

    //     // 現在月の勤怠情報が表示されていることを確認
    //     $response->assertStatus(200);
    //     $response->assertSee(now()->format('Y/m')); // 現在の月（例：2025/02）

    //     // 日付フォーマットを調整
    //     $formattedDate = Carbon::parse($attendance->date)->format('m/d (D)'); // Carbonを使って日付フォーマット
    //     $response->assertSee($formattedDate); // フォーマットを合わせた日付

    //     // 詳細ボタンをクリックする
    //     $response = $this->get(route('attendance.show', ['attendance' => $attendance->id]));

    //     // 勤怠詳細ページに遷移したかを確認
    //     $response->assertStatus(200);
    //     $response->assertSee($formattedDate); // 詳細ページに日付が表示されていること
    //     $response->assertSee($attendance->status); // ステータスも表示されていること
    // }
}
