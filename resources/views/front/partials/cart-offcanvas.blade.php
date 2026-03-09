{{-- Cart Offcanvas Backdrop --}}
<div data-cart-backdrop
    class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm transition-opacity hidden"
    aria-hidden="true"></div>

{{-- Cart Offcanvas --}}
<aside data-cart-offcanvas
    class="fixed right-0 top-0 z-50 h-full w-full max-w-md transform translate-x-full bg-white shadow-2xl transition-transform duration-300 ease-out flex flex-col"
    aria-label="Shopping Cart">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-green-100 px-5 py-4">
        <h2 class="text-lg font-semibold text-slate-900">Your Cart</h2>
        <button type="button" data-cart-close
            class="flex h-10 w-10 items-center justify-center rounded-full text-slate-500 transition hover:bg-green-50 hover:text-green-700"
            aria-label="Close cart">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{-- Cart Empty State --}}
    <div data-cart-empty class="flex flex-1 flex-col items-center justify-center p-6 text-center hidden">
        <div class="mb-4 rounded-full bg-green-100 p-4">
            <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M6 7h12l1 12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L6 7Z"></path>
                <path d="M9 7V6a3 3 0 0 1 6 0v1"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-slate-900">Your cart is empty</h3>
        <p class="mt-1 text-sm text-slate-500">Start shopping to add items to your cart</p>
        <a href="{{ route('frontend.shop') }}"
            class="mt-4 inline-flex items-center rounded-full bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700"
            data-cart-close>
            Browse Products
        </a>
    </div>

    {{-- Cart Items List --}}
    <div data-cart-list class="flex-1 overflow-y-auto p-5 hidden">
        <div data-cart-items class="space-y-4">
            {{-- Cart items will be rendered here by JS --}}
        </div>
    </div>

    {{-- Cart Footer --}}
    <div data-cart-footer class="border-t border-green-100 bg-green-50/50 p-5 hidden">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-slate-600">Subtotal</span>
            <span class="text-lg font-semibold text-slate-900" data-cart-total>৳0.00</span>
        </div>
        <div class="space-y-2">
            <a href="{{ route('frontend.checkout.view') }}"
                class="flex w-full items-center justify-center rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7"></path>
                </svg>
                Proceed to Checkout
            </a>
            <button type="button" data-cart-close
                class="flex w-full items-center justify-center rounded-2xl border border-green-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-green-300 hover:text-green-700">
                Continue Shopping
            </button>
        </div>
    </div>
</aside>
