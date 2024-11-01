"use strict";

function iwbvelGetProductGalleryImages(productsId) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_gallery_images',
            nonce: IWBVEL_DATA.nonce,
            product_id: productsId,
        },
        success: function (response) {
            if (response.success && response.images !== '') {
                jQuery('#iwbvel-modal-gallery-items').html(response.images);
            }
        },
        error: function () { }
    })
}

function iwbvelGetProductsChecked() {
    let productIds = [];
    let productsChecked = jQuery("input.iwbvel-check-item:visible:checkbox:checked");
    if (productsChecked.length > 0) {
        productIds = productsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }
    return productIds;
}

function iwbvelReloadProducts(edited_ids = [], current_page = iwbvelGetCurrentPage()) {
    let data = iwbvelGetCurrentFilterData();
    iwbvelProductsFilter(data, 'pro_search', edited_ids, current_page);
}

function iwbvelProductsFilter(data, action, edited_ids = null, page = iwbvelGetCurrentPage()) {
    if (action === 'pagination') {
        iwbvelPaginationLoadingStart();
    } else {
        iwbvelLoadingStart();
    }

    if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
        iwbvelCloseFloatSideModal();
    }

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_products_filter',
            nonce: IWBVEL_DATA.nonce,
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                iwbvelSetProductsList(response, edited_ids)
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelClearFilterDataWithRedirect() {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_clear_filter_data',
            nonce: IWBVEL_DATA.nonce,
        },
        success: function (response) {
            window.location.search = '?page=iwbvel';
        },
        error: function () { }
    });
}

function iwbvelSetStatusFilter(statusFilters) {
    jQuery('.iwbvel-top-nav-status-filter').html(statusFilters);

    jQuery('.iwbvel-bulk-edit-status-filter-item').removeClass('active');
    let statusFilter = (jQuery('#iwbvel-filter-form-product-status').val() && jQuery('#iwbvel-filter-form-product-status').val() != '') ? jQuery('#iwbvel-filter-form-product-status').val() : 'all';
    if (jQuery.isArray(statusFilter)) {
        statusFilter.forEach(function (val) {
            jQuery('.iwbvel-bulk-edit-status-filter-item[data-status="' + val + '"]').addClass('active');
        });
    } else {
        let activeItem = jQuery('.iwbvel-bulk-edit-status-filter-item[data-status="' + statusFilter + '"]');
        activeItem.addClass('active');
        jQuery('.iwbvel-status-filter-selected-name').text(' - ' + activeItem.text())
    }
}

function iwbvelSetProductsList(response, edited_ids = null) {
    jQuery('#iwbvel-bulk-edit-select-all-variations').prop('checked', false);
    jQuery('#iwbvel-items-table').html(response.products_list);
    jQuery('.iwbvel-items-pagination').html(response.pagination);
    jQuery('.iwbvel-items-count').html(iwbvelGetTableCount(jQuery('#iwbvel-quick-per-page').val(), iwbvelGetCurrentPage(), response.products_count));
    iwbvelSetStatusFilter(response.status_filters);

    iwbvelReInitDatePicker();
    iwbvelReInitColorPicker();
    iwbvelCheckShowVariations();
    if (edited_ids && edited_ids.length > 0) {
        jQuery('tr').removeClass('iwbvel-item-edited');
        edited_ids.forEach(function (productID) {
            jQuery('tr[data-item-id="' + productID + '"]').addClass('iwbvel-item-edited');
            jQuery('input[value="' + productID + '"]').prop('checked', true);
        });
        iwbvelShowSelectionTools();
    } else {
        iwbvelHideSelectionTools();
    }
    if (jQuery('#iwbvel-bulk-edit-show-variations').prop('checked') === true) {
        jQuery('tr[data-product-type=variation]').show();
    }
    iwbvelSetTipsyTooltip();
}

function iwbvelGetProductData(productID) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_data',
            nonce: IWBVEL_DATA.nonce,
            product_id: productID
        },
        success: function (response) {
            if (response.success) {
                iwbvelSetProductDataBulkEditForm(response.product_data);
            } else {

            }
        },
        error: function () {

        }
    });
}

