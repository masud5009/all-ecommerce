<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductContent;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantSerialBatch;
use App\Models\ProductVariantValue;
use App\Models\SliderImage;
use App\Models\Admin\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuickTestProductsSeeder extends Seeder
{
    public function run(): void
    {
        $languages = Language::all();
        if ($languages->isEmpty()) {
            $this->command?->warn('No languages found. Aborting.');
            return;
        }

        $defaultLang = $languages->firstWhere('is_default', 1) ?? $languages->first();

        $this->cleanupTestProducts();

        $categoryMap = $this->ensureCategories($defaultLang);

        $fallbackThumbs = $this->globImages(public_path('assets/img/product'));
        $fallbackGallery = $this->globImages(public_path('assets/img/product/gallery'));

        if (empty($fallbackThumbs) || empty($fallbackGallery)) {
            $this->command?->warn('No fallback images found in public/assets/img/product. Aborting.');
            return;
        }

        $variantDir = public_path('assets/img/product/variant');
        if (!is_dir($variantDir)) {
            @mkdir($variantDir, 0775, true);
        }

        $templates = [
            [
                'key' => 'electronics',
                'label' => 'Electronics',
                'prefix' => 'EL',
                'titles' => [
                    'Smartphone Pro 5G',
                    'Noise Cancelling Headphones',
                    '4K Action Camera',
                    'Bluetooth Speaker Mini',
                ],
                'options' => [
                    ['name' => 'Color', 'values' => ['Black', 'Silver']],
                    ['name' => 'Storage', 'values' => ['64GB', '128GB']],
                ],
            ],
            [
                'key' => 'fashion',
                'label' => 'Fashion',
                'prefix' => 'FA',
                'titles' => [
                    'Classic Denim Jacket',
                    'Premium Cotton T-Shirt',
                    'Women Sneakers',
                ],
                'options' => [
                    ['name' => 'Size', 'values' => ['S', 'M']],
                    ['name' => 'Color', 'values' => ['Red', 'Blue']],
                ],
            ],
            [
                'key' => 'grocery',
                'label' => 'Grocery',
                'prefix' => 'GR',
                'titles' => [
                    'Organic Basmati Rice',
                    'Cold Pressed Olive Oil',
                    'Premium Green Tea',
                ],
                'options' => [
                    ['name' => 'Weight', 'values' => ['500g', '1kg']],
                    ['name' => 'Pack', 'values' => ['Single', 'Pack of 2']],
                ],
            ],
        ];

        $productsToCreate = [];
        foreach ($templates as $group) {
            foreach ($group['titles'] as $title) {
                $productsToCreate[] = [
                    'group' => $group,
                    'title' => $title,
                ];
            }
        }

        $productsToCreate = array_slice($productsToCreate, 0, 10);

        foreach ($productsToCreate as $index => $payload) {
            $group = $payload['group'];
            $title = $payload['title'];

            $price = rand(25, 250) * 10; // 250-2500
            $imagePool = $this->getCategoryImages($group['key'], $fallbackThumbs);
            $galleryPool = $this->getCategoryGalleryImages($group['key'], $fallbackGallery);

            $thumbnail = basename($imagePool[array_rand($imagePool)]);

            $product = Product::create([
                'stock' => 0,
                'last_restock_qty' => 0,
                'sku' => 'TEST-' . $group['prefix'] . '-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                'thumbnail' => $thumbnail,
                'current_price' => $price,
                'previous_price' => $price + rand(20, 100),
                'type' => 'physical',
                'file_type' => null,
                'download_link' => null,
                'download_file' => null,
                'status' => 1,
                'featured' => 0,
                'rating' => 0,
                'order' => 0,
                'has_variants' => 1,
            ]);

            // slider images (2 per product)
            $galleryPick = $this->pickRandom($galleryPool, 2);
            foreach ($galleryPick as $path) {
                SliderImage::create([
                    'image' => basename($path),
                    'item_id' => $product->id,
                    'item_type' => 'product',
                ]);
            }

            // content only for default language
            $categoryId = $categoryMap[$group['key']] ?? null;
            if ($categoryId) {
                $content = new ProductContent();
                $content->language_id = $defaultLang->id;
                $content->product_id = $product->id;
                $content->category_id = $categoryId;
                $content->title = $title;
                $content->slug = Str::slug($content->title);
                $content->summary = 'Short summary for ' . $title . ' (' . $group['label'] . ').';
                $content->description = '<p>Detailed description for ' . $title . ' with key features and specs.</p>';
                $content->meta_keyword = json_encode([
                    strtolower($group['label']),
                    'sale',
                    'new',
                ]);
                $content->meta_description = 'Meta description for ' . $title . ' in ' . $group['label'] . '.';
                $content->save();
            }

            // options + values
            $valueIdMap = [];
            foreach ($group['options'] as $pos => $opt) {
                $option = ProductOption::create([
                    'product_id' => $product->id,
                    'name' => $opt['name'],
                    'position' => $pos,
                ]);

                foreach ($opt['values'] as $vpos => $val) {
                    $value = ProductOptionValue::create([
                        'product_option_id' => $option->id,
                        'value' => $val,
                        'position' => $vpos,
                    ]);
                    $valueIdMap[$opt['name']][$val] = $value->id;
                }
            }

            // variants (2x2 = 4)
            $optionNames = array_map(fn($o) => $o['name'], $group['options']);
            $optionValues = array_map(fn($o) => $o['values'], $group['options']);
            $combinations = $this->cartesian($optionValues);

            foreach ($combinations as $vIndex => $combo) {
                $variantSku = 'TEST-' . $group['prefix'] . '-' . $product->id . '-V' . ($vIndex + 1);
                $variantPrice = $price + ($vIndex * 15);
                $variantStock = rand(10, 60);

                $variantImage = $this->copyVariantImage($imagePool, $variantDir);

                $serialStart = $this->formatSerial(100000 + ($product->id * 1000) + ($vIndex * 100));
                $serialEnd = $this->formatSerial((int)$serialStart + $variantStock - 1);

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantSku,
                    'image' => $variantImage,
                    'price' => $variantPrice,
                    'stock' => $variantStock,
                    'status' => 1,
                    'track_serial' => 1,
                    'serial_start' => $serialStart,
                    'serial_end' => $serialEnd,
                ]);

                ProductVariantSerialBatch::create([
                    'variant_id' => $variant->id,
                    'batch_no' => 'INIT-' . $variant->id . '-' . now()->format('YmdHis'),
                    'serial_start' => $serialStart,
                    'serial_end' => $serialEnd,
                    'qty' => $variantStock,
                    'sold_qty' => 0,
                ]);

                foreach ($combo as $idx => $val) {
                    $optName = $optionNames[$idx];
                    $optValueId = $valueIdMap[$optName][$val] ?? null;
                    if ($optValueId) {
                        ProductVariantValue::create([
                            'variant_id' => $variant->id,
                            'option_value_id' => $optValueId,
                        ]);
                    }
                }
            }
        }

        $this->command?->info('Quick test products created successfully.');
    }

    private function ensureCategories($defaultLang): array
    {
        $map = [];
        $groups = [
            'electronics' => 'Electronics',
            'fashion' => 'Fashion',
            'grocery' => 'Grocery',
        ];

        foreach ($groups as $key => $label) {
            $category = ProductCategory::where('language_id', $defaultLang->id)
                ->where('name', 'like', '%' . $label . '%')
                ->first();

            if (!$category) {
                $category = ProductCategory::create([
                    'name' => $label,
                    'slug' => Str::slug($label . '-' . $defaultLang->code),
                    'serial_number' => 0,
                    'status' => 1,
                    'language_id' => $defaultLang->id,
                ]);
            }

            $map[$key] = $category->id;
        }

        return $map;
    }

    private function globImages(string $path): array
    {
        $patterns = [
            $path . '/*.jpg',
            $path . '/*.jpeg',
            $path . '/*.png',
            $path . '/*.webp',
            $path . '/*.gif',
        ];

        $files = [];
        foreach ($patterns as $pattern) {
            $files = array_merge($files, glob($pattern) ?: []);
        }

        return array_values(array_filter($files));
    }

    private function getCategoryImages(string $categoryKey, array $fallback): array
    {
        $path = public_path('assets/img/product/freepik/' . $categoryKey);
        $images = $this->globImages($path);
        return !empty($images) ? $images : $fallback;
    }

    private function getCategoryGalleryImages(string $categoryKey, array $fallback): array
    {
        $path = public_path('assets/img/product/freepik/' . $categoryKey . '/gallery');
        $images = $this->globImages($path);
        return !empty($images) ? $images : $fallback;
    }

    private function pickRandom(array $items, int $count): array
    {
        if (count($items) <= $count) {
            return $items;
        }

        $keys = array_rand($items, $count);
        $keys = is_array($keys) ? $keys : [$keys];

        return array_map(fn($k) => $items[$k], $keys);
    }

    private function cartesian(array $arrays): array
    {
        return array_reduce($arrays, function ($acc, $curr) {
            $res = [];
            foreach ($acc as $a) {
                foreach ($curr as $b) {
                    $res[] = array_merge($a, [$b]);
                }
            }
            return $res;
        }, [[]]);
    }

    private function cleanupTestProducts(): void
    {
        $products = Product::where('sku', 'like', 'TEST-%')->get();
        if ($products->isEmpty()) {
            return;
        }

        $variantDir = public_path('assets/img/product/variant/');

        foreach ($products as $product) {
            $variants = ProductVariant::where('product_id', $product->id)->get(['id', 'image']);
            $variantIds = $variants->pluck('id');

            foreach ($variants as $variant) {
                if (!empty($variant->image)) {
                    $path = $variantDir . $variant->image;
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                }
            }

            if ($variantIds->isNotEmpty()) {
                ProductVariantValue::whereIn('variant_id', $variantIds)->delete();
            }
            ProductVariant::where('product_id', $product->id)->delete();

            $optionIds = ProductOption::where('product_id', $product->id)->pluck('id');
            if ($optionIds->isNotEmpty()) {
                ProductOptionValue::whereIn('product_option_id', $optionIds)->delete();
            }
            ProductOption::where('product_id', $product->id)->delete();

            ProductContent::where('product_id', $product->id)->delete();
            SliderImage::where('item_id', $product->id)
                ->where('item_type', 'product')
                ->delete();

            $product->delete();
        }
    }

    private function copyVariantImage(array $thumbFiles, string $variantDir): ?string
    {
        if (empty($thumbFiles)) {
            return null;
        }

        $src = $thumbFiles[array_rand($thumbFiles)];
        $ext = pathinfo($src, PATHINFO_EXTENSION);
        $filename = uniqid('variant_', true) . '.' . $ext;
        @copy($src, $variantDir . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    private function formatSerial(int $value): string
    {
        return str_pad((string)$value, 6, '0', STR_PAD_LEFT);
    }
}
