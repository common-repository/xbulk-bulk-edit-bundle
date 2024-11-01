<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-select-products">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Select Products', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-select-products-item-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-inline-select-products">
                            <select id="iwbvel-select-products-value" class="iwbvel-get-products-ajax iwbvel-select2 iwbvel-w100p" multiple></select>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-content-type="select_products" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>