<?php

namespace App\Http\Requests\Vendor;

use App\Models\Admin\Language;
use App\Models\Vendor;
use App\Rules\ImageExtension;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'username' => ['required', Rule::unique('vendors', 'username')->ignore($this->id, 'id')],
            'email' => ['required', Rule::unique('vendors', 'email')->ignore($this->email, 'email')],
        ];
        $image = Vendor::find($this->id)->image;
        if (empty($image)) {
            $rules['image'] = 'required';
        }
        if ($this->hasFile('image')) {
            $rules['image'] = new ImageExtension();
        }

        $defaulLang = Language::where('is_default', 1)->first();
        $rules[$defaulLang->code . '_name'] = 'required|max:255';

        foreach (Language::all() as $language) {
            $code = $language->code;
            if ($defaulLang->id == $language->id) {
                continue;
            }
            if (
                $this->filled($code . '_name') ||
                $this->filled($code . '_address') ||
                $this->filled($code . '_city') ||
                $this->filled($code . '_state') ||
                $this->filled($code . '_country')
            ) {
                $rules[$code . '_name'] = 'required|max:255';
            }
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach (Language::all() as $language) {
            $code = $language->code;
            $text = ' ' . $language->name . ' ' . __('language.');
            $messages[$code . '_name.required'] = __('The name field is required for') . $text;
        }

        return $messages;
    }
}
