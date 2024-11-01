"use strict";

var wpbelOpenFullScreenIcon = '<i class="wpbel-icon-enlarge"></i>';
var wpbelCloseFullScreenIcon = '<i class="wpbel-icon-shrink"></i>';

function openFullscreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

function wpbelDataTableFixSize() {
    jQuery('#wpbel-main').css({
        top: jQuery('#wpadminbar').height() + 'px',
        "padding-left": (jQuery('#adminmenu:visible').length) ? jQuery('#adminmenu').width() + 'px' : 0
    });

    jQuery('#wpbel-loading').css({
        top: jQuery('#wpadminbar').height() + 'px',
    });

    let height = parseInt(jQuery(window).height()) - parseInt(jQuery('#wpbel-header').height() + 85);

    jQuery('.wpbel-table').css({
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

function wpbelFullscreenHandler() {
    if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        jQuery('#wpbel-full-screen').html(wpbelOpenFullScreenIcon).attr('title', 'Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').show();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
    } else {
        jQuery('#wpbel-full-screen').html(wpbelCloseFullScreenIcon).attr('title', 'Exit Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').hide();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": 0 });
    }

    wpbelDataTableFixSize();
}

function wpbelOpenTab(item) {
    let wpbelTabItem = item;
    let wpbelParentContent = wpbelTabItem.closest(".wpbel-tabs-list");
    let wpbelParentContentID = wpbelParentContent.attr("data-content-id");
    let wpbelDataBox = wpbelTabItem.attr("data-content");
    wpbelParentContent.find("li button.selected").removeClass("selected");
    if (wpbelTabItem.closest('.wpbel-sub-tab').length > 0) {
        wpbelTabItem.closest('li.wpbel-has-sub-tab').find('button').first().addClass("selected");
    } else {
        wpbelTabItem.addClass("selected");
    }

    if (item.closest('.wpbel-tabs-list').attr('data-content-id') && item.closest('.wpbel-tabs-list').attr('data-content-id') == 'wpbel-main-tabs-contents') {
        jQuery('.wpbel-tabs-list[data-content-id="wpbel-main-tabs-contents"] li[data-depend] button').not('.wpbel-tab-item').addClass('disabled');
        jQuery('.wpbel-tabs-list[data-content-id="wpbel-main-tabs-contents"] li[data-depend="' + wpbelDataBox + '"] button').removeClass('disabled');
    }

    jQuery("#" + wpbelParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wpbelParentContentID + " div[data-content=" + wpbelDataBox + "]").addClass("selected");

    if (item.attr("data-type") === "main-tab") {
        wpbelFilterFormClose();
    }
}

function wpbelFixModalHeight(modal) {
    if (!modal.attr('data-height-fixed') || modal.attr('data-height-fixed') != 'true') {
        let footerHeight = 0;
        let contentHeight = modal.find(".wpbel-modal-content").height();
        let titleHeight = modal.find(".wpbel-modal-title").height();
        if (modal.find(".wpbel-modal-footer").length > 0) {
            footerHeight = modal.find(".wpbel-modal-footer").height();
        }

        let modalMargin = parseInt((parseInt(jQuery('body').height()) * 20) / 100);
        let bodyHeight = (modal.find(".wpbel-modal-body-content").length) ? parseInt(modal.find(".wpbel-modal-body-content").height() + 30) : contentHeight;
        let bodyMaxHeight = parseInt(jQuery('body').height()) - (titleHeight + footerHeight + modalMargin);
        if (modal.find('.wpbel-modal-top-search').length > 0) {
            bodyHeight += parseInt(modal.find('.wpbel-modal-top-search').height() + 30);
            bodyMaxHeight -= parseInt(modal.find('.wpbel-modal-top-search').height());
        }

        modal.find(".wpbel-modal-content").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.find(".wpbel-modal-body").css({
            "height": parseInt(bodyHeight) + 'px',
            'max-height': parseInt(bodyMaxHeight) + 'px'
        });
        modal.find(".wpbel-modal-box").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.attr('data-height-fixed', 'true');
    }
}

function wpbelOpenFloatSideModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn(20);
    modal.find(".wpbel-float-side-modal-box").animate({
        right: 0
    }, 180);
}

function wpbelCloseFloatSideModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    jQuery('.wpbel-float-side-modal-box').animate({
        right: "-80%"
    }, 180);
    jQuery('.wpbel-float-side-modal').fadeOut(200);
}

function wpbelCloseModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    let lastModalOpened = jQuery('#wpbel-last-modal-opened');
    let modal = jQuery(lastModalOpened.val());
    if (lastModalOpened.val() !== '') {
        modal.find(' .wpbel-modal-box').fadeOut();
        modal.fadeOut();
        lastModalOpened.val('');
    } else {
        let lastModal = jQuery('.wpbel-modal:visible').last();
        lastModal.find('.wpbel-modal-box').fadeOut();
        lastModal.fadeOut();
    }

    setTimeout(function () {
        modal.find('.wpbel-modal-box').css({
            height: 'auto',
            "max-height": '80%'
        });
        modal.find('.wpbel-modal-body').css({
            height: 'auto',
            "max-height": '90%'
        });
        modal.find('.wpbel-modal-content').css({
            height: 'auto',
            "max-height": '92%'
        });
    }, 400);
}

function wpbelOpenModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn();
    modal.find(".wpbel-modal-box").fadeIn();
    jQuery("#wpbel-last-modal-opened").val(jQuery(this).attr("data-target"));

    // set height for modal body
    setTimeout(function () {
        wpbelFixModalHeight(modal);
    }, 150)
}

function wpbelReInitColorPicker() {
    if (jQuery('.wpbel-color-picker').length > 0) {
        jQuery('.wpbel-color-picker').wpColorPicker();
    }
    if (jQuery('.wpbel-color-picker-field').length > 0) {
        jQuery('.wpbel-color-picker-field').wpColorPicker();
    }
}

function wpbelReInitDatePicker() {
    if (jQuery.fn.datetimepicker) {
        jQuery('.wpbel-datepicker-with-dash').datetimepicker('destroy');
        jQuery('.wpbel-datepicker').datetimepicker('destroy');
        jQuery('.wpbel-timepicker').datetimepicker('destroy');
        jQuery('.wpbel-datetimepicker').datetimepicker('destroy');

        jQuery('.wpbel-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y/m/d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wpbel-datepicker-with-dash').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wpbel-timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wpbel-datetimepicker').datetimepicker({
            format: 'Y/m/d H:i',
            scrollMonth: false,
            scrollInput: false
        });
    }

}

function wpbelPaginationLoadingStart() {
    jQuery('.wpbel-pagination-loading').show();
}

function wpbelPaginationLoadingEnd() {
    jQuery('.wpbel-pagination-loading').hide();
}

function wpbelLoadingStart() {
    jQuery('#wpbel-loading').removeClass('wpbel-loading-error').removeClass('wpbel-loading-success').text('Loading ...').slideDown(300);
}

function wpbelLoadingSuccess(message = 'Success !') {
    jQuery('#wpbel-loading').removeClass('wpbel-loading-error').addClass('wpbel-loading-success').text(message).delay(1500).slideUp(200);
}

function wpbelLoadingError(message = 'Error !') {
    jQuery('#wpbel-loading').removeClass('wpbel-loading-success').addClass('wpbel-loading-error').text(message).delay(1500).slideUp(200);
}

function wpbelSetColorPickerTitle() {
    jQuery('.wpbel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wpbel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wpbelSetTipsyTooltip();
    });
}