function iwbvelSetSelectedProducts(productIds) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_by_ids',
            nonce: IWBVEL_DATA.nonce,
            product_ids: productIds
        },
        success: function (response) {
            if (response.success && response.products instanceof Object && Object.keys(response.products).length > 0) {
                let productsField = jQuery('#iwbvel-select-products-value');
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

function iwbvelSetProductDataBulkEditForm(productData) {

    let reviews_allowed = (productData.reviews_allowed) ? 'yes' : 'no';
    let sold_individually = (productData.sold_individually) ? 'yes' : 'no';
    let manage_stock = (productData.manage_stock) ? 'yes' : 'no';
    let featured = (productData.featured) ? 'yes' : 'no';
    let virtual = (productData.virtual) ? 'yes' : 'no';
    let downloadable = (productData.downloadable) ? 'yes' : 'no';

    let attributes = jQuery('#iwbvel-float-side-modal-bulk-edit .iwbvel-bulk-edit-form-group[data-type=attribute]');
    if (attributes.length > 0) {
        let attribute_name = '';
        attributes.each(function () {
            attribute_name = jQuery(this).attr('data-name');
            if (productData.attribute[attribute_name]) {
                jQuery('#iwbvel-float-side-modal-bulk-edit .iwbvel-bulk-edit-form-group[data-type=attribute][data-name="' + attribute_name + '"]').find('select[data-field=value]').val(productData.attribute[attribute_name]).change();
            }
        });
    }

    let custom_fields = jQuery('#iwbvel-float-side-modal-bulk-edit .iwbvel-bulk-edit-form-group[data-type=custom_fields]');
    if (custom_fields.length > 0) {
        let taxonomy_name = '';
        custom_fields.each(function () {
            taxonomy_name = jQuery(this).attr('data-name');
            if (productData.meta_field[taxonomy_name]) {
                jQuery('#iwbvel-float-side-modal-bulk-edit .iwbvel-bulk-edit-form-group[data-type=custom_fields][data-name="' + taxonomy_name + '"]').find('[data-field=value]').val(productData.meta_field[taxonomy_name][0]).change();
            }
        });
    }

    jQuery('#iwbvel-bulk-edit-form-product-title').val(productData.post_title);
    jQuery('#iwbvel-bulk-edit-form-product-slug').val(productData.post_slug);
    jQuery('#iwbvel-bulk-edit-form-product-sku').val(productData.sku);
    jQuery('#iwbvel-bulk-edit-form-product-description').val(productData.post_content);
    jQuery('#iwbvel-bulk-edit-form-product-short-description').val(productData.post_excerpt);
    jQuery('#iwbvel-bulk-edit-form-product-purchase-note').val(productData.purchase_note);
    jQuery('#iwbvel-bulk-edit-form-product-menu-order').val(productData.menu_order);
    jQuery('#iwbvel-bulk-edit-form-product-sold-individually').val(sold_individually).change();
    jQuery('#iwbvel-bulk-edit-form-product-enable-reviews').val(reviews_allowed).change();
    jQuery('#iwbvel-bulk-edit-form-product-product-status').val(productData.post_status).change();
    jQuery('#iwbvel-bulk-edit-form-product-catalog-visibility').val(productData.catalog_visibility).change();
    jQuery('#iwbvel-bulk-edit-form-product-date-created').val(productData.post_date);
    jQuery('#iwbvel-bulk-edit-form-product-author').val(productData.post_author).change();
    jQuery('#iwbvel-bulk-edit-form-categories').val(productData.product_cat).change();
    jQuery('#iwbvel-bulk-edit-form-tags').val(productData.product_tag).change();
    jQuery('#iwbvel-bulk-edit-form-regular-price').val(productData.regular_price);
    jQuery('#iwbvel-bulk-edit-form-sale-price').val(productData.sale_price);
    jQuery('#iwbvel-bulk-edit-form-sale-date-from').val(productData.date_on_sale_from);
    jQuery('#iwbvel-bulk-edit-form-sale-date-to').val(productData.date_on_sale_to);
    jQuery('#iwbvel-bulk-edit-form-tax-status').val(productData.tax_status).change();
    jQuery('#iwbvel-bulk-edit-form-tax-class').val(productData.tax_class).change();
    jQuery('#iwbvel-bulk-edit-form-shipping-class').val(productData.shipping_class).change();
    jQuery('#iwbvel-bulk-edit-form-width').val(productData.width);
    jQuery('#iwbvel-bulk-edit-form-height').val(productData.height);
    jQuery('#iwbvel-bulk-edit-form-length').val(productData.length);
    jQuery('#iwbvel-bulk-edit-form-weight').val(productData.weight);
    jQuery('#iwbvel-bulk-edit-form-manage-stock').val(manage_stock).change();
    jQuery('#iwbvel-bulk-edit-form-stock-status').val(productData.stock_status).change();
    jQuery('#iwbvel-bulk-edit-form-stock-quantity').val(productData.stock_quantity);
    jQuery('#iwbvel-bulk-edit-form-backorders').val(productData.backorders).change();
    jQuery('#iwbvel-bulk-edit-form-product-type').val(productData.product_type).change();
    jQuery('#iwbvel-bulk-edit-form-featured').val(featured).change();
    jQuery('#iwbvel-bulk-edit-form-virtual').val(virtual).change();
    jQuery('#iwbvel-bulk-edit-form-downloadable').val(downloadable).change();
    jQuery('#iwbvel-bulk-edit-form-download-limit').val(productData.download_limit);
    jQuery('#iwbvel-bulk-edit-form-download-expiry').val(productData.download_expiry).change();
    jQuery('#iwbvel-bulk-edit-form-product-url').val(productData.meta_field._product_url);
    jQuery('#iwbvel-bulk-edit-form-button-text').val(productData.meta_field._button_text);
    jQuery('#iwbvel-bulk-edit-form-upsells').val(productData.upsell_ids).change();
    jQuery('#iwbvel-bulk-edit-form-cross-sells').val(productData.cross_sell_ids).change();
}

function iwbvelDeleteProduct(productIDs, deleteType) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_delete_products',
            nonce: IWBVEL_DATA.nonce,
            product_ids: productIDs,
            delete_type: deleteType,
            filter_data: iwbvelGetCurrentFilterData(),
        },
        success: function (response) {
            if (response.success) {
                iwbvelReloadProducts();
                iwbvelHideSelectionTools();
                iwbvelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.iwbvel-history-items tbody').html(response.history_items);
                jQuery('.iwbvel-history-pagination-container').html(response.history_pagination);
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelRestoreProduct(productIDs) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_untrash_products',
            nonce: IWBVEL_DATA.nonce,
            product_ids: productIDs,
        },
        success: function (response) {
            if (response.success) {
                iwbvelReloadProducts();
                iwbvelHideSelectionTools();
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelEmptyTrash() {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_empty_trash',
            nonce: IWBVEL_DATA.nonce,
        },
        success: function (response) {
            if (response.success) {
                iwbvelReloadProducts();
                iwbvelHideSelectionTools();
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelDuplicateProduct(productIDs, duplicateNumber) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_duplicate_product',
            nonce: IWBVEL_DATA.nonce,
            product_ids: productIDs,
            duplicate_number: duplicateNumber
        },
        success: function (response) {
            if (response.success) {
                iwbvelReloadProducts([], iwbvelGetCurrentPage());
                iwbvelCloseModal();
                iwbvelHideSelectionTools();
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelCreateNewProduct(count = 1) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_create_new_product',
            nonce: IWBVEL_DATA.nonce,
            count: count
        },
        success: function (response) {
            if (response.success) {
                iwbvelReloadProducts([], 1);
                iwbvelCloseModal();
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelHideVariationSelectionTools() {
    jQuery('#iwbvel-bulk-edit-select-all-variations-tools').hide();
}

function iwbvelShowVariationSelectionTools() {
    jQuery('#iwbvel-bulk-edit-select-all-variations-tools').show();
}

function iwbvelSaveColumnProfile(presetKey, items, type) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_save_column_profile',
            nonce: IWBVEL_DATA.nonce,
            preset_key: presetKey,
            items: items,
            type: type
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                location.href = location.href.replace(location.hash, "");
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelLoadFilterProfile(presetKey) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_load_filter_profile',
            nonce: IWBVEL_DATA.nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                iwbvelResetFilterForm();
                iwbvelLoadingSuccess();
                iwbvelSetProductsList(response);
                iwbvelCloseModal();

                setTimeout(function () {
                    setFilterValues(response.filter_data);
                }, 500);
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelDeleteFilterProfile(presetKey) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_delete_filter_profile',
            nonce: IWBVEL_DATA.nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelFilterProfileChangeUseAlways(presetKey) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_filter_profile_change_use_always',
            nonce: IWBVEL_DATA.nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
            } else {
                iwbvelLoadingError()
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelGetCurrentFilterData() {
    return (jQuery('#iwbvel-quick-search-text').val()) ? iwbvelGetQuickSearchData() : iwbvelGetProSearchData()
}

function iwbvelResetQuickSearchForm() {
    jQuery('.iwbvel-top-nav-filters-search input').val('');
    jQuery('.iwbvel-top-nav-filters-search select').prop('selectedIndex', 0);
    jQuery('#iwbvel-quick-search-reset').hide();
}

function iwbvelResetFilterForm() {
    jQuery('#iwbvel-float-side-modal-filter input').val('').change();
    jQuery('#iwbvel-float-side-modal-filter textarea').val('').change();
    jQuery('#iwbvel-float-side-modal-filter select').prop('selectedIndex', 0).trigger('change');
    jQuery('#iwbvel-float-side-modal-filter .iwbvel-select2').val(null).trigger('change');
    jQuery('.iwbvel-bulk-edit-status-filter-item').removeClass('active');
}

function iwbvelResetFilters() {
    iwbvelResetFilterForm();
    iwbvelResetQuickSearchForm();
    jQuery(".iwbvel-filter-profiles-items tr").removeClass("iwbvel-filter-profile-loaded");
    jQuery('input.iwbvel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
    jQuery("#iwbvel-bulk-edit-reset-filter").hide();
    jQuery('#iwbvel-bulk-edit-reset-filter').hide();

    jQuery('.iwbvel-reset-filter-form').closest('li').hide();

    setTimeout(function () {
        if (window.location.search !== '?page=iwbvel') {
            iwbvelClearFilterDataWithRedirect()
        } else {
            let data = iwbvelGetCurrentFilterData();
            iwbvelFilterProfileChangeUseAlways("default");
            iwbvelProductsFilter(data, "pro_search");
        }
    }, 250);
}

function iwbvelChangeCountPerPage(countPerPage) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_change_count_per_page',
            nonce: IWBVEL_DATA.nonce,
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                iwbvelReloadProducts([], 1);
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelAddProductTaxonomy(taxonomyInfo, taxonomyName, taxonomy_id) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_add_product_taxonomy',
            nonce: IWBVEL_DATA.nonce,
            taxonomy_info: taxonomyInfo,
            taxonomy_name: taxonomyName,
        },
        success: function (response) {
            if (response.success) {
                taxonomy_id = (taxonomyInfo.modal_id) ? taxonomyInfo.modal_id : 'iwbvel-modal-taxonomy-' + taxonomyName + '-' + taxonomyInfo.product_id;
                jQuery('#' + taxonomy_id + ' .iwbvel-product-items-list').html(response.taxonomy_items);
                iwbvelLoadingSuccess();
                iwbvelCloseModal()
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelAddProductAttribute(attributeInfo, attributeName) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_add_product_attribute',
            nonce: IWBVEL_DATA.nonce,
            attribute_info: attributeInfo,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-modal-attribute-' + attributeName + '-' + attributeInfo.product_id + ' .iwbvel-product-items-list ul').html(response.attribute_items);
                iwbvelLoadingSuccess();
                iwbvelCloseModal()
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelAddNewFileItem() {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_add_new_file_item',
            nonce: IWBVEL_DATA.nonce,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-modal-select-files .iwbvel-inline-select-files').prepend(response.file_item);
                iwbvelSetTipsyTooltip();
            }
        },
        error: function () {

        }
    });
}

function iwbvelGetProductFiles(productID) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_files',
            nonce: IWBVEL_DATA.nonce,
            product_id: productID,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-modal-select-files .iwbvel-inline-select-files').html(response.files);
                iwbvelSetTipsyTooltip();
            } else {
                jQuery('#iwbvel-modal-select-files .iwbvel-inline-select-files').html('');
            }
        },
        error: function () {
            jQuery('#iwbvel-modal-select-files .iwbvel-inline-select-files').html('');
        }
    });
}

function changedTabs(item) {
    let change = false;

    let tab = jQuery('nav.iwbvel-tabs-navbar a[data-content="' + item.closest('.iwbvel-tab-content-item').attr('data-content') + '"]');
    item.closest('.iwbvel-tab-content-item').find('[data-field=operator]').each(function () {
        if (jQuery(this).val() === 'text_remove_duplicate') {
            change = true;
            return false;
        }
    });
    item.closest('.iwbvel-tab-content-item').find('[data-field="value"]').each(function () {
        if (jQuery(this).val() && jQuery(this).val() != '') {
            change = true;
            return false;
        }
    });

    if (change === true) {
        tab.addClass('iwbvel-tab-changed');
    } else {
        tab.removeClass('iwbvel-tab-changed');
    }
}

function iwbvelCheckResetFilterButton() {
    if (jQuery('#iwbvel-bulk-edit-filter-tabs-contents [data-field="value"]').length > 0) {
        jQuery('#iwbvel-bulk-edit-filter-tabs-contents [data-field="value"]').each(function () {
            if (jQuery(this).val() != '') {
                jQuery('.iwbvel-reset-filter-form').closest('li').show();
                return true;
            }
        });
    }
}

function iwbvelGetQuickSearchData() {
    return {
        search_type: 'quick_search',
        quick_search_text: jQuery('#iwbvel-quick-search-text').val(),
        quick_search_field: jQuery('#iwbvel-quick-search-field').val(),
        quick_search_operator: jQuery('#iwbvel-quick-search-operator').val(),
    };
}

function iwbvelSortByColumn(columnName, sortType) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_sort_by_column',
            nonce: IWBVEL_DATA.nonce,
            filter_data: iwbvelGetCurrentFilterData(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                iwbvelSetProductsList(response)
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelColumnManagerFieldsGetForEdit(presetKey) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_column_manager_get_fields_for_edit',
            nonce: IWBVEL_DATA.nonce,
            preset_key: presetKey
        },
        success: function (response) {
            jQuery('#iwbvel-modal-column-manager-edit-preset .iwbvel-box-loading').hide();
            jQuery('.iwbvel-column-manager-added-fields[data-action=edit] .items').html(response.html);
            setTimeout(function () {
                iwbvelSetColorPickerTitle();
            }, 250);
            jQuery('.iwbvel-column-manager-available-fields[data-action=edit] li').each(function () {
                if (jQuery.inArray(jQuery(this).attr('data-name'), response.fields.split(',')) !== -1) {
                    jQuery(this).attr('data-added', 'true').hide();
                } else {
                    jQuery(this).attr('data-added', 'false').show();
                }
            });
            jQuery('.iwbvel-color-picker').wpColorPicker();
        },
    })
}

