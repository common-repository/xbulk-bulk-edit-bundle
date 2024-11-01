function iwbvelGetProductVariations(productID, variationIds, paged = 1) {
    iwbvelResetVariationsPage();
    jQuery('.iwbvel-variation-bulk-edit-loading').show();
    let variableIds = iwbvelGetProductsChecked();

    jQuery('.iwbvel-variations-wc-product-edit-button').attr('href', IWBVEL_DATA.wc_product_edit_link.replace('{id}', productID));

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_variations',
            nonce: IWBVEL_DATA.nonce,
            product_id: productID,
            current_page: paged
        },
        success: function (response) {
            if (response.success) {
                if (response.variations != '') {
                    jQuery('#iwbvel-variations-table tbody').html(response.variations).ready(function () {

                        if (variationIds && variationIds.length > 0) {
                            variationIds.forEach(function (id) {
                                jQuery('.iwbvel-variation-row-select[value="' + id + '"]').prop('checked', true).change();
                            });
                        }

                        iwbvelSetBulkActionsButtonStatus();
                        iwbvelSetTabStatus();
                    });
                }

                if (response.pagination != '') {
                    jQuery('.iwbvel-variations-pagination').html(response.pagination);
                }

                if (variableIds.length === 1) {
                    jQuery('#iwbvel-variations-swap-from-attribute-selector option').prop('disabled', true);
                    jQuery('#iwbvel-variations-swap-from-attribute-selector option[value=""]').prop('disabled', false);
                } else {
                    jQuery('#iwbvel-variations-swap-from-attribute-selector option').prop('disabled', false);
                    jQuery('#iwbvel-variations-swap-from-attribute-selector').val('').change();
                }

                if (Object.keys(response.attributes).length > 0) {
                    let attributeElement;
                    jQuery.each(response.attributes, function (key, attribute) {
                        // for "attaching" tab
                        jQuery('#iwbvel-variations-attach-attribute-selector option[value="pa_' + key + '"]').attr('data-used-flag', 'true');

                        // for "add variations" tab
                        attributeElement = jQuery('.iwbvel-product-attribute-item[data-name="' + key + '"]');

                        if (attribute.terms.length > 0) {
                            jQuery('#iwbvel-variations-swap-from-attribute-selector option[value="pa_' + key + '"]').prop('disabled', false);
                        }

                        if (attributeElement.find('.iwbvel-product-attribute-term-item').length > 0) {
                            attributeElement.find('.iwbvel-product-attribute-term-item').each(function () {
                                if (jQuery.inArray(parseInt(jQuery(this).val()), attribute.terms) !== -1) {
                                    jQuery(this).prop('checked', true).change();
                                }
                            }).promise().done(function () {
                                attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked', (attribute.visible === true)).change();
                                attributeElement.find('.iwbvel-attribute-used-for-variations').prop('checked', (attribute.variation === true)).change();
                            });
                        }
                    });
                    iwbvelAddSelectedTerms();
                }

                iwbvelSetGenerateButtonStatus();
            } else {
                jQuery('#iwbvel-variations-table tbody').html(IWBVEL_VARIATION_DATA.html.empty_table);
            }
            jQuery('.iwbvel-variation-bulk-edit-loading').hide();
        },
        error: function () {
            jQuery('#iwbvel-variations-table tbody').html(IWBVEL_VARIATION_DATA.html.empty_table);
            jQuery('.iwbvel-variation-bulk-edit-loading').hide();
        }
    });
}

function iwbvelChangePage(productID, paged = 1) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_variations_change_page',
            nonce: IWBVEL_DATA.nonce,
            product_id: productID,
            current_page: paged
        },
        success: function (response) {
            if (response.success) {
                if (response.variations != '') {
                    jQuery('#iwbvel-variations-table tbody').html(response.variations);
                }

                if (response.pagination != '') {
                    jQuery('.iwbvel-variations-pagination').html(response.pagination);
                }
            } else {
                jQuery('#iwbvel-variations-table tbody').html(IWBVEL_VARIATION_DATA.html.empty_table);
            }
            jQuery('.iwbvel-variations-table-loading').hide();
        },
        error: function () {
            jQuery('#iwbvel-variations-table tbody').html(IWBVEL_VARIATION_DATA.html.empty_table);
            jQuery('.iwbvel-variations-table-loading').hide();
        }
    });
}