function wpbelFilterFormClose() {
    if (jQuery('#wpbel-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.wpbel-filter-form-icon').addClass('wpbel-icon-chevron-down').removeClass('wpbel-icon-chevron-up');
        jQuery('#wpbel-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function wpbelSetTipsyTooltip() {
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

function wpbelCheckUndoRedoStatus(reverted, history) {
    if (reverted) {
        wpbelEnableRedo();
    } else {
        wpbelDisableRedo();
    }
    if (history) {
        wpbelEnableUndo();
    } else {
        wpbelDisableUndo();
    }
}

function wpbelDisableUndo() {
    jQuery('#wpbel-bulk-edit-undo').attr('disabled', 'disabled');
}

function wpbelEnableUndo() {
    jQuery('#wpbel-bulk-edit-undo').prop('disabled', false);
}

function wpbelDisableRedo() {
    jQuery('#wpbel-bulk-edit-redo').attr('disabled', 'disabled');
}

function wpbelEnableRedo() {
    jQuery('#wpbel-bulk-edit-redo').prop('disabled', false);
}

function wpbelHideSelectionTools() {
    jQuery('.wpbel-bulk-edit-form-selection-tools').hide();
    jQuery('#wpbel-bulk-edit-trash-restore').hide();
}

function wpbelShowSelectionTools() {
    jQuery('.wpbel-bulk-edit-form-selection-tools').show();
    jQuery('#wpbel-bulk-edit-trash-restore').show();
}

function wpbelSetColorPickerTitle() {
    jQuery('.wpbel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wpbel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wpbelSetTipsyTooltip();
    });
}

function wpbelColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wpbel_column_manager_add_field',
            nonce: WPBEL_DATA.ajax_nonce,
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function (response) {
            jQuery('.wpbel-box-loading').hide();
            jQuery('.wpbel-column-manager-added-fields[data-action=' + action + '] .items').append(response);
            fieldName.forEach(function (name) {
                jQuery('.wpbel-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            wpbelReInitColorPicker();
            jQuery('.wpbel-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.wpbel-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function () {
                wpbelSetColorPickerTitle();
            }, 250);
        },
        error: function () {
        }
    })
}

function wpbelAddMetaKeysManual(meta_key_name) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wpbel_add_meta_keys_manual',
            nonce: WPBEL_DATA.ajax_nonce,
            meta_key_name: meta_key_name,
        },
        success: function (response) {
            jQuery('#wpbel-meta-fields-items').append(response);
            wpbelLoadingSuccess();
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelAddACFMetaField(field_name, field_label, field_type) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wpbel_add_acf_meta_field',
            nonce: WPBEL_DATA.ajax_nonce,
            field_name: field_name,
            field_label: field_label,
            field_type: field_type
        },
        success: function (response) {
            jQuery('#wpbel-meta-fields-items').append(response);
            wpbelLoadingSuccess();
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#wpbel-filter-form-content [data-field="value"]').each(function () {
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
    jQuery('#wpbel-filter-form-content [data-field="from"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wpbel-filter-form-content [data-field="to"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery('#filter-form-changed').val(isChanged);

    if (isChanged === true) {
        jQuery('#wpbel-bulk-edit-reset-filter').show();
    } else {
        jQuery('.wpbel-top-nav-status-filter button[data-status="all"]').addClass('active');
    }
}

function wpbelGetCheckedItem() {
    let itemIds;
    let itemsChecked = jQuery("input.wpbel-check-item:checkbox:checked");
    if (itemsChecked.length > 0) {
        itemIds = itemsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }

    return itemIds;
}

function wpbelGetTableCount(countPerPage, currentPage, total) {
    currentPage = (currentPage) ? currentPage : 1;
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = (total > 0) ? parseInt(showingTo - countPerPage) + 1 : 0;
    showingTo = (showingTo < total) ? showingTo : total;
    return "Showing " + showingFrom + " to " + showingTo + " of " + total + " entries";
}

function wpbelGetPostsChecked() {
    let postIds = [];
    let postsChecked = jQuery("input.wpbel-check-item:checkbox:checked");
    if (postsChecked.length > 0) {
        postIds = postsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }
    return postIds;
}

function wpbelInlineEdit(postsIDs, field, value, reload = false) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_inline_edit',
            nonce: WPBEL_DATA.ajax_nonce,
            posts_ids: postsIDs,
            field: field,
            value: value
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    wpbelReloadPosts(response.edited_ids);
                } else {
                    wpbelLoadingSuccess()
                }
                wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wpbel-history-items tbody').html(response.history_items).ready(function () {
                    wpbelReInitDatePicker();
                });
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelGetPostTags() {
    let query;
    jQuery(".wpbel-select2-post-tags").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WPBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wpbel_get_post_tags",
                    nonce: WPBEL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Tag Name ...",
        minimumInputLength: 2,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wpbelReloadPosts(edited_ids = [], current_page = wpbelGetCurrentPage()) {
    let data = wpbelGetCurrentFilterData();
    wpbelPostsFilter(data, data.search_type, edited_ids, current_page);
}

function wpbelPostsFilter(data, action, edited_ids = null, page = wpbelGetCurrentPage()) {
    if (action === 'pagination') {
        wpbelPaginationLoadingStart();
    } else {
        wpbelLoadingStart();
    }

    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_posts_filter',
            nonce: WPBEL_DATA.ajax_nonce,
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                wpbelSetPostsList(response, edited_ids)
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelSetPostsList(response, edited_ids = null) {
    jQuery('#wpbel-items-table').html(response.posts_list);
    jQuery('.wpbel-items-pagination').html(response.pagination);
    jQuery('.wpbel-top-nav-status-filter').html(response.status_filters);
    jQuery('.wpbel-items-count').html(wpbelGetTableCount(jQuery('#wpbel-quick-per-page').val(), wpbelGetCurrentPage(), response.posts_count)).ready(function () {
        wpbelReInitDatePicker();
    });
    jQuery('.wpbel-bulk-edit-status-filter-item').removeClass('active');
    let statusFilter = (jQuery('#wpbel-filter-form-post-status').val()) ? jQuery('#wpbel-filter-form-post-status').val() : 'all';
    if (jQuery.isArray(statusFilter)) {
        statusFilter.forEach(function (val) {
            jQuery('.wpbel-bulk-edit-status-filter-item[data-status="' + val + '"]').addClass('active');
        });
    } else {
        let activeItem = jQuery('.wpbel-bulk-edit-status-filter-item[data-status="' + statusFilter + '"]');
        activeItem.addClass('active');
        jQuery('.wpbel-status-filter-selected-name').text(' - ' + activeItem.text())
    }

    if (jQuery.fn.datepicker) {
        jQuery('.wpbel-datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
    }

    if (edited_ids && edited_ids.length > 0) {
        jQuery('tr').removeClass('wpbel-item-edited');
        edited_ids.forEach(function (postID) {
            jQuery('tr[data-item-id=' + postID + ']').addClass('wpbel-item-edited');
            jQuery('input.wpbel-check-item[value=' + postID + ']').prop('checked', true);
        });
        wpbelShowSelectionTools();
    } else {
        wpbelHideSelectionTools();
    }

    wpbelSetTipsyTooltip();
    setTimeout(function () {
        let maxHeightScrollWrapper = jQuery('.scroll-wrapper > .scroll-content').css('max-height');
        jQuery('.scroll-wrapper > .scroll-content').css({
            'max-height': (parseInt(maxHeightScrollWrapper) + 5)
        });
    }, 500);
}

function wpbelGetPostData(postID) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_post_data',
            nonce: WPBEL_DATA.ajax_nonce,
            post_id: postID
        },
        success: function (response) {
            if (response.success) {
                wpbelSetPostDataBulkEditForm(response.post_data);
            } else {

            }
        },
        error: function () {

        }
    });
}

function wpbelSetSelectedParent(postId) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_post_by_id',
            nonce: WPBEL_DATA.ajax_nonce,
            post_id: postId
        },
        success: function (response) {
            if (response.post_title) {
                let parentField = jQuery('#wpbel-select-post-value');
                if (parentField.length > 0) {
                    parentField.append("<option value='" + postId + "' selected>" + response.post_title + "</option>").prop('selected', true);
                }
            }
        },
        error: function () {

        }
    });
}