function iwbvelAddMetaKeysByProductID(productID) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'iwbvel_add_meta_keys_by_product_id',
            nonce: IWBVEL_DATA.nonce,
            product_id: productID,
        },
        success: function (response) {
            jQuery('#iwbvel-meta-fields-items').append(response);
            iwbvelLoadingSuccess();
        },
        error: function () {
            iwbvelLoadingError();
        }
    })
}

function iwbvelHistoryChangePage(page = 1, filters = null) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_history_change_page',
            nonce: IWBVEL_DATA.nonce,
            page: page,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.iwbvel-history-items tbody').html(response.history_items);
                    jQuery('.iwbvel-history-pagination-container').html(response.history_pagination);
                } else {
                    jQuery('.iwbvel-history-items tbody').html("<td colspan='4'><span>" + iwbvelTranslate.notFound + "</span></td>");
                }
                jQuery('.iwbvel-history-pagination-loading').hide();
            } else {
                jQuery('.iwbvel-history-pagination-loading').hide();
            }
        },
        error: function () {
            jQuery('.iwbvel-history-pagination-loading').hide();
        }
    });
}

function iwbvelGetCurrentPage() {
    return jQuery('.iwbvel-top-nav-filters .iwbvel-top-nav-filters-paginate button.current').attr('data-index');
}

