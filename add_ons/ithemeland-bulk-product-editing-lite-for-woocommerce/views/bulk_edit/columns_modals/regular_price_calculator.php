<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($product)) : ?>
    <div class="wcbel-modal" id="wcbel-modal-regular_price-<?php echo esc_attr($product['id']); ?>">
        <div class="wcbel-modal-container">
            <div class="wcbel-modal-box wcbel-modal-box-sm">
                <div class="wcbel-modal-content">
                    <div class="wcbel-modal-title">
                        <h2><?php esc_html_e('Calculator', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span class="wcbel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                            <i class="wcbel-icon-x"></i>
                        </button>
                    </div>
                    <div class="wcbel-modal-body">
                        <div class="wcbel-wrap">
                            <select id="wcbel-regular_price-calculator-operator-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Select Operator', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                                <option value="+">+</option>
                                <option value="-">-</option>
                                <option value="sp+">sp+</option>
                            </select>
                            <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" id="wcbel-regular_price-calculator-value-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Value', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                            <select id="wcbel-regular_price-calculator-type-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Select Type', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                                <option value="n">n</option>
                                <option value="%">%</option>
                            </select>
                            <select id="wcbel-regular_price-calculator-round-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Rounding', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                                <option value=""><?php esc_html_e('no rounding', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="9">9</option>
                                <option value="19">19</option>
                                <option value="29">29</option>
                                <option value="39">39</option>
                                <option value="49">49</option>
                                <option value="59">59</option>
                                <option value="69">69</option>
                                <option value="79">79</option>
                                <option value="89">89</option>
                                <option value="99">99</option>
                            </select>
                        </div>
                    </div>
                    <div class="wcbel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($product['id']); ?>" data-field="regular_price" data-update-type="woocommerce_field" data-toggle="modal-close" class="wcbel-button wcbel-button-blue wcbel-edit-action-price-calculator">
                            <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>