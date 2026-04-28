<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Product;
use App\Models\LandingPage;
use App\Models\ProductContent;
use App\Support\ProductCardPrice;
use App\Http\Controllers\Controller;

class LandingPageController extends Controller
{
    private array $viewMap = [
        'theme_one' => 'landing-page.theme-one',
        'theme_two' => 'landing-page.theme-two',
    ];

    public function show(string $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $pageData = $landingPage->content ?? [];

        $view = $this->viewMap[$landingPage->template] ?? null;
        if (empty($view)) {
            abort(404);
        }

        return view($view, [
            'landingPage' => $landingPage,
            'pageData' => $pageData,
        ] + $this->selectedProductData($pageData));
    }

    private function selectedProductData(array $pageData): array
    {
        $data = [
            'selectedProduct' => null,
            'selectedProductTitle' => null,
            'selectedProductSummary' => null,
            'selectedProductImage' => null,
            'selectedProductPriceLabel' => null,
            'selectedProductOldPriceLabel' => null,
            'selectedProductSaveLabel' => null,
            'selectedProductCtaText' => null,
        ];

        if (empty($pageData['product_id'])) {
            return $data;
        }

        $product = Product::with('variants')->find($pageData['product_id']);
        if (empty($product)) {
            return $data;
        }

        $content = $this->productContent((int) $product->id);
        $priceData = ProductCardPrice::build($product, false, 0, $product->variants);
        $priceLabel = ProductCardPrice::formatRange($priceData);
        $oldPriceLabel = null;
        $saveLabel = null;

        if (!empty($priceData['show_old_price'])) {
            $oldPriceLabel = currency_symbol((float) ($priceData['old_price'] ?? 0));
            $saveAmount = (float) ($priceData['old_price'] ?? 0) - (float) ($priceData['current_price'] ?? 0);

            if ($saveAmount > 0) {
                $saveLabel = __('Save') . ' ' . currency_symbol($saveAmount) . ' ' . __('today');
            }
        } elseif (!empty($priceData['show_old_range'])) {
            $oldPriceLabel = ProductCardPrice::formatRange($priceData, true);
        }

        $summary = trim(strip_tags($content->summary ?? ''));
        if ($summary === '') {
            $summary = trim(strip_tags($content->description ?? ''));
        }

        return [
            'selectedProduct' => $product,
            'selectedProductTitle' => $content->title ?? __('Product') . ' #' . $product->id,
            'selectedProductSummary' => $summary,
            'selectedProductImage' => !empty($product->thumbnail) ? 'assets/img/product/' . $product->thumbnail : null,
            'selectedProductPriceLabel' => $priceLabel,
            'selectedProductOldPriceLabel' => $oldPriceLabel,
            'selectedProductSaveLabel' => $saveLabel,
            'selectedProductCtaText' => __('Order Now') . ' - ' . $priceLabel,
        ];
    }

    private function productContent(int $productId): ?ProductContent
    {
        $languageId = app('defaultLang')->id ?? null;
        $query = ProductContent::where('product_id', $productId);

        if (!empty($languageId)) {
            $query->where('language_id', $languageId);
        }

        $content = $query->first();

        return $content ?: ProductContent::where('product_id', $productId)->first();
    }
}