function iwbvelGetDefaultFilterProfileProducts() {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_default_filter_profile_products',
            nonce: IWBVEL_DATA.nonce,
        },
        success: function (response) {
            if (response.success) {
                iwbvelSetProductsList(response);
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
        jQuery('.iwbvel-bulk-edit-status-filter-item').removeClass('active');
        jQuery.each(filterData, function (key, values) {
            switch (key) {
                case 'product_status':
                    if (values) {
                        jQuery('.iwbvel-bulk-edit-status-filter-item[data-status="' + values + '"]').addClass('active');
                        jQuery('#iwbvel-filter-form-product-status').val(values).change();
                    } else {
                        jQuery('.iwbvel-bulk-edit-status-filter-item[data-status="all"]').addClass('active');
                    }
                    break;
                case 'product_taxonomies':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.taxonomy + '"][data-type="taxonomy"]').find('[data-field="operator"]').val(val.operator).change();
                        }
                        if (val.value) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.taxonomy + '"][data-type="taxonomy"]').find('[data-field="value"]').val(val.value).change();
                        }
                    });
                    break;
                case 'product_attributes':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.key + '"][data-type="attribute"]').find('[data-field="operator"]').val(val.operator).change();
                        }
                        if (val.value) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.key + '"][data-type="attribute"]').find('[data-field="value"]').val(val.value).change();
                        }
                    });
                    break;
                case 'product_custom_fields':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="operator"]').val(val.operator).change();
                        }
                        if (jQuery.isArray(val.value)) {
                            if (val.value[0]) {
                                jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="from"]').val(val.value[0]);
                            }
                            if (val.value[1]) {
                                jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="to"]').val(val.value[1]);
                            }
                        } else {
                            if (val.value) {
                                jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + val.taxonomy + '"]').find('[data-field="value"]').val(val.value).change();
                            }
                        }
                    });
                    break;
                default:
                    if (values instanceof Object) {
                        if (values.operator) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + key + '"]').find('[data-field="operator"]').val(values.operator).change();
                        }
                        if (values.value) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + key + '"]').find('[data-field="value"]').val(values.value).change();
                        }
                        if (values.from) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + key + '"]').find('[data-field="from"]').val(values.from).change();
                        }
                        if (values.to) {
                            jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + key + '"]').find('[data-field="to"]').val(values.to);
                        }
                    } else {
                        jQuery('#iwbvel-float-side-modal-filter .iwbvel-form-group[data-name="' + key + '"]').find('[data-field="value"]').val(values);
                    }
                    break;
            }
        });
        iwbvelCheckFilterFormChanges();
        iwbvelFilterFormCheckAttributes();
        iwbvelCheckResetFilterButton();
    }
}

