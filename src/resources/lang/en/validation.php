<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'email' => ':attributeは正しいメールアドレス形式で入力してください。',
    'required' => ':attributeを入力してください。',
    'min' => [
        'string' => ':attributeは:min文字以上で入力してください。',
    ],
    'confirmed' => ':attributeと一致しません。',
    'max' => [
        'string' => ':attributeは:max文字以内で入力してください。',
    ],
    'email' => ':attributeは正しいメールアドレス形式で入力してください。',
    'unique' => ':attributeは既に使用されています。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'login_identifier' => [
        'required' => 'ユーザー名またはメールアドレスを入力してください。',
        'string' => '入力が無効です。',
        'max' => '255文字以内で入力してください。',
        ],
        'password' => [
            'required' => 'パスワードを入力してください。',
            'min' => 'パスワードは8文字以上で入力してください。',
        ],
        'password_confirmation' => [
            'confirmed' => 'パスワードと一致しません。',
        ],
        'attributes' => [
            'email' => 'メールアドレス',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'お名前',
        'email' => 'メールアドレス',
        'login_identifier' => 'ユーザー名またはメールアドレス', // 追加
        'password' => 'パスワード',
        'password_confirmation' => '確認用パスワード',
    ],
];
