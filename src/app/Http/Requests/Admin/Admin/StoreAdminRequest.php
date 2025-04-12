<?php

namespace App\Http\Requests\Admin\Admin;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
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
            'employee_number' => ['bail', 'required', 'string', 'max:255', 'unique:admins,employee_number'],
            'name' => ['bail', 'required', 'string', 'max:255'],
            'password' => ['bail', 'required', new PasswordRule, 'confirmed'],
        ];
    }

    /**
     * 属性名に対する表示用名称の設定
     * @return array
     */
    public function attributes()
    {
        return [
            'employee_number' => '社員番号',
            'name' => '名前',
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード（確認）',
        ];
    }
}