function iwbvelSelectAllAttributeTerms(attributeName, attributeLabel) {
    if (jQuery('ul.iwbvel-combine-attributes-items li[data-attribute="' + attributeName + '"][data-term="iwbvel-all-terms"]').length > 0) {
        return;
    }

    jQuery('ul.iwbvel-combine-attributes-items li[data-attribute="' + attributeName + '"]').remove();

    iwbvelCombineBoxAddNewTerm({
        "attributeName": attributeName,
        "attributeLabel": attributeLabel,
        "termLabel": 'All',
        "termId": 'iwbvel-all-terms',
    });
}

function iwbvelCombineBoxAddNewTerm(data) {
    let className = 'iwbvel-' + data.attributeName + '-' + data.termId;
    let newItem = (IWBVEL_VARIATION_DATA.html.combine_item).replaceAll('{iwbvel-new-item}', className);
    jQuery('ul.iwbvel-combine-attributes-items').append(newItem).ready(function () {
        let addedItem = jQuery('ul.iwbvel-combine-attributes-items li.' + className);
        addedItem.find('.iwbvel-combine-attribute-item-taxonomy').text(data.attributeLabel + ": ");
        addedItem.find('.iwbvel-combine-attribute-item-term').text(data.termLabel);
        addedItem.attr('data-attribute', data.attributeName);
        addedItem.attr('data-term', data.termId);

        if (jQuery('input.iwbvel-check-item:visible:checked').length == 1) {
            iwbvelAddNewTermForSwap(data);
        }
    });
}

function iwbvelAddNewTermForSwap(data) {
    data.attributeName = 'pa_' + data.attributeName;
    jQuery('#iwbvel-variations-swap-from-attribute-selector option[value="' + data.attributeName + '"]').prop('disabled', false);

    if (jQuery('#iwbvel-variations-swap-from-attribute-selector').val() == data.attributeName) {
        if (data.termId == 'iwbvel-all-terms') {
            jQuery('#iwbvel-variations-swap-from-term-selector option').prop('disabled', false);
        } else {
            jQuery('#iwbvel-variations-swap-from-term-selector option[value="' + data.termId + '"]').prop('disabled', false);
        }
    }
}

function iwbvelCheckAvailabilitySwapTerms() {
    if (jQuery('input.iwbvel-check-item:visible:checked').length == 1) {
        let swapAttributeName = jQuery('#iwbvel-variations-swap-from-attribute-selector').val();
        if (jQuery('#iwbvel-variations-swap-from-attribute-selector').val() != '') {
            jQuery('#iwbvel-variations-swap-from-term-selector').val('').change();
            jQuery('#iwbvel-variations-swap-from-term-selector option').prop('disabled', true);
            jQuery('#iwbvel-variations-swap-from-term-selector option[value=""]').prop('disabled', false);

            let attributeTerms = jQuery('.iwbvel-attribute-selected-term-item[data-attribute="' + swapAttributeName.replace('pa_', '') + '"]');
            if (attributeTerms.length > 0) {
                if (attributeTerms.length == 1 && attributeTerms.attr('data-term') == 'iwbvel-all-terms') {
                    jQuery('#iwbvel-variations-swap-from-term-selector option').prop('disabled', false);
                } else {
                    attributeTerms.each(function () {
                        jQuery('#iwbvel-variations-swap-from-term-selector option[value="' + jQuery(this).attr('data-term') + '"]').prop('disabled', false);
                    });
                }
            }
        }
    }
}

