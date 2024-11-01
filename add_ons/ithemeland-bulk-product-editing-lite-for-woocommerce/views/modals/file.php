<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-file">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-lg">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Select File', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-select-file-item-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-inline-select-files">
                            <div class="wcbe-modal-select-files-file-item">
                                <input type="text" class="wcbel-inline-edit-file-url wcbel-w60p" id="wcbel-file-url" placeholder="File Url ..." value="">
                                <button type="button" class="wcbel-button wcbel-button-white wcbel-open-uploader wcbel-inline-edit-choose-file" data-type="single" data-target="inline-file-custom-field"><?php esc_html_e('Choose File', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button>
                                <input type="hidden" id="wcbel-file-id" value="">
                                <button type="button" class="wcbel-button wcbel-button-white" id="wcbel-modal-file-clear"><?php esc_html_e('Clear', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" id="wcbel-modal-file-apply" data-item-id="" data-field="" data-content-type="file" class="wcbel-button wcbel-button-blue wcbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>