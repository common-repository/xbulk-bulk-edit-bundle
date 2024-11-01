jQuery(document).ready(function ($) {
    $(document).on('click', '#wbebl-activation-activate', function () {
        $('#wbebl-activation-type').val('activate');

        if ($('#wbebl-activation-email').val() != '') {
            if ($('#wbebl-activation-industry').val() != '') {
                setTimeout(function () {
                    $('#wbebl-activation-form').first().submit();
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

    $(document).on('click', '#wbebl-activation-skip', function () {
        $('#wbebl-activation-type').val('skip');

        setTimeout(function () {
            $('#wbebl-activation-form').first().submit();
        }, 200)
    });
})