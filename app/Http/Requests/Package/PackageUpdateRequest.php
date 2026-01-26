<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class PackageUpdateRequest extends FormRequest
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
            'title' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required'
        ];

        if ($this->is_trial == 1) {
            $rules['trial_days'] = 'required|integer|min:1';
            $rules['term'] = 'nullable';
        } else {
            $rules['term'] = 'required';
        }

        return $rules;
    }
}
