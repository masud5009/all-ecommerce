
"use strict";

$("#ajaxEditForm").attr('onsubmit', 'return false');
$("#ajaxForm").attr('onsubmit', 'return false');

function hideActiveOverlays() {
    $('.modal.show').each(function () {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getInstance(this);
            if (modalInstance) {
                modalInstance.hide();
                return;
            }
        }
        $(this).modal('hide');
    });

    if (typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
        $('.offcanvas.show').each(function () {
            const offcanvasInstance = bootstrap.Offcanvas.getInstance(this) || new bootstrap.Offcanvas(this);
            offcanvasInstance.hide();
        });
    }
}

window.hideActiveOverlays = hideActiveOverlays;

/*===================  Uploaded Image Preview Start ================*/
document.querySelectorAll('.thumb-preview, .thumb-preview2').forEach((thumb) => {
    thumb.addEventListener('click', function () {
        const inputId = thumb.classList.contains('thumb-preview2') ? 'thumbnailInput2' : 'thumbnailInput';
        const thumbnailInput = document.getElementById(inputId);
        if (thumbnailInput) {
            thumbnailInput.click();
        }
    });
});

// handle image input changes dynamically
$('.img-input, .img-input2').on('change', function (event) {
    const input = $(this);
    const file = event.target.files[0];
    if (!file) {
        return;
    }

    const reader = new FileReader();

    reader.onload = function (e) {
        const previewSelector = input.hasClass('img-input2') ? '.uploaded-img2' : '.uploaded-img';
        const preview = input.closest('form').find(previewSelector).first();

        if (preview.length > 0) {
            preview.attr('src', e.target.result);
            return;
        }

        $(previewSelector).attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
});

/*===================  Color picker js ================*/



/*===================  Form Update with AJAX Request Start ================*/
$("#submitBtn").on('click', function (e) {
    e.preventDefault();
    $('.request-loader').show();

    // Reset previous errors
    $('.em').html('');
    $('.form-control').removeClass('is-invalid').removeClass('is-valid');

    let ajaxForm = document.getElementById('ajaxForm');
    let fd = new FormData(ajaxForm);
    let url = $("#ajaxForm").attr('action');
    let method = $("#ajaxForm").attr('method');

    // Handle summernote content
    $('.summernote').each(function () {
        let tmcId = $(this).attr('id');
        let content = tinyMCE.get(tmcId) ? tinyMCE.get(tmcId).getContent() : '';
        fd.set($(this).attr('name'), content);
    });

    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
            $('.request-loader').hide();

            if (data.status == 'success') {
                hideActiveOverlays();
                location.reload();
            }
        },
        error: function (error) {
            $('.request-loader').hide();

            if (error.status === 422 || error.status === 400) {
                const errors = error.responseJSON.errors;

                for (let key in errors) {
                    // Show error message
                    $('#err_' + key).html(errors[key][0]);

                    // Add invalid class
                    $('[name="' + key + '"]').addClass('is-invalid').removeClass('is-valid');
                }
            } else {
                $.toast({
                    heading: 'Error',
                    text: 'An unexpected error occurred. Please try again later.',
                    showHideTransition: 'plain',
                    icon: 'error',
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 4000
                })
            }
        }
    });
    // Real-time validation
    $('.form-control').on('input', function () {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid').addClass('is-valid');
            $('#err_' + $(this).attr('name')).html('');
        } else {
            $(this).removeClass('is-valid');
        }
    });

    $('.form-control, .form-select').on('input change', function () {
        let fieldName = $(this).attr('name');
        let fieldValue = $(this).val();

        // For select dropdowns
        if ($(this).is('select')) {
            if (fieldValue && fieldValue !== '') {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#err_' + fieldName).html('');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        }
        // For text inputs, textareas etc.
        else {
            if (fieldValue.trim() !== '') {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#err_' + fieldName).html('');
            } else {
                $(this).removeClass('is-valid');
            }
        }
    });
});

