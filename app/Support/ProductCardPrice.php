<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductCardPrice
{
    public static function activeVariants(Product $product, $variants = null): Collection
    {
        $source = $variants;

        if ($source === null) {
            if ($product->relationLoaded('variations')) {
                $source = $product->variations;
            } elseif ($product->relationLoaded('variants')) {
                $source = $product->variants;
            } else {
                $source = collect();
            }
        }

        return collect($source)
            ->filter(function ($variant) {
                return (int) ($variant->status ?? 1) === 1;
            })
            ->values();
    }

    public static function selectedVariant(Product $product, $variants = null)
    {
        return self::activeVariants($product, $variants)
            ->first(function ($variant) {
                return (int) ($variant->show_on_card_price ?? 0) === 1;
            });
    }

    public static function selectedVariantId(Product $product, $variants = null): ?int
    {
        $selectedVariant = self::selectedVariant($product, $variants);

        return $selectedVariant ? (int) $selectedVariant->id : null;
    }

    public static function build(Product $product, bool $isFlashSaleActive = false, float $discountPercent = 0, $variants = null): array
    {
        $activeVariants = self::activeVariants($product, $variants);
        $discountPercent = min(max($discountPercent, 0), 100);

        $hasVariants = (int) ($product->has_variants ?? 0) === 1 && $activeVariants->isNotEmpty();

        if (!$hasVariants) {
            $basePrice = (float) ($product->current_price ?? 0);
            $currentPrice = self::applyDiscount($basePrice, $isFlashSaleActive, $discountPercent);
            $oldPrice = $isFlashSaleActive ? $basePrice : (float) ($product->previous_price ?? 0);

            return [
                'mode' => 'single',
                'variants' => $activeVariants,
                'selected_variant' => null,
                'selected_variant_id' => null,
                'current_price' => $currentPrice,
                'old_price' => $oldPrice,
                'show_old_price' => $oldPrice > $currentPrice,
                'current_min_price' => $currentPrice,
                'current_max_price' => $currentPrice,
                'old_min_price' => $oldPrice,
                'old_max_price' => $oldPrice,
                'show_old_range' => false,
            ];
        }

        $basePrices = $activeVariants
            ->map(function ($variant) use ($product) {
                return self::baseVariantPrice($variant, $product);
            })
            ->values();

        $baseMinPrice = (float) ($basePrices->min() ?? 0);
        $baseMaxPrice = (float) ($basePrices->max() ?? 0);
        $currentMinPrice = self::applyDiscount($baseMinPrice, $isFlashSaleActive, $discountPercent);
        $currentMaxPrice = self::applyDiscount($baseMaxPrice, $isFlashSaleActive, $discountPercent);

        $selectedVariant = self::selectedVariant($product, $activeVariants);

        if ($selectedVariant) {
            $basePrice = self::baseVariantPrice($selectedVariant, $product);
            $currentPrice = self::applyDiscount($basePrice, $isFlashSaleActive, $discountPercent);
            $oldPrice = $isFlashSaleActive ? $basePrice : null;

            return [
                'mode' => 'selected_variant',
                'variants' => $activeVariants,
                'selected_variant' => $selectedVariant,
                'selected_variant_id' => (int) $selectedVariant->id,
                'current_price' => $currentPrice,
                'old_price' => $oldPrice,
                'show_old_price' => $oldPrice !== null && $oldPrice > $currentPrice,
                'current_min_price' => $currentMinPrice,
                'current_max_price' => $currentMaxPrice,
                'old_min_price' => $isFlashSaleActive ? $baseMinPrice : null,
                'old_max_price' => $isFlashSaleActive ? $baseMaxPrice : null,
                'show_old_range' => false,
            ];
        }

        return [
            'mode' => 'range',
            'variants' => $activeVariants,
            'selected_variant' => null,
            'selected_variant_id' => null,
            'current_price' => $currentMinPrice,
            'old_price' => $isFlashSaleActive ? $baseMinPrice : null,
            'show_old_price' => false,
            'current_min_price' => $currentMinPrice,
            'current_max_price' => $currentMaxPrice,
            'old_min_price' => $isFlashSaleActive ? $baseMinPrice : null,
            'old_max_price' => $isFlashSaleActive ? $baseMaxPrice : null,
            'show_old_range' => $isFlashSaleActive,
        ];
    }

    public static function prioritizeSelectedVariant(array $units, ?int $selectedVariantId): array
    {
        if (!$selectedVariantId) {
            return array_values($units);
        }

        return collect($units)
            ->sortByDesc(function ($unit) use ($selectedVariantId) {
                return (int) ($unit['variant_id'] ?? 0) === $selectedVariantId;
            })
            ->values()
            ->all();
    }

    public static function formatSinglePrice(array $priceData): string
    {
        return currency_symbol((float) ($priceData['current_price'] ?? 0));
    }

    public static function formatRange(array $priceData, bool $useOldRange = false): string
    {
        $minKey = $useOldRange ? 'old_min_price' : 'current_min_price';
        $maxKey = $useOldRange ? 'old_max_price' : 'current_max_price';

        $minPrice = (float) ($priceData[$minKey] ?? 0);
        $maxPrice = (float) ($priceData[$maxKey] ?? 0);

        if ($minPrice === $maxPrice) {
            return currency_symbol($minPrice);
        }

        return currency_symbol($minPrice) . ' - ' . currency_symbol($maxPrice);
    }

    private static function baseVariantPrice($variant, Product $product): float
    {
        return (float) ($variant->price ?? ($product->current_price ?? 0));
    }

    private static function applyDiscount(float $price, bool $isFlashSaleActive, float $discountPercent): float
    {
        if (!$isFlashSaleActive) {
            return $price;
        }

        return max($price * (1 - ($discountPercent / 100)), 0);
    }
}