function iwbvelAddSelectedTerms() {
    jQuery('.iwbvel-combine-attributes-items').html('');

    jQuery('.iwbvel-product-attribute-item').each(function () {
        let attributeElement = jQuery(this);
        let selectedItems = attributeElement.find('input.iwbvel-product-attribute-term-item:checked');

        if (!selectedItems.length) {
            return;
        }

        if (selectedItems.length == attributeElement.find('input.iwbvel-product-attribute-term-item').length) {
            attributeElement.find('.iwbvel-product-attribute-select-all').prop('checked', true).change();
            iwbvelSelectAllAttributeTerms(attributeElement.attr('data-name'), attributeElement.attr('data-label'));
        } else {
            selectedItems.each(function () {
                let $this = jQuery(this);
                iwbvelCombineBoxAddNewTerm({
                    "attributeName": attributeElement.attr('data-name'),
                    "attributeLabel": attributeElement.attr('data-label'),
                    "termLabel": $this.attr('data-term-name'),
                    "termId": $this.val(),
                });
            });
        }
    });
}

function iwbvelAddNewTerm(data) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_add_new_term',
            nonce: IWBVEL_DATA.nonce,
            attribute_name: data.attributeName,
            term_name: data.termName
        },
        success: function (response) {
            if (response.success) {
                jQuery('.iwbvel-product-attribute-item[data-name="' + data.attributeName + '"] .iwbvel-product-attribute-item-terms').append(response.new_term_html).ready(function () {
                    jQuery('#iwbvel-variations-attach-attribute-selector').change();
                    jQuery('.iwbvel-variation-add-new-term-loading').hide();
                });
            } else {
                jQuery('.iwbvel-variation-add-new-term-loading').hide();
            }
        },
        error: function () {
            jQuery('.iwbvel-variation-add-new-term-loading').hide();
        }
    });
}

function iwbvelResetBulkActionsForm() {
    let bulkActionsForm = jQuery('#iwbvel-variations-bulk-actions-modal');
    bulkActionsForm.find('input').not(':checkbox').val('').change();
    bulkActionsForm.find('input:checkbox').prop('checked', false).change();
    bulkActionsForm.find('.iwbvel-form-group[data-name="enabled"] input:checkbox').prop('checked', true).change();
    bulkActionsForm.find('.iwbvel-tab-item[data-content="general"]').trigger('click');
    bulkActionsForm.find('textarea').val('').change();
    bulkActionsForm.find('select').prop('selectedIndex', 0).change();
    bulkActionsForm.find('.iwbvel-variation-bulk-actions-file-item').remove();
}

function iwbvelGetVariationData(variationId) {
    jQuery('.iwbvel-variation-bulk-actions-loading').show();

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_variation',
            nonce: IWBVEL_DATA.nonce,
            variation_id: variationId,
        },
        success: function (response) {
            if (response.success && jQuery.type(response.variation) == 'object') {
                iwbvelSetVariationDataInBulkActionsForm(response.variation);
            }
            jQuery('.iwbvel-variation-bulk-actions-loading').hide();
        },
        error: function () {
            jQuery('.iwbvel-variation-bulk-actions-loading').hide();
        }
    });
}

