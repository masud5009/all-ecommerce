<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Coupon') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.product.coupon_store') }}" method="post">
                    @csrf
                    <div class="row">
                        <x-text-input col="6" placeholder="Enter coupon name" name="name" type="text"
                            label="Name" required="*" action="store" />
                        <x-text-input col="6" placeholder="Enter coupon code" name="code" type="text"
                            label="Code" required="*" action="store" />

                        @php
                            $options = ['fixed' => 'Fixed', 'percentage' => 'Percentage'];
                        @endphp
                        <x-text-input col="6" placeholder="Select a Type" name="type" type="custom-select"
                            label="Type" required="*" :dataInfo="$options" action="store" />

                        <x-text-input col="6" placeholder="Enter coupon value" name="value" type="number"
                            label="Value" required="*" action="store" />

                        <x-text-input col="6" placeholder="Enter start date" name="start_date" type="date"
                            label="Start Date" required="*" action="store" />

                        <x-text-input col="6" placeholder="Enter end date" name="end_date" type="date"
                            label="End Date" required="*" action="store" />

                        <x-text-input col="12" placeholder="Enter minimum spend amount" name="amount_spend"
                            type="number" label="Minimum Spend" required="*" action="store" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
