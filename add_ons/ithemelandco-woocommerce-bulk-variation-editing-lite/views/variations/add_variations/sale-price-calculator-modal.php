<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-variations-sale-price">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_attr_e('Calculator', 'ithemeland-woocommerce-bulk-variations-editing'); ?></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <select class="iwbvel-calculator-operator" title="<?php esc_html_e('Select Operator', 'ithemeland-woocommerce-bulk-variations-editing'); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="rp-">rp-</option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemeland-woocommerce-bulk-variations-editing'); ?>" class="iwbvel-calculator-value" title="<?php esc_html_e('Value', 'ithemeland-woocommerce-bulk-variations-editing'); ?>">
                        <select class="iwbvel-calculator-type" title="<?php esc_html_e('Select Type', 'ithemeland-woocommerce-bulk-variations-editing'); ?>">
                            <option value="n">n</option>
                            <option value="%">%</option>
                        </select>
                        <select class="iwbvel-calculator-round" title="<?php esc_html_e('Rounding', 'ithemeland-woocommerce-bulk-variations-editing'); ?>">
                            <option value=""><?php esc_html_e('no rounding', 'ithemeland-woocommerce-bulk-variations-editing'); ?></option>
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
                    <button type="button" data-item-id="" data-field="sale_price" data-name="sale_price" data-toggle="modal-close" class="iwbvel-button iwbvel-button-blue iwbvel-variations-price-calculator-apply-button">
                        <?php esc_html_e('Apply Changes', 'ithemeland-woocommerce-bulk-variations-editing'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>