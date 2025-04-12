<?php

namespace App\Http\Requests\Admin\UserWork;

use App\Rules\ClockOutAfterClockInRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserWorkRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'clock_in'  => ['bail', 'required', 'date_format:H:i'],
            'clock_out' => ['bail', 'required', 'date_format:H:i', new ClockOutAfterClockInRule($this->clock_in)],
            'break_time' => ['bail', 'required', 'integer', Rule::in(array_keys(config('const.break_time_list')))],
            'memo' => ['bail', 'nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * 属性名に対する表示用名称の設定
     * @return array
     */
    public function attributes()
    {
        return [
            'clock_in' => '出勤時間',
            'clock_out' => '退勤時間',
            'break_time' => '休憩時間',
            'memo' => '備考',
        ];
    }

    /**
     * カスタムエラーメッセージを定義
     */
    public function messages(): array
    {
        return [
            'break_time.in' => '選択した休憩時間は無効です。',
        ];
    }
}
