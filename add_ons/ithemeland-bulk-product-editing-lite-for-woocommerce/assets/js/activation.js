"use strict";

jQuery(document).ready(function ($) {
    $(document).on('click', '#wcbel-activation-activate', function () {
        $('#wcbel-activation-type').val('activate');

        if ($('#wcbel-activation-email').val() != '') {
            if ($('#wcbel-activation-industry').val() != '') {
                setTimeout(function () {
                    $('#wcbel-activation-form').first().submit();
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

    $(document).on('click', '#wcbel-activation-skip', function () {
        $('#wcbel-activation-type').val('skip');

        setTimeout(function () {
            $('#wcbel-activation-form').first().submit();
        }, 200)
    });
})