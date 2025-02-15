<?php

// app/Models/StampCorrectionRequest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRequest extends Model
{
    use HasFactory;

    // fillable属性を設定して、マスアサインメントを許可する
    protected $fillable = [
        'user_id',
        'approval_status',
        'date',
        'reason', // 申請理由
    ];

    // 必要に応じて他の設定を追加
}
