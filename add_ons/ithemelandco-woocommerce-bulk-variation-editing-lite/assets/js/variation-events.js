jQuery(document).ready(function ($) {
    "use strict";

    $(document).on("click", ".iwbvel-bulk-edit-variations", function () {
        let productIds = iwbvelGetProductsChecked();
        if (productIds.length > 1) {
            swal({
                title: "It's not possible to manage variation for multiple products on Lite version!",
                type: "warning"
            });

            return false;
        } else {
            iwbvelOpenVariationsFloatSide();
        }
    });

    $(document).on("click", ".iwbvel-combine-attributes-generate-button", function () {
        $(this).closest('.iwbvel-combine-attributes-button').find('.active .iwbvel-combine-attributes-sub-buttons').slideToggle(150);
    });

    $(document).on("click", "body", function (e) {
        if (!$(e.target).closest('.iwbvel-combine-attributes-button').length && !$(e.target).hasClass('iwbvel-combine-attributes-button')) {
            $('.iwbvel-combine-attributes-sub-buttons').slideUp(150);
        }

        if (!$(e.target).closest('.iwbvel-variations-bulk-actions-button').length && !$(e.target).hasClass('iwbvel-variations-bulk-actions-button')) {
            $('.iwbvel-variations-bulk-actions-sub-buttons').slideUp(150);
        }
    });

    $(document).on("click", ".iwbvel-product-attribute-select-all", function (e) {
        let attributeElement = $(this).closest('.iwbvel-product-attribute-item');

        if ($(this).prop('checked') == true) {
            if (attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked') === true) {
                iwbvelSelectAllAttributeTerms(attributeElement.attr('data-name'), attributeElement.attr('data-label'));
            }
            attributeElement.find('.iwbvel-product-attribute-term-item').prop('checked', true).change();
        } else {
            $('.iwbvel-attribute-selected-term-item[data-attribute="' + attributeElement.attr('data-name') + '"][data-term="iwbvel-all-terms"]').find('.iwbvel-attribute-selected-term-remove').trigger('click');
            attributeElement.find('.iwbvel-product-attribute-term-item').prop('checked', false).change();
        }
    });

    $(document).on("change", ".iwbvel-product-attribute-select-all", function (e) {
        let attributeElement = $(this).closest('.iwbvel-product-attribute-item');

        if ($(this).prop('checked') == true) {
            attributeElement.find('.iwbvel-product-attribute-select-all-label').addClass('checked');
            attributeElement.find('.iwbvel-attribute-used-for-variations').prop('checked', true).change();
            attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked', true).change();
            attributeElement.find('.iwbvel-attribute-item-bottom-items').removeClass('disabled');
        } else {
            attributeElement.find('.iwbvel-product-attribute-select-all-label').removeClass('checked');
        }
    });

    $(document).on("click", ".iwbvel-product-attribute-term-item", function (e) {
        let $this = $(this);
        let attributeElement = $(this).closest('.iwbvel-product-attribute-item');

        if (attributeElement.find('.iwbvel-product-attribute-term-item').length == attributeElement.find('.iwbvel-product-attribute-term-item:checked').length) {
            attributeElement.find('.iwbvel-product-attribute-select-all').prop('checked', true).change();
            if (attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked') === true) {
                iwbvelSelectAllAttributeTerms(attributeElement.attr('data-name'), attributeElement.attr('data-label'));
            }
        } else {
            attributeElement.find('.iwbvel-product-attribute-select-all').prop('checked', false).change();

            if ($this.prop('checked') == true) {
                attributeElement.find('.iwbvel-attribute-item-bottom-items').removeClass('disabled');
                attributeElement.find('.iwbvel-attribute-used-for-variations').prop('checked', true).change();
                attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked', true).trigger('iwbvel-attribute-visible-on-the-product-page-checked');
                iwbvelCombineBoxAddNewTerm({
                    "attributeName": attributeElement.attr('data-name'),
                    "attributeLabel": attributeElement.attr('data-label'),
                    "termLabel": $this.attr('data-term-name'),
                    "termId": $this.val(),
                });
            } else {
                if (!attributeElement.find('.iwbvel-product-attribute-term-item:checked').length) {
                    attributeElement.find('.iwbvel-attribute-item-bottom-items').addClass('disabled');
                    attributeElement.find('.iwbvel-attribute-used-for-variations').prop('checked', false).change();
                    attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked', false).trigger('iwbve-attribute-visible-on-the-product-page-checked');
                }

                $('ul.iwbvel-combine-attributes-items').find('li[data-attribute="' + attributeElement.attr('data-name') + '"][data-term="' + $this.val() + '"]').remove();

                let allTerms = $('ul.iwbvel-combine-attributes-items').find('li[data-attribute="' + attributeElement.attr('data-name') + '"][data-term="iwbvel-all-terms"]');
                if (allTerms.length > 0) {
                    if (attributeElement.find('.iwbvel-product-attribute-term-item:checked').length > 0 && attributeElement.find('.iwbvel-attribute-visible-on-the-product-page').prop('checked') === true) {
                        attributeElement.find('.iwbvel-product-attribute-term-item:checked').each(function () {
                            let termInputElement = $(this);
                            iwbvelCombineBoxAddNewTerm({
                                "attributeName": attributeElement.attr('data-name'),
                                "attributeLabel": attributeElement.attr('data-label'),
                                "termLabel": termInputElement.attr('data-term-name'),
                                "termId": termInputElement.val(),
                            });
                        })
                    }
                    allTerms.remove();
                }
            }

            setTimeout(function () {
                iwbvelSetGenerateButtonStatus();
                iwbvelCheckAvailabilitySwapTerms();
            }, 200);
        }
    });

    $(document).on('iwbvel-attribute-visible-on-the-product-page-checked', '.iwbvel-attribute-visible-on-the-product-page', function (e) {
        if ($(this).prop('checked') == true) {
            $(this).closest('label').addClass('checked');
        } else {
            $(this).closest('label').removeClass('checked');
        }
    });

    $(document).on('change', '.iwbvel-attribute-used-for-variations', function (e) {
        if ($(this).prop('checked') == true) {
            if ($(this).closest('.iwbvel-product-attribute-item').find('.iwbvel-product-attribute-term-item:checkbox:checked').length > 0) {
                $(this).closest('.iwbvel-attribute-item-bottom-items').removeClass('disabled');
                $(this).closest('label').addClass('checked');
            } else {
                $(this).closest('.iwbvel-attribute-item-bottom-items').addClass('disabled');
                $(this).prop('checked', false).change();
            }
        } else {
            $(this).closest('label').removeClass('checked');
        }
    });

    $(document).on('change', '.iwbvel-attribute-visible-on-the-product-page', function (e) {
        let attributeElement = $(this).closest('.iwbvel-product-attribute-item');
        let terms = attributeElement.find('.iwbvel-product-attribute-term-item:checkbox:checked');

        if ($(this).prop('checked') == true) {
            if ($(this).closest('.iwbvel-product-attribute-item').find('.iwbvel-product-attribute-term-item:checkbox:checked').length > 0) {
                $(this).closest('label').addClass('checked');
                $('.iwbvel-combine-attributes-generate-button').attr('disabled', false);

                if (attributeElement.find('.iwbvel-product-attribute-term-item').length == attributeElement.find('.iwbvel-product-attribute-term-item:checked').length) {
                    attributeElement.find('.iwbvel-product-attribute-select-all').prop('checked', true);
                    iwbvelSelectAllAttributeTerms(attributeElement.attr('data-name'), attributeElement.attr('data-label'));
                } else {
                    if (terms.length > 0) {
                        terms.each(function () {
                            let termInputElement = $(this);
                            iwbvelCombineBoxAddNewTerm({
                                "attributeName": attributeElement.attr('data-name'),
                                "attributeLabel": attributeElement.attr('data-label'),
                                "termLabel": termInputElement.attr('data-term-name'),
                                "termId": termInputElement.val(),
                            });
                        })
                    }
                }
            } else {
                $(this).prop('checked', false).change();
            }
        } else {
            $(this).closest('label').removeClass('checked');
            if (!$('.iwbvel-combine-attributes-items').find('li').length) {
                $('.iwbvel-combine-attributes-generate-button').attr('disabled', true);
            }

            let all = $('ul.iwbvel-combine-attributes-items').find('li[data-attribute="' + attributeElement.attr('data-name') + '"][data-term="iwbvel-all-terms"]');
            if (all.length) {
                attributeElement.find('.iwbvel-product-attribute-select-all').prop('checked', false);
                all.remove();
            } else {
                if (terms.length > 0) {
                    terms.each(function () {
                        $('ul.iwbvel-combine-attributes-items').find('li[data-attribute="' + attributeElement.attr('data-name') + '"][data-term="' + $(this).val() + '"]').remove();
                    });
                }
            }
        }
    });

    $(document).on('click', '.iwbvel-product-attribute-item-label', function (e) {
        if (!$(e.target).hasClass('iwbvel-product-attribute-select-all-button') && !$(e.target).hasClass('iwbvel-product-attribute-select-all-label') && !$(e.target).hasClass('iwbvel-product-attribute-select-all') && !$(e.target).hasClass('iwbvel-product-attribute-add-new-button') && !$(e.target).closest('.iwbvel-product-attribute-add-new-button').length) {
            $(this).closest('.iwbvel-product-attribute-item').find('.iwbvel-product-attribute-item-middle-container').slideToggle(200);
        }
    });

    $(document).on('click', '.iwbvel-attribute-selected-term-remove', function () {
        let selectedTerm = $(this).closest('.iwbvel-attribute-selected-term-item');
        iwbvelSelectedTermRemove(selectedTerm.attr('data-attribute'), selectedTerm.attr('data-term'));
    });

    $(document).on('click', '.iwbvel-variations-table-select-all-button', function () {
        if ($(this).prop('checked') == true) {
            $('#iwbvel-variations-table tbody input.iwbvel-variation-row-select').prop('checked', true).change();
        } else {
            $('#iwbvel-variations-table tbody input.iwbvel-variation-row-select').prop('checked', false).change();
        }
    });

    $(document).on('click', '.iwbvel-variation-row-select', function () {
        if ($(this).prop('checked') == true) {
            if ($('#iwbvel-variations-table tbody input.iwbvel-variation-row-select').length == $('#iwbvel-variations-table tbody input.iwbvel-variation-row-select:checked').length) {
                $('.iwbvel-variations-table-select-all-button').prop('checked', true);
            } else {
                $('.iwbvel-variations-table-select-all-button').prop('checked', false);
            }
        } else {
            $('.iwbvel-variations-table-select-all-button').prop('checked', false);
        }
    });

    $(document).on('click', '.iwbvel-product-attribute-add-new-button', function () {
        $('#iwbvel-variations-new-attribute-term-name').val('').change();
        $('#iwbvel-variations-new-attribute-term-modal').find('.iwbvel-new-attribute-button').attr('data-attribute', $(this).closest('.iwbvel-product-attribute-item').attr('data-name'));
    });

    $(document).on('click', '.iwbvel-variations-attach-add-term', function () {
        $('#iwbvel-variations-new-attribute-term-name').val('').change();
        $('#iwbvel-variations-new-attribute-term-modal').find('.iwbvel-new-attribute-button').attr('data-attribute', $('#iwbvel-variations-attach-attribute-selector').val().replace('pa_', ''));
    });

    $(document).on('click', '.iwbvel-variations-individual-variation-button', function () {
        $('.iwbvel-individual-variation-add-button').attr('data-type', $(this).attr('data-type'));
        $('.iwbvel-variations-individual-variation-loading').show();

        let items = '';
        $('.iwbvel-product-attribute-item').each(function () {
            if ($(this).find('.iwbvel-attribute-used-for-variations').prop('checked') === true && $(this).find('.iwbvel-attribute-visible-on-the-product-page').prop('checked') === true) {
                if ($(this).find('input.iwbvel-product-attribute-term-item:checked').length > 0) {
                    items += '<div class="iwbvel-variations-individual-variation-item">';
                    items += '<label>' + $(this).attr("data-label") + '</label>';
                    items += '<select data-name="' + $(this).attr("data-name") + '" data-label="' + $(this).attr("data-label") + '">';
                    $(this).find('input.iwbvel-product-attribute-term-item:checked').each(function () {
                        items += '<option value="' + $(this).attr('data-term-slug') + '">' + $(this).attr('data-term-name') + '</option>';
                    })
                    items += '</select>';
                    items += '</div>';
                }
            }
        }).promise().done(function () {
            $('#iwbvel-variations-individual-variation-items').html(items).ready(function () {
                $('.iwbvel-variations-individual-variation-loading').hide();
            });
        });
    });

    $(document).on('click', '.iwbvel-new-attribute-button', function () {
        $('.iwbvel-variations-attach-attribute .iwbvel-variations-term-loading').show();
        $('.iwbvel-product-attribute-item[data-name="' + $(this).attr('data-attribute') + '"]').find('.iwbvel-variation-add-new-term-loading').show();

        iwbvelAddNewTerm({
            attributeName: $(this).attr('data-attribute'),
            termName: $('#iwbvel-variations-new-attribute-term-name').val(),
        });
    });

    $(document).on('click', '.iwbvel-variations-delete-row', function () {
        let variationId = $(this).val();
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
                iwbvelDeleteVariationsByIds([variationId]);
            }
        });
    });

    $(document).on("click", ".iwbvel-variations-bulk-actions-remove-image", function () {
        $(this).closest("div").remove();
        $(".iwbvel-variations-bulk-actions-image").val("");
    });

    $(document).on('change', '#iwbvel-variations-bulk-actions-virtual', function () {
        if ($(this).val() == 'yes') {
            $('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-actions"] .iwbvel-tab-item[data-content="shipping"]').hide();
        } else {
            $('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-actions"] .iwbvel-tab-item[data-content="shipping"]').show();
        }
    });

    $(document).on('change', '#iwbvel-variations-bulk-actions-downloadable', function () {
        if ($(this).val() == 'yes') {
            $('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-actions"] .iwbvel-tab-item[data-content="downloadable"]').show();
        } else {
            $('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-actions"] .iwbvel-tab-item[data-content="downloadable"]').hide();
        }
    });

    $(document).on('change', '#iwbvel-variations-bulk-actions-manage-stock', function () {
        if ($(this).val() == 'yes') {
            $('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-actions"] .iwbvel-tab-item[data-content="manage_stock"]').show();
        } else {
            $('.iwbvel-tabs-list[data-content-id="iwbvel-variations-bulk-actions"] .iwbvel-tab-item[data-content="manage_stock"]').hide();
        }
    });

    $(document).on('click', '.iwbvel-variation-bulk-actions-file-item-remove-button', function () {
        $(this).closest('.iwbvel-variation-bulk-actions-file-item').remove();
    });

    $(document).on('click', '.iwbvel-variation-bulk-actions-add-file', function () {
        $(this).closest('div').find('.iwbvel-variation-bulk-actions-files').append(IWBVEL_VARIATION_DATA.html.file_item);
    });

    $(document).on('click', '.iwbvel-variations-bulk-actions-button', function () {
        $('.iwbvel-variations-bulk-actions-sub-buttons').slideToggle(150);
    });

    $(document).on('click', '.iwbvel-variations-bulk-actions-all-button', function () {
        $('.iwbvel-variation-row-select').prop('checked', false).change();
        $('.iwbvel-variations-bulk-actions-sub-buttons').slideUp(150);
        iwbvelOpenModal('#iwbvel-variations-bulk-actions-modal');
        iwbvelResetBulkActionsForm();
        if ($('.iwbvel-variation-row-select:visible:checked').length == 1) {
            iwbvelGetVariationData($('.iwbvel-variation-row-select:visible:checked').val());
        }

        $('.iwbvel-variations-bulk-action-do-bulk-button').attr('data-type', 'all');
    });

    $(document).on('click', '.iwbvel-variations-bulk-actions-selected-button', function () {
        if (!$('.iwbvel-variation-row-select:visible:checked').length) {
            swal({
                title: "Please select one variation",
                type: "warning"
            });

            return false;
        } else {
            $('.iwbvel-variations-bulk-actions-sub-buttons').slideUp(150);
            iwbvelOpenModal('#iwbvel-variations-bulk-actions-modal');

            iwbvelResetBulkActionsForm();
            if ($('.iwbvel-variation-row-select:visible:checked').length == 1) {
                iwbvelGetVariationData($('.iwbvel-variation-row-select:visible:checked').val());
            }

            $('.iwbvel-variations-bulk-action-do-bulk-button').attr('data-type', 'selected');
        }
    });

    $(document).on('click', '.iwbvel-variation-row-edit-button', function () {
        $('.iwbvel-variations-bulk-action-do-bulk-button').attr('data-type', 'selected');

        $('.iwbvel-variation-row-select').prop('checked', false).change();
        $(this).closest('tr').find('.iwbvel-variation-row-select').prop('checked', true).change();
        iwbvelResetBulkActionsForm();
        iwbvelGetVariationData($(this).val());
    });

    $(document).on('click', '.iwbvel-variations-table-attributes-edit-button', function () {
        let $this = $(this);
        let attributes = iwbvelGetActiveAttributes();
        if (!attributes.length) {
            return false;
        }

        $('.iwbvel-variation-attributes-edit-button').attr('data-id', $this.closest('tr').attr('data-id'));

        let $containerElement = $('#iwbvel-variation-attributes-items');
        $containerElement.html('');

        let item = '';
        let termSelected = '';
        attributes.forEach(function (attribute) {
            if (attribute[1] && attribute[1]['terms'].length > 0) {
                termSelected = $this.closest('tr').find('input[data-attribute="' + attribute[0] + '"]').val();
                item = '<div class="iwbvel-variation-attributes-item"><label>' + attribute[1]['attribute_label'] + '</label><select name="' + attribute[0] + '">';
                attribute[1]['terms'].forEach(function (term) {
                    item += '<option value="' + term['slug'] + '" ' + ((termSelected == term['slug']) ? "selected" : "") + '>' + term['name'] + '</option>';
                });
                item += '</select></div>';
                $containerElement.append(item);
            }
        });
    });

    $(document).on('click', '.iwbvel-variation-attributes-edit-button', function () {
        let attributes = {};
        let variationId = $(this).attr('data-id');
        if ($('#iwbvel-variation-attributes-items .iwbvel-variation-attributes-item').length > 0) {
            $('#iwbvel-variation-attributes-items .iwbvel-variation-attributes-item').each(function () {
                let item = $(this).find('select');
                attributes[item.attr('name')] = item.val();
            }).promise().done(function () {
                iwbvelVariationsAttributesEdit({
                    variation_id: variationId,
                    attributes: attributes,
                });
            })
        }
    });

    $(document).on('click', '.iwbvel-variations-all-combinations-button', function () {
        $('.iwbvel-variations-all-combinations-generate-button').attr('data-type', $(this).attr('data-type'));
        $('#iwbvel-variations-all-variations-items').html('');
        $('#iwbvel-variations-all-variations-modal .iwbvel-variations-individual-variation-loading').show();
        iwbvelGetPossibleCombinations(iwbvelGetActiveAttributesForAllCombinations());
    });

    $(document).on('click', '.iwbvel-individual-variation-add-button', function () {
        if ($('#iwbvel-variations-table tbody tr').length >= 2) {
            swal({
                title: "It's not possible to add more than 2 variations on lite version.",
                type: "warning"
            });

            return false;
        } else {
            let variableIds = iwbvelGetProductsChecked();
            let attributesElement = $(this).closest('.iwbvel-modal-content').find('.iwbvel-modal-body select');

            if ($(this).attr('data-type') == 'all-products') {
                swal({
                    title: 'All of current variations will be replaced with new variation. Are you sure?',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
                    confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
                    confirmButtonText: iwbvelTranslate.iAmSure,
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        iwbvelPrepareAddVariations(variableIds, attributesElement);
                    }
                });
            } else {
                if ($('#iwbvel-variations-variable-products-selector:visible').length) {
                    variableIds = [$('#iwbvel-variations-variable-products-selector:visible').val()];
                } else {
                    variableIds = [variableIds[0]];
                }

                iwbvelPrepareAddVariations(variableIds, attributesElement);
            }
        }

    });

    $(document).on('click', '.iwbvel-variations-save-changes', function () {
        if ($("input.iwbvel-check-item:visible:checkbox:checked").first().attr('data-item-type') != 'variable') {
            swal({
                title: 'This product will be changed to Variable product, Are you sure?',
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-white",
                confirmButtonClass: "iwbvel-button iwbvel-button-lg iwbvel-button-green",
                confirmButtonText: iwbvelTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    iwbvelUpdateProductVariations();
                }
            });
        } else {
            iwbvelUpdateProductVariations();
        }
    });

    $(document).on('click', '.iwbvel-variations-table-delete-button', function () {
        if (!$("input.iwbvel-variation-row-select:visible:checkbox:checked").length) {
            swal({
                title: "Please select one variation",
                type: "warning"
            });

            return false;
        }

        if ($("input.iwbvel-variation-row-select:visible:checkbox:checked").length > 0) {
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
                    iwbvelDeleteVariationsByIds(iwbvelGetVariationsChecked());
                }
            });
        }
    });

    $(document).on('change', '#iwbvel-variations-delete-type-selector', function () {
        if ($(this).val() == 'attributes') {
            $('.iwbvel-variations-delete-attribute-items').show();
        } else {
            $('.iwbvel-variations-delete-attribute-items').hide();
        }
    })

    $(document).on('click', '.iwbvel-variations-delete-button', function () {
        switch ($('#iwbvel-variations-delete-type-selector').val()) {
            case 'all':
                iwbvelDeleteAllVariationsByVariableIds(iwbvelGetProductsChecked());
                break;
            case 'attributes':
                iwbvelDeleteVariationsByAttribute({
                    attribute: $('#iwbvel-variations-delete-attribute-selector').val(),
                    term: $('#iwbvel-variations-delete-term-selector').val(),
                });
                break;
            default:
                return false;
        }
    });

    $(document).on('change', '#iwbvel-variations-delete-attribute-selector', function () {
        $('.iwbvel-variations-delete-term-selector-container').hide();
        $('#iwbvel-variations-delete-term-selector').html('');

        if ($(this).val() != '') {
            $(this).closest('.iwbvel-variations-delete-attribute-items').find('.iwbvel-variations-term-loading').show();

            iwbvelGetTermsByAttributeName($(this).val(), {
                container: $('.iwbvel-variations-delete-term-selector-container'),
                selectBox: $('#iwbvel-variations-delete-term-selector')
            }, '<option value="all_terms">All</option>');
        } else {
            $(this).closest('.iwbvel-variations-delete-attribute-items').find('.iwbvel-variations-term-loading').hide();
        }
    });

    $(document).on('change', '#iwbvel-variations-attach-attribute-selector', function () {
        $('.iwbvel-variations-attach-term-selector-container').hide();
        $('.iwbvel-variations-attach-items').html('');
        $('#iwbvel-variations-attach-term-selector').html('');

        if ($(this).val() == '') {
            $(this).closest('.iwbvel-variations-attach-attribute').find('.iwbvel-variations-term-loading').hide();
            return;
        }

        $(this).closest('.iwbvel-variations-attach-attribute').find('.iwbvel-variations-term-loading').show();
        let variableIds = iwbvelGetProductsChecked();

        if ($(this).find('option:selected').attr('data-used-flag') === 'true') {
            iwbvelGetTermsByAttributeName($(this).val(), {
                container: $('.iwbvel-variations-attach-term-selector-container'),
                selectBox: $('#iwbvel-variations-attach-term-selector')
            }, '<option value="">Select</option>');
        } else {
            iwbvelGetVariationsForAttach(variableIds[0], $(this).val());
        }
    });

    $(document).on('change', '#iwbvel-variations-swap-from-attribute-selector', function () {
        $('.iwbvel-variations-swap-from-term-selector-container').hide();
        $('.iwbvel-variations-swap-to-term-selector-container').hide();
        $('#iwbvel-variations-swap-from-term-selector').html('');
        $('#iwbvel-variations-swap-to-term-selector').html('');

        if ($(this).val() != '') {
            $(this).closest('.iwbvel-variations-swap-from-attribute').find('.iwbvel-variations-term-loading').show();

            iwbvelGetTermsByAttributeNameForSwap($(this).val(), {
                from_container: $('.iwbvel-variations-swap-from-term-selector-container'),
                to_container: $('.iwbvel-variations-swap-to-term-selector-container'),
                from: $('#iwbvel-variations-swap-from-term-selector'),
                to: $('#iwbvel-variations-swap-to-term-selector')
            });
        } else {
            $(this).closest('.iwbvel-variations-swap-from-attribute').find('.iwbvel-variations-term-loading').hide();
        }
    });

    $(document).on('click', '.iwbvel-variations-bulk-action-do-bulk-button', function () {
        jQuery('.iwbvel-variation-bulk-edit-loading').show();
        let productIds = [], type;
        if ($(this).attr('data-type') == 'selected') {
            productIds = iwbvelGetVariationsChecked();
        }

        if ($(this).attr('data-type') == 'all') {
            productIds = iwbvelGetProductsChecked();
            type = 'product_variations';
        }

        let productData = [];

        $("#iwbvel-variations-bulk-actions-modal .iwbvel-form-group").each(function () {
            let value;
            if ($(this).find('[data-field="value"]').length > 1) {
                value = $(this).find('[data-field="value"]').map(function () {
                    if ($(this).val() !== '') {
                        return $(this).val();
                    }
                }).get();
            } else {
                value = $(this).find('[data-field="value"]').val();
            }

            if ($(this).attr('data-name') == 'downloadable_files') {
                let names = $(this).find('.iwbvel-variation-bulk-actions-file-item-name-input').map(function () {
                    return $(this).val();
                }).get();

                let urls = $(this).find('.iwbvel-variation-bulk-actions-file-item-url-input').map(function () {
                    return $(this).val();
                }).get();

                value = {
                    files_name: names,
                    files_url: urls,
                };
            }

            if (typeof $(this).attr('data-name') != 'undefined') {
                if (
                    ($.isArray(value) && value.length > 0)
                    ||
                    (!$.isArray(value) && value != '' && typeof value != 'undefined')
                    ||
                    ($.inArray($(this).find('[data-field="operator"]').val(), ['text_remove_duplicate', 'number_clear']) !== -1)
                ) {
                    let name = $(this).attr('data-name');
                    let type = $(this).attr('data-type');

                    if ($(this).find('[data-field="operator"]').val() == 'text_remove_duplicate') {
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
                        operation: 'bulk_edit'
                    });
                }
            }
        });

        if (productIds.length > 0 || $(this).attr('data-type') == 'all') {
            iwbvelVariationEdit(productIds, productData, true, type);
        } else {
            swal({
                title: "Please select one variation",
                type: "warning"
            });
        }
    });

    $(document).on('click', '.iwbvel-variations-swap-button', function () {
        if ($('#iwbvel-variations-swap-from-attribute-selector').val() != '') {
            if ($('#iwbvel-variations-swap-from-term-selector').val() != '' && $('#iwbvel-variations-swap-to-term-selector').val() != '') {
                if ($('#iwbvel-variations-swap-from-term-selector').val() == $('#iwbvel-variations-swap-to-term-selector').val()) {
                    swal({
                        title: "The terms must not be equal",
                        type: "warning"
                    });
                    return false;
                } else {
                    iwbvelVariationsSwapTerms({
                        'variation_ids': 'all',
                        'attribute': $('#iwbvel-variations-swap-from-attribute-selector').val(),
                        'from_term': $('#iwbvel-variations-swap-from-term-selector').val(),
                        'to_term': $('#iwbvel-variations-swap-to-term-selector').val(),
                    });
                }

            } else {
                swal({
                    title: "Select terms is required",
                    type: "warning"
                });
            }
        } else {
            swal({
                title: "Select attribute is required",
                type: "warning"
            });
        }
    });

    $(document).on('click', '.iwbvel-variations-reload-table', function () {
        $('#iwbvel-float-side-modal-variations-bulk-edit').removeClass('iwbvel-float-side-modal-close-with-confirm');

        let variableId;
        if ($('#iwbvel-variations-variable-products-selector:visible').length) {
            variableId = $('#iwbvel-variations-variable-products-selector:visible').val();
        } else {
            let variableIds = iwbvelGetProductsChecked();
            variableId = variableIds[0];
        }

        let variationIds = iwbvelGetVariationsChecked();
        let currentPage = ($('.iwbvel-variations-pagination-item.current').length) ? parseInt($('.iwbvel-variations-pagination-item.current').attr('data-index')) : 1;

        iwbvelGetProductVariations(variableId, variationIds, currentPage);
    });

    $(document).on('click', '.iwbvel-variation-item-btn', function () {
        $('.iwbvel-check-item:checkbox:checked').prop('checked', false).change();
        $(this).closest('label').find('.iwbvel-check-item:checkbox').prop('checked', true).change();
        iwbvelOpenVariationsFloatSide();
    });

    $(document).on('click', '.iwbvel-variations-table-image', function () {
        let modal = $('#iwbvel-variation-thumbnail-modal');

        let image_id = $(this).attr('data-image-id');
        let item_id = $(this).closest('tr').attr('data-id');
        let full_size_url = $(this).attr('data-full-image-src');

        modal.find('.iwbvel-open-uploader').attr('data-item-id', item_id);
        modal.find('.iwbvel-inline-image-preview').html('<img src="' + full_size_url + '" />');
        modal.find('.iwbvel-variations-table-thumbnail-inline-edit-button').attr('data-item-id', $(this).closest('tr').attr('data-id')).attr('data-image-id', image_id);
    });

    $(document).on('click', '.iwbvel-variations-table-thumbnail-inline-edit-button', function () {
        iwbvelVariationEdit([$(this).attr("data-item-id")], [{
            name: $(this).attr('data-name'),
            sub_name: '',
            type: $(this).attr('data-update-type'),
            value: $(this).attr("data-image-id"),
            operation: 'inline_edit'
        }]);
    });

    $(document).on('click', '.iwbvel-default-variation-radio-button', function () {
        let attributes = {};
        if ($(this).closest('tr').find('.iwbvel-variation-attributes-inputs').length > 0) {
            $(this).closest('tr').find('.iwbvel-variation-attributes-inputs').each(function () {
                attributes[$(this).attr('data-attribute')] = $(this).val();
            }).promise().done(function () {
                iwbvelDefaultAttributesUpdate(attributes);
            })
        }
    });

    $(document).on('click', '.iwbvel-enable-variation-checkbox', function () {
        let productData = [{
            name: 'enabled',
            type: 'woocommerce_field',
            operator: '',
            value: ($(this).prop('checked') === true) ? 'yes' : 'no',
            operation: 'inline_edit'
        }];

        iwbvelVariationEdit([$(this).closest('tr').attr('data-id')], productData, false);
    });

    $(document).on('click', '.iwbvel-variations-inline-edit-column', function () {
        let $this = $(this);
        let item;
        $('.iwbvel-variations-inline-edit-input:visible').each(function () {
            item = $(this).closest("td").find('.iwbvel-variations-inline-edit-column');
            $(this).val(item.text()).hide();
            item.show();
        }).promise().done(function () {
            $this.hide();
            $this.closest('td').find('.iwbvel-variations-inline-edit-input').show().focus().select();
        });
    });

    $(document).on("click", function (e) {
        if (!$(e.target).hasClass("iwbvel-variations-inline-edit-column") && !$(e.target).hasClass("iwbvel-variations-inline-edit-input")) {
            let item;
            $('.iwbvel-variations-inline-edit-input:visible').each(function () {
                item = $(this).closest("td").find('.iwbvel-variations-inline-edit-column');
                $(this).val(item.text()).hide();
                item.show();
            });
        }
    });

    // Save Inline Edit By Enter Key
    $(document).on("keypress", '.iwbvel-variations-inline-edit-input', function (event) {
        let iwbvelKeyCode = event.keyCode ? event.keyCode : event.which;
        if (iwbvelKeyCode === 13) {
            let productData = [];
            let productIds = [$(this).closest('tr').attr('data-id')];

            productData.push({
                name: $(this).attr('data-name'),
                sub_name: '',
                type: 'woocommerce_field',
                value: $(this).val(),
                operation: 'inline_edit'
            });

            $(this).closest("td").find('.iwbvel-variations-inline-edit-column').text($(this).val()).show();
            $(this).hide();
            iwbvelVariationEdit(productIds, productData, true);
        }
    });

    $(document).on({
        mouseenter: function () {
            $(this).find(".iwbvel-calculator").show();
        },
        mouseleave: function () {
            $(this).find(".iwbvel-calculator").hide();
        }
    },
        "td.iwbvel-has-price-calculator"
    );

    $(document).on("click", '.iwbvel-variations-sale-price-calculator-button', function () {
        $('#iwbvel-modal-variations-sale-price').find('.iwbvel-variations-price-calculator-apply-button').attr("data-item-id", $(this).closest("tr").attr('data-id'));
    });

    $(document).on("click", '.iwbvel-variations-regular-price-calculator-button', function () {
        $('#iwbvel-modal-variations-regular-price').find('.iwbvel-variations-price-calculator-apply-button').attr("data-item-id", $(this).closest("tr").attr('data-id'));
    });

    $(document).on('click', '.iwbvel-variations-pagination-item', function () {
        jQuery('.iwbvel-variations-table-loading').show();
        $('.iwbvel-variations-pagination-item').removeClass('current');
        $(this).addClass('current');

        let variableId;
        if ($('#iwbvel-variations-variable-products-selector:visible').length) {
            variableId = $('#iwbvel-variations-variable-products-selector:visible').val();
        } else {
            let variableIds = iwbvelGetProductsChecked();
            variableId = variableIds[0];
        }

        iwbvelChangePage(variableId, $(this).attr('data-index'));
    });

    $(document).on("click", ".iwbvel-variations-price-calculator-apply-button", function () {
        let variationId = [$(this).attr("data-item-id")];
        let productData = [];
        let parentElement = $(this).closest('.iwbvel-modal')

        productData.push({
            name: $(this).attr("data-name"),
            sub_name: '',
            type: 'woocommerce_field',
            operator: parentElement.find(".iwbvel-calculator-operator").val(),
            value: parentElement.find(".iwbvel-calculator-value").val(),
            operator_type: (parentElement.find(".iwbvel-calculator-type").val()) ? parentElement.find(".iwbvel-calculator-type").val() : 'n',
            round: parentElement.find(".iwbvel-calculator-round").val()
        });

        iwbvelVariationEdit(variationId, productData);
    });

    $(document).on('input', '.iwbvel-variations-bulk-edit-add-variation-right input', function () {
        $('#iwbvel-float-side-modal-variations-bulk-edit').addClass('iwbvel-float-side-modal-close-with-confirm');
    });

    $(document).on('change', '#iwbvel-variations-variable-products-selector', function () {
        iwbvelCheckVisibilityProductSelectorButtons();
        iwbvelGetProductVariations($(this).val());
    });

    $(document).on('click', '.iwbvel-variations-product-selector-next-button', function () {
        $('.iwbvel-variations-product-selector-prev-button').removeAttr('disabled');
        let nextButtonElement = $(this);
        let next = $('#iwbvel-variations-variable-products-selector option:selected').next().val();
        if (next) {
            $('#iwbvel-variations-variable-products-selector').val(next).change();
        }

        setTimeout(function () {
            if ($('#iwbvel-variations-variable-products-selector option').length === ($('#iwbvel-variations-variable-products-selector').prop('selectedIndex') + 1)) {
                nextButtonElement.attr('disabled', 'disabled');
            } else {
                nextButtonElement.removeAttr('disabled');
            }
        }, 50)
    });

    $(document).on('click', '.iwbvel-variations-product-selector-prev-button', function () {
        $('.iwbvel-variations-product-selector-next-button').removeAttr('disabled');
        let prevButtonElement = $(this);
        let prev = $('#iwbvel-variations-variable-products-selector option:selected').prev().val();
        if (prev) {
            $('#iwbvel-variations-variable-products-selector').val(prev).change();
        }

        setTimeout(function () {
            if ($('#iwbvel-variations-variable-products-selector').prop('selectedIndex') == 0) {
                prevButtonElement.attr('disabled', 'disabled');
            } else {
                prevButtonElement.removeAttr('disabled');
            }
        }, 50)
    });
});