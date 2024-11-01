"use strict";

var wcbelOpenFullScreenIcon = '<i class="wcbel-icon-enlarge"></i>';
var wcbelCloseFullScreenIcon = '<i class="wcbel-icon-shrink"></i>';

function openFullscreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

function wcbelDataTableFixSize() {
    jQuery('#wcbel-main').css({
        top: jQuery('#wpadminbar').height() + 'px',
        "padding-left": (jQuery('#adminmenu:visible').length) ? jQuery('#adminmenu').width() + 'px' : 0
    });

    jQuery('#wcbel-loading').css({
        top: jQuery('#wpadminbar').height() + 'px',
    });

    let height = parseInt(jQuery(window).height()) - parseInt(jQuery('#wcbel-header').height() + 85);

    jQuery('.wcbel-table').css({
        "max-height": height + 'px'
    });
}

function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    }
}

function wcbelFullscreenHandler() {
    if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        jQuery('#wcbel-full-screen').html(wcbelOpenFullScreenIcon).attr('title', 'Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').show();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
    } else {
        jQuery('#wcbel-full-screen').html(wcbelCloseFullScreenIcon).attr('title', 'Exit Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').hide();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": 0 });
    }

    wcbelDataTableFixSize();
}

function wcbelOpenTab(item) {
    let wcbelTabItem = item;
    let wcbelParentContent = wcbelTabItem.closest(".wcbel-tabs-list");
    let wcbelParentContentID = wcbelParentContent.attr("data-content-id");
    let wcbelDataBox = wcbelTabItem.attr("data-content");
    wcbelParentContent.find("li button.selected").removeClass("selected");
    if (wcbelTabItem.closest('.wcbel-sub-tab').length > 0) {
        wcbelTabItem.closest('li.wcbel-has-sub-tab').find('button').first().addClass("selected");
    } else {
        wcbelTabItem.addClass("selected");
    }

    if (item.closest('.wcbel-tabs-list').attr('data-content-id') && item.closest('.wcbel-tabs-list').attr('data-content-id') == 'wcbel-main-tabs-contents') {
        jQuery('.wcbel-tabs-list[data-content-id="wcbel-main-tabs-contents"] li[data-depend] button').not('.wcbel-tab-item').addClass('disabled');
        jQuery('.wcbel-tabs-list[data-content-id="wcbel-main-tabs-contents"] li[data-depend="' + wcbelDataBox + '"] button').removeClass('disabled');
    }

    jQuery("#" + wcbelParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wcbelParentContentID + " div[data-content=" + wcbelDataBox + "]").addClass("selected");

    if (item.attr("data-type") === "main-tab") {
        wcbelFilterFormClose();
    }
}

function wcbelFixModalHeight(modal) {
    if (!modal.attr('data-height-fixed') || modal.attr('data-height-fixed') != 'true') {
        let footerHeight = 0;
        let contentHeight = modal.find(".wcbel-modal-content").height();
        let titleHeight = modal.find(".wcbel-modal-title").height();
        if (modal.find(".wcbel-modal-footer").length > 0) {
            footerHeight = modal.find(".wcbel-modal-footer").height();
        }

        let modalMargin = parseInt((parseInt(jQuery('body').height()) * 20) / 100);
        let bodyHeight = (modal.find(".wcbel-modal-body-content").length) ? parseInt(modal.find(".wcbel-modal-body-content").height() + 30) : contentHeight;
        let bodyMaxHeight = parseInt(jQuery('body').height()) - (titleHeight + footerHeight + modalMargin);
        if (modal.find('.wcbel-modal-top-search').length > 0) {
            bodyHeight += parseInt(modal.find('.wcbel-modal-top-search').height() + 30);
            bodyMaxHeight -= parseInt(modal.find('.wcbel-modal-top-search').height());
        }

        modal.find(".wcbel-modal-content").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.find(".wcbel-modal-body").css({
            "height": parseInt(bodyHeight) + 'px',
            'max-height': parseInt(bodyMaxHeight) + 'px'
        });
        modal.find(".wcbel-modal-box").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.attr('data-height-fixed', 'true');
    }
}

function wcbelOpenFloatSideModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn(20);
    modal.find(".wcbel-float-side-modal-box").animate({
        right: 0
    }, 180);
}

function wcbelCloseFloatSideModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    jQuery('.wcbel-float-side-modal-box').animate({
        right: "-80%"
    }, 180);
    jQuery('.wcbel-float-side-modal').fadeOut(200);
}

function wcbelCloseModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    let lastModalOpened = jQuery('#wcbel-last-modal-opened');
    let modal = jQuery(lastModalOpened.val());
    if (lastModalOpened.val() !== '') {
        modal.find(' .wcbel-modal-box').fadeOut();
        modal.fadeOut();
        lastModalOpened.val('');
    } else {
        let lastModal = jQuery('.wcbel-modal:visible').last();
        lastModal.find('.wcbel-modal-box').fadeOut();
        lastModal.fadeOut();
    }

    setTimeout(function () {
        modal.find('.wcbel-modal-box').css({
            height: 'auto',
            "max-height": '80%'
        });
        modal.find('.wcbel-modal-body').css({
            height: 'auto',
            "max-height": '90%'
        });
        modal.find('.wcbel-modal-content').css({
            height: 'auto',
            "max-height": '92%'
        });
    }, 400);
}

function wcbelOpenModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn();
    modal.find(".wcbel-modal-box").fadeIn();
    jQuery("#wcbel-last-modal-opened").val(jQuery(this).attr("data-target"));

    // set height for modal body
    setTimeout(function () {
        wcbelFixModalHeight(modal);
    }, 150)
}

function wcbelReInitColorPicker() {
    if (jQuery('.wcbel-color-picker').length > 0) {
        jQuery('.wcbel-color-picker').wpColorPicker();
    }
    if (jQuery('.wcbel-color-picker-field').length > 0) {
        jQuery('.wcbel-color-picker-field').wpColorPicker();
    }
}

function wcbelReInitDatePicker() {
    if (jQuery.fn.datetimepicker) {
        jQuery('.wcbel-datepicker-with-dash').datetimepicker('destroy');
        jQuery('.wcbel-datepicker').datetimepicker('destroy');
        jQuery('.wcbel-timepicker').datetimepicker('destroy');
        jQuery('.wcbel-datetimepicker').datetimepicker('destroy');

        jQuery('.wcbel-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y/m/d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wcbel-datepicker-with-dash').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wcbel-timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wcbel-datetimepicker').datetimepicker({
            format: 'Y/m/d H:i',
            scrollMonth: false,
            scrollInput: false
        });
    }

}

function wcbelPaginationLoadingStart() {
    jQuery('.wcbel-pagination-loading').show();
}

function wcbelPaginationLoadingEnd() {
    jQuery('.wcbel-pagination-loading').hide();
}

function wcbelLoadingStart() {
    jQuery('#wcbel-loading').removeClass('wcbel-loading-error').removeClass('wcbel-loading-success').text('Loading ...').slideDown(300);
}

function wcbelLoadingSuccess(message = 'Success !') {
    jQuery('#wcbel-loading').removeClass('wcbel-loading-error').addClass('wcbel-loading-success').text(message).delay(1500).slideUp(200);
}

function wcbelLoadingError(message = 'Error !') {
    jQuery('#wcbel-loading').removeClass('wcbel-loading-success').addClass('wcbel-loading-error').text(message).delay(1500).slideUp(200);
}

function wcbelSetColorPickerTitle() {
    jQuery('.wcbel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wcbel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wcbelSetTipsyTooltip();
    });
}

