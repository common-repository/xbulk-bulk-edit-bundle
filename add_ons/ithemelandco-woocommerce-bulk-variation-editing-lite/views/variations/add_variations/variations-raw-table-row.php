<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<tr data-id="{id}">
    {attributes}
    <input type="hidden" name="variations[{id}][variation_id]" value="{id}">
    <td><input type="checkbox" class="iwbvel-variation-row-select" value="{id}"></td>
    <td>
        {id}
        <br>
        <button type="button" class="iwbvel-button-flat iwbvel-variations-delete-row" value="{id}"><i class="iwbvel-icon-trash"></i></button>
        <button type="button" data-toggle="modal" data-target="#iwbvel-variations-bulk-actions-modal" class="iwbvel-button-flat iwbvel-variation-row-edit-button" value="{id}"><i class="iwbvel-icon-edit"></i></button>
    </td>
    <td>
        <div class="iwbvel-variations-table-image" data-toggle="modal" data-target="#iwbvel-variation-thumbnail-modal" data-full-image-src="{thumbnail_full_size}">
            <img src="{thumbnail}" alt="" width="40" height="40" />
        </div>
    </td>
    <td style="text-align: left; position: relative; padding-right: 20px;" title="<?php esc_html_e('Edit Attributes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" class="iwbvel-variations-table-attributes-edit-button" data-toggle="modal" data-target="#iwbvel-variation-attributes-edit-modal">
        <div class="iwbvel-variations-table-name">{name}</div>
        <div class="iwbvel-variations-table-attributes-edit">
            <button type="button" class="iwbvel-button-flat" data-toggle="modal" data-target="#iwbvel-variation-attributes-edit-modal"><i class="iwbvel-icon-edit-2"></i></button>
        </div>
    </td>
    <td class="iwbvel-has-price-calculator">
        <div class="iwbvel-variations-inline-edit-container">
            <span class="iwbvel-variations-inline-edit-column">{regular_price}</span>
            <button type="button" data-toggle="modal" class="iwbvel-calculator iwbvel-variations-regular-price-calculator-button" data-target="#iwbvel-modal-variations-regular-price" style="display: none;"></button>
            <input type="number" class="iwbvel-variations-inline-edit-input" data-name="regular_price" value="{regular_price}">
        </div>
    </td>
    <td class="iwbvel-has-price-calculator">
        <div class="iwbvel-variations-inline-edit-container">
            <span class="iwbvel-variations-inline-edit-column">{sale_price}</span>
            <button type="button" data-toggle="modal" class="iwbvel-calculator iwbvel-variations-sale-price-calculator-button" data-target="#iwbvel-modal-variations-sale-price" style="display: none;"></button>
            <input type="number" class="iwbvel-variations-inline-edit-input" data-name="sale_price" value="{sale_price}">
        </div>
    </td>
    <td>
        <div class="iwbvel-variations-inline-edit-container">
            <span class="iwbvel-variations-inline-edit-column">{stock_quantity}</span>
            <input type="number" class="iwbvel-variations-inline-edit-input" data-name="stock_quantity" value="{stock_quantity}">
        </div>
    </td>
    <td>
        <label style="margin: 0;">
            <input type="checkbox" class="iwbvel-enable-variation-checkbox" name="variations[{id}][enabled]" value="{id}" {enable_checked}>
        </label>
    </td>
    <td>
        <label style="margin: 0;">
            <input type="radio" class="iwbvel-default-variation-radio-button" name="default_variation" value="{id}" {default_checked}>
        </label>
    </td>
</tr>