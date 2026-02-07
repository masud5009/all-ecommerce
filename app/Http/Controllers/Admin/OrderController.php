<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CheckoutController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Shop\ProductService;
use App\Services\Shipping\StedfastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function create()
    {
        return view('admin.sales.create');
    }

    public function searchItems(Request $request)
    {
        $term = trim((string)($request->input('q') ?? $request->input('term', '')));
        $page = max(1, (int)$request->input('page', 1));
        $perPage = 10;

        $defaultLang = app('defaultLang');

        $productsQuery = Product::query()
            ->with(['content' => function ($q) use ($defaultLang) {
                $q->where('language_id', $defaultLang->id);
            }])
            ->where('status', 1)
            ->where('has_variants', 0)
            ->when($term !== '', function ($q) use ($term, $defaultLang) {
                $q->where(function ($sub) use ($term, $defaultLang) {
                    $sub->where('sku', 'like', '%' . $term . '%')
                        ->orWhereHas('content', function ($cq) use ($term, $defaultLang) {
                            $cq->where('language_id', $defaultLang->id)
                                ->where('title', 'like', '%' . $term . '%');
                        });
                });
            });

        $variantsQuery = ProductVariant::query()
            ->with([
                'product.content' => function ($q) use ($defaultLang) {
                    $q->where('language_id', $defaultLang->id);
                },
                'variantValues.optionValue.option',
            ])
            ->where('status', 1)
            ->whereHas('product', function ($q) {
                $q->where('status', 1);
            })
            ->when($term !== '', function ($q) use ($term, $defaultLang) {
                $q->where(function ($sub) use ($term, $defaultLang) {
                    $sub->where('sku', 'like', '%' . $term . '%')
                        ->orWhereHas('product', function ($pq) use ($term, $defaultLang) {
                            $pq->where('sku', 'like', '%' . $term . '%')
                                ->orWhereHas('content', function ($cq) use ($term, $defaultLang) {
                                    $cq->where('language_id', $defaultLang->id)
                                        ->where('title', 'like', '%' . $term . '%');
                                });
                        });
                });
            });

        $totalVariants = (clone $variantsQuery)->count();
        $totalProducts = (clone $productsQuery)->count();
        $total = $totalVariants + $totalProducts;
        $offset = ($page - 1) * $perPage;

        $variants = collect();
        $products = collect();

        if ($offset < $totalVariants) {
            $variants = (clone $variantsQuery)
                ->orderBy('id', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();

            $remaining = $perPage - $variants->count();
            if ($remaining > 0) {
                $products = (clone $productsQuery)
                    ->orderBy('id', 'desc')
                    ->skip(0)
                    ->take($remaining)
                    ->get();
            }
        } else {
            $productOffset = $offset - $totalVariants;
            $products = (clone $productsQuery)
                ->orderBy('id', 'desc')
                ->skip($productOffset)
                ->take($perPage)
                ->get();
        }

        $results = [];

        foreach ($variants as $variant) {
            $product = $variant->product;
            $title = optional($product->content->first())->title ?? ('Product #' . $product->id);
            $variantLabel = $this->buildVariantLabel($variant);
            $text = $title . ' - ' . $variantLabel . ' (SKU: ' . ($variant->sku ?? 'N/A') . ')';
            $available = (int)$variant->track_serial === 1
                ? ProductService::getVariantAvailableStock($variant->id)
                : (int)$variant->stock;
            $price = $variant->price !== null ? (float)$variant->price : (float)($product->current_price ?? 0);

            $results[] = [
                'id' => 'variant-' . $variant->id,
                'text' => $text,
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'price' => $price,
                'available' => $available,
                'track_serial' => (int)$variant->track_serial,
                'type' => strtolower((string)$product->type),
            ];
        }

        foreach ($products as $product) {
            $title = optional($product->content->first())->title ?? ('Product #' . $product->id);
            $sku = $product->sku ?? 'N/A';
            $available = strtolower((string)$product->type) === 'physical' ? (int)$product->stock : null;

            $results[] = [
                'id' => 'product-' . $product->id,
                'text' => $title . ' (SKU: ' . $sku . ')',
                'product_id' => $product->id,
                'variant_id' => null,
                'price' => (float)($product->current_price ?? 0),
                'available' => $available,
                'track_serial' => 0,
                'type' => strtolower((string)$product->type),
            ];
        }

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($offset + $perPage) < $total,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => ['required', 'string', 'max:255'],
            'billing_email' => ['required', 'email', 'max:255'],
            'billing_phone' => ['required', 'string', 'max:255'],
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_city' => ['nullable', 'string', 'max:255'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
            'delivery_date' => ['nullable', 'date'],
            'payment_method' => ['required', 'string', 'max:255'],
            'gateway' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', Rule::in(['pending', 'completed', 'rejected'])],
            'order_status' => ['required', Rule::in(['pending', 'completed', 'rejected'])],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'shipping_charge' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        $items = $request->input('items', []);
        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', __('Please add at least one item.'));
        }

        $cartTotal = 0;
        foreach ($items as $item) {
            $qty = (int)($item['qty'] ?? 0);
            $price = (float)($item['price'] ?? 0);
            $cartTotal += $qty * $price;
        }

        $discount = (float)($request->discount_amount ?? 0);
        $tax = (float)($request->tax_amount ?? 0);
        $shipping = (float)($request->shipping_charge ?? 0);
        $payAmount = ($cartTotal - $discount) + $tax + $shipping;

        if ($payAmount < 0) {
            return redirect()->back()->withInput()->with('error', __('Total amount cannot be negative.'));
        }

        $settings = DB::table('settings')
            ->select('currency_symbol', 'currency_symbol_position', 'currency_text', 'currency_text_position')
            ->first();

        DB::beginTransaction();
        try {
            $hasPhysical = false;
            $order = Order::create([
                'order_number' => generateOrderNumber(8),
                'billing_name' => $request->billing_name,
                'billing_email' => $request->billing_email,
                'billing_phone' => $request->billing_phone,
                'billing_address' => $request->billing_address,
                'billing_city' => $request->billing_city,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'gateway' => $request->gateway,
                'cart_total' => $cartTotal,
                'pay_amount' => $payAmount,
                'discount_amount' => $discount,
                'tax' => $tax,
                'shipping_charge' => $shipping,
                'currency_symbol' => $settings->currency_symbol ?? '',
                'currency_symbol_position' => $settings->currency_symbol_position ?? 'left',
                'currency_text' => $settings->currency_text ?? '',
                'currency_text_position' => $settings->currency_text_position ?? 'left',
                'payment_status' => $request->payment_status,
                'order_status' => $request->order_status,
                'receipt' => null,
                'delivery_date' => $request->delivery_date,
                'invoice_number' => null,
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int)$item['qty'];
                $price = (float)$item['price'];
                $variant = null;
                $variations = [];
                $hasPhysical = $hasPhysical || strtolower((string)$product->type) === 'physical';

                if (!empty($item['variant_id'])) {
                    $variant = ProductVariant::with('variantValues.optionValue.option')
                        ->findOrFail($item['variant_id']);

                    if ((int)$variant->product_id !== (int)$product->id) {
                        throw new \Exception(__('Selected variant does not belong to the product.'));
                    }

                    if ((int)$variant->status !== 1) {
                        throw new \Exception(__('Selected variant is inactive.'));
                    }

                    $variations = $this->buildVariationsPayload($product->id, $variant, $qty);
                }

                if ((int)$product->has_variants === 1 && !$variant) {
                    throw new \Exception(__('Please select a variant for this product.'));
                }

                if ((int)$product->has_variants !== 1 && $variant) {
                    throw new \Exception(__('This product does not support variants.'));
                }

                if (strtolower((string)$product->type) === 'physical') {
                    if ($variant) {
                        if ((int)$variant->track_serial === 1) {
                            $available = ProductService::getVariantAvailableStock($variant->id);
                            if ($available < $qty) {
                                throw new \Exception(__('Not enough serial stock available.'));
                            }
                        } else {
                            if ((int)$variant->stock < $qty) {
                                throw new \Exception(__('Not enough variant stock available.'));
                            }
                            $variant->stock = (int)$variant->stock - $qty;
                            $variant->save();
                        }
                    } else {
                        if ((int)$product->stock < $qty) {
                            throw new \Exception(__('Not enough product stock available.'));
                        }
                        $product->stock = (int)$product->stock - $qty;
                        $product->save();
                    }
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'customer_id' => null,
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'product_price' => $price,
                    'qty' => $qty,
                    'variations' => json_encode($variations),
                ]);

                if ($variant && (int)$variant->track_serial === 1) {
                    ProductService::allocateSerialsForOrderItem($variant->id, $orderItem->id, $qty);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        try {
            CheckoutController::generateInvoice($order);
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.sales.details', ['id' => $order->id])
                ->with('warning', __('Order created but invoice generation failed.'));
        }

        if ($order->payment_status === 'completed') {
            TransactionController::product_purchase($order->fresh());
        }

        $stedfastResult = StedfastService::createConsignment($order->fresh(), $hasPhysical);
        $redirect = redirect()
            ->route('admin.sales.details', ['id' => $order->id])
            ->with('success', __('Order created successfully.'));

        if (($stedfastResult['status'] ?? null) === 'error') {
            $warning = __('Order created but Stedfast booking failed.');
            if (!empty($stedfastResult['message'])) {
                $warning .= ' ' . $stedfastResult['message'];
            }
            return $redirect->with('warning', $warning);
        }

        return $redirect;
    }

    private function buildVariationsPayload(int $productId, ProductVariant $variant, int $qty): array
    {
        return $variant->variantValues
            ->sortBy(function ($variantValue) {
                return optional(optional($variantValue->optionValue)->option)->position ?? 0;
            })
            ->map(function ($variantValue, $index) use ($productId, $qty) {
                $option = optional($variantValue->optionValue)->option;
                $value = optional($variantValue->optionValue)->value;

                if (!$option || $value === null) {
                    return null;
                }

                return [
                    'product_id' => $productId,
                    'variation_id' => $variantValue->option_value_id ?? null,
                    'variation_name' => $option->name,
                    'option_name' => $value,
                    'price' => 0,
                    'option_key' => $index,
                    'qty' => $qty,
                ];
            })
            ->filter()
            ->values()
            ->toArray();
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
