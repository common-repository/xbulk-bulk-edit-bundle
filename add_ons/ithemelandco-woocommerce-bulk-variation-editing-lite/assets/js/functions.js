"use strict";

var iwbvelOpenFullScreenIcon = '<i class="iwbvel-icon-enlarge"></i>';
var iwbvelCloseFullScreenIcon = '<i class="iwbvel-icon-shrink"></i>';

function openFullscreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

function iwbvelDataTableFixSize() {
    jQuery('#iwbvel-main').css({
        top: jQuery('#wpadminbar').height() + 'px',
        "padding-left": (jQuery('#adminmenu:visible').length) ? jQuery('#adminmenu').width() + 'px' : 0
    });

    jQuery('#iwbvel-loading').css({
        top: jQuery('#wpadminbar').height() + 'px',
    });

    let height = parseInt(jQuery(window).height()) - parseInt(jQuery('#iwbvel-header').height() + 85);

    jQuery('.iwbvel-table').css({
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

function iwbvelFullscreenHandler() {
    if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        jQuery('#iwbvel-full-screen').html(iwbvelOpenFullScreenIcon).attr('title', 'Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').show();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
    } else {
        jQuery('#iwbvel-full-screen').html(iwbvelCloseFullScreenIcon).attr('title', 'Exit Full screen');
        jQuery('#adminmenuback, #adminmenuwrap').hide();
        jQuery('#wpcontent, #wpfooter').css({ "margin-left": 0 });
    }

    iwbvelDataTableFixSize();
}

function iwbvelOpenTab(item) {
    let iwbvelTabItem = item;
    let iwbvelParentContent = iwbvelTabItem.closest(".iwbvel-tabs-list");
    let iwbvelParentContentID = iwbvelParentContent.attr("data-content-id");
    let iwbvelDataBox = iwbvelTabItem.attr("data-content");
    iwbvelParentContent.find("li button.selected").removeClass("selected");
    if (iwbvelTabItem.closest('.iwbvel-sub-tab').length > 0) {
        iwbvelTabItem.closest('li.iwbvel-has-sub-tab').find('button').first().addClass("selected");
    } else {
        iwbvelTabItem.addClass("selected");
    }

    if (item.closest('.iwbvel-tabs-list').attr('data-content-id') && item.closest('.iwbvel-tabs-list').attr('data-content-id') == 'iwbvel-main-tabs-contents') {
        jQuery('.iwbvel-tabs-list[data-content-id="iwbvel-main-tabs-contents"] li[data-depend] button').not('.iwbvel-tab-item').addClass('disabled');
        jQuery('.iwbvel-tabs-list[data-content-id="iwbvel-main-tabs-contents"] li[data-depend="' + iwbvelDataBox + '"] button').removeClass('disabled');
    }

    jQuery("#" + iwbvelParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + iwbvelParentContentID + " div[data-content=" + iwbvelDataBox + "]").addClass("selected");

    if (item.attr("data-type") === "main-tab") {
        iwbvelFilterFormClose();
    }
}

function iwbvelFixModalHeight(modal) {
    if (!modal.attr('data-height-fixed') || modal.attr('data-height-fixed') != 'true') {
        let footerHeight = 0;
        let contentHeight = modal.find(".iwbvel-modal-content").height();
        let titleHeight = modal.find(".iwbvel-modal-title").height();
        if (modal.find(".iwbvel-modal-footer").length > 0) {
            footerHeight = modal.find(".iwbvel-modal-footer").height();
        }

        let modalMargin = parseInt((parseInt(jQuery('body').height()) * 20) / 100);
        let bodyHeight = (modal.find(".iwbvel-modal-body-content").length) ? parseInt(modal.find(".iwbvel-modal-body-content").height() + 30) : contentHeight;
        let bodyMaxHeight = parseInt(jQuery('body').height()) - (titleHeight + footerHeight + modalMargin);
        if (modal.find('.iwbvel-modal-top-search').length > 0) {
            bodyHeight += parseInt(modal.find('.iwbvel-modal-top-search').height() + 30);
            bodyMaxHeight -= parseInt(modal.find('.iwbvel-modal-top-search').height());
        }

        modal.find(".iwbvel-modal-content").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.find(".iwbvel-modal-body").css({
            "height": parseInt(bodyHeight) + 'px',
            'max-height': parseInt(bodyMaxHeight) + 'px'
        });
        modal.find(".iwbvel-modal-box").css({
            "height": parseInt(titleHeight + footerHeight + bodyHeight) + 'px'
        });
        modal.attr('data-height-fixed', 'true');
    }
}

function iwbvelOpenFloatSideModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn(20);
    modal.find(".iwbvel-float-side-modal-box").animate({
        right: 0
    }, 180);
}

function iwbvelCloseFloatSideModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    jQuery('.iwbvel-float-side-modal-box').animate({
        right: "-80%"
    }, 180);
    jQuery('.iwbvel-float-side-modal').fadeOut(200);
}

function iwbvelCloseModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery('body').removeClass('_winvoice-modal-open');
    jQuery('._winvoice-modal-backdrop').remove();

    let lastModalOpened = jQuery('#iwbvel-last-modal-opened');
    let modal = jQuery(lastModalOpened.val());
    if (lastModalOpened.val() !== '') {
        modal.find(' .iwbvel-modal-box').fadeOut();
        modal.fadeOut();
        lastModalOpened.val('');
    } else {
        let lastModal = jQuery('.iwbvel-modal:visible').last();
        lastModal.find('.iwbvel-modal-box').fadeOut();
        lastModal.fadeOut();
    }

    setTimeout(function () {
        modal.find('.iwbvel-modal-box').css({
            height: 'auto',
            "max-height": '80%'
        });
        modal.find('.iwbvel-modal-body').css({
            height: 'auto',
            "max-height": '90%'
        });
        modal.find('.iwbvel-modal-content').css({
            height: 'auto',
            "max-height": '92%'
        });
    }, 400);
}

function iwbvelOpenModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn();
    modal.find(".iwbvel-modal-box").fadeIn();
    jQuery("#iwbvel-last-modal-opened").val(jQuery(this).attr("data-target"));

    // set height for modal body
    setTimeout(function () {
        iwbvelFixModalHeight(modal);
    }, 150)
}

function iwbvelReInitColorPicker() {
    if (jQuery('.iwbvel-color-picker').length > 0) {
        jQuery('.iwbvel-color-picker').wpColorPicker();
    }
    if (jQuery('.iwbvel-color-picker-field').length > 0) {
        jQuery('.iwbvel-color-picker-field').wpColorPicker();
    }
}

function iwbvelReInitDatePicker() {
    if (jQuery.fn.datetimepicker) {
        jQuery('.iwbvel-datepicker-with-dash').datetimepicker('destroy');
        jQuery('.iwbvel-datepicker').datetimepicker('destroy');
        jQuery('.iwbvel-timepicker').datetimepicker('destroy');
        jQuery('.iwbvel-datetimepicker').datetimepicker('destroy');

        jQuery('.iwbvel-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y/m/d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.iwbvel-datepicker-with-dash').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.iwbvel-timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.iwbvel-datetimepicker').datetimepicker({
            format: 'Y/m/d H:i',
            scrollMonth: false,
            scrollInput: false
        });
    }

}

function iwbvelPaginationLoadingStart() {
    jQuery('.iwbvel-pagination-loading').show();
}

function iwbvelPaginationLoadingEnd() {
    jQuery('.iwbvel-pagination-loading').hide();
}

function iwbvelLoadingStart() {
    jQuery('#iwbvel-loading').removeClass('iwbvel-loading-error').removeClass('iwbvel-loading-success').text('Loading ...').slideDown(300);
}

function iwbvelLoadingSuccess(message = 'Success !') {
    jQuery('#iwbvel-loading').removeClass('iwbvel-loading-error').addClass('iwbvel-loading-success').text(message).delay(1500).slideUp(200);
}