function iwbvelSetVariationDataInBulkActionsForm(variation) {
    let bulkActionsForm = jQuery('#iwbvel-variations-bulk-actions');
    bulkActionsForm.find('#iwbvel-variations-bulk-actions-enabled').val((variation['is_visible'] === true) ? 'yes' : 'no').change();
    bulkActionsForm.find('#iwbvel-variations-bulk-actions-virtual').val((variation['virtual'] === true) ? 'yes' : 'no').change();
    bulkActionsForm.find('#iwbvel-variations-bulk-actions-manage-stock').val((variation['manage_stock'] === true) ? 'yes' : 'no').change();
    bulkActionsForm.find('#iwbvel-variations-bulk-actions-downloadable').val((variation['downloadable'] === true) ? 'yes' : 'no').change();

    bulkActionsForm.find('#iwbvel-variations-bulk-actions-regular-price').val(variation['regular_price']).change();
    bulkActionsForm.find('#iwbvel-variations-bulk-actions-sale-price').val(variation['sale_price']).change();
    bulkActionsForm.find('#iwbvel-variations-bulk-actions-sku').val(variation['sku']).change();

    if (variation['virtual'] === false) {
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-weight').val(variation['weight']).change();
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-length').val(variation['length']).change();
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-width').val(variation['width']).change();
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-height').val(variation['height']).change();

        bulkActionsForm.find('#iwbvel-variations-bulk-actions-shipping-class').val(variation['shipping_class_id']).change();
    }

    if (variation['manage_stock'] === true) {
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-stock-quantity').val(variation['stock_quantity']).change();
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-low-stock-threshold').val(variation['low_stock_amount']).change();
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-backorders').val(variation['backorders']).change();
    }

    if (variation['downloadable'] === true) {
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-download-limit').val(variation['download_limit']).change();
        bulkActionsForm.find('#iwbvel-variations-bulk-actions-download-expiry').val(variation['download_expiry']).change();
        if (variation['downloads'].length > 0) {
            jQuery.each(variation['downloads'], function (i, item) {
                jQuery('.iwbvel-variation-bulk-actions-files').append(IWBVEL_VARIATION_DATA.html.file_item);
            });

            setTimeout(function () {
                jQuery('.iwbvel-variation-bulk-actions-files .iwbvel-variation-bulk-actions-file-item').each(function (i) {
                    jQuery(this).find('.iwbvel-variation-bulk-actions-file-item-name-input').val(variation['downloads'][i]['name'])
                    jQuery(this).find('.iwbvel-variation-bulk-actions-file-item-url-input').val(variation['downloads'][i]['file'])
                });
            }, 1000);
        }
    }
}

function iwbvelDeleteVariationsByAttribute(data) {
    iwbvelLoadingStart();
    let productIds = iwbvelGetProductsChecked();

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_delete_variations_by_attribute',
            nonce: IWBVEL_DATA.nonce,
            variable_ids: productIds,
            attribute: data.attribute,
            term: data.term
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                } else {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelDeleteAllVariationsByVariableIds(productIds) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_delete_all_variations_by_variable_ids',
            nonce: IWBVEL_DATA.nonce,
            variable_ids: productIds,
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                } else {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelDeleteVariationsByIds(variationIds) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_delete_variations_by_ids',
            nonce: IWBVEL_DATA.nonce,
            variable_id: iwbvelGetProductsChecked(),
            variation_ids: variationIds
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                } else {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelVariationEdit(productIds, productData, reload = true, type = '') {
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
            type: type
        },
        success: function (response) {
            if (response.success) {
                iwbvelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.iwbvel-history-items tbody').html(response.history_items);
                jQuery('.iwbvel-history-pagination-container').html(response.history_pagination);
                iwbvelLoadingSuccess();
                if (reload === true) {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
            } else {
                jQuery('.iwbvel-variation-bulk-edit-loading').hide();
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
            jQuery('.iwbvel-variation-bulk-edit-loading').hide();
        }
    });
}

