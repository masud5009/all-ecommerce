<?php

namespace App\Services\Frontend;

use App\Models\ProductCategory;

class CategoryService
{
    /**
     * Get active product categories for the frontend,
     *  -including the count of products in each category for a specific language.
     */
    public static function getHomeFeaturedCategories($languageId)
    {
        $categories = ProductCategory::with(['productContent' => function ($query) use ($languageId) {
            $query->where('language_id', $languageId);
        }])
            ->where('status', 1)
            ->when($languageId, function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })
            ->orderByDesc('serial_number')
            ->get();


        return $categories;
    }
}
