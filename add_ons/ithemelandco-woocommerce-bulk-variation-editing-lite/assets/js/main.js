"use strict";

var iwbvelWpEditorSettings = {
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
    $(document).on('click', '.iwbvel-timepicker, .iwbvel-datetimepicker, .iwbvel-datepicker', function () {
        $(this).attr('data-val', $(this).val());
    });

    iwbvelReInitDatePicker();
    iwbvelReInitColorPicker();

    // Select2
    if ($.fn.select2) {
        let iwbvelSelect2 = $(".iwbvel-select2");
        if (iwbvelSelect2.length) {
            iwbvelSelect2.select2({
                placeholder: "Select ..."
            });
        }
    }

    $(document).on("click", ".iwbvel-tabs-list li button.iwbvel-tab-item", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();

            if ($(this).closest('.iwbvel-tabs-list').attr('data-type') == 'url') {
                window.location.hash = $(this).attr('data-content');
            }

            iwbvelOpenTab($(this));
        }
    });

    // Modal
    $(document).on("click", '[data-toggle="modal"]', function () {
        iwbvelOpenModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="modal-close"]', function () {
        iwbvelCloseModal();
    });

    // Float side modal
    $(document).on("click", '[data-toggle="float-side-modal"]', function () {
        iwbvelOpenFloatSideModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="float-side-modal-close"]', function () {
        if ($('.iwbvel-float-side-modal:visible').length && $('.iwbvel-float-side-modal:visible').hasClass('iwbvel-float-side-modal-close-with-confirm')) {
            swal({
                title: 'Are you sure?',
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
                confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
                confirmButtonText: iwbvelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $('.iwbvel-float-side-modal:visible').removeClass('iwbvel-float-side-modal-close-with-confirm');
                    iwbvelCloseFloatSideModal();
                }
            });
        } else {
            iwbvelCloseFloatSideModal();
        }
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            if (jQuery('.iwbvel-modal:visible').length > 0) {
                iwbvelCloseModal();
            } else {
                if ($('.iwbvel-float-side-modal:visible').length && $('.iwbvel-float-side-modal:visible').hasClass('iwbvel-float-side-modal-close-with-confirm')) {
                    swal({
                        title: ($('.iwbvel-float-side-modal:visible').attr('data-confirm-message') && $('.iwbvel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.iwbvel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
                        confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
                        confirmButtonText: iwbvelTranslate.iAmSure,
                        closeOnConfirm: true
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $('.iwbvel-float-side-modal:visible').removeClass('iwbvel-float-side-modal-close-with-confirm');
                            iwbvelCloseFloatSideModal();
                        }
                    });
                } else {
                    iwbvelCloseFloatSideModal();
                }
            }

            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            if ($("#iwbvel-filter-form-content").css("display") === "block") {
                $("#iwbvel-bulk-edit-filter-form-close-button").trigger("click");
            }
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    $(document).on('click', '#iwbvel-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            openFullscreen();
        } else {
            exitFullscreen();
        }
    });

    if (document.addEventListener) {
        document.addEventListener('fullscreenchange', iwbvelFullscreenHandler, false);
        document.addEventListener('mozfullscreenchange', iwbvelFullscreenHandler, false);
        document.addEventListener('MSFullscreenChange', iwbvelFullscreenHandler, false);
        document.addEventListener('webkitfullscreenchange', iwbvelFullscreenHandler, false);
    }

    $(document).on("click", ".iwbvel-top-nav-duplicate-button", function () {
        let itemIds = $("input.iwbvel-check-item:visible:checkbox:checked").map(function () {
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
                title: (IWBVEL_DATA.strings && IWBVEL_DATA.strings['please_select_one_item']) ? IWBVEL_DATA.strings['please_select_one_item'] : "Please select one item",
                type: "warning"
            });
            return false;
        } else {
            iwbvelOpenModal('#iwbvel-modal-item-duplicate');
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".iwbvel-check-item-main", function () {
        let checkbox_items = $(".iwbvel-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#iwbvel-items-list tr").addClass("iwbvel-tr-selected");
            checkbox_items.each(function () {
                $("#iwbvel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            iwbvelShowSelectionTools();
            $("#iwbvel-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#iwbvel-items-list tr").removeClass("iwbvel-tr-selected");
            $("#iwbvel-export-items-selected").html("");
            iwbvelHideSelectionTools();
            $("#iwbvel-export-only-selected-items").prop("disabled", true);
            $("#iwbvel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".iwbvel-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#iwbvel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            if ($(".iwbvel-check-item:checked").length === $(".iwbvel-check-item").length) {
                $(".iwbvel-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("iwbvel-tr-selected");
        } else {
            $("#iwbvel-export-items-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("iwbvel-tr-selected");
            $(".iwbvel-check-item-main").prop("checked", false);
        }

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".iwbvel-check-item:checkbox:checked").length > 0) {
            $("#iwbvel-export-only-selected-items").prop("disabled", false);
            iwbvelShowSelectionTools();
        } else {
            iwbvelHideSelectionTools();
            $("#iwbvel-export-only-selected-items").prop("disabled", true);
            $("#iwbvel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#iwbvel-bulk-edit-unselect", function () {
        $("input.iwbvel-check-item").prop("checked", false);
        $("input.iwbvel-check-item-main").prop("checked", false);
        iwbvelHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#iwbvel-column-profiles-choose", function () {
        let preset = $(this).val();
        $('.iwbvel-column-profiles-fields input[type="checkbox"]').prop('checked', false);
        $('#iwbvel-column-profile-select-all').prop('checked', false);
        $('.iwbvel-column-profile-select-all span').text('Select All');
        $("#iwbvel-column-profiles-apply").attr("data-preset-key",);
        if (defaultPresets && $.inArray(preset, defaultPresets) === -1) {
            $("#iwbvel-column-profiles-update-changes").show();
        } else {
            $("#iwbvel-column-profiles-update-changes").hide();
        }

        if (columnPresetsFields && columnPresetsFields[preset]) {
            columnPresetsFields[preset].forEach(function (val) {
                $('.iwbvel-column-profiles-fields input[type="checkbox"][value="' + val + '"]').prop('checked', true);
            });
        }
    });

    $(document).on("keyup", "#iwbvel-column-profile-search", function () {
        let iwbvelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".iwbvel-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(iwbvelSearchFieldValue) > -1);
        });
    });

    $(document).on('change', '#iwbvel-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.iwbvel-column-profile-fields input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.iwbvel-column-profile-fields input:checkbox').prop('checked', false);
        }
        $(".iwbvel-column-profile-save-dropdown").show();
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on({
        mouseenter: function () {
            $(this)
                .children(".iwbvel-calculator")
                .show();
        },
        mouseleave: function () {
            $(this)
                .children(".iwbvel-calculator")
                .hide();
        }
    },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".iwbvel-bulk-edit-delete-item", function () {
        $(this).find(".iwbvel-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $(document).on("change", ".iwbvel-column-profile-fields input:checkbox", function () {
        $(".iwbvel-column-profile-save-dropdown").show();
    });

    $(document).on("click", ".iwbvel-column-profile-save-dropdown", function () {
        $(this).find(".iwbvel-column-profile-save-dropdown-buttons").slideToggle(200);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-iwbvel-col-view"></li>');

    $(document).on({
        mouseenter: function () {
            $('#wp-admin-bar-iwbvel-col-view').html('#' + $(this).attr('data-item-id') + ' | ' + $(this).attr('data-item-title') + ' [<span class="iwbvel-col-title">' + $(this).attr('data-col-title') + '</span>] ');
        },
        mouseleave: function () {
            $('#wp-admin-bar-iwbvel-col-view').html('');
        }
    },
        "#iwbvel-items-list td"
    );

    $(document).on("click", ".iwbvel-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let element = $(this).closest('div');
        let type = $(this).attr("data-type");
        let mediaUploader;
        let iwbvelNewImageElementID = $(this).attr("data-id");
        let iwbvelProductID = $(this).attr("data-item-id");
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
                    $("#url-" + iwbvelNewImageElementID).val(attachment[0].url);
                    break;
                case "inline-file-custom-field":
                    $("#iwbvel-file-url").val(attachment[0].url);
                    $('#iwbvel-file-id').val(attachment[0].id)
                    break;
                case "inline-edit":
                    $("#" + iwbvelNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + iwbvelNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("#iwbvel-modal-image button[data-item-id=" + iwbvelProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "variations-inline-edit":
                    $("#iwbve-variation-thumbnail-modal .iwbve-inline-image-preview").html("<img src='" + attachment[0].url + "' alt='' />");
                    $('#iwbve-variation-thumbnail-modal .iwbve-variations-table-thumbnail-inline-edit-button[data-button-type="save"]').attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("#iwbvel-modal-gallery-items").append('<div class="iwbvel-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="iwbvel-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
                case "bulk-edit-image":
                    element.find(".iwbvel-bulk-edit-form-item-image").val(attachment[0].id);
                    element.find(".iwbvel-bulk-edit-form-item-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="iwbvel-bulk-edit-form-remove-image"><i class="iwbvel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-image":
                    element.find(".iwbve-variations-bulk-actions-image").val(attachment[0].id);
                    element.find(".iwbve-variations-bulk-actions-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="iwbve-variations-bulk-actions-remove-image"><i class="iwbvel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-file":
                    element.find(".iwbve-variation-bulk-actions-file-item-url-input").val(attachment[0].url);
                    break;
                case "bulk-edit-file":
                    element.find(".iwbvel-bulk-edit-form-item-file").val(attachment[0].id);
                    break;
                case "bulk-edit-gallery":
                    attachment.forEach(function (item) {
                        $(".iwbvel-bulk-edit-form-item-gallery").append('<input type="hidden" value="' + item.id + '" data-field="value">');
                        $(".iwbvel-bulk-edit-form-item-gallery-preview").append('<div><img src="' + item.url + '" width="43" height="43" alt=""><button type="button" data-id="' + item.id + '" class="iwbvel-bulk-edit-form-remove-gallery-item"><i class="iwbvel-icon-x"></i></button></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".iwbvel-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("change", ".iwbvel-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".iwbvel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".iwbvel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("click", ".iwbvel-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".iwbvel-column-manager-available-fields[data-action=" + action + "] input[data-type=field]:checkbox:checked");
        if (checked.length > 0) {
            $('.iwbvel-column-manager-empty-text').hide();
            if (action === 'new') {
                $('.iwbvel-column-manager-added-fields-wrapper .iwbvel-box-loading').show();
            } else {
                $('#iwbvel-modal-column-manager-edit-preset .iwbvel-box-loading').show();
            }
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            iwbvelColumnManagerAddField(fieldName, fieldLabel, action);
        }
    });

    $(".iwbvel-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#iwbvel_column_manager_delete_preset_key").val($this.val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#iwbvel-column-manager-delete-preset-form").submit();
            }
        });
    });

    $(document).on("keyup", ".iwbvel-column-manager-search-field", function () {
        let iwbvelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".iwbvel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(iwbvelSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".iwbvel-column-manager-remove-field", function () {
        $(".iwbvel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".iwbvel-column-manager-right-item").remove();
        if ($('.iwbvel-column-manager-added-fields-wrapper .iwbvel-column-manager-right-item').length < 1) {
            $('.iwbvel-column-manager-empty-text').show();
        }
    });

    if ($.fn.sortable) {
        let iwbvelColumnManagerFields = $(".iwbvel-column-manager-added-fields .items");
        iwbvelColumnManagerFields.sortable({
            handle: ".iwbvel-column-manager-field-sortable-btn",
            cancel: ""
        });
        iwbvelColumnManagerFields.disableSelection();

        let iwbvelMetaFieldItems = $(".iwbvel-meta-fields-right");
        iwbvelMetaFieldItems.sortable({
            handle: ".iwbvel-meta-field-item-sortable-btn",
            cancel: ""
        });
        iwbvelMetaFieldItems.disableSelection();
    }

    $(document).on("click", "#iwbvel-add-acf-meta-field", function () {
        let input = $("#iwbvel-add-meta-fields-acf");
        if (input.val()) {
            $(".iwbvel-meta-fields-empty-text").hide();
            iwbvelAddACFMetaField(input.val(), input.find('option:selected').text(), input.find('option:selected').attr('data-type'));
            input.val("").change();
        }
    });

    $(document).on("click", ".iwbvel-meta-field-remove", function () {
        $(this).closest(".iwbvel-meta-fields-right-item").remove();
        if ($(".iwbvel-meta-fields-right-item").length < 1) {
            $(".iwbvel-meta-fields-empty-text").show();
        }
    });

    $(document).on("click", ".iwbvel-history-delete-item", function () {
        $("#iwbvel-history-clicked-id").attr("name", "delete").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#iwbvel-history-items").submit();
            }
        });
    });

    $(document).on("click", "#iwbvel-history-clear-all-btn", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#iwbvel-history-clear-all").submit();
            }
        });
    });

    $(document).on("click", ".iwbvel-history-revert-item", function () {
        $("#iwbvel-history-clicked-id").attr("name", "revert").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#iwbvel-history-items").submit();
            }
        });
    });

    $(document).on('click', '.iwbvel-modal', function (e) {
        if ($(e.target).hasClass('iwbvel-modal') || $(e.target).hasClass('iwbvel-modal-container') || $(e.target).hasClass('iwbvel-modal-box')) {
            iwbvelCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on('change', '#iwbvel-filter-form-content [data-field=value], #iwbvel-filter-form-content [data-field=from], #iwbvel-filter-form-content [data-field=to]', function () {
        iwbvelCheckFilterFormChanges();
    });

    $(document).on('change', 'input[type=number][data-field=to]', function () {
        let from = $(this).closest('.iwbvel-form-group').find('input[type=number][data-field=from]');
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val('').addClass('iwbvel-input-danger').focus();
        }
    });

    $(document).on('change', 'input[type=number][data-field=from]', function () {
        let to = $(this).closest('.iwbvel-form-group').find('input[type=number][data-field=to]');
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val('').addClass('iwbvel-input-danger');
        } else {
            $(this).removeClass('iwbvel-input-danger')
        }
    });

    $(document).on('change', '#iwbvel-switcher', function () {
        iwbvelLoadingStart();
        $('#iwbvel-switcher-form').submit();
    });

    $(document).on('click', 'span[data-target="#iwbvel-modal-image"]', function () {
        let tdElement = $(this).closest('td');
        let modal = $('#iwbvel-modal-image');
        let col_title = tdElement.attr('data-col-title');
        let id = $(this).attr('data-id');
        let image_id = $(this).attr('data-image-id');
        let item_id = tdElement.attr('data-item-id');
        let full_size_url = $(this).attr('data-full-image-src');
        let field = tdElement.attr('data-field');
        let field_type = tdElement.attr('data-field-type');

        $('#iwbvel-modal-image-item-title').text(col_title);
        modal.find('.iwbvel-open-uploader').attr('data-id', id).attr('data-item-id', item_id);
        modal.find('.iwbvel-inline-image-preview').attr('data-image-preview-id', id).html('<img src="' + full_size_url + '" />');
        modal.find('.iwbvel-image-preview-hidden-input').attr('id', id);
        modal.find('button[data-button-type="save"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-image-url', full_size_url).attr('data-image-id', image_id).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        modal.find('button[data-button-type="remove"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
    });

    $(document).on('click', 'button[data-target="#iwbvel-modal-file"]', function () {
        let modal = $('#iwbvel-modal-file');
        modal.find('#iwbvel-modal-select-file-item-title').text($(this).closest('td').attr('data-col-title'));
        modal.find('#iwbvel-modal-file-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        modal.find('#iwbvel-file-id').val($(this).attr('data-file-id'));
        modal.find('#iwbvel-file-url').val($(this).attr('data-file-url'));
    });

    $(document).on('click', '#iwbvel-modal-file-clear', function () {
        let modal = $('#iwbvel-modal-file');
        modal.find('#iwbvel-file-id').val(0).change();
        modal.find('#iwbvel-file-url').val('').change();
    });

    $(document).on('click', '.iwbvel-sub-tab-title', function () {
        $(this).closest('.iwbvel-sub-tab-titles').find('.iwbvel-sub-tab-title').removeClass('active');
        $(this).addClass('active');

        $(this).closest('div').find('.iwbvel-sub-tab-content').hide();
        $(this).closest('div').find('.iwbvel-sub-tab-content[data-content="' + $(this).attr('data-content') + '"]').show();
    });

    if ($('.iwbvel-sub-tab-titles').length > 0) {
        $('.iwbvel-sub-tab-titles').each(function () {
            $(this).find('.iwbvel-sub-tab-title').first().trigger('click');
        });
    }

    $(document).on("mouseenter", ".iwbvel-thumbnail", function () {
        let position = $(this).offset();
        let imageHeight = $(this).find('img').first().height();
        let top = ((position.top - imageHeight) > $('#wpadminbar').offset().top) ? position.top - imageHeight : position.top + 15;

        $('.iwbvel-thumbnail-hover-box').css({
            top: top,
            left: position.left - 100,
            display: 'block',
            height: imageHeight
        }).html($(this).find('.iwbvel-original-thumbnail').clone());
    });

    $(document).on("mouseleave", ".iwbvel-thumbnail", function () {
        $('.iwbvel-thumbnail-hover-box').hide();
    });

    setTimeout(function () {
        $('#iwbvel-column-profiles-choose').trigger('change');
    }, 500);

    $(document).on('click', '.iwbvel-filter-form-action', function () {
        iwbvelFilterFormClose();
    });

    $(document).on('click', '#iwbvel-license-renew-button', function () {
        $(this).closest('#iwbvel-license').find('.iwbvel-license-form').slideDown();
    });

    $(document).on('click', '#iwbvel-license-form-cancel', function () {
        $(this).closest('#iwbvel-license').find('.iwbvel-license-form').slideUp();
    });

    $(document).on('click', '#iwbvel-license-deactivate-button', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#iwbvel-license-deactivation-form').submit();
            }
        });
    });

    iwbvelSetTipsyTooltip();

    $(window).on('resize', function () {
        iwbvelDataTableFixSize();
    });

    $(document).on('click', 'body', function (e) {
        if (!$(e.target).hasClass('iwbvel-status-filter-button') && $(e.target).closest('.iwbvel-status-filter-button').length == 0) {
            $('.iwbvel-top-nav-status-filter').hide();
        }

        if (!$(e.target).hasClass('iwbvel-quick-filter') && $(e.target).closest('.iwbvel-quick-filter').length == 0) {
            $('.iwbvel-top-nav-filters').hide();
        }

        if (!$(e.target).hasClass('iwbvel-post-type-switcher') && $(e.target).closest('.iwbvel-post-type-switcher').length == 0) {
            $('.iwbvel-top-nav-filters-switcher').hide();
        }

        if (!$(e.target).hasClass('iwbvel-float-side-modal') &&
            !$(e.target).closest('.iwbvel-float-side-modal-box').length &&
            !$('.sweet-overlay:visible').length &&
            !$('.iwbvel-modal:visible').length &&
            $(e.target).attr('data-toggle') != 'float-side-modal' &&
            !$(e.target).closest('.select2-container').length &&
            !$(e.target).is('i') &&
            !$(e.target).closest('.media-modal').length &&
            !$(e.target).closest('.sweet-alert').length &&
            !$(e.target).closest('[data-toggle="float-side-modal"]').length &&
            !$(e.target).closest('[data-toggle="float-side-modal-after-confirm"]').length) {
            if ($('.iwbvel-float-side-modal:visible').length && $('.iwbvel-float-side-modal:visible').hasClass('iwbvel-float-side-modal-close-with-confirm')) {
                swal({
                    title: ($('.iwbvel-float-side-modal:visible').attr('data-confirm-message') && $('.iwbvel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.iwbvel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
                    confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
                    confirmButtonText: iwbvelTranslate.iAmSure,
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $('.iwbvel-float-side-modal:visible').removeClass('iwbvel-float-side-modal-close-with-confirm');
                        iwbvelCloseFloatSideModal();
                    }
                });
            } else {
                iwbvelCloseFloatSideModal();
            }
        }
    });

    $(document).on('click', '.iwbvel-status-filter-button', function () {
        $(this).closest('.iwbvel-status-filter-container').find('.iwbvel-top-nav-status-filter').toggle();
    });

    $(document).on('click', '.iwbvel-quick-filter > button', function (e) {
        if (!$(e.target).closest('.iwbvel-top-nav-filters').length) {
            $('.iwbvel-top-nav-filters').slideToggle(150);
        }
    });
    $(document).on('click', '.iwbvel-post-type-switcher > button', function (e) {
        if (!$(e.target).closest('.iwbvel-top-nav-filters-switcher').length) {
            $('.iwbvel-top-nav-filters-switcher').slideToggle(150);
        }
    });

    $(document).on('click', '.iwbvel-bind-edit-switch', function () {
        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            $('#iwbvel-bind-edit').prop('checked', false);
            $(this).removeClass('active');
        } else {
            $('#iwbvel-bind-edit').prop('checked', true);
            $(this).addClass('active');
        }
    });

    if ($('#iwbvel-bind-edit').prop('checked') === true) {
        $('.iwbvel-bind-edit-switch').addClass('active');
    } else {
        $('.iwbvel-bind-edit-switch').removeClass('active');
    }

    if ($('.iwbvel-flush-message').length) {
        setTimeout(function () {
            $('.iwbvel-flush-message').slideUp();
        }, 3000);
    }

    iwbvelDataTableFixSize();
});