<?php

// tests/Feature/LoginTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
  use RefreshDatabase;

  // メールアドレスが未入力の場合、バリデーションメッセージが表示される
  public function testEmailIsRequiredForLogin()
  {
    // 1. ユーザーを登録する
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    // 2. メールアドレス以外のユーザー情報を入力する
    $data = [
        'login_identifier' => '', // メールアドレスを空に設定
        'password' => 'password123',
    ];

    // 3. ログインの処理を行う
    $response = $this->post(route('login'), $data);

    // バリデーションエラーメッセージが存在することを確認
    $response->assertSessionHasErrors('login_identifier');
  }

  // パスワードが未入力の場合、バリデーションメッセージが表示される
  public function testPasswordIsRequiredForLogin()
  {
    // 1. ユーザーを登録する
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),  // ユーザー登録時にはパスワードを設定
    ]);

    // 2. パスワード以外のユーザー情報を入力する
    $data = [
        'login_identifier' => $user->email,  // メールアドレスは入力
        'password' => '',  // パスワードは空にする
    ];

    // 3. ログインの処理を行う
    $response = $this->post(route('login'), $data);

    // 4. バリデーションエラーメッセージが表示されることを確認
    $response->assertSessionHasErrors('password');  // パスワードのバリデーションエラーを確認
  }

  // 登録内容と一致しない場合、バリデーションメッセージが表示される
  public function testLoginFailsWithIncorrectEmail()
  {
    // 1. ユーザーを登録する
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),  // ユーザー登録時にはパスワードを設定
    ]);

    // 2. 誤ったメールアドレスのユーザー情報を入力する
    $data = [
        'login_identifier' => 'wrong@example.com',  // 誤ったメールアドレス
        'password' => 'password123',  // 正しいパスワード
    ];

    // 3. ログインの処理を行う
    $response = $this->post(route('login'), $data);

    // 4. バリデーションエラーメッセージが表示されることを確認
    $response->assertSessionHasErrors('login_identifier');  // メールアドレス（login_identifier）にエラーが表示されることを確認
  }
}
