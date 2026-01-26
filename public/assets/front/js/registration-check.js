
"use strict";
$("#ajaxForm").attr('onsubmit', 'return false');

$("#signupBtn").on('click', function (e) {
    e.preventDefault();
    $('.request-loader').show();

    let signupForm = document.getElementById('signupForm');
    let fd = new FormData(signupForm);
    let url = $("#signupForm").attr('action');
    let method = $("#signupForm").attr('method');

    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
            $('.request-loader').hide();
            $('.em').each(function () {
                $(this).html('');
            });

            //after succcessfully submit the form
            if (data.status == 'success') {
                window.location.href = data.url;
            }
        },
        error: function (error) {
            //remove required message if fillup the required filed
            $('.em').each(function () {
                $(this).html('');
            });
            if (error.status == 422 || error) {
                const errors = error.responseJSON.errors;

                for (let key in errors) {
                    // Show the error message
                    document.getElementById('err_' + key).innerHTML = errors[key][0];

                    // Set the border color to red for invalid fields
                    $('[name="' + key + '"]').css('border-color', '#dc3545');
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
                }
            } else {
                alert('An unexpected error occurred. Please try again later.');
            }
            $('.request-loader').hide();
        }
    });
    // After making the AJAX request, check for valid inputs
    $('.form-control').each(function () {
        if ($(this).val()) {
            $(this).css('border-color', '#71dd37');
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }
    });
});
