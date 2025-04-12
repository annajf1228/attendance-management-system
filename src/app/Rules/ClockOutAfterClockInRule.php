<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ClockOutAfterClockInRule implements ValidationRule
{
    protected string $clockIn;

    public function __construct(string $clockIn)
    {
        $this->clockIn = $clockIn;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtotime($value) <= strtotime($this->clockIn)) {
            $fail('退勤時間は出勤時間より後の時間を指定してください。');
        }
    }
}