$("#submitBtn3").on('click', function (e) {
    e.preventDefault();
    $('.request-loader').show();

    // Reset previous errors
    $('.em').html('');
    $('.form-control').removeClass('is-invalid').removeClass('is-valid');

    let ajaxForm3 = document.getElementById('ajaxForm3');
    let fd = new FormData(ajaxForm3);
    let url = $("#ajaxForm3").attr('action');
    let method = $("#ajaxForm3").attr('method');

    // Handle summernote content
    $('.summernote').each(function () {
        let tmcId = $(this).attr('id');
        let content = tinyMCE.get(tmcId) ? tinyMCE.get(tmcId).getContent() : '';
        fd.set($(this).attr('name'), content);
    });

    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
            $('.request-loader').hide();

            if (data.status == 'success') {
                hideActiveOverlays();
                location.reload();
            }
        },
        error: function (error) {
            $('.request-loader').hide();

            if (error.status === 422 || error.status === 400) {
                const errors = error.responseJSON.errors;

                for (let key in errors) {
                    // Show error message
                    $('#err_' + key).html(errors[key][0]);

                    // Add invalid class
                    $('[name="' + key + '"]').addClass('is-invalid').removeClass('is-valid');
                }
            } else {
                $.toast({
                    heading: 'Error',
                    text: 'An unexpected error occurred. Please try again later.',
                    showHideTransition: 'plain',
                    icon: 'error',
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 4000
                })
            }
        }
    });
    // Real-time validation
    $('.form-control').on('input', function () {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid').addClass('is-valid');
            $('#err_' + $(this).attr('name')).html('');
        } else {
            $(this).removeClass('is-valid');
        }
    });

    $('.form-control, .form-select').on('input change', function () {
        let fieldName = $(this).attr('name');
        let fieldValue = $(this).val();

        // For select dropdowns
        if ($(this).is('select')) {
            if (fieldValue && fieldValue !== '') {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#err_' + fieldName).html('');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        }
        // For text inputs, textareas etc.
        else {
            if (fieldValue.trim() !== '') {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#err_' + fieldName).html('');
            } else {
                $(this).removeClass('is-valid');
            }
        }
    });
});