function wpbelSetPostDataBulkEditForm(postData) {

    let reviews_allowed = (postData.reviews_allowed) ? 'yes' : 'no';
    let sold_individually = (postData.sold_individually) ? 'yes' : 'no';
    let manage_stock = (postData.manage_stock) ? 'yes' : 'no';
    let featured = (postData.featured) ? 'yes' : 'no';
    let virtual = (postData.virtual) ? 'yes' : 'no';
    let downloadable = (postData.downloadable) ? 'yes' : 'no';

    let attributes = jQuery('#wpbel-float-side-modal-bulk-edit .wpbel-bulk-edit-form-group[data-type=attribute]');
    if (attributes.length > 0) {
        let attribute_name = '';
        attributes.each(function () {
            attribute_name = jQuery(this).attr('data-taxonomy');
            if (postData.attribute[attribute_name]) {
                jQuery('#wpbel-float-side-modal-bulk-edit .wpbel-bulk-edit-form-group[data-type=attribute][data-taxonomy=' + attribute_name + ']').find('select[data-field=value]').val(postData.attribute[attribute_name]).change();
            }
        });
    }

    let custom_fields = jQuery('#wpbel-float-side-modal-bulk-edit .wpbel-bulk-edit-form-group[data-type=custom_fields]');
    if (custom_fields.length > 0) {
        let taxonomy_name = '';
        custom_fields.each(function () {
            taxonomy_name = jQuery(this).attr('data-taxonomy');
            if (postData.meta_field[taxonomy_name]) {
                jQuery('#wpbel-float-side-modal-bulk-edit .wpbel-bulk-edit-form-group[data-type=custom_fields][data-taxonomy=' + taxonomy_name + ']').find('[data-field=value]').val(postData.meta_field[taxonomy_name][0]).change();
            }
        });
    }

    jQuery('#wpbel-bulk-edit-form-post-title').val(postData.post_title);
    jQuery('#wpbel-bulk-edit-form-post-slug').val(postData.post_slug);
    jQuery('#wpbel-bulk-edit-form-post-sku').val(postData.sku);
    jQuery('#wpbel-bulk-edit-form-post-description').val(postData.post_content);
    jQuery('#wpbel-bulk-edit-form-post-short-description').val(postData.post_excerpt);
    jQuery('#wpbel-bulk-edit-form-post-purchase-note').val(postData.purchase_note);
    jQuery('#wpbel-bulk-edit-form-post-menu-order').val(postData.menu_order);
    jQuery('#wpbel-bulk-edit-form-post-sold-individually').val(sold_individually).change();
    jQuery('#wpbel-bulk-edit-form-post-enable-reviews').val(reviews_allowed).change();
    jQuery('#wpbel-bulk-edit-form-post-post-status').val(postData.post_status).change();
    jQuery('#wpbel-bulk-edit-form-post-catalog-visibility').val(postData.catalog_visibility).change();
    jQuery('#wpbel-bulk-edit-form-post-date-created').val(postData.post_date);
    jQuery('#wpbel-bulk-edit-form-post-author').val(postData.post_author).change();
    jQuery('#wpbel-bulk-edit-form-categories').val(postData.post_cat).change();
    jQuery('#wpbel-bulk-edit-form-tags').val(postData.post_tag).change();
    jQuery('#wpbel-bulk-edit-form-regular-price').val(postData.regular_price);
    jQuery('#wpbel-bulk-edit-form-sale-price').val(postData.sale_price);
    jQuery('#wpbel-bulk-edit-form-sale-date-from').val(postData.date_on_sale_from);
    jQuery('#wpbel-bulk-edit-form-sale-date-to').val(postData.date_on_sale_to);
    jQuery('#wpbel-bulk-edit-form-tax-status').val(postData.tax_status).change();
    jQuery('#wpbel-bulk-edit-form-tax-class').val(postData.tax_class).change();
    jQuery('#wpbel-bulk-edit-form-shipping-class').val(postData.shipping_class).change();
    jQuery('#wpbel-bulk-edit-form-width').val(postData.width);
    jQuery('#wpbel-bulk-edit-form-height').val(postData.height);
    jQuery('#wpbel-bulk-edit-form-length').val(postData.length);
    jQuery('#wpbel-bulk-edit-form-weight').val(postData.weight);
    jQuery('#wpbel-bulk-edit-form-manage-stock').val(manage_stock).change();
    jQuery('#wpbel-bulk-edit-form-stock-status').val(postData.stock_status).change();
    jQuery('#wpbel-bulk-edit-form-stock-quantity').val(postData.stock_quantity);
    jQuery('#wpbel-bulk-edit-form-backorders').val(postData.backorders).change();
    jQuery('#wpbel-bulk-edit-form-post-type').val(postData.post_type).change();
    jQuery('#wpbel-bulk-edit-form-featured').val(featured).change();
    jQuery('#wpbel-bulk-edit-form-virtual').val(virtual).change();
    jQuery('#wpbel-bulk-edit-form-downloadable').val(downloadable).change();
    jQuery('#wpbel-bulk-edit-form-download-limit').val(postData.download_limit);
    jQuery('#wpbel-bulk-edit-form-download-expiry').val(postData.download_expiry).change();
    jQuery('#wpbel-bulk-edit-form-post-url').val(postData.meta_field._post_url);
    jQuery('#wpbel-bulk-edit-form-button-text').val(postData.meta_field._button_text);
    jQuery('#wpbel-bulk-edit-form-upsells').val(postData.upsell_ids).change();
    jQuery('#wpbel-bulk-edit-form-cross-sells').val(postData.cross_sell_ids).change();
}

