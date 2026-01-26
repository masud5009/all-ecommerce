"use strict";
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('.hover-icon').on('click', function () {
        $('.request-loader').show();
        const id = $(this).data('id');

        if ($(this).data('variation') == 'yes') {
            $('#variationModal').modal('show');
            $('.modal-title').text($(this).data('title'));
            $('.cart-link').attr('data-id', $(this).data('id'));
            getVariation(variationUrl, id);
        } else {
            updateItem(id);
        }
    });

    // Use event delegation for increment and decrement buttons
    $('body').on('click', '.increment', function () {
        $('.request-loader').show();
        const inId = $(this).data('productid');
        updateItem(inId);
    });

    $('body').on('click', '.decrement', function () {
        $('.request-loader').show();
        const decId = $(this).data('productid');
        updateItem(decId, 'decrement');
    });

    $('body').on('click', '.remove-product', function () {
        $('.request-loader').show();
        const reId = $(this).data('removeid');
        updateItem(reId, null, 'remove');
    });

    function updateItem(id, decrement = null, remove = null) {
        $.get(addUrl, { id: id, decrement: decrement, remove: remove })
            .done(function (data) {
                $('.request-loader').hide();
                $('#checkout-list').load(window.location.href + ' #checkout-list', function () {
                    // update the checkout form
                    checkoutSubmit();
                });
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                $('.request-loader').hide();
                console.error('Error:', textStatus, errorThrown);
            });
    }


    function getVariation(url, id) {
        $.get(url, { id: id })
            .done(function (data) {
                $('#variationModal').show('modal');
                $('#add-variations').html(data);
                $('.request-loader').hide();
            })

            .fail(function (jqXHR, textStatus, errorThrown) {
                $('.request-loader').hide();
                console.error('Error:', textStatus, errorThrown);
            });
    }


    //add variation product from here
    $('.cart-link').on('click', function (e) {
        e.preventDefault();
        // Prevent multiple clicks
        if ($(this).data('loading')) return;
        $(this).data('loading', true);

        var form = $('#variationsForm');
        var formData = form.serialize();
        $('.cart-link').html('Adding to cart <i class="fas fa-spinner fa-spin"></i>');

        var url = form.attr('action') + '?' + formData;

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                let text = '';
                let heading = '';
                let icon = '';

                switch (response.status) {
                    case 'null-variations':
                        text = "Please select a variation before proceeding.";
                        heading = 'Warning';
                        icon = 'warning';
                        break;

                    case 'stock_out':
                        const options = response.data.map(item => item.option_name).join(', ');
                        text = `${options} is out of stock!`;
                        heading = 'Warning';
                        icon = 'warning';
                        break;

                    case 'success':
                        text = "Product successfully added";
                        heading = 'Success';
                        icon = 'success';
                        $('#variationModal').modal('hide');
                        $('#checkout-list').load(window.location.href + ' #checkout-list', function () {
                            // update the checkout form
                            checkoutSubmit();
                        });
                        break;

                    default:
                        text = "An unexpected error occurred.";
                        heading = 'Error';
                        icon = 'error';
                        break;
                }

                $.toast({
                    heading: heading,
                    text: text,
                    showHideTransition: 'plain',
                    icon: icon,
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 5000,
                });
            },
            error: function (xhr, status, error) {
                $('.cart-link').html('<i class="fas fa-cart-plus"></i> Add to Cart');

            },
            complete: function () {
                // Reset button after request completes
                $('.cart-link').html('<i class="fas fa-cart-plus"></i> Add to Cart');
                $('.cart-link').data('loading', false);
            }
        });
    });

    /**
     * checkout calculation
     */
    $('body').on('input', '#shipping_amount, #discount_amount, #tax_amount', function () {
           // update the checkout form
           checkoutSubmit();
        // by default to 0.00 if input is empty or 0
        let amount = parseFloat($(this).val()) || 0.00;
        let shippingAmount = parseFloat($('#shipping_amount').val() || 0.00);
        let discountPercent = parseFloat($('#discount_amount').val() || 0.00);
        let taxAmount = parseFloat($('#tax_amount').val() || 0.00);

        // calculate discount amount based on discount percentage
        let discountAmount = (parseFloat(totalAmount) * discountPercent) / 100;
        // calculate the final total amount
        let calculateAmount = (parseFloat(totalAmount) - discountAmount) + taxAmount + shippingAmount;

        // derive target class
        let targetClass = $(this).attr('id').replace('_amount', '_amount');
        if ($(this).attr('id') === 'discount_amount') {
            amount = discountAmount;
        }
        $('.' + targetClass).text(amount.toFixed(2));
        $('.' + targetClass).val(amount.toFixed(2));

        // update the total price and discount percentage display
        $('#total-price').text(currency_symbol + calculateAmount.toFixed(2));
        $('.discount_percent').text(`(${discountPercent.toFixed(2)}%)`);
    });

    function checkoutSubmit() {
        $("#pos_checkout").on('click', function (e) {
            e.preventDefault();
            if ($(this).data('loading')) return;
            $(this).data('loading', true);

            $('#pos_checkout').html('Processing <i class="fas fa-spinner fa-spin"></i>');
            $('#pos_checkout_form').submit();
        });
    }

    //if the page is reloaded, the checkout form will be submitted
    checkoutSubmit();

    $('#product-search').on('keydown', function () {
        $('.pos-loader').removeClass('d-none');
        var query = $(this).val();
        $.ajax({
            url: searchUrl,
            method: "GET",
            data: { search: query },
            success: function (data) {
                $('.pos-loader').addClass('d-none');
                $('#pos-product').html(data);
            }
        });
    });

});