function iwbvelVariationsAttachTerms(data) {
    iwbvelLoadingStart();
    let productIds = iwbvelGetProductsChecked();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_variations_attach_terms',
            nonce: IWBVEL_DATA.nonce,
            variable_ids: productIds,
            attribute: data.attribute,
            terms: data.terms,
            variations: data.variations
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                } else {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelVariationsSwapTerms(data) {
    iwbvelLoadingStart();
    let productIds = iwbvelGetProductsChecked();

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_variations_swap_terms',
            nonce: IWBVEL_DATA.nonce,
            variable_ids: productIds,
            variation_ids: data.variation_ids,
            attribute: data.attribute,
            from_term: data.from_term,
            to_term: data.to_term
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                } else {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelResetVariationsPage() {
    jQuery('#iwbvel-float-side-modal-variations-bulk-edit .iwbvel-tab-item[data-content="add-variations"]').trigger('click');
    jQuery('#iwbvel-variations-attach-attribute-selector').val('').change();
    jQuery('#iwbvel-variations-swap-from-attribute-selector').val('').change();
    jQuery('#iwbvel-variations-delete-type-selector').val('all').change();
    jQuery('#iwbvel-variations-swap-from-attribute-selector option').prop('disabled', false);
    iwbvelResetAddVariationTab();
}

function iwbvelResetAddVariationTab() {
    jQuery('#iwbvel-variations-table tbody').html(IWBVEL_VARIATION_DATA.html.empty_table);
    jQuery('.iwbvel-variations-pagination').html('');
    jQuery('#iwbvel-float-side-modal-variations-bulk-edit .iwbvel-tab-item').attr('disabled', 'disabled');
    jQuery('#iwbvel-float-side-modal-variations-bulk-edit .iwbvel-tab-item[data-content="add-variations"]').removeAttr('disabled');
    jQuery('.iwbvel-variations-table-select-all-button').prop('checked', false).change();
    jQuery('.iwbvel-product-attribute-select-all').prop('checked', false).change();
    jQuery('.iwbvel-product-attribute-term-item').prop('checked', false).change();
    jQuery('.iwbvel-attribute-used-for-variations').prop('checked', false).change();
    jQuery('.iwbvel-attribute-visible-on-the-product-page').prop('checked', false).change();
    jQuery('.iwbvel-combine-attributes-items .iwbvel-attribute-selected-term-item').remove();
}

function iwbvelCheckVisibilityProductSelectorButtons() {
    let nextButtonElement = jQuery('.iwbvel-variations-product-selector-next-button');
    let prevButtonElement = jQuery('.iwbvel-variations-product-selector-prev-button');

    if (jQuery('#iwbvel-variations-variable-products-selector').prop('selectedIndex') == 0) {
        prevButtonElement.attr('disabled', 'disabled');
    } else {
        prevButtonElement.removeAttr('disabled');
    }

    if (jQuery('#iwbvel-variations-variable-products-selector option').length === (jQuery('#iwbvel-variations-variable-products-selector').prop('selectedIndex') + 1)) {
        nextButtonElement.attr('disabled', 'disabled');
    } else {
        nextButtonElement.removeAttr('disabled');
    }
}

function iwbvelGetProductPreview(variableId) {
    jQuery('.iwbvel-variations-view-product-loading').show();

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_product_preview',
            nonce: IWBVEL_DATA.nonce,
            variable_id: variableId,
        },
        success: function (response) {
            if (response.success) {
                jQuery('.iwbvel-variations-view-product-container').html(response.product);
            } else {
                jQuery('.iwbvel-variations-view-product-container').html(response.error);
            }

            jQuery('.iwbvel-variations-view-product-loading').hide();
        },
        error: function () {
            jQuery('.iwbvel-variations-view-product-container').html('Error !');
            jQuery('.iwbvel-variations-view-product-loading').hide();
        }
    });
}

function iwbvelDisableAttachingTab() {
    jQuery('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-edit-tabs"] .iwbvel-tab-item[data-content="attach-variations"]').removeClass('selected').attr('disabled', 'disabled');
    jQuery('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-edit-tabs"] .iwbvel-tab-item[data-content="add-variations"]').trigger('click');
}

function iwbvelEnableAttachingTab() {
    jQuery('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-edit-tabs"] .iwbvel-tab-item[data-content="attach-variations"]').removeAttr('disabled');
}

function iwbvelGetTermsByAttributeName(attributeName, target, preItem = '') {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_terms_by_attribute_name',
            nonce: IWBVEL_DATA.nonce,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('.iwbvel-variations-term-loading').hide();
                target.container.show();
                target.selectBox.html(preItem + response.terms);
                if (target.selectBox.hasClass('iwbvel-select2')) {
                    target.selectBox.select2();
                }
            }
        },
        error: function () { }
    });
}

function iwbvelGetTermIdsByAttributeName(attributeName, target) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_term_ids_by_attribute_name',
            nonce: IWBVEL_DATA.nonce,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('.iwbvel-variations-term-loading').hide();
                target.container.show();
                target.selectBox.html(response.terms);
                if (target.selectBox.hasClass('iwbvel-select2')) {
                    target.selectBox.select2();
                }
            }
        },
        error: function () { }
    });
}

