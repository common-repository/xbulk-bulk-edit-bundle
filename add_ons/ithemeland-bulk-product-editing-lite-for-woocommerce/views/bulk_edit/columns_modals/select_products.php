<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-select-products">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Select Products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-select-products-item-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-inline-select-products">
                            <select id="wcbel-select-products-value" class="wcbel-get-products-ajax wcbel-select2 wcbel-w100p" multiple></select>
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-content-type="select_products" class="wcbel-button wcbel-button-blue wcbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>