function wpbelEditByCalculator(postIDs, field, values) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_edit_by_calculator',
            nonce: WPBEL_DATA.ajax_nonce,
            post_ids: postIDs,
            field: field,
            operator: values.operator,
            value: values.value,
            operator_type: values.operator_type,
            round_item: values.roundItem,
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts(response.edited_ids);
            }
            wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
            jQuery('.wpbel-history-items tbody').html(response.history_items);
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelGetPostChecked() {
    let postIds = [];
    let postsChecked = jQuery("input.wpbel-check-item:checkbox:checked");
    if (postsChecked.length > 0) {
        postIds = postsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }
    return postIds;
}

function wpbelDeletePost(postIDs, deleteType) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_delete_posts',
            nonce: WPBEL_DATA.ajax_nonce,
            post_ids: postIDs,
            delete_type: deleteType,
            filter_data: wpbelGetCurrentFilterData(),
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts();
                wpbelHideSelectionTools();
                wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wpbel-history-items tbody').html(response.history_items);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}


function wpbelRestorePost(postIds) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_untrash_posts',
            nonce: WPBEL_DATA.ajax_nonce,
            post_ids: postIds,
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts();
                wpbelHideSelectionTools();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelEmptyTrash() {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_empty_trash',
            nonce: WPBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts();
                wpbelHideSelectionTools();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelDuplicatePost(postIDs, duplicateNumber) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_duplicate_post',
            nonce: WPBEL_DATA.ajax_nonce,
            post_ids: postIDs,
            duplicate_number: duplicateNumber
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts([], wpbelGetCurrentPage());
                wpbelCloseModal();
                wpbelHideSelectionTools();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelCreateNewPost(count = 1, postType = null) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_create_new_post',
            nonce: WPBEL_DATA.ajax_nonce,
            count: count,
            post_type: postType
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts(response.post_ids, 1);
                wpbelCloseModal();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelGetAllCombinations(attributes_arr) {
    var combinations = [], args = attributes_arr, max = args.length - 1;
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

function wpbelSaveColumnProfile(presetKey, items, type) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_save_column_profile',
            nonce: WPBEL_DATA.ajax_nonce,
            preset_key: presetKey,
            items: items,
            type: type
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                location.href = location.href.replace(location.hash, "");
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelLoadFilterProfile(presetKey) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_load_filter_profile',
            nonce: WPBEL_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbelResetFilterForm();
                setTimeout(function () {
                    setFilterValues(response.filter_data);
                }, 500);
                wpbelLoadingSuccess();
                wpbelSetPostsList(response);
                wpbelCloseModal();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelDeleteFilterProfile(presetKey) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_delete_filter_profile',
            nonce: WPBEL_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelFilterProfileChangeUseAlways(presetKey) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_filter_profile_change_use_always',
            nonce: WPBEL_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
            } else {
                wpbelLoadingError()
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelGetCurrentFilterData() {
    return (jQuery('#wpbel-quick-search-text').val()) ? wpbelGetQuickSearchData() : wpbelGetProSearchData()
}

function wpbelResetQuickSearchForm() {
    jQuery('.wpbel-top-nav-filters-search input').val('');
    jQuery('.wpbel-top-nav-filters-search select').prop('selectedIndex', 0);
    jQuery('#wpbel-quick-search-reset').hide();
}

function wpbelResetFilterForm() {
    jQuery('#wpbel-float-side-modal-filter input').val('');
    jQuery('#wpbel-float-side-modal-filter select').prop('selectedIndex', 0).change();
    jQuery('#wpbel-float-side-modal-filter .wpbel-select2').val(null).trigger('change');
    jQuery('.wpbel-bulk-edit-status-filter-item').removeClass('active');
    jQuery('.wpbel-bulk-edit-status-filter-item[data-status="all"]').addClass('active');
}

function wpbelResetFilters() {
    wpbelResetFilterForm();
    wpbelResetQuickSearchForm();

    jQuery(".wpbel-filter-profiles-items tr").removeClass("wpbel-filter-profile-loaded");
    jQuery('input.wpbel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
    jQuery("#wpbel-bulk-edit-reset-filter").hide();
    jQuery('#wpbel-bulk-edit-reset-filter').hide();

    jQuery('.wpbel-reset-filter-form').closest('li').hide();

    setTimeout(function () {
        if (window.location.search !== '?page=wpbel') {
            wpbelClearFilterDataWithRedirect();
        } else {
            let data = wpbelGetCurrentFilterData();
            wpbelFilterProfileChangeUseAlways("default");
            wpbelPostsFilter(data, "pro_search");
        }
    }, 250);
}

function wpbelCheckResetFilterButton() {
    if (jQuery('#wpbel-bulk-edit-filter-tabs-contents [data-field="value"]').length > 0) {
        jQuery('#wpbel-bulk-edit-filter-tabs-contents [data-field="value"]').each(function () {
            if (jQuery(this).val() != '') {
                jQuery('.wpbel-reset-filter-form').closest('li').show();
                return true;
            }
        });
    }
}

function wpbelClearFilterDataWithRedirect() {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_clear_filter_data',
            nonce: WPBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            window.location.search = '?page=wpbel';
        },
        error: function () {
        }
    });
}