function wcbelFilterFormClose() {
    if (jQuery('#wcbel-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.wcbel-filter-form-icon').addClass('wcbel-icon-chevron-down').removeClass('wcbel-icon-chevron-up');
        jQuery('#wcbel-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function wcbelSetTipsyTooltip() {
    jQuery('[title]').tipsy({
        html: true,
        arrowWidth: 10, //arrow css border-width * 2, default is 5 * 2
        attr: 'data-tipsy',
        cls: null,
        duration: 150,
        offset: 7,
        position: 'top-center',
        trigger: 'hover',
        onShow: null,
        onHide: null
    });
}

function wcbelCheckUndoRedoStatus(reverted, history) {
    if (reverted) {
        wcbelEnableRedo();
    } else {
        wcbelDisableRedo();
    }
    if (history) {
        wcbelEnableUndo();
    } else {
        wcbelDisableUndo();
    }
}

function wcbelDisableUndo() {
    jQuery('#wcbel-bulk-edit-undo').attr('disabled', 'disabled');
}

function wcbelEnableUndo() {
    jQuery('#wcbel-bulk-edit-undo').prop('disabled', false);
}

function wcbelDisableRedo() {
    jQuery('#wcbel-bulk-edit-redo').attr('disabled', 'disabled');
}

function wcbelEnableRedo() {
    jQuery('#wcbel-bulk-edit-redo').prop('disabled', false);
}

function wcbelHideSelectionTools() {
    jQuery('.wcbel-bulk-edit-form-selection-tools').hide();
    jQuery('#wcbel-bulk-edit-trash-restore').hide();
}

function wcbelShowSelectionTools() {
    jQuery('.wcbel-bulk-edit-form-selection-tools').show();
    jQuery('#wcbel-bulk-edit-trash-restore').show();
}

function wcbelSetColorPickerTitle() {
    jQuery('.wcbel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wcbel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wcbelSetTipsyTooltip();
    });
}

function wcbelColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wcbel_column_manager_add_field',
            nonce: WCBEL_DATA.ajax_nonce,
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function (response) {
            jQuery('.wcbel-box-loading').hide();
            jQuery('.wcbel-column-manager-added-fields[data-action=' + action + '] .items').append(response);
            fieldName.forEach(function (name) {
                jQuery('.wcbel-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            wcbelReInitColorPicker();
            jQuery('.wcbel-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.wcbel-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function () {
                wcbelSetColorPickerTitle();
            }, 250);
        },
        error: function () {
        }
    })
}

function wcbelAddMetaKeysManual(meta_key_name) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wcbel_add_meta_keys_manual',
            nonce: WCBEL_DATA.ajax_nonce,
            meta_key_name: meta_key_name,
        },
        success: function (response) {
            jQuery('#wcbel-meta-fields-items').append(response);
            wcbelLoadingSuccess();
        },
        error: function () {
            wcbelLoadingError();
        }
    })
}

function wcbelAddACFMetaField(field_name, field_label, field_type) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wcbel_add_acf_meta_field',
            nonce: WCBEL_DATA.ajax_nonce,
            field_name: field_name,
            field_label: field_label,
            field_type: field_type
        },
        success: function (response) {
            jQuery('#wcbel-meta-fields-items').append(response);
            wcbelLoadingSuccess();
        },
        error: function () {
            wcbelLoadingError();
        }
    })
}

function wcbelCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#wcbel-filter-form-content [data-field="value"]').each(function () {
        if (jQuery.isArray(jQuery(this).val())) {
            if (jQuery(this).val().length > 0) {
                isChanged = true;
            }
        } else {
            if (jQuery(this).val()) {
                isChanged = true;
            }
        }
    });
    jQuery('#wcbel-filter-form-content [data-field="from"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wcbel-filter-form-content [data-field="to"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery('#filter-form-changed').val(isChanged);

    if (isChanged === true) {
        jQuery('#wcbel-bulk-edit-reset-filter').show();
    } else {
        jQuery('.wcbel-top-nav-status-filter button[data-status="all"]').addClass('active');
    }
}

function wcbelGetCheckedItem() {
    let itemIds;
    let itemsChecked = jQuery("input.wcbel-check-item:checkbox:checked");
    if (itemsChecked.length > 0) {
        itemIds = itemsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }

    return itemIds;
}

function wcbelGetTableCount(countPerPage, currentPage, total) {
    currentPage = (currentPage) ? currentPage : 1;
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = (total > 0) ? parseInt(showingTo - countPerPage) + 1 : 0;
    showingTo = (showingTo < total) ? showingTo : total;
    return "Showing " + showingFrom + " to " + showingTo + " of " + total + " entries";
}

function wcbelGetProductGalleryImages(productsId) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_gallery_images',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productsId,
        },
        success: function (response) {
            if (response.success && response.images !== '') {
                jQuery('#wcbel-modal-gallery-items').html(response.images);
            }
        },
        error: function () { }
    })
}

function wcbelGetProductsChecked() {
    let productIds = [];
    let productsChecked = jQuery("input.wcbel-check-item:visible:checkbox:checked");
    if (productsChecked.length > 0) {
        productIds = productsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }
    return productIds;
}

function wcbelReloadProducts(edited_ids = [], current_page = wcbelGetCurrentPage()) {
    let data = wcbelGetCurrentFilterData();
    wcbelProductsFilter(data, 'pro_search', edited_ids, current_page);
}

function wcbelProductsFilter(data, action, edited_ids = null, page = wcbelGetCurrentPage()) {
    if (action === 'pagination') {
        wcbelPaginationLoadingStart();
    } else {
        wcbelLoadingStart();
    }

    if (WCBEL_DATA.wcbel_settings.close_popup_after_applying == 'yes') {
        wcbelCloseFloatSideModal();
    }

    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_products_filter',
            nonce: WCBEL_DATA.ajax_nonce,
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
                wcbelSetProductsList(response, edited_ids)
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelClearFilterDataWithRedirect() {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_clear_filter_data',
            nonce: WCBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            window.location.search = '?page=wcbel';
        },
        error: function () { }
    });
}

function wcbelSetStatusFilter(statusFilters) {
    jQuery('.wcbel-top-nav-status-filter').html(statusFilters);

    jQuery('.wcbel-bulk-edit-status-filter-item').removeClass('active');
    let statusFilter = (jQuery('#wcbel-filter-form-product-status').val() && jQuery('#wcbel-filter-form-product-status').val() != '') ? jQuery('#wcbel-filter-form-product-status').val() : 'all';
    if (jQuery.isArray(statusFilter)) {
        statusFilter.forEach(function (val) {
            jQuery('.wcbel-bulk-edit-status-filter-item[data-status="' + val + '"]').addClass('active');
        });
    } else {
        let activeItem = jQuery('.wcbel-bulk-edit-status-filter-item[data-status="' + statusFilter + '"]');
        activeItem.addClass('active');
        jQuery('.wcbel-status-filter-selected-name').text(' - ' + activeItem.text())
    }
}

function wcbelSetProductsList(response, edited_ids = null) {
    jQuery('#wcbel-bulk-edit-select-all-variations').prop('checked', false);
    jQuery('#wcbel-items-table').html(response.products_list);
    jQuery('.wcbel-items-pagination').html(response.pagination);
    jQuery('.wcbel-items-count').html(wcbelGetTableCount(jQuery('#wcbel-quick-per-page').val(), wcbelGetCurrentPage(), response.products_count));
    wcbelSetStatusFilter(response.status_filters);

    wcbelReInitDatePicker();
    wcbelReInitColorPicker();
    wcbelCheckShowVariations();
    if (edited_ids && edited_ids.length > 0) {
        jQuery('tr').removeClass('wcbel-item-edited');
        edited_ids.forEach(function (productID) {
            jQuery('tr[data-item-id="' + productID + '"]').addClass('wcbel-item-edited');
            jQuery('input[value="' + productID + '"]').prop('checked', true);
        });
        wcbelShowSelectionTools();
    } else {
        wcbelHideSelectionTools();
    }
    if (jQuery('#wcbel-bulk-edit-show-variations').prop('checked') === true) {
        jQuery('tr[data-product-type=variation]').show();
    }
    wcbelSetTipsyTooltip();
}

function wcbelGetProductData(productID) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_data',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productID
        },
        success: function (response) {
            if (response.success) {
                wcbelSetProductDataBulkEditForm(response.product_data);
            } else {

            }
        },
        error: function () {

        }
    });
}

function wcbelSetSelectedProducts(productIds) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_by_ids',
            nonce: WCBEL_DATA.ajax_nonce,
            product_ids: productIds
        },
        success: function (response) {
            if (response.success && response.products instanceof Object && Object.keys(response.products).length > 0) {
                let productsField = jQuery('#wcbel-select-products-value');
                if (productsField.length > 0) {
                    jQuery.each(response.products, function (productId, productTitle) {
                        productsField.append("<option value='" + productId + "' selected>" + productTitle + "</option>").prop('selected', true);
                    });
                }
            }
        },
        error: function () {

        }
    });
}

