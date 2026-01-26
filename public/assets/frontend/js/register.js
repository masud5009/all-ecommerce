
"use strict";
$(document).ready(function () {
    $("input[name='username']").on('input', function () {
        let username = $(this).val();
        if (username.length > 0) {
            $("#username").text(username);
        } else {
            $("#username").text("{username}");
        }
    });
});
