"use strict";

$('#blogSubmit').on('click', function (e) {

    $(e.target).attr('disabled', true);
    $(".request-loader").addClass("show");

    let blogForm = document.getElementById('blogForm');
    let fd = new FormData(blogForm);
    let url = $("#blogForm").attr('action');
    let method = $("#blogForm").attr('method');

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
        let index = i;

        let $toInput = $('.form-control').eq(index);

        if ($(this).hasClass('summernote')) {
            let tmcId = $toInput.attr('id');
            let content = tinyMCE.get(tmcId).getContent();
            fd.delete($(this).attr('name'));
            fd.append($(this).attr('name'), content);
        }
    });


    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {

            $(e.target).attr('disabled', false);
            $('.request-loader').removeClass('show');

            $('.em').each(function () {
                $(this).html('');
            });
            if (data == 'nullError') {
                $.toast({
                    heading: 'Warning',
                    text: 'No variation has been added yet!',
                    showHideTransition: 'plain',
                    icon: 'warning',
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 4000,
                });
            }
            if (data == 'success') {
                location.reload();
                $('#blogForm')[0].reset();
            }

        },
        error: function (error) {
            let errors = ``;
            const response = error.responseJSON || {};

            if (response.errors) {
                for (let x in response.errors) {
                    errors += `<li>
                      <p class="text-danger mb-0">${response.errors[x][0]}</p>
                    </li>`;
                }
            } else if (response.message) {
                errors += `<li>
                  <p class="text-danger mb-0">${response.message}</p>
                </li>`;
            } else {
                errors += `<li>
                  <p class="text-danger mb-0">Something went wrong. Please try again.</p>
                </li>`;
            }

            $('#blog_errors ul').html(errors);
            $('#blog_errors').removeClass('d-none');

            $('.request-loader').removeClass('show');

            document.getElementById('blog_errors').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
    $(e.target).attr('disabled', false);
});
