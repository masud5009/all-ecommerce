<form action="{{ route('admin.pos_mangment.add_variation_product') }}" method="GET" id="variationsForm">
    <input type="hidden" value="{{ $product->id }}" name="id">
    <div class="row px-3">
        @foreach ($variations as $variation)
            @php
                $optionNames = json_decode($variation->option_name);
                $optionPrices = json_decode($variation->option_price);
                $optionStocks = json_decode($variation->option_stock);
            @endphp
            <div class="col-lg-6 mb-2">
                <h6><strong>{{ $variation->variant_name }}</strong></h6>
                @foreach ($optionNames as $key => $optionName)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="{{ $key }}"
                            name="variations[{{ $variation->id }}]" id="flex_{{ $optionName }}_{{ $key }}">
                        <label class="form-check-label" for="flex_{{ $optionName }}_{{ $key }}">
                            {{ $optionName }} - ${{ $optionPrices[$key] }} (Stock: {{ $optionStocks[$key] }})
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

</form>
