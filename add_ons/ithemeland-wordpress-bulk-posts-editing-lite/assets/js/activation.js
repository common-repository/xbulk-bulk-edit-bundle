"use strict";

jQuery(document).ready(function ($) {
    $(document).on('click', '#wpbel-activation-activate', function () {
        $('#wpbel-activation-type').val('activate');

        if ($('#wpbel-activation-email').val() != '') {
            if ($('#wpbel-activation-industry').val() != '') {
                setTimeout(function () {
                    $('#wpbel-activation-form').first().submit();
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

    $(document).on('click', '#wpbel-activation-skip', function () {
        $('#wpbel-activation-type').val('skip');

        setTimeout(function () {
            $('#wpbel-activation-form').first().submit();
        }, 200)
    });
})