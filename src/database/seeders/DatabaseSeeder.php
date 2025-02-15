<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ユーザーと勤怠データをシーディング
        $this->call([
            UserSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
