<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
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
            'full_name' => 'required|max:100',
            'username' => 'required|max:20|alpha_num|unique:users,username',
            'payment_method' => 'required|exists:payment_gateways,keyword',
            'email' => 'required|email|unique:users,email',
            'company_name' => 'nullable|max:100',
            'country' => 'nullable|max:255',
            'package_id' => 'required|exists:packages,id',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',        // at least one uppercase
                'regex:/[a-z]/',        // at least one lowercase
                'regex:/[0-9]/',        // at least one number
                'regex:/[@$!%*?&]/'     // at least one special character
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).'
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()->toArray()
        ], 422));
    }
}
