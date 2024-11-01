"use strict";

var wbeblWpEditorSettings = {
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
    var navigationTopOffset;
    if ($('#wbebl-bulk-edit-navigation').length) {
        navigationTopOffset = $("#wbebl-bulk-edit-navigation").offset().top;
    }

    $(document).on('click', '.wbebl-timepicker, .wbebl-datetimepicker, .wbebl-datepicker', function () {
        $(this).attr('data-val', $(this).val());
    });

    wbeblReInitDatePicker();
    wbeblReInitColorPicker();

    // Select2
    if ($.fn.select2) {
        let wbeblSelect2 = $(".wbebl-select2");
        if (wbeblSelect2.length) {
            wbeblSelect2.select2({
                placeholder: "Select ..."
            });
        }
    }

    if ($.fn.scrollbar) {
        $("#wbebl-items-table").scrollbar({
            autoScrollSize: false,
            scrollx: $(".external-scroll_x"),
        });
    }

    let mainTabs = [
        'bulk-edit',
        'column-manager',
        'meta-fields',
        'history',
        'import-export',
        'settings',
        'purchase-verification'
    ]
    let currentTab = (window.location.hash && $.inArray(window.location.hash.split('#')[1], mainTabs) !== -1) ? window.location.hash.split('#')[1] : 'bulk-edit';
    window.location.hash = currentTab;
    wbeblOpenTab($('.wbebl-tabs-list li a[data-content="' + currentTab + '"]'));
    if ($("#wbebl-bulk-edit-navigation").length > 0) {
        navigationTopOffset = ($("#wbebl-bulk-edit-navigation").offset().top > 300) ? $("#wbebl-bulk-edit-navigation").offset().top : 300;
    }

    // Tabs
    $(document).on("click", ".wbebl-tabs-list li a", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();
            window.location.hash = $(this).attr('data-content');
            wbeblOpenTab($(this));
            if ($("#wbebl-bulk-edit-navigation").length > 0) {
                navigationTopOffset = ($("#wbebl-bulk-edit-navigation").offset().top > 300) ? $("#wbebl-bulk-edit-navigation").offset().top : 300;
            }
        }
    });

    $(window).scroll(function () {
        if ($('a[data-content=bulk-edit]').hasClass('selected')) {
            let top = ($(window).width() > 768) ? "32px" : "0";
            if ($(window).scrollTop() >= navigationTopOffset) {
                $("#wbebl-bulk-edit-navigation").css({
                    position: "fixed",
                    top: top,
                    "z-index": 9988,
                    width: $("#wbebl-items-table").width()
                });
            } else {
                $("#wbebl-bulk-edit-navigation").css({
                    position: "static",
                    width: "100%"
                });
            }
        }
    });

    // Filter Form (Show & Hide)
    $(".wbebl-filter-form-toggle").on("click", function () {
        if ($("#wbebl-filter-form-content").attr("data-visibility") === "visible") {
            wbeblFilterFormClose();
        } else {
            wbeblFilterFormOpen();
        }

        if ($("#wbebl-filter-form").css("position") === "static") {
            setTimeout(function () {
                navigationTopOffset = $("#wbebl-bulk-edit-navigation").offset().top;
            }, 300);
        }
    });

    // Modal
    $(document).on("click", "[data-toggle=modal]", function () {
        let modal = $($(this).attr("data-target"));

        modal.fadeIn();
        modal.find(".wbebl-modal-box").fadeIn();
        $("#wbebl-last-modal-opened").val($(this).attr("data-target"));

        // set height for modal body
        setTimeout(function () {
            wbeblFixModalHeight(modal);
        }, 150)
    });

    $(document).on("click", "[data-toggle=modal-close]", function () {
        wbeblCloseModal();
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            wbeblCloseModal();
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            if ($("#wbebl-filter-form-content").css("display") === "block") {
                $("#wbebl-bulk-edit-filter-form-close-button").trigger("click");
            }
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    $(document).on('click', '#wbebl-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            $('#adminmenuback, #adminmenuwrap').hide();
            $('#wpcontent, #wpfooter').css({ "margin-left": 0 });
        } else {
            $('#adminmenuback, #adminmenuwrap').show();
            $('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".wbebl-check-item-main", function () {
        let checkbox_items = $(".wbebl-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wbebl-items-list tr").addClass("wbebl-tr-selected");
            checkbox_items.each(function () {
                $("#wbebl-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            wbeblShowSelectionTools();
            $("#wbebl-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wbebl-items-list tr").removeClass("wbebl-tr-selected");
            $("#wbebl-export-items-selected").html("");
            wbeblHideSelectionTools();
            $("#wbebl-export-only-selected-items").prop("disabled", true);
            $("#wbebl-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wbebl-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wbebl-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            if ($(".wbebl-check-item:checked").length === $(".wbebl-check-item").length) {
                $(".wbebl-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("wbebl-tr-selected");
        } else {
            $("#wbebl-export-items-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("wbebl-tr-selected");
            $(".wbebl-check-item-main").prop("checked", false);
        }

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".wbebl-check-item:checkbox:checked").length > 0) {
            $("#wbebl-export-only-selected-items").prop("disabled", false);
            wbeblShowSelectionTools();
        } else {
            wbeblHideSelectionTools();
            $("#wbebl-export-only-selected-items").prop("disabled", true);
            $("#wbebl-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#wbebl-bulk-edit-unselect", function () {
        $("input.wbebl-check-item").prop("checked", false);
        $("input.wbebl-check-item-main").prop("checked", false);
        wbeblHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#wbebl-column-profiles-choose", function () {
        let preset = $(this).val();
        $('.wbebl-column-profiles-fields input[type="checkbox"]').prop('checked', false);
        $('#wbebl-column-profile-select-all').prop('checked', false);
        $('.wbebl-column-profile-select-all span').text('Select All');
        $("#wbebl-column-profiles-apply").attr("data-preset-key",);
        if (defaultPresets && $.inArray(preset, defaultPresets) === -1) {
            $("#wbebl-column-profiles-update-changes").show();
        } else {
            $("#wbebl-column-profiles-update-changes").hide();
        }

        if (columnPresetsFields && columnPresetsFields[preset]) {
            columnPresetsFields[preset].forEach(function (val) {
                $('.wbebl-column-profiles-fields input[type="checkbox"][value="' + val + '"]').prop('checked', true);
            });
        }
    });

    $(document).on("keyup", "#wbebl-column-profile-search", function () {
        let wbeblSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wbebl-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wbeblSearchFieldValue) > -1);
        });
    });

    $(document).on('change', '#wbebl-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.wbebl-column-profile-fields input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.wbebl-column-profile-fields input:checkbox').prop('checked', false);
        }
        $(".wbebl-column-profile-save-dropdown").show();
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on(
        {
            mouseenter: function () {
                $(this)
                    .children(".wbebl-calculator")
                    .show();
            },
            mouseleave: function () {
                $(this)
                    .children(".wbebl-calculator")
                    .hide();
            }
        },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".wbebl-bulk-edit-delete-item", function () {
        $(this).find(".wbebl-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $(document).on("change", ".wbebl-column-profile-fields input:checkbox", function () {
        $(".wbebl-column-profile-save-dropdown").show();
    });

    $(document).on("click", ".wbebl-column-profile-save-dropdown", function () {
        $(this).find(".wbebl-column-profile-save-dropdown-buttons").slideToggle(200);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-wbebl-col-view"></li>');

    $(document).on(
        {
            mouseenter: function () {
                $('#wp-admin-bar-wbebl-col-view').html('#' + $(this).attr('data-item-id') + ' | ' + $(this).attr('data-item-title') + ' [<span class="wbebl-col-title">' + $(this).attr('data-col-title') + '</span>] ');
            },
            mouseleave: function () {
                $('#wp-admin-bar-wbebl-col-view').html('');
            }
        },
        "#wbebl-items-list td"
    );

    $(document).on("click", ".wbebl-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let element = $(this).closest('div');
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wbeblNewImageElementID = $(this).attr("data-id");
        let wbeblProductID = $(this).attr("data-item-id");
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
                    $("#url-" + wbeblNewImageElementID).val(attachment[0].url);
                    break;
                case "inline-file-custom-field":
                    $("#wbebl-file-url").val(attachment[0].url);
                    $('#wbebl-file-id').val(attachment[0].id)
                    break;
                case "inline-edit":
                    $("#" + wbeblNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + wbeblNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("#wbebl-modal-image button[data-item-id=" + wbeblProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("#wbebl-modal-gallery-items").append('<div class="wbebl-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wbebl-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
                case "bulk-edit-image":
                    element.find(".wbebl-bulk-edit-form-item-image").val(attachment[0].id);
                    element.find(".wbebl-bulk-edit-form-item-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wbebl-bulk-edit-form-remove-image">x</button></div>');
                    break;
                case "bulk-edit-file":
                    element.find(".wbebl-bulk-edit-form-item-file").val(attachment[0].id);
                    break;
                case "bulk-edit-gallery":
                    attachment.forEach(function (item) {
                        $(".wbebl-bulk-edit-form-item-gallery").append('<input type="hidden" value="' + item.id + '" data-field="value">');
                        $(".wbebl-bulk-edit-form-item-gallery-preview").append('<div><img src="' + item.url + '" width="43" height="43" alt=""><button type="button" data-id="' + item.id + '" class="wbebl-bulk-edit-form-remove-gallery-item">x</button></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".wbebl-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("change", ".wbebl-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wbebl-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wbebl-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("click", ".wbebl-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".wbebl-column-manager-available-fields[data-action=" + action + "] input[data-type=field]:checkbox:checked");
        if (checked.length > 0) {
            $('.wbebl-column-manager-empty-text').hide();
            if (action === 'new') {
                $('.wbebl-column-manager-added-fields-wrapper .wbebl-box-loading').show();
            } else {
                $('#wbebl-modal-column-manager-edit-preset .wbebl-box-loading').show();
            }
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            wbeblColumnManagerAddField(fieldName, fieldLabel, action);
        }
    });

    $(".wbebl-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#wbebl_column_manager_delete_preset_key").val($this.val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wbebl-button wbebl-button-lg wbebl-button-white",
            confirmButtonClass: "wbebl-button wbebl-button-lg wbebl-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wbebl-column-manager-delete-preset-form").submit();
            }
        }
        );
    });

    $(document).on("keyup", ".wbebl-column-manager-search-field", function () {
        let wbeblSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wbebl-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wbeblSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".wbebl-column-manager-remove-field", function () {
        $(".wbebl-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".wbebl-column-manager-right-item").remove();
        if ($('.wbebl-column-manager-added-fields-wrapper .wbebl-column-manager-right-item').length < 1) {
            $('.wbebl-column-manager-empty-text').show();
        }
    });

    if ($.fn.sortable) {
        let wbeblColumnManagerFields = $(".wbebl-column-manager-added-fields .items");
        wbeblColumnManagerFields.sortable({
            handle: ".wbebl-column-manager-field-sortable-btn",
            cancel: ""
        });
        wbeblColumnManagerFields.disableSelection();

        let wbeblMetaFieldItems = $(".wbebl-meta-fields-right");
        wbeblMetaFieldItems.sortable({
            handle: ".wbebl-meta-field-item-sortable-btn",
            cancel: ""
        });
        wbeblMetaFieldItems.disableSelection();
    }

    $(document).on("click", "#wbebl-add-meta-field-manual", function () {
        $(".wbebl-meta-fields-empty-text").hide();
        let input = $("#wbebl-meta-fields-manual_key_name");
        wbeblAddMetaKeysManual(input.val());
        input.val("");
    });

    $(document).on("click", "#wbebl-add-acf-meta-field", function () {
        let input = $("#wbebl-add-meta-fields-acf");
        if (input.val()) {
            $(".wbebl-meta-fields-empty-text").hide();
            wbeblAddACFMetaField(input.val(), input.find('option:selected').text(), input.find('option:selected').attr('data-type'));
            input.val("").change();
        }
    });

    $(document).on("click", ".wbebl-meta-field-remove", function () {
        $(this).closest(".wbebl-meta-fields-right-item").remove();
        if ($(".wbebl-meta-fields-right-item").length < 1) {
            $(".wbebl-meta-fields-empty-text").show();
        }
    });

    $(document).on("click", ".wbebl-history-delete-item", function () {
        $("#wbebl-history-clicked-id").attr("name", "delete").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wbebl-button wbebl-button-lg wbebl-button-white",
            confirmButtonClass: "wbebl-button wbebl-button-lg wbebl-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wbebl-history-items").submit();
            }
        });
    });

    $(document).on("click", "#wbebl-history-clear-all-btn", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wbebl-button wbebl-button-lg wbebl-button-white",
            confirmButtonClass: "wbebl-button wbebl-button-lg wbebl-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wbebl-history-clear-all").submit();
            }
        });
    });

    $(document).on("click", ".wbebl-history-revert-item", function () {
        $("#wbebl-history-clicked-id").attr("name", "revert").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wbebl-button wbebl-button-lg wbebl-button-white",
            confirmButtonClass: "wbebl-button wbebl-button-lg wbebl-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wbebl-history-items").submit();
            }
        });
    });

    $(document).on('click', '.wbebl-modal', function (e) {
        if ($(e.target).hasClass('wbebl-modal') || $(e.target).hasClass('wbebl-modal-container') || $(e.target).hasClass('wbebl-modal-box')) {
            wbeblCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on('change', '#wbebl-filter-form-content [data-field=value], #wbebl-filter-form-content [data-field=from], #wbebl-filter-form-content [data-field=to]', function () {
        wbeblCheckFilterFormChanges();
    });

    $(document).on('change', 'input[type=number][data-field=to]', function () {
        let from = $(this).closest('.wbebl-form-group').find('input[type=number][data-field=from]');
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val('').addClass('wbebl-input-danger').focus();
        }
    });

    $(document).on('change', 'input[type=number][data-field=from]', function () {
        let to = $(this).closest('.wbebl-form-group').find('input[type=number][data-field=to]');
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val('').addClass('wbebl-input-danger');
        } else {
            $(this).removeClass('wbebl-input-danger')
        }
    });

    $(document).on('change', '#wbebl-switcher', function () {
        wbeblLoadingStart();
        $('#wbebl-switcher-form').submit();
    });

    $(document).on('click', 'span[data-target="#wbebl-modal-image"]', function () {
        let tdElement = $(this).closest('td');
        let modal = $('#wbebl-modal-image');
        let col_title = tdElement.attr('data-col-title');
        let id = $(this).attr('data-id');
        let image_id = $(this).attr('data-image-id');
        let item_id = tdElement.attr('data-item-id');
        let full_size_url = $(this).attr('data-full-image-src');
        let field = tdElement.attr('data-field');
        let field_type = tdElement.attr('data-field-type');

        $('#wbebl-modal-image-item-title').text(col_title);
        modal.find('.wbebl-open-uploader').attr('data-id', id).attr('data-item-id', item_id);
        modal.find('.wbebl-inline-image-preview').attr('data-image-preview-id', id).html('<img src="' + full_size_url + '" />');
        modal.find('.wbebl-image-preview-hidden-input').attr('id', id);
        modal.find('button[data-button-type="save"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-image-url', full_size_url).attr('data-image-id', image_id).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        modal.find('button[data-button-type="remove"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
    });

    $(document).on('click', 'button[data-target="#wbebl-modal-file"]', function () {
        let modal = $('#wbebl-modal-file');
        modal.find('#wbebl-modal-select-file-item-title').text($(this).closest('td').attr('data-col-title'));
        modal.find('#wbebl-modal-file-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        modal.find('#wbebl-file-id').val($(this).attr('data-file-id'));
        modal.find('#wbebl-file-url').val($(this).attr('data-file-url'));
    });

    $(document).on('click', '#wbebl-modal-file-clear', function () {
        let modal = $('#wbebl-modal-file');
        modal.find('#wbebl-file-id').val(0).change();
        modal.find('#wbebl-file-url').val('').change();
    });

    $(document).on('click', '.wbebl-sub-tab-title', function () {
        $(this).closest('.wbebl-sub-tab-titles').find('.wbebl-sub-tab-title').removeClass('active');
        $(this).addClass('active');

        $(this).closest('div').find('.wbebl-sub-tab-content').hide();
        $(this).closest('div').find('.wbebl-sub-tab-content[data-content="' + $(this).attr('data-content') + '"]').show();
    });

    if ($('.wbebl-sub-tab-titles').length > 0) {
        $('.wbebl-sub-tab-titles').each(function () {
            $(this).find('.wbebl-sub-tab-title').first().trigger('click');
        });
    }

    $(document).on("mouseenter", ".wbebl-thumbnail", function () {
        let position = $(this).offset();
        let imageHeight = $(this).find('img').first().height();
        let top = ((position.top - imageHeight) > $('#wpadminbar').offset().top) ? position.top - imageHeight : position.top + 15;

        $('.wbebl-thumbnail-hover-box').css({
            top: top,
            left: position.left - 100,
            display: 'block',
            height: imageHeight
        }).html($(this).find('.wbebl-original-thumbnail').clone());
    });

    $(document).on("mouseleave", ".wbebl-thumbnail", function () {
        $('.wbebl-thumbnail-hover-box').hide();
    });

    setTimeout(function () {
        $('#wbebl-column-profiles-choose').trigger('change');
    }, 500);

    $(document).on('scroll', function () {
        let element = $('.wbebl-tab-middle-content');
        if (element.length > 0 && (element.offset().top + element.outerHeight(true)) <= ($(window).scrollTop() + $(window).height() + 50)) {
            $('.external-scroll_wrapper').css({
                position: 'absolute',
                bottom: '-30px',
                right: '0'
            })
        } else {
            $('.external-scroll_wrapper').css({
                position: 'fixed',
                bottom: '30px',
                right: '3%'
            })
        }
    });

    $(document).on('click', '.wbebl-filter-form-action', function () {
        wbeblFilterFormClose();
    });

    $(document).on('click', '#wbebl-license-renew-button', function () {
        $(this).closest('#wbebl-license').find('.wbebl-license-form').slideDown();
    });

    $(document).on('click', '#wbebl-license-form-cancel', function () {
        $(this).closest('#wbebl-license').find('.wbebl-license-form').slideUp();
    });

    $(document).on('click', '#wbebl-license-deactivate-button', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wbebl-button wbebl-button-lg wbebl-button-white",
            confirmButtonClass: "wbebl-button wbebl-button-lg wbebl-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#wbebl-license-deactivation-form').submit();
            }
        });
    });

    wbeblSetTipsyTooltip();
});
