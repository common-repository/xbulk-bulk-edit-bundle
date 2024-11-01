"use strict";

function wbeblOpenTab(item) {
    let wbeblTabItem = item;
    let wbeblParentContent = wbeblTabItem.closest(".wbebl-tabs-list");
    let wbeblParentContentID = wbeblParentContent.attr("data-content-id");
    let wbeblDataBox = wbeblTabItem.attr("data-content");
    wbeblParentContent.find("li a.selected").removeClass("selected");
    wbeblTabItem.addClass("selected");
    jQuery("#" + wbeblParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wbeblParentContentID + " div[data-content=" + wbeblDataBox + "]").addClass("selected");
    if (item.attr("data-type") === "main-tab") {
        wbeblFilterFormClose();
    }
}

function wbeblFixModalHeight(modal) {
    let footerHeight = 0;
    let contentHeight = modal.find(".wbebl-modal-content").height();
    let titleHeight = modal.find(".wbebl-modal-title").height();
    if (modal.find(".wbebl-modal-footer").length > 0) {
        footerHeight = modal.find(".wbebl-modal-footer").height() + 30;
    }
    let height = parseInt(contentHeight) - parseInt(titleHeight);
    if (modal.find('.wbebl-modal-top-search').length > 0) {
        height -= parseInt(modal.find('.wbebl-modal-top-search').height() + 40);
    }

    modal.find(".wbebl-modal-content").css({
        "height": contentHeight + footerHeight
    });
    modal.find(".wbebl-modal-body").css({
        "height": height
    });
    modal.find(".wbebl-modal-box").css({
        "height": contentHeight + footerHeight
    });
}

function wbeblCloseModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    let lastModalOpened = jQuery('#wbebl-last-modal-opened');
    let modal = jQuery(lastModalOpened.val());
    if (lastModalOpened.val() !== '') {
        modal.find(' .wbebl-modal-box').fadeOut();
        modal.fadeOut();
        lastModalOpened.val('');
    } else {
        jQuery('.wbebl-modal-box').fadeOut();
        jQuery('.wbebl-modal').fadeOut();
    }

    setTimeout(function() {
        modal.find('.wbebl-modal-box').css({
            height: 'auto',
            "max-height": '80%'
        });
        modal.find('.wbebl-modal-body').css({
            height: 'auto',
            "max-height": '90%'
        });
        modal.find('.wbebl-modal-content').css({
            height: 'auto',
            "max-height": '92%'
        });
    }, 400);
}

function wbeblReInitColorPicker() {
    if (jQuery('.wbebl-color-picker').length > 0) {
        jQuery('.wbebl-color-picker').wpColorPicker();
    }
    if (jQuery('.wbebl-color-picker-field').length > 0) {
        jQuery('.wbebl-color-picker-field').wpColorPicker();
    }
}

function wbeblReInitDatePicker() {
    if (jQuery.fn.datetimepicker) {
        jQuery('.wbebl-datepicker-with-dash').datetimepicker('destroy');
        jQuery('.wbebl-datepicker').datetimepicker('destroy');
        jQuery('.wbebl-timepicker').datetimepicker('destroy');
        jQuery('.wbebl-datetimepicker').datetimepicker('destroy');

        jQuery('.wbebl-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y/m/d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wbebl-datepicker-with-dash').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wbebl-timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wbebl-datetimepicker').datetimepicker({
            format: 'Y/m/d H:i',
            scrollMonth: false,
            scrollInput: false
        });
    }

}

function wbeblPaginationLoadingStart() {
    jQuery('.wbebl-pagination-loading').show();
}

function wbeblPaginationLoadingEnd() {
    jQuery('.wbebl-pagination-loading').hide();
}

function wbeblLoadingStart() {
    jQuery('#wbebl-loading').removeClass('wbebl-loading-error').removeClass('wbebl-loading-success').text('Loading ...').slideDown(300);
}

function wbeblLoadingSuccess(message = 'Success !') {
    jQuery('#wbebl-loading').removeClass('wbebl-loading-error').addClass('wbebl-loading-success').text(message).delay(1500).slideUp(200);
}

function wbeblLoadingError(message = 'Error !') {
    jQuery('#wbebl-loading').removeClass('wbebl-loading-success').addClass('wbebl-loading-error').text(message).delay(1500).slideUp(200);
}

