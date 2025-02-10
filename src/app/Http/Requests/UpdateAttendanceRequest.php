<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_time' => 'required|date_format:H:i|before:end_time', // 出勤時間は退勤時間より前であるべき
            'end_time' => 'required|date_format:H:i|after:start_time', // 退勤時間は出勤時間より後であるべき
            'break_start_time' => 'nullable|date_format:H:i|after:start_time|before:end_time', // 休憩開始時間は出勤時間後、退勤時間前
            'break_end_time' => 'nullable|date_format:H:i|after:break_start_time|before:end_time', // 休憩終了時間は休憩開始時間後、退勤時間前
            'remarks' => 'required|string|max:255', // 備考は必須項目として設定
        ];
    }

    /**
     * Custom error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 出勤・退勤時間のバリデーション
            'start_time.required' => '出勤時間は必須です。',
            'end_time.required' => '退勤時間は必須です。',
            'start_time.date_format' => '出勤時間の形式は HH:mm で入力してください。',
            'end_time.date_format' => '退勤時間の形式は HH:mm で入力してください。',
            'start_time.before' => '出勤時間は退勤時間より前でなければなりません。',
            'end_time.after' => '退勤時間は出勤時間より後でなければなりません。',
            'start_time.after' => '出勤時間もしくは退勤時間が不適切な値です。',
            'end_time.before' => '出勤時間もしくは退勤時間が不適切な値です。',
            
            // 休憩時間のバリデーション
            'break_start_time.date_format' => '休憩開始時間の形式は HH:mm で入力してください。',
            'break_end_time.date_format' => '休憩終了時間の形式は HH:mm で入力してください。',
            'break_start_time.after' => '休憩時間が勤務時間外です。',
            'break_end_time.before' => '休憩時間が勤務時間外です。',
            
            // 備考のバリデーション
            'remarks.required_with' => '備考を記入してください。',
        ];
    }

    /**
     * Get the custom attributes for validation errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'start_time' => '出勤時間',
            'end_time' => '退勤時間',
            'break_start_time' => '休憩開始時間',
            'break_end_time' => '休憩終了時間',
            'remarks' => '備考',
        ];
    }

    /**
     * リクエストを処理する前にデータの調整を行う
     */
    protected function prepareForValidation()
    {
        // 入力された値を半角にする
        $this->merge([
            'start_time' => mb_convert_kana($this->start_time, 'a'),
            'end_time' => mb_convert_kana($this->end_time, 'a'),
            'break_start_time' => mb_convert_kana($this->break_start_time, 'a'),
            'break_end_time' => mb_convert_kana($this->break_end_time, 'a'),
            'remarks' => mb_convert_kana($this->remarks, 'a', 'UTF-8')  // 備考も半角に変換
        ]);
    }
}
