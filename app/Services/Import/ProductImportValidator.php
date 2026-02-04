<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\Validator;

class ProductImportValidator
{
    public function validateProductRow(array $row): array
    {
        $rules = [
            'row_type' => 'required|in:product',
            'product_sku' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:physical,digital',
            'status' => 'nullable|in:0,1,active,inactive',
            'stock' => 'nullable|integer|min:0',
            'current_price' => 'nullable|numeric|min:0',
            'previous_price' => 'nullable|numeric|min:0',
            'download_link' => 'nullable|url',
            'category_id' => 'nullable|integer',
            'category_name' => 'nullable|string',
        ];

        $messages = [
            'product_sku.required' => 'product_sku is required.',
            'row_type.in' => 'row_type must be product or variant.',
        ];

        $validator = Validator::make($row, $rules, $messages);
        return $validator->fails() ? $validator->errors()->all() : [];
    }

    public function validateVariantRow(array $row): array
    {
        $rules = [
            'row_type' => 'required|in:variant',
            'group_key' => 'required|string|max:255',
            'variant_map' => 'required|string',
            'variant_sku' => 'required|string|max:255',
            'variant_price' => 'nullable|numeric|min:0',
            'variant_stock' => 'required|integer|min:0',
            'variant_status' => 'required|in:0,1,active,inactive',
            'download_link' => 'nullable|url',
        ];

        $messages = [
            'group_key.required' => 'group_key is required for variant rows.',
            'variant_map.required' => 'variant_map is required for variant rows.',
        ];

        $validator = Validator::make($row, $rules, $messages);

        $errors = $validator->fails() ? $validator->errors()->all() : [];

        if (empty($errors)) {
            if ($this->parseVariantMap((string)($row['variant_map'] ?? '')) === null) {
                $errors[] = 'variant_map format must be Key=Value|Key=Value.';
            }
        }

        return $errors;
    }

    public function parseVariantMap(string $raw): ?array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }

        $pairs = preg_split('/\s*\|\s*/', $raw);
        $map = [];

        foreach ($pairs as $pair) {
            $pair = trim($pair);
            if ($pair === '') {
                continue;
            }

            $sepPos = strpos($pair, '=');
            if ($sepPos === false) {
                return null;
            }

            $name = trim(substr($pair, 0, $sepPos));
            $value = trim(substr($pair, $sepPos + 1));

            if ($name === '' || $value === '') {
                return null;
            }

            $map[$name] = $value;
        }

        return count($map) ? $map : null;
    }
}
