"use strict";

var wcbelWpEditorSettings = {
    mediaButtons: true,
    tinymce: {
        branding: false,
        theme: 'modern',
        skin: 'lightgray',
        language: 'en',
        formats: {
            alignleft: [
                { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'left' } },
                { selector: 'img,table,dl.wp-caption', classes: 'alignleft' }
            ],
            aligncenter: [
                { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'center' } },
                { selector: 'img,table,dl.wp-caption', classes: 'aligncenter' }
            ],
            alignright: [
                { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'right' } },
                { selector: 'img,table,dl.wp-caption', classes: 'alignright' }
            ],
            strikethrough: { inline: 'del' }
        },
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false,
        browser_spellcheck: true,
        fix_list_elements: true,
        entities: '38,amp,60,lt,62,gt',
        entity_encoding: 'raw',
        keep_styles: false,
        paste_webkit_styles: 'font-weight font-style color',
        preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
        end_container_on_empty_block: true,
        wpeditimage_disable_captions: false,
        wpeditimage_html5_captions: true,
        plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview',
        menubar: false,
        wpautop: true,
        indent: false,
        resize: true,
        theme_advanced_resizing: true,
        theme_advanced_resize_horizontal: false,
        statusbar: true,
        toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_adv',
        toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
        toolbar3: '',
        toolbar4: '',
        tabfocus_elements: ':prev,:next',
    },
    quicktags: {
        buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
    }
}

