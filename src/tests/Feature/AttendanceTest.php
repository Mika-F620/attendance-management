<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
  use RefreshDatabase;

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
}
