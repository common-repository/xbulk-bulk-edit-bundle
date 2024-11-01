<div class="wcbef-modal" id="wcbef-modal-numeric-calculator">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-sm">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Calculator', 'woocommerce-bulk-edit-free'); ?> - <span id="wcbef-modal-numeric-calculator-product-title" class="wcbef-modal-product-title"></span></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <select id="wcbef-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', 'woocommerce-bulk-edit-free'); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', 'woocommerce-bulk-edit-free'); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'woocommerce-bulk-edit-free'); ?>" id="wcbef-numeric-calculator-value" title="<?php esc_html_e('Value', 'woocommerce-bulk-edit-free'); ?>">
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" data-product-id="" data-field="" data-field-type="" data-toggle="modal-close" class="wcbef-button wcbef-button-blue wcbef-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', 'woocommerce-bulk-edit-free'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>