function wpbelChangeCountPerPage(countPerPage) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_change_count_per_page',
            nonce: WPBEL_DATA.ajax_nonce,
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts([], 1);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelUpdatePostTaxonomy(post_ids, field, data, reload) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_update_post_taxonomy',
            nonce: WPBEL_DATA.ajax_nonce,
            post_ids: post_ids,
            field: field,
            values: data
        },
        success: function (response) {
            if (response.success) {
                wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wpbel-history-items tbody').html(response.history_items);

                if (reload === true) {
                    wpbelReloadPosts(post_ids);
                } else {
                    wpbelLoadingSuccess();
                }
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelAddPostTaxonomy(taxonomyInfo, taxonomyName) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_add_post_taxonomy',
            nonce: WPBEL_DATA.ajax_nonce,
            taxonomy_info: taxonomyInfo,
            taxonomy_name: taxonomyName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-taxonomy-' + taxonomyName + '-' + taxonomyInfo.post_id + ' .wpbel-post-items-list').html(response.taxonomy_items);
                wpbelLoadingSuccess();
                wpbelCloseModal()
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelAddPostAttribute(attributeInfo, attributeName) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_add_post_attribute',
            nonce: WPBEL_DATA.ajax_nonce,
            attribute_info: attributeInfo,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-attribute-' + attributeName + '-' + attributeInfo.post_id + ' .wpbel-post-items-list').html(response.attribute_items);
                wpbelLoadingSuccess();
                wpbelCloseModal()
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelAddNewFileItem() {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_add_new_file_item',
            nonce: WPBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').prepend(response.file_item);
                wpbelSetTipsyTooltip();
            }
        },
        error: function () {

        }
    });
}

function wpbelGetPostFiles(postID) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_post_files',
            nonce: WPBEL_DATA.ajax_nonce,
            post_id: postID,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').html(response.files);
                wpbelSetTipsyTooltip();
            } else {
                jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').html('');
            }
        },
        error: function () {
            jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').html('');
        }
    });
}

function changedTabs(item) {
    let change = false;
    let tab = jQuery('nav.wpbel-tabs-navbar a[data-content=' + item.closest('.wpbel-tab-content-item').attr('data-content') + ']');
    item.closest('.wpbel-tab-content-item').find('[data-field=operator]').each(function () {
        if (jQuery(this).val() === 'text_remove_duplicate') {
            change = true;
            return false;
        }
    });
    item.closest('.wpbel-tab-content-item').find('[data-field=value]').each(function () {
        if (jQuery(this).val()) {
            change = true;
            return false;
        }
    });
    if (change === true) {
        tab.addClass('wpbel-tab-changed');
    } else {
        tab.removeClass('wpbel-tab-changed');
    }
}

function wpbelGetQuickSearchData() {
    return {
        search_type: 'quick_search',
        quick_search_text: jQuery('#wpbel-quick-search-text').val(),
        quick_search_field: jQuery('#wpbel-quick-search-field').val(),
        quick_search_operator: jQuery('#wpbel-quick-search-operator').val(),
    };
}

function wpbelSortByColumn(columnName, sortType) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_sort_by_column',
            nonce: WPBEL_DATA.ajax_nonce,
            filter_data: wpbelGetCurrentFilterData(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                wpbelSetPostsList(response)
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelColumnManagerFieldsGetForEdit(presetKey) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_column_manager_get_fields_for_edit',
            nonce: WPBEL_DATA.ajax_nonce,
            preset_key: presetKey
        },
        success: function (response) {
            jQuery('#wpbel-modal-column-manager-edit-preset .wpbel-box-loading').hide();
            jQuery('.wpbel-column-manager-added-fields[data-action=edit] .items').html(response.html);
            setTimeout(function () {
                wpbelSetColorPickerTitle();
            }, 250);
            jQuery('.wpbel-column-manager-available-fields[data-action=edit] li').each(function () {
                if (jQuery.inArray(jQuery(this).attr('data-name'), response.fields.split(',')) !== -1) {
                    jQuery(this).attr('data-added', 'true').hide();
                } else {
                    jQuery(this).attr('data-added', 'false').show();
                }
            });
            jQuery('.wpbel-color-picker').wpColorPicker();
        },
    })
}

function wpbelAddMetaKeysByPostID(postID) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wpbel_add_meta_keys_by_post_id',
            nonce: WPBEL_DATA.ajax_nonce,
            post_id: postID,
        },
        success: function (response) {
            jQuery('#wpbel-meta-fields-items').append(response);
            wpbelLoadingSuccess();
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelHistoryUndo() {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_history_undo',
            nonce: WPBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wpbel-history-items tbody').html(response.history_items);
                wpbelReloadPosts(response.post_ids);
            }
        },
        error: function () {

        }
    });
}

function wpbelHistoryRedo() {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_history_redo',
            nonce: WPBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wpbel-history-items tbody').html(response.history_items);
                wpbelReloadPosts(response.post_ids);
            }
        },
        error: function () {

        }
    });
}

