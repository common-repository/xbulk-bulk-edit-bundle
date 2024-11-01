<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-item-duplicate">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Duplicate', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-form-group">
                            <label class="wcbel-label-big" for="wcbel-bulk-edit-duplicate-number">
                                <?php esc_html_e('Enter how many item(s) to Duplicate!', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                            </label>
                            <input type="number" class="wcbel-input-numeric-sm" id="wcbel-bulk-edit-duplicate-number" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" class="wcbel-button wcbel-button-blue" id="wcbel-bulk-edit-duplicate-start">
                        <?php esc_html_e('Start Duplicate', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>