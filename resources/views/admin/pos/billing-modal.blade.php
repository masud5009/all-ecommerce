<div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Billing Details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.pos_mangment.add_billing') }}" method="post">
                    @csrf
                    <div class="row">
                    <x-text-input col="6" placeholder="Enter name" name="billing_name" type="text"
                        label="Name" required="*"  action="store" />
                    <x-text-input col="6" placeholder="Enter phone" name="billing_phone" type="text"
                        label="Phone" required="*"  action="store" />
                    <x-text-input col="6" placeholder="Enter email" name="billing_email" type="text"
                        label="Email" required="*"  action="store" />
                    <x-text-input col="6" placeholder="Enter address" name="billing_address" type="text"
                        label="Address" required="*"  action="store" />
                    <x-text-input col="6" placeholder="Enter city" name="billing_city" type="text"
                        label="City" required="*"  action="store" />
                    <x-text-input col="6" placeholder="Delivery Date" name="delivery_date" type="date"
                        label="City" required="*"  action="store" />
                </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-success" id="submitBtn"> {{ __('Apply Now') }}</button>
            </div>
        </div>
    </div>
</div>
