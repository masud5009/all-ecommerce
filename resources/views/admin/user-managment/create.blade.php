<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add User') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.user.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <x-text-input col="6" name="name" type="text" label="Full Name" required="*"
                            action="store" />

                        <x-text-input col="6" name="username" type="text" label="Username" required="*"
                            action="store" />

                        <x-text-input col="6" name="email" type="text" label="Email" required="*"
                            action="store" />

                        <x-text-input col="6" name="password" type="password" label="Password" action="store"
                            required="*" />

                        <x-text-input col="6" name="password_confirmation" type="password"
                            label="Password Confirmation" action="store" required="*" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-success" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
