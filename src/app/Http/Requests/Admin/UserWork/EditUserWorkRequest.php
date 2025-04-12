<?php

namespace App\Http\Requests\Admin\UserWork;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class EditUserWorkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'work_record_id' => (int)$this->work_record_id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'work_record_id' => ['bail', 'required', 'integer', 'exists:work_records,id'],
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
