<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-numeric-calculator">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Calculator', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-numeric-calculator-item-title" class="iwbvel-modal-product-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <select id="iwbvel-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" id="iwbvel-numeric-calculator-value" title="<?php esc_html_e('Value', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-field-type="" data-toggle="modal-close" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>