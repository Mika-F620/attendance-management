<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginTest extends TestCase
{
  use RefreshDatabase;

  // メールアドレスが未入力の場合、バリデーションメッセージが表示される
  public function test_admin_login_fails_with_empty_email()
  {
    // 1. ユーザーを登録する
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password1234'),
        'role' => 'admin',
    ]);

    // 2. メールアドレス以外のユーザー情報を入力する
    $data = [
        'password' => 'password1234',  // メールアドレスを空にする
    ];

    // 3. ログインの処理を行う
    $response = $this->post(route('admin.login'), $data);

    // 4. バリデーションエラーメッセージが表示されることを確認
    $response->assertSessionHasErrors('email');  // エラーがセッションに含まれていることを確認
  }

  // パスワードが未入力の場合、バリデーションメッセージが表示される
  public function test_admin_login_fails_with_empty_password()
  {
    // 1. ユーザーを登録する
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password1234'),
        'role' => 'admin',
    ]);

    // 2. パスワード以外のユーザー情報を入力する
    $data = [
        'email' => 'admin@example.com',  // メールアドレスは正しいものを入力
    ];

    // 3. ログインの処理を行う
    $response = $this->post(route('admin.login'), $data);

    // 4. バリデーションエラーメッセージが表示されることを確認
    $response->assertSessionHasErrors('password');  // パスワードに関するエラーメッセージが表示されることを確認
  }

  // 登録内容と一致しない場合、バリデーションメッセージが表示される
  // public function test_admin_login_fails_with_incorrect_email()
  //   {
  //       // 1. ユーザーを登録する
  //       $user = User::create([
  //           'name' => 'Test Admin',
  //           'email' => 'admin@example.com',
  //           'password' => bcrypt('password123'),
  //           'role' => 'admin'
  //       ]);

  //       // 2. 誤ったメールアドレスのユーザー情報を入力する
  //       $data = [
  //           'email' => 'incorrect@example.com', // 正しいメールアドレスではなく、誤ったものを指定
  //           'password' => 'password123'
  //       ];

  //       // 3. ログインの処理を行う
  //       $response = $this->post(route('admin.login'), $data);

  //       // 4. バリデーションエラーメッセージが表示されることを確認
  //       $response->assertSessionHasErrors('email');  // 'email'フィールドにエラーが表示されることを確認
  //   }
}
