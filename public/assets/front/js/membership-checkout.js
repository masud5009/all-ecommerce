"use strict";
$("#checkoutForm").attr('onsubmit', 'return false');

$("#submitCheckout").on('click', function (e) {
    e.preventDefault();
    const submitBtn = $('#submitCheckout');
    const originalText = submitBtn.html();
    const selectedPayment = $('#payment_method').val();

    submitBtn.prop('disabled', true)
        .html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');

    // Check if Stripe is selected
    if (selectedPayment === 'stripe' && card) {
        // Show loading on Stripe card element
        $('#stripe-element').css('opacity', '0.5');
        const stripeLoadingMsg = $('<div class="stripe-loading text-center mt-2" style="color: #6c757d;"><i class="fas fa-spinner fa-spin me-2"></i>Generating secure payment token...</div>');
        $('#stripe-element').after(stripeLoadingMsg);

        // Create Stripe token first
        stripe.createToken(card).then(function (result) {
            // Remove loading state
            $('#stripe-element').css('opacity', '1');
            $('.stripe-loading').remove();

            if (result.error) {
                // Show error
                $.toast({
                    heading: 'Error',
                    text: result.error.message,
                    showHideTransition: 'plain',
                    icon: 'error',
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 4000,
                });
                submitBtn.prop('disabled', false).html(originalText);
            } else {
                // Submit with Stripe token
                submitCheckoutForm(result.token.id, submitBtn, originalText);
            }
        }).catch(function (error) {
            // Remove loading state
            $('#stripe-element').css('opacity', '1');
            $('.stripe-loading').remove();

            $.toast({
                heading: 'Error',
                text: 'Failed to generate payment token. Please try again.',
                showHideTransition: 'plain',
                icon: 'error',
                allowToastClose: true,
                position: 'top-right',
                hideAfter: 4000,
            });
            submitBtn.prop('disabled', false).html(originalText);
        });
    } else {
        // Submit without Stripe token
        submitCheckoutForm(null, submitBtn, originalText);
    }
});

function submitCheckoutForm(stripeToken, submitBtn, originalText) {
    let checkoutForm = document.getElementById('checkoutForm');
    let fd = new FormData(checkoutForm);

    // Add Stripe token if exists
    if (stripeToken) {
        fd.append('stripeToken', stripeToken);
    }

    let url = $("#checkoutForm").attr('action');
    let method = $("#checkoutForm").attr('method');

    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
            submitBtn.prop('disabled', false).html(originalText);
            $('.em').each(function () {
                $(this).html('');
            });

            if (data.status === 'success') {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            } else if (data.status === 'error') {
                // Show error toast
                $.toast({
                    heading: 'Error',
                    text: data.message,
                    showHideTransition: 'plain',
                    icon: 'error',
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 4000,
                });

                // Redirect to cancel URL if provided
                if (data.redirect_url) {
                    setTimeout(function () {
                        window.location.href = data.redirect_url;
                    }, 2000);
                }
            }
        },
        error: function (error) {
            if (error.responseJSON != null) {
                $.toast({
                    heading: 'Error',
                    text: error.responseJSON.message,
                    showHideTransition: 'plain',
                    icon: 'error',
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 4000,
                });

                // Check if there's a redirect URL in error response
                if (error.responseJSON.redirect_url) {
                    setTimeout(function () {
                        window.location.href = error.responseJSON.redirect_url;
                    }, 2000);
                }
            }

            $('.em').each(function () {
                $(this).html('');
            });

            if (error.status == 422) {
                const errors = error.responseJSON.errors;
                for (let key in errors) {
                    document.getElementById('err_' + key).innerHTML = errors[key][0];
                    $('[name="' + key + '"]').css('border-color', '#dc3545');
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
                }
            }
            submitBtn.prop('disabled', false).html(originalText);
        }
    });

    $('.form-control').each(function () {
        if ($(this).val()) {
            $(this).css('border-color', '#71dd37');
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }
    });
}

// Stripe setup
const stripe = Stripe(stripeKey);
const elements = stripe.elements();
let card = null;

$('body').on('change', '#payment_method', function () {
    const selected = this.value;
    const stripeElement = document.getElementById('stripe-element');

    if (selected === 'stripe') {
        stripeElement.style.display = 'block';
        if (!card) {
            card = elements.create('card', {
                style: {
                    base: {
                        iconColor: '#454545',
                        color: '#454545',
                        fontWeight: '500',
                        lineHeight: '50px',
                        fontSmoothing: 'antialiased',
                        backgroundColor: '#f2f2f2',
                        ':-webkit-autofill': {
                            color: '#454545',
                        },
                        '::placeholder': {
                            color: '#454545',
                        },
                    }
                },
            });
            card.mount('#stripe-element');
        }
    } else {
        stripeElement.style.display = 'none';
        if (card) {
            card.unmount();
            card = null;
        }
    }
});