function wcbelSetProductDataBulkEditForm(productData) {

    let reviews_allowed = (productData.reviews_allowed) ? 'yes' : 'no';
    let sold_individually = (productData.sold_individually) ? 'yes' : 'no';
    let manage_stock = (productData.manage_stock) ? 'yes' : 'no';
    let featured = (productData.featured) ? 'yes' : 'no';
    let virtual = (productData.virtual) ? 'yes' : 'no';
    let downloadable = (productData.downloadable) ? 'yes' : 'no';

    let attributes = jQuery('#wcbel-float-side-modal-bulk-edit .wcbel-bulk-edit-form-group[data-type=attribute]');
    if (attributes.length > 0) {
        let attribute_name = '';
        attributes.each(function () {
            attribute_name = jQuery(this).attr('data-name');
            if (productData.attribute[attribute_name]) {
                jQuery('#wcbel-float-side-modal-bulk-edit .wcbel-bulk-edit-form-group[data-type=attribute][data-name="' + attribute_name + '"]').find('select[data-field=value]').val(productData.attribute[attribute_name]).change();
            }
        });
    }

    let custom_fields = jQuery('#wcbel-float-side-modal-bulk-edit .wcbel-bulk-edit-form-group[data-type=custom_fields]');
    if (custom_fields.length > 0) {
        let taxonomy_name = '';
        custom_fields.each(function () {
            taxonomy_name = jQuery(this).attr('data-name');
            if (productData.meta_field[taxonomy_name]) {
                jQuery('#wcbel-float-side-modal-bulk-edit .wcbel-bulk-edit-form-group[data-type=custom_fields][data-name="' + taxonomy_name + '"]').find('[data-field=value]').val(productData.meta_field[taxonomy_name][0]).change();
            }
        });
    }

    jQuery('#wcbel-bulk-edit-form-product-title').val(productData.post_title);
    jQuery('#wcbel-bulk-edit-form-product-slug').val(productData.post_slug);
    jQuery('#wcbel-bulk-edit-form-product-sku').val(productData.sku);
    jQuery('#wcbel-bulk-edit-form-product-description').val(productData.post_content);
    jQuery('#wcbel-bulk-edit-form-product-short-description').val(productData.post_excerpt);
    jQuery('#wcbel-bulk-edit-form-product-purchase-note').val(productData.purchase_note);
    jQuery('#wcbel-bulk-edit-form-product-menu-order').val(productData.menu_order);
    jQuery('#wcbel-bulk-edit-form-product-sold-individually').val(sold_individually).change();
    jQuery('#wcbel-bulk-edit-form-product-enable-reviews').val(reviews_allowed).change();
    jQuery('#wcbel-bulk-edit-form-product-product-status').val(productData.post_status).change();
    jQuery('#wcbel-bulk-edit-form-product-catalog-visibility').val(productData.catalog_visibility).change();
    jQuery('#wcbel-bulk-edit-form-product-date-created').val(productData.post_date);
    jQuery('#wcbel-bulk-edit-form-product-author').val(productData.post_author).change();
    jQuery('#wcbel-bulk-edit-form-categories').val(productData.product_cat).change();
    jQuery('#wcbel-bulk-edit-form-tags').val(productData.product_tag).change();
    jQuery('#wcbel-bulk-edit-form-regular-price').val(productData.regular_price);
    jQuery('#wcbel-bulk-edit-form-sale-price').val(productData.sale_price);
    jQuery('#wcbel-bulk-edit-form-sale-date-from').val(productData.date_on_sale_from);
    jQuery('#wcbel-bulk-edit-form-sale-date-to').val(productData.date_on_sale_to);
    jQuery('#wcbel-bulk-edit-form-tax-status').val(productData.tax_status).change();
    jQuery('#wcbel-bulk-edit-form-tax-class').val(productData.tax_class).change();
    jQuery('#wcbel-bulk-edit-form-shipping-class').val(productData.shipping_class).change();
    jQuery('#wcbel-bulk-edit-form-width').val(productData.width);
    jQuery('#wcbel-bulk-edit-form-height').val(productData.height);
    jQuery('#wcbel-bulk-edit-form-length').val(productData.length);
    jQuery('#wcbel-bulk-edit-form-weight').val(productData.weight);
    jQuery('#wcbel-bulk-edit-form-manage-stock').val(manage_stock).change();
    jQuery('#wcbel-bulk-edit-form-stock-status').val(productData.stock_status).change();
    jQuery('#wcbel-bulk-edit-form-stock-quantity').val(productData.stock_quantity);
    jQuery('#wcbel-bulk-edit-form-low-stock-amount').val(productData.low_stock_amount);
    jQuery('#wcbel-bulk-edit-form-backorders').val(productData.backorders).change();
    jQuery('#wcbel-bulk-edit-form-product-type').val(productData.product_type).change();
    jQuery('#wcbel-bulk-edit-form-featured').val(featured).change();
    jQuery('#wcbel-bulk-edit-form-virtual').val(virtual).change();
    jQuery('#wcbel-bulk-edit-form-downloadable').val(downloadable).change();
    jQuery('#wcbel-bulk-edit-form-download-limit').val(productData.download_limit);
    jQuery('#wcbel-bulk-edit-form-download-expiry').val(productData.download_expiry).change();
    jQuery('#wcbel-bulk-edit-form-product-url').val(productData.meta_field._product_url);
    jQuery('#wcbel-bulk-edit-form-button-text').val(productData.meta_field._button_text);
    jQuery('#wcbel-bulk-edit-form-upsells').val(productData.upsell_ids).change();
    jQuery('#wcbel-bulk-edit-form-cross-sells').val(productData.cross_sell_ids).change();
}

function wcbelDeleteProduct(productIDs, deleteType) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_delete_products',
            nonce: WCBEL_DATA.ajax_nonce,
            product_ids: productIDs,
            delete_type: deleteType,
            filter_data: wcbelGetCurrentFilterData(),
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts();
                wcbelHideSelectionTools();
                wcbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbel-history-items tbody').html(response.history_items);
                jQuery('.wcbel-history-pagination-container').html(response.history_pagination);
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelRestoreProduct(productIDs) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_untrash_products',
            nonce: WCBEL_DATA.ajax_nonce,
            product_ids: productIDs,
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts();
                wcbelHideSelectionTools();
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelEmptyTrash() {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_empty_trash',
            nonce: WCBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts();
                wcbelHideSelectionTools();
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelDuplicateProduct(productIDs, duplicateNumber) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_duplicate_product',
            nonce: WCBEL_DATA.ajax_nonce,
            product_ids: productIDs,
            duplicate_number: duplicateNumber
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts([], wcbelGetCurrentPage());
                wcbelCloseModal();
                wcbelHideSelectionTools();
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelCreateNewProduct(count = 1) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_create_new_product',
            nonce: WCBEL_DATA.ajax_nonce,
            count: count
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts([], 1);
                wcbelCloseModal();
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelHideVariationSelectionTools() {
    jQuery('#wcbel-bulk-edit-select-all-variations-tools').hide();
}

function wcbelShowVariationSelectionTools() {
    jQuery('#wcbel-bulk-edit-select-all-variations-tools').show();
}

function wcbelGetAllCombinations(attributes_arr) {
    var combinations = [],
        args = attributes_arr,
        max = args.length - 1;
    helper([], 0);

    function helper(arr, i) {
        for (let j = 0; j < args[i][1].length; j++) {
            let a = arr.slice(0);
            a.push([args[i][0], args[i][1][j]]);
            if (i === max) {
                combinations.push(a);
            } else {
                helper(a, i + 1);
            }
        }
    }

    return combinations;
}

function wcbelSetProductsVariations(productIDs, attributes, variations, default_variation) {
    wcbelLoadingStart();

    if (WCBEL_DATA.wcbel_settings.close_popup_after_applying == 'yes') {
        wcbelCloseFloatSideModal();
    }

    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_set_products_variations',
            nonce: WCBEL_DATA.ajax_nonce,
            product_ids: productIDs,
            attributes: attributes,
            variations: variations,
            default_variation: default_variation
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts(productIDs)
                wcbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbel-history-items tbody').html(response.history_items);
                jQuery('.wcbel-history-pagination-container').html(response.history_pagination);
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelDeleteProductsVariations(ProductIds, deleteType, variations) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_delete_products_variations',
            nonce: WCBEL_DATA.ajax_nonce,
            product_ids: ProductIds,
            delete_type: deleteType,
            variations: variations
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts(ProductIds, wcbelGetCurrentPage());
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function getProductVariationsForAttach(productID, attribute, attributeItem) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_variations_for_attach',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productID,
            attribute: attribute,
            attribute_item: attributeItem
        },
        success: function (response) {
            if (response.success && response.variations) {
                jQuery('#wcbel-variations-attaching-product-variations').html(response.variations);
                jQuery('#wcbel-variation-attaching-start-attaching').prop('disabled', false);
            } else {
                jQuery('#wcbel-variation-attaching-start-attaching').attr('disabled', 'disabled');
                jQuery('#wcbel-variations-attaching-product-variations').html('<span class="wcbel-alert wcbel-alert-danger">' + wcbelTranslate.productHasNoVariations + '</span>');
            }
        },
        error: function () {
            jQuery('#wcbel-variation-attaching-start-attaching').attr('disabled', 'disabled');
            jQuery('#wcbel-variations-attaching-product-variations').html('');
        }
    });
}

