<?php

namespace App\Http\Requests\User;

use App\Services\Plugins\GoogleRecaptchaService;
use Illuminate\Foundation\Http\FormRequest;

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
            'username' => 'required|string|min:3|max:20|alpha_num|unique:users,username',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
            'password_confirmation' => 'required|string'
        ];

        if (app(GoogleRecaptchaService::class)->isEnabled()) {
            $rules['g-recaptcha-response'] = ['required', 'string'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.alpha_num' => __('Username may only contain letters and numbers.'),
            'password.confirmed' => __('Password confirmation does not match.'),
            'password.regex' => __('Password must include uppercase, lowercase, number and special character.'),
            'g-recaptcha-response.required' => __('Please complete the Google Recaptcha verification.'),
        ];
    }

    public function withValidator($validator): void
    {
        $recaptcha = app(GoogleRecaptchaService::class);

        if (!$recaptcha->isEnabled()) {
            return;
        }

        $validator->after(function ($validator) use ($recaptcha) {
            if ($validator->errors()->has('g-recaptcha-response')) {
                return;
            }

            if (!$recaptcha->verify($this->input('g-recaptcha-response'), $this->ip())) {
                $validator->errors()->add('g-recaptcha-response', __('Google Recaptcha verification failed. Please try again.'));
            }
        });
    }
}