function iwbvelGetTermsByAttributeNameForSwap(attributeName, target) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_term_ids_by_attribute_name',
            nonce: IWBVEL_DATA.nonce,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('.iwbvel-variations-term-loading').hide();
                target.from_container.show();
                target.to_container.show();
                let termsHtml = '<option value="">Select</option>' + response.terms;
                target.from.html(termsHtml);
                target.to.html(termsHtml).ready(function () {
                    iwbvelCheckAvailabilitySwapTerms();
                });

                if (target.from.hasClass('iwbvel-select2')) {
                    target.from.select2();
                }
                if (target.to.hasClass('iwbvel-select2')) {
                    target.to.select2();
                }
            }
        },
        error: function () { }
    });
}

function iwbvelGetVariationsChecked() {
    let variationsIds = [];
    let variationsChecked = jQuery("input.iwbvel-variation-row-select:visible:checkbox:checked");
    if (variationsChecked.length > 0) {
        variationsIds = variationsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }
    return variationsIds;
}

function iwbvelSetGenerateButtonStatus() {
    if (jQuery('.iwbvel-combine-attributes-items li').length > 0) {
        jQuery('.iwbvel-combine-attributes-generate-button').prop('disabled', false);
    } else {
        jQuery('.iwbvel-combine-attributes-generate-button').prop('disabled', true);
    }
}

function iwbvelSelectedTermRemove(attributeName, termSlug) {
    let attributeElement = jQuery('.iwbvel-product-attribute-item[data-name="' + attributeName + '"]');
    let selectedTerm = jQuery('.iwbvel-attribute-selected-term-item[data-attribute="' + attributeName + '"][data-term="' + termSlug + '"]');

    selectedTerm.hide();
    if (termSlug == 'iwbvel-all-terms') {
        attributeElement.find('input.iwbvel-product-attribute-select-all').prop('checked', false).change();
        attributeElement.find('input.iwbvel-product-attribute-term-item').prop('checked', false).change();
    } else {
        attributeElement.find('input.iwbvel-product-attribute-term-item[value="' + termSlug + '"]').prop('checked', false);
    }

    setTimeout(function () {
        if (!attributeElement.find('.iwbvel-product-attribute-term-item:checked').length) {
            attributeElement.find('.iwbvel-attribute-item-bottom-items').addClass('disabled');
            attributeElement.find('.iwbvel-attribute-used-for-variations').prop('checked', false).change();
            attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked', false).change();
        }
        selectedTerm.remove();
        iwbvelSetGenerateButtonStatus();
        iwbvelCheckAvailabilitySwapTerms();
    }, 250);
}

function iwbvelOpenVariationsFloatSide() {
    // get product variations
    jQuery('.iwbvel-variations-product-selector #iwbvel-variations-variable-products-selector').html('');

    let productID = jQuery("input.iwbvel-check-item:visible:checkbox:checked");

    if (!productID.length) {
        swal({
            title: "Please select one product",
            type: "warning"
        });
        return false;
    }

    let hasVariation = false;
    let variableProducts = '';

    productID.each(function () {
        variableProducts += '<option value="' + jQuery(this).val() + '">[#' + jQuery(this).val() + '] - ' + jQuery(this).closest('td').attr('data-item-title') + '</option>';
        if (jQuery(this).attr('data-item-type') == 'variation') {
            swal({
                title: "Please select variable product",
                type: "warning"
            });
            hasVariation = true;
        }
    }).promise().done(function () {
        if (hasVariation === false) {
            iwbvelOpenFloatSideModal('#iwbvel-float-side-modal-variations-bulk-edit');
            iwbvelResetVariationsPage();

            jQuery('.iwbvel-variation-bulk-edit-loading').show();

            if (productID.length === 1) {
                jQuery('.iwbvel-combine-attributes-button .single-product').addClass('active');
                jQuery('.iwbvel-combine-attributes-button .multiple-products').removeClass('active');

                jQuery('.iwbvel-variations-from-all-products-label').hide();
                jQuery('.iwbvel-variations-product-selector').hide();
                // jQuery('.iwbvel-variations-multiple-products-alert').hide();
                iwbvelEnableAttachingTab();
                iwbvelGetProductVariations(productID.val());
            } else {
                jQuery('.iwbvel-combine-attributes-button .multiple-products').addClass('active');
                jQuery('.iwbvel-combine-attributes-button .single-product').removeClass('active');
                jQuery('.iwbvel-variations-from-all-products-label').show();
                jQuery('.iwbvel-variations-product-selector #iwbvel-variations-variable-products-selector').html(variableProducts).ready(function () {
                    jQuery('.iwbvel-variations-product-selector').show();
                    iwbvelGetProductVariations(jQuery('.iwbvel-variations-product-selector #iwbvel-variations-variable-products-selector').val());
                });

                iwbvelCheckVisibilityProductSelectorButtons();
                // jQuery('.iwbvel-variations-multiple-products-alert').show();
                iwbvelDisableAttachingTab();
            }
        }
    });
}

