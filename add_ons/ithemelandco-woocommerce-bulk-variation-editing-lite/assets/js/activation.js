"use strict";

jQuery(document).ready(function ($) {
    $(document).on('click', '#iwbvel-activation-activate', function () {
        $('#iwbvel-activation-type').val('activate');

        if ($('#iwbvel-activation-email').val() != '') {
            if ($('#iwbvel-activation-industry').val() != '') {
                setTimeout(function () {
                    $('#iwbvel-activation-form').first().submit();
                }, 200)
            } else {
                swal({
                    title: "Industry is required !",
                    type: "warning"
                });
            }
        } else {
            swal({
                title: "Email is required !",
                type: "warning"
            });
        }
    });

    $(document).on('click', '#iwbvel-activation-skip', function () {
        $('#iwbvel-activation-type').val('skip');

        setTimeout(function () {
            $('#iwbvel-activation-form').first().submit();
        }, 200)
    });
})