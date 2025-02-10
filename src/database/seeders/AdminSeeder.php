<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',  // 管理者のメールアドレス
            'password' => Hash::make('password123'),  // 管理者のパスワード（ハッシュ化）
            'role' => 'admin',  // 役割を「admin」として設定
        ]);
    }
}