function getAttributeValuesForAttach(name) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_attribute_values_for_attach',
            nonce: WCBEL_DATA.ajax_nonce,
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-variation-attaching-attribute-items').html('<div class="wcbel-w40p wcbel-float-left"><select title="' + wcbelTranslate.selectAttribute + '" id="wcbel-variations-attaching-attribute-item" class="wcbel-w100p">' + response.attribute_items + '</select></div>');
                jQuery('.wcbel-variations-attaching-variation-attribute-item').html(response.attribute_items);
            } else {
                jQuery('#wcbel-variation-attaching-attribute-items').html('');
                jQuery('.wcbel-variations-attaching-variation-attribute-item').html('');
            }
        },
        error: function () {
            jQuery('#wcbel-variation-attaching-attribute-items').html('');
            jQuery('.wcbel-variations-attaching-variation-attribute-item').html('');
        }
    });
}

function wcbelVariationAttaching(productId, attributeKey, variationId, attributeItem) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_variation_attaching',
            nonce: WCBEL_DATA.ajax_nonce,
            attribute_key: attributeKey,
            variation_id: variationId,
            attribute_item: attributeItem
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts([productId]);
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelSaveColumnProfile(presetKey, items, type) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_save_column_profile',
            nonce: WCBEL_DATA.ajax_nonce,
            preset_key: presetKey,
            items: items,
            type: type
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
                location.href = location.href.replace(location.hash, "");
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelLoadFilterProfile(presetKey) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_load_filter_profile',
            nonce: WCBEL_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wcbelResetFilterForm();
                wcbelLoadingSuccess();
                wcbelSetProductsList(response);
                wcbelCloseModal();

                setTimeout(function () {
                    setFilterValues(response.filter_data);
                }, 500);
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelDeleteFilterProfile(presetKey) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_delete_filter_profile',
            nonce: WCBEL_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelFilterProfileChangeUseAlways(presetKey) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_filter_profile_change_use_always',
            nonce: WCBEL_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
            } else {
                wcbelLoadingError()
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelGetCurrentFilterData() {
    return (jQuery('#wcbel-quick-search-text').val()) ? wcbelGetQuickSearchData() : wcbelGetProSearchData()
}

function wcbelResetQuickSearchForm() {
    jQuery('.wcbel-top-nav-filters-search input').val('');
    jQuery('.wcbel-top-nav-filters-search select').prop('selectedIndex', 0);
    jQuery('#wcbel-quick-search-reset').hide();
}

function wcbelResetFilterForm() {
    jQuery('#wcbel-float-side-modal-filter input').val('');
    jQuery('#wcbel-float-side-modal-filter textarea').val('');
    jQuery('#wcbel-float-side-modal-filter select').prop('selectedIndex', 0).trigger('change');
    jQuery('#wcbel-float-side-modal-filter .wcbel-select2').val(null).trigger('change');
    jQuery('.wcbel-bulk-edit-status-filter-item').removeClass('active');
}

function wcbelResetFilters() {
    wcbelResetFilterForm();
    wcbelResetQuickSearchForm();
    jQuery(".wcbel-filter-profiles-items tr").removeClass("wcbel-filter-profile-loaded");
    jQuery('input.wcbel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
    jQuery("#wcbel-bulk-edit-reset-filter").hide();
    jQuery('#wcbel-bulk-edit-reset-filter').hide();

    jQuery('.wcbel-reset-filter-form').closest('li').hide();

    setTimeout(function () {
        if (window.location.search !== '?page=wcbel') {
            wcbelClearFilterDataWithRedirect()
        } else {
            let data = wcbelGetCurrentFilterData();
            wcbelFilterProfileChangeUseAlways("default");
            wcbelProductsFilter(data, "pro_search");
        }
    }, 250);
}

function wcbelChangeCountPerPage(countPerPage) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_change_count_per_page',
            nonce: WCBEL_DATA.ajax_nonce,
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadProducts([], 1);
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelAddProductTaxonomy(taxonomyInfo, taxonomyName, taxonomy_id) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_add_product_taxonomy',
            nonce: WCBEL_DATA.ajax_nonce,
            taxonomy_info: taxonomyInfo,
            taxonomy_name: taxonomyName,
        },
        success: function (response) {
            if (response.success) {
                taxonomy_id = (taxonomyInfo.modal_id) ? taxonomyInfo.modal_id : 'wcbel-modal-taxonomy-' + taxonomyName + '-' + taxonomyInfo.product_id;
                jQuery('#' + taxonomy_id + ' .wcbel-product-items-list').html(response.taxonomy_items);
                wcbelLoadingSuccess();
                wcbelCloseModal()
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelAddProductAttribute(attributeInfo, attributeName) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_add_product_attribute',
            nonce: WCBEL_DATA.ajax_nonce,
            attribute_info: attributeInfo,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-modal-attribute-' + attributeName + '-' + attributeInfo.product_id + ' .wcbel-product-items-list ul').html(response.attribute_items);
                wcbelLoadingSuccess();
                wcbelCloseModal()
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelAddNewFileItem() {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_add_new_file_item',
            nonce: WCBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-modal-select-files .wcbel-inline-select-files').prepend(response.file_item);
                wcbelSetTipsyTooltip();
            }
        },
        error: function () {

        }
    });
}

function wcbelGetProductFiles(productID) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_files',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productID,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-modal-select-files .wcbel-inline-select-files').html(response.files);
                wcbelSetTipsyTooltip();
            } else {
                jQuery('#wcbel-modal-select-files .wcbel-inline-select-files').html('');
            }
        },
        error: function () {
            jQuery('#wcbel-modal-select-files .wcbel-inline-select-files').html('');
        }
    });
}

function changedTabs(item) {
    let change = false;

    let tab = jQuery('nav.wcbel-tabs-navbar a[data-content="' + item.closest('.wcbel-tab-content-item').attr('data-content') + '"]');
    item.closest('.wcbel-tab-content-item').find('[data-field=operator]').each(function () {
        if (jQuery(this).val() === 'text_remove_duplicate') {
            change = true;
            return false;
        }
    });
    item.closest('.wcbel-tab-content-item').find('[data-field="value"]').each(function () {
        if (jQuery(this).val() && jQuery(this).val() != '') {
            change = true;
            return false;
        }
    });

    if (change === true) {
        tab.addClass('wcbel-tab-changed');
    } else {
        tab.removeClass('wcbel-tab-changed');
    }
}