jQuery(document).ready(function ($) {
    $(document).on('click', '.wcbel-timepicker, .wcbel-datetimepicker, .wcbel-datepicker', function () {
        $(this).attr('data-val', $(this).val());
    });

    wcbelReInitDatePicker();
    wcbelReInitColorPicker();

    // Select2
    if ($.fn.select2) {
        let wcbelSelect2 = $(".wcbel-select2");
        if (wcbelSelect2.length) {
            wcbelSelect2.select2({
                placeholder: "Select ..."
            });
        }
    }

    $(document).on("click", ".wcbel-tabs-list li button.wcbel-tab-item", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();

            if ($(this).closest('.wcbel-tabs-list').attr('data-type') == 'url') {
                window.location.hash = $(this).attr('data-content');
            }

            wcbelOpenTab($(this));
        }
    });

    // Modal
    $(document).on("click", '[data-toggle="modal"]', function () {
        wcbelOpenModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="modal-close"]', function () {
        wcbelCloseModal();
    });

    // Float side modal
    $(document).on("click", '[data-toggle="float-side-modal"]', function () {
        wcbelOpenFloatSideModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="float-side-modal-close"]', function () {
        if ($('.wcbel-float-side-modal:visible').length && $('.wcbel-float-side-modal:visible').hasClass('wcbel-float-side-modal-close-with-confirm')) {
            swal({
                title: 'Are you sure?',
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                confirmButtonText: iwbveTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $('.wcbel-float-side-modal:visible').removeClass('wcbel-float-side-modal-close-with-confirm');
                    wcbelCloseFloatSideModal();
                }
            });
        } else {
            wcbelCloseFloatSideModal();
        }
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            if (jQuery('.wcbel-modal:visible').length > 0) {
                wcbelCloseModal();
            } else {
                if ($('.wcbel-float-side-modal:visible').length && $('.wcbel-float-side-modal:visible').hasClass('wcbel-float-side-modal-close-with-confirm')) {
                    swal({
                        title: ($('.wcbel-float-side-modal:visible').attr('data-confirm-message') && $('.wcbel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.wcbel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                        confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                        confirmButtonText: iwbveTranslate.iAmSure,
                        closeOnConfirm: true
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $('.wcbel-float-side-modal:visible').removeClass('wcbel-float-side-modal-close-with-confirm');
                            wcbelCloseFloatSideModal();
                        }
                    });
                } else {
                    wcbelCloseFloatSideModal();
                }
            }

            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            if ($("#wcbel-filter-form-content").css("display") === "block") {
                $("#wcbel-bulk-edit-filter-form-close-button").trigger("click");
            }
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    $(document).on('click', '#wcbel-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            openFullscreen();
        } else {
            exitFullscreen();
        }
    });

    if (document.addEventListener) {
        document.addEventListener('fullscreenchange', wcbelFullscreenHandler, false);
        document.addEventListener('mozfullscreenchange', wcbelFullscreenHandler, false);
        document.addEventListener('MSFullscreenChange', wcbelFullscreenHandler, false);
        document.addEventListener('webkitfullscreenchange', wcbelFullscreenHandler, false);
    }

    $(document).on("click", ".wcbel-top-nav-duplicate-button", function () {
        let itemIds = $("input.wcbel-check-item:visible:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: "Duplicate for variations product is disabled!",
                    type: "warning"
                });
                return false;
            }
            return $(this).val();
        }).get();

        if (!itemIds.length) {
            swal({
                title: (WCBEL_DATA.strings && WCBEL_DATA.strings['please_select_one_item']) ? WCBEL_DATA.strings['please_select_one_item'] : "Please select one item",
                type: "warning"
            });
            return false;
        } else {
            wcbelOpenModal('#wcbel-modal-item-duplicate');
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".wcbel-check-item-main", function () {
        let checkbox_items = $(".wcbel-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wcbel-items-list tr").addClass("wcbel-tr-selected");
            checkbox_items.each(function () {
                $("#wcbel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            wcbelShowSelectionTools();
            $("#wcbel-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wcbel-items-list tr").removeClass("wcbel-tr-selected");
            $("#wcbel-export-items-selected").html("");
            wcbelHideSelectionTools();
            $("#wcbel-export-only-selected-items").prop("disabled", true);
            $("#wcbel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wcbel-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wcbel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            if ($(".wcbel-check-item:checked").length === $(".wcbel-check-item").length) {
                $(".wcbel-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("wcbel-tr-selected");
        } else {
            $("#wcbel-export-items-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("wcbel-tr-selected");
            $(".wcbel-check-item-main").prop("checked", false);
        }

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".wcbel-check-item:checkbox:checked").length > 0) {
            $("#wcbel-export-only-selected-items").prop("disabled", false);
            wcbelShowSelectionTools();
        } else {
            wcbelHideSelectionTools();
            $("#wcbel-export-only-selected-items").prop("disabled", true);
            $("#wcbel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#wcbel-bulk-edit-unselect", function () {
        $("input.wcbel-check-item").prop("checked", false);
        $("input.wcbel-check-item-main").prop("checked", false);
        wcbelHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#wcbel-column-profiles-choose", function () {
        let preset = $(this).val();
        $('.wcbel-column-profiles-fields input[type="checkbox"]').prop('checked', false);
        $('#wcbel-column-profile-select-all').prop('checked', false);
        $('.wcbel-column-profile-select-all span').text('Select All');
        $("#wcbel-column-profiles-apply").attr("data-preset-key",);
        if (defaultPresets && $.inArray(preset, defaultPresets) === -1) {
            $("#wcbel-column-profiles-update-changes").show();
        } else {
            $("#wcbel-column-profiles-update-changes").hide();
        }

        if (columnPresetsFields && columnPresetsFields[preset]) {
            columnPresetsFields[preset].forEach(function (val) {
                $('.wcbel-column-profiles-fields input[type="checkbox"][value="' + val + '"]').prop('checked', true);
            });
        }
    });

    $(document).on("keyup", "#wcbel-column-profile-search", function () {
        let wcbelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wcbel-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbelSearchFieldValue) > -1);
        });
    });

    $(document).on('change', '#wcbel-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.wcbel-column-profile-fields input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.wcbel-column-profile-fields input:checkbox').prop('checked', false);
        }
        $(".wcbel-column-profile-save-dropdown").show();
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on({
        mouseenter: function () {
            $(this)
                .children(".wcbel-calculator")
                .show();
        },
        mouseleave: function () {
            $(this)
                .children(".wcbel-calculator")
                .hide();
        }
    },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".wcbel-bulk-edit-delete-item", function () {
        $(this).find(".wcbel-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $(document).on("change", ".wcbel-column-profile-fields input:checkbox", function () {
        $(".wcbel-column-profile-save-dropdown").show();
    });

    $(document).on("click", ".wcbel-column-profile-save-dropdown", function () {
        $(this).find(".wcbel-column-profile-save-dropdown-buttons").slideToggle(200);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-wcbel-col-view"></li>');

    $(document).on({
        mouseenter: function () {
            $('#wp-admin-bar-wcbel-col-view').html('#' + $(this).attr('data-item-id') + ' | ' + $(this).attr('data-item-title') + ' [<span class="wcbel-col-title">' + $(this).attr('data-col-title') + '</span>] ');
        },
        mouseleave: function () {
            $('#wp-admin-bar-wcbel-col-view').html('');
        }
    },
        "#wcbel-items-list td"
    );

    $(document).on("click", ".wcbel-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let element = $(this).closest('div');
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wcbelNewImageElementID = $(this).attr("data-id");
        let wcbelProductID = $(this).attr("data-item-id");
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        if (type === "single") {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Image",
                button: {
                    text: "Choose Image"
                },
                multiple: false
            });
        } else {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Images",
                button: {
                    text: "Choose Images"
                },
                multiple: true
            });
        }

        mediaUploader.on("select", function () {
            let attachment = mediaUploader.state().get("selection").toJSON();
            switch (target) {
                case "inline-file":
                    $("#url-" + wcbelNewImageElementID).val(attachment[0].url);
                    break;
                case "inline-file-custom-field":
                    $("#wcbel-file-url").val(attachment[0].url);
                    $('#wcbel-file-id').val(attachment[0].id)
                    break;
                case "inline-edit":
                    $("#" + wcbelNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + wcbelNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("#wcbel-modal-image button[data-item-id=" + wcbelProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "variations-inline-edit":
                    $("#iwbve-variation-thumbnail-modal .iwbve-inline-image-preview").html("<img src='" + attachment[0].url + "' alt='' />");
                    $('#iwbve-variation-thumbnail-modal .iwbve-variations-table-thumbnail-inline-edit-button[data-button-type="save"]').attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("#wcbel-modal-gallery-items").append('<div class="wcbel-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wcbel-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
                case "bulk-edit-image":
                    element.find(".wcbel-bulk-edit-form-item-image").val(attachment[0].id);
                    element.find(".wcbel-bulk-edit-form-item-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wcbel-bulk-edit-form-remove-image"><i class="wcbel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-image":
                    element.find(".iwbve-variations-bulk-actions-image").val(attachment[0].id);
                    element.find(".iwbve-variations-bulk-actions-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="iwbve-variations-bulk-actions-remove-image"><i class="wcbel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-file":
                    element.find(".iwbve-variation-bulk-actions-file-item-url-input").val(attachment[0].url);
                    break;
                case "bulk-edit-file":
                    element.find(".wcbel-bulk-edit-form-item-file").val(attachment[0].id);
                    break;
                case "bulk-edit-gallery":
                    attachment.forEach(function (item) {
                        $(".wcbel-bulk-edit-form-item-gallery").append('<input type="hidden" value="' + item.id + '" data-field="value">');
                        $(".wcbel-bulk-edit-form-item-gallery-preview").append('<div><img src="' + item.url + '" width="43" height="43" alt=""><button type="button" data-id="' + item.id + '" class="wcbel-bulk-edit-form-remove-gallery-item"><i class="wcbel-icon-x"></i></button></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".wcbel-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("change", ".wcbel-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wcbel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wcbel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("click", ".wcbel-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".wcbel-column-manager-available-fields[data-action=" + action + "] input[data-type=field]:checkbox:checked");
        if (checked.length > 0) {
            $('.wcbel-column-manager-empty-text').hide();
            if (action === 'new') {
                $('.wcbel-column-manager-added-fields-wrapper .wcbel-box-loading').show();
            } else {
                $('#wcbel-modal-column-manager-edit-preset .wcbel-box-loading').show();
            }
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            wcbelColumnManagerAddField(fieldName, fieldLabel, action);
        }
    });

    $(".wcbel-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#wcbel_column_manager_delete_preset_key").val($this.val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbel-column-manager-delete-preset-form").submit();
            }
        });
    });

    $(document).on("keyup", ".wcbel-column-manager-search-field", function () {
        let wcbelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wcbel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbelSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".wcbel-column-manager-remove-field", function () {
        $(".wcbel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".wcbel-column-manager-right-item").remove();
        if ($('.wcbel-column-manager-added-fields-wrapper .wcbel-column-manager-right-item').length < 1) {
            $('.wcbel-column-manager-empty-text').show();
        }
    });

    if ($.fn.sortable) {
        let wcbelColumnManagerFields = $(".wcbel-column-manager-added-fields .items");
        wcbelColumnManagerFields.sortable({
            handle: ".wcbel-column-manager-field-sortable-btn",
            cancel: ""
        });
        wcbelColumnManagerFields.disableSelection();

        let wcbelMetaFieldItems = $(".wcbel-meta-fields-right");
        wcbelMetaFieldItems.sortable({
            handle: ".wcbel-meta-field-item-sortable-btn",
            cancel: ""
        });
        wcbelMetaFieldItems.disableSelection();
    }

    $(document).on("click", "#wcbel-add-meta-field-manual", function () {
        $(".wcbel-meta-fields-empty-text").hide();
        let input = $("#wcbel-meta-fields-manual_key_name");
        wcbelAddMetaKeysManual(input.val());
        input.val("");
    });

    $(document).on("click", "#wcbel-add-acf-meta-field", function () {
        let input = $("#wcbel-add-meta-fields-acf");
        if (input.val()) {
            $(".wcbel-meta-fields-empty-text").hide();
            wcbelAddACFMetaField(input.val(), input.find('option:selected').text(), input.find('option:selected').attr('data-type'));
            input.val("").change();
        }
    });

    $(document).on("click", ".wcbel-meta-field-remove", function () {
        $(this).closest(".wcbel-meta-fields-right-item").remove();
        if ($(".wcbel-meta-fields-right-item").length < 1) {
            $(".wcbel-meta-fields-empty-text").show();
        }
    });

    $(document).on("click", ".wcbel-history-delete-item", function () {
        $("#wcbel-history-clicked-id").attr("name", "delete").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbel-history-items").submit();
            }
        });
    });

    $(document).on("click", "#wcbel-history-clear-all-btn", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbel-history-clear-all").submit();
            }
        });
    });

    $(document).on("click", ".wcbel-history-revert-item", function () {
        $("#wcbel-history-clicked-id").attr("name", "revert").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbel-history-items").submit();
            }
        });
    });

    $(document).on('click', '.wcbel-modal', function (e) {
        if ($(e.target).hasClass('wcbel-modal') || $(e.target).hasClass('wcbel-modal-container') || $(e.target).hasClass('wcbel-modal-box')) {
            wcbelCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on('change', '#wcbel-filter-form-content [data-field=value], #wcbel-filter-form-content [data-field=from], #wcbel-filter-form-content [data-field=to]', function () {
        wcbelCheckFilterFormChanges();
    });

    $(document).on('change', 'input[type=number][data-field=to]', function () {
        let from = $(this).closest('.wcbel-form-group').find('input[type=number][data-field=from]');
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val('').addClass('wcbel-input-danger').focus();
        }
    });

    $(document).on('change', 'input[type=number][data-field=from]', function () {
        let to = $(this).closest('.wcbel-form-group').find('input[type=number][data-field=to]');
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val('').addClass('wcbel-input-danger');
        } else {
            $(this).removeClass('wcbel-input-danger')
        }
    });

    $(document).on('change', '#wcbel-switcher', function () {
        wcbelLoadingStart();
        $('#wcbel-switcher-form').submit();
    });

    $(document).on('click', 'span[data-target="#wcbel-modal-image"]', function () {
        let tdElement = $(this).closest('td');
        let modal = $('#wcbel-modal-image');
        let col_title = tdElement.attr('data-col-title');
        let id = $(this).attr('data-id');
        let image_id = $(this).attr('data-image-id');
        let item_id = tdElement.attr('data-item-id');
        let full_size_url = $(this).attr('data-full-image-src');
        let field = tdElement.attr('data-field');
        let field_type = tdElement.attr('data-field-type');

        $('#wcbel-modal-image-item-title').text(col_title);
        modal.find('.wcbel-open-uploader').attr('data-id', id).attr('data-item-id', item_id);
        modal.find('.wcbel-inline-image-preview').attr('data-image-preview-id', id).html('<img src="' + full_size_url + '" />');
        modal.find('.wcbel-image-preview-hidden-input').attr('id', id);
        modal.find('button[data-button-type="save"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-image-url', full_size_url).attr('data-image-id', image_id).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        modal.find('button[data-button-type="remove"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
    });

    $(document).on('click', 'button[data-target="#wcbel-modal-file"]', function () {
        let modal = $('#wcbel-modal-file');
        modal.find('#wcbel-modal-select-file-item-title').text($(this).closest('td').attr('data-col-title'));
        modal.find('#wcbel-modal-file-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        modal.find('#wcbel-file-id').val($(this).attr('data-file-id'));
        modal.find('#wcbel-file-url').val($(this).attr('data-file-url'));
    });

    $(document).on('click', '#wcbel-modal-file-clear', function () {
        let modal = $('#wcbel-modal-file');
        modal.find('#wcbel-file-id').val(0).change();
        modal.find('#wcbel-file-url').val('').change();
    });

    $(document).on('click', '.wcbel-sub-tab-title', function () {
        $(this).closest('.wcbel-sub-tab-titles').find('.wcbel-sub-tab-title').removeClass('active');
        $(this).addClass('active');

        $(this).closest('div').find('.wcbel-sub-tab-content').hide();
        $(this).closest('div').find('.wcbel-sub-tab-content[data-content="' + $(this).attr('data-content') + '"]').show();
    });

    if ($('.wcbel-sub-tab-titles').length > 0) {
        $('.wcbel-sub-tab-titles').each(function () {
            $(this).find('.wcbel-sub-tab-title').first().trigger('click');
        });
    }

    $(document).on("mouseenter", ".wcbel-thumbnail", function () {
        let position = $(this).offset();
        let imageHeight = $(this).find('img').first().height();
        let top = ((position.top - imageHeight) > $('#wpadminbar').offset().top) ? position.top - imageHeight : position.top + 15;

        $('.wcbel-thumbnail-hover-box').css({
            top: top,
            left: position.left - 100,
            display: 'block',
            height: imageHeight
        }).html($(this).find('.wcbel-original-thumbnail').clone());
    });

    $(document).on("mouseleave", ".wcbel-thumbnail", function () {
        $('.wcbel-thumbnail-hover-box').hide();
    });

    setTimeout(function () {
        $('#wcbel-column-profiles-choose').trigger('change');
    }, 500);

    $(document).on('click', '.wcbel-filter-form-action', function () {
        wcbelFilterFormClose();
    });

    $(document).on('click', '#wcbel-license-renew-button', function () {
        $(this).closest('#wcbel-license').find('.wcbel-license-form').slideDown();
    });

    $(document).on('click', '#wcbel-license-form-cancel', function () {
        $(this).closest('#wcbel-license').find('.wcbel-license-form').slideUp();
    });

    $(document).on('click', '#wcbel-license-deactivate-button', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#wcbel-license-deactivation-form').submit();
            }
        });
    });

    wcbelSetTipsyTooltip();

    $(window).on('resize', function () {
        wcbelDataTableFixSize();
    });

    $(document).on('click', 'body', function (e) {
        if (!$(e.target).hasClass('wcbel-status-filter-button') && $(e.target).closest('.wcbel-status-filter-button').length == 0) {
            $('.wcbel-top-nav-status-filter').hide();
        }

        if (!$(e.target).hasClass('wcbel-quick-filter') && $(e.target).closest('.wcbel-quick-filter').length == 0) {
            $('.wcbel-top-nav-filters').hide();
        }

        if (!$(e.target).hasClass('wcbel-post-type-switcher') && $(e.target).closest('.wcbel-post-type-switcher').length == 0) {
            $('.wcbel-top-nav-filters-switcher').hide();
        }

        if (!$(e.target).hasClass('wcbel-float-side-modal') &&
            !$(e.target).closest('.wcbel-float-side-modal-box').length &&
            !$('.sweet-overlay:visible').length &&
            !$('.wcbel-modal:visible').length &&
            $(e.target).attr('data-toggle') != 'float-side-modal' &&
            !$(e.target).closest('.select2-container').length &&
            !$(e.target).is('i') &&
            !$(e.target).closest('.media-modal').length &&
            !$(e.target).closest('.sweet-alert').length &&
            !$(e.target).closest('[data-toggle="float-side-modal"]').length &&
            !$(e.target).closest('[data-toggle="float-side-modal-after-confirm"]').length) {
            if ($('.wcbel-float-side-modal:visible').length && $('.wcbel-float-side-modal:visible').hasClass('wcbel-float-side-modal-close-with-confirm')) {
                swal({
                    title: ($('.wcbel-float-side-modal:visible').attr('data-confirm-message') && $('.wcbel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.wcbel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                    confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                    confirmButtonText: iwbveTranslate.iAmSure,
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $('.wcbel-float-side-modal:visible').removeClass('wcbel-float-side-modal-close-with-confirm');
                        wcbelCloseFloatSideModal();
                    }
                });
            } else {
                wcbelCloseFloatSideModal();
            }
        }
    });

    $(document).on('click', '.wcbel-status-filter-button', function () {
        $(this).closest('.wcbel-status-filter-container').find('.wcbel-top-nav-status-filter').toggle();
    });

    $(document).on('click', '.wcbel-quick-filter > button', function (e) {
        if (!$(e.target).closest('.wcbel-top-nav-filters').length) {
            $('.wcbel-top-nav-filters').slideToggle(150);
        }
    });
    $(document).on('click', '.wcbel-post-type-switcher > button', function (e) {
        if (!$(e.target).closest('.wcbel-top-nav-filters-switcher').length) {
            $('.wcbel-top-nav-filters-switcher').slideToggle(150);
        }
    });

    $(document).on('click', '.wcbel-bind-edit-switch', function () {
        if ($('#wcbel-bind-edit').prop('checked') === true) {
            $('#wcbel-bind-edit').prop('checked', false);
            $(this).removeClass('active');
        } else {
            $('#wcbel-bind-edit').prop('checked', true);
            $(this).addClass('active');
        }
    });

    if ($('#wcbel-bind-edit').prop('checked') === true) {
        $('.wcbel-bind-edit-switch').addClass('active');
    } else {
        $('.wcbel-bind-edit-switch').removeClass('active');
    }

    if ($('.wcbel-flush-message').length) {
        setTimeout(function () {
            $('.wcbel-flush-message').slideUp();
        }, 3000);
    }

    wcbelDataTableFixSize();

    // Inline edit
    $(document).on("click", 'td[data-action="inline-editable"]', function (e) {
        if ($(e.target).attr("data-type") !== "edit-mode" && $(e.target).find('[data-type="edit-mode"]').length === 0) {
            // Close All Inline Edit
            $('[data-type="edit-mode"]').each(function () {
                $(this).closest("span").html($(this).attr('data-val'));
            });

            // Open Clicked Inline Edit
            switch ($(this).attr("data-content-type")) {
                case "text":
                case "password":
                case "url":
                case "email":
                    $(this).children("span").html("<textarea data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "'>" + $(this).text().trim() + "</textarea>").children("textarea").focus().select();
                    break;
                case "numeric":
                case "regular_price":
                case "sale_price":
                    $(this).children("span").html("<input type='number' min='-1' data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "' value='" + $(this).text().trim().replaceAll(',', '') + "'>").children("input[type=number]").focus().select();
                    break;
            }
        }
    });

    // Discard Save
    $(document).on("click", function (e) {
        if ($(e.target).attr("data-action") !== "inline-editable" && $(e.target).attr("data-type") !== "edit-mode") {
            $('[data-type="edit-mode"]').each(function () {
                $(this).closest("span").html($(this).attr('data-val'));
            });
        }
    });

    $(document).on('click', '.wcbel-reload-table', function () {
        wcbelReloadProducts();
    });

    // Save Inline Edit By Enter Key
    $(document).on("keypress", '[data-type="edit-mode"]', function (event) {
        let wcbelKeyCode = event.keyCode ? event.keyCode : event.which;
        if (wcbelKeyCode === 13) {
            let productData = [];
            let productIds = [];
            let tdElement = $(this).closest('td');

            if ($('#wcbel-bind-edit').prop('checked') === true) {
                productIds = wcbelGetProductsChecked();
            }
            productIds.push($(this).attr("data-item-id"));

            productData.push({
                name: tdElement.attr('data-name'),
                sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
                type: tdElement.attr('data-update-type'),
                value: $(this).val(),
                operation: 'inline_edit'
            });

            $(this).closest("span").html($(this).val());
            wcbelProductEdit(productIds, productData);
        }
    });

    // fetch product data by click to bulk edit button
    $(document).on("click", "#wcbel-bulk-edit-bulk-edit-btn", function () {
        if ($(this).attr("data-fetch-product") === "yes") {
            let productID = $("input.wcbel-check-item:visible:checkbox:checked");
            if (productID.length === 1) {
                wcbelGetProductData(productID.val());
            } else {
                wcbelResetBulkEditForm();
            }
        }
    });

    $(document).on('click', '.wcbel-inline-edit-color-action', function () {
        $(this).closest('td').find('input.wcbel-inline-edit-action').trigger('change');
    });

    $(document).on("change", ".wcbel-inline-edit-action", function (e) {
        let $this = $(this);
        setTimeout(function () {
            if ($('div.xdsoft_datetimepicker:visible').length > 0) {
                e.preventDefault();
                return false;
            }

            if ($this.hasClass('wcbel-datepicker') || $this.hasClass('wcbel-timepicker') || $this.hasClass('wcbel-datetimepicker')) {
                if ($this.attr('data-val') == $this.val()) {
                    e.preventDefault();
                    return false;
                }
            }

            let productData = [];
            let productIds = [];
            let tdElement = $this.closest('td');
            if ($('#wcbel-bind-edit').prop('checked') === true) {
                productIds = wcbelGetProductsChecked();
            }
            productIds.push($this.attr("data-item-id"));
            let wcbelValue;
            switch (tdElement.attr("data-content-type")) {
                case 'checkbox_dual_mode':
                    wcbelValue = $this.prop("checked") ? "yes" : "no";
                    break;
                case 'checkbox':
                    let checked = [];
                    tdElement.find('input[type=checkbox]:checked').each(function () {
                        checked.push($(this).val());
                    });
                    wcbelValue = checked;
                    break;
                default:
                    wcbelValue = $this.val();
                    break;
            }

            productData.push({
                name: tdElement.attr('data-name'),
                sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
                type: tdElement.attr('data-update-type'),
                value: wcbelValue,
                operation: 'inline_edit'
            });

            wcbelProductEdit(productIds, productData);
        }, 250)
    });

    $(document).on("click", ".wcbel-inline-edit-clear-date", function () {
        let productData = [];
        let productIds = [];
        let tdElement = $(this).closest('td');

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));
        productData.push({
            name: tdElement.attr('data-name'),
            sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
            type: tdElement.attr('data-update-type'),
            value: '',
            operation: 'inline_edit'
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on("click", ".wcbel-edit-action-price-calculator", function () {
        let productId = $(this).attr("data-item-id");
        let fieldName = $(this).attr("data-field");
        let productIds = [];
        let productData = [];

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push(productId);

        productData.push({
            name: fieldName,
            sub_name: '',
            type: $(this).attr('data-update-type'),
            operator: $("#wcbel-" + fieldName + "-calculator-operator-" + productId).val(),
            value: $("#wcbel-" + fieldName + "-calculator-value-" + productId).val(),
            operator_type: $("#wcbel-" + fieldName + "-calculator-type-" + productId).val(),
            round: $("#wcbel-" + fieldName + "-calculator-round-" + productId).val()
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on("click", ".wcbel-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr('data-delete-type');
        let productIds = wcbelGetProductsChecked();

        if (!productIds.length && deleteType != 'all') {
            swal({
                title: "Please select one product",
                type: "warning"
            });
            return false;
        }

        let alertMessage = wcbelTranslate.areYouSure;

        if (deleteType == 'all') {
            alertMessage = ($('.wcbel-reset-filter-form:visible').length) ? "All of filtered products will be delete. Are you sure?" : "All of products will be delete. Are you sure?";
        }

        swal({
            title: alertMessage,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: wcbelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (productIds.length > 0 || deleteType == 'all') {
                    wcbelDeleteProduct(productIds, deleteType);
                } else {
                    swal({
                        title: "Please select one product",
                        type: "warning"
                    });
                }
            }
        });
    });

    $(document).on("click", "#wcbel-bulk-edit-duplicate-start", function () {
        let productIDs = $("input.wcbel-check-item:visible:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: wcbelTranslate.duplicateVariationsDisabled,
                    type: "warning"
                });
                return false;
            }
            return $(this).val();
        }).get();

        if (!productIDs.length) {
            swal({
                title: "Please select one product",
                type: "warning"
            });
            return false;
        } else {
            wcbelDuplicateProduct(productIDs, parseInt($("#wcbel-bulk-edit-duplicate-number").val()));
        }
    });

    $(document).on("click", ".wcbel-top-nav-duplicate-button", function () {
        let productIDs = $("input.wcbel-check-item:visible:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: wcbelTranslate.duplicateVariationsDisabled,
                    type: "warning"
                });
                return false;
            }
            return $(this).val();
        }).get();

        if (!productIDs.length) {
            swal({
                title: "Please select one product",
                type: "warning"
            });
            return false;
        } else {
            wcbelOpenModal('#wcbel-modal-item-duplicate');
        }
    });

    $(document).on("click", "#wcbel-create-new-item", function () {
        let count = $("#wcbel-new-item-count").val();
        wcbelCreateNewProduct(count);
    });

    $(document).on("click", ".wcbel-bulk-edit-show-variations-button", function () {
        if ($('#wcbel-bulk-edit-show-variations').prop("checked") === true) {
            $(this).removeClass('selected')
            $('#wcbel-bulk-edit-show-variations').prop("checked", false).change();
        } else {
            $(this).addClass('selected')
            $('#wcbel-bulk-edit-show-variations').prop("checked", true).change();
        }
    });

    $(document).on("change", "#wcbel-bulk-edit-show-variations", function () {
        if ($(this).prop("checked") === true) {
            $("tr[data-item-type=variation]").show();
            wcbelShowVariationSelectionTools();
        } else {
            $("tr[data-item-type=variation]").hide();
            wcbelHideVariationSelectionTools();
        }
    });


    $(document).on("click", ".wcbel-bulk-edit-select-all-variations-button", function () {
        if ($('#wcbel-bulk-edit-select-all-variations').prop("checked") === true) {
            $(this).removeClass('selected')
            $('#wcbel-bulk-edit-select-all-variations').prop("checked", false).change();
        } else {
            $(this).addClass('selected')
            $('#wcbel-bulk-edit-select-all-variations').prop("checked", true).change();
        }
    });

    $(document).on("change", "#wcbel-bulk-edit-select-all-variations", function () {
        if ($(this).prop("checked") === true) {
            $("input.wcbel-check-item[data-item-type=variation]").prop("checked", true);
        } else {
            $("input.wcbel-check-item[data-item-type=variation]").prop("checked", false);
        }
    });

    $(document).on("click", "#wcbel-variation-bulk-edit-manual-add", function () {
        let attributes = [];
        let currents = [];
        $(".wcbel-variation-bulk-edit-current-item-name").each(function () {
            currents.push($(this).find("span").text());
        });

        $(".wcbel-variation-bulk-edit-manual-item").each(function () {
            if ($(this).val()) {
                attributes.push([$(this).attr("data-attribute-name"), $(this).val()]);
            }
        });

        let label = attributes.map(function (val) {
            return val[1];
        });

        // generate if not exist
        if (jQuery.inArray(label.join(" | "), currents) === -1) {
            $(".wcbel-variation-bulk-edit-current-items").append('<div class="wcbel-variation-bulk-edit-current-item"><label class="wcbel-variation-bulk-edit-current-item-name"><input type="checkbox" name="variation_item[]" checked="checked" value="' + attributes.join("&&") + '"><span>' + label.join(" | ") + '</span></label><button type="button" class="wcbel-button wcbel-button-flat wcbel-variation-bulk-edit-current-item-sortable-btn" title="' + wcbelTranslate.drag + '"><i class=" wcbel-icon-menu1"></i></button><div class="wcbel-variation-bulk-edit-current-item-radio"><input type="radio" name="default_variation" title="' + wcbelTranslate.setAsDefault + '"></div></div>');
            $("#wcbel-variation-bulk-edit-do-bulk-variations").prop("disabled", false);
        }

        wcbelSetTipsyTooltip();
    });

    $(document).on("click", "#wcbel-variation-bulk-edit-generate", function () {
        let attributes = [];
        let currents = [];
        $(".wcbel-variation-bulk-edit-current-item-name").each(function () {
            currents.push($(this).find("span").text());
        });

        $(".wcbel-variation-bulk-edit-attribute-item").each(function () {
            if ($(this).find("select").val()) {
                attributes.push([$(this).find("select").attr("data-attribute-name"), $(this).find("select").val()]);
            }
        });

        let combinations = wcbelGetAllCombinations(attributes);

        if (combinations.length > 0) {
            $(".wcbel-variation-bulk-edit-current-items").html("");
            combinations.forEach(function (value) {
                let variation = value.map(function (val) {
                    return val[1];
                });
                $(".wcbel-variation-bulk-edit-current-items").append('<div class="wcbel-variation-bulk-edit-current-item"><label class="wcbel-variation-bulk-edit-current-item-name"><input type="checkbox" name="variation_item[]" checked="checked" value="' + value.join("&&") + '"><span>' + variation.join(" | ") + '</span></label><button type="button" class="wcbel-button wcbel-button-flat wcbel-variation-bulk-edit-current-item-sortable-btn" title="' + wcbelTranslate.drag + '"><i class=" wcbel-icon-menu1"></i></button><div class="wcbel-variation-bulk-edit-current-item-radio"><input type="radio" name="default_variation" value="' + value.join("&&") + '" title="' + wcbelTranslate.setAsDefault + '"></div></div>');
                $("#wcbel-variation-bulk-edit-do-bulk-variations").prop("disabled", false);
            });
        }
        wcbelSetTipsyTooltip();
    });

    $(document).on("click", "#wcbel-variation-bulk-edit-do-bulk-variations", function () {
        let productIds;
        let defaultVariation = $('.wcbel-variation-bulk-edit-current-item .wcbel-variation-bulk-edit-current-item-radio input:radio:checked[name="default_variation"]').val();
        let productsChecked = $("input.wcbel-check-item:visible:checkbox:checked");
        let attributes = [];
        $(".wcbel-variation-bulk-edit-attribute-item").each(function () {
            let selectItem = $(this).find("select");
            let ids = selectItem.select2().find(":selected").map(function () {
                return $(this).attr("data-id");
            }).toArray();
            if (selectItem.val() != null) {
                attributes.push([$(this).find("select").attr("data-attribute-name"), ids]);
            }
        });

        let variations = [];
        $('input:checkbox:checked[name="variation_item[]"]').each(function () {
            variations.push([$(this).val(), $(this).attr("data-id")]);
        });

        if (productsChecked.length > 0) {
            let notVariable = 0;
            productsChecked.each(function () {
                if ($(this).attr("data-item-type") !== "variable") {
                    notVariable++;
                }
            });
            if (variations.length > 0) {
                productIds = productsChecked.map(function () {
                    return $(this).val();
                }).get();
                if (notVariable > 0) {
                    swal({
                        title: wcbelTranslate.selectedProductsIsNotVariable,
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                        confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                        confirmButtonText: wcbelTranslate.iAmSure,
                        closeOnConfirm: true
                    }, function (isConfirm) {
                        if (isConfirm === true) {
                            wcbelSetProductsVariations(productIds, attributes, variations, defaultVariation);
                        }
                    });
                } else {
                    wcbelSetProductsVariations(productIds, attributes, variations, defaultVariation);
                }
            } else {
                swal({
                    title: wcbelTranslate.variationRequired,
                    type: "warning"
                });
            }
        } else {
            swal({
                title: wcbelTranslate.productRequired,
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbel-variation-delete-selected", function () {
        let deleteType = "single_product";
        let productIds;
        let variations;
        let productsChecked = $("input.wcbel-check-item:visible:checkbox:checked[data-item-type=variable]");
        if (productsChecked.length > 0) {
            productIds = productsChecked.map(function () {
                return $(this).val();
            }).get();
            variations = $("#wcbel-variation-single-delete-items input:checkbox:checked").map(function () {
                return $(this).val();
            }).get();
            swal({
                title: wcbelTranslate.areYouSure,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                confirmButtonText: wcbelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm === true) {
                    wcbelDeleteProductsVariations(productIds, deleteType, variations);
                }
            });
        } else {
            swal({
                title: wcbelTranslate.variableProductRequired,
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbel-variation-delete-all", function () {
        let deleteType = "all_variations";
        let productIds;
        let productsChecked = $("input.wcbel-check-item:visible:checkbox:checked[data-item-type=variable]");
        if (productsChecked.length > 0) {
            productIds = productsChecked.map(function () {
                return $(this).val();
            }).get();
            swal({
                title: wcbelTranslate.areYouSure,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                confirmButtonText: wcbelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm === true) {
                    wcbelDeleteProductsVariations(
                        productIds,
                        deleteType,
                        "all_variations"
                    );
                }
            });
        } else {
            swal({
                title: wcbelTranslate.variableProductRequired,
                type: "warning"
            });
        }
    });

    $(document).on("keyup", "#wcbel-variation-attaching-variable-id", function () {
        if ($(this).val() !== "") {
            $("#wcbel-variation-attaching-get-variations").prop("disabled", false);
        } else {
            $("#wcbel-variation-attaching-get-variations").attr("disabled", "disabled");
        }
    });

    $(document).on("click", "#wcbel-variation-attaching-get-variations", function () {
        getProductVariationsForAttach($("#wcbel-variation-attaching-variable-id").val(), $("#wcbel-variations-attaching-attributes").val(), $("#wcbel-variations-attaching-attribute-item").val());
    });

    $(document).on("change", "#wcbel-variations-attaching-attributes", function () {
        getAttributeValuesForAttach($(this).val());
    });

    $(document).on('click', '#wcbel-variation-attaching-start-attaching', function () {
        let productId = $('#wcbel-variation-attaching-variable-id').val();
        let attributeKey = $('#wcbel-variations-attaching-attributes').val();
        let variationId = [];
        let attributeItem = [];
        $('#wcbel-variations-attaching-product-variations .wcbel-variation-bulk-edit-current-item').map(function () {
            variationId.push($(this).find('input[type=hidden][name="variation_id[]"]').val());
            attributeItem.push($(this).find('select[name="attribute_item[]"]').val());
        });
        wcbelVariationAttaching(productId, attributeKey, variationId, attributeItem)
    });

    $(document).on("click", "#wcbel-column-profiles-save-as-new-preset", function () {
        let presetKey = $("#wcbel-column-profiles-choose").val();
        let items = $(".wcbel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wcbelSaveColumnProfile(presetKey, items, "save_as_new");
    });

    $(document).on("click", "#wcbel-column-profiles-update-changes", function () {
        let presetKey = $("#wcbel-column-profiles-choose").val();
        let items = $(".wcbel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wcbelSaveColumnProfile(presetKey, items, "update_changes");
    });

    $(document).on("click", ".wcbel-bulk-edit-filter-profile-load", function () {
        wcbelLoadFilterProfile($(this).val());
        if ($(this).val() !== "default") {
            $("#wcbel-bulk-edit-reset-filter").show();
        }
        $(".wcbel-filter-profiles-items tr").removeClass("wcbel-filter-profile-loaded");
        $(this).closest("tr").addClass("wcbel-filter-profile-loaded");

        if (WCBEL_DATA.wcbel_settings.close_popup_after_applying == 'yes') {
            wcbelCloseFloatSideModal();
        }
    });

    $(document).on("click", ".wcbel-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
        swal({
            title: wcbelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: wcbelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wcbelDeleteFilterProfile(presetKey);
                if (item.hasClass('wcbel-filter-profile-loaded')) {
                    $('.wcbel-filter-profiles-items tbody tr:first-child').addClass('wcbel-filter-profile-loaded').find('input[type=radio]').prop('checked', true);
                    $('#wcbel-bulk-edit-reset-filter').trigger('click');
                }
                if (WCBEL_DATA.wcbel_settings.close_popup_after_applying == 'yes') {
                    wcbelCloseFloatSideModal();
                }
                item.remove();
            }
        });
    });

    $(document).on("change", "input.wcbel-filter-profile-use-always-item", function () {
        if ($(this).val() !== "default") {
            $("#wcbel-bulk-edit-reset-filter").show();
        } else {
            $("#wcbel-bulk-edit-reset-filter").hide();
        }
        wcbelFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".wcbel-filter-form-action", function (e) {
        let data = wcbelGetCurrentFilterData();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search" && $('#wcbel-quick-search-text').val() !== '') {
            wcbelResetFilterForm();
            $('#wcbel-bulk-edit-reset-filter').hide();
        }
        if (action === "pro_search") {
            $('#wcbel-bulk-edit-reset-filter').show();
            wcbelResetQuickSearchForm();
            $(".wcbel-filter-profiles-items tr").removeClass("wcbel-filter-profile-loaded");
            $('input.wcbel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
            wcbelFilterProfileChangeUseAlways("default");
        }
        wcbelProductsFilter(data, action, null, page);
    });

    $(document).on("click", "#wcbel-filter-form-reset", function () {
        wcbelResetFilters();
    });

    $(document).on("click", "#wcbel-bulk-edit-reset-filter", function () {
        wcbelResetFilters();
    });

    $(document).on("change", "#wcbel-quick-search-field", function () {
        let options = $("#wcbel-quick-search-operator option");
        switch ($(this).val()) {
            case "title":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 0);
                    $(this).prop("disabled", false);
                });
                break;
            case "id":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 1);
                    if ($(this).attr("value") === "exact") {
                        $(this).prop("disabled", false);
                    } else {
                        $(this).prop("disabled", true);
                    }
                });
                break;
        }
    });

    // Quick Per Page
    $("#wcbel-quick-per-page").on("change", function () {
        wcbelChangeCountPerPage($(this).val());
    });

    $(document).on("click", ".wcbel-edit-action-with-button", function () {
        let productIds = [];
        let productData = [];
        let modal = $(this).closest('.wcbel-modal');

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));

        let wcbelValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                wcbelValue = tinymce.get("wcbel-text-editor").getContent();
                break;
            case "select_products":
                wcbelValue = $('#wcbel-select-products-value').val();
                break;
            case "multi_select":
                if (modal) {
                    wcbelValue = modal.find('.wcbel-modal-acf-taxonomy-multi-select-value').val();
                }
                break;
            case "select_files":
                let names = $('.wcbel-inline-edit-file-name').map(function () {
                    return $(this).val();
                }).get();

                let urls = $('.wcbel-inline-edit-file-url').map(function () {
                    return $(this).val();
                }).get();

                wcbelValue = {
                    files_name: names,
                    files_url: urls,
                };
                break;
            case "file":
                wcbelValue = $('#wcbel-modal-file #wcbel-file-id').val();
                break;
            case "image":
                wcbelValue = $(this).attr("data-image-id");
                break;
            case "gallery":
                wcbelValue = $("#wcbel-modal-gallery-items input.wcbel-inline-edit-gallery-image-ids").map(function () {
                    return $(this).val();
                }).get();
                break;
        }

        productData.push({
            name: $(this).attr('data-name'),
            sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
            type: $(this).attr('data-update-type'),
            value: wcbelValue,
            operation: 'inline_edit'
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on("click", ".wcbel-load-text-editor", function () {
        tinymce.get("wcbel-text-editor").setContent('');

        let productId = $(this).attr("data-item-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $('#wcbel-modal-text-editor-item-title').text($(this).attr('data-item-name'));
        $("#wcbel-text-editor-apply").attr("data-field", field).attr("data-field-type", fieldType).attr("data-item-id", productId);
        $.ajax({
            url: WCBEL_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "wcbel_get_text_editor_content",
                nonce: WCBEL_DATA.ajax_nonce,
                product_id: productId,
                field: field,
                field_type: fieldType
            },
            success: function (response) {
                if (response.success && response.content !== '') {
                    tinymce.get("wcbel-text-editor").setContent(response.content);
                    tinymce.execCommand('mceFocus', false, 'wcbel-text-editor');
                }
            },
            error: function () { }
        });
    });

    $(document).on("click", "#wcbel-create-new-product-taxonomy", function () {
        if ($("#wcbel-new-product-category-name").val() !== "") {
            let taxonomyInfo = {
                name: $("#wcbel-new-product-taxonomy-name").val(),
                slug: $("#wcbel-new-product-taxonomy-slug").val(),
                parent: $("#wcbel-new-product-taxonomy-parent").val(),
                description: $("#wcbel-new-product-taxonomy-description").val(),
                product_id: $(this).attr("data-item-id"),
                modal_id: $(this).attr('data-closest-id')
            };
            wcbelAddProductTaxonomy(taxonomyInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: wcbelTranslate.taxonomyNameRequired,
                type: "warning"
            });
        }
    });

    //Search
    $(document).on("keyup", ".wcbel-search-in-list", function () {
        let wcbelSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-id") + " .wcbel-product-items-list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbelSearchValue) > -1);
        });
    });

    $(document).on("click", "#wcbel-create-new-product-attribute", function () {
        if ($("#wcbel-new-product-attribute-name").val() !== "") {
            let attributeInfo = {
                name: $("#wcbel-new-product-attribute-name").val(),
                slug: $("#wcbel-new-product-attribute-slug").val(),
                description: $("#wcbel-new-product-attribute-description").val(),
                product_id: $(this).attr("data-item-id")
            };
            wcbelAddProductAttribute(attributeInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: wcbelTranslate.attributeNameRequired,
                type: "warning"
            });
        }
    });

    $(document).on('click', 'button[data-target="#wcbel-modal-select-products"]', function () {
        let childrenIds = $(this).attr('data-children-ids').split(',');
        let tdElement = $(this).closest('td');
        $('#wcbel-select-products-value').val('').change();
        $('#wcbel-modal-select-products-item-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-select-products .wcbel-edit-action-with-button').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        wcbelSetSelectedProducts(childrenIds);
    });

    $(document).on('click', '#wcbel-modal-select-files-add-file-item', function () {
        wcbelAddNewFileItem();
    });

    $(document).on('click', 'button[data-toggle=modal][data-target="#wcbel-modal-select-files"]', function () {
        let tdElement = $(this).closest('td');
        $('#wcbel-modal-select-files-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr(('data-field'))).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        $('#wcbel-modal-select-files-item-title').text($(this).closest('td').attr('data-col-title'));
        wcbelGetProductFiles($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wcbel-inline-edit-file-remove-item', function () {
        $(this).closest('.wcbel-modal-select-files-file-item').remove();
    });

    if ($.fn.sortable) {
        let wcbelSelectFiles = $(".wcbel-inline-select-files");
        wcbelSelectFiles.sortable({
            handle: ".wcbel-select-files-sortable-btn",
            cancel: ""
        });
        wcbelSelectFiles.disableSelection();

        // yikes custom tabs 
        let wcbelTabItems = $("#wcbel-modal-yikes-custom-tabs");
        wcbelTabItems.sortable({
            handle: ".wcbel-yikes-tab-item-sort",
            cancel: ""
        });
        wcbelTabItems.disableSelection();
    }

    $(document).on("change", ".wcbel-bulk-edit-form-variable", function () {
        let newVal = $(this).val() ? $(this).closest("div").find("input[type=text]").val() + "{" + $(this).val() + "}" : "";
        $(this).closest("div").find("input[type=text]").first().val(newVal).change();
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        let id = $(this).closest(".wcbel-form-group").find("label").attr("for");
        let $this = $(this);

        if ($(this).val() === "text_replace") {
            $(this).closest(".wcbel-form-group").append("<div class='wcbel-bulk-edit-form-extra-field'>" +
                "<select id='" + id + "-sensitive' data-field='sensitive'>" +
                "<option value='yes'>" + wcbelTranslate.sameCase + "</option>" +
                "<option value='no'>" + wcbelTranslate.ignoreCase + "</option>" +
                "</select>" +
                "<input type='text' id='" + id + "-replace' data-field='replace' placeholder='" + wcbelTranslate.enterText + "'>" +
                "<select class='wcbel-bulk-edit-form-variable' title='" + wcbelTranslate.selectVariable + "' data-field='variable'>" +
                "<option value=''>" + wcbelTranslate.variable + "</option>" +
                "<option value='title'>" + wcbelTranslate.title + "</option>" +
                "<option value='id'>" + wcbelTranslate.id + "</option>" +
                "<option value='sku'>" + wcbelTranslate.sku + "</option>" +
                "<option value='menu_order'>Menu Order</option>" +
                "<option value='parent_id'>" + wcbelTranslate.parentId + "</option>" +
                "<option value='parent_title'>" + wcbelTranslate.parentTitle + "</option>" +
                "<option value='parent_sku'>" + wcbelTranslate.parentSku + "</option>" +
                "<option value='regular_price'>" + wcbelTranslate.regularPrice + "</option>" +
                "<option value='sale_price'>" + wcbelTranslate.salePrice + "</option>" +
                "</select>" +
                "</div>");
        } else if ($(this).val() === "number_round") {
            $(this).closest(".wcbel-form-group").append('<div class="wcbel-bulk-edit-form-extra-field"><select id="' + id + '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>');
        } else {
            $(this).closest(".wcbel-form-group").find(".wcbel-bulk-edit-form-extra-field").remove();
        }
        if ($.inArray($(this).val(), ['number_clear', 'text_remove_duplicate']) !== -1) {
            $(this).closest(".wcbel-form-group").find('input[data-field=value]').val('').prop('disabled', true);
            $(this).closest(".wcbel-form-group").find('select[data-field=variable]').val('').prop('disabled', true);
        } else {
            $(this).closest(".wcbel-form-group").find('input[data-field=value]').prop('disabled', false);
            $(this).closest(".wcbel-form-group").find('select[data-field=variable]').prop('disabled', false);
        }

        setTimeout(function () {
            changedTabs($this);
        }, 150)
    });

    $("#wcbel-float-side-modal-bulk-edit .wcbel-tab-content-item").on("change", "[data-field=value]", function () {
        changedTabs($(this));
    });

    $(document).on("change", ".wcbel-date-from", function () {
        let field_to = $('#' + $(this).attr('data-to-id'));
        let datepicker = true;
        let timepicker = false;
        let format = 'Y/m/d';

        if ($(this).hasClass('wcbel-datetimepicker')) {
            timepicker = true;
            format = 'Y/m/d H:i'
        }

        if ($(this).hasClass('wcbel-timepicker')) {
            datepicker = false;
            timepicker = true;
            format = 'H:i'
        }

        field_to.val("");
        field_to.datetimepicker("destroy");
        field_to.datetimepicker({
            format: format,
            datepicker: datepicker,
            timepicker: timepicker,
            minDate: $(this).val(),
        });
    });

    $(document).on("click", ".wcbel-bulk-edit-form-remove-image", function () {
        $(this).closest("div").remove();
        $("#wcbel-bulk-edit-form-product-image").val("");
    });

    $(document).on("click", ".wcbel-bulk-edit-form-remove-gallery-item", function () {
        $(this).closest("div").remove();
        $("#wcbel-bulk-edit-form-product-gallery input[value=" + $(this).attr("data-id") + "]").remove();
    });

    var sortType = 'DESC'
    $(document).on('click', '.wcbel-sortable-column', function () {
        if (sortType === 'DESC') {
            sortType = 'ASC';
            $(this).find('i.wcbel-sortable-column-icon').text('d');
        } else {
            sortType = 'DESC';
            $(this).find('i.wcbel-sortable-column-icon').text('u');
        }
        wcbelSortByColumn($(this).attr('data-column-name'), sortType);
    });

    $(document).on("click", ".wcbel-column-manager-edit-field-btn", function () {
        $('#wcbel-modal-column-manager-edit-preset .wcbel-box-loading').show();
        let presetKey = $(this).val();
        $('#wcbel-modal-column-manager-edit-preset .items').html('');
        $("#wcbel-column-manager-edit-preset-key").val(presetKey);
        $("#wcbel-column-manager-edit-preset-name").val($(this).attr("data-preset-name"));
        wcbelColumnManagerFieldsGetForEdit(presetKey);
    });

    $(document).on("click", "#wcbel-bulk-edit-undo", function () {
        wcbelHistoryUndo();
    });

    $(document).on("click", "#wcbel-bulk-edit-redo", function () {
        wcbelHistoryRedo();
    });

    $(document).on("click", "#wcbel-history-filter-apply", function () {
        let filters = {
            operation: $("#wcbel-history-filter-operation").val(),
            author: $("#wcbel-history-filter-author").val(),
            fields: $("#wcbel-history-filter-fields").val(),
            date: {
                from: $("#wcbel-history-filter-date-from").val(),
                to: $("#wcbel-history-filter-date-to").val()
            }
        };
        wcbelHistoryFilter(filters);
    });

    $(document).on("click", "#wcbel-history-filter-reset", function () {
        $(".wcbel-history-filter-fields input").val("");
        $(".wcbel-history-filter-fields select").val("").change();
        wcbelHistoryFilter();
    });

    $(document).on("change", ".wcbel-meta-fields-main-type", function () {
        let item = $(this).closest('.wcbel-meta-fields-right-item');
        if ($(this).val() === "textinput") {
            item.find(".wcbel-meta-fields-sub-type").show();
        } else {
            item.find(".wcbel-meta-fields-sub-type").hide();
        }

        if ($.inArray($(this).val(), ['select', 'array']) !== -1) {
            item.find(".wcbel-meta-fields-key-value").show();
        } else {
            item.find(".wcbel-meta-fields-key-value").hide();
        }
    });

    $("#wcbel-column-manager-add-new-preset").on("submit", function (e) {
        if ($(this).find(".wcbel-column-manager-added-fields .items .wcbel-column-manager-right-item").length < 1) {
            e.preventDefault();
            swal({
                title: wcbelTranslate.plzAddColumns,
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbel-bulk-edit-form-reset", function () {
        wcbelResetBulkEditForm();
    });

    $(document).on("click", "#wcbel-filter-form-save-preset", function () {
        let presetName = $("#wcbel-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = wcbelGetProSearchData();
            wcbelSaveFilterPreset(data, presetName);
        } else {
            swal({
                title: wcbelTranslate.presetNameRequired,
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbel-bulk-edit-form-do-bulk-edit", function (e) {
        let productIds = wcbelGetProductsChecked();
        let productData = [];

        $("#wcbel-float-side-modal-bulk-edit .wcbel-form-group").each(function () {
            let value;
            if ($(this).find("[data-field=value]").length > 1) {
                value = $(this).find("[data-field=value]").map(function () {
                    if ($(this).val() !== '') {
                        return $(this).val();
                    }
                }).get();
            } else {
                value = $(this).find("[data-field=value]").val();
            }

            if (typeof $(this).attr('data-name') != 'undefined') {
                if (
                    ($.isArray(value) && value.length > 0) ||
                    (!$.isArray(value) && value != '' && typeof value != 'undefined') ||
                    ($(this).find('select[data-field="used_for_variations"]').length > 0 && $(this).find('select[data-field="used_for_variations"]').val() != '') ||
                    ($(this).find('select[data-field="attribute_is_visible"]').length > 0 && $(this).find('select[data-field="attribute_is_visible"]').val() != '') ||
                    ($.inArray($(this).find("[data-field=operator]").val(), ['text_remove_duplicate', 'number_clear']) !== -1)
                ) {
                    let name = $(this).attr('data-name');
                    let type = $(this).attr('data-type');

                    if ($(this).find("[data-field=operator]").val() == 'text_remove_duplicate') {
                        name = "remove_duplicate";
                        type = 'remove_duplicate';
                        value = 'trash';
                        if (productIds.length < 1) {
                            productIds = [0]
                        }
                    }

                    productData.push({
                        name: name,
                        sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
                        type: type,
                        operator: $(this).find("[data-field=operator]").val(),
                        value: value,
                        replace: $(this).find("[data-field=replace]").val(),
                        sensitive: $(this).find("[data-field=sensitive]").val(),
                        round: $(this).find("[data-field=round]").val(),
                        used_for_variations: $(this).find('select[data-field="used_for_variations"]').val(),
                        attribute_is_visible: $(this).find('select[data-field="attribute_is_visible"]').val(),
                        operation: 'bulk_edit'
                    });
                }
            }
        });

        if (productIds.length > 0) {
            if (WCBEL_DATA.wcbel_settings.close_popup_after_applying == 'yes') {
                wcbelCloseFloatSideModal();
            }
            wcbelProductEdit(productIds, productData);
            if (WCBEL_DATA.wcbel_settings.keep_filled_data_in_bulk_edit_form == 'no') {
                wcbelResetBulkEditForm();
            }
        } else {
            swal({
                title: wcbelTranslate.areYouSureForEditAllFilteredProducts,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                confirmButtonText: wcbelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    if (WCBEL_DATA.wcbel_settings.close_popup_after_applying == 'yes') {
                        wcbelCloseFloatSideModal();
                    }
                    wcbelProductEdit(productIds, productData);
                    if (WCBEL_DATA.wcbel_settings.keep_filled_data_in_bulk_edit_form == 'yes') {
                        wcbelResetBulkEditForm();
                    }
                }
            });
        }
    });

    $(document).on('click', '[data-target="#wcbel-modal-new-item"]', function () {
        $('#wcbel-new-item-title').html(wcbelTranslate.newProduct);
        $('#wcbel-new-item-description').html(wcbelTranslate.newProductNumber);
    });

    $(document).on('click', '[data-target="#wcbel-modal-text-editor"]', function () {
        let tdElement = $(this).closest('td');
        $('#wcbel-modal-text-editor-item-title').html($(this).attr(''));
        $('#wcbel-text-editor-apply').attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($('#wcbel-quick-search-text').val() !== '' && $($('#wcbel-last-modal-opened').val()).css('display') !== 'block' && $('.wcbel-tabs-list button[data-content=bulk-edit]').hasClass('selected')) {
                wcbelReloadProducts();
                $('#wcbel-quick-search-reset').show();
            }
            if ($("#wcbel-modal-new-product-taxonomy").css("display") === "block") {
                $("#wcbel-create-new-product-taxonomy").trigger("click");
            }
            if ($("#wcbel-modal-new-item").css("display") === "block") {
                $("#wcbel-create-new-item").trigger("click");
            }
            if ($("#wcbel-modal-item-duplicate").css("display") === "block") {
                $("#wcbel-bulk-edit-duplicate-start").trigger("click");
            }

            // filter form
            if ($('#wcbel-float-side-modal-filter:visible').length) {
                $('#wcbel-float-side-modal-filter:visible').find('.wcbel-filter-form-action').trigger('click');
            }
        }
    });

    let query;
    $(".wcbel-get-products-ajax").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: WCBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wcbel_get_products_name",
                    nonce: WCBEL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: wcbelTranslate.enterProductName,
        minimumInputLength: 3
    });

    $(document).on("select2:select", "#wcbel-variation-bulk-edit-attributes", function (e) {
        getAttributeValues(e.params.data.id, "#wcbel-variation-bulk-edit-attributes-added");
    });

    $(document).on("select2:select", "#wcbel-variation-bulk-edit-delete-attributes", function (e) {
        getAttributeValuesForDelete(e.params.data.id, "#wcbel-variation-bulk-edit-delete-attributes-added");
    });

    $(document).on("select2:unselect", "#wcbel-variation-bulk-edit-attributes", function (e) {
        $("div[data-id=wcbel-variation-bulk-edit-attribute-item-" + e.params.data.id + "]").remove();
        $(".wcbel-variation-bulk-edit-attribute-item[data-id=" + e.params.data.id + "]").remove();
    });

    $(document).on("select2:unselect", "#wcbel-variation-bulk-edit-delete-attributes", function (e) {
        $("div[data-id=wcbel-variation-bulk-edit-delete-attribute-item-" + e.params.data.id + "]").remove();
    });

    $(document).on("click", ".wcbel-bulk-edit-variations", function () {
        // get product variations
        let productID = $("input.wcbel-check-item:visible:checkbox:checked");

        if (!productID.length) {
            swal({
                title: "Please select one product",
                type: "warning"
            });
            return false;
        }

        wcbelOpenFloatSideModal($(this).attr('data-target'));

        // reset fields
        $("#wcbel-variation-bulk-edit-attributes-added").html("");
        $("#wcbel-variation-bulk-edit-attributes").val("").change();
        $(".wcbel-variation-bulk-edit-individual-items").html("");
        $(".wcbel-variation-bulk-edit-current-items").html("");
        $("#wcbel-variation-single-delete-items").html("");
        $("#wcbel-variation-single-delete-variations").hide();
        $("#wcbel-variation-bulk-edit-do-bulk-variations").attr("disabled", "disabled");
        $("#wcbel-variation-bulk-edit-manual-add").attr("disabled", "disabled");
        $("#wcbel-variation-bulk-edit-generate").attr("disabled", "disabled");
        $("#wcbel-variations-multiple-products-delete-variation").show();
        $("#wcbel-variation-attaching-variable-id").val("").change();
        $("#wcbel-variation-attaching-get-variations").attr("disabled", "disabled");
        $("#wcbel-variations-attaching-product-variations").html("");

        // set sortable
        let variationCurrentItems = $(".wcbel-variation-bulk-edit-current-items");
        variationCurrentItems.sortable({
            handle: ".wcbel-variation-bulk-edit-current-item-sortable-btn",
            cancel: ""
        });
        variationCurrentItems.disableSelection();

        if (productID.length === 1) {
            $('.wcbel-variation-bulk-edit-loading').show();
            wcbelGetProductVariations(productID.val());
            $("#wcbel-variation-single-delete-variations").show();
            $("#wcbel-variations-multiple-products-delete-variation").hide();
            $("#wcbel-variation-attaching-variable-id").val(productID.val()).change();
            $("#wcbel-variation-attaching-get-variations").prop("disabled", false).trigger("click");
        }
    });

    $(document).on("change", "input:radio[name=create_variation_mode]", function () {
        if ($(this).attr("data-mode") === "all_combination") {
            $("#wcbel-variation-bulk-edit-individual").hide();
            $("#wcbel-variation-bulk-edit-generate").show();
        } else {
            $("#wcbel-variation-bulk-edit-generate").hide();
            $("#wcbel-variation-bulk-edit-individual").show();
        }
    });

    $(document).on("select2:select", ".wcbel-select2-ajax", function (e) {
        if ($(".wcbel-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").length === 0) {
            $(".wcbel-variation-bulk-edit-individual-items").append('<div data-id="' + $(this).attr("id") + '"><select class="wcbel-variation-bulk-edit-manual-item" data-attribute-name="' + $(this).attr("data-attribute-name") + '"></select></div>');
        }
        $(".wcbel-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").find("select").append('<option value="' + e.params.data.id + '">' + e.params.data.id + "</option>");
        $("#wcbel-variation-bulk-edit-manual-add").prop("disabled", false);
        $("#wcbel-variation-bulk-edit-generate").prop("disabled", false);
    });

    $(document).on("select2:unselect", ".wcbel-select2-ajax", function (e) {
        $(".wcbel-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").find("option[value=" + e.params.data.id + "]").remove();
        if ($(".wcbel-variation-bulk-edit-attribute-item").find(".select2-selection__choice").length === 0) {
            $("#wcbel-variation-bulk-edit-manual-add").attr("disabled", "disabled");
            $("#wcbel-variation-bulk-edit-generate").attr("disabled", "disabled");
        }
        if ($(this).val() === null) {
            $("div[data-id=wcbel-variation-bulk-edit-attribute-item-" + $(this).attr("data-attribute-name") + "]").remove();
        }
    });

    $(document).on("change", "input:radio[name=delete_variation_mode]", function () {
        if ($(this).attr("data-mode") === "delete_all") {
            $("#wcbel-variation-delete-single-delete").hide();
            $("#wcbel-variation-delete-delete-all").show();
        } else {
            $("#wcbel-variation-delete-delete-all").hide();
            $("#wcbel-variation-delete-single-delete").show();
        }
    });

    $(document).on("click", "#wcbel-variation-delete-selected-variation", function () {
        let deleteType = "multiple_product";
        let productIds;
        let variations = [];
        let attributeName;
        let productsChecked = $("input.wcbel-check-item:visible:checkbox:checked[data-item-type=variable]");
        if (productsChecked.length > 0) {
            productIds = productsChecked.map(function () {
                return $(this).val();
            }).get();

            $("#wcbel-variation-bulk-edit-delete-attributes-added select").each(function () {
                attributeName = "attribute_pa_" + encodeURIComponent($(this).attr("data-name"));
                attributeName = attributeName.toLowerCase();
                variations.push({
                    [attributeName]: $(this).val()
                });
            });

            swal({
                title: wcbelTranslate.areYouSure,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                confirmButtonText: wcbelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm === true) {
                    wcbelDeleteProductsVariations(productIds, deleteType, variations);
                }
            });
        } else {
            swal({
                title: variableProductRequired,
                type: "warning"
            });
        }
    });

    $(document).on('click', '.wcbel-bulk-edit-status-filter-item', function () {
        $('.wcbel-top-nav-status-filter').hide();

        $('.wcbel-bulk-edit-status-filter-item').removeClass('active');
        $(this).addClass('active');
        $('.wcbel-status-filter-selected-name').text(' - ' + $(this).text());

        if ($(this).attr('data-status') === 'all') {
            $('#wcbel-filter-form-reset').trigger('click');
        } else {
            $('#wcbel-filter-form-product-status').val($(this).attr('data-status')).change();
            setTimeout(function () {
                $('#wcbel-filter-form-get-products').trigger('click');
            }, 250);
        }
    });

    $(document).on('click', '.wcbel-reset-filter-form', function () {
        wcbelResetFilters();
    });

    $(document).on("change", "#wcbel-variation-single-delete-items input:checkbox", function () {
        if ($("#wcbel-variation-single-delete-items input:checkbox:checked").length > 0) {
            $("#wcbel-variation-delete-selected").prop("disabled", false);
        } else {
            $("#wcbel-variation-delete-selected").attr("disabled", "disabled");
        }
    });

    $(document).on("click", "#wcbel-filter-form-get-products", function () {
        wcbelFilterFormCheckAttributes();
        wcbelCheckResetFilterButton();
    });

    $(document).on("change", "#wcbel-variation-bulk-edit-attributes", function () {
        if ($(this).val() === null) {
            $("#wcbel-variation-bulk-edit-generate").attr("disabled", "disabled");
            $("#wcbel-variation-bulk-edit-manual-add").attr("disabled", "disabled");
        }
    });

    $(document).on("click", ".wcbel-inline-edit-taxonomy-save", function () {
        let productData = [];
        let productIds = [];

        let value = $("#wcbel-modal-taxonomy-" + $(this).attr("data-field") + "-" + $(this).attr("data-item-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));
        productData.push({
            name: $(this).attr('data-name'),
            sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
            type: $(this).attr('data-update-type'),
            value: value,
            operation: 'inline_edit'
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on('click', '.wcbel-product-attribute', function () {
        let modalId = $(this).attr('data-target');
        $(modalId).find('input.is-visible').prop('checked', ($(this).attr('data-is-variation') == 'true')).change();
        $(modalId).find('input.is-visible-prev').val(($(this).attr('data-is-visible') == 'true') ? 'yes' : 'no');
        $(modalId).find('input.is-variation').prop('checked', ($(this).attr('data-is-variation') == 'true')).change();
        $(modalId).find('input.is-variation-prev').val(($(this).attr('data-is-variation') == 'true') ? 'yes' : 'no');
    });

    $(document).on("click", ".wcbel-inline-edit-attribute-save", function () {
        let productData = [];
        let productIds = [];
        let modal = $("#wcbel-modal-attribute-" + $(this).attr("data-field") + "-" + $(this).attr("data-item-id"));
        let value = modal.find("input[data-field='value']:checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));
        productData.push({
            name: $(this).attr('data-name'),
            sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
            type: $(this).attr('data-update-type'),
            value: value,
            used_for_variations: (modal.find('input.is-variation').prop('checked') === true) ? 'yes' : 'no',
            used_for_variations_prev: modal.find('input.is-variation-prev').val(),
            attribute_is_visible: (modal.find('input.is-visible').prop('checked') === true) ? 'yes' : 'no',
            attribute_is_visible_prev: modal.find('input.is-visible-prev').val(),
            operation: 'inline_edit'
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on("click", ".wcbel-inline-edit-add-new-taxonomy", function () {
        $("#wcbel-create-new-product-taxonomy").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id")).attr('data-closest-id', $(this).attr('data-closest-id'));
        $('#wcbel-modal-new-product-taxonomy-product-title').text($(this).attr('data-item-name'));
        wcbelGetTaxonomyParentSelectBox($(this).attr("data-field"));
    });

    $(document).on("click", ".wcbel-inline-edit-add-new-attribute", function () {
        $("#wcbel-create-new-product-attribute").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        $('#wcbel-modal-new-product-attribute-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", 'button.wcbel-calculator[data-target="#wcbel-modal-numeric-calculator"]', function () {
        let btn = $("#wcbel-modal-numeric-calculator .wcbel-edit-action-numeric-calculator");
        let tdElement = $(this).closest('td');
        btn.attr("data-item-id", $(this).attr("data-item-id"));
        btn.attr("data-field", $(this).attr("data-field"));
        btn.attr("data-name", tdElement.attr('data-name'));
        btn.attr("data-update-type", tdElement.attr('data-update-type'));
        btn.attr("data-field-type", $(this).attr("data-field-type"));
        if ($(this).attr('data-field') === 'download_limit' || $(this).attr('data-field') === 'download_expiry') {
            $('#wcbel-modal-numeric-calculator #wcbel-numeric-calculator-type').val('n').change().hide();
            $('#wcbel-modal-numeric-calculator #wcbel-numeric-calculator-round').val('').change().hide();
        } else {
            $('#wcbel-modal-numeric-calculator #wcbel-numeric-calculator-type').show();
            $('#wcbel-modal-numeric-calculator #wcbel-numeric-calculator-round').show();
        }
        $('#wcbel-modal-numeric-calculator-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", ".wcbel-edit-action-numeric-calculator", function () {
        let productId = $(this).attr("data-item-id");
        let productIds = [];
        let productData = [];

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push(productId);

        productData.push({
            name: $(this).attr("data-name"),
            sub_name: ($(this).attr("data-name")) ? $(this).attr("data-name") : '',
            type: $(this).attr('data-update-type'),
            operator: $("#wcbel-numeric-calculator-operator").val(),
            value: $("#wcbel-numeric-calculator-value").val(),
            operator_type: ($("#wcbel-numeric-calculator-type").val()) ? $("#wcbel-numeric-calculator-type").val() : 'n',
            round: $("#wcbel-numeric-calculator-round").val()
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on('keyup', 'input[type=number][data-field=download_limit], input[type=number][data-field=download_expiry]', function () {
        if ($(this).val() < -1) {
            $(this).val(-1);
        }
    });

    $(document).on('click', '#wcbel-quick-search-button', function () {
        if ($('#wcbel-quick-search-text').val() !== '') {
            $('#wcbel-quick-search-reset').show();
        }
    });

    $(document).on('click', '#wcbel-quick-search-reset', function () {
        wcbelResetFilters()
    });

    $(document).on('click', 'button[data-toggle="modal"][data-target="#wcbel-modal-product-badges"]', function () {
        $('#wcbel-modal-product-badges-item-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-product-badges-apply').attr('data-item-id', $(this).attr('data-item-id'));
        $('#wcbel-modal-product-badge-items').val('').change();
        wcbelGetProductBadges($(this).attr('data-item-id'));
    });

    $(document).on('click', 'button[data-toggle="modal"][data-target="#wcbel-modal-ithemeland-badge"]', function () {
        let productId = $(this).attr('data-item-id');
        $('#wcbel-modal-ithemeland-badge-item-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-ithemeland-badge-apply').attr('data-item-id', productId);
        $('.it_unique_nav_for_general').trigger('click');
        $('#_unique_label_type').val('none').change();
        wcbelGetProductIthemelandBadge(productId);
    });

    $(document).on('click', 'button[data-toggle="modal"][data-target="#wcbel-modal-yikes-custom-product-tabs"]', function () {
        $('#wcbel-modal-yikes-custom-tabs').html('');
        let productId = $(this).attr('data-item-id');
        $('#wcbel-modal-yikes-custom-product-tabs-item-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-yikes-custom-product-tabs-apply').attr('data-item-id', productId);
        wcbelGetYikesCustomProductTabs(productId);
    });

    $(document).on('click', '#wcbel-modal-product-badges-apply', function () {
        let productIds = [];
        let productData = [];
        productIds.push($(this).attr('data-item-id'));
        productData.push({
            name: "_yith_wcbm_product_meta",
            sub_name: "id_badge",
            type: "meta_field",
            operation: "inline_edit",
            value: $('#wcbel-modal-product-badge-items').val(),
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on('click', '#wcbel-yikes-add-tab', function () {
        let newUniqueId = 'editor-' + Math.floor((Math.random() * 9999) + 1000);
        $('#wcbel-modal-yikes-custom-product-tabs #duplicate-item').clone().appendTo('#wcbel-modal-yikes-custom-tabs').ready(function () {
            let duplicated = $('#wcbel-modal-yikes-custom-tabs').find('#duplicate-item');
            duplicated.find('.wcbel-yikes-tab-content').attr('data-id', newUniqueId).find('textarea').attr('id', newUniqueId);
            duplicated.removeAttr('id');
            wp.editor.initialize(newUniqueId, wcbelWpEditorSettings);
        });
    });

    $(document).on('click', '#wcbel-modal-ithemeland-badge-apply', function () {
        let productIds = [];
        let productData = [];
        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));

        $('#ithemeland-badge-form .ithemeland-badge-form-item[data-value-position="child"]').each(function () {
            let fieldName = $(this).attr('data-name');
            let value;
            switch ($(this).attr('data-type')) {
                case 'text':
                case 'dropdown':
                    value = $('input[name="' + fieldName + '"]').val();
                    break;
                case 'radio':
                case 'checkbox':
                    value = $('input[name="' + fieldName + '"]:checked').val();
                    break;
            }
            productData.push({
                name: fieldName,
                type: 'meta_field',
                value: value,
                operation: 'inline_edit'
            });
        });

        $('#ithemeland-badge-form .ithemeland-badge-form-item[data-value-position="self"]').each(function () {
            let value;
            if ($(this).attr('type') === 'checkbox') {
                value = ($(this).prop('checked') === true) ? 'yes' : 'no';
            } else {
                value = $(this).val()
            }
            productData.push({
                name: $(this).attr('name'),
                type: 'meta_field',
                value: value,
                operation: 'inline_edit'
            });
        });

        wcbelProductEdit(productIds, productData);
    });

    $('#wcbel-bulk-edit-select-all-variations').prop('checked', false);

    if (itemIdInUrl && itemIdInUrl > 0) {
        wcbelResetFilterForm();
        setTimeout(function () {
            $('#wcbel-filter-form-product-ids').val(itemIdInUrl);
            $('#wcbel-filter-form-get-products').trigger('click');
        }, 500);
    }

    $(document).on('click', '.wcbel-yikes-tab-item-header', function (e) {
        if ($.inArray($(e.target).attr('class'), ['wcbel-yikes-tab-item-header', 'wcbel-yikes-tab-item-header-title']) !== -1) {
            if ($(this).closest('div.wcbel-yikes-tab-item').find('.wcbel-yikes-tab-item-body:visible').length > 0) {
                $('.wcbel-yikes-tab-item-body').slideUp(250);
            } else {
                $('.wcbel-yikes-tab-item-body').slideUp(250);
                $(this).closest('div.wcbel-yikes-tab-item').find('.wcbel-yikes-tab-item-body').slideDown(250);
            }
        }
    });

    $(document).on('keyup', '.wcbel-yikes-tab-title input', function () {
        $(this).closest('.wcbel-yikes-tab-item').find('.wcbel-yikes-tab-item-header strong').text($(this).val());
    });

    $(document).on('click', '.wcbel-yikes-tab-item-remove', function () {
        $(this).closest('.wcbel-yikes-tab-item').remove();
    });

    $(document).on('click', '#wcbel-modal-yikes-custom-product-tabs-apply', function () {
        let productIds = [];
        let productData = [];
        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));

        let tabs = [];
        let customProductTabsElement = $(this).closest('#wcbel-modal-yikes-custom-product-tabs').find('#yikes-custom-product-tabs-form .wcbel-yikes-tab-item');
        if (customProductTabsElement.length > 0) {
            customProductTabsElement.each(function () {
                let editorId = ($(this).find('.wcbel-yikes-tab-content').attr('data-id'));
                tabs.push({
                    global_tab: $(this).find('input[name="global_tab"]').val(),
                    title: $(this).find('.wcbel-yikes-tab-title input').val(),
                    content: tinymce.get(editorId).getContent()
                });
            })
        }

        productData.push({
            name: 'yikes_woo_products_tabs',
            type: 'meta_field',
            value: tabs
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on('click', '#wcbel-yikes-add-saved-tab', function () {
        $('#wcbel-last-modal-opened').val('.wcbel-yikes-saved-tabs');
        $(this).closest('#wcbel-modal-yikes-custom-product-tabs').find('.wcbel-yikes-saved-tabs').fadeIn(250);
    });

    $(document).on('click', '.wcbel-yikes-saved-tabs-close-button', function () {
        $(this).closest('.wcbel-yikes-saved-tabs').fadeOut(250);
        $('#wcbel-last-modal-opened').val('#wcbel-modal-yikes-custom-product-tabs');
    });

    $(document).on('click', '.wcbel-yikes-saved-tab-add', function () {
        wcbelAddYikesSavedTab($(this).attr('data-id'));
        $('.wcbel-yikes-saved-tabs-close-button').trigger('click')
    });

    $(document).on('change', '.wcbel-yikes-override-tab', function () {
        let tabItem = $(this).closest('.wcbel-yikes-tab-item');
        let globalInput = tabItem.find('input[name="global_tab"]');
        if ($(this).prop('checked') === false) {
            globalInput.val(globalInput.attr('data-global-id'));
            tabItem.find('.wcbel-yikes-tab-title input').prop('disabled', 'disabled');
            tabItem.find('.wcbel-yikes-tab-content button').prop('disabled', 'disabled');
            tinyMCE.get(tabItem.find('.wcbel-yikes-tab-content').attr('data-id')).getBody().setAttribute('contenteditable', false);
        } else {
            globalInput.val('');
            tabItem.find('.wcbel-yikes-tab-title input').prop('disabled', false);
            tabItem.find('.wcbel-yikes-tab-content button').prop('disabled', false);
            tinyMCE.get(tabItem.find('.wcbel-yikes-tab-content').attr('data-id')).getBody().setAttribute('contenteditable', true);
        }
    });

    $(document).on('click', '[data-toggle="modal"][data-target="#wcbel-modal-gallery"]', function () {
        let tdElement = $(this).closest('td');
        $('#wcbel-modal-gallery #wcbel-modal-gallery-items').html('');
        $('#wcbel-modal-gallery #wcbel-modal-gallery-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-gallery #wcbel-modal-gallery-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        wcbelGetProductGalleryImages($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wcbel-delete-item-btn', function () {
        let productIds = [];
        productIds.push($(this).attr('data-item-id'));
        let deleteType = $(this).attr('data-delete-type');
        swal({
            title: wcbelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: wcbelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wcbelDeleteProduct(productIds, deleteType);
            }
        });
    });

    $(document).on('click', '.wcbel-restore-item-btn', function () {
        let productIds = [];
        productIds.push($(this).attr('data-item-id'));
        swal({
            title: wcbelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: wcbelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wcbelRestoreProduct(productIds);
            }
        });
    });

    $(document).on('change', '#wcbel-filter-form-product-status', function () {
        if ($(this).val() === 'trash') {
            $('.wcbel-top-navigation-trash-buttons').show();
        } else {
            $('.wcbel-top-navigation-trash-buttons').hide();
        }
    });

    $(document).on('click', '#wcbel-bulk-edit-trash-empty', function () {
        swal({
            title: wcbelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: wcbelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wcbelEmptyTrash();
            }
        });
    });

    $(document).on('click', '#wcbel-bulk-edit-trash-restore', function () {
        let productIds = wcbelGetProductsChecked();
        wcbelRestoreProduct(productIds);
    });

    $(document).on('click', '[data-toggle="modal"][data-target="#wcbel-modal-it-wc-dynamic-pricing-all-fields"]', function () {
        let tdElement = $(this).closest('td');
        let productType = $(this).attr('data-item-type');

        $('#wcbel-modal-it-wc-dynamic-pricing-all-fields .wcbel-modal-section').each(function () {
            let sectionType = $(this).attr('data-type').split(',');
            if ($.inArray(productType, sectionType) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })

        $('#wcbel-modal-it-wc-dynamic-pricing-all-fields').find('input[type="number"]').val('').change();
        $('#wcbel-modal-it-wc-dynamic-pricing-all-fields').find('select').val('').change();
        $('#wcbel-modal-it-wc-dynamic-pricing-all-fields').find('input[type="checkbox"]').prop('checked', false);

        $('#wcbel-modal-it-wc-dynamic-pricing-all-fields-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-it-wc-dynamic-pricing-all-fields-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        wcbelGetItWcDynamicPricingAllFields($(this).attr('data-item-id'));
    });

    $(document).on('click', '[data-toggle="modal"][data-target="#wcbel-modal-it-wc-dynamic-pricing"]', function () {
        let tdElement = $(this).closest('td');
        $('#wcbel-modal-it-wc-dynamic-pricing').find('input[data-type="value"]').val('').change();
        $('#wcbel-modal-it-wc-dynamic-pricing-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-it-wc-dynamic-pricing-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        wcbelGetItWcRolePrices($(this).attr('data-item-id'));
    });

    $(document).on('click', '[data-toggle="modal"][data-target="#wcbel-modal-it-wc-dynamic-pricing-select-roles"]', function () {
        let tdElement = $(this).closest('td');
        $('#wcbel-modal-it-wc-dynamic-pricing-select-roles #wcbel-user-roles').val('').change();
        $('#wcbel-modal-it-wc-dynamic-pricing-select-roles-title').text($(this).attr('data-item-name'));
        $('#wcbel-modal-it-wc-dynamic-pricing-select-roles-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        wcbelGetItWcDynamicPricingSelectedRoles($(this).attr('data-item-id'), tdElement.attr('data-name'));
    });

    $(document).on('click', '#wcbel-modal-it-wc-dynamic-pricing-apply', function () {
        let productIds = [];
        let productData = [];
        let values = [];

        $(this).closest('#wcbel-modal-it-wc-dynamic-pricing').find('input[data-type="value"]').each(function () {
            if ($(this).val()) {
                values.push({
                    field: $(this).attr('data-name'),
                    amount: $(this).val()
                });
            }
        });

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr('data-item-id'));

        productData.push({
            name: $(this).attr('data-name'),
            type: $(this).attr('data-update-type'),
            operation: "inline_edit",
            value: values,
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on('click', '#wcbel-modal-it-wc-dynamic-pricing-select-roles-apply', function () {
        let productIds = [];
        let productData = [];

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr('data-item-id'));

        productData.push({
            name: $(this).attr('data-name'),
            type: $(this).attr('data-update-type'),
            operation: "inline_edit",
            value: $(this).closest('#wcbel-modal-it-wc-dynamic-pricing-select-roles').find('#wcbel-user-roles').val(),
        });

        wcbelProductEdit(productIds, productData);
    });

    $(document).on('click', '.wcbel-acf-taxonomy-multi-select', function () {
        $('.wcbel-modal-acf-taxonomy-multi-select-value').select2();
    })

    $(document).on('click', '#wcbel-modal-it-wc-dynamic-pricing-all-fields-apply', function () {
        let productIds = [];
        let productData = [];

        let pricing_roles = [];

        $(this).closest('#wcbel-modal-it-wc-dynamic-pricing-all-fields').find('#wcbel-it-pricing-roles input[data-type="value"]').each(function () {
            if ($(this).val()) {
                pricing_roles.push({
                    field: $(this).attr('data-name'),
                    amount: $(this).val()
                });
            }
        });

        if ($('#wcbel-bind-edit').prop('checked') === true) {
            productIds = wcbelGetProductsChecked();
        }
        productIds.push($(this).attr('data-item-id'));

        productData.push({
            name: $(this).attr('data-name'),
            type: $(this).attr('data-update-type'),
            operation: "inline_edit",
            value: {
                it_product_disable_discount: ($('#wcbel-it-wc-dynamic-pricing-disable-discount').prop('checked') === true) ? 'yes' : 'no',
                it_product_hide_price_unregistered: ($('#wcbel-it-wc-dynamic-pricing-hide-price-unregistered').prop('checked') === true) ? 'yes' : 'no',
                pricing_rules_product: pricing_roles,
                it_pricing_product_price_user_role: $('#wcbel-select-roles-hide-price').val(),
                it_pricing_product_add_to_cart_user_role: $('#wcbel-select-roles-hide-add-to-cart').val(),
                it_pricing_product_hide_user_role: $('#wcbel-select-roles-hide-product').val(),
            },
        });

        wcbelProductEdit(productIds, productData);
    });

    wcbelGetDefaultFilterProfileProducts();

    wcbelCheckShowVariations();

    // Compatible with ithemeland badge

    // Only show the "remove image" button when needed
    if (!$('#_unique_label_image').val()) {
        $('.it_remove_image_button').hide();
        $('#_unique_label_image').val('http://wordpress.local/wp-content/plugins/woocommerce-advanced-product-labels/assets/admin/images/placeholder.png');
        $('.product-label img').css({
            "width": '50px'
        });
    }

    // Uploading files
    var file_frame;
    $(document).on('click', '.it_upload_image_button', function (event) {
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.downloadable_file = wp.media({
            title: 'Choose an image',
            button: {
                text: 'Use image',
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            attachment = file_frame.state().get('selection').first().toJSON();

            $('#_unique_label_image').val(attachment.url);
            $('#unique_thumbnail img').attr('src', attachment.url);
            $('.it_remove_image_button').show();
            if ($('.custom_label_pic').length < 1) {
                $('.product-label').wrapInner("<img class='custom_label_pic' style='width: auto;' src='" + attachment.url + "'/>");
            } else {
                $('.product-label img').attr('src', attachment.url);
            }
            $('.product-label img').css({
                "width": 'auto'
            });
        });

        // Finally, open the modal.
        file_frame.open();
    });

    $(document).on('click', '.it_remove_image_button', function (event) {
        $('#unique_thumbnail img').attr('src', 'http://wordpress.local/wp-content/plugins/woocommerce-advanced-product-labels/assets/admin/images/placeholder.png');
        $('.product-label img').attr('src', 'http://wordpress.local/wp-content/plugins/woocommerce-advanced-product-labels/assets/admin/images/placeholder.png');
        $('.product-label img').css({
            "width": '50px'
        });
        $('#_unique_label_image').val('');
        $('#_unique_label_image').val('http://wordpress.local/wp-content/plugins/woocommerce-advanced-product-labels/assets/admin/images/placeholder.png');
        $('.it_remove_image_button').hide();
        return false;
    });

    /**************Admin Panel's Setting Tab End Here****************/
    /*custom range*/
    var rangeSlider = function () {
        var slider = $('.range-slider'),
            range = $('.range-slider__range'),
            value = $('.range-slider__value');

        slider.each(function () {

            value.each(function () {
                var value = $(this).prev().attr('value');
                $(this).html(value);
            });

            range.on('input', function () {
                $(this).next(value).html(this.value);
            });
        });
    };

    rangeSlider();
    /*end custom range*/

    if ($(".color-picker").length > 0 && $.fn.wpColorPicker) {
        $(".color-picker").wpColorPicker();
    }

    $(document).on('click', '.wcbel-history-pagination-item', function () {
        $('.wcbel-history-pagination-loading').show();

        let filters = {
            operation: $("#wcbel-history-filter-operation").val(),
            author: $("#wcbel-history-filter-author").val(),
            fields: $("#wcbel-history-filter-fields").val(),
            date: {
                from: $("#wcbel-history-filter-date-from").val(),
                to: $("#wcbel-history-filter-date-to").val()
            }
        };

        wcbelHistoryChangePage($(this).attr('data-index'), filters);
    });

    if ($('#wcbel-settings-show-only-filtered-variations').val() === 'yes') {
        $('#wcbel-bulk-edit-show-variations').prop('checked', true).attr('disabled', 'disabled');
    }

    $(document).on('change', '#wcbel-filter-form-product-status', function () {
        if ($(this).val() == 'trash') {
            $('.wcbel-trash-options').closest('li').show();
        } else {
            $('.wcbel-trash-options').closest('li').hide();
        }
    });

    $(document).on('click', '.wcbel-trash-option-restore-selected-items', function () {
        let productIds = wcbelGetProductsChecked();
        if (!productIds.length) {
            swal({
                title: "Please select one product",
                type: "warning"
            });
            return false;
        } else {
            swal({
                title: wcbelTranslate.areYouSure,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                confirmButtonText: wcbelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wcbelRestoreProduct(productIds);
                }
            });
        }
    });

    $(document).on('click', '.wcbel-trash-option-restore-all', function () {
        swal({
            title: wcbelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: wcbelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wcbelRestoreProduct([]);
            }
        });
    });

    $(document).on('click', '.wcbel-trash-option-delete-selected-items', function () {
        let productIds = wcbelGetProductsChecked();
        if (!productIds.length) {
            swal({
                title: "Please select one product",
                type: "warning"
            });
            return false;
        } else {
            swal({
                title: wcbelTranslate.areYouSure,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
                confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
                confirmButtonText: wcbelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wcbelDeleteProduct(productIds, 'permanently');
                }
            });
        }
    });

    $(document).on('click', '.wcbel-trash-option-delete-all', function () {
        swal({
            title: wcbelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbel-button wcbel-button-lg wcbel-button-white",
            confirmButtonClass: "wcbel-button wcbel-button-lg wcbel-button-green",
            confirmButtonText: wcbelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wcbelEmptyTrash()
            }
        });
    })
});