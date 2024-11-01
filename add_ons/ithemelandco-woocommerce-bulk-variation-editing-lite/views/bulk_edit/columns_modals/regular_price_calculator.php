<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($product)) :
?>
    <div class="iwbvel-modal" id="iwbvel-modal-regular_price-<?php echo esc_attr($product['id']); ?>">
        <div class="iwbvel-modal-container">
            <div class="iwbvel-modal-box iwbvel-modal-box-sm">
                <div class="iwbvel-modal-content">
                    <div class="iwbvel-modal-title">
                        <h2><?php esc_html_e('Calculator', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span class="iwbvel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                            <i class="iwbvel-icon-x"></i>
                        </button>
                    </div>
                    <div class="iwbvel-modal-body">
                        <div class="iwbvel-wrap">
                            <select id="iwbvel-regular_price-calculator-operator-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Select Operator', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                <option value="+">+</option>
                                <option value="-">-</option>
                                <option value="sp+">sp+</option>
                            </select>
                            <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" id="iwbvel-regular_price-calculator-value-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Value', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                            <select id="iwbvel-regular_price-calculator-type-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Select Type', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                <option value="n">n</option>
                                <option value="%">%</option>
                            </select>
                            <select id="iwbvel-regular_price-calculator-round-<?php echo esc_attr($product['id']); ?>" title="<?php esc_html_e('Rounding', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                <option value=""><?php esc_html_e('no rounding', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
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
                    <div class="iwbvel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($product['id']); ?>" data-field="regular_price" data-update-type="woocommerce_field" data-toggle="modal-close" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-price-calculator">
                            <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>