function wcbelCheckResetFilterButton() {
    if (jQuery('#wcbel-bulk-edit-filter-tabs-contents [data-field="value"]').length > 0) {
        jQuery('#wcbel-bulk-edit-filter-tabs-contents [data-field="value"]').each(function () {
            if (jQuery(this).val() != '') {
                jQuery('.wcbel-reset-filter-form').closest('li').show();
                return true;
            }
        });
    }

    if (jQuery('#wcbel-bulk-edit-filter-tabs-contents [data-field="from"]').length > 0) {
        jQuery('#wcbel-bulk-edit-filter-tabs-contents [data-field="from"]').each(function () {
            if (jQuery(this).val() != '') {
                jQuery('.wcbel-reset-filter-form').closest('li').show();
                return true;
            }
        });
    }

    if (jQuery('#wcbel-bulk-edit-filter-tabs-contents [data-field="to"]').length > 0) {
        jQuery('#wcbel-bulk-edit-filter-tabs-contents [data-field="to"]').each(function () {
            if (jQuery(this).val() != '') {
                jQuery('.wcbel-reset-filter-form').closest('li').show();
                return true;
            }
        });
    }
}

function wcbelGetQuickSearchData() {
    return {
        search_type: 'quick_search',
        quick_search_text: jQuery('#wcbel-quick-search-text').val(),
        quick_search_field: jQuery('#wcbel-quick-search-field').val(),
        quick_search_operator: jQuery('#wcbel-quick-search-operator').val(),
    };
}

function wcbelSortByColumn(columnName, sortType) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_sort_by_column',
            nonce: WCBEL_DATA.ajax_nonce,
            filter_data: wcbelGetCurrentFilterData(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
                wcbelSetProductsList(response)
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelColumnManagerFieldsGetForEdit(presetKey) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_column_manager_get_fields_for_edit',
            nonce: WCBEL_DATA.ajax_nonce,
            preset_key: presetKey
        },
        success: function (response) {
            jQuery('#wcbel-modal-column-manager-edit-preset .wcbel-box-loading').hide();
            jQuery('.wcbel-column-manager-added-fields[data-action=edit] .items').html(response.html);
            setTimeout(function () {
                wcbelSetColorPickerTitle();
            }, 250);
            jQuery('.wcbel-column-manager-available-fields[data-action=edit] li').each(function () {
                if (jQuery.inArray(jQuery(this).attr('data-name'), response.fields.split(',')) !== -1) {
                    jQuery(this).attr('data-added', 'true').hide();
                } else {
                    jQuery(this).attr('data-added', 'false').show();
                }
            });
            jQuery('.wcbel-color-picker').wpColorPicker();
        },
    })
}

function wcbelHistoryUndo() {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_history_undo',
            nonce: WCBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                wcbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbel-history-items tbody').html(response.history_items);
                jQuery('.wcbel-history-pagination-container').html(response.history_pagination);
                wcbelReloadProducts(response.product_ids);
            }
        },
        error: function () {

        }
    });
}

function wcbelHistoryRedo() {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_history_redo',
            nonce: WCBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                wcbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbel-history-items tbody').html(response.history_items);
                jQuery('.wcbel-history-pagination-container').html(response.history_pagination);
                wcbelReloadProducts(response.product_ids);
            }
        },
        error: function () {

        }
    });
}

function wcbelHistoryFilter(filters = null) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_history_filter',
            nonce: WCBEL_DATA.ajax_nonce,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wcbel-history-items tbody').html(response.history_items);
                    jQuery('.wcbel-history-pagination-container').html(response.history_pagination);
                } else {
                    jQuery('.wcbel-history-items tbody').html("<td colspan='4'><span>" + wcbelTranslate.notFound + "</span></td>");
                }
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelHistoryChangePage(page = 1, filters = null) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_history_change_page',
            nonce: WCBEL_DATA.ajax_nonce,
            page: page,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wcbel-history-items tbody').html(response.history_items);
                    jQuery('.wcbel-history-pagination-container').html(response.history_pagination);
                } else {
                    jQuery('.wcbel-history-items tbody').html("<td colspan='4'><span>" + wcbelTranslate.notFound + "</span></td>");
                }
                jQuery('.wcbel-history-pagination-loading').hide();
            } else {
                jQuery('.wcbel-history-pagination-loading').hide();
            }
        },
        error: function () {
            jQuery('.wcbel-history-pagination-loading').hide();
        }
    });
}

function wcbelGetCurrentPage() {
    return jQuery('.wcbel-top-nav-filters .wcbel-top-nav-filters-paginate button.current').attr('data-index');
}

function wcbelGetDefaultFilterProfileProducts() {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_default_filter_profile_products',
            nonce: WCBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                wcbelSetProductsList(response);
                setTimeout(function () {
                    setFilterValues(response.filter_data);
                }, 600);
            }
        },
        error: function () { }
    });
}

function setFilterValues(filterData) {
    if (filterData) {
        jQuery('.wcbel-bulk-edit-status-filter-item').removeClass('active');
        jQuery.each(filterData, function (key, values) {
            switch (key) {
                case 'product_status':
                    if (values) {
                        jQuery('.wcbel-bulk-edit-status-filter-item[data-status="' + values + '"]').addClass('active');
                        jQuery('#wcbel-filter-form-product-status').val(values).change();
                    } else {
                        jQuery('.wcbel-bulk-edit-status-filter-item[data-status="all"]').addClass('active');
                    }
                    break;
                case 'product_taxonomies':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.taxonomy + '"][data-type="taxonomy"]').find('[data-field="operator"]').val(val.operator).change();
                        }
                        if (val.value) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.taxonomy + '"][data-type="taxonomy"]').find('[data-field="value"]').val(val.value).change();
                        }
                    });
                    break;
                case 'product_attributes':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.key + '"][data-type="attribute"]').find('[data-field="operator"]').val(val.operator).change();
                        }
                        if (val.value) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.key + '"][data-type="attribute"]').find('[data-field="value"]').val(val.value).change();
                        }
                    });
                    break;
                case 'product_custom_fields':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="operator"]').val(val.operator).change();
                        }
                        if (jQuery.isArray(val.value)) {
                            if (val.value[0]) {
                                jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="from"]').val(val.value[0]);
                            }
                            if (val.value[1]) {
                                jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="to"]').val(val.value[1]);
                            }
                        } else {
                            if (val.value) {
                                jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="value"]').val(val.value).change();
                            }
                        }
                    });
                    break;
                default:
                    if (values instanceof Object) {
                        if (values.operator) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + key + '"]').find('[data-field="operator"]').val(values.operator).change();
                        }
                        if (values.value) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + key + '"]').find('[data-field="value"]').val(values.value).change();
                        }
                        if (values.from) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + key + '"]').find('[data-field="from"]').val(values.from).change();
                        }
                        if (values.to) {
                            jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + key + '"]').find('[data-field="to"]').val(values.to);
                        }
                    } else {
                        jQuery('#wcbel-float-side-modal-filter .wcbel-form-group[data-name="' + key + '"]').find('[data-field="value"]').val(values);
                    }
                    break;
            }
        });
        wcbelCheckFilterFormChanges();
        wcbelFilterFormCheckAttributes();
        wcbelCheckResetFilterButton();
    }
}

function checkedCurrentCategory(id, categoryIds) {
    categoryIds.forEach(function (value) {
        jQuery(id + ' input[value="' + value + '"]').prop('checked', 'checked');
    });
}

