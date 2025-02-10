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
            'date_year' => 'required|string',
            'date_day' => 'required|string',
            'start_time' => 'required|date_format:H:i',  // 出勤時間は必須
            'end_time' => 'required|date_format:H:i|after:start_time',  // 退勤時間は必須で、出勤時間より後でなければならない
            'break_start_time' => 'nullable|date_format:H:i|before:break_end_time',  // 休憩開始時間（任意）
            'break_end_time' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string|max:255',
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
            'date_year' => '年',
            'date_day' => '日付',
            'start_time' => '出勤時間',
            'end_time' => '退勤時間',
            'break_start_time' => '休憩開始時間',
            'break_end_time' => '休憩終了時間',
            'remarks' => '備考',
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
            'end_time.after' => '退勤時間は出勤時間より後でなければなりません。',
            'break_end_time.before' => '休憩終了時間は休憩開始時間より後でなければなりません。',
            'start_time.required' => '出勤時間は必須です。',
            'end_time.required' => '退勤時間は必須です。',
            'start_time.date_format' => '出勤時間の形式は HH:mm で入力してください。',
            'end_time.date_format' => '退勤時間の形式は HH:mm で入力してください。',
            'break_start_time.date_format' => '休憩開始時間の形式は HH:mm で入力してください。',
            'break_end_time.date_format' => '休憩終了時間の形式は HH:mm で入力してください。',
        ];
    }

    /**
     * リクエストを処理する前にデータの調整を行う
     */
    protected function prepareForValidation()
    {
        // 出勤・退勤時間、休憩時間を半角にする
        $this->merge([
            'start_time' => mb_convert_kana($this->start_time, 'a'),
            'end_time' => mb_convert_kana($this->end_time, 'a'),
            'break_start_time' => mb_convert_kana($this->break_start_time, 'a'),
            'break_end_time' => mb_convert_kana($this->break_end_time, 'a'),
            'remarks' => mb_convert_kana($this->remarks, 'a', 'UTF-8')  // 備考も半角に変換
        ]);
    }
}
