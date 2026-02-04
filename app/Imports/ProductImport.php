<?php

namespace App\Imports;

use App\Models\Admin\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $inserted = 0;
    public int $skipped = 0;
    public array $errors = [];

    protected $defaultLanguage;
    protected array $categoryNameMap = [];
    protected array $categoryIdSet = [];
    protected array $seenSkus = [];
    protected array $seenVariantSkus = [];

    public function __construct()
    {
        $this->defaultLanguage = Language::where('is_default', 1)->first();

        $categories = ProductCategory::where('language_id', $this->defaultLanguage->id)->get();
        $this->categoryNameMap = $categories
            ->mapWithKeys(fn ($c) => [mb_strtolower(trim((string) $c->name)) => $c->id])
            ->toArray();
        $this->categoryIdSet = $categories->pluck('id')->all();
    }

    public function collection(Collection $rows)
    {
        $singleRows = [];
        $variantGroups = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // heading row is 1
            $data = $row->toArray();

            if ($this->rowHasVariant($data)) {
                $groupKey = $this->stringOrEmpty($data['group_key'] ?? null);
                if ($groupKey === '') {
                    $this->skipRow($rowNum, 'group_key is required for variant rows.');
                    continue;
                }
                $variantGroups[$groupKey][] = ['rowNum' => $rowNum, 'data' => $data];
            } else {
                $singleRows[] = ['rowNum' => $rowNum, 'data' => $data];
            }
        }

        foreach ($singleRows as $row) {
            $this->importSimpleRow($row['rowNum'], $row['data']);
        }

        foreach ($variantGroups as $groupKey => $rowsData) {
            $this->importVariantGroup($groupKey, $rowsData);
        }
    }

    protected function importSimpleRow(int $rowNum, array $data): void
    {
        $title = $this->stringOrEmpty($data['title'] ?? null);
        $description = $this->stringOrEmpty($data['description'] ?? null);

        if ($title === '') {
            $this->skipRow($rowNum, 'Title is required.');
            return;
        }
        if ($description === '') {
            $this->skipRow($rowNum, 'Description is required.');
            return;
        }

        $categoryId = $this->resolveCategoryId($data);
        if (!$categoryId) {
            $this->skipRow($rowNum, 'Category not found. Provide valid category_id or category_name.');
            return;
        }

        $typeRaw = $this->stringOrEmpty($data['type'] ?? 'physical');
        $typeLower = mb_strtolower($typeRaw);
        if (!in_array($typeLower, ['physical', 'digital'], true)) {
            $this->skipRow($rowNum, 'Type must be physical or digital.');
            return;
        }
        $type = $typeLower === 'digital' ? 'Digital' : 'Physical';

        $status = $this->normalizeStatus($data['status'] ?? 1);
        if ($status === null) {
            $this->skipRow($rowNum, 'Status must be 1/0 or active/inactive.');
            return;
        }

        $sku = $this->stringOrNull($data['sku'] ?? null);
        if ($sku !== null) {
            $skuKey = mb_strtolower($sku);
            if (in_array($skuKey, $this->seenSkus, true) || Product::where('sku', $sku)->exists()) {
                $this->skipRow($rowNum, 'Duplicate SKU.');
                return;
            }
            $this->seenSkus[] = $skuKey;
        }

        $currentPrice = $this->normalizeNumber($data['current_price'] ?? 0);
        if ($currentPrice === null) {
            $this->skipRow($rowNum, 'Current price must be numeric.');
            return;
        }

        $previousPrice = $this->normalizeNumber($data['previous_price'] ?? null);
        if ($previousPrice === null && $this->stringOrEmpty($data['previous_price'] ?? null) !== '') {
            $this->skipRow($rowNum, 'Previous price must be numeric.');
            return;
        }

        $stock = 0;
        if ($typeLower === 'physical') {
            $stock = $this->normalizeInteger($data['stock'] ?? 0);
            if ($stock === null || $stock < 0) {
                $this->skipRow($rowNum, 'Stock must be a non-negative integer.');
                return;
            }
        }

        $downloadLink = $this->stringOrNull($data['download_link'] ?? null);
        if ($typeLower === 'digital' && $downloadLink === null) {
            $this->skipRow($rowNum, 'Download link is required for digital products.');
            return;
        }

        $summary = $this->stringOrNull($data['summary'] ?? null);
        $metaKeywordRaw = $this->stringOrEmpty($data['meta_keyword'] ?? ($data['meta_keywords'] ?? null));
        $metaKeywords = $this->normalizeKeywords($metaKeywordRaw);
        $metaDescription = $this->stringOrNull($data['meta_description'] ?? null);

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->thumbnail = 'noimage.jpg';
            $product->has_variants = 0;
            $product->stock = $typeLower === 'physical' ? $stock : 0;
            $product->last_restock_qty = $typeLower === 'physical' ? $stock : 0;
            $product->sku = $sku;
            $product->current_price = $currentPrice ?? 0;
            $product->previous_price = $previousPrice;
            $product->type = $type;
            $product->file_type = $typeLower === 'digital' ? 'link' : null;
            $product->download_link = $downloadLink;
            $product->download_file = null;
            $product->status = $status;
            $product->save();

            $content = new ProductContent();
            $content->language_id = $this->defaultLanguage->id;
            $content->product_id = $product->id;
            $content->category_id = $categoryId;
            $content->title = $title;
            $content->slug = createSlug($title);
            $content->summary = $summary;
            $content->description = $description;
            $content->meta_keyword = $metaKeywords;
            $content->meta_description = $metaDescription;
            $content->save();

            DB::commit();
            $this->inserted++;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->skipRow($rowNum, 'Failed to import row.');
        }
    }

    protected function importVariantGroup(string $groupKey, array $rowsData): void
    {
        $productData = [];
        foreach ($rowsData as $row) {
            $data = $row['data'];
            foreach ([
                'sku', 'title', 'category_id', 'category_name', 'type', 'status', 'stock',
                'current_price', 'previous_price', 'summary', 'description', 'meta_keyword',
                'meta_keywords', 'meta_description', 'download_link'
            ] as $field) {
                if (!isset($productData[$field]) || $productData[$field] === '') {
                    $productData[$field] = $this->stringOrEmpty($data[$field] ?? null);
                }
            }
        }

        $title = $this->stringOrEmpty($productData['title'] ?? null);
        $description = $this->stringOrEmpty($productData['description'] ?? null);

        if ($title === '' || $description === '') {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Title and description are required.");
            return;
        }

        $categoryId = $this->resolveCategoryId($productData);
        if (!$categoryId) {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Category not found.");
            return;
        }

        $typeRaw = $this->stringOrEmpty($productData['type'] ?? 'physical');
        $typeLower = mb_strtolower($typeRaw);
        if (!in_array($typeLower, ['physical', 'digital'], true)) {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Type must be physical or digital.");
            return;
        }
        $type = $typeLower === 'digital' ? 'Digital' : 'Physical';

        $status = $this->normalizeStatus($productData['status'] ?? 1);
        if ($status === null) {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Status must be 1/0 or active/inactive.");
            return;
        }

        $sku = $this->stringOrNull($productData['sku'] ?? null);
        if ($sku !== null) {
            $skuKey = mb_strtolower($sku);
            if (in_array($skuKey, $this->seenSkus, true) || Product::where('sku', $sku)->exists()) {
                $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Duplicate product SKU.");
                return;
            }
            $this->seenSkus[] = $skuKey;
        }

        $currentPrice = $this->normalizeNumber($productData['current_price'] ?? 0);
        if ($currentPrice === null && $this->stringOrEmpty($productData['current_price'] ?? null) !== '') {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Current price must be numeric.");
            return;
        }

        $previousPrice = $this->normalizeNumber($productData['previous_price'] ?? null);
        if ($previousPrice === null && $this->stringOrEmpty($productData['previous_price'] ?? null) !== '') {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Previous price must be numeric.");
            return;
        }

        $downloadLink = $this->stringOrNull($productData['download_link'] ?? null);
        if ($typeLower === 'digital' && $downloadLink === null) {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Download link is required for digital products.");
            return;
        }

        $summary = $this->stringOrNull($productData['summary'] ?? null);
        $metaKeywordRaw = $this->stringOrEmpty($productData['meta_keyword'] ?? ($productData['meta_keywords'] ?? null));
        $metaKeywords = $this->normalizeKeywords($metaKeywordRaw);
        $metaDescription = $this->stringOrNull($productData['meta_description'] ?? null);

        $optionsOrder = [];
        $optionValues = [];
        $variantRows = [];

        foreach ($rowsData as $row) {
            $rowNum = $row['rowNum'];
            $data = $row['data'];

            $mapRaw = $this->stringOrEmpty($data['variant_map'] ?? null);
            $map = $this->parseVariantMap($mapRaw);
            if ($map === null) {
                $this->skipRow($rowNum, "Group {$groupKey}: Invalid variant_map format.");
                continue;
            }

            foreach ($map as $name => $value) {
                if (!isset($optionsOrder[$name])) {
                    $optionsOrder[$name] = count($optionsOrder);
                }
                if (!isset($optionValues[$name])) {
                    $optionValues[$name] = [];
                }
                if (!isset($optionValues[$name][$value])) {
                    $optionValues[$name][$value] = count($optionValues[$name]);
                }
            }

            $variantRows[] = ['rowNum' => $rowNum, 'data' => $data, 'map' => $map];
        }

        if (count($variantRows) === 0) {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: No valid variant rows found.");
            return;
        }

        $optionNames = array_keys($optionsOrder);

        $validVariantRows = [];
        foreach ($variantRows as $row) {
            $rowNum = $row['rowNum'];
            $map = $row['map'];

            foreach ($optionNames as $name) {
                if (!array_key_exists($name, $map)) {
                    $this->skipRow($rowNum, "Group {$groupKey}: Missing option {$name} in variant_map.");
                    continue 2;
                }
            }

            $variantSku = $this->stringOrNull($row['data']['variant_sku'] ?? null);
            if ($variantSku !== null) {
                $variantSkuKey = mb_strtolower($variantSku);
                if (in_array($variantSkuKey, $this->seenVariantSkus, true) || \App\Models\ProductVariant::where('sku', $variantSku)->exists()) {
                    $this->skipRow($rowNum, "Group {$groupKey}: Duplicate variant SKU.");
                    continue;
                }
                $this->seenVariantSkus[] = $variantSkuKey;
            }

            $variantPrice = $this->normalizeNumber($row['data']['variant_price'] ?? null);
            if ($variantPrice === null && $this->stringOrEmpty($row['data']['variant_price'] ?? null) !== '') {
                $this->skipRow($rowNum, "Group {$groupKey}: Variant price must be numeric.");
                continue;
            }

            $variantStock = $this->normalizeInteger($row['data']['variant_stock'] ?? 0);
            if ($variantStock === null || $variantStock < 0) {
                $this->skipRow($rowNum, "Group {$groupKey}: Variant stock must be a non-negative integer.");
                continue;
            }

            $variantStatus = $this->normalizeStatus($row['data']['variant_status'] ?? 1);
            if ($variantStatus === null) {
                $this->skipRow($rowNum, "Group {$groupKey}: Variant status must be 1/0 or active/inactive.");
                continue;
            }

            $validVariantRows[] = [
                'rowNum' => $rowNum,
                'map' => $map,
                'sku' => $variantSku,
                'price' => $variantPrice,
                'stock' => $variantStock,
                'status' => $variantStatus,
            ];
        }

        if (count($validVariantRows) === 0) {
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: No valid variants to import.");
            return;
        }

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->thumbnail = 'noimage.jpg';
            $product->has_variants = 1;
            $product->stock = 0;
            $product->last_restock_qty = 0;
            $product->sku = $sku;
            $product->current_price = $currentPrice ?? 0;
            $product->previous_price = $previousPrice;
            $product->type = $type;
            $product->file_type = $typeLower === 'digital' ? 'link' : null;
            $product->download_link = $downloadLink;
            $product->download_file = null;
            $product->status = $status;
            $product->save();

            $content = new ProductContent();
            $content->language_id = $this->defaultLanguage->id;
            $content->product_id = $product->id;
            $content->category_id = $categoryId;
            $content->title = $title;
            $content->slug = createSlug($title);
            $content->summary = $summary;
            $content->description = $description;
            $content->meta_keyword = $metaKeywords;
            $content->meta_description = $metaDescription;
            $content->save();

            $valueIdMap = [];
            foreach ($optionNames as $optName) {
                $option = \App\Models\ProductOption::create([
                    'product_id' => $product->id,
                    'name' => $optName,
                    'position' => $optionsOrder[$optName],
                ]);

                foreach ($optionValues[$optName] as $value => $pos) {
                    $val = \App\Models\ProductOptionValue::create([
                        'product_option_id' => $option->id,
                        'value' => $value,
                        'position' => $pos,
                    ]);
                    $valueIdMap[$optName][$value] = $val->id;
                }
            }

            foreach ($validVariantRows as $variantRow) {
                $variant = \App\Models\ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantRow['sku'],
                    'price' => $variantRow['price'],
                    'stock' => $variantRow['stock'],
                    'status' => $variantRow['status'],
                ]);

                foreach ($variantRow['map'] as $optName => $optValue) {
                    $optionValueId = $valueIdMap[$optName][$optValue] ?? null;
                    if ($optionValueId) {
                        \App\Models\ProductVariantValue::create([
                            'variant_id' => $variant->id,
                            'option_value_id' => $optionValueId,
                        ]);
                    }
                }
            }

            DB::commit();
            $this->inserted++;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->skipRow($rowsData[0]['rowNum'], "Group {$groupKey}: Failed to import.");
        }
    }

    protected function resolveCategoryId(array $data): ?int
    {
        $categoryIdRaw = $this->stringOrEmpty($data['category_id'] ?? null);
        if ($categoryIdRaw !== '') {
            $categoryId = (int) $categoryIdRaw;
            return in_array($categoryId, $this->categoryIdSet, true) ? $categoryId : null;
        }

        $categoryName = $this->stringOrEmpty($data['category_name'] ?? ($data['category'] ?? null));
        if ($categoryName === '') {
            return null;
        }

        $key = mb_strtolower($categoryName);
        return $this->categoryNameMap[$key] ?? null;
    }

    protected function normalizeStatus($value): ?int
    {
        $raw = mb_strtolower($this->stringOrEmpty($value));
        if ($raw === '' || $raw === '1' || $raw === 'active') {
            return 1;
        }
        if ($raw === '0' || $raw === 'inactive') {
            return 0;
        }
        return null;
    }

    protected function normalizeNumber($value): ?float
    {
        $raw = $this->stringOrEmpty($value);
        if ($raw === '') {
            return null;
        }
        if (!is_numeric($raw)) {
            return null;
        }
        return (float) $raw;
    }

    protected function normalizeInteger($value): ?int
    {
        $raw = $this->stringOrEmpty($value);
        if ($raw === '') {
            return 0;
        }
        if (!is_numeric($raw)) {
            return null;
        }
        return (int) $raw;
    }

    protected function normalizeKeywords(string $value): ?string
    {
        if ($value === '') {
            return null;
        }
        $parts = array_filter(array_map('trim', explode(',', $value)));
        return count($parts) ? json_encode(array_values($parts)) : null;
    }

    protected function stringOrEmpty($value): string
    {
        if ($value === null) {
            return '';
        }
        return trim((string) $value);
    }

    protected function stringOrNull($value): ?string
    {
        $value = $this->stringOrEmpty($value);
        return $value === '' ? null : $value;
    }

    protected function skipRow(int $rowNum, string $message): void
    {
        $this->errors[] = "Row {$rowNum}: {$message}";
        $this->skipped++;
    }

    protected function rowHasVariant(array $data): bool
    {
        foreach (['variant_map', 'variant_sku', 'variant_price', 'variant_stock', 'variant_status'] as $key) {
            if ($this->stringOrEmpty($data[$key] ?? null) !== '') {
                return true;
            }
        }
        return false;
    }

    protected function parseVariantMap(string $raw): ?array
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
                $sepPos = strpos($pair, ':');
            }
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