function wcbelSaveFilterPreset(data, presetName) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_save_filter_preset',
            nonce: WCBEL_DATA.ajax_nonce,
            filter_data: data,
            preset_name: presetName
        },
        success: function (response) {
            if (response.success) {
                wcbelLoadingSuccess();
                jQuery('#wcbel-float-side-modal-filter-profiles').find('tbody').append(response.new_item);
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelResetBulkEditForm() {
    jQuery('#wcbel-float-side-modal-bulk-edit input').val('').change();
    jQuery('#wcbel-float-side-modal-bulk-edit select').prop('selectedIndex', 0).change();
    jQuery('#wcbel-float-side-modal-bulk-edit .wcbel-select2').val('').trigger('change');
    jQuery('#wcbel-float-side-modal-bulk-edit .wcbel-bulk-edit-form-item-gallery').html('');
    jQuery('#wcbel-float-side-modal-bulk-edit .wcbel-bulk-edit-form-item-gallery-preview').html('');
    jQuery("nav.wcbel-tabs-navbar li a").removeClass("wcbel-tab-changed");
}

function wcbelGetProSearchData() {
    let data;
    let taxonomies = [];
    let attributes = [];
    let custom_fields = [];
    let woo_multi_currency_regular = [];
    let woo_multi_currency_sale = [];

    jQuery('#wcbel-float-side-modal-filter .wcbel-sub-tab-content[data-content="multi_currency"] .wcbel-form-group').each(function () {
        if (jQuery(this).find('input[data-field="from"]').val() !== null || jQuery(this).find('input[data-field="to"]').val() !== null) {
            if (jQuery(this).find('input[data-field="from"]').attr('data-field-type') == 'regular') {
                woo_multi_currency_regular.push({
                    name: jQuery(this).find('input[data-field="from"]').attr('data-field-name'),
                    from: jQuery(this).find('input[data-field="from"]').val(),
                    to: jQuery(this).find('input[data-field="to"]').val()
                });
            } else {
                woo_multi_currency_sale.push({
                    name: jQuery(this).find('input[data-field="from"]').attr('data-field-name'),
                    from: jQuery(this).find('input[data-field="from"]').val(),
                    to: jQuery(this).find('input[data-field="to"]').val()
                });
            }
        }
    });

    jQuery('#wcbel-float-side-modal-filter .wcbel-tab-content-item[data-content="filter_categories_tags_taxonomies"] .wcbel-form-group[data-type="taxonomy"]').each(function () {
        if (jQuery.isArray(jQuery(this).find('select[data-field="value"]').val()) && jQuery(this).find('select[data-field="value"]').val().length > 0) {
            taxonomies.push({
                taxonomy: jQuery(this).attr('data-name'),
                operator: jQuery(this).find('select[data-field="operator"]').val(),
                value: jQuery(this).find('select[data-field="value"]').val()
            });
        }
    });

    jQuery('#wcbel-float-side-modal-filter .wcbel-tab-content-item[data-content="filter_categories_tags_taxonomies"] .wcbel-form-group[data-type="attribute"]').each(function () {
        if (jQuery.isArray(jQuery(this).find('select[data-field="value"]').val()) && jQuery(this).find('select[data-field="value"]').val().length > 0) {
            attributes.push({
                key: jQuery(this).attr('data-name'),
                value: jQuery(this).find('select[data-field="value"]').val(),
                operator: jQuery(this).find('select[data-field="operator"]').val(),
            });
        }
    });

    jQuery('#wcbel-float-side-modal-filter .wcbel-tab-content-item[data-content="filter_custom_fields"] .wcbel-form-group').each(function () {
        let fieldName = jQuery(this).attr('data-name');
        if (jQuery(this).find('input').length === 2) {
            let dataFieldType;
            let values = jQuery(this).find('input').map(function () {
                dataFieldType = jQuery(this).attr('data-field-type');
                if (jQuery(this).val()) {
                    return jQuery(this).val()
                }
            }).get();
            custom_fields.push({
                type: 'from-to-' + dataFieldType,
                taxonomy: fieldName,
                value: values
            });
        } else if (jQuery(this).find('input[data-field="value"]').length === 1) {
            if (jQuery(this).find('input[data-field="value"]').val() != null) {
                custom_fields.push({
                    type: 'text',
                    taxonomy: fieldName,
                    operator: jQuery(this).find('select[data-field="operator"]').val(),
                    value: jQuery(this).find('input[data-field="value"]').val()
                });
            }
        } else if (jQuery(this).find('select[data-field="value"]').length === 1) {
            if (jQuery(this).find('select[data-field="value"]').val() != null) {
                custom_fields.push({
                    type: 'select',
                    taxonomy: fieldName,
                    value: jQuery(this).find('select[data-field="value"]').val()
                });
            }
        }
    });

    data = {
        search_type: 'pro_search',
        product_ids: {
            operator: jQuery('#wcbel-filter-form-product-ids-operator').val(),
            parent_only: (jQuery('#wcbel-filter-form-product-ids-parent-only').prop('checked') === true) ? 'yes' : 'no',
            value: jQuery('#wcbel-filter-form-product-ids').val(),
        },
        product_title: {
            operator: jQuery('#wcbel-filter-form-product-title-operator').val(),
            value: jQuery('#wcbel-filter-form-product-title').val()
        },
        product_content: {
            operator: jQuery('#wcbel-filter-form-product-content-operator').val(),
            value: jQuery('#wcbel-filter-form-product-content').val()
        },
        product_excerpt: {
            operator: jQuery('#wcbel-filter-form-product-excerpt-operator').val(),
            value: jQuery('#wcbel-filter-form-product-excerpt').val()
        },
        product_slug: {
            operator: jQuery('#wcbel-filter-form-product-slug-operator').val(),
            value: jQuery('#wcbel-filter-form-product-slug').val()
        },
        product_sku: {
            operator: jQuery('#wcbel-filter-form-product-sku-operator').val(),
            value: jQuery('#wcbel-filter-form-product-sku').val()
        },
        product_url: {
            operator: jQuery('#wcbel-filter-form-product-url-operator').val(),
            value: jQuery('#wcbel-filter-form-product-url').val()
        },
        product_taxonomies: taxonomies,
        product_attributes: attributes,
        product_custom_fields: custom_fields,
        _regular_price_wmcp: woo_multi_currency_regular,
        _sale_price_wmcp: woo_multi_currency_sale,
        product_regular_price: {
            from: jQuery('#wcbel-filter-form-product-regular-price-from').val(),
            to: jQuery('#wcbel-filter-form-product-regular-price-to').val()
        },
        product_sale_price: {
            from: jQuery('#wcbel-filter-form-product-sale-price-from').val(),
            to: jQuery('#wcbel-filter-form-product-sale-price-to').val()
        },
        product_width: {
            from: jQuery('#wcbel-filter-form-product-width-from').val(),
            to: jQuery('#wcbel-filter-form-product-width-to').val()
        },
        product_height: {
            from: jQuery('#wcbel-filter-form-product-height-from').val(),
            to: jQuery('#wcbel-filter-form-product-height-to').val()
        },
        product_length: {
            from: jQuery('#wcbel-filter-form-product-length-from').val(),
            to: jQuery('#wcbel-filter-form-product-length-to').val()
        },
        product_weight: {
            from: jQuery('#wcbel-filter-form-product-weight-from').val(),
            to: jQuery('#wcbel-filter-form-product-weight-to').val()
        },
        stock_quantity: {
            from: jQuery('#wcbel-filter-form-stock-quantity-from').val(),
            to: jQuery('#wcbel-filter-form-stock-quantity-to').val()
        },
        low_stock_amount: {
            from: jQuery('#wcbel-filter-form-low-stock-amount-from').val(),
            to: jQuery('#wcbel-filter-form-low-stock-amount-to').val()
        },
        manage_stock: {
            value: jQuery('#wcbel-filter-form-manage-stock').val()
        },
        product_menu_order: {
            from: jQuery('#wcbel-filter-form-product-menu-order-from').val(),
            to: jQuery('#wcbel-filter-form-product-menu-order-to').val()
        },
        date_created: {
            from: jQuery('#wcbel-filter-form-date-created-from').val(),
            to: jQuery('#wcbel-filter-form-date-created-to').val()
        },
        sale_price_date_from: {
            value: jQuery('#wcbel-filter-form-product-sale-price-date-from').val(),
        },
        sale_price_date_to: {
            value: jQuery('#wcbel-filter-form-product-sale-price-date-to').val()
        },
        product_type: jQuery('#wcbel-filter-form-product-type').val(),
        product_status: jQuery('#wcbel-filter-form-product-status').val(),
        stock_status: jQuery('#wcbel-filter-form-stock-status').val(),
        featured: jQuery('#wcbel-filter-form-featured').val(),
        downloadable: jQuery('#wcbel-filter-form-downloadable').val(),
        backorders: jQuery('#wcbel-filter-form-backorders').val(),
        sold_individually: jQuery('#wcbel-filter-form-sold-individually').val(),
        author: jQuery('#wcbel-filter-form-author').val(),
        catalog_visibility: jQuery('#wcbel-filter-form-visibility').val(),
        minimum_allowed_quantity: {
            from: jQuery('#wcbel-filter-form-minimum-quantity-from').val(),
            to: jQuery('#wcbel-filter-form-minimum-quantity-to').val(),
        },
        maximum_allowed_quantity: {
            from: jQuery('#wcbel-filter-form-maximum-quantity-from').val(),
            to: jQuery('#wcbel-filter-form-maximum-quantity-to').val(),
        },
        group_of_quantity: {
            from: jQuery('#wcbel-filter-form-group-of-quantity-from').val(),
            to: jQuery('#wcbel-filter-form-group-of-quantity-to').val(),
        },
        minmax_do_not_count: {
            value: jQuery('#wcbel-filter-form-do-not-count').val()
        },
        minmax_cart_exclude: {
            value: jQuery('#wcbel-filter-form-cart-exclude').val()
        },
        minmax_category_group_of_exclude: {
            value: jQuery('#wcbel-filter-form-category-exclude').val()
        },
        _ywmmq_product_minimum_quantity: {
            from: jQuery('#wcbel-filter-form-minimum-quantity-restriction-from').val(),
            to: jQuery('#wcbel-filter-form-minimum-quantity-restriction-to').val(),
        },
        _ywmmq_product_maximum_quantity: {
            from: jQuery('#wcbel-filter-form-maximum-quantity-restriction-from').val(),
            to: jQuery('#be-filter-form-maximum-quantity-restriction-to').val(),
        },
        _ywmmq_product_step_quantity: {
            from: jQuery('#wcbel-filter-form-product-step-quantity-from').val(),
            to: jQuery('#wcbel-filter-form-product-step-quantity-to').val(),
        },
        _ywmmq_product_exclusion: {
            value: jQuery('#wcbel-filter-form-exclude-product').val()
        },
        _ywmmq_product_quantity_limit_override: {
            value: jQuery('#wcbel-filter-form-override-product').val()
        },
        _ywmmq_product_quantity_limit_variations_override: {
            value: jQuery('#wcbel-filter-form-enable-variation').val()
        },
        _product_commission: {
            from: jQuery('#wcbel-filter-form-yith-product-commission-from').val(),
            to: jQuery('#wcbel-filter-form-yith-product-commission-to').val()
        },
        yith_shop_vendor: {
            operator: jQuery('#wcbel-filter-form-yith-vendor-operator').val(),
            value: jQuery('#wcbel-filter-form-yith-vendor').val()
        },
        _wcpv_product_commission: {
            from: jQuery('#wcbel-filter-form-wc-product-commission-from').val(),
            to: jQuery('#wcbel-filter-form-wc-product-commission-to').val()
        },
        _wcpv_product_taxes: {
            value: jQuery('#wcbel-filter-form-wc-product-taxes').val(),
        },
        _wcpv_product_pass_shipping: {
            value: jQuery('#wcbel-filter-form-wc-pass-shipping').val(),
        },
        wcpv_product_vendors: {
            operator: jQuery('#wcbel-filter-form-wc-vendor-operator').val(),
            value: jQuery('#wcbel-filter-form-wc-vendor').val()
        },
        yith_cog_cost: {
            from: jQuery('#wcbel-filter-form-yith-cost-of-goods-from').val(),
            to: jQuery('#wcbel-filter-form-yith-cost-of-goods-to').val()
        },
        _wc_cog_cost: {
            from: jQuery('#wcbel-filter-form-wc-cost-of-goods-from').val(),
            to: jQuery('#wcbel-filter-form-wc-cost-of-goods-to').val()
        },

        // yith product badge
        "_yith_wcbm_product_meta_-_id_badge": {
            operator: jQuery("#wcbel-filter-form-yith-product-badge-id-operator").val(),
            value: jQuery("#wcbel-filter-form-yith-product-badge-id").val()
        },
        "_yith_wcbm_product_meta_-_start_date": {
            from: jQuery("#wcbel-filter-form-yith-product-badge-start-date-from").val(),
            to: jQuery("#wcbel-filter-form-yith-product-badge-start-date-to").val()
        },
        "_yith_wcbm_product_meta_-_end_date": {
            from: jQuery("#wcbel-filter-form-yith-product-badge-end-date-from").val(),
            to: jQuery("#wcbel-filter-form-yith-product-badge-end-date-to").val()
        }
    };
    return data;
}

function wcbelProductEdit(productIds, productData) {
    wcbelLoadingStart();
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_product_edit',
            nonce: WCBEL_DATA.ajax_nonce,
            product_ids: productIds,
            product_data: productData,
            filter_data: wcbelGetCurrentFilterData(),
            current_page: wcbelGetCurrentPage(),
        },
        success: function (response) {
            if (response.success) {
                wcbelReloadRows(response.products, response.product_statuses);
                wcbelSetStatusFilter(response.status_filters);
                wcbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbel-history-items tbody').html(response.history_items);
                jQuery('.wcbel-history-pagination-container').html(response.history_pagination);
                wcbelReInitDatePicker();
                wcbelReInitColorPicker();
                let wcbelTextEditors = jQuery('input[name="wcbel-editors[]"]');
                if (wcbelTextEditors.length > 0) {
                    wcbelTextEditors.each(function () {
                        tinymce.execCommand('mceRemoveEditor', false, jQuery(this).val());
                        tinymce.execCommand('mceAddEditor', false, jQuery(this).val());
                    })
                }
                wcbelLoadingSuccess();
            } else {
                wcbelLoadingError();
            }
        },
        error: function () {
            wcbelLoadingError();
        }
    });
}