function checkedCurrentCategory(id, categoryIds) {
    categoryIds.forEach(function (value) {
        jQuery(id + ' input[value="' + value + '"]').prop('checked', 'checked');
    });
}

function iwbvelSaveFilterPreset(data, presetName) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_save_filter_preset',
            nonce: IWBVEL_DATA.nonce,
            filter_data: data,
            preset_name: presetName
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                jQuery('#iwbvel-float-side-modal-filter-profiles').find('tbody').append(response.new_item);
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelResetBulkEditForm() {
    jQuery('#iwbvel-float-side-modal-bulk-edit input').val('').change();
    jQuery('#iwbvel-float-side-modal-bulk-edit select').prop('selectedIndex', 0).change();
    jQuery('#iwbvel-float-side-modal-bulk-edit .iwbvel-select2').val('').trigger('change');
    jQuery('#iwbvel-float-side-modal-bulk-edit .iwbvel-bulk-edit-form-item-gallery').html('');
    jQuery('#iwbvel-float-side-modal-bulk-edit .iwbvel-bulk-edit-form-item-gallery-preview').html('');
    jQuery("nav.iwbvel-tabs-navbar li a").removeClass("iwbvel-tab-changed");
}

function iwbvelGetProSearchData() {
    let data;
    let taxonomies = [];
    let attributes = [];
    let custom_fields = [];
    let woo_multi_currency_regular = [];
    let woo_multi_currency_sale = [];

    jQuery('#iwbvel-float-side-modal-filter .iwbvel-sub-tab-content[data-content="multi_currency"] .iwbvel-form-group').each(function () {
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

    jQuery('#iwbvel-float-side-modal-filter .iwbvel-tab-content-item[data-content="filter_categories_tags_taxonomies"] .iwbvel-form-group[data-type="taxonomy"]').each(function () {
        if (jQuery.isArray(jQuery(this).find('select[data-field="value"]').val()) && jQuery(this).find('select[data-field="value"]').val().length > 0) {
            taxonomies.push({
                taxonomy: jQuery(this).attr('data-name'),
                operator: jQuery(this).find('select[data-field="operator"]').val(),
                value: jQuery(this).find('select[data-field="value"]').val()
            });
        }
    });

    jQuery('#iwbvel-float-side-modal-filter .iwbvel-tab-content-item[data-content="filter_categories_tags_taxonomies"] .iwbvel-form-group[data-type="attribute"]').each(function () {
        if (jQuery.isArray(jQuery(this).find('select[data-field="value"]').val()) && jQuery(this).find('select[data-field="value"]').val().length > 0) {
            attributes.push({
                key: jQuery(this).attr('data-name'),
                value: jQuery(this).find('select[data-field="value"]').val(),
                operator: jQuery(this).find('select[data-field="operator"]').val(),
            });
        }
    });

    jQuery('#iwbvel-float-side-modal-filter .iwbvel-tab-content-item[data-content="filter_custom_fields"] .iwbvel-form-group').each(function () {
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
            operator: jQuery('#iwbvel-filter-form-product-ids-operator').val(),
            parent_only: (jQuery('#iwbvel-filter-form-product-ids-parent-only').prop('checked') === true) ? 'yes' : 'no',
            value: jQuery('#iwbvel-filter-form-product-ids').val(),
        },
        product_title: {
            operator: jQuery('#iwbvel-filter-form-product-title-operator').val(),
            value: jQuery('#iwbvel-filter-form-product-title').val()
        },
        product_content: {
            operator: jQuery('#iwbvel-filter-form-product-content-operator').val(),
            value: jQuery('#iwbvel-filter-form-product-content').val()
        },
        product_excerpt: {
            operator: jQuery('#iwbvel-filter-form-product-excerpt-operator').val(),
            value: jQuery('#iwbvel-filter-form-product-excerpt').val()
        },
        product_slug: {
            operator: jQuery('#iwbvel-filter-form-product-slug-operator').val(),
            value: jQuery('#iwbvel-filter-form-product-slug').val()
        },
        product_sku: {
            operator: jQuery('#iwbvel-filter-form-product-sku-operator').val(),
            value: jQuery('#iwbvel-filter-form-product-sku').val()
        },
        product_url: {
            operator: jQuery('#iwbvel-filter-form-product-url-operator').val(),
            value: jQuery('#iwbvel-filter-form-product-url').val()
        },
        product_taxonomies: taxonomies,
        product_attributes: attributes,
        product_custom_fields: custom_fields,
        _regular_price_wmcp: woo_multi_currency_regular,
        _sale_price_wmcp: woo_multi_currency_sale,
        product_regular_price: {
            from: jQuery('#iwbvel-filter-form-product-regular-price-from').val(),
            to: jQuery('#iwbvel-filter-form-product-regular-price-to').val()
        },
        product_sale_price: {
            from: jQuery('#iwbvel-filter-form-product-sale-price-from').val(),
            to: jQuery('#iwbvel-filter-form-product-sale-price-to').val()
        },
        product_width: {
            from: jQuery('#iwbvel-filter-form-product-width-from').val(),
            to: jQuery('#iwbvel-filter-form-product-width-to').val()
        },
        product_height: {
            from: jQuery('#iwbvel-filter-form-product-height-from').val(),
            to: jQuery('#iwbvel-filter-form-product-height-to').val()
        },
        product_length: {
            from: jQuery('#iwbvel-filter-form-product-length-from').val(),
            to: jQuery('#iwbvel-filter-form-product-length-to').val()
        },
        product_weight: {
            from: jQuery('#iwbvel-filter-form-product-weight-from').val(),
            to: jQuery('#iwbvel-filter-form-product-weight-to').val()
        },
        stock_quantity: {
            from: jQuery('#iwbvel-filter-form-stock-quantity-from').val(),
            to: jQuery('#iwbvel-filter-form-stock-quantity-to').val()
        },
        manage_stock: {
            value: jQuery('#iwbvel-filter-form-manage-stock').val()
        },
        product_menu_order: {
            from: jQuery('#iwbvel-filter-form-product-menu-order-from').val(),
            to: jQuery('#iwbvel-filter-form-product-menu-order-to').val()
        },
        date_created: {
            from: jQuery('#iwbvel-filter-form-date-created-from').val(),
            to: jQuery('#iwbvel-filter-form-date-created-to').val()
        },
        sale_price_date_from: {
            value: jQuery('#iwbvel-filter-form-product-sale-price-date-from').val(),
        },
        sale_price_date_to: {
            value: jQuery('#iwbvel-filter-form-product-sale-price-date-to').val()
        },
        product_type: jQuery('#iwbvel-filter-form-product-type').val(),
        product_status: jQuery('#iwbvel-filter-form-product-status').val(),
        stock_status: jQuery('#iwbvel-filter-form-stock-status').val(),
        featured: jQuery('#iwbvel-filter-form-featured').val(),
        downloadable: jQuery('#iwbvel-filter-form-downloadable').val(),
        backorders: jQuery('#iwbvel-filter-form-backorders').val(),
        sold_individually: jQuery('#iwbvel-filter-form-sold-individually').val(),
        author: jQuery('#iwbvel-filter-form-author').val(),
        catalog_visibility: jQuery('#iwbvel-filter-form-visibility').val(),
        minimum_allowed_quantity: {
            from: jQuery('#iwbvel-filter-form-minimum-quantity-from').val(),
            to: jQuery('#iwbvel-filter-form-minimum-quantity-to').val(),
        },
        maximum_allowed_quantity: {
            from: jQuery('#iwbvel-filter-form-maximum-quantity-from').val(),
            to: jQuery('#iwbvel-filter-form-maximum-quantity-to').val(),
        },
        group_of_quantity: {
            from: jQuery('#iwbvel-filter-form-group-of-quantity-from').val(),
            to: jQuery('#iwbvel-filter-form-group-of-quantity-to').val(),
        },
        minmax_do_not_count: {
            value: jQuery('#iwbvel-filter-form-do-not-count').val()
        },
        minmax_cart_exclude: {
            value: jQuery('#iwbvel-filter-form-cart-exclude').val()
        },
        minmax_category_group_of_exclude: {
            value: jQuery('#iwbvel-filter-form-category-exclude').val()
        },
        _ywmmq_product_minimum_quantity: {
            from: jQuery('#iwbvel-filter-form-minimum-quantity-restriction-from').val(),
            to: jQuery('#iwbvel-filter-form-minimum-quantity-restriction-to').val(),
        },
        _ywmmq_product_maximum_quantity: {
            from: jQuery('#iwbvel-filter-form-maximum-quantity-restriction-from').val(),
            to: jQuery('#be-filter-form-maximum-quantity-restriction-to').val(),
        },
        _ywmmq_product_step_quantity: {
            from: jQuery('#iwbvel-filter-form-product-step-quantity-from').val(),
            to: jQuery('#iwbvel-filter-form-product-step-quantity-to').val(),
        },
        _ywmmq_product_exclusion: {
            value: jQuery('#iwbvel-filter-form-exclude-product').val()
        },
        _ywmmq_product_quantity_limit_override: {
            value: jQuery('#iwbvel-filter-form-override-product').val()
        },
        _ywmmq_product_quantity_limit_variations_override: {
            value: jQuery('#iwbvel-filter-form-enable-variation').val()
        },
        _product_commission: {
            from: jQuery('#iwbvel-filter-form-yith-product-commission-from').val(),
            to: jQuery('#iwbvel-filter-form-yith-product-commission-to').val()
        },
        yith_shop_vendor: {
            operator: jQuery('#iwbvel-filter-form-yith-vendor-operator').val(),
            value: jQuery('#iwbvel-filter-form-yith-vendor').val()
        },
        _wcpv_product_commission: {
            from: jQuery('#iwbvel-filter-form-wc-product-commission-from').val(),
            to: jQuery('#iwbvel-filter-form-wc-product-commission-to').val()
        },
        _wcpv_product_taxes: {
            value: jQuery('#iwbvel-filter-form-wc-product-taxes').val(),
        },
        _wcpv_product_pass_shipping: {
            value: jQuery('#iwbvel-filter-form-wc-pass-shipping').val(),
        },
        wcpv_product_vendors: {
            operator: jQuery('#iwbvel-filter-form-wc-vendor-operator').val(),
            value: jQuery('#iwbvel-filter-form-wc-vendor').val()
        },
        yith_cog_cost: {
            from: jQuery('#iwbvel-filter-form-yith-cost-of-goods-from').val(),
            to: jQuery('#iwbvel-filter-form-yith-cost-of-goods-to').val()
        },
        _wc_cog_cost: {
            from: jQuery('#iwbvel-filter-form-wc-cost-of-goods-from').val(),
            to: jQuery('#iwbvel-filter-form-wc-cost-of-goods-to').val()
        },

        // yith product badge
        "_yith_wcbm_product_meta_-_id_badge": {
            operator: jQuery("#iwbvel-filter-form-yith-product-badge-id-operator").val(),
            value: jQuery("#iwbvel-filter-form-yith-product-badge-id").val()
        },
        "_yith_wcbm_product_meta_-_start_date": {
            from: jQuery("#iwbvel-filter-form-yith-product-badge-start-date-from").val(),
            to: jQuery("#iwbvel-filter-form-yith-product-badge-start-date-to").val()
        },
        "_yith_wcbm_product_meta_-_end_date": {
            from: jQuery("#iwbvel-filter-form-yith-product-badge-end-date-from").val(),
            to: jQuery("#iwbvel-filter-form-yith-product-badge-end-date-to").val()
        }
    };
    return data;
}

function iwbvelProductEdit(productIds, productData) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_product_edit',
            nonce: IWBVEL_DATA.nonce,
            product_ids: productIds,
            product_data: productData,
            filter_data: iwbvelGetCurrentFilterData(),
            current_page: iwbvelGetCurrentPage(),
        },
        success: function (response) {
            console.log(response);
            if (response.success) {
                iwbvelReloadRows(response.products, response.product_statuses);
                iwbvelSetStatusFilter(response.status_filters);
                iwbvelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.iwbvel-history-items tbody').html(response.history_items);
                jQuery('.iwbvel-history-pagination-container').html(response.history_pagination);
                iwbvelReInitDatePicker();
                iwbvelReInitColorPicker();
                let iwbvelTextEditors = jQuery('input[name="iwbvel-editors[]"]');
                if (iwbvelTextEditors.length > 0) {
                    iwbvelTextEditors.each(function () {
                        tinymce.execCommand('mceRemoveEditor', false, jQuery(this).val());
                        tinymce.execCommand('mceAddEditor', false, jQuery(this).val());
                    })
                }
                iwbvelLoadingSuccess();
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelReloadRows(products, statuses) {
    let currentStatus = (jQuery('#iwbvel-filter-form-product-status').val());
    jQuery('tr').removeClass('iwbvel-item-edited').find('.iwbvel-check-item').prop('checked', false);
    if (Object.keys(products).length > 0) {
        jQuery.each(products, function (key, val) {
            if (statuses[key] === currentStatus || (!currentStatus && statuses[key] !== 'trash')) {
                jQuery('#iwbvel-items-list').find('tr[data-item-id="' + key + '"]').replaceWith(val);
                jQuery('tr[data-item-id="' + key + '"]').addClass('iwbvel-item-edited').find('.iwbvel-check-item').prop('checked', true);
            } else {
                jQuery('#iwbvel-items-list').find('tr[data-item-id="' + key + '"]').remove();
            }
        });
        iwbvelShowSelectionTools();
    } else {
        iwbvelHideSelectionTools();
    }

    iwbvelCheckShowVariations();
}

function iwbvelGetTaxonomyParentSelectBox(taxonomy) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_taxonomy_parent_select_box',
            nonce: IWBVEL_DATA.nonce,
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-new-product-taxonomy-parent').html(response.options);
            }
        },
        error: function () { }
    });
}