function wpbelHistoryFilter(filters = null) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_history_filter',
            nonce: WPBEL_DATA.ajax_nonce,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wpbel-history-items tbody').html(response.history_items);
                } else {
                    jQuery('.wpbel-history-items tbody').html("<td colspan='4'><span>Not Found!</span></td>");
                }
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelHistoryChangePage(page = 1, filters = null) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_history_change_page',
            nonce: WPBEL_DATA.ajax_nonce,
            page: page,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wpbel-history-items tbody').html(response.history_items);
                    jQuery('.wpbel-history-pagination-container').html(response.history_pagination);
                } else {
                    jQuery('.wpbel-history-items tbody').html("<td colspan='4'><span>" + wpbelTranslate.notFound + "</span></td>");
                }
                jQuery('.wpbel-history-pagination-loading').hide();
            } else {
                jQuery('.wpbel-history-pagination-loading').hide();
            }
        },
        error: function () {
            jQuery('.wpbel-history-pagination-loading').hide();
        }
    });
}

function wpbelGetCurrentPage() {
    return jQuery('.wpbel-top-nav-filters .wpbel-top-nav-filters-paginate button.current').attr('data-index');
}

function wpbelGetDefaultFilterProfilePosts() {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_default_filter_profile_posts',
            nonce: WPBEL_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                setTimeout(function () {
                    setFilterValues(response.filter_data);
                }, 500);
                wpbelSetPostsList(response)
            }
        },
        error: function () {
        }
    });
}

function setFilterValues(filterData) {
    if (filterData) {
        jQuery('.wpbel-top-nav-status-filter button').removeClass('active');
        jQuery.each(filterData, function (key, values) {
            switch (key) {
                case 'post_status':
                    if (values) {
                        jQuery('.wpbel-top-nav-status-filter button[data-status="' + values + '"]').addClass('active');
                        jQuery('#wpbel-filter-form-post-status').val(values).change();
                    } else {
                        jQuery('.wpbel-top-nav-status-filter button[data-status="all"]').addClass('active');
                    }
                    break;
                case 'taxonomies':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=operator]').val(val.operator).change();
                        }
                        if (val.value) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=value]').val(val.value).change();
                        }
                    });
                    break;
                case 'post_custom_fields':
                    jQuery.each(values, function (key, val) {
                        if (val.operator) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=operator]').val(val.operator).change();
                        }
                        if (val.value) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=value]').val(val.value).change();
                        }
                        if (val.value[0]) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=from]').val(val.value[0]);
                        }
                        if (val.value[1]) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=to]').val(val.value[1]);
                        }
                    });
                    break;
                default:
                    if (values instanceof Object) {
                        if (values.operator) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-name=' + key + ']').find('[data-field=operator]').val(values.operator).change();
                        }
                        if (values.value) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-name=' + key + ']').find('[data-field=value]').val(values.value).change();
                        }
                        if (values.from) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-name=' + key + ']').find('[data-field=from]').val(values.from).change();
                        }
                        if (values.to) {
                            jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-name=' + key + ']').find('[data-field=to]').val(values.to);
                        }
                    } else {
                        jQuery('#wpbel-float-side-modal-filter .wpbel-form-group[data-name=' + key + ']').find('[data-field=value]').val(values);
                    }
                    break;
            }
        });

        wpbelCheckFilterFormChanges();
        wpbelCheckResetFilterButton();
    }
}

function checkedCurrentCategory(id, categoryIds) {
    categoryIds.forEach(function (value) {
        jQuery(id + ' input[value=' + value + ']').prop('checked', 'checked');
    });
}

function wpbelSaveFilterPreset(data, presetName) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_save_filter_preset',
            nonce: WPBEL_DATA.ajax_nonce,
            filter_data: data,
            preset_name: presetName
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                jQuery('#wpbel-modal-filter-profiles').find('tbody').append(response.new_item);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelResetBulkEditForm() {
    jQuery('#wpbel-float-side-modal-bulk-edit input').val('').change();
    jQuery('#wpbel-float-side-modal-bulk-edit select').prop('selectedIndex', 0).change();
    jQuery('#wpbel-float-side-modal-bulk-edit .wpbel-select2').val(null).trigger('change');
}

