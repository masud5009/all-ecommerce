<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ProductVariantSerialBatch;
use App\Models\ProductVariantSoldSerial;
use App\Models\ProductVariantValue;
use App\Services\Shop\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VariantController extends Controller
{
    public function restockForm(Request $request)
    {
        $defaultLang = app('defaultLang');
        $variants = $this->getSerialTrackedVariants($defaultLang->id);
        $selectedVariantId = old('variant_id', $request->input('variant_id'));

        $selectedVariant = null;
        $recentBatches = collect();
        $lastSerialEnd = null;
        $suggestedStart = null;
        $selectedVariantLabel = null;

        if ($selectedVariantId) {
            $selectedVariant = ProductVariant::with([
                'product.content' => function ($q) use ($defaultLang) {
                    $q->where('language_id', $defaultLang->id);
                },
                'variantValues.optionValue.option',
                'serialBatches' => function ($q) {
                    $q->orderBy('id', 'desc');
                },
            ])->where('track_serial', 1)->find($selectedVariantId);

            if ($selectedVariant) {
                $recentBatches = $selectedVariant->serialBatches->take(10);
                $lastBatch = $selectedVariant->serialBatches->first();

                if ($lastBatch) {
                    $lastSerialEnd = $lastBatch->serial_end;
                    $suggestedStart = ProductService::suggestNextSerialStart($lastSerialEnd);
                }

                $selectedVariantLabel = $this->buildVariantLabel($selectedVariant);
            }
        }

        return view('admin.product.variant-restock', [
            'variants' => $variants,
            'selectedVariantId' => $selectedVariantId,
            'selectedVariant' => $selectedVariant,
            'selectedVariantLabel' => $selectedVariantLabel,
            'recentBatches' => $recentBatches,
            'lastSerialEnd' => $lastSerialEnd,
            'suggestedStart' => $suggestedStart,
        ]);
    }

    public function restock(Request $request)
    {
        $request->validate([
            'variant_id' => [
                'required',
                'integer',
                Rule::exists('product_variants', 'id')->where('track_serial', 1),
            ],
            'qty' => ['required', 'integer', 'min:1'],
            'serial_start' => ['required', 'string', 'regex:/^\d+$/'],
            'serial_end' => ['required', 'string', 'regex:/^\d+$/'],
        ], [
            'serial_start.regex' => __('Serial start must be numeric.'),
            'serial_end.regex' => __('Serial end must be numeric.'),
        ]);

        try {
            ProductService::restockVariantWithBatch(
                (int)$request->variant_id,
                (string)$request->serial_start,
                (string)$request->serial_end,
                (int)$request->qty
            );

            return redirect()
                ->back()
                ->with('success', __('Restock completed successfully.'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage() ?: __('Restock failed.'));
        }
    }

    public function show($id)
    {
        $defaultLang = app('defaultLang');

        $variant = ProductVariant::with([
            'product.content' => function ($q) use ($defaultLang) {
                $q->where('language_id', $defaultLang->id);
            },
            'variantValues.optionValue.option',
            'serialBatches' => function ($q) {
                $q->orderBy('id', 'desc');
            },
        ])->findOrFail($id);

        $productTitle = optional($variant->product->content->first())->title
            ?? ('Product #' . $variant->product_id);

        $variantLabel = $this->buildVariantLabel($variant);
        $availableStock = ProductService::getVariantAvailableStock($variant->id);

        return view('admin.product.variant-details', [
            'variant' => $variant,
            'productTitle' => $productTitle,
            'variantLabel' => $variantLabel,
            'availableStock' => $availableStock,
        ]);
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,id',
        ]);

        $variant = ProductVariant::findOrFail((int)$validated['variant_id']);
        $productId = (int)$variant->product_id;

        DB::beginTransaction();
        try {
            $hasOrderHistory = OrderItem::where('variant_id', $variant->id)->exists();
            $hasSoldSerialHistory = ProductVariantSoldSerial::where('variant_id', $variant->id)->exists();

            if ($hasOrderHistory || $hasSoldSerialHistory) {
                DB::rollBack();
                return redirect()->back()->with('error', __('This variant has order history and cannot be removed.'));
            }

            if (!empty($variant->image)) {
                @unlink(public_path('assets/img/product/variant/') . $variant->image);
            }

            Cart::where('variant_id', $variant->id)->delete();

            ProductVariantSerialBatch::where('variant_id', $variant->id)->delete();
            ProductVariantValue::where('variant_id', $variant->id)->delete();
            $variant->delete();

            DB::commit();
            return redirect()->route('admin.product.edit', ['id' => $productId])
                ->with('success', __('Variant removed successfully.'));
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage() ?: __('Failed to remove variant.'));
        }
    }

    private function getSerialTrackedVariants(int $languageId)
    {
        $variants = ProductVariant::with([
            'product.content' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            },
            'variantValues.optionValue.option',
        ])
            ->where('track_serial', 1)
            ->orderBy('id', 'desc')
            ->get();

        $variants->each(function ($variant) {
            $productTitle = optional($variant->product->content->first())->title
                ?? ('Product #' . $variant->product_id);
            $variantLabel = $this->buildVariantLabel($variant);
            $skuLabel = $variant->sku ? ('SKU: ' . $variant->sku) : __('SKU: N/A');
            $statusLabel = (int)$variant->status === 1 ? __('Active') : __('Inactive');

            $variant->display_name = $productTitle . ' - ' . $variantLabel . ' (' . $skuLabel . ', ' . $statusLabel . ')';
        });

        return $variants;
    }

    private function buildVariantLabel(ProductVariant $variant): string
    {
        $parts = $variant->variantValues
            ->sortBy(function ($variantValue) {
                return optional(optional($variantValue->optionValue)->option)->position ?? 0;
            })
            ->map(function ($variantValue) {
                $option = optional($variantValue->optionValue)->option;
                $value = optional($variantValue->optionValue)->value;

                if (!$option || $value === null) {
                    return null;
                }

                return $option->name . ': ' . $value;
            })
            ->filter()
            ->values();

        return $parts->isNotEmpty() ? $parts->implode(', ') : __('Default');
    }
}
