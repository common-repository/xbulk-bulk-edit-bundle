<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-form-group" data-name="stock_quantity" data-type="woocommerce_field">
    <label for="iwbvel-variations-bulk-actions-stock-quantity"><?php esc_html_e('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="" data-field="operator">
        <?php
        if (!empty($edit_number_operator)) :
            foreach ($edit_number_operator as $key => $value) :
        ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
        <?php
            endforeach;
        endif;
        ?>
    </select>
    <input type="number" id="iwbvel-variations-bulk-actions-stock-quantity" data-field="value" value="">
</div>

<div class="iwbvel-form-group" data-name="low_stock_threshold" data-type="woocommerce_field">
    <label for="iwbvel-variations-bulk-actions-low-stock-threshold"><?php esc_html_e('Low stock threshold ', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="" data-field="operator">
        <?php
        if (!empty($edit_number_operator)) :
            foreach ($edit_number_operator as $key => $value) :
        ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
        <?php
            endforeach;
        endif;
        ?>
    </select>
    <input type="number" id="iwbvel-variations-bulk-actions-low-stock-threshold" data-field="value" value="">
</div>

<div class="iwbvel-form-group" data-name="backorders" data-type="woocommerce_field">
    <label for="iwbvel-variations-bulk-actions-backorders"><?php esc_html_e('Allow backorders', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select id="iwbvel-variations-bulk-actions-backorders" style="width: auto; padding-top: 2px;" data-field="value">
        <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <?php
        $backorders = wc_get_product_backorder_options();
        if (!empty($backorders)) :
            foreach ($backorders as $key => $label) :
        ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
        <?php
            endforeach;
        endif;
        ?>
    </select>
</div>