function iwbvelPrepareAddVariations(variableIds, attributesElement) {
    let variationAttributes = {};

    if (attributesElement.length > 0) {
        attributesElement.each(function (i) {
            variationAttributes['pa_' + jQuery(this).attr('data-name')] = jQuery(this).val();
        }).promise().done(function () {
            iwbvelAddVariations(variableIds, iwbvelGetActiveAttributesForAddVariations(), [variationAttributes]);
        });
    }
}

function iwbvelSetBulkActionsButtonStatus() {
    if (jQuery('#iwbvel-variations-table tbody tr').first().find('td').length > 1) {
        jQuery('.iwbvel-variations-bulk-actions-button').show();
    } else {
        jQuery('.iwbvel-variations-bulk-actions-button').hide();
    }
}

function iwbvelSetTabStatus() {
    if (jQuery('#iwbvel-variations-table tbody tr').first().find('td').length > 1) {
        jQuery('#iwbvel-float-side-modal-variations-bulk-edit .iwbvel-tab-item').removeAttr('disabled');
        if (jQuery("input.iwbvel-check-item:visible:checkbox:checked").length === 1) {
            iwbvelEnableAttachingTab();
        } else {
            iwbvelDisableAttachingTab();
        }
    } else {
        jQuery('#iwbvel-float-side-modal-variations-bulk-edit .iwbvel-tab-item').attr('disabled', 'disabled');
        jQuery('#iwbvel-float-side-modal-variations-bulk-edit .iwbvel-tab-item[data-content="add-variations"]').removeAttr('disabled');
    }
}

