<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;  // Carbonをインポートして日付操作を行います

class UserSeeder extends Seeder
{
    public function run()
    {
        // 管理者ユーザーの作成
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',  // 役職を「admin」として設定
            'email_verified_at' => Carbon::now(),  // 現在の日付をメール認証日として設定
        ]);

        // 一般ユーザーの作成
        User::create([
            'name' => '田中 太郎',
            'email' => 'tanaka.taro@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',  // 役職を「user」として設定
            'email_verified_at' => Carbon::now(),  // 現在の日付をメール認証日として設定
        ]);

        // 他のユーザーを追加する場合、同様に繰り返します
        User::create([
            'name' => '山田 花子',
            'email' => 'yamada.hanako@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => Carbon::now(),  // 現在の日付をメール認証日として設定
        ]);
    }
}
