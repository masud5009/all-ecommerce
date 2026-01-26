"use strict";
$("#earningBtn").on('click', function (e) {
    e.preventDefault();

    let url = $("#earningForm").attr('action');
    let queryParams = $("#earningForm").serialize();

    $.ajax({
        url: url + '?' + queryParams,
        method: 'GET',
        contentType: false,
        processData: false,
        success: function (data) {
            $('.earn_date').text(data.date);
            $('.daily_earning').text(
                (symbol_position === 'left' ? currency_symbol : '') +
                data.total_earnings +
                (symbol_position === 'right' ? currency_symbol : '')
            );

        },
        error: function (error) {
            //
        }
    });
});
$("#orderBtn").on('click', function (e) {
    e.preventDefault();

    let url = $("#orderForm").attr('action');
    let queryParams = $("#orderForm").serialize();

    $.ajax({
        url: url + '?' + queryParams,
        method: 'GET',
        contentType: false,
        processData: false,
        success: function (data) {
            $('.order_date').text(data.date);
            $('.daily_order').text(data.total_orders);

        },
        error: function (error) {
            //
        }
    });
});
