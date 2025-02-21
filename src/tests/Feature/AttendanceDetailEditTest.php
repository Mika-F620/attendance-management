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
}