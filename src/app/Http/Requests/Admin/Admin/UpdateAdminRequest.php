<?php

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\AdminRepository;

class UpdateAdminRequest extends FormRequest
{

    public function __construct(private AdminRepository $adminRepository) {}

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
        $employeeNumber = $this->adminRepository->findOrFail($this->id)->employee_number;
        return [
            'employee_number' => ['bail', 'required', 'string', 'max:255', 'unique:admins,employee_number,' . $employeeNumber. ',employee_number'],
            'name' => ['bail', 'required', 'string', 'max:255'],
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
        ];
    }
}
