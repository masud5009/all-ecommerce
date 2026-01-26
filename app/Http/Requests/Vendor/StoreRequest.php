<?php

namespace App\Http\Requests\Vendor;

use App\Models\Admin\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        $rules = [
            'username' => ['required', Rule::unique('vendors', 'username')],
            'email' => ['required','email', Rule::unique('vendors', 'email')],
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
            'package_id' => 'required',
            'gateway' => 'required'
        ];
        return $rules;
    }
}