function wbeblSetColorPickerTitle() {
    jQuery('.wbebl-column-manager-right-item .wp-picker-container').each(function() {
        let title = jQuery(this).find('.wbebl-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wbeblSetTipsyTooltip();
    });
}

function wbeblFilterFormClose() {
    if (jQuery('#wbebl-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.wbebl-filter-form-icon').addClass('lni-chevron-down').removeClass('lni lni-chevron-up');
        jQuery('#wbebl-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function wbeblFilterFormOpen() {
    if (jQuery('#wbebl-filter-form-content').attr('data-visibility') === 'hidden') {
        jQuery('.wbebl-filter-form-icon').removeClass('lni lni-chevron-down').addClass('lni lni-chevron-up');
        jQuery('#wbebl-filter-form-content').slideDown(200).attr('data-visibility', 'visible');
    }
}

function wbeblSetTipsyTooltip() {
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

function wbeblCheckUndoRedoStatus(reverted, history) {
    if (reverted) {
        wbeblEnableRedo();
    } else {
        wbeblDisableRedo();
    }
    if (history) {
        wbeblEnableUndo();
    } else {
        wbeblDisableUndo();
    }
}

function wbeblDisableUndo() {
    jQuery('#wbebl-bulk-edit-undo').attr('disabled', 'disabled');
}

function wbeblEnableUndo() {
    jQuery('#wbebl-bulk-edit-undo').prop('disabled', false);
}

function wbeblDisableRedo() {
    jQuery('#wbebl-bulk-edit-redo').attr('disabled', 'disabled');
}

function wbeblEnableRedo() {
    jQuery('#wbebl-bulk-edit-redo').prop('disabled', false);
}

function wbeblHideSelectionTools() {
    jQuery('.wbebl-bulk-edit-form-selection-tools').hide();
    jQuery('#wbebl-bulk-edit-trash-restore').hide();
}

function wbeblShowSelectionTools() {
    jQuery('.wbebl-bulk-edit-form-selection-tools').show();
    jQuery('#wbebl-bulk-edit-trash-restore').show();
}

function wbeblSetColorPickerTitle() {
    jQuery('.wbebl-column-manager-right-item .wp-picker-container').each(function() {
        let title = jQuery(this).find('.wbebl-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wbeblSetTipsyTooltip();
    });
}

function wbeblColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WBEBL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wbebl_column_manager_add_field',
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function(response) {
            jQuery('.wbebl-box-loading').hide();
            jQuery('.wbebl-column-manager-added-fields[data-action=' + action + '] .items').append(response);
            fieldName.forEach(function(name) {
                jQuery('.wbebl-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            wbeblReInitColorPicker();
            jQuery('.wbebl-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.wbebl-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function() {
                wbeblSetColorPickerTitle();
            }, 250);
        },
        error: function() {}
    })
}

function wbeblAddMetaKeysManual(meta_key_name) {
    wbeblLoadingStart();
    jQuery.ajax({
        url: WBEBL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wbebl_add_meta_keys_manual',
            meta_key_name: meta_key_name,
        },
        success: function(response) {
            jQuery('#wbebl-meta-fields-items').append(response);
            wbeblLoadingSuccess();
        },
        error: function() {
            wbeblLoadingError();
        }
    })
}

function wbeblAddACFMetaField(field_name, field_label, field_type) {
    wbeblLoadingStart();
    jQuery.ajax({
        url: WBEBL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wbebl_add_acf_meta_field',
            field_name: field_name,
            field_label: field_label,
            field_type: field_type
        },
        success: function(response) {
            jQuery('#wbebl-meta-fields-items').append(response);
            wbeblLoadingSuccess();
        },
        error: function() {
            wbeblLoadingError();
        }
    })
}

function wbeblCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#wbebl-filter-form-content [data-field=value]').each(function() {
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
    jQuery('#wbebl-filter-form-content [data-field=from]').each(function() {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wbebl-filter-form-content [data-field=to]').each(function() {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery('#filter-form-changed').val(isChanged);

    if (isChanged === true) {
        jQuery('#wbebl-bulk-edit-reset-filter').show();
    } else {
        jQuery('.wbebl-top-nav-status-filter a[data-status="all"]').addClass('active');
    }
}

function wbeblGetCheckedItem() {
    let itemIds;
    let itemsChecked = jQuery("input.wbebl-check-item:checkbox:checked");
    if (itemsChecked.length > 0) {
        itemIds = itemsChecked.map(function(i) {
            return jQuery(this).val();
        }).get();
    }

    return itemIds;
}

function wbeblGetTableCount(countPerPage, currentPage, total) {
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = parseInt(showingTo - countPerPage) + 1;
    showingTo = (showingTo < total) ? showingTo : total;
    return "Showing " + showingFrom + " to " + showingTo + " of " + total + " entries";
}