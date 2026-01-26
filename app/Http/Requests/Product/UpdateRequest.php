<?php

namespace App\Http\Requests\Product;

use App\Models\Admin\Language;
use App\Models\ProductContent;
use App\Rules\ImageExtension;
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
    public function rules()
    {
        $ruleArray = [
            'thumbnail' => $this->hasFile('thumbnail') ? new ImageExtension() : '',
            'current_price' => 'required|numeric|min:0',
            'status' => 'required|between:0,1',
            'sku' => 'required',
        ];
        //for physical product
        if ($this->type == 'Physical') {
            $ruleArray['stock'] = 'required|numeric|min:0';
        }
        //for digital product
        if ($this->type == 'Digital') {
            if ($this->file_type == 'upload') {
                $ruleArray['download_file'] = 'required|mimes:pdf,xlsx,xls,jpeg,png,jpg,gif,zip,txt';
            } else {
                $ruleArray['download_link'] = 'required|url';
            }
        }


        // Default language fields should always be required
        $defaultLanguage = Language::where('is_default', 1)->first();
        $ruleArray[$defaultLanguage->code . '_title'] = 'required|max:255';
        $ruleArray[$defaultLanguage->code . '_category_id'] = 'required|exists:product_categories,id';
        $ruleArray[$defaultLanguage->code . '_description'] = 'required';

        $languages = app('languages');

        foreach ($languages as $language) {
            $code = $language->code;
           // Skip the default language as it's always required
           $hasExistingContent = ProductContent::where([['language_id', $language->id],['product_id',$this->id]])->exists();
           if ($language->id == $defaultLanguage->id) {
               continue;
           }

            if (
                $hasExistingContent ||
                $this->filled($code . '_title') ||
                $this->filled($code . '_category_id') ||
                $this->filled($code . '_summary') ||
                $this->filled($code . '_description') ||
                $this->filled($code . '_meta_keywords') ||
                $this->filled($code . '_meta_description')
            ) {
                $ruleArray[$code . '_title'] = 'required|max:255';
                $ruleArray[$code . '_category_id'] = 'required|exists:product_categories,id';
                $ruleArray[$code . '_description'] = 'required';
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
            $messageArray[$code . '_description.required'] = __('The text field is required for') . $name;
        }
        return $messageArray;
    }
}
