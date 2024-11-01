"use strict";

var wpbelWpEditorSettings = {
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
    $(document).on('click', '.wpbel-timepicker, .wpbel-datetimepicker, .wpbel-datepicker', function () {
        $(this).attr('data-val', $(this).val());
    });

    wpbelReInitDatePicker();
    wpbelReInitColorPicker();

    // Select2
    if ($.fn.select2) {
        let wpbelSelect2 = $(".wpbel-select2");
        if (wpbelSelect2.length) {
            wpbelSelect2.select2({
                placeholder: "Select ..."
            });
        }
    }

    $(document).on("click", ".wpbel-tabs-list li button.wpbel-tab-item", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();

            if ($(this).closest('.wpbel-tabs-list').attr('data-type') == 'url') {
                window.location.hash = $(this).attr('data-content');
            }

            wpbelOpenTab($(this));
        }
    });

    // Modal
    $(document).on("click", '[data-toggle="modal"]', function () {
        wpbelOpenModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="modal-close"]', function () {
        wpbelCloseModal();
    });

    // Float side modal
    $(document).on("click", '[data-toggle="float-side-modal"]', function () {
        wpbelOpenFloatSideModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="float-side-modal-close"]', function () {
        if ($('.wpbel-float-side-modal:visible').length && $('.wpbel-float-side-modal:visible').hasClass('wpbel-float-side-modal-close-with-confirm')) {
            swal({
                title: 'Are you sure?',
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
                confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
                confirmButtonText: iwbveTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $('.wpbel-float-side-modal:visible').removeClass('wpbel-float-side-modal-close-with-confirm');
                    wpbelCloseFloatSideModal();
                }
            });
        } else {
            wpbelCloseFloatSideModal();
        }
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            if (jQuery('.wpbel-modal:visible').length > 0) {
                wpbelCloseModal();
            } else {
                if ($('.wpbel-float-side-modal:visible').length && $('.wpbel-float-side-modal:visible').hasClass('wpbel-float-side-modal-close-with-confirm')) {
                    swal({
                        title: ($('.wpbel-float-side-modal:visible').attr('data-confirm-message') && $('.wpbel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.wpbel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
                        confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
                        confirmButtonText: iwbveTranslate.iAmSure,
                        closeOnConfirm: true
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $('.wpbel-float-side-modal:visible').removeClass('wpbel-float-side-modal-close-with-confirm');
                            wpbelCloseFloatSideModal();
                        }
                    });
                } else {
                    wpbelCloseFloatSideModal();
                }
            }

            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            if ($("#wpbel-filter-form-content").css("display") === "block") {
                $("#wpbel-bulk-edit-filter-form-close-button").trigger("click");
            }
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    $(document).on('click', '#wpbel-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            openFullscreen();
        } else {
            exitFullscreen();
        }
    });

    if (document.addEventListener) {
        document.addEventListener('fullscreenchange', wpbelFullscreenHandler, false);
        document.addEventListener('mozfullscreenchange', wpbelFullscreenHandler, false);
        document.addEventListener('MSFullscreenChange', wpbelFullscreenHandler, false);
        document.addEventListener('webkitfullscreenchange', wpbelFullscreenHandler, false);
    }

    $(document).on("click", ".wpbel-top-nav-duplicate-button", function () {
        let itemIds = $("input.wpbel-check-item:visible:checkbox:checked").map(function () {
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
                title: (WPBEL_DATA.strings && WPBEL_DATA.strings['please_select_one_item']) ? WPBEL_DATA.strings['please_select_one_item'] : "Please select one item",
                type: "warning"
            });
            return false;
        } else {
            wpbelOpenModal('#wpbel-modal-item-duplicate');
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".wpbel-check-item-main", function () {
        let checkbox_items = $(".wpbel-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wpbel-items-list tr").addClass("wpbel-tr-selected");
            checkbox_items.each(function () {
                $("#wpbel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            wpbelShowSelectionTools();
            $("#wpbel-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wpbel-items-list tr").removeClass("wpbel-tr-selected");
            $("#wpbel-export-items-selected").html("");
            wpbelHideSelectionTools();
            $("#wpbel-export-only-selected-items").prop("disabled", true);
            $("#wpbel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wpbel-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wpbel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            if ($(".wpbel-check-item:checked").length === $(".wpbel-check-item").length) {
                $(".wpbel-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("wpbel-tr-selected");
        } else {
            $("#wpbel-export-items-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("wpbel-tr-selected");
            $(".wpbel-check-item-main").prop("checked", false);
        }

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".wpbel-check-item:checkbox:checked").length > 0) {
            $("#wpbel-export-only-selected-items").prop("disabled", false);
            wpbelShowSelectionTools();
        } else {
            wpbelHideSelectionTools();
            $("#wpbel-export-only-selected-items").prop("disabled", true);
            $("#wpbel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#wpbel-bulk-edit-unselect", function () {
        $("input.wpbel-check-item").prop("checked", false);
        $("input.wpbel-check-item-main").prop("checked", false);
        wpbelHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#wpbel-column-profiles-choose", function () {
        let preset = $(this).val();
        $('.wpbel-column-profiles-fields input[type="checkbox"]').prop('checked', false);
        $('#wpbel-column-profile-select-all').prop('checked', false);
        $('.wpbel-column-profile-select-all span').text('Select All');
        $("#wpbel-column-profiles-apply").attr("data-preset-key",);
        if (defaultPresets && $.inArray(preset, defaultPresets) === -1) {
            $("#wpbel-column-profiles-update-changes").show();
        } else {
            $("#wpbel-column-profiles-update-changes").hide();
        }

        if (columnPresetsFields && columnPresetsFields[preset]) {
            columnPresetsFields[preset].forEach(function (val) {
                $('.wpbel-column-profiles-fields input[type="checkbox"][value="' + val + '"]').prop('checked', true);
            });
        }
    });

    $(document).on("keyup", "#wpbel-column-profile-search", function () {
        let wpbelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wpbel-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wpbelSearchFieldValue) > -1);
        });
    });

    $(document).on('change', '#wpbel-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.wpbel-column-profile-fields input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.wpbel-column-profile-fields input:checkbox').prop('checked', false);
        }
        $(".wpbel-column-profile-save-dropdown").show();
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on({
        mouseenter: function () {
            $(this)
                .children(".wpbel-calculator")
                .show();
        },
        mouseleave: function () {
            $(this)
                .children(".wpbel-calculator")
                .hide();
        }
    },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".wpbel-bulk-edit-delete-item", function () {
        $(this).find(".wpbel-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $(document).on("change", ".wpbel-column-profile-fields input:checkbox", function () {
        $(".wpbel-column-profile-save-dropdown").show();
    });

    $(document).on("click", ".wpbel-column-profile-save-dropdown", function () {
        $(this).find(".wpbel-column-profile-save-dropdown-buttons").slideToggle(200);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-wpbel-col-view"></li>');

    $(document).on({
        mouseenter: function () {
            $('#wp-admin-bar-wpbel-col-view').html('#' + $(this).attr('data-item-id') + ' | ' + $(this).attr('data-item-title') + ' [<span class="wpbel-col-title">' + $(this).attr('data-col-title') + '</span>] ');
        },
        mouseleave: function () {
            $('#wp-admin-bar-wpbel-col-view').html('');
        }
    },
        "#wpbel-items-list td"
    );

    $(document).on("click", ".wpbel-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let element = $(this).closest('div');
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wpbelNewImageElementID = $(this).attr("data-id");
        let wpbelProductID = $(this).attr("data-item-id");
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
                    $("#url-" + wpbelNewImageElementID).val(attachment[0].url);
                    break;
                case "inline-file-custom-field":
                    $("#wpbel-file-url").val(attachment[0].url);
                    $('#wpbel-file-id').val(attachment[0].id)
                    break;
                case "inline-edit":
                    $("#" + wpbelNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + wpbelNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("#wpbel-modal-image button[data-item-id=" + wpbelProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "variations-inline-edit":
                    $("#iwbve-variation-thumbnail-modal .iwbve-inline-image-preview").html("<img src='" + attachment[0].url + "' alt='' />");
                    $('#iwbve-variation-thumbnail-modal .iwbve-variations-table-thumbnail-inline-edit-button[data-button-type="save"]').attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("#wpbel-modal-gallery-items").append('<div class="wpbel-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wpbel-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
                case "bulk-edit-image":
                    element.find(".wpbel-bulk-edit-form-item-image").val(attachment[0].id);
                    element.find(".wpbel-bulk-edit-form-item-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wpbel-bulk-edit-form-remove-image"><i class="wpbel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-image":
                    element.find(".iwbve-variations-bulk-actions-image").val(attachment[0].id);
                    element.find(".iwbve-variations-bulk-actions-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="iwbve-variations-bulk-actions-remove-image"><i class="wpbel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-file":
                    element.find(".iwbve-variation-bulk-actions-file-item-url-input").val(attachment[0].url);
                    break;
                case "bulk-edit-file":
                    element.find(".wpbel-bulk-edit-form-item-file").val(attachment[0].id);
                    break;
                case "bulk-edit-gallery":
                    attachment.forEach(function (item) {
                        $(".wpbel-bulk-edit-form-item-gallery").append('<input type="hidden" value="' + item.id + '" data-field="value">');
                        $(".wpbel-bulk-edit-form-item-gallery-preview").append('<div><img src="' + item.url + '" width="43" height="43" alt=""><button type="button" data-id="' + item.id + '" class="wpbel-bulk-edit-form-remove-gallery-item"><i class="wpbel-icon-x"></i></button></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".wpbel-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("change", ".wpbel-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wpbel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wpbel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("click", ".wpbel-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".wpbel-column-manager-available-fields[data-action=" + action + "] input[data-type=field]:checkbox:checked");
        if (checked.length > 0) {
            $('.wpbel-column-manager-empty-text').hide();
            if (action === 'new') {
                $('.wpbel-column-manager-added-fields-wrapper .wpbel-box-loading').show();
            } else {
                $('#wpbel-modal-column-manager-edit-preset .wpbel-box-loading').show();
            }
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            wpbelColumnManagerAddField(fieldName, fieldLabel, action);
        }
    });

    $(".wpbel-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#wpbel_column_manager_delete_preset_key").val($this.val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wpbel-column-manager-delete-preset-form").submit();
            }
        });
    });

    $(document).on("keyup", ".wpbel-column-manager-search-field", function () {
        let wpbelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wpbel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wpbelSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".wpbel-column-manager-remove-field", function () {
        $(".wpbel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".wpbel-column-manager-right-item").remove();
        if ($('.wpbel-column-manager-added-fields-wrapper .wpbel-column-manager-right-item').length < 1) {
            $('.wpbel-column-manager-empty-text').show();
        }
    });

    if ($.fn.sortable) {
        let wpbelColumnManagerFields = $(".wpbel-column-manager-added-fields .items");
        wpbelColumnManagerFields.sortable({
            handle: ".wpbel-column-manager-field-sortable-btn",
            cancel: ""
        });
        wpbelColumnManagerFields.disableSelection();

        let wpbelMetaFieldItems = $(".wpbel-meta-fields-right");
        wpbelMetaFieldItems.sortable({
            handle: ".wpbel-meta-field-item-sortable-btn",
            cancel: ""
        });
        wpbelMetaFieldItems.disableSelection();
    }

    $(document).on("click", "#wpbel-add-meta-field-manual", function () {
        $(".wpbel-meta-fields-empty-text").hide();
        let input = $("#wpbel-meta-fields-manual_key_name");
        wpbelAddMetaKeysManual(input.val());
        input.val("");
    });

    $(document).on("click", "#wpbel-add-acf-meta-field", function () {
        let input = $("#wpbel-add-meta-fields-acf");
        if (input.val()) {
            $(".wpbel-meta-fields-empty-text").hide();
            wpbelAddACFMetaField(input.val(), input.find('option:selected').text(), input.find('option:selected').attr('data-type'));
            input.val("").change();
        }
    });

    $(document).on("click", ".wpbel-meta-field-remove", function () {
        $(this).closest(".wpbel-meta-fields-right-item").remove();
        if ($(".wpbel-meta-fields-right-item").length < 1) {
            $(".wpbel-meta-fields-empty-text").show();
        }
    });

    $(document).on("click", ".wpbel-history-delete-item", function () {
        $("#wpbel-history-clicked-id").attr("name", "delete").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wpbel-history-items").submit();
            }
        });
    });

    $(document).on("click", "#wpbel-history-clear-all-btn", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wpbel-history-clear-all").submit();
            }
        });
    });

    $(document).on("click", ".wpbel-history-revert-item", function () {
        $("#wpbel-history-clicked-id").attr("name", "revert").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wpbel-history-items").submit();
            }
        });
    });

    $(document).on('click', '.wpbel-modal', function (e) {
        if ($(e.target).hasClass('wpbel-modal') || $(e.target).hasClass('wpbel-modal-container') || $(e.target).hasClass('wpbel-modal-box')) {
            wpbelCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on('change', '#wpbel-filter-form-content [data-field=value], #wpbel-filter-form-content [data-field=from], #wpbel-filter-form-content [data-field=to]', function () {
        wpbelCheckFilterFormChanges();
    });

    $(document).on('change', 'input[type=number][data-field=to]', function () {
        let from = $(this).closest('.wpbel-form-group').find('input[type=number][data-field=from]');
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val('').addClass('wpbel-input-danger').focus();
        }
    });

    $(document).on('change', 'input[type=number][data-field=from]', function () {
        let to = $(this).closest('.wpbel-form-group').find('input[type=number][data-field=to]');
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val('').addClass('wpbel-input-danger');
        } else {
            $(this).removeClass('wpbel-input-danger')
        }
    });

    $(document).on('change', '#wpbel-switcher', function () {
        wpbelLoadingStart();
        $('#wpbel-switcher-form').submit();
    });

    $(document).on('click', 'span[data-target="#wpbel-modal-image"]', function () {
        let tdElement = $(this).closest('td');
        let modal = $('#wpbel-modal-image');
        let col_title = tdElement.attr('data-col-title');
        let id = $(this).attr('data-id');
        let image_id = $(this).attr('data-image-id');
        let item_id = tdElement.attr('data-item-id');
        let full_size_url = $(this).attr('data-full-image-src');
        let field = tdElement.attr('data-field');
        let field_type = tdElement.attr('data-field-type');

        $('#wpbel-modal-image-item-title').text(col_title);
        modal.find('.wpbel-open-uploader').attr('data-id', id).attr('data-item-id', item_id);
        modal.find('.wpbel-inline-image-preview').attr('data-image-preview-id', id).html('<img src="' + full_size_url + '" />');
        modal.find('.wpbel-image-preview-hidden-input').attr('id', id);
        modal.find('button[data-button-type="save"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-image-url', full_size_url).attr('data-image-id', image_id).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        modal.find('button[data-button-type="remove"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
    });

    $(document).on('click', 'button[data-target="#wpbel-modal-file"]', function () {
        let modal = $('#wpbel-modal-file');
        modal.find('#wpbel-modal-select-file-item-title').text($(this).closest('td').attr('data-col-title'));
        modal.find('#wpbel-modal-file-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        modal.find('#wpbel-file-id').val($(this).attr('data-file-id'));
        modal.find('#wpbel-file-url').val($(this).attr('data-file-url'));
    });

    $(document).on('click', '#wpbel-modal-file-clear', function () {
        let modal = $('#wpbel-modal-file');
        modal.find('#wpbel-file-id').val(0).change();
        modal.find('#wpbel-file-url').val('').change();
    });

    $(document).on('click', '.wpbel-sub-tab-title', function () {
        $(this).closest('.wpbel-sub-tab-titles').find('.wpbel-sub-tab-title').removeClass('active');
        $(this).addClass('active');

        $(this).closest('div').find('.wpbel-sub-tab-content').hide();
        $(this).closest('div').find('.wpbel-sub-tab-content[data-content="' + $(this).attr('data-content') + '"]').show();
    });

    if ($('.wpbel-sub-tab-titles').length > 0) {
        $('.wpbel-sub-tab-titles').each(function () {
            $(this).find('.wpbel-sub-tab-title').first().trigger('click');
        });
    }

    $(document).on("mouseenter", ".wpbel-thumbnail", function () {
        let position = $(this).offset();
        let imageHeight = $(this).find('img').first().height();
        let top = ((position.top - imageHeight) > $('#wpadminbar').offset().top) ? position.top - imageHeight : position.top + 15;

        $('.wpbel-thumbnail-hover-box').css({
            top: top,
            left: position.left - 100,
            display: 'block',
            height: imageHeight
        }).html($(this).find('.wpbel-original-thumbnail').clone());
    });

    $(document).on("mouseleave", ".wpbel-thumbnail", function () {
        $('.wpbel-thumbnail-hover-box').hide();
    });

    setTimeout(function () {
        $('#wpbel-column-profiles-choose').trigger('change');
    }, 500);

    $(document).on('click', '.wpbel-filter-form-action', function () {
        wpbelFilterFormClose();
    });

    $(document).on('click', '#wpbel-license-renew-button', function () {
        $(this).closest('#wpbel-license').find('.wpbel-license-form').slideDown();
    });

    $(document).on('click', '#wpbel-license-form-cancel', function () {
        $(this).closest('#wpbel-license').find('.wpbel-license-form').slideUp();
    });

    $(document).on('click', '#wpbel-license-deactivate-button', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#wpbel-license-deactivation-form').submit();
            }
        });
    });

    wpbelSetTipsyTooltip();

    $(window).on('resize', function () {
        wpbelDataTableFixSize();
    });

    $(document).on('click', 'body', function (e) {
        if (!$(e.target).hasClass('wpbel-status-filter-button') && $(e.target).closest('.wpbel-status-filter-button').length == 0) {
            $('.wpbel-top-nav-status-filter').hide();
        }

        if (!$(e.target).hasClass('wpbel-quick-filter') && $(e.target).closest('.wpbel-quick-filter').length == 0) {
            $('.wpbel-top-nav-filters').hide();
        }

        if (!$(e.target).hasClass('wpbel-post-type-switcher') && $(e.target).closest('.wpbel-post-type-switcher').length == 0) {
            $('.wpbel-top-nav-filters-switcher').hide();
        }

        if (!$(e.target).hasClass('wpbel-float-side-modal') &&
            !$(e.target).closest('.wpbel-float-side-modal-box').length &&
            !$('.sweet-overlay:visible').length &&
            !$('.wpbel-modal:visible').length &&
            $(e.target).attr('data-toggle') != 'float-side-modal' &&
            !$(e.target).closest('.select2-container').length &&
            !$(e.target).is('i') &&
            !$(e.target).closest('.media-modal').length &&
            !$(e.target).closest('.sweet-alert').length &&
            !$(e.target).closest('[data-toggle="float-side-modal"]').length &&
            !$(e.target).closest('[data-toggle="float-side-modal-after-confirm"]').length) {
            if ($('.wpbel-float-side-modal:visible').length && $('.wpbel-float-side-modal:visible').hasClass('wpbel-float-side-modal-close-with-confirm')) {
                swal({
                    title: ($('.wpbel-float-side-modal:visible').attr('data-confirm-message') && $('.wpbel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.wpbel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
                    confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
                    confirmButtonText: iwbveTranslate.iAmSure,
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $('.wpbel-float-side-modal:visible').removeClass('wpbel-float-side-modal-close-with-confirm');
                        wpbelCloseFloatSideModal();
                    }
                });
            } else {
                wpbelCloseFloatSideModal();
            }
        }
    });

    $(document).on('click', '.wpbel-status-filter-button', function () {
        $(this).closest('.wpbel-status-filter-container').find('.wpbel-top-nav-status-filter').toggle();
    });

    $(document).on('click', '.wpbel-quick-filter > button', function (e) {
        if (!$(e.target).closest('.wpbel-top-nav-filters').length) {
            $('.wpbel-top-nav-filters').slideToggle(150);
        }
    });
    $(document).on('click', '.wpbel-post-type-switcher > button', function (e) {
        if (!$(e.target).closest('.wpbel-top-nav-filters-switcher').length) {
            $('.wpbel-top-nav-filters-switcher').slideToggle(150);
        }
    });

    $(document).on('click', '.wpbel-bind-edit-switch', function () {
        if ($('#wpbel-bind-edit').prop('checked') === true) {
            $('#wpbel-bind-edit').prop('checked', false);
            $(this).removeClass('active');
        } else {
            $('#wpbel-bind-edit').prop('checked', true);
            $(this).addClass('active');
        }
    });

    if ($('#wpbel-bind-edit').prop('checked') === true) {
        $('.wpbel-bind-edit-switch').addClass('active');
    } else {
        $('.wpbel-bind-edit-switch').removeClass('active');
    }

    if ($('.wpbel-flush-message').length) {
        setTimeout(function () {
            $('.wpbel-flush-message').slideUp();
        }, 3000);
    }

    wpbelDataTableFixSize();

    // Select2
    if ($.fn.select2) {
        wpbelGetPostTags();
    }

    // Inline edit
    $(document).on("click", "td[data-action=inline-editable]", function (e) {
        if ($(e.target).attr("data-type") !== "edit-mode" && $(e.target).find("[data-type=edit-mode]").length === 0) {
            // Close All Inline Edit
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
            // Open Clicked Inline Edit
            switch ($(this).attr("data-content-type")) {
                case "text":
                    $(this).children("span").html("<textarea data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "'>" + $(this).text().trim() + "</textarea>").children("textarea").focus().select();
                    break;
                case "numeric":
                case "regular_price":
                case "sale_price":
                    $(this).children("span").html("<input type='number' min='-1' data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "' value='" + $(this).text().trim() + "'>").children("input[type=number]").focus().select();
                    break;
            }
        }
    });

    // Discard Save
    $(document).on("click", function (e) {
        if ($(e.target).attr("data-action") !== "inline-editable" && $(e.target).attr("data-type") !== "edit-mode") {
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    // Save Inline Edit By Enter Key
    $(document).on("keypress", "[data-type=edit-mode]", function (event) {
        let wpbelKeyCode = event.keyCode ? event.keyCode : event.which;
        let reload_posts = true;
        if (wpbelKeyCode === 13) {
            let PostIds;
            let postsChecked = $("input.wpbel-check-item:checkbox:checked");
            let bindEdit = $("#wpbel-bind-edit");
            if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
                PostIds = postsChecked.map(function (i) {
                    return $(this).val();
                }).get();
                PostIds[postsChecked.length] = $(this).attr("data-item-id");
            } else {
                PostIds = [];
                PostIds[0] = $(this).attr("data-item-id");
            }
            let wpbelField;
            if ($(this).attr("data-field-type")) {
                wpbelField = [
                    $(this).attr("data-field-type"),
                    $(this).attr("data-field")
                ];
            } else {
                wpbelField = $(this).attr("data-field");
            }

            let wpbelValue = $(this).val();
            $(this).closest("span").html($(this).val());
            wpbelInlineEdit(PostIds, wpbelField, wpbelValue, reload_posts);
        }
    });

    // fetch post data by click to bulk edit button
    $(document).on("click", "#wpbel-bulk-edit-bulk-edit-btn", function () {
        if ($(this).attr("data-fetch-post") === "yes") {
            let postID = $("input.wpbel-check-item:checkbox:checked");
            if (postID.length === 1) {
                wpbelGetPostData(postID.val());
            } else {
                wpbelResetBulkEditForm();
            }
        }
    });

    $(document).on("change", ".wpbel-inline-edit-action", function (e) {
        let $this = $(this);
        setTimeout(function () {
            if ($('div.xdsoft_datetimepicker:visible').length > 0) {
                e.preventDefault();
                return false;
            }
            if ($this.hasClass('wpbel-datepicker') || $this.hasClass('wpbel-timepicker') || $this.hasClass('wpbel-datetimepicker')) {
                if ($this.attr('data-val') == $this.val()) {
                    e.preventDefault();
                    return false;
                }
            }

            let wpbelField;
            let tdElement = $this.closest('td');
            let reload_posts = true;
            let PostIds = [];
            let postsChecked = $("input.wpbel-check-item:checkbox:checked");
            let bindEdit = $("#wpbel-bind-edit");
            if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
                postsChecked.each(function (i) {
                    if ($(this).val() !== $this.attr("data-item-id")) {
                        PostIds.push($(this).val());
                    }
                });
            }
            PostIds.push($this.attr("data-item-id"));

            if (tdElement.attr("data-field-type")) {
                wpbelField = [tdElement.attr("data-field-type"), tdElement.attr("data-field")];
            } else {
                wpbelField = tdElement.attr("data-field");
            }

            let wpbelValue;
            if ($this.attr("type") === "checkbox") {
                wpbelValue = $this.prop("checked") ? "yes" : "no";
            } else {
                wpbelValue = $this.val();
            }

            wpbelInlineEdit(PostIds, wpbelField, wpbelValue, reload_posts);
        }, 250)
    });

    $(document).on("click", ".wpbel-inline-edit-clear-date", function () {
        let wpbelField;
        let reload_posts = true;
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-bind-edit");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }

        if ($(this).attr("data-field-type")) {
            wpbelField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wpbelField = $(this).attr("data-field");
        }

        wpbelInlineEdit(PostIds, wpbelField, '', reload_posts);
    });

    $(document).on("click", ".wpbel-edit-action-price-calculator", function () {
        let postID = $(this).attr("data-item-id");
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-bind-edit");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = postID;
        } else {
            PostIds = [];
            PostIds[0] = postID;
        }

        let wpbelField = $(this).attr("data-field");
        let values = {
            operator: $("#wpbel-" + wpbelField + "-calculator-operator-" + postID).val(),
            value: $("#wpbel-" + wpbelField + "-calculator-value-" + postID).val(),
            operator_type: $("#wpbel-" + wpbelField + "-calculator-type-" + postID).val(),
            roundItem: $("#wpbel-" + wpbelField + "-calculator-round-" + postID).val()
        };

        wpbelEditByCalculator(PostIds, wpbelField, values);
    });

    $(document).on("click", ".wpbel-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr('data-delete-type');
        let postIds = wpbelGetPostChecked();

        if (!postIds.length && deleteType != 'all') {
            swal({
                title: "Please select one post",
                type: "warning"
            });
            return false;
        }

        let alertMessage = "Are you sure?";

        if (deleteType == 'all') {
            alertMessage = ($('.wpbel-reset-filter-form:visible').length) ? "All of filtered posts will be delete. Are you sure?" : "All of posts will be delete. Are you sure?";
        }

        swal({
            title: alertMessage,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (postIds.length > 0 || deleteType == 'all') {
                    wpbelDeletePost(postIds, deleteType);
                } else {
                    swal({
                        title: "Please Select Post !",
                        type: "warning"
                    });
                }
            }
        });
    });

    $(document).on("click", "#wpbel-bulk-edit-duplicate-start", function () {
        let postIDs = $("input.wpbel-check-item:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wpbelDuplicatePost(postIDs, parseInt($("#wpbel-bulk-edit-duplicate-number").val()));
    });

    $(document).on("click", "#wpbel-create-new-item", function () {
        let count = $("#wpbel-new-item-count").val();
        let postType = ($('#wpbel-new-item-select-custom-post')) ? $('#wpbel-new-item-select-custom-post').val() : null;
        wpbelCreateNewPost(count, postType);
    });

    $(document).on("click", "#wpbel-column-profiles-save-as-new-preset", function () {
        let presetKey = $("#wpbel-column-profiles-choose").val();
        let items = $(".wpbel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wpbelSaveColumnProfile(presetKey, items, "save_as_new");
    });

    $(document).on("click", "#wpbel-column-profiles-update-changes", function () {
        let presetKey = $("#wpbel-column-profiles-choose").val();
        let items = $(".wpbel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wpbelSaveColumnProfile(presetKey, items, "update_changes");
    });

    $(document).on("click", ".wpbel-bulk-edit-filter-profile-load", function () {
        wpbelLoadFilterProfile($(this).val());
        if ($(this).val() !== "default") {
            $("#wpbel-bulk-edit-reset-filter").show();
        }
        $(".wpbel-filter-profiles-items tr").removeClass("wpbel-filter-profile-loaded");
        $(this).closest("tr").addClass("wpbel-filter-profile-loaded");
        if (WPBEL_DATA.wpbel_settings.close_popup_after_applying == 'yes') {
            wpbelCloseFloatSideModal();
        }
    });

    $(document).on("click", ".wpbel-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wpbelDeleteFilterProfile(presetKey);
                if (item.hasClass('wpbel-filter-profile-loaded')) {
                    $('.wpbel-filter-profiles-items tbody tr:first-child').addClass('wpbel-filter-profile-loaded');
                    $('.wpbel-filter-profile-use-always-item[value=default]').prop('checked', true);
                    $('#wpbel-bulk-edit-reset-filter').trigger('click');
                }
                if (item.length > 0) {
                    item.remove();
                }
            }
        });
    });

    $(document).on("change", "input.wpbel-filter-profile-use-always-item", function () {
        if ($(this).val() !== "default") {
            $("#wpbel-bulk-edit-reset-filter").show();
        } else {
            $("#wpbel-bulk-edit-reset-filter").hide();
        }
        wpbelFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".wpbel-filter-form-action", function (e) {
        let data = wpbelGetCurrentFilterData();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search" && $('#wpbel-quick-search-text').val() !== '') {
            wpbelResetFilterForm();
        }
        if (action === "pro_search") {
            $('#wpbel-bulk-edit-reset-filter').show();
            wpbelResetQuickSearchForm();
            $(".wpbel-filter-profiles-items tr").removeClass("wpbel-filter-profile-loaded");
            $('input.wpbel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
            wpbelFilterProfileChangeUseAlways("default");
        }
        wpbelPostsFilter(data, action, null, page);

        if (WPBEL_DATA.wpbel_settings.close_popup_after_applying == 'yes') {
            wpbelCloseFloatSideModal();
        }

        wpbelCheckResetFilterButton();
    });

    $(document).on("click", "#wpbel-filter-form-reset", function () {
        wpbelResetFilters();
    });

    $(document).on("click", "#wpbel-bulk-edit-reset-filter", function () {
        wpbelResetFilters();
    });

    $(document).on("change", "#wpbel-quick-search-field", function () {
        let options = $("#wpbel-quick-search-operator option");
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
    $(document).on("change", "#wpbel-quick-per-page", function () {
        wpbelChangeCountPerPage($(this).val());
    });

    $(document).on("click", ".wpbel-edit-action-with-button", function () {
        let reload_posts = true;
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-bind-edit");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }

        let wpbelField;
        if ($(this).attr("data-field-type")) {
            wpbelField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wpbelField = $(this).attr("data-field");
        }
        let wpbelValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                wpbelValue = tinymce.get("wpbel-text-editor").getContent();
                break;
            case "select_post":
                wpbelValue = $('#wpbel-select-post-value').val();
                break;
            case "image":
                wpbelValue = $(this).attr("data-image-id");
                break;
        }
        wpbelInlineEdit(PostIds, wpbelField, wpbelValue, reload_posts);
    });

    $(document).on("click", ".wpbel-load-text-editor", function () {
        let postId = $(this).attr("data-item-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $('#wpbel-modal-text-editor-item-title').text($(this).attr('data-item-name'));
        $("#wpbel-text-editor-apply").attr("data-field", field).attr("data-field-type", fieldType).attr("data-item-id", postId);
        $.ajax({
            url: WPBEL_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "wpbel_get_text_editor_content",
                nonce: WPBEL_DATA.ajax_nonce,
                post_id: postId,
                field: field,
                field_type: fieldType
            },
            success: function (response) {
                if (response.success) {
                    tinymce.get("wpbel-text-editor").setContent(response.content);
                    tinymce.execCommand('mceFocus', false, 'wpbel-text-editor');
                }
            },
            error: function () { }
        });
    });

    $(document).on("click", ".wpbel-inline-edit-taxonomy-save", function () {
        let reload = true;
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-bind-edit");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }
        let field = $(this).attr("data-field");
        let data = $("#wpbel-modal-taxonomy-" + field + "-" + $(this).attr("data-item-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        wpbelUpdatePostTaxonomy(PostIds, field, data, reload);
    });

    $(document).on("click", "#wpbel-create-new-post-taxonomy", function () {
        if ($("#wpbel-new-post-category-name").val() !== "") {
            let taxonomyInfo = {
                name: $("#wpbel-new-post-taxonomy-name").val(),
                slug: $("#wpbel-new-post-taxonomy-slug").val(),
                parent: $("#wpbel-new-post-taxonomy-parent").val(),
                description: $("#wpbel-new-post-taxonomy-description").val(),
                post_id: $(this).attr("data-item-id")
            };
            wpbelAddPostTaxonomy(taxonomyInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: "Taxonomy Name is required !",
                type: "warning"
            });
        }
    });

    //Search
    $(document).on("keyup", ".wpbel-search-in-list", function () {
        let wpbelSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-id") + " .wpbel-post-items-list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wpbelSearchValue) > -1);
        });
    });

    $(document).on("click", "#wpbel-create-new-post-attribute", function () {
        if ($("#wpbel-new-post-attribute-name").val() !== "") {
            let attributeInfo = {
                name: $("#wpbel-new-post-attribute-name").val(),
                slug: $("#wpbel-new-post-attribute-slug").val(),
                description: $("#wpbel-new-post-attribute-description").val(),
                post_id: $(this).attr("data-item-id")
            };
            wpbelAddPostAttribute(attributeInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: "Attribute Name is required !",
                type: "warning"
            });
        }
    });

    $(document).on('click', 'button[data-target="#wpbel-modal-select-post"]', function () {
        $('#wpbel-modal-select-post-item-title').text($(this).attr('data-item-name'));
        $('#wpbel-modal-select-post .wpbel-edit-action-with-button').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        $('#wpbel-select-post-value').val('').change();
        wpbelSetSelectedParent(parseInt($(this).attr('data-parent-id')));
    });

    let select2Query;
    $(".wpbel-get-posts-ajax").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: WPBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                select2Query = {
                    action: "wpbel_get_posts_by_name",
                    nonce: WPBEL_DATA.ajax_nonce,
                    post_title: params.term
                };
                return select2Query;
            }
        },
        placeholder: "Post name ...",
        minimumInputLength: 3
    });

    $(document).on('click', '#wpbel-modal-select-files-add-file-item', function () {
        wpbelAddNewFileItem();
    });

    $(document).on('click', 'button[data-toggle=modal][data-target="#wpbel-modal-select-files"]', function () {
        $('#wpbel-modal-select-files-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr(('data-field')));
        $('#wpbel-modal-select-files-item-title').text($(this).attr('data-item-name'));
        wpbelGetPostFiles($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wpbel-inline-edit-file-remove-item', function () {
        $(this).closest('.wpbel-modal-select-files-file-item').remove();
    });

    if ($.fn.sortable) {
        let wpbelSelectFiles = $(".wpbel-inline-select-files");
        wpbelSelectFiles.sortable({
            handle: ".wpbel-select-files-sortable-btn",
            cancel: ""
        });
        wpbelSelectFiles.disableSelection();
    }

    $(document).on("change", ".wpbel-bulk-edit-form-variable", function () {
        let newVal = $(this).val() ? $(this).closest("div").find("input[type=text]").val() + "{" + $(this).val() + "}" : "";
        $(this).closest("div").find("input[type=text]").val(newVal).change();
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        let id = $(this).closest(".wpbel-form-group").find("label").attr("for");
        if ($(this).val() === "text_replace") {
            $(this).closest(".wpbel-form-group").append('<div class="wpbel-bulk-edit-form-extra-field"><select id="' + id + '-sensitive"><option value="yes">Same Case</option><option value="no">Ignore Case</option></select><input type="text" id="' + id + '-replace" placeholder="Text ..."><select class="wpbel-bulk-edit-form-variable" title="Select Variable" data-field="variable"><option value="">Variable</option><option value="title">Title</option><option value="id">ID</option><option value="sku">SKU</option><option value="menu_order">Menu Order</option><option value="parent_id">Parent ID</option><option value="parent_title">Parent Title</option><option value="parent_sku">Parent SKU</option><option value="regular_price">Regular Price</option><option value="sale_price">Sale Price</option></select></div>');
        } else if ($(this).val() === "number_round") {
            $(this).closest(".wpbel-form-group").append('<div class="wpbel-bulk-edit-form-extra-field"><select id="' + id + '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>');
        } else {
            $(this).closest(".wpbel-form-group").find(".wpbel-bulk-edit-form-extra-field").remove();
        }
        if ($(this).val() === "number_clear") {
            $(this).closest(".wpbel-form-group").find('input[data-field=value]').prop('disabled', true);
        } else {
            $(this).closest(".wpbel-form-group").find('input[data-field=value]').prop('disabled', false);
        }
        changedTabs($(this));
    });

    $("#wpbel-float-side-modal-bulk-edit .wpbel-tab-content-item").on("change", "[data-field=value]", function () {
        changedTabs($(this));
    });

    $(document).on("change", ".wpbel-date-from", function () {
        let field_to = $('#' + $(this).attr('data-to-id'));
        field_to.val("");
        field_to.datepicker("destroy");
        field_to.datepicker({
            dateFormat: "yy/mm/dd",
            minDate: $(this).val()
        });
    });

    $(document).on("click", ".wpbel-bulk-edit-form-remove-image", function () {
        $(this).closest("div").remove();
        $(".wpbel-bulk-edit-form-item-image").val("");
    });

    $(document).on("click", ".wpbel-bulk-edit-form-remove-gallery-item", function () {
        $(this).closest("div").remove();
        $("#wpbel-bulk-edit-form-post-gallery input[value=" + $(this).attr("data-id") + "]").remove();
    });

    var sortType = 'DESC'
    $(document).on('click', '.wpbel-sortable-column', function () {
        if (sortType === 'DESC') {
            sortType = 'ASC';
            $(this).find('i.wpbel-sortable-column-icon').text('d');
        } else {
            sortType = 'DESC';
            $(this).find('i.wpbel-sortable-column-icon').text('u');
        }
        wpbelSortByColumn($(this).attr('data-column-name'), sortType);
    });

    $(document).on("click", ".wpbel-column-manager-edit-field-btn", function () {
        $('#wpbel-modal-column-manager-edit-preset .wpbel-box-loading').show();
        let presetKey = $(this).val();
        $('#wpbel-modal-column-manager-edit-preset .items').html('');
        $("#wpbel-column-manager-edit-preset-key").val(presetKey);
        $("#wpbel-column-manager-edit-preset-name").val($(this).attr("data-preset-name"));
        wpbelColumnManagerFieldsGetForEdit(presetKey);
    });

    $(document).on("click", "#wpbel-get-meta-fields-by-post-id", function () {
        $(".wpbel-meta-fields-empty-text").hide();
        let input = $("#wpbel-add-meta-fields-post-id");
        wpbelAddMetaKeysByPostID(input.val());
        input.val("");
    });

    $(document).on("click", "#wpbel-bulk-edit-undo", function () {
        wpbelHistoryUndo();
    });

    $(document).on("click", "#wpbel-bulk-edit-redo", function () {
        wpbelHistoryRedo();
    });

    $(document).on("click", "#wpbel-history-filter-apply", function () {
        let filters = {
            operation: $("#wpbel-history-filter-operation").val(),
            author: $("#wpbel-history-filter-author").val(),
            fields: $("#wpbel-history-filter-fields").val(),
            date: {
                from: $("#wpbel-history-filter-date-from").val(),
                to: $("#wpbel-history-filter-date-to").val()
            }
        };
        wpbelHistoryFilter(filters);
    });

    $(document).on("click", "#wpbel-history-filter-reset", function () {
        $(".wpbel-history-filter-fields input").val("");
        $(".wpbel-history-filter-fields select").val("").change();
        wpbelHistoryFilter();
    });

    $(document).on("change", ".wpbel-meta-fields-main-type", function () {
        let item = $(this).closest('.wpbel-meta-fields-right-item');
        if ($(this).val() === "textinput") {
            item.find(".wpbel-meta-fields-sub-type").show();
        } else {
            item.find(".wpbel-meta-fields-sub-type").hide();
        }

        if ($.inArray($(this).val(), ['select', 'array']) !== -1) {
            item.find(".wpbel-meta-fields-key-value").show();
        } else {
            item.find(".wpbel-meta-fields-key-value").hide();
        }
    });

    $(document).on("submit", "#wpbel-column-manager-add-new-preset", function (e) {
        if ($(this).find(".wpbel-column-manager-added-fields .items .wpbel-column-manager-right-item").length < 1) {
            e.preventDefault();
            swal({
                title: "Please Add Columns !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wpbel-bulk-edit-form-reset", function () {
        wpbelResetBulkEditForm();
        $("nav.wpbel-tabs-navbar li a").removeClass("wpbel-tab-changed");
    });

    $(document).on("click", "#wpbel-filter-form-save-preset", function () {
        let presetName = $("#wpbel-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = wpbelGetProSearchData();
            wpbelSaveFilterPreset(data, presetName);
        } else {
            swal({
                title: "Preset name is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wpbel-bulk-edit-form-do-bulk-edit", function (e) {
        let postIDs;
        let filterData;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");

        let taxonomies = [];
        let custom_fields = [];
        let i = 0;
        let j = 0;
        $(".wpbel-bulk-edit-form-group[data-type=taxonomy]").each(function () {
            if ($(this).find("select[data-field=value]").val() != null) {
                taxonomies[i++] = {
                    field: $(this).attr("data-taxonomy"),
                    operator: $(this).find("select[data-field=operator]").val(),
                    value: $(this).find("select[data-field=value]").val()
                };
            }
        });
        $(".wpbel-bulk-edit-form-group[data-type=custom_field]").each(function () {
            if ($(this).find("input[data-field=value]").val() != null) {
                custom_fields[j++] = {
                    field: $(this).attr("data-taxonomy"),
                    operator: $(this).find("select[data-field=operator]").val(),
                    value: $(this).find("input[data-field=value]").val()
                };
            }
        });

        let data = {
            post_title: {
                value: $("#wpbel-bulk-edit-form-post-title").val(),
                replace: $("#wpbel-bulk-edit-form-post-title-replace").val(),
                sensitive: $("#wpbel-bulk-edit-form-post-title-sensitive").val(),
                operator: $("#wpbel-bulk-edit-form-post-title-operator").val()
            },
            post_slug: {
                value: $("#wpbel-bulk-edit-form-post-slug").val(),
                replace: $("#wpbel-bulk-edit-form-post-slug-replace").val(),
                sensitive: $("#wpbel-bulk-edit-form-post-slug-sensitive").val(),
                operator: $("#wpbel-bulk-edit-form-post-slug-operator").val()
            },
            post_content: {
                value: $("#wpbel-bulk-edit-form-post-description").val(),
                replace: $("#wpbel-bulk-edit-form-post-description-replace").val(),
                sensitive: $("#wpbel-bulk-edit-form-post-description-sensitive").val(),
                operator: $("#wpbel-bulk-edit-form-post-description-operator").val()
            },
            post_excerpt: {
                value: $("#wpbel-bulk-edit-form-post-short-description").val(),
                replace: $("#wpbel-bulk-edit-form-post-short-description-replace").val(),
                sensitive: $("#wpbel-bulk-edit-form-post-short-description-sensitive").val(),
                operator: $("#wpbel-bulk-edit-form-post-short-description-operator").val()
            },
            post_password: {
                value: $("#wpbel-bulk-edit-form-post-password").val(),
                replace: $("#wpbel-bulk-edit-form-post-password-replace").val(),
                sensitive: $("#wpbel-bulk-edit-form-post-password-sensitive").val(),
                operator: $("#wpbel-bulk-edit-form-post-password-operator").val()
            },
            menu_order: {
                value: $("#wpbel-bulk-edit-form-post-menu-order").val()
            },
            post_status: {
                value: $("#wpbel-bulk-edit-form-post-post-status").val()
            },
            ping_status: {
                value: $("#wpbel-bulk-edit-form-post-ping-status").val()
            },
            comment_status: {
                value: $("#wpbel-bulk-edit-form-post-comment-status").val()
            },
            post_date: {
                value: $("#wpbel-bulk-edit-form-post-date-published").val()
            },
            post_date_gmt: {
                value: $("#wpbel-bulk-edit-form-post-date-published-gmt").val()
            },
            post_modified: {
                value: $("#wpbel-bulk-edit-form-post-date-modified").val()
            },
            post_modified_gmt: {
                value: $("#wpbel-bulk-edit-form-post-date-modified-gmt").val()
            },
            post_author: {
                value: $("#wpbel-bulk-edit-form-post-author").val()
            },
            _thumbnail_id: {
                value: $(".wpbel-bulk-edit-form-item-image").val()
            },
            taxonomy: taxonomies,
            custom_field: custom_fields,
            post_url: {
                value: $("#wpbel-bulk-edit-form-post-url").val()
            },
            post_type: {
                value: $("#wpbel-bulk-edit-form-post-type").val()
            },
            post_parent: {
                value: $("#wpbel-bulk-edit-form-post-parent").val()
            },
            sticky: {
                value: $("#wpbel-bulk-edit-form-post-sticky").val()
            },
        };

        if (postsChecked.length > 0) {
            postIDs = postsChecked.map(function () {
                return $(this).val();
            }).get();

            if (WPBEL_DATA.wpbel_settings.close_popup_after_applying == 'yes') {
                wpbelCloseFloatSideModal();
            }

            wpbelPostsBulkEdit(postIDs, data, filterData);
            if (WPBEL_DATA.wpbel_settings.keep_filled_data_in_bulk_edit_form == 'yes') {
                wpbelResetBulkEditForm();
            }
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
                confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wpbelCloseModal();
                    wpbelPostsBulkEdit(postIDs, data, filterData);
                }
            }
            );
            filterData = wpbelGetCurrentFilterData();
        }
    });

    $(document).on('click', '#wpbel-quick-search-button', function () {
        if ($('#wpbel-quick-search-text').val() !== '') {
            $('#wpbel-quick-search-reset').show();
        }
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($("#wpbel-float-side-modal-filter").attr("data-visibility") === "visible") {
                wpbelReloadPosts();
                $("#wpbel-bulk-edit-reset-filter").show();
                wpbelFilterFormClose();
            }
            if ($('#wpbel-quick-search-text').val() !== '' && $($('#wpbel-last-modal-opened').val()).css('display') !== 'block' && $('.wpbel-tabs-list button[data-content="bulk-edit"]').hasClass('selected')) {
                wpbelReloadPosts();
                $('#wpbel-quick-search-reset').show();
            }
            if ($("#wpbel-modal-new-post-taxonomy").css("display") === "block") {
                $("#wpbel-create-new-post-taxonomy").trigger("click");
            }
            if ($("#wpbel-modal-new-item").css("display") === "block") {
                $("#wpbel-create-new-item").trigger("click");
            }
            if ($("#wpbel-modal-post-duplicate").css("display") === "block") {
                $("#wpbel-bulk-edit-duplicate-start").trigger("click");
            }

            // filter form
            if ($('#wpbel-float-side-modal-filter:visible').length) {
                $('#wpbel-float-side-modal-filter:visible').find('.wpbel-filter-form-action').trigger('click');
            }
        }
    });

    $(document).on("click", ".wpbel-inline-edit-attribute-save", function () {
        let reload = true;
        let PostIds;
        let postsChecked = $("input.wpbel-item-id:checkbox:checked");
        let bindEdit = $("#wpbel-bind-edit");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }
        let field = $(this).attr("data-field");
        let data = $("#wpbel-modal-attribute-" + field + "-" + $(this).attr("data-item-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wpbelUpdatePostAttribute(PostIds, field, data, reload);
    });

    $(document).on('click', '.wpbel-reset-filter-form', function () {
        wpbelResetFilters();
    });

    $(document).on("click", ".wpbel-inline-edit-add-new-taxonomy", function () {
        $("#wpbel-create-new-post-taxonomy").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        $('#wpbel-modal-new-post-taxonomy-post-title').text($(this).attr('data-item-name'));
        wpbelGetTaxonomyParentSelectBox($(this).attr("data-field"));
        $("#wpbel-modal-new-post-taxonomy input").val('');
        $("#wpbel-modal-new-post-taxonomy select").val('').change();
        $("#wpbel-modal-new-post-taxonomy textarea").val('');
    });

    $(document).on("click", ".wpbel-inline-edit-add-new-attribute", function () {
        $("#wpbel-create-new-post-attribute").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        $('#wpbel-modal-new-post-attribute-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", 'button.wpbel-calculator[data-target="#wpbel-modal-numeric-calculator"]', function () {
        let btn = $("#wpbel-modal-numeric-calculator .wpbel-edit-action-numeric-calculator");
        btn.attr("data-item-id", $(this).attr("data-item-id"));
        btn.attr("data-field", $(this).attr("data-field"));
        btn.attr("data-field-type", $(this).attr("data-field-type"));
        if ($(this).attr('data-field') === 'download_limit' || $(this).attr('data-field') === 'download_expiry') {
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-type').val('n').change().hide();
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-round').val('').change().hide();
        } else {
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-type').show();
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-round').show();
        }
        $('#wpbel-modal-numeric-calculator-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", ".wpbel-edit-action-numeric-calculator", function () {
        let postID = $(this).attr("data-item-id");
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-bind-edit");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = postID;
        } else {
            PostIds = [];
            PostIds[0] = postID;
        }

        let wpbelField;
        if ($(this).attr("data-field-type")) {
            wpbelField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wpbelField = $(this).attr("data-field");
        }

        let values = {
            operator: $("#wpbel-numeric-calculator-operator").val(),
            value: $("#wpbel-numeric-calculator-value").val(),
            operator_type: $("#wpbel-numeric-calculator-type").val(),
            roundItem: $("#wpbel-numeric-calculator-round").val()
        };

        wpbelEditByCalculator(PostIds, wpbelField, values);
    });

    $(document).on('keyup', 'input[type=number][data-field=download_limit], input[type=number][data-field=download_expiry]', function () {
        if ($(this).val() < -1) {
            $(this).val(-1);
        }
    });

    $(document).on('click', '#wpbel-quick-search-reset', function () {
        wpbelResetFilters()
    });

    $(document).on('click', '[data-target="#wpbel-modal-new-item"]', function () {
        let title;
        let description;
        switch ($(this).attr('data-post-type')) {
            case 'post':
                title = "New Post";
                description = "Enter how many new post(s) to create!";
                break;
            case 'page':
                title = "New Page";
                description = "Enter how many new page(s) to create!";
                break;
            case 'custom_post':
                title = "New Custom Post Item";
                description = "Enter how many new custom post(s) to create!";
                break;
        }

        $('#wpbel-new-item-title').html(title);
        $('#wpbel-new-item-description').html(description);
    });

    if (itemTypeInUrl && itemTypeInUrl !== '' && itemTypeInUrl !== $('#wpbel-switcher').val()) {
        $('#wpbel-switcher').val(itemTypeInUrl).trigger('change');
    }

    if (itemIdInUrl && itemIdInUrl > 0) {
        wpbelResetFilterForm();
        setTimeout(function () {
            $('#wpbel-filter-form-post-ids').val(itemIdInUrl);
            $('#wpbel-filter-form-get-posts').trigger('click');
        }, 500);
    }

    $(document).on('click', '.wpbel-delete-item-btn', function () {
        let postIds = [];
        postIds.push($(this).attr('data-item-id'));
        let deleteType = $(this).attr('data-delete-type');
        swal({
            title: 'Are you sure?',
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wpbelDeletePost(postIds, deleteType);
            }
        });
    });

    $(document).on('click', '.wpbel-restore-item-btn', function () {
        let postIds = [];
        postIds.push($(this).attr('data-item-id'));
        swal({
            title: 'Are you sure?',
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wpbelRestorePost(postIds);
            }
        });
    });

    $(document).on('change', '#wpbel-filter-form-post-status', function () {
        if ($(this).val() === 'trash') {
            $('.wpbel-top-navigation-trash-buttons').show();
        } else {
            $('.wpbel-top-navigation-trash-buttons').hide();
        }
    });

    $(document).on('click', '#wpbel-bulk-edit-trash-empty', function () {
        swal({
            title: 'Are you sure?',
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wpbelEmptyTrash();
            }
        });
    });

    $(document).on('click', '#wpbel-bulk-edit-trash-restore', function () {
        let postIds = wpbelGetPostChecked();
        wpbelRestorePost(postIds);
    });

    $(document).on('click', '.wpbel-history-pagination-item', function () {
        $('.wpbel-history-pagination-loading').show();

        let filters = {
            operation: $("#wpbel-history-filter-operation").val(),
            author: $("#wpbel-history-filter-author").val(),
            fields: $("#wpbel-history-filter-fields").val(),
            date: {
                from: $("#wpbel-history-filter-date-from").val(),
                to: $("#wpbel-history-filter-date-to").val()
            }
        };

        wpbelHistoryChangePage($(this).attr('data-index'), filters);
    });

    $(document).on('click', '.wpbel-reload-table', function () {
        wpbelReloadPosts();
    });

    $(document).on('change', '#wpbel-export-type', function () {
        if ($(this).val() == 'xml') {
            $('.wpbel-export-radio input[name="fields"]').prop('checked', false).change();
            $('.wpbel-export-radio input[name="fields"][value="all"]').prop('checked', true).change();
            $('.wpbel-export-radio input[name="fields"]').prop('disabled', true);
        } else {
            $('.wpbel-export-radio input').prop('disabled', false);
        }
    });

    $(document).on('change', '#wpbel-filter-form-post-status', function () {
        if ($(this).val() == 'trash') {
            $('.wpbel-trash-options').closest('li').show();
        } else {
            $('.wpbel-trash-options').closest('li').hide();
        }
    });

    $(document).on('click', '.wpbel-trash-option-restore-selected-items', function () {
        let postIds = wpbelGetPostsChecked();
        if (!postIds.length) {
            swal({
                title: "Please select one post",
                type: "warning"
            });
            return false;
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
                confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wpbelRestorePost(postIds);
                }
            });
        }
    });

    $(document).on('click', '.wpbel-trash-option-restore-all', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wpbelRestorePost([]);
            }
        });
    });

    $(document).on('click', '.wpbel-trash-option-delete-selected-items', function () {
        let postIds = wpbelGetPostsChecked();
        if (!postIds.length) {
            swal({
                title: "Please select one post",
                type: "warning"
            });
            return false;
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
                confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wpbelDeletePost(postIds, 'permanently');
                }
            });
        }
    });

    $(document).on('click', '.wpbel-trash-option-delete-all', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wpbelEmptyTrash()
            }
        });
    });

    wpbelGetDefaultFilterProfilePosts();
});