function iwbvelAddVariations(variableIds, attributes, variations) {
    iwbvelLoadingStart();

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_add_variations',
            nonce: IWBVEL_DATA.nonce,
            variable_ids: variableIds,
            attributes: attributes,
            variations: variations,
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                } else {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
                iwbvelReloadProducts(variableIds);
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelGetActiveAttributes() {
    let attributes = [];

    jQuery(".iwbvel-product-attributes-list .iwbvel-product-attribute-item").each(function (i) {
        if (jQuery(this).find('.iwbvel-attribute-used-for-variations').prop('checked') === true && jQuery(this).find('.iwbvel-attribute-visible-on-the-product-page').prop('checked') === true) {
            if (jQuery(this).find('.iwbvel-product-attribute-term-item:checked').length > 0) {
                let attributeName = 'pa_' + jQuery(this).attr("data-name");
                let attributeLabel = jQuery(this).attr("data-label");
                let fields = {
                    attribute_label: attributeLabel,
                    attribute_name: attributeName,
                    terms: [],
                }
                jQuery(this).find('.iwbvel-product-attribute-term-item:checked').each(function (_, el) {
                    fields.terms.push({
                        'slug': jQuery(el).attr('data-term-slug'),
                        'name': jQuery(el).attr('data-term-name')
                    });
                }).promise().done(function () {
                    attributes.push([attributeName, fields]);
                });
            }
        }
    });

    return attributes;
}

function iwbvelGetActiveAttributesForAllCombinations() {
    let attributes = {};

    jQuery(".iwbvel-product-attributes-list .iwbvel-product-attribute-item").each(function (i) {
        if (jQuery(this).find('.iwbvel-attribute-used-for-variations').prop('checked') === true && jQuery(this).find('.iwbvel-attribute-visible-on-the-product-page').prop('checked') === true) {
            if (jQuery(this).find('.iwbvel-product-attribute-term-item:checked').length > 0) {
                let attributeName = 'pa_' + jQuery(this).attr("data-name");
                let slugs = [];
                jQuery(this).find('.iwbvel-product-attribute-term-item:checked').each(function (_, el) {
                    slugs.push(jQuery(el).attr('data-term-slug'));
                }).promise().done(function () {
                    attributes[attributeName] = slugs;
                });
            }
        }
    });

    return attributes;
}

function iwbvelGetActiveAttributesForAddVariations() {
    let attributes = {};

    jQuery(".iwbvel-product-attributes-list .iwbvel-product-attribute-item").each(function (i) {
        if (jQuery(this).find('.iwbvel-attribute-used-for-variations').prop('checked') === true && jQuery(this).find('.iwbvel-attribute-visible-on-the-product-page').prop('checked') === true) {
            if (jQuery(this).find('.iwbvel-product-attribute-term-item:checked').length > 0) {
                let attributeName = 'pa_' + jQuery(this).attr("data-name");
                let ids = [];
                jQuery(this).find('.iwbvel-product-attribute-term-item:checked').each(function (_, el) {
                    ids.push(jQuery(el).val());
                }).promise().done(function () {
                    attributes[attributeName] = ids;
                });
            }
        }
    });

    return attributes;
}

function iwbvelVariationsAttributesEdit(data) {
    iwbvelLoadingStart();
    let productIds = iwbvelGetProductsChecked();

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_variations_attributes_edit',
            nonce: IWBVEL_DATA.nonce,
            variable_id: productIds[0],
            variation_id: data.variation_id,
            attributes: data.attributes
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                } else {
                    jQuery('.iwbvel-variations-reload-table').trigger('click');
                }
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelDefaultAttributesUpdate(attributes) {
    iwbvelLoadingStart();
    let productIds = iwbvelGetProductsChecked();

    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_default_attributes_update',
            nonce: IWBVEL_DATA.nonce,
            variable_ids: [productIds[0]],
            attributes: attributes
        },
        success: function (response) {
            if (response.success) {
                iwbvelLoadingSuccess();
                if (IWBVEL_DATA.iwbvel_settings.close_popup_after_applying == 'yes') {
                    iwbvelCloseFloatSideModal();
                }
            } else {
                iwbvelLoadingError();
            }
        },
        error: function () {
            iwbvelLoadingError();
        }
    });
}

function iwbvelGetAllCombinations(attributes_arr) {
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

function iwbvelGetPossibleCombinations(attributes) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'iwbvel_get_possible_combinations',
            nonce: IWBVEL_DATA.nonce,
            attributes: attributes,
        },
        success: function (response) {
            jQuery('#iwbvel-variations-all-variations-modal .iwbvel-variations-individual-variation-loading').hide();
            jQuery('#iwbvel-variations-all-variations-modal').attr('data-height-fixed', 'false');
            if (response.success) {
                jQuery('#iwbvel-variations-all-variations-modal #iwbvel-variations-all-variations-items').html(response.items).ready(function () {
                    setTimeout(function () {
                        if (jQuery.fn.sortable) {
                            let possibleCombinationsRow = jQuery(".iwbvel-variations-possible-combinations-rows");
                            possibleCombinationsRow.sortable({
                                handle: ".iwbvel-variations-possible-combination-sort-button",
                                cancel: ""
                            });
                            possibleCombinationsRow.disableSelection();
                        }

                        iwbvelFixModalHeight(jQuery('#iwbvel-variations-all-variations-modal'));
                    }, 50);
                });
            } else {
                jQuery('#iwbvel-variations-all-variations-modal #iwbvel-variations-all-variations-items').html('No data available');
            }
        },
        error: function () {
            jQuery('#iwbvel-variations-all-variations-modal .iwbvel-variations-individual-variation-loading').hide();
            jQuery('#iwbvel-variations-all-variations-modal #iwbvel-variations-all-variations-items').html('Error !');
        }
    });
}