function getAttributeValues(name, target) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_attribute_values',
            nonce: IWBVEL_DATA.nonce,
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
                jQuery('.iwbvel-select2-ajax').select2();
            } else {

            }
        },
        error: function () {

        }
    });
}

function iwbvelGetProductBadges(productId) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_badge_ids',
            nonce: IWBVEL_DATA.nonce,
            product_id: productId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-modal-product-badge-items').val(response.badges).change();
            }
        },
        error: function () { }
    });
}

function iwbvelGetProductIthemelandBadge(productId) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_ithemeland_badge',
            nonce: IWBVEL_DATA.nonce,
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

function iwbvelGetYikesCustomProductTabs(productId) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_yikes_custom_product_tabs',
            nonce: IWBVEL_DATA.nonce,
            product_id: productId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-modal-yikes-custom-tabs').html(response.tabs_html);
                setTimeout(function () {
                    if (response.text_editor_ids) {
                        jQuery.each(response.text_editor_ids, function (key) {
                            tinymce.remove('#' + response.text_editor_ids[key]);
                            tinymce.execCommand('mceAddEditor', true, response.text_editor_ids[key]);
                        });
                    }
                }, 100);

                setTimeout(function () {
                    jQuery('.iwbvel-yikes-override-tab').trigger('change');
                }, 250);
            }
        },
        error: function () { }
    });
}

