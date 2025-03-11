<?php

namespace App\Http\Requests\Admin\User;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'employee_number' => ['bail', 'required', 'string', 'max:255', 'unique:users,employee_number'],
            'name' => ['bail', 'required', 'string', 'max:255'],
            'email' => ['bail', 'required', 'email:rfc', 'unique:users,email'],
            'join_date' => ['bail', 'date'],
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
            'email' => 'メールアドレス',
            'join_date' => '入社日',
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード（確認）',
        ];
    }
}
