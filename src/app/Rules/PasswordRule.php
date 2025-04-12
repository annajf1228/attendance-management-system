<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 半角英数字8～20文字
        if (!preg_match('/^[a-zA-Z0-9]{8,20}$/', $value)) {
            $fail('パスワードは半角英数字、8～20文字以内で入力ください。');
            return;
        }

        // 英字と数字の両方を含むかチェック
        if (!preg_match('/[a-zA-Z]/', $value) || !preg_match('/[0-9]/', $value)) {
            $fail('パスワードは半角英数字、8～20文字以内で入力ください。');
        }
    }
}
