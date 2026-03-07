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
            'variations'
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
            'variations'
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
            'variations'
        ])
            ->whereHas('content', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }
}
