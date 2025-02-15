<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'start_time', 'end_time', 'break_start_time', 'break_end_time', 'status', 'remarks'];

    // もしタイムスタンプを使用しない場合
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