$("#minorBtn").on('click', function (e) {
    e.preventDefault();
    $('.request-loader').show();

    let minorForm = document.getElementById('minorForm');
    let fd = new FormData(minorForm);
    let url = $("#minorForm").attr('action');
    let method = $("#minorForm").attr('method');

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
                hideActiveOverlays();
                location.reload();
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

$("#updateBtn").on('click', function (e) {
    e.preventDefault();
    $('.request-loader').show();
    if ($(".iconpicker-component").length > 0) {
        $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxEditForm = document.getElementById('ajaxEditForm');
    let fd = new FormData(ajaxEditForm);
    let url = $("#ajaxEditForm").attr('action');
    let method = $("#ajaxEditForm").attr('method');

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
                hideActiveOverlays();
                location.reload();
            }
        },
        error: function (error) {
            $('.em').each(function () {
                $(this).html('');
            });
            //shown green border if fillup the required filed
            if (error.status == 422 || error) {
                $('.request-loader').hide();
                const errors = error.responseJSON.errors;

                for (let key in errors) {
                    // Show the error message
                    const errorField = document.getElementById('editErr_' + key);
                    if (errorField) {
                        errorField.innerHTML = errors[key][0];
                    }

                    // Set the border color to red for invalid fields
                    const $field = $('[name="' + key + '"]');
                    if ($field.length > 0) {
                        $field.css('border-color', '#dc3545');
                        $('input[name="' + key + '"], select[name="' + key + '"], textarea[name="' + key + '"]').addClass('is-invalid');
                    }
                }
            } else {
                $('.request-loader').hide();
                alert('An unexpected error occurred. Please try again later.');
            }
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

/*===================  Initialize tinymce ================*/
const isDarkMode = $('body').attr('data-background-color') === 'dark';

$(".editor").each(function (i) {

    tinymce.init({
        selector: '.editor',
        plugins: 'autolink charmap emoticons image link lists media searchreplace table visualblocks wordcount directionality',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat | ltr rtl',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        promotion: false,
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        skin: isDarkMode ? 'oxide-dark' : 'oxide',
        content_css: isDarkMode ? 'dark' : 'default',
        toolbar_mode: 'sliding'
    });

});

/*===================  data-tables ================*/
$(document).ready(function () {
    let table = new DataTable('#myTable', {
        ordering: false,
        responsive: true,
        autoWidth: true,
        "pagingType": "simple_numbers",
        language: {
            "paginate": {
                "previous": "Previous",
                "next": "Next"
            },
            "zeroRecords": "NO DATA FOUND !",
            "lengthMenu": "Show _MENU_ entries",
        },
        layout: {
            topEnd: {
                search: {
                    placeholder: 'Search...',
                    text: '',
                }
            }
        }
    });
});


$('.select2').select2();
/*===== icon picker int =========*/
$('.icp-dd').iconpicker();


/*===================  delete button ================*/

$(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault();

    const $form = $(this).closest(".deleteForm");
    if (!$form.length) {
        return;
    }

    if (typeof Swal === 'undefined') {
        $form.submit();
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            $form.submit();
        } else if (typeof swal !== 'undefined') {
            swal.close();
        }
    });
});


/*===================  Edit Button ================*/
$(".editBtn").on('click', function () {
    let datas = $(this).data();
    delete datas['toggle'];

    for (let x in datas) {
        if ($("#in_" + x).hasClass('summernote')) {
            tinyMCE.get("in_" + x).setContent(datas[x]);
        } else if ($("#in_" + x).data('role') == 'tagsinput') {
            if (datas[x].length > 0) {
                let arr = datas[x].split(" ");
                for (let i = 0; i < arr.length; i++) {
                    $("#in_" + x).tagsinput('add', arr[i]);
                }
            } else {
                $("#in_" + x).tagsinput('removeAll');
            }
        } else if ($("input[name='" + x + "']").attr('type') == 'radio') {
            $("input[name='" + x + "']").each(function (i) {
                if ($(this).val() == datas[x]) {
                    $(this).prop('checked', true);
                }
            });
        } else if ($("#in_" + x).hasClass('select2')) {
            $("#in_" + x).val(datas[x]);
            $("#in_" + x).trigger('change');
        } else if ($("#in_" + x).is('select')) {
            const $input = $("#in_" + x);
            const currentValue = datas[x];

            $input.val(currentValue);
            if ($input.val() === null && typeof currentValue === 'string') {
                const upperValue = currentValue.toUpperCase();
                const lowerValue = currentValue.toLowerCase();

                if ($input.find('option[value="' + upperValue + '"]').length > 0) {
                    $input.val(upperValue);
                } else if ($input.find('option[value="' + lowerValue + '"]').length > 0) {
                    $input.val(lowerValue);
                }
            }

            $input.trigger('change');
        } else {
            $("#in_" + x).val(datas[x]);

            if ($('.in_image').length > 0) {
                $('.in_image').attr('src', datas['image']);
            }

            if ($('#in_icon').length > 0) {
                if ($('#in_icon').is('input,textarea,select')) {
                    const iconClass = (datas['icon'] || '').trim().length > 0 ? datas['icon'].trim() : 'fas fa-seedling';
                    $('#in_icon').val(iconClass);

                    if ($('#in_selectedIcon').length > 0) {
                        $('#in_selectedIcon').attr('class', iconClass);
                    }

                    const $componentIcon = $('#in_icon').closest('.input-group').find('.iconpicker-component i');
                    if ($componentIcon.length > 0) {
                        $componentIcon.attr('class', iconClass);
                    }
                } else {
                    $('#in_icon').attr('class', datas['icon'] || 'fas fa-seedling');
                }
            }
        }
    }

    // focus & blur colorpicker inputs
    setTimeout(() => {
        $(".jscolor").each(function () {
            $(this).focus();
            $(this).blur();
        });
    }, 300);
});

/*===================  Bulk Delete Using Ajax ================*/
$(".bulk-check").on('change', function () {
    let isAll = $(this).data('val') === 'all';
    let isChecked = $(this).prop('checked');

    if (isAll) {
        $(".bulk-check").prop('checked', isChecked);
    }

    let anyChecked = $(".bulk-check:checked").length > 0;

    let selectedCount = $(".bulk-check:checked").length;
    $('.bulk-delete-div .text').text(selectedCount);

    if (anyChecked || isChecked) {
        $('.bulk-delete-div').css({
            visibility: 'visible',
            opacity: "1",
            width: "250px"
        })
    } else {
        $('.bulk-delete-div').css({
            visibility: 'hidden',
            opacity: "0",
            width: "0px"
        })
    }
});

$(".cross-btn").on('click', function () {
    $(".bulk-check").prop('checked', false);

    $('.bulk-delete-div').css({
        visibility: 'hidden',
        opacity: "0",
        width: "0px"
    });
});


$('.bulk-delete').on('click', function () {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning', // Use "icon" instead of "type"
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            // $(".request-loader").addClass('show');
            let href = $(this).data('href');
            let ids = [];

            // take ids of checked one's
            $(".bulk-check:checked").each(function () {
                if ($(this).data('val') != 'all') {
                    ids.push($(this).data('val'));
                }
            });

            let fd = new FormData();
            for (let i = 0; i < ids.length; i++) {
                fd.append('ids[]', ids[i]);
            }

            $.ajax({
                url: href,
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    // $(".request-loader").removeClass('show');

                    if (data.status == "success") {
                        location.reload();
                    }
                }
            });
        }
    });
});