function iwbvelAddYikesSavedTab(tabId) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_add_yikes_saved_tab',
            nonce: IWBVEL_DATA.nonce,
            tab_id: tabId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-modal-yikes-custom-tabs').append(response.tab_html);
                setTimeout(function () {
                    if (response.text_editor_id) {
                        tinymce.remove('#' + response.text_editor_id);
                        tinymce.execCommand('mceAddEditor', true, response.text_editor_id);
                    }
                }, 100);
                setTimeout(function () {
                    jQuery('.iwbvel-yikes-override-tab').trigger('change');
                }, 250);
            }
        },
        error: function () { }
    });
}

function iwbvelGetItWcRolePrices(productId) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_it_wc_role_prices',
            nonce: IWBVEL_DATA.nonce,
            product_id: productId
        },
        success: function (response) {
            if (response.success) {
                jQuery('#iwbvel-modal-it-wc-dynamic-pricing').find('input[data-type="value"]').each(function () {
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

function iwbvelGetItWcDynamicPricingSelectedRoles(productId, field) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_it_wc_dynamic_pricing_selected_roles',
            nonce: IWBVEL_DATA.nonce,
            product_id: productId,
            field: field
        },
        success: function (response) {
            if (response.success) {
                if (jQuery.isArray(response.roles) && response.roles.length > 0) {
                    jQuery('#iwbvel-modal-it-wc-dynamic-pricing-select-roles #iwbvel-user-roles').val(response.roles).change();
                } else {
                    jQuery('#iwbvel-modal-it-wc-dynamic-pricing-select-roles #iwbvel-user-roles').val('').change();
                }
            }
        },
        error: function () { }
    });
}

