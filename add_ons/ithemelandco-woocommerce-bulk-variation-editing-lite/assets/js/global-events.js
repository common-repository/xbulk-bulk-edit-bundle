jQuery(document).ready(function ($) {
    "use strict";

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

    $(document).on('click', '.iwbvel-reload-table', function () {
        iwbvelReloadProducts();
    });

    // Save Inline Edit By Enter Key
    $(document).on("keypress", '[data-type="edit-mode"]', function (event) {
        let iwbvelKeyCode = event.keyCode ? event.keyCode : event.which;
        if (iwbvelKeyCode === 13) {
            let productData = [];
            let productIds = [];
            let tdElement = $(this).closest('td');

            if ($('#iwbvel-bind-edit').prop('checked') === true) {
                productIds = iwbvelGetProductsChecked();
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
            iwbvelProductEdit(productIds, productData);
        }
    });

    // fetch product data by click to bulk edit button
    $(document).on("click", "#iwbvel-bulk-edit-bulk-edit-btn", function () {
        if ($(this).attr("data-fetch-product") === "yes") {
            let productID = $("input.iwbvel-check-item:visible:checkbox:checked");
            if (productID.length === 1) {
                iwbvelGetProductData(productID.val());
            } else {
                iwbvelResetBulkEditForm();
            }
        }
    });

    $(document).on('click', '.iwbvel-inline-edit-color-action', function () {
        $(this).closest('td').find('input.iwbvel-inline-edit-action').trigger('change');
    });

    $(document).on("change", ".iwbvel-inline-edit-action", function (e) {
        let $this = $(this);
        setTimeout(function () {
            if ($('div.xdsoft_datetimepicker:visible').length > 0) {
                e.preventDefault();
                return false;
            }

            if ($this.hasClass('iwbvel-datepicker') || $this.hasClass('iwbvel-timepicker') || $this.hasClass('iwbvel-datetimepicker')) {
                if ($this.attr('data-val') == $this.val()) {
                    e.preventDefault();
                    return false;
                }
            }

            let productData = [];
            let productIds = [];
            let tdElement = $this.closest('td');
            if ($('#iwbvel-bind-edit').prop('checked') === true) {
                productIds = iwbvelGetProductsChecked();
            }
            productIds.push($this.attr("data-item-id"));
            let iwbvelValue;
            switch (tdElement.attr("data-content-type")) {
                case 'checkbox_dual_mode':
                    iwbvelValue = $this.prop("checked") ? "yes" : "no";
                    break;
                case 'checkbox':
                    let checked = [];
                    tdElement.find('input[type=checkbox]:checked').each(function () {
                        checked.push($(this).val());
                    });
                    iwbvelValue = checked;
                    break;
                default:
                    iwbvelValue = $this.val();
                    break;
            }

            productData.push({
                name: tdElement.attr('data-name'),
                sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
                type: tdElement.attr('data-update-type'),
                value: iwbvelValue,
                operation: 'inline_edit'
            });

            iwbvelProductEdit(productIds, productData);
        }, 250)
    });

    $(document).on("click", ".iwbvel-inline-edit-clear-date", function () {
        let productData = [];
        let productIds = [];
        let tdElement = $(this).closest('td');

        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));
        productData.push({
            name: tdElement.attr('data-name'),
            sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
            type: tdElement.attr('data-update-type'),
            value: '',
            operation: 'inline_edit'
        });

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on("click", ".iwbvel-edit-action-price-calculator", function () {
        let productId = $(this).attr("data-item-id");
        let fieldName = $(this).attr("data-field");
        let productIds = [];
        let productData = [];

        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
        }
        productIds.push(productId);

        productData.push({
            name: fieldName,
            sub_name: '',
            type: $(this).attr('data-update-type'),
            operator: $("#iwbvel-" + fieldName + "-calculator-operator-" + productId).val(),
            value: $("#iwbvel-" + fieldName + "-calculator-value-" + productId).val(),
            operator_type: $("#iwbvel-" + fieldName + "-calculator-type-" + productId).val(),
            round: $("#iwbvel-" + fieldName + "-calculator-round-" + productId).val()
        });

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on("click", ".iwbvel-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr('data-delete-type');
        let productIds = iwbvelGetProductsChecked();

        if (!productIds.length && deleteType != 'all') {
            swal({
                title: "Please select one product",
                type: "warning"
            });
            return false;
        }

        swal({
            title: iwbvelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: iwbvelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (productIds.length > 0 || deleteType == 'all') {
                    iwbvelDeleteProduct(productIds, deleteType);
                } else {
                    swal({
                        title: "Please select one product",
                        type: "warning"
                    });
                }
            }
        });
    });

    $(document).on("click", "#iwbvel-bulk-edit-duplicate-start", function () {
        let productIDs = $("input.iwbvel-check-item:visible:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: iwbvelTranslate.duplicateVariationsDisabled,
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
            iwbvelDuplicateProduct(productIDs, parseInt($("#iwbvel-bulk-edit-duplicate-number").val()));
        }
    });

    $(document).on("click", ".iwbvel-top-nav-duplicate-button", function () {
        let productIDs = $("input.iwbvel-check-item:visible:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: iwbvelTranslate.duplicateVariationsDisabled,
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
            iwbvelOpenModal('#iwbvel-modal-item-duplicate');
        }
    });

    $(document).on("click", "#iwbvel-create-new-item", function () {
        let count = $("#iwbvel-new-item-count").val();
        iwbvelCreateNewProduct(count);
    });

    $(document).on("click", ".iwbvel-bulk-edit-show-variations-button", function () {
        if ($('#iwbvel-bulk-edit-show-variations').prop("checked") === true) {
            $(this).removeClass('selected')
            $('#iwbvel-bulk-edit-show-variations').prop("checked", false).change();
        } else {
            $(this).addClass('selected')
            $('#iwbvel-bulk-edit-show-variations').prop("checked", true).change();
        }
    });

    $(document).on("change", "#iwbvel-bulk-edit-show-variations", function () {
        if ($(this).prop("checked") === true) {
            $("tr[data-item-type=variation]").show();
            iwbvelShowVariationSelectionTools();
        } else {
            $("tr[data-item-type=variation]").hide();
            iwbvelHideVariationSelectionTools();
        }
    });

    $(document).on("click", ".iwbvel-bulk-edit-select-all-variations-button", function () {
        if ($('#iwbvel-bulk-edit-select-all-variations').prop("checked") === true) {
            $(this).removeClass('selected')
            $('#iwbvel-bulk-edit-select-all-variations').prop("checked", false).change();
        } else {
            $(this).addClass('selected')
            $('#iwbvel-bulk-edit-select-all-variations').prop("checked", true).change();
        }
    });

    $(document).on("change", "#iwbvel-bulk-edit-select-all-variations", function () {
        if ($(this).prop("checked") === true) {
            $("input.iwbvel-check-item[data-item-type=variation]").prop("checked", true);
        } else {
            $("input.iwbvel-check-item[data-item-type=variation]").prop("checked", false);
        }
    });

    $(document).on("click", "#iwbvel-column-profiles-save-as-new-preset", function () {
        let presetKey = $("#iwbvel-column-profiles-choose").val();
        let items = $(".iwbvel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        iwbvelSaveColumnProfile(presetKey, items, "save_as_new");
    });

    $(document).on("click", "#iwbvel-column-profiles-update-changes", function () {
        let presetKey = $("#iwbvel-column-profiles-choose").val();
        let items = $(".iwbvel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        iwbvelSaveColumnProfile(presetKey, items, "update_changes");
    });

    $(document).on("click", ".iwbvel-bulk-edit-filter-profile-load", function () {
        iwbvelLoadFilterProfile($(this).val());
        if ($(this).val() !== "default") {
            $("#iwbvel-bulk-edit-reset-filter").show();
        }
        $(".iwbvel-filter-profiles-items tr").removeClass("iwbvel-filter-profile-loaded");
        $(this).closest("tr").addClass("iwbvel-filter-profile-loaded");

        if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
            iwbvelCloseFloatSideModal();
        }
    });

    $(document).on("click", ".iwbvel-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
        swal({
            title: iwbvelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: iwbvelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                iwbvelDeleteFilterProfile(presetKey);
                if (item.hasClass('iwbvel-filter-profile-loaded')) {
                    $('.iwbvel-filter-profiles-items tbody tr:first-child').addClass('iwbvel-filter-profile-loaded').find('input[type=radio]').prop('checked', true);
                    $('#iwbvel-bulk-edit-reset-filter').trigger('click');
                }
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                }
                item.remove();
            }
        });
    });

    $(document).on("change", "input.iwbvel-filter-profile-use-always-item", function () {
        if ($(this).val() !== "default") {
            $("#iwbvel-bulk-edit-reset-filter").show();
        } else {
            $("#iwbvel-bulk-edit-reset-filter").hide();
        }
        iwbvelFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".iwbvel-filter-form-action", function (e) {
        let data = iwbvelGetCurrentFilterData();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search" && $('#iwbvel-quick-search-text').val() !== '') {
            iwbvelResetFilterForm();
            $('#iwbvel-bulk-edit-reset-filter').hide();
        }
        if (action === "pro_search") {
            $('#iwbvel-bulk-edit-reset-filter').show();
            iwbvelResetQuickSearchForm();
            $(".iwbvel-filter-profiles-items tr").removeClass("iwbvel-filter-profile-loaded");
            $('input.iwbvel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
            iwbvelFilterProfileChangeUseAlways("default");
        }
        iwbvelProductsFilter(data, action, null, page);
    });

    $(document).on("click", "#iwbvel-filter-form-reset", function () {
        iwbvelResetFilters();
    });

    $(document).on("click", "#iwbvel-bulk-edit-reset-filter", function () {
        iwbvelResetFilters();
    });

    $(document).on("change", "#iwbvel-quick-search-field", function () {
        let options = $("#iwbvel-quick-search-operator option");
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
    $("#iwbvel-quick-per-page").on("change", function () {
        iwbvelChangeCountPerPage($(this).val());
    });

    $(document).on("click", ".iwbvel-edit-action-with-button", function () {
        let productIds = [];
        let productData = [];
        let modal = $(this).closest('.iwbvel-modal');

        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));

        let iwbvelValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                iwbvelValue = tinymce.get("iwbvel-text-editor").getContent();
                break;
            case "select_products":
                iwbvelValue = $('#iwbvel-select-products-value').val();
                break;
            case "multi_select":
                if (modal) {
                    iwbvelValue = modal.find('.iwbvel-modal-acf-taxonomy-multi-select-value').val();
                }
                break;
            case "select_files":
                let names = $('.iwbvel-inline-edit-file-name').map(function () {
                    return $(this).val();
                }).get();

                let urls = $('.iwbvel-inline-edit-file-url').map(function () {
                    return $(this).val();
                }).get();

                iwbvelValue = {
                    files_name: names,
                    files_url: urls,
                };
                break;
            case "file":
                iwbvelValue = $('#iwbvel-modal-file #iwbvel-file-id').val();
                break;
            case "image":
                iwbvelValue = $(this).attr("data-image-id");
                break;
            case "gallery":
                iwbvelValue = $("#iwbvel-modal-gallery-items input.iwbvel-inline-edit-gallery-image-ids").map(function () {
                    return $(this).val();
                }).get();
                break;
        }

        productData.push({
            name: $(this).attr('data-name'),
            sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
            type: $(this).attr('data-update-type'),
            value: iwbvelValue,
            operation: 'inline_edit'
        });

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on("click", ".iwbvel-load-text-editor", function () {
        tinymce.get("iwbvel-text-editor").setContent('');

        let productId = $(this).attr("data-item-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $('#iwbvel-modal-text-editor-item-title').text($(this).attr('data-item-name'));
        $("#iwbvel-text-editor-apply").attr("data-field", field).attr("data-field-type", fieldType).attr("data-item-id", productId);
        $.ajax({
            url: IWBVEL_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "iwbvel_get_text_editor_content",
                nonce: IWBVEL_DATA.nonce,
                product_id: productId,
                field: field,
                field_type: fieldType
            },
            success: function (response) {
                if (response.success && response.content !== '') {
                    tinymce.get("iwbvel-text-editor").setContent(response.content);
                    tinymce.execCommand('mceFocus', false, 'iwbvel-text-editor');
                }
            },
            error: function () { }
        });
    });

    $(document).on("click", "#iwbvel-create-new-product-taxonomy", function () {
        if ($("#iwbvel-new-product-category-name").val() !== "") {
            let taxonomyInfo = {
                name: $("#iwbvel-new-product-taxonomy-name").val(),
                slug: $("#iwbvel-new-product-taxonomy-slug").val(),
                parent: $("#iwbvel-new-product-taxonomy-parent").val(),
                description: $("#iwbvel-new-product-taxonomy-description").val(),
                product_id: $(this).attr("data-item-id"),
                modal_id: $(this).attr('data-closest-id')
            };
            iwbvelAddProductTaxonomy(taxonomyInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: iwbvelTranslate.taxonomyNameRequired,
                type: "warning"
            });
        }
    });

    //Search
    $(document).on("keyup", ".iwbvel-search-in-list", function () {
        let iwbvelSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-id") + " .iwbvel-product-items-list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(iwbvelSearchValue) > -1);
        });
    });

    $(document).on("click", "#iwbvel-create-new-product-attribute", function () {
        if ($("#iwbvel-new-product-attribute-name").val() !== "") {
            let attributeInfo = {
                name: $("#iwbvel-new-product-attribute-name").val(),
                slug: $("#iwbvel-new-product-attribute-slug").val(),
                description: $("#iwbvel-new-product-attribute-description").val(),
                product_id: $(this).attr("data-item-id")
            };
            iwbvelAddProductAttribute(attributeInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: iwbvelTranslate.attributeNameRequired,
                type: "warning"
            });
        }
    });

    $(document).on('click', 'button[data-target="#iwbvel-modal-select-products"]', function () {
        let childrenIds = $(this).attr('data-children-ids').split(',');
        let tdElement = $(this).closest('td');
        $('#iwbvel-select-products-value').val('').change();
        $('#iwbvel-modal-select-products-item-title').text($(this).attr('data-item-name'));
        $('#iwbvel-modal-select-products .iwbvel-edit-action-with-button').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        iwbvelSetSelectedProducts(childrenIds);
    });

    $(document).on('click', '#iwbvel-modal-select-files-add-file-item', function () {
        iwbvelAddNewFileItem();
    });

    $(document).on('click', 'button[data-toggle=modal][data-target="#iwbvel-modal-select-files"]', function () {
        let tdElement = $(this).closest('td');
        $('#iwbvel-modal-select-files-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr(('data-field'))).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        $('#iwbvel-modal-select-files-item-title').text($(this).closest('td').attr('data-col-title'));
        iwbvelGetProductFiles($(this).attr('data-item-id'));
    });

    $(document).on('click', '.iwbvel-inline-edit-file-remove-item', function () {
        $(this).closest('.iwbvel-modal-select-files-file-item').remove();
    });

    if ($.fn.sortable) {
        let iwbvelSelectFiles = $(".iwbvel-inline-select-files");
        iwbvelSelectFiles.sortable({
            handle: ".iwbvel-select-files-sortable-btn",
            cancel: ""
        });
        iwbvelSelectFiles.disableSelection();

        // yikes custom tabs 
        let iwbvelTabItems = $("#iwbvel-modal-yikes-custom-tabs");
        iwbvelTabItems.sortable({
            handle: ".iwbvel-yikes-tab-item-sort",
            cancel: ""
        });
        iwbvelTabItems.disableSelection();
    }

    $(document).on("change", ".iwbvel-bulk-edit-form-variable", function () {
        let newVal = $(this).val() ? $(this).closest("div").find("input[type=text]").val() + "{" + $(this).val() + "}" : "";
        $(this).closest("div").find("input[type=text]").first().val(newVal).change();
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).closest('#iwbvel-variations-bulk-actions-modal').length > 0) {
            return false;
        }
        let id = $(this).closest(".iwbvel-form-group").find("label").attr("for");
        let $this = $(this);

        if ($(this).val() === "text_replace") {
            $(this).closest(".iwbvel-form-group").append("<div class='iwbvel-bulk-edit-form-extra-field'>" +
                "<select id='" + id + "-sensitive' data-field='sensitive'>" +
                "<option value='yes'>" + iwbvelTranslate.sameCase + "</option>" +
                "<option value='no'>" + iwbvelTranslate.ignoreCase + "</option>" +
                "</select>" +
                "<input type='text' id='" + id + "-replace' data-field='replace' placeholder='" + iwbvelTranslate.enterText + "'>" +
                "<select class='iwbvel-bulk-edit-form-variable' title='" + iwbvelTranslate.selectVariable + "' data-field='variable'>" +
                "<option value=''>" + iwbvelTranslate.variable + "</option>" +
                "<option value='title'>" + iwbvelTranslate.title + "</option>" +
                "<option value='id'>" + iwbvelTranslate.id + "</option>" +
                "<option value='sku'>" + iwbvelTranslate.sku + "</option>" +
                "<option value='menu_order'>Menu Order</option>" +
                "<option value='parent_id'>" + iwbvelTranslate.parentId + "</option>" +
                "<option value='parent_title'>" + iwbvelTranslate.parentTitle + "</option>" +
                "<option value='parent_sku'>" + iwbvelTranslate.parentSku + "</option>" +
                "<option value='regular_price'>" + iwbvelTranslate.regularPrice + "</option>" +
                "<option value='sale_price'>" + iwbvelTranslate.salePrice + "</option>" +
                "</select>" +
                "</div>");
        } else if ($(this).val() === "number_round") {
            $(this).closest(".iwbvel-form-group").append('<div class="iwbvel-bulk-edit-form-extra-field"><select id="' + id + '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>');
        } else {
            $(this).closest(".iwbvel-form-group").find(".iwbvel-bulk-edit-form-extra-field").remove();
        }
        if ($.inArray($(this).val(), ['number_clear', 'text_remove_duplicate']) !== -1) {
            $(this).closest(".iwbvel-form-group").find('input[data-field=value]').val('').prop('disabled', true);
            $(this).closest(".iwbvel-form-group").find('select[data-field=variable]').val('').prop('disabled', true);
        } else {
            $(this).closest(".iwbvel-form-group").find('input[data-field=value]').prop('disabled', false);
            $(this).closest(".iwbvel-form-group").find('select[data-field=variable]').prop('disabled', false);
        }

        setTimeout(function () {
            changedTabs($this);
        }, 150)
    });

    $("#iwbvel-float-side-modal-bulk-edit .iwbvel-tab-content-item").on("change", "[data-field=value]", function () {
        changedTabs($(this));
    });

    $(document).on("change", ".iwbvel-date-from", function () {
        let field_to = $('#' + $(this).attr('data-to-id'));
        let datepicker = true;
        let timepicker = false;
        let format = 'Y/m/d';

        if ($(this).hasClass('iwbvel-datetimepicker')) {
            timepicker = true;
            format = 'Y/m/d H:i'
        }

        if ($(this).hasClass('iwbvel-timepicker')) {
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

    $(document).on("click", ".iwbvel-bulk-edit-form-remove-image", function () {
        $(this).closest("div").remove();
        $(".iwbvel-bulk-edit-form-product-image").val("");
    });

    $(document).on("click", ".iwbvel-bulk-edit-form-remove-gallery-item", function () {
        $(this).closest("div").remove();
        $("#iwbvel-bulk-edit-form-product-gallery input[value=" + $(this).attr("data-id") + "]").remove();
    });

    var sortType = 'DESC'
    $(document).on('click', '.iwbvel-sortable-column', function () {
        if (sortType === 'DESC') {
            sortType = 'ASC';
            $(this).find('i.iwbvel-sortable-column-icon').text('d');
        } else {
            sortType = 'DESC';
            $(this).find('i.iwbvel-sortable-column-icon').text('u');
        }
        iwbvelSortByColumn($(this).attr('data-column-name'), sortType);
    });

    $(document).on("click", ".iwbvel-column-manager-edit-field-btn", function () {
        $('#iwbvel-modal-column-manager-edit-preset .iwbvel-box-loading').show();
        let presetKey = $(this).val();
        $('#iwbvel-modal-column-manager-edit-preset .items').html('');
        $("#iwbvel-column-manager-edit-preset-key").val(presetKey);
        $("#iwbvel-column-manager-edit-preset-name").val($(this).attr("data-preset-name"));
        iwbvelColumnManagerFieldsGetForEdit(presetKey);
    });

    $(document).on("change", ".iwbvel-meta-fields-main-type", function () {
        let item = $(this).closest('.iwbvel-meta-fields-right-item');
        if ($(this).val() === "textinput") {
            item.find(".iwbvel-meta-fields-sub-type").show();
        } else {
            item.find(".iwbvel-meta-fields-sub-type").hide();
        }

        if ($.inArray($(this).val(), ['select', 'array']) !== -1) {
            item.find(".iwbvel-meta-fields-key-value").show();
        } else {
            item.find(".iwbvel-meta-fields-key-value").hide();
        }
    });

    $("#iwbvel-column-manager-add-new-preset").on("submit", function (e) {
        if ($(this).find(".iwbvel-column-manager-added-fields .items .iwbvel-column-manager-right-item").length < 1) {
            e.preventDefault();
            swal({
                title: iwbvelTranslate.plzAddColumns,
                type: "warning"
            });
        }
    });

    $(document).on("click", "#iwbvel-bulk-edit-form-reset", function () {
        iwbvelResetBulkEditForm();
    });

    $(document).on("click", "#iwbvel-filter-form-save-preset", function () {
        let presetName = $("#iwbvel-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = iwbvelGetProSearchData();
            iwbvelSaveFilterPreset(data, presetName);
        } else {
            swal({
                title: iwbvelTranslate.presetNameRequired,
                type: "warning"
            });
        }
    });

    $(document).on("click", "#iwbvel-bulk-edit-form-do-bulk-edit", function (e) {
        let productIds = iwbvelGetProductsChecked();
        let productData = [];

        $("#iwbvel-float-side-modal-bulk-edit .iwbvel-form-group").each(function () {
            let value;
            if ($(this).find('[data-field="value"]').length > 1) {
                value = $(this).find('[data-field="value"]').map(function () {
                    if ($(this).val() !== '') {
                        return $(this).val();
                    }
                }).get();
            } else {
                if ($(this).find('[data-field="value"]').val() !== '') {
                    value = $(this).find('[data-field="value"]').val();
                }
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
            if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                iwbvelCloseFloatSideModal();
            }
            iwbvelProductEdit(productIds, productData);
            if (IWBVEL_DATA.iwbvel_settings.keep_filled_data_in_bulk_edit_form == 'no') {
                iwbvelResetBulkEditForm();
            }
        } else {
            swal({
                title: iwbvelTranslate.areYouSureForEditAllFilteredProducts,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
                confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
                confirmButtonText: iwbvelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                        iwbvelCloseFloatSideModal();
                    }
                    iwbvelProductEdit(productIds, productData);
                    if (IWBVEL_DATA.iwbvel_settings.keep_filled_data_in_bulk_edit_form == 'yes') {
                        iwbvelResetBulkEditForm();
                    }
                }
            });
        }
    });

    $(document).on('click', '[data-target="#iwbvel-modal-new-item"]', function () {
        $('#iwbvel-new-item-title').html(iwbvelTranslate.newProduct);
        $('#iwbvel-new-item-description').html(iwbvelTranslate.newProductNumber);
    });

    $(document).on('click', '[data-target="#iwbvel-modal-text-editor"]', function () {
        let tdElement = $(this).closest('td');
        $('#iwbvel-modal-text-editor-item-title').html($(this).attr(''));
        $('#iwbvel-text-editor-apply').attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($('#iwbvel-quick-search-text').val() !== '' && $($('#iwbvel-last-modal-opened').val()).css('display') !== 'block' && $('.iwbvel-tabs-list button[data-content=bulk-edit]').hasClass('selected')) {
                iwbvelReloadProducts();
                $('#iwbvel-quick-search-reset').show();
            }
            if ($("#iwbvel-modal-new-product-taxonomy").css("display") === "block") {
                $("#iwbvel-create-new-product-taxonomy").trigger("click");
            }
            if ($("#iwbvel-modal-new-item").css("display") === "block") {
                $("#iwbvel-create-new-item").trigger("click");
            }
            if ($("#iwbvel-modal-item-duplicate").css("display") === "block") {
                $("#iwbvel-bulk-edit-duplicate-start").trigger("click");
            }

            // filter form
            if ($('#iwbvel-float-side-modal-filter:visible').length) {
                $('#iwbvel-float-side-modal-filter:visible').find('.iwbvel-filter-form-action').trigger('click');
            }
        }
    });

    let query;
    $(".iwbvel-get-products-ajax").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: IWBVEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "iwbvel_get_products_name",
                    nonce: IWBVEL_DATA.nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: iwbvelTranslate.enterProductName,
        minimumInputLength: 3
    });

    $(document).on('click', '.iwbvel-bulk-edit-status-filter-item', function () {
        $('.iwbvel-top-nav-status-filter').hide();

        $('.iwbvel-bulk-edit-status-filter-item').removeClass('active');
        $(this).addClass('active');
        $('.iwbvel-status-filter-selected-name').text(' - ' + $(this).text());

        if ($(this).attr('data-status') === 'all') {
            $('#iwbvel-filter-form-reset').trigger('click');
        } else {
            $('#iwbvel-filter-form-product-status').val($(this).attr('data-status')).change();
            setTimeout(function () {
                $('#iwbvel-filter-form-get-products').trigger('click');
            }, 250);
        }
    });

    $(document).on('click', '.iwbvel-reset-filter-form', function () {
        iwbvelResetFilters();
    });

    $(document).on("click", "#iwbvel-filter-form-get-products", function () {
        iwbvelFilterFormCheckAttributes();
        iwbvelCheckResetFilterButton();
    });

    $(document).on("click", ".iwbvel-inline-edit-taxonomy-save", function () {
        let productData = [];
        let productIds = [];

        let value = $("#iwbvel-modal-taxonomy-" + $(this).attr("data-field") + "-" + $(this).attr("data-item-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));
        productData.push({
            name: $(this).attr('data-name'),
            sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
            type: $(this).attr('data-update-type'),
            value: value,
            operation: 'inline_edit'
        });

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on('click', '.iwbvel-product-attribute', function () {
        let modalId = $(this).attr('data-target');
        $(modalId).find('input.is-visible').prop('checked', ($(this).attr('data-is-variation') == 'true')).change();
        $(modalId).find('input.is-visible-prev').val(($(this).attr('data-is-visible') == 'true') ? 'yes' : 'no');
        $(modalId).find('input.is-variation').prop('checked', ($(this).attr('data-is-variation') == 'true')).change();
        $(modalId).find('input.is-variation-prev').val(($(this).attr('data-is-variation') == 'true') ? 'yes' : 'no');
    });

    $(document).on("click", ".iwbvel-inline-edit-attribute-save", function () {
        let productData = [];
        let productIds = [];
        let modal = $("#iwbvel-modal-attribute-" + $(this).attr("data-field") + "-" + $(this).attr("data-item-id"));
        let value = modal.find("input[data-field='value']:checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
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

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on("click", ".iwbvel-inline-edit-add-new-taxonomy", function () {
        $("#iwbvel-create-new-product-taxonomy").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id")).attr('data-closest-id', $(this).attr('data-closest-id'));
        $('#iwbvel-modal-new-product-taxonomy-product-title').text($(this).attr('data-item-name'));
        iwbvelGetTaxonomyParentSelectBox($(this).attr("data-field"));
    });

    $(document).on("click", ".iwbvel-inline-edit-add-new-attribute", function () {
        $("#iwbvel-create-new-product-attribute").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        $('#iwbvel-modal-new-product-attribute-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", 'button.iwbvel-calculator[data-target="#iwbvel-modal-numeric-calculator"]', function () {
        let btn = $("#iwbvel-modal-numeric-calculator .iwbvel-edit-action-numeric-calculator");
        let tdElement = $(this).closest('td');
        btn.attr("data-item-id", $(this).attr("data-item-id"));
        btn.attr("data-field", $(this).attr("data-field"));
        btn.attr("data-name", tdElement.attr('data-name'));
        btn.attr("data-update-type", tdElement.attr('data-update-type'));
        btn.attr("data-field-type", $(this).attr("data-field-type"));
        if ($(this).attr('data-field') === 'download_limit' || $(this).attr('data-field') === 'download_expiry') {
            $('#iwbvel-modal-numeric-calculator #iwbvel-numeric-calculator-type').val('n').change().hide();
            $('#iwbvel-modal-numeric-calculator #iwbvel-numeric-calculator-round').val('').change().hide();
        } else {
            $('#iwbvel-modal-numeric-calculator #iwbvel-numeric-calculator-type').show();
            $('#iwbvel-modal-numeric-calculator #iwbvel-numeric-calculator-round').show();
        }
        $('#iwbvel-modal-numeric-calculator-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", ".iwbvel-edit-action-numeric-calculator", function () {
        let productId = $(this).attr("data-item-id");
        let productIds = [];
        let productData = [];

        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
        }
        productIds.push(productId);

        productData.push({
            name: $(this).attr("data-name"),
            sub_name: ($(this).attr("data-name")) ? $(this).attr("data-name") : '',
            type: $(this).attr('data-update-type'),
            operator: $("#iwbvel-numeric-calculator-operator").val(),
            value: $("#iwbvel-numeric-calculator-value").val(),
            operator_type: ($("#iwbvel-numeric-calculator-type").val()) ? $("#iwbvel-numeric-calculator-type").val() : 'n',
            round: $("#iwbvel-numeric-calculator-round").val()
        });

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on('keyup', 'input[type=number][data-field=download_limit], input[type=number][data-field=download_expiry]', function () {
        if ($(this).val() < -1) {
            $(this).val(-1);
        }
    });

    $(document).on('click', '#iwbvel-quick-search-button', function () {
        if ($('#iwbvel-quick-search-text').val() !== '') {
            $('#iwbvel-quick-search-reset').show();
        }
    });

    $(document).on('click', '#iwbvel-quick-search-reset', function () {
        iwbvelResetFilters()
    });

    $(document).on('click', 'button[data-toggle="modal"][data-target="#iwbvel-modal-product-badges"]', function () {
        $('#iwbvel-modal-product-badges-item-title').text($(this).attr('data-item-name'));
        $('#iwbvel-modal-product-badges-apply').attr('data-item-id', $(this).attr('data-item-id'));
        $('#iwbvel-modal-product-badge-items').val('').change();
        iwbvelGetProductBadges($(this).attr('data-item-id'));
    });

    $(document).on('click', 'button[data-toggle="modal"][data-target="#iwbvel-modal-ithemeland-badge"]', function () {
        let productId = $(this).attr('data-item-id');
        $('#iwbvel-modal-ithemeland-badge-item-title').text($(this).attr('data-item-name'));
        $('#iwbvel-modal-ithemeland-badge-apply').attr('data-item-id', productId);
        $('.it_unique_nav_for_general').trigger('click');
        $('#_unique_label_type').val('none').change();
        iwbvelGetProductIthemelandBadge(productId);
    });

    $(document).on('click', 'button[data-toggle="modal"][data-target="#iwbvel-modal-yikes-custom-product-tabs"]', function () {
        $('#iwbvel-modal-yikes-custom-tabs').html('');
        let productId = $(this).attr('data-item-id');
        $('#iwbvel-modal-yikes-custom-product-tabs-item-title').text($(this).attr('data-item-name'));
        $('#iwbvel-modal-yikes-custom-product-tabs-apply').attr('data-item-id', productId);
        iwbvelGetYikesCustomProductTabs(productId);
    });

    $(document).on('click', '#iwbvel-modal-product-badges-apply', function () {
        let productIds = [];
        let productData = [];
        productIds.push($(this).attr('data-item-id'));
        productData.push({
            name: "_yith_wcbm_product_meta",
            sub_name: "id_badge",
            type: "meta_field",
            operation: "inline_edit",
            value: $('#iwbvel-modal-product-badge-items').val(),
        });

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on('click', '#iwbvel-yikes-add-tab', function () {
        let newUniqueId = 'editor-' + Math.floor((Math.random() * 9999) + 1000);
        $('#iwbvel-modal-yikes-custom-product-tabs #duplicate-item').clone().appendTo('#iwbvel-modal-yikes-custom-tabs').ready(function () {
            let duplicated = $('#iwbvel-modal-yikes-custom-tabs').find('#duplicate-item');
            duplicated.find('.iwbvel-yikes-tab-content').attr('data-id', newUniqueId).find('textarea').attr('id', newUniqueId);
            duplicated.removeAttr('id');
            wp.editor.initialize(newUniqueId, iwbvelWpEditorSettings);
        });
    });

    $(document).on('click', '#iwbvel-modal-ithemeland-badge-apply', function () {
        let productIds = [];
        let productData = [];
        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
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

        iwbvelProductEdit(productIds, productData);
    });

    $('#iwbvel-bulk-edit-select-all-variations').prop('checked', false);

    if (itemIdInUrl && itemIdInUrl > 0) {
        iwbvelResetFilterForm();
        setTimeout(function () {
            $('#iwbvel-filter-form-product-ids').val(itemIdInUrl);
            $('#iwbvel-filter-form-get-products').trigger('click');
        }, 500);
    }

    $(document).on('click', '.iwbvel-yikes-tab-item-header', function (e) {
        if ($.inArray($(e.target).attr('class'), ['iwbvel-yikes-tab-item-header', 'iwbvel-yikes-tab-item-header-title']) !== -1) {
            if ($(this).closest('div.iwbvel-yikes-tab-item').find('.iwbvel-yikes-tab-item-body:visible').length > 0) {
                $('.iwbvel-yikes-tab-item-body').slideUp(250);
            } else {
                $('.iwbvel-yikes-tab-item-body').slideUp(250);
                $(this).closest('div.iwbvel-yikes-tab-item').find('.iwbvel-yikes-tab-item-body').slideDown(250);
            }
        }
    });

    $(document).on('keyup', '.iwbvel-yikes-tab-title input', function () {
        $(this).closest('.iwbvel-yikes-tab-item').find('.iwbvel-yikes-tab-item-header strong').text($(this).val());
    });

    $(document).on('click', '.iwbvel-yikes-tab-item-remove', function () {
        $(this).closest('.iwbvel-yikes-tab-item').remove();
    });

    $(document).on('click', '#iwbvel-modal-yikes-custom-product-tabs-apply', function () {
        let productIds = [];
        let productData = [];
        if ($('#iwbvel-bind-edit').prop('checked') === true) {
            productIds = iwbvelGetProductsChecked();
        }
        productIds.push($(this).attr("data-item-id"));

        let tabs = [];
        let customProductTabsElement = $(this).closest('#iwbvel-modal-yikes-custom-product-tabs').find('#yikes-custom-product-tabs-form .iwbvel-yikes-tab-item');
        if (customProductTabsElement.length > 0) {
            customProductTabsElement.each(function () {
                let editorId = ($(this).find('.iwbvel-yikes-tab-content').attr('data-id'));
                tabs.push({
                    global_tab: $(this).find('input[name="global_tab"]').val(),
                    title: $(this).find('.iwbvel-yikes-tab-title input').val(),
                    content: tinymce.get(editorId).getContent()
                });
            })
        }

        productData.push({
            name: 'yikes_woo_products_tabs',
            type: 'meta_field',
            value: tabs
        });

        iwbvelProductEdit(productIds, productData);
    });

    $(document).on('click', '#iwbvel-yikes-add-saved-tab', function () {
        $('#iwbvel-last-modal-opened').val('.iwbvel-yikes-saved-tabs');
        $(this).closest('#iwbvel-modal-yikes-custom-product-tabs').find('.iwbvel-yikes-saved-tabs').fadeIn(250);
    });

    $(document).on('click', '.iwbvel-yikes-saved-tabs-close-button', function () {
        $(this).closest('.iwbvel-yikes-saved-tabs').fadeOut(250);
        $('#iwbvel-last-modal-opened').val('#iwbvel-modal-yikes-custom-product-tabs');
    });

    $(document).on('click', '.iwbvel-yikes-saved-tab-add', function () {
        iwbvelAddYikesSavedTab($(this).attr('data-id'));
        $('.iwbvel-yikes-saved-tabs-close-button').trigger('click')
    });

    $(document).on('change', '.iwbvel-yikes-override-tab', function () {
        let tabItem = $(this).closest('.iwbvel-yikes-tab-item');
        let globalInput = tabItem.find('input[name="global_tab"]');
        if ($(this).prop('checked') === false) {
            globalInput.val(globalInput.attr('data-global-id'));
            tabItem.find('.iwbvel-yikes-tab-title input').prop('disabled', 'disabled');
            tabItem.find('.iwbvel-yikes-tab-content button').prop('disabled', 'disabled');
            tinyMCE.get(tabItem.find('.iwbvel-yikes-tab-content').attr('data-id')).getBody().setAttribute('contenteditable', false);
        } else {
            globalInput.val('');
            tabItem.find('.iwbvel-yikes-tab-title input').prop('disabled', false);
            tabItem.find('.iwbvel-yikes-tab-content button').prop('disabled', false);
            tinyMCE.get(tabItem.find('.iwbvel-yikes-tab-content').attr('data-id')).getBody().setAttribute('contenteditable', true);
        }
    });

    $(document).on('click', '[data-toggle="modal"][data-target="#iwbvel-modal-gallery"]', function () {
        let tdElement = $(this).closest('td');
        $('#iwbvel-modal-gallery #iwbvel-modal-gallery-items').html('');
        $('#iwbvel-modal-gallery #iwbvel-modal-gallery-title').text($(this).attr('data-item-name'));
        $('#iwbvel-modal-gallery #iwbvel-modal-gallery-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        iwbvelGetProductGalleryImages($(this).attr('data-item-id'));
    });

    $(document).on('click', '.iwbvel-delete-item-btn', function () {
        let productIds = [];
        productIds.push($(this).attr('data-item-id'));
        let deleteType = $(this).attr('data-delete-type');
        swal({
            title: iwbvelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: iwbvelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                iwbvelDeleteProduct(productIds, deleteType);
            }
        });
    });

    $(document).on('click', '.iwbvel-restore-item-btn', function () {
        let productIds = [];
        productIds.push($(this).attr('data-item-id'));
        swal({
            title: iwbvelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: iwbvelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                iwbvelRestoreProduct(productIds);
            }
        });
    });

    $(document).on('change', '#iwbvel-filter-form-product-status', function () {
        if ($(this).val() === 'trash') {
            $('.iwbvel-top-navigation-trash-buttons').show();
        } else {
            $('.iwbvel-top-navigation-trash-buttons').hide();
        }
    });

    $(document).on('click', '#iwbvel-bulk-edit-trash-empty', function () {
        swal({
            title: iwbvelTranslate.areYouSure,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
            confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
            confirmButtonText: iwbvelTranslate.iAmSure,
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                iwbvelEmptyTrash();
            }
        });
    });

    $(document).on('click', '#iwbvel-bulk-edit-trash-restore', function () {
        let productIds = iwbvelGetProductsChecked();
        iwbvelRestoreProduct(productIds);
    });

    $(document).on('click', '.iwbvel-acf-taxonomy-multi-select', function () {
        $('.iwbvel-modal-acf-taxonomy-multi-select-value').select2();
    })

    iwbvelGetDefaultFilterProfileProducts();

    iwbvelCheckShowVariations();

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

    $(document).on('click', '.iwbvel-history-pagination-item', function () {
        $('.iwbvel-history-pagination-loading').show();

        let filters = {
            operation: $("#iwbvel-history-filter-operation").val(),
            author: $("#iwbvel-history-filter-author").val(),
            fields: $("#iwbvel-history-filter-fields").val(),
            date: {
                from: $("#iwbvel-history-filter-date-from").val(),
                to: $("#iwbvel-history-filter-date-to").val()
            }
        };

        iwbvelHistoryChangePage($(this).attr('data-index'), filters);
    });

    if ($('#iwbvel-settings-show-only-filtered-variations').val() === 'yes') {
        $('#iwbvel-bulk-edit-show-variations').prop('checked', true).attr('disabled', 'disabled');
    }
});