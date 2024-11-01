<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-column-manager-right-item">
    <input type="hidden" name="field_name[]" value="<?php echo (!empty($field_name)) ? esc_attr($field_name) : ''; ?>">
    <input type="hidden" name="field_label[]" value="<?php echo (!empty($field_label)) ? esc_attr($field_label) : ''; ?>">
    <span class="wpbel-column-manager-field-name"><?php echo (!empty($field_label)) ? esc_html($field_label) : ''; ?></span>
    <input type="text" class="wpbel-column-manager-field-title" placeholder="<?php esc_html_e('Enter column title ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" name="field_title[]" required value="<?php echo (!empty($field_title)) ? esc_attr($field_title) : ''; ?>" title="<?php esc_html_e('Enter column title', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
    <label class="wpbel-column-manager-color-field" title="<?php esc_html_e('Background Color', 'ithemeland-wordpress-bulk-posts-editing-lite') ?>" style="background: <?php echo (!empty($field_background_color)) ? esc_attr($field_background_color) : '#fff'; ?>">
        <input type="text" class="wpbel-color-picker" title="<?php esc_html_e('Select Background Color', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" value="<?php echo (!empty($field_background_color)) ? esc_attr($field_background_color) : '#ffffff'; ?>" name="field_background_color[]">
    </label>
    <label class="wpbel-column-manager-color-field">
        <input type="text" value="<?php echo (!empty($field_text_color)) ? esc_attr($field_text_color) : '#444444'; ?>" name="field_text_color[]" class="wpbel-color-picker" title="<?php esc_html_e('Select Text Color', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
    </label>
    <button type="button" class="wpbel-button wpbel-button-flat wpbel-column-manager-field-sortable-btn">
        <i class="wpbel-icon-menu1"></i>
    </button>
    <button type="button" class="wpbel-button wpbel-button-flat wpbel-column-manager-remove-field" data-name="<?php echo (!empty($field_name)) ? esc_attr($field_name) : ''; ?>" data-action="<?php echo (!empty($field_action)) ? esc_attr($field_action) : ''; ?>" data-label="<?php echo (!empty($field_label)) ? esc_attr($field_label) : ''; ?>">
        <i class="wpbel-icon-x"></i>
    </button>
</div>