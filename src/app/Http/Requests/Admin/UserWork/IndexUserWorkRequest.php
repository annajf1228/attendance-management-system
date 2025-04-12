<?php

namespace App\Http\Requests\Admin\UserWork;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class IndexUserWorkRequest extends FormRequest
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
            'id' => ['bail', 'required', 'integer', 'exists:users,id'],
            'year' => ['bail', 'nullable', 'integer', 'digits:4'],
            'month' => ['bail', 'nullable', 'integer', 'between:1,12'],
        ];
    }

    /**
     * バリデーション失敗時に 404 を返す
     */
    protected function failedValidation(Validator $validator): void
    {
        abort(404);
    }

}
