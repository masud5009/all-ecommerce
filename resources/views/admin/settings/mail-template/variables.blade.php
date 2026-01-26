<div class="col-lg-5 mail-variable">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">{{ __('Variable') }}</th>
                <th scope="col">{{ __('Preview') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{customer_name}</td>
                <td>Customer name/username</td>
            </tr>
            @if ($data->type == 'verify_email')
                <tr>
                    <td>{verification_link}</td>
                    <td>{{ __('Email Verification Link') }}</td>
                </tr>
            @endif
            @if ($data->type == 'reset_password')
                <tr>
                    <td>{password_reset_link}</td>
                    <td>{{ __('Email Verification Link') }}</td>
                </tr>
            @endif
            @if ($data->type == 'place_order')
                <tr>
                    <td>{order_number}</td>
                    <td>{{ __('Product Order Number') }}</td>
                </tr>
                <tr>
                    <td>{date}</td>
                    <td>{{ __('Order Date') }}</td>
                </tr>
                <tr>
                    <td>{price}</td>
                    <td>{{ __('Paid Amount') }}</td>
                </tr>
                <tr>
                    <td>{quantiy}</td>
                    <td>{{ __('Quanity Of Order Items') }}</td>
                </tr>
            @endif
            <tr>
                <td>{website_title}</td>
                <td>{{ __('Website Title') }}</td>
            </tr>
        </tbody>
    </table>
</div>
