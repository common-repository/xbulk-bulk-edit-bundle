<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-variation-bulk-actions-file-item">
    <div class="iwbvel-variation-bulk-actions-file-item-name">
        <label for=""><?php esc_html_e('File name', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
        <input type="text" class="iwbvel-variation-bulk-actions-file-item-name-input" value="" data-field="file_name">
    </div>
    <div class="iwbvel-variation-bulk-actions-file-item-url">
        <label for=""><?php esc_html_e('File url', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
        <input type="text" class="iwbvel-variation-bulk-actions-file-item-url-input" value="" data-field="file_url">
    </div>
    <button type="button" class="iwbvel-button iwbvel-button-white iwbvel-open-uploader iwbvel-inline-edit-choose-file iwbvel-variation-bulk-actions-file-item-choose-button" data-type="single" data-target="variations-bulk-actions-file"><?php esc_html_e('Choose File', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
    <div class="iwbvel-variation-bulk-actions-file-item-remove">
        <button type="button" class="iwbvel-variation-bulk-actions-file-item-remove-button">
            <i class="iwbvel-icon-x"></i>
        </button>
    </div>
</div>