<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize()
    {
        // 管理者用なので、常にtrue
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|max:255',  // メール形式を必須に
            'password' => 'required|string|min:8',  // パスワードは8文字以上
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
        ];
    }
}
