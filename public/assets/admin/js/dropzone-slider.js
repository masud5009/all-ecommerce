
"use strict";

Dropzone.options.myDropzone = {
    acceptedFiles: '.png, .jpg, .jpeg, .webp',
    url: uploadSliderImage,
    success: function (file, response) {
        $("#sliders").append(`<input type="hidden" name="slider_image[]" id="slider${response.file_id}" value="${response.file_id}">`);
        // Create the remove button
        var removeButton = Dropzone.createElement("<button class='btn btn-xs rmv-btn'><i class='fa fa-times'></i></button>");
        // Capture the Dropzone instance as closure.
        var _this = this;
        // Listen to the click event
        removeButton.addEventListener("click", function (e) {
            // Make sure the button click doesn't submit the form:
            e.preventDefault();
            e.stopPropagation();
            _this.removeFile(file);
            rmvImg(response.file_id);
        });
        // Add the button to the file preview element.
        file.previewElement.appendChild(removeButton);
        if (typeof response.error != 'undefined') {
            if (typeof response.file != 'undefined') {
                document.getElementById('errpreimg').innerHTML = response.file[0];
            }
        }
    }
};

function rmvImg(file_Id) {
    $(".request-loader").show();
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    $.ajax({
        url: rmvSliderImage,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { 'value': file_Id, '_token': csrf },
        success: function (data) {
            $(".request-loader").hide();
            const ele = document.getElementById("slider" + file_Id);
            if (ele) {
                ele.remove();
            }

            $.toast({
                heading: 'Success',
                text: 'Image removed successfully!',
                showHideTransition: 'plain',
                icon: 'success',
                allowToastClose: true,
                position: 'top-right',
                hideAfter: 4000,
            });
        },
        error: function (e) {
        }
    });
}

$(document).on('click', '.rmvbtndb', function () {
    let indb = $(this).data('indb');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    $(".request-loader").show();
    $.ajax({
        url: rmvDbSliderImage,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf },
        data: {
            fileid: indb,
            _token: csrf
        },
        success: function (data) {
            $(".request-loader").hide();
            if (data == 'warning') {
                var text = "You can't delete all images!";
                var heading = 'Warning';
                var icon = 'warning';
            } else {
                $("#trdb" + indb).remove();
                var text = 'Slider image deleted successfully!';
                var heading = 'Success';
                var icon = 'success';
            }

            $.toast({
                heading: heading,
                text: text,
                showHideTransition: 'plain',
                icon: icon,
                allowToastClose: true,
                position: 'top-right',
                hideAfter: 4000,
            });
        }
    });
});
