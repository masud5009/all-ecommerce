<?php

namespace App\Services\Frontend;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Admin\Language;

class ProductService
{
    /**
     * Get featured products for the frontend
     */
    public static function getHomeFeaturedProducts($languageId = null)
    {
        return Product::with([
            'content' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            },
            'variations.variantValues.optionValue.option'
        ])
            ->where('featured', 1)
            ->whereHas('content', function ($query) use ($languageId) {
                $query->where('language_id', $languageId)
                    ->whereIn('category_id', function ($categoryQuery) {
                        $categoryQuery->select('id')
                            ->from('product_categories')
                            ->where('status', 1);
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }


    public static function getHomeFlashSaleProducts($languageId = null)
    {
        $now = Carbon::now();

        return Product::with([
            'content' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            },
            'variations.variantValues.optionValue.option'
        ])
            ->where('flash_sale_status', 1)
            ->whereNotNull('flash_sale_price')
            ->where('flash_sale_price', '>', 0)
            ->whereNotNull('flash_sale_start_at')
            ->whereNotNull('flash_sale_end_at')
            ->where('flash_sale_start_at', '<=', $now)
            ->where('flash_sale_end_at', '>=', $now)
            ->whereHas('content', function ($query) use ($languageId) {
                $query->where('language_id', $languageId)
                    ->whereIn('category_id', function ($categoryQuery) {
                        $categoryQuery->select('id')
                            ->from('product_categories')
                            ->where('status', 1);
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Latest edit: Refactored to remove redundant code and ensure consistent data retrieval for featured and flash sale products.
     */
    public static function latestHomeProducts($languageId = null)
    {
        return Product::with([
            'content' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            },
            'variations.variantValues.optionValue.option'
        ])
            ->whereHas('content', function ($query) use ($languageId) {
                $query->where('language_id', $languageId)
                    ->whereIn('category_id', function ($categoryQuery) {
                        $categoryQuery->select('id')
                            ->from('product_categories')
                            ->where('status', 1);
                    });
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    /**
     * Get all products for shop page with filtering and pagination.
     */
    public static function getShopProducts($languageId = null, array $filters = [])
    {
        $query = Product::with([
            'content' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            },
            'variations.variantValues.optionValue.option'
        ])
            ->where('status', 1)
            ->whereHas('content', function ($q) use ($languageId) {
                $q->where('language_id', $languageId)
                    ->whereIn('category_id', function ($categoryQuery) {
                        $categoryQuery->select('id')
                            ->from('product_categories')
                            ->where('status', 1);
                    });
            });

        // Category filter
        if (!empty($filters['category'])) {
            $query->whereHas('content', function ($q) use ($filters, $languageId) {
                $q->where('language_id', $languageId)
                    ->where('category_id', $filters['category']);
            });
        }

        // Search filter
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->whereHas('content', function ($q) use ($searchTerm, $languageId) {
                $q->where('language_id', $languageId)
                    ->where(function ($q2) use ($searchTerm) {
                        $q2->where('title', 'like', "%{$searchTerm}%")
                            ->orWhere('summary', 'like', "%{$searchTerm}%")
                            ->orWhere('description', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Price filter
        if (!empty($filters['min_price'])) {
            $query->where('current_price', '>=', (float) $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('current_price', '<=', (float) $filters['max_price']);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('current_price', 'asc');
                break;
            case 'price_high':
                $query->orderByDesc('current_price');
                break;
            case 'name_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'name_desc':
                $query->orderByDesc('id');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        return $query->paginate(12);
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

                $flashDiscountPercent = (float) ($product->flash_sale_price ?? 0);

                $isFlashSaleActive =
                    (int) ($product->flash_sale_status ?? 0) === 1 &&
                    $flashDiscountPercent > 0 &&
                    !empty($product->flash_sale_start_at) &&
                    !empty($product->flash_sale_end_at) &&
                    $now->between(
                        Carbon::parse($product->flash_sale_start_at),
                        Carbon::parse($product->flash_sale_end_at)
                    );

                if ($isFlashSaleActive) {
                    $flashDiscountPercent = min($flashDiscountPercent, 100);
                }

                if ((int) ($product->has_variants ?? 0) === 1 && $variations->isNotEmpty()) {
                    $units = $variations
                        ->values()
                        ->map(function ($variation, $index) use ($product, $isFlashSaleActive, $flashDiscountPercent) {
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

                            $baseVariantPrice = (float) ($variation->price ?? $product->current_price ?? 0);

                            return [
                                'label' => $variantParts->isNotEmpty()
                                    ? $variantParts->implode(', ')
                                    : __('Option') . ' ' . ($index + 1),
                                'price' => $isFlashSaleActive
                                    ? max($baseVariantPrice * (1 - ($flashDiscountPercent / 100)), 0)
                                    : $baseVariantPrice,
                                'oldPrice' => $isFlashSaleActive
                                    ? $baseVariantPrice
                                    : (float) ($product->previous_price ?? 0),
                            ];
                        })
                        ->all();

                    $variantBasePrices = $variations->map(function ($variation) use ($product) {
                        return (float) ($variation->price ?? $product->current_price ?? 0);
                    });

                    $minBasePrice = (float) ($variantBasePrices->min() ?? 0);
                    $price = $isFlashSaleActive
                        ? max($minBasePrice * (1 - ($flashDiscountPercent / 100)), 0)
                        : $minBasePrice;
                    $oldPrice = $isFlashSaleActive
                        ? $minBasePrice
                        : (float) ($product->previous_price ?? 0);
                } else {
                    $currentPrice = (float) ($product->current_price ?? 0);

                    $price = $isFlashSaleActive
                        ? max($currentPrice * (1 - ($flashDiscountPercent / 100)), 0)
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
