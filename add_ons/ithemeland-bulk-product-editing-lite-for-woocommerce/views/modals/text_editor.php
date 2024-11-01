<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-text-editor">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-lg">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Content Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-text-editor-item-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <?php wp_editor("", 'wcbel-text-editor'); ?>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" data-field="" data-item-id="" data-content-type="textarea" id="wcbel-text-editor-apply" class="wcbel-button wcbel-button-blue wcbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>