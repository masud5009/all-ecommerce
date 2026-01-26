<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MatchEmailRule implements ValidationRule
{
    public $personType;

    function __construct($personType)
    {
        $this->personType = $personType;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->personType == 'admin') {
            $admin = Admin::where('email', $value)->first();

            if (is_null($admin)) {
                $fail(__('This email does not exist!'));
            }
        } elseif ($this->personType == 'user') {
            $admin = User::where('email', $value)->first();

            if (is_null($admin)) {
                $fail(__('This email does not exist!'));
            }
        }
    }
}