/*===================  Form Submit Using Ajax With Error List ================*/
$('#submitBtn2').on('click', function (e) {
    e.preventDefault();
    $('.request-loader').show();
    if ($(".iconpicker-component").length > 0) {
        $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxForm2 = document.getElementById('ajaxForm2');
    let fd = new FormData(ajaxForm2);
    let url = $("#ajaxForm2").attr('action');
    let method = $("#ajaxForm2").attr('method');

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
            $('.request-loader').hide();

            $('.em').each(function () {
                $(this).html('');
            });

            if (data == 'success') {
                location.reload();
                $('#blogForm')[0].reset();
            }

        },
        error: function (error) {
            let errors = ``;

            for (let x in error.responseJSON.errors) {
                errors += `<li>
                  <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
                </li>`;
            }

            $('#blog_errors ul').html(errors);
            $('#blog_errors').removeClass('d-none');

            $('.request-loader').hide();

            $('html, body').animate({
                scrollTop: $('#blog_errors').offset().top - 100
            }, 1000);
        }
    });
    $(e.target).attr('disabled', false);
});

/*===================  Required message without ajax ================*/
$('.card-header').click(function () {
    $(this).next('#stripe').collapse('toggle');
});

/*=================== language specific input-field show/hide ================*/
function toggleLanguage(langId) {
    $('.request-loader').addClass('show');
    let section = document.getElementById(`language_${langId}`);
    if (section) {
        section.classList.toggle('d-none');
    }
    setTimeout(() => {
        $('.request-loader').removeClass('show');
    }, 200);
}


$('body').on('change', '#fileType', function () {
    if ($(this).val() === 'link') {
        $('#downloadFile').addClass('d-none');
        $('#downloadLink').removeClass('d-none');
        $('#customFile').val(''); // Clear the file input
    } else {
        $('#downloadFile').removeClass('d-none');
        $('#downloadLink').addClass('d-none');
        $('#downloadLink input').val('');
    }
});

