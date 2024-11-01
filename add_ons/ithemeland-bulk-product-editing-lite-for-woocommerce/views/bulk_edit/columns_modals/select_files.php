<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-select-files">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-lg">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Select Files', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-select-files-item-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <button type="button" id="wcbel-modal-select-files-add-file-item" class="wcbel-button wcbel-button-green wcbel-mb10"><?php esc_html_e('Add File', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button>
                        <div class="wcbel-inline-select-files"></div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" id="wcbel-modal-select-files-apply" data-item-id="" data-field="" data-content-type="select_files" class="wcbel-button wcbel-button-blue wcbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>