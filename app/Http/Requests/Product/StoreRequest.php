<?php

namespace App\Http\Requests\Product;

use App\Models\Admin\Language;
use App\Rules\ImageExtension;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $type = strtolower((string) $this->input('type'));
        $hasVariants = $this->boolean('has_variants');

        $ruleArray = [
            'slider_image'   => 'required',
            'thumbnail'      => ['required', new ImageExtension()],
            'status'         => 'required|in:0,1',

            // ✅ Base price/sku rules depend on variants
            'current_price'  => $hasVariants ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'sku'            => $hasVariants ? 'nullable|string|max:255' : 'required|string|max:255',
        ];

        // ✅ Physical stock depends on variants
        if ($type === 'physical') {
            $ruleArray['stock'] = $hasVariants ? 'nullable|numeric|min:0' : 'required|numeric|min:0';
        }

        // ✅ Digital file validation
        if ($type === 'digital') {
            if ($this->file_type == 'upload') {
                $ruleArray['download_file'] = 'required|mimes:pdf,xlsx,xls,jpeg,png,jpg,gif,zip,txt';
            } else {
                $ruleArray['download_link'] = 'required|url';
            }
        }

        // ✅ Variants validation
        if ($hasVariants) {
            $ruleArray['variant_options'] = 'required|array|min:1';
            $ruleArray['variant_options.*.name'] = 'required|string|max:255';
            $ruleArray['variant_options.*.values'] = 'required|string';

            $ruleArray['variants'] = 'required|array|min:1';
            $ruleArray['variants.*.stock'] = 'required|integer|min:0';
            $ruleArray['variants.*.status'] = 'required|in:0,1';
            $ruleArray['variants.*.map'] = 'required|string';     // JSON string
            $ruleArray['variants.*.sku'] = 'nullable|string|max:255';
            $ruleArray['variants.*.price'] = 'nullable|numeric|min:0';
            $ruleArray['variants.*.serial_start'] = 'nullable|string|max:255';
            $ruleArray['variants.*.serial_end'] = 'nullable|string|max:255';
            $ruleArray['variants.*.image'] = ['nullable', new ImageExtension()];
        }

        // ✅ Default language always required
        $defaultLanguage = Language::where('is_default', 1)->first();
        $ruleArray[$defaultLanguage->code . '_title'] = 'required|max:255';
        $ruleArray[$defaultLanguage->code . '_category_id'] = 'required|exists:product_categories,id';
        $ruleArray[$defaultLanguage->code . '_description'] = 'required';

        $languages = app('languages');

        foreach ($languages as $language) {
            $code = $language->code;

            if ($language->id == $defaultLanguage->id) {
                continue;
            }

            if (
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

        // Optional: nicer variant messages
        $messageArray['variant_options.required'] = 'Please add at least one option.';
        $messageArray['variants.required'] = 'Please generate variants before submit.';
        $messageArray['variants.*.stock.required'] = 'Each variant must have stock.';

        return $messageArray;
    }
}