$(document).on('change', '#languageSelect', function () {
    const selectedLanguage = $(this).val();
    const currentUrl = new URL(window.location.href);

    if (selectedLanguage) {
        currentUrl.searchParams.set('language', selectedLanguage);
    } else {
        currentUrl.searchParams.delete('language');
    }

    window.location.href = currentUrl.toString();
});

/*==============faltpciker js================*/
flatpickr(".flatpickr", {
    //
});

/*==============status change js================*/
$('body').on('click', '.changeStatusBtn', function () {
    let _id = $(this).data('id');
    const _url = $(this).data('url');

    let _button = $(this);
    let newStatus = _button.data('value') == 1 ? 0 : 1;
    let badgeClass = newStatus == 1 ? 'bg-success' : 'bg-danger';
    let badgeText = newStatus == 1 ? activeText : InactiveText;

    _button
        .removeClass('bg-success bg-danger')
        .addClass(badgeClass)
        .addClass('fade-in-top')
        .data('value', newStatus)
        .text(badgeText);

    $.ajax({
        url: _url,
        type: 'POST',
        data: {
            id: _id,
            status: newStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response !== 'success') {
                alert('Failed to update status please try again');
                location.reload();
            }
        },
        error: function () {
            alert('Error updating status please try again');
            location.reload();
        }
    });
});

/*===================  Flash Sale Form (AJAX) ================*/
$(document).on('click', '.flashSaleBtn', function () {
    const $btn = $(this);
    const hasSavedValues = (($btn.attr('data-flash-sale-price') || '').toString().trim() !== '') ||
        (($btn.attr('data-flash-sale-start-at') || '').toString().trim() !== '') ||
        (($btn.attr('data-flash-sale-end-at') || '').toString().trim() !== '');
    const savedStatus = $btn.attr('data-flash-sale-status') === '1' ? '1' : '0';
    const initialStatus = savedStatus === '1' || !hasSavedValues ? '1' : '0';

    $('#flash_product_id').val($btn.attr('data-product-id'));
    $('#flash_product_title').val($btn.data('title') || '');
    $('#flash_current_price').val($btn.attr('data-current-price') || 0);
    $('#flash_sale_status').val(initialStatus);
    $('#flash_sale_price').val($btn.attr('data-flash-sale-price') || '');
    $('#flash_sale_start_at').val($btn.attr('data-flash-sale-start-at') || '');
    $('#flash_sale_end_at').val($btn.attr('data-flash-sale-end-at') || '');

    $('.em').html('');
    $('#flashSaleForm .is-invalid, #flashSaleForm .is-valid').removeClass('is-invalid is-valid');
});

$(document).on('change', '#flash_sale_status', function () {
    if ($(this).val() === '0') {
        $('#flash_sale_price').val('');
        $('#flash_sale_start_at').val('');
        $('#flash_sale_end_at').val('');
    }
});

$(document).on('submit', '#flashSaleForm', function (e) {
    e.preventDefault();
    $('.request-loader').show();

    const $form = $(this);
    const fd = new FormData(this);
    const url = $form.attr('action');
    const method = $form.attr('method') || 'POST';

    $('.em').html('');
    $form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');

    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
            $('.request-loader').hide();

            if (data.status === 'success' || data === 'success') {
                hideActiveOverlays();
                location.reload();
            }
        },
        error: function (error) {
            $('.request-loader').hide();

            if (error.status === 422 || error.status === 400) {
                const errors = (error.responseJSON && error.responseJSON.errors) ? error.responseJSON.errors : {};

                for (let key in errors) {
                    $('#err_' + key).html(errors[key][0]);
                    const $field = $form.find('[name="' + key + '"]');
                    $field.addClass('is-invalid').removeClass('is-valid');
                }
            } else {
                $.toast({
                    heading: 'Error',
                    text: 'An unexpected error occurred. Please try again later.',
                    showHideTransition: 'plain',
                    icon: 'error',
                    allowToastClose: true,
                    position: 'top-right',
                    hideAfter: 4000
                });
            }
        }
    });
});