function wpbelGetProSearchData() {
    let data;
    let taxonomies = [];
    let custom_fields = [];
    let i = 0;
    let j = 0;
    jQuery('.wpbel-form-group[data-type=taxonomy]').each(function () {
        if (jQuery(this).find('select[data-field=value]').val() !== null) {
            taxonomies[i++] = {
                taxonomy: jQuery(this).attr('data-taxonomy'),
                operator: jQuery(this).find('select[data-field=operator]').val(),
                value: jQuery(this).find('select[data-field=value]').val()
            }
        }
    });

    jQuery('.wpbel-form-group[data-type=custom_field]').each(function () {
        if (jQuery(this).find('input').length === 2) {
            let dataFieldType;
            let values = jQuery(this).find('input').map(function () {
                dataFieldType = jQuery(this).attr('data-field-type');
                return jQuery(this).val();
            }).get();
            custom_fields[j++] = {
                type: 'from-to-' + dataFieldType,
                taxonomy: jQuery(this).attr('data-taxonomy'),
                value: values
            }
        } else if (jQuery(this).find('input[data-field=value]').length === 1) {
            if (jQuery(this).find('input[data-field=value]').val() != null) {
                custom_fields[j++] = {
                    type: 'text',
                    taxonomy: jQuery(this).attr('data-taxonomy'),
                    operator: jQuery(this).find('select[data-field=operator]').val(),
                    value: jQuery(this).find('input[data-field=value]').val()
                }
            }
        } else if (jQuery(this).find('select[data-field=value]').length === 1) {
            if (jQuery(this).find('select[data-field=value]').val() != null) {
                custom_fields[j++] = {
                    type: 'select',
                    taxonomy: jQuery(this).attr('data-taxonomy'),
                    value: jQuery(this).find('select[data-field=value]').val()
                }
            }
        }
    });

    data = {
        search_type: 'pro_search',
        post_ids: {
            operator: jQuery('#wpbel-filter-form-post-ids-operator').val(),
            parent_only: (jQuery('#wpbel-filter-form-post-ids-parent-only').prop('checked') === true) ? 'yes' : 'no',
            value: jQuery('#wpbel-filter-form-post-ids').val(),
        },
        post_title: {
            operator: jQuery('#wpbel-filter-form-post-title-operator').val(),
            value: jQuery('#wpbel-filter-form-post-title').val()
        },
        post_content: {
            operator: jQuery('#wpbel-filter-form-post-content-operator').val(),
            value: jQuery('#wpbel-filter-form-post-content').val()
        },
        post_excerpt: {
            operator: jQuery('#wpbel-filter-form-post-excerpt-operator').val(),
            value: jQuery('#wpbel-filter-form-post-excerpt').val()
        },
        post_name: {
            operator: jQuery('#wpbel-filter-form-post-slug-operator').val(),
            value: jQuery('#wpbel-filter-form-post-slug').val()
        },
        post_url: {
            operator: jQuery('#wpbel-filter-form-post-url-operator').val(),
            value: jQuery('#wpbel-filter-form-post-url').val()
        },
        custom_fields: custom_fields,
        taxonomies: taxonomies,
        menu_order: {
            from: jQuery('#wpbel-filter-form-post-menu-order-from').val(),
            to: jQuery('#wpbel-filter-form-post-menu-order-to').val()
        },
        post_date: {
            from: jQuery('#wpbel-filter-form-date-published-from').val(),
            to: jQuery('#wpbel-filter-form-date-published-to').val()
        },
        post_date_gmt: {
            from: jQuery('#wpbel-filter-form-date-published-gmt-from').val(),
            to: jQuery('#wpbel-filter-form-date-published-gmt-to').val()
        },
        post_modified: {
            from: jQuery('#wpbel-filter-form-date-modified-from').val(),
            to: jQuery('#wpbel-filter-form-date-modified-to').val()
        },
        post_modified_gmt: {
            from: jQuery('#wpbel-filter-form-date-modified-gmt-from').val(),
            to: jQuery('#wpbel-filter-form-date-modified-gmt-to').val()
        },
        post_status: jQuery('#wpbel-filter-form-post-status').val(),
        comment_status: jQuery('#wpbel-filter-form-comment-status').val(),
        ping_status: jQuery('#wpbel-filter-form-ping-status').val(),
        sticky: jQuery('#wpbel-filter-form-post-sticky').val(),
        post_author: jQuery('#wpbel-filter-form-author').val(),
    };
    return data;
}

function wpbelPostsBulkEdit(postIDs, data, filterData) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_posts_bulk_edit',
            nonce: WPBEL_DATA.ajax_nonce,
            post_ids: postIDs,
            new_data: data,
            current_page: wpbelGetCurrentPage(),
            filter_data: filterData
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts(response.post_ids);
                wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wpbel-history-items tbody').html(response.history_items);
                if (jQuery.fn.datepicker) {
                    jQuery('.wpbel-datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
                }
                let wpbelTextEditors = jQuery('input[name="wpbel-editors[]"]');
                if (wpbelTextEditors.length > 0) {
                    wpbelTextEditors.each(function () {
                        tinymce.execCommand('mceRemoveEditor', false, jQuery(this).val());
                        tinymce.execCommand('mceAddEditor', false, jQuery(this).val());
                    })
                }
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelUpdatePostAttribute(post_ids, field, data, reload) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_update_post_attribute',
            nonce: WPBEL_DATA.ajax_nonce,
            post_ids: post_ids,
            field: field,
            values: data
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    wpbelReloadPosts(post_ids);
                } else {
                    wpbelLoadingSuccess();
                }
                wpbelCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wpbel-history-items tbody').html(response.history_items);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelGetTaxonomyParentSelectBox(taxonomy) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_taxonomy_parent_select_box',
            nonce: WPBEL_DATA.ajax_nonce,
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-new-post-taxonomy-parent').html(response.options);
            }
        },
        error: function () {
        }
    });
}

function getAttributeValues(name, target) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_attribute_values',
            nonce: WPBEL_DATA.ajax_nonce,
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
                jQuery('.wpbel-select2-ajax').select2();
            } else {

            }
        },
        error: function () {

        }
    });
}

function getAttributeValuesForDelete(name, target) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_attribute_values_for_delete',
            nonce: WPBEL_DATA.ajax_nonce,
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