function wcbelReloadRows(products, statuses) {
    let currentStatus = (jQuery('#wcbel-filter-form-product-status').val());
    jQuery('tr').removeClass('wcbel-item-edited').find('.wcbel-check-item').prop('checked', false);
    if (Object.keys(products).length > 0) {
        jQuery.each(products, function (key, val) {
            if (statuses[key] === currentStatus || (!currentStatus && statuses[key] !== 'trash')) {
                jQuery('#wcbel-items-list').find('tr[data-item-id="' + key + '"]').replaceWith(val);
                jQuery('tr[data-item-id="' + key + '"]').addClass('wcbel-item-edited').find('.wcbel-check-item').prop('checked', true);
            } else {
                jQuery('#wcbel-items-list').find('tr[data-item-id="' + key + '"]').remove();
            }
        });
        wcbelShowSelectionTools();
    } else {
        wcbelHideSelectionTools();
    }

    wcbelCheckShowVariations();
}

function wcbelGetProductVariations(productID) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_variations',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productID
        },
        success: function (response) {
            if (response.success) {
                jQuery('.wcbel-variation-bulk-edit-current-items').html(response.variations);
                jQuery('#wcbel-variation-bulk-edit-attributes-added').html(response.attributes);
                jQuery('#wcbel-variation-bulk-edit-attributes').val(response.selected_items).change();
                jQuery('#wcbel-variation-single-delete-items').html(response.variations_single_delete);
                jQuery('.wcbel-variation-bulk-edit-individual-items').html(response.individual);
                jQuery('#wcbel-variation-bulk-edit-do-bulk-variations').prop('disabled', false);
                jQuery('#wcbel-variation-bulk-edit-manual-add').prop('disabled', false);
                jQuery('#wcbel-variation-bulk-edit-generate').prop('disabled', false);
                jQuery('.wcbel-select2-ajax').select2();
            } else {
                jQuery('.wcbel-variation-bulk-edit-current-items').html('');
                jQuery('#wcbel-variation-bulk-edit-attributes-added').html('');
                jQuery('#wcbel-variation-bulk-edit-attributes').val('').change();
                jQuery('#wcbel-variation-single-delete-items').html('');
                jQuery('.wcbel-variation-bulk-edit-individual-items').html('');
                jQuery('#wcbel-variation-bulk-edit-manual-add').attr('disabled', 'disabled');
                jQuery('#wcbel-variation-bulk-edit-generate').attr('disabled', 'disabled');
                jQuery('#wcbel-variation-bulk-edit-do-bulk-variations').attr('disabled', 'disabled');
            }
            jQuery('.wcbel-variation-bulk-edit-loading').hide();
        },
        error: function () {
            jQuery('.wcbel-variation-bulk-edit-current-items').html('');
            jQuery('#wcbel-variation-bulk-edit-attributes-added').html('');
            jQuery('#wcbel-variation-bulk-edit-attributes').val('').change();
            jQuery('#wcbel-variation-single-delete-items').html('');
            jQuery('.wcbel-variation-bulk-edit-individual-items').html('');
            jQuery('#wcbel-variation-bulk-edit-manual-add').attr('disabled', 'disabled');
            jQuery('#wcbel-variation-bulk-edit-generate').attr('disabled', 'disabled');
            jQuery('#wcbel-variation-bulk-edit-do-bulk-variations').attr('disabled', 'disabled');
            jQuery('.wcbel-variation-bulk-edit-loading').hide();
        }
    });
}

