"use strict";
$(document).ready(function () {
    function handleKeywordSubmission(e, formId, btnId) {
        e.preventDefault();
        $('.request-loader').show();

        let formElement = document.getElementById(formId);
        let fd = new FormData(formElement);
        let url = $("#" + formId).attr('action');
        let method = $("#" + formId).attr('method');

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

                if (data.status == 'success') {
                    $(".modal").modal('hide');
                    location.reload();
                }
            },
            error: function (error) {
                $('.em').each(function () {
                    $(this).html('');
                });

                if (error.status == 422 || error) {
                    const errors = error.responseJSON.errors;

                    for (let key in errors) {
                        document.getElementById('err_' + key).innerHTML = errors[key][0];
                        $('[name="' + key + '"]').css('border-color', '#dc3545');
                        $('input[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
                    }
                } else {
                    console.log(error);
                    alert('An unexpected error occurred. Please try again later.');
                }
                $('.request-loader').hide();
            }
        });
    }

    $("#keywordBtn").on('click', function (e) {
        handleKeywordSubmission(e, 'addKeyword', 'keywordBtn');
    });

    $("#addKeyword").on('keypress', function (e) {
        if (e.key === "Enter") {
            handleKeywordSubmission(e, 'addKeyword', 'keywordBtn');
        }
    });

    $("#adminKeywordBtn").on('click', function (e) {
        handleKeywordSubmission(e, 'adminKeyword', 'adminKeywordBtn');
    });

    $("#adminKeyword").on('keypress', function (e) {
        if (e.key === "Enter") {
            handleKeywordSubmission(e, 'adminKeyword', 'adminKeywordBtn');
        }
    });
});
