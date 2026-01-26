<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ImageExtension implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg', 'gif');
        $fileExtension = $value->getClientOriginalExtension();

        if (!in_array($fileExtension, $allowedExtensions)) {
            $fail("The $attribute must be a file of type: " . implode(', ', $allowedExtensions) . '.');
        }
    }
}
