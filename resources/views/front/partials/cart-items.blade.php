@forelse ($items as $item)
    <div class="flex gap-4 border-b border-green-100 pb-4" data-cart-item="{{ $item['id'] }}">
        <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-green-100 bg-white">
            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
        </div>
        <div class="flex flex-1 flex-col">
            <div class="flex justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 line-clamp-1">{{ $item['name'] }}</h4>
                    @if (!empty($item['variant_label']))
                        <p class="text-xs text-slate-500">{{ $item['variant_label'] }}</p>
                    @endif
                </div>
                <button type="button" class="text-slate-400 hover:text-red-500 transition"
                    data-remove-item="{{ $item['id'] }}" aria-label="Remove item">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-auto flex items-center justify-between">
                <div class="inline-flex items-center rounded-lg border border-green-200 bg-white">
                    <button type="button"
                        class="flex h-8 w-8 items-center justify-center text-slate-600 hover:bg-green-50 hover:text-green-700 rounded-l-lg"
                        data-qty-dec="{{ $item['id'] }}" aria-label="Decrease">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 12h14"></path>
                        </svg>
                    </button>
                    <span class="w-8 text-center text-sm font-semibold" data-qty-val="{{ $item['id'] }}">
                        {{ $item['quantity'] }}
                    </span>
                    <button type="button"
                        class="flex h-8 w-8 items-center justify-center text-slate-600 hover:bg-green-50 hover:text-green-700 rounded-r-lg"
                        data-qty-inc="{{ $item['id'] }}" aria-label="Increase">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm font-semibold text-green-700" data-item-subtotal="{{ $item['id'] }}">
                    {{ currency_symbol($item['subtotal']) }}
                </p>
            </div>
        </div>
    </div>
@empty
    {{-- Empty handled by parent --}}
@endforelse