function wcbelGetTaxonomyParentSelectBox(taxonomy) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_taxonomy_parent_select_box',
            nonce: WCBEL_DATA.ajax_nonce,
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-new-product-taxonomy-parent').html(response.options);
            }
        },
        error: function () { }
    });
}

function getAttributeValues(name, target) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_attribute_values',
            nonce: WCBEL_DATA.ajax_nonce,
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
                jQuery('.wcbel-select2-ajax').select2();
            } else {

            }
        },
        error: function () {

        }
    });
}

function getAttributeValuesForDelete(name, target) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_attribute_values_for_delete',
            nonce: WCBEL_DATA.ajax_nonce,
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
            } else {

            }
        },
        error: function () {

        }
    });
}

function wcbelGetProductBadges(productId) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_badge_ids',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-modal-product-badge-items').val(response.badges).change();
            }
        },
        error: function () { }
    });
}

function wcbelGetProductIthemelandBadge(productId) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_product_ithemeland_badge',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productId
        },
        success: function (response) {
            if (response.success) {
                if (Object.keys(response.badge_fields).length > 0) {
                    jQuery.each(response.badge_fields, function (key) {
                        switch (key) {
                            case '_unique_label_exclude':
                                jQuery('[name="' + key + '"]').prop('checked', (response.badge_fields[key] === 'yes'));
                                break;
                            case '_unique_label_shape':
                                jQuery('[name="' + key + '"][value="' + response.badge_fields[key] + '"]').trigger('click');
                                break;
                            case '_unique_label_rotation_x':
                            case '_unique_label_rotation_y':
                            case '_unique_label_rotation_z':
                                jQuery('[name="' + key + '"]').val(response.badge_fields[key]).change().closest('span').find('.range-slider__value').text(response.badge_fields[key]);
                                break;
                            case '_unique_label_opacity':
                                jQuery('[name="' + key + '"]').val(response.badge_fields[key]).change().closest('p').find('.range-slider__value').text(response.badge_fields[key]);
                                break;
                            default:
                                jQuery('[name="' + key + '"]').val(response.badge_fields[key]).change();
                        }

                    });
                }
            }
        },
        error: function () { }
    });
}

function wcbelGetYikesCustomProductTabs(productId) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_yikes_custom_product_tabs',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-modal-yikes-custom-tabs').html(response.tabs_html);
                setTimeout(function () {
                    if (response.text_editor_ids) {
                        jQuery.each(response.text_editor_ids, function (key) {
                            tinymce.remove('#' + response.text_editor_ids[key]);
                            tinymce.execCommand('mceAddEditor', true, response.text_editor_ids[key]);
                        });
                    }
                }, 100);

                setTimeout(function () {
                    jQuery('.wcbel-yikes-override-tab').trigger('change');
                }, 250);
            }
        },
        error: function () { }
    });
}

function wcbelAddYikesSavedTab(tabId) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_add_yikes_saved_tab',
            nonce: WCBEL_DATA.ajax_nonce,
            tab_id: tabId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-modal-yikes-custom-tabs').append(response.tab_html);
                setTimeout(function () {
                    if (response.text_editor_id) {
                        tinymce.remove('#' + response.text_editor_id);
                        tinymce.execCommand('mceAddEditor', true, response.text_editor_id);
                    }
                }, 100);
                setTimeout(function () {
                    jQuery('.wcbel-yikes-override-tab').trigger('change');
                }, 250);
            }
        },
        error: function () { }
    });
}

function wcbelGetItWcRolePrices(productId) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_it_wc_role_prices',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbel-modal-it-wc-dynamic-pricing').find('input[data-type="value"]').each(function () {
                    if (response.prices[jQuery(this).attr('data-name')]) {
                        let amount = (response.prices[jQuery(this).attr('data-name')].price) ? response.prices[jQuery(this).attr('data-name')].price : response.prices[jQuery(this).attr('data-name')].amount;
                        jQuery(this).val(amount).change();
                    } else {
                        jQuery(this).val('').change();
                    }
                });
            }
        },
        error: function () { }
    });
}

function wcbelGetItWcDynamicPricingSelectedRoles(productId, field) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_it_wc_dynamic_pricing_selected_roles',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productId,
            field: field
        },
        success: function (response) {
            if (response.success) {
                if (jQuery.isArray(response.roles) && response.roles.length > 0) {
                    jQuery('#wcbel-modal-it-wc-dynamic-pricing-select-roles #wcbel-user-roles').val(response.roles).change();
                } else {
                    jQuery('#wcbel-modal-it-wc-dynamic-pricing-select-roles #wcbel-user-roles').val('').change();
                }
            }
        },
        error: function () { }
    });
}

function wcbelGetItWcDynamicPricingAllFields(productId) {
    jQuery.ajax({
        url: WCBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbel_get_it_wc_dynamic_pricing_all_fields',
            nonce: WCBEL_DATA.ajax_nonce,
            product_id: productId,
        },
        success: function (response) {
            if (response.success) {
                let element = jQuery('#wcbel-modal-it-wc-dynamic-pricing-all-fields');

                if (response.it_product_disable_discount == 'yes') {
                    element.find('input#wcbel-it-wc-dynamic-pricing-disable-discount').prop('checked', true);
                } else {
                    element.find('input#wcbel-it-wc-dynamic-pricing-disable-discount').prop('checked', false);
                }

                if (response.it_product_hide_price_unregistered == 'yes') {
                    element.find('input#wcbel-it-wc-dynamic-pricing-hide-price-unregistered').prop('checked', true);
                } else {
                    element.find('input#wcbel-it-wc-dynamic-pricing-hide-price-unregistered').prop('checked', false);
                }

                if (response.it_pricing_product_price_user_role) {
                    element.find('select#wcbel-select-roles-hide-price').val(response.it_pricing_product_price_user_role).change();
                }

                if (response.it_pricing_product_add_to_cart_user_role) {
                    element.find('select#wcbel-select-roles-hide-add-to-cart').val(response.it_pricing_product_add_to_cart_user_role).change();
                }

                if (response.it_pricing_product_hide_user_role) {
                    element.find('select#wcbel-select-roles-hide-product').val(response.it_pricing_product_hide_user_role).change();
                }

                if (response.pricing_rules_product.price_rule && (response.pricing_rules_product.price_rule instanceof Object) && Object.keys(response.pricing_rules_product.price_rule).length > 0) {
                    element.find('#wcbel-it-pricing-roles input[data-type="value"]').each(function () {
                        if (response.pricing_rules_product.price_rule[jQuery(this).attr('data-name')]) {
                            let amount = (response.pricing_rules_product.price_rule[jQuery(this).attr('data-name')].price) ? response.pricing_rules_product.price_rule[jQuery(this).attr('data-name')].price : response.pricing_rules_product.price_rule[jQuery(this).attr('data-name')].amount;
                            jQuery(this).val(amount).change();
                        } else {
                            jQuery(this).val('').change();
                        }
                    });
                }
            }
        },
        error: function () { }
    });
}

function wcbelCheckShowVariations() {
    if (jQuery("#wcbel-bulk-edit-show-variations").prop("checked") === true) {
        jQuery('tr[data-item-type="variation"]').show();
        wcbelShowVariationSelectionTools();
    } else {
        jQuery('tr[data-item-type="variation"]').hide();
        wcbelHideVariationSelectionTools();
    }
}

function wcbelFilterFormCheckAttributes() {
    let attributes = jQuery('.wcbel-tab-content-item[data-content="filter_categories_tags_taxonomies"] .wcbel-form-group[data-type="attribute"]');
    if (attributes.length > 0) {
        jQuery.each(attributes, function () {
            let valueField = jQuery(this).find('select[data-field="value"]');
            if (jQuery.isArray(valueField.val()) && valueField.val().length > 0) {
                jQuery('#wcbel-bulk-edit-show-variations').prop('checked', true).change();
            }
        })
    }
}