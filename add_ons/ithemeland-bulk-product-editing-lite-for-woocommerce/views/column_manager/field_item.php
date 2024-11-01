<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-column-manager-right-item">
    <input type="hidden" name="field_name[]" value="<?php echo (!empty($field_name)) ? esc_attr($field_name) : ''; ?>">
    <input type="hidden" name="field_label[]" value="<?php echo (!empty($field_label)) ? esc_attr($field_label) : ''; ?>">
    <span class="wcbel-column-manager-field-name"><?php echo (!empty($field_label)) ? esc_attr($field_label) : ''; ?></span>
    <input type="text" class="wcbel-column-manager-field-title" placeholder="<?php esc_html_e('Enter column title ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" name="field_title[]" required value="<?php echo (!empty($field_title)) ? esc_attr($field_title) : ''; ?>" title="<?php esc_html_e('Enter column title', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
    <label class="wcbel-column-manager-color-field" title="<?php esc_html_e('Background Color', 'ithemeland-bulk-product-editing-lite-for-woocommerce') ?>" style="background: <?php echo (!empty($field_background_color)) ? esc_attr($field_background_color) : '#fff'; ?>">
        <input type="text" class="wcbel-color-picker" title="<?php esc_html_e('Select Background Color', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" value="<?php echo (!empty($field_background_color)) ? esc_attr($field_background_color) : '#ffffff'; ?>" name="field_background_color[]">
    </label>
    <label class="wcbel-column-manager-color-field">
        <input type="text" value="<?php echo (!empty($field_text_color)) ? esc_attr($field_text_color) : '#444444'; ?>" name="field_text_color[]" class="wcbel-color-picker" title="<?php esc_html_e('Select Text Color', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
    </label>
    <button type="button" class="wcbel-button wcbel-button-flat wcbel-column-manager-field-sortable-btn">
        <i class="wcbel-icon-menu1"></i>
    </button>
    <button type="button" class="wcbel-button wcbel-button-flat wcbel-column-manager-remove-field" data-name="<?php echo (!empty($field_name)) ? esc_attr($field_name) : ''; ?>" data-action="<?php echo (!empty($field_action)) ? esc_attr($field_action) : ''; ?>" data-label="<?php echo (!empty($field_label)) ? esc_attr($field_label) : ''; ?>">
        <i class="wcbel-icon-x"></i>
    </button>
</div>