function iwbvelLoadingError(message = 'Error !') {
    jQuery('#iwbvel-loading').removeClass('iwbvel-loading-success').addClass('iwbvel-loading-error').text(message).delay(1500).slideUp(200);
}

function iwbvelSetColorPickerTitle() {
    jQuery('.iwbvel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.iwbvel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        iwbvelSetTipsyTooltip();
    });
}

function iwbvelFilterFormClose() {
    if (jQuery('#iwbvel-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.iwbvel-filter-form-icon').addClass('iwbvel-icon-chevron-down').removeClass('iwbvel-icon-chevron-up');
        jQuery('#iwbvel-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function iwbvelSetTipsyTooltip() {
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

function iwbvelCheckUndoRedoStatus(reverted, history) {
    if (reverted) {
        iwbvelEnableRedo();
    } else {
        iwbvelDisableRedo();
    }
    if (history) {
        iwbvelEnableUndo();
    } else {
        iwbvelDisableUndo();
    }
}

function iwbvelDisableUndo() {
    jQuery('#iwbvel-bulk-edit-undo').attr('disabled', 'disabled');
}

function iwbvelEnableUndo() {
    jQuery('#iwbvel-bulk-edit-undo').prop('disabled', false);
}

function iwbvelDisableRedo() {
    jQuery('#iwbvel-bulk-edit-redo').attr('disabled', 'disabled');
}

function iwbvelEnableRedo() {
    jQuery('#iwbvel-bulk-edit-redo').prop('disabled', false);
}

function iwbvelHideSelectionTools() {
    jQuery('.iwbvel-bulk-edit-form-selection-tools').hide();
    jQuery('#iwbvel-bulk-edit-trash-restore').hide();
}

function iwbvelShowSelectionTools() {
    jQuery('.iwbvel-bulk-edit-form-selection-tools').show();
    jQuery('#iwbvel-bulk-edit-trash-restore').show();
}

function iwbvelSetColorPickerTitle() {
    jQuery('.iwbvel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.iwbvel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        iwbvelSetTipsyTooltip();
    });
}

function iwbvelColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'iwbvel_column_manager_add_field',
            nonce: IWBVEL_DATA.nonce,
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function (response) {
            jQuery('.iwbvel-box-loading').hide();
            jQuery('.iwbvel-column-manager-added-fields[data-action=' + action + '] .items').append(response);
            fieldName.forEach(function (name) {
                jQuery('.iwbvel-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            iwbvelReInitColorPicker();
            jQuery('.iwbvel-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.iwbvel-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function () {
                iwbvelSetColorPickerTitle();
            }, 250);
        },
        error: function () {
        }
    })
}

function iwbvelAddACFMetaField(field_name, field_label, field_type) {
    iwbvelLoadingStart();
    jQuery.ajax({
        url: IWBVEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'iwbvel_add_acf_meta_field',
            nonce: IWBVEL_DATA.nonce,
            field_name: field_name,
            field_label: field_label,
            field_type: field_type
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

function iwbvelCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#iwbvel-filter-form-content [data-field="value"]').each(function () {
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
    jQuery('#iwbvel-filter-form-content [data-field="from"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#iwbvel-filter-form-content [data-field="to"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery('#filter-form-changed').val(isChanged);

    if (isChanged === true) {
        jQuery('#iwbvel-bulk-edit-reset-filter').show();
    } else {
        jQuery('.iwbvel-top-nav-status-filter button[data-status="all"]').addClass('active');
    }
}

function iwbvelGetCheckedItem() {
    let itemIds;
    let itemsChecked = jQuery("input.iwbvel-check-item:checkbox:checked");
    if (itemsChecked.length > 0) {
        itemIds = itemsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }

    return itemIds;
}

function iwbvelGetTableCount(countPerPage, currentPage, total) {
    currentPage = (currentPage) ? currentPage : 1;
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = (total > 0) ? parseInt(showingTo - countPerPage) + 1 : 0;
    showingTo = (showingTo < total) ? showingTo : total;
    return "Showing " + showingFrom + " to " + showingTo + " of " + total + " entries";
}