<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class RegisterTest extends TestCase
{
    use RefreshDatabase; // データベースをリフレッシュしてテストを行う

    /** @test */
    // 名前が未入力の場合、バリデーションメッセージが表示される
    public function it_shows_validation_error_when_name_is_empty()
    {
        // 名前以外の情報を入力してフォームを送信
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // バリデーションエラーメッセージが表示されていることを確認
        $response->assertSessionHasErrors('name');
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->has('name') && $errors->first('name') === 'お名前を入力してください。';
        });
    }

    /** @test */
    // メールアドレスが未入力の場合、バリデーションメッセージが表示される
    public function it_shows_validation_error_when_email_is_empty()
    {
        // メールアドレス以外の情報を入力してフォームを送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // バリデーションエラーメッセージが表示されていることを確認
        $response->assertSessionHasErrors('email');
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->has('email') && $errors->first('email') === 'メールアドレスを入力してください。';
        });
    }

    /** @test */
    // パスワードが8文字未満の場合、バリデーションメッセージが表示される
    public function it_shows_validation_error_when_password_is_less_than_8_characters()
    {
        // パスワードが8文字未満のデータでフォームを送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        // バリデーションエラーメッセージが表示されていることを確認
        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->has('password') && $errors->first('password') === 'パスワードは8文字以上で入力してください。';
        });
    }

    /** @test */
    // パスワードが一致しない場合、バリデーションメッセージが表示される
    public function it_shows_validation_error_when_passwords_do_not_match()
    {
        // パスワードと確認用パスワードが一致しないデータでフォームを送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456', // パスワードが一致しない
        ]);

        // バリデーションエラーメッセージが表示されていることを確認
        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->has('password') && $errors->first('password') === 'パスワードと一致しません。';
        });
    }

    // パスワードが未入力の場合、バリデーションメッセージが表示される
    public function testPasswordIsRequired()
    {
        // 1. パスワード以外のユーザー情報を入力する
        $data = [
            'name' => 'Test User', // 名前を入力
            'email' => 'test@example.com', // メールアドレスを入力
            'password' => '', // パスワードを空にする
            'password_confirmation' => '', // 確認用パスワードも空にする
        ];

        // 2. 会員登録の処理を行う
        $response = $this->post(route('register'), $data);

        // 期待されるバリデーションエラーメッセージが表示されることを確認
        $response->assertSessionHasErrors('password', 'パスワードを入力してください。');
    }

    // フォームに内容が入力されていた場合、データが正常に保存される
    public function testUserIsSavedInDatabase()
    {
        // 1. ユーザー情報を入力する
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',  // パスワード
            'password_confirmation' => 'password123',  // 確認用パスワード
        ];

        // 2. 会員登録の処理を行う
        $response = $this->post(route('register'), $data);

        // データベースにユーザーが保存されたことを確認
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 必要に応じて、passwordのハッシュ化を確認
        $user = \App\Models\User::where('email', 'test@example.com')->first();
        $this->assertNotEquals('password123', $user->password);  // ハッシュ化されていることを確認
    }
}