<?php

namespace App\Services\Frontend;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Admin\Language;

class ProductService
{
    /**
     * Get featured products for the frontend, including their category and content for a specific language.
     */
    public static function getHomeFeaturedProducts($languageId = null)
    {
        if (!$languageId) {
            $languageId = Language::where('is_default', 1)->value('id');
        }

        return Product::with([
            'content' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            },
            'variations.variantValues.optionValue.option'
        ])
            ->where('featured', 1)
            ->whereHas('content', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public static function getHomeFlashSaleProducts($languageId = null)
    {
        if (!$languageId) {
            $languageId = Language::where('is_default', 1)->value('id');
        }

        $now = Carbon::now();

        return Product::with([
            'content' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            },
            'variations.variantValues.optionValue.option'
        ])
            ->where('flash_sale_status', 1)
            ->whereNotNull('flash_sale_price')
            ->whereNotNull('flash_sale_start_at')
            ->whereNotNull('flash_sale_end_at')
            ->where('flash_sale_start_at', '<=', $now)
            ->where('flash_sale_end_at', '>=', $now)
            ->whereHas('content', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Latest edit: Refactored to remove redundant code and ensure consistent data retrieval for featured and flash sale products.
     */
    public static function latestHomeProducts($languageId = null)
    {
        if (!$languageId) {
            $languageId = Language::where('is_default', 1)->value('id');
        }

        return Product::with([
            'content' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            },
            'variations.variantValues.optionValue.option'
        ])
            ->whereHas('content', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    /**
     * Normalize products for homepage quick-view modal payload.
     */
    public static function mapHomeProductsForQuickView($products, $badgeLabel = 'Featured')
    {
        $now = Carbon::now();

        return collect($products)
            ->map(function ($product) use ($now, $badgeLabel) {
                $content = $product->content->first();
                $productTitle = $content->title ?? __('Untitled Product');
                $summary = trim(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        strip_tags((string) ($content->summary ?? $content->description ?? 'Featured product from our catalog.'))
                    )
                );

                $thumbnail = !empty($product->thumbnail)
                    ? asset('assets/img/product/' . $product->thumbnail)
                    : null;

                $variations = $product->variations ?? collect();

                $isFlashSaleActive =
                    (int) ($product->flash_sale_status ?? 0) === 1 &&
                    !is_null($product->flash_sale_price) &&
                    !empty($product->flash_sale_start_at) &&
                    !empty($product->flash_sale_end_at) &&
                    $now->between(
                        Carbon::parse($product->flash_sale_start_at),
                        Carbon::parse($product->flash_sale_end_at)
                    );

                if ((int) ($product->has_variants ?? 0) === 1 && $variations->isNotEmpty()) {
                    $units = $variations
                        ->values()
                        ->map(function ($variation, $index) use ($product) {
                            $variantParts = collect($variation->variantValues ?? [])
                                ->sortBy(function ($variantValue) {
                                    return optional(optional($variantValue->optionValue)->option)->position ?? PHP_INT_MAX;
                                })
                                ->map(function ($variantValue) {
                                    $option = optional($variantValue->optionValue)->option;
                                    $value = optional($variantValue->optionValue)->value;

                                    if (!$option || $value === null || $value === '') {
                                        return null;
                                    }

                                    return $option->name . ': ' . $value;
                                })
                                ->filter()
                                ->values();

                            return [
                                'label' => $variantParts->isNotEmpty()
                                    ? $variantParts->implode(', ')
                                    : __('Option') . ' ' . ($index + 1),
                                'price' => (float) ($variation->price ?? $product->current_price ?? 0),
                                'oldPrice' => (float) ($product->previous_price ?? 0),
                            ];
                        })
                        ->all();

                    $price = (float) ($variations->min('price') ?? 0);
                    $oldPrice = (float) ($product->previous_price ?? 0);
                } else {
                    $currentPrice = (float) ($product->current_price ?? 0);
                    $flashSaleAmount = (float) ($product->flash_sale_price ?? 0);

                    $price = $isFlashSaleActive
                        ? max($currentPrice - $flashSaleAmount, 0)
                        : $currentPrice;

                    $oldPrice = $isFlashSaleActive
                        ? $currentPrice
                        : (float) ($product->previous_price ?? 0);

                    $units = [
                        [
                            'label' => '1 unit',
                            'price' => $price,
                            'oldPrice' => $oldPrice,
                        ],
                    ];
                }

                return [
                    'id' => (string) $product->id,
                    'name' => $productTitle,
                    'category' => 'Featured',
                    'rating' => 4.7,
                    'reviews' => 142,
                    'badge' => $isFlashSaleActive ? 'Flash Sale' : $badgeLabel,
                    'image' => $thumbnail,
                    'images' => $thumbnail ? [$thumbnail] : [],
                    'description' => $summary !== '' ? $summary : 'Featured product from our catalog.',
                    'summary' => $summary !== '' ? $summary : 'Featured product from our catalog.',
                    'price' => $price,
                    'oldPrice' => $oldPrice,
                    'units' => $units,
                    'isDeal' => $oldPrice > $price || $isFlashSaleActive,
                ];
            })
            ->filter(function ($item) {
                return !empty($item['image']);
            })
            ->values()
            ->all();
    }
}
