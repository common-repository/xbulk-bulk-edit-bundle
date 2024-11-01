<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-numeric-calculator">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Calculator', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-numeric-calculator-item-title" class="wcbel-modal-product-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <select id="wcbel-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" id="wcbel-numeric-calculator-value" title="<?php esc_html_e('Value', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-field-type="" data-toggle="modal-close" class="wcbel-button wcbel-button-blue wcbel-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>