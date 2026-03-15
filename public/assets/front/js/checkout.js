
(function () {
    'use strict';

    const shippingSelect = document.getElementById('shipping_charge_id');
    const shippingAmount = document.getElementById('shippingAmount');
    const totalAmount = document.getElementById('totalAmount');

    function updateCheckoutSummary() {
        if (!shippingSelect || !shippingAmount || !totalAmount) return;

        const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
        if (!selectedOption) return;

        shippingAmount.textContent = selectedOption.dataset.formattedCharge || shippingAmount.textContent;
        totalAmount.textContent = selectedOption.dataset.formattedTotal || totalAmount.textContent;
    }

    if (shippingSelect) {
        shippingSelect.addEventListener('change', updateCheckoutSummary);
        updateCheckoutSummary();
    }





    $("#paymentForm").attr('onsubmit', 'return false');
    const setBtnLoading = (isLoading) => {
        const $btn = $("#paymentSubmitBtn");

        if (isLoading) {
            $btn.data('oldText', $btn.text());
            $btn.prop('disabled', true).text(`Processing...`);
        } else {
            $btn.prop('disabled', false).text($btn.data('oldText') || 'Place Order');
        }
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*================================= payment gateway ================================*/
    function submitPaymentForm() {
        let paymentForm = document.getElementById('paymentForm');
        let fd = new FormData(paymentForm);
        let url = $("#paymentForm").attr('action');
        let method = $("#paymentForm").attr('method');

        $.ajax({
            url: url,
            method: method,
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
                setBtnLoading(false);

                $(".em").each(function () {
                    $(this).html('');
                });

                if (data.status === 'success') {
                    // redirect type
                    if (data.action === 'redirect' && data.url) {
                        window.location.href = data.url;
                        return;
                    }

                    // html type
                    if (data.action === 'html' && data.html) {
                        document.open();
                        document.write(data.html);
                        document.close();
                        return;
                    }
                }

                // if error occurs
                else if (typeof data.error != 'undefined') {
                    for (let x in data) {
                        if (x == 'error') {
                            continue;
                        }
                        document.getElementById('err_' + x).innerHTML = data[x][0];
                    }
                } else if (data?.errors?.error) {
                    const errors = data?.errors;
                    Object.keys(errors).map(function (key) {
                        if (key !== 'error')
                            document.getElementById('err_' + key).innerHTML = errors[key][0];
                    });
                }
            },
            error: function (error) {
                setBtnLoading(false);

                $(".em").each(function () {
                    $(this).html('');
                })

                const responseJson = error?.responseJSON;
                if (responseJson?.errors) {
                    for (let x in responseJson.errors) {
                        document.getElementById('err_' + x).innerHTML = responseJson.errors[x][0];
                    }
                    return;
                }
            }
        });
    }

    $("#paymentSubmitBtn").on('click', function (e) {
        setBtnLoading(true);
        submitPaymentForm();
    });

})();