function iwbvelGetItWcDynamicPricingAllFields(productId) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_it_wc_dynamic_pricing_all_fields',
            nonce: IWBVEL_DATA.nonce,
            product_id: productId,
        },
        success: function (response) {
            if (response.success) {
                let element = jQuery('#iwbvel-modal-it-wc-dynamic-pricing-all-fields');

                if (response.it_product_disable_discount == 'yes') {
                    element.find('input#iwbvel-it-wc-dynamic-pricing-disable-discount').prop('checked', true);
                } else {
                    element.find('input#iwbvel-it-wc-dynamic-pricing-disable-discount').prop('checked', false);
                }

                if (response.it_product_hide_price_unregistered == 'yes') {
                    element.find('input#iwbvel-it-wc-dynamic-pricing-hide-price-unregistered').prop('checked', true);
                } else {
                    element.find('input#iwbvel-it-wc-dynamic-pricing-hide-price-unregistered').prop('checked', false);
                }

                if (response.it_pricing_product_price_user_role) {
                    element.find('select#iwbvel-select-roles-hide-price').val(response.it_pricing_product_price_user_role).change();
                }

                if (response.it_pricing_product_add_to_cart_user_role) {
                    element.find('select#iwbvel-select-roles-hide-add-to-cart').val(response.it_pricing_product_add_to_cart_user_role).change();
                }

                if (response.it_pricing_product_hide_user_role) {
                    element.find('select#iwbvel-select-roles-hide-product').val(response.it_pricing_product_hide_user_role).change();
                }

                if (response.pricing_rules_product.price_rule && (response.pricing_rules_product.price_rule instanceof Object) && Object.keys(response.pricing_rules_product.price_rule).length > 0) {
                    element.find('#iwbvel-it-pricing-roles input[data-type="value"]').each(function () {
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

function iwbvelCheckShowVariations() {
    if (jQuery("#iwbvel-bulk-edit-show-variations").prop("checked") === true) {
        jQuery('tr[data-item-type="variation"]').show();
        iwbvelShowVariationSelectionTools();
    } else {
        jQuery('tr[data-item-type="variation"]').hide();
        iwbvelHideVariationSelectionTools();
    }
}

function iwbvelFilterFormCheckAttributes() {
    let attributes = jQuery('.iwbvel-tab-content-item[data-content="filter_categories_tags_taxonomies"] .iwbvel-form-group[data-type="attribute"]');
    if (attributes.length > 0) {
        jQuery.each(attributes, function () {
            let valueField = jQuery(this).find('select[data-field="value"]');
            if (jQuery.isArray(valueField.val()) && valueField.val().length > 0) {
                jQuery('#iwbvel-bulk-edit-show-variations').prop('checked', true).change();
            }
        })
    }
}