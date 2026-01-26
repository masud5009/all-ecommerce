<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.shop.shipping_charge_store') }}" method="post">
                    @csrf
                    @foreach ($languages as $lang)
                        <x-text-input col="12" placeholder="Enter title" name="{{ $lang->code }}_title"
                            type="text" label="Title ({{ $lang->name }})" required="*"
                            language="{{ $lang->code }}" action="store" />
                    @endforeach
                    <x-text-input col="12" placeholder="Enter text" name="text" type="textarea" label="Text"
                        required="*" action="store" />

                    <x-text-input col="12" placeholder="Enter charge" name="charge" type="number"
                        label="Charge({{ $websiteInfo->currency_text }})" required="*" action="store" />

                    <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="text"
                        label="Serial Number" required="*" action="store" action="store" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
