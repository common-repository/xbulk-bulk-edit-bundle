<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($file_id)) : ?>
    <div class="wcbel-modal-select-files-file-item">
        <button type="button" class="wcbel-button wcbel-button-flat wcbel-select-files-sortable-btn" title="<?php esc_html_e('Drag', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
            <i class=" wcbel-icon-menu1"></i>
        </button>
        <input type="text" class="wcbel-inline-edit-file-name" placeholder="File Name ..." value="<?php echo !empty($file_item) ? esc_attr($file_item->get_name()) : ''; ?>">
        <input type="text" class="wcbel-inline-edit-file-url wcbel-w60p" id="url-<?php echo esc_attr($file_id); ?>" name="file_url" placeholder="File Url ..." value="<?php echo !empty($file_item) ? esc_attr($file_item->get_file()) : ''; ?>">
        <button type="button" class="wcbel-button wcbel-button-white wcbel-open-uploader wcbel-inline-edit-choose-file" data-type="single" data-target="inline-file" data-id="<?php echo esc_attr($file_id); ?>"><?php esc_html_e('Choose File', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button>
        <button type="button" class="wcbel-button wcbel-button-white wcbel-inline-edit-file-remove-item">x</button>
    </div>
<?php endif; ?>