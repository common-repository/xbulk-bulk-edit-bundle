<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!empty($file_id)) :
?>
    <div class="iwbvel-modal-select-files-file-item">
        <button type="button" class="iwbvel-button iwbvel-button-flat iwbvel-select-files-sortable-btn" title="<?php esc_html_e('Drag', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
            <i class=" iwbvel-icon-menu1"></i>
        </button>
        <input type="text" class="iwbvel-inline-edit-file-name" placeholder="File Name ..." value="<?php echo !empty($file_item) ? esc_attr($file_item->get_name()) : ''; ?>">
        <input type="text" class="iwbvel-inline-edit-file-url iwbvel-w60p" id="url-<?php echo esc_attr($file_id); ?>" name="file_url" placeholder="File Url ..." value="<?php echo !empty($file_item) ? esc_attr($file_item->get_file()) : ''; ?>">
        <button type="button" class="iwbvel-button iwbvel-button-white iwbvel-open-uploader iwbvel-inline-edit-choose-file" data-type="single" data-target="inline-file" data-id="<?php echo esc_attr($file_id); ?>"><?php esc_html_e('Choose File', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
        <button type="button" class="iwbvel-button iwbvel-button-white iwbvel-inline-edit-file-remove-item">x</button>
    </div>
<?php endif; ?>