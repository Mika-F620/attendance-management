<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'start_time', 'status', 'end_time'
    ];

    // もしタイムスタンプを使用しない場合
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
