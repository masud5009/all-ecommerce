@if (count($products) == 0)
    <div class="text-center">
        <h5 class="w-100 mb-3">{{ __('NO PRODUCT FOUND') }}</h5>
        <i class="fas fa-sad-tear fs-1" aria-hidden="true"></i>
    </div>
@else
    @foreach ($products as $product)
        <div class="col-lg-3">
            <div class="product-item" data-id="{{ $product->id }}" data-name="{{ $product->title }}"
                data-price="{{ $product->price }}">
                <div class="product-image">
                    <img src="{{ asset('assets/img/product/' . $product->thumbnail) }}" alt="{{ $product->title }}">
                    <div class="hover-icon" data-toggle="tooltip" data-placement="top"
                        data-title="{{ truncateString($product->title, 30) }}" data-id="{{ $product->id }}"
                        title="Add to Cart" data-variation="{{ check_variation($product->id) > 0 ? 'yes' : 'null' }}"
                        data-id="{{ $product->id }}">
                        <i class="fas fa-cart-plus"></i>
                    </div>
                </div>
                <p class="title">{{ truncateString($product->title, 25) }}</p>
                <span class="price">{{ currency_symbol($product->current_price) }}</span>
            </div>
        </div>
    @endforeach
@endif
