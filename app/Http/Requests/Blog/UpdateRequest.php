<?php

namespace App\Http\Requests\Blog;

use App\Models\Admin\Language;
use App\Models\BlogContent;
use Illuminate\Foundation\Http\FormRequest;

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
    public function rules(): array
    {
        $ruleArray = [
            'serial_number' => 'required|numeric',
            'status' => 'required',
        ];

        // Default language fields should always be required
        $defaultLanguage = Language::where('is_default', 1)->first();
        $ruleArray[$defaultLanguage->code . '_title'] = 'required|max:255';
        $ruleArray[$defaultLanguage->code . '_category_id'] = 'required';
        $ruleArray[$defaultLanguage->code . '_text'] = 'required';
        $ruleArray[$defaultLanguage->code . '_author'] = 'required|max:255';

        $languages = app('languages');
        foreach ($languages as $language) {
            $code = $language->code;
            // Skip the default language as it's always required
            $hasExistingContent = BlogContent::where('language_id', $language->id)->where('blog_id', $this->id)->exists();
            if ($language->id == $defaultLanguage->id) {
                continue;
            }

            if (
                $hasExistingContent ||
                $this->filled($code . '_title') ||
                $this->filled($code . '_author') ||
                $this->filled($code . '_category_id') ||
                $this->filled($code . '_text') ||
                $this->filled($code . '_meta_keywords') ||
                $this->filled($code . '_meta_description')
            ) {
                $ruleArray[$code . '_title'] = 'required|max:255';
                $ruleArray[$code . '_category_id'] = 'required';
                $ruleArray[$code . '_text'] = 'required';
                $ruleArray[$code . '_author'] = 'required|max:255';
            }
        }

        return $ruleArray;
    }

    public function messages()
    {
        $messageArray = [];
        $languages = app('languages');

        foreach ($languages as $language) {
            $code = $language->code;
            $name = ' ' . $language->name . ' ' . __('language.');

            $messageArray[$code . '_title.required'] = __('The title field is required for') . $name;
            $messageArray[$code . '_title.max'] = __('The title field cannot contain more than 255 characters for') . $name;
            $messageArray[$code . '_category_id.required'] = __('The category field is required for') . $name;
            $messageArray[$code . '_text.required'] = __('The text field is required for') . $name;
            $messageArray[$code . '_author.required'] = __('The author field is required for') . $name;
            $messageArray[$code . '_author.max'] = __('The author field cannot contain more than 255 characters for') . $name;
        }
        return $messageArray;
    }
}
