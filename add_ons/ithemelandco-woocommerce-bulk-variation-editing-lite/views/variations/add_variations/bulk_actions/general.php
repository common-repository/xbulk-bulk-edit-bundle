<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-form-group" data-name="enabled">
    <label for="iwbvel-variations-bulk-actions-enabled"><?php esc_html_e('Enabled', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="iwbvel-variations-bulk-actions-enabled" disabled="disabled">
        <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="yes"><?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="no"><?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
    </select>
    <span class="iwbvel-short-description"><?php esc_html_e('Upgrade to pro version', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
</div>
<div class="iwbvel-form-group" data-name="downloadable">
    <label for="iwbvel-variations-bulk-actions-downloadable"><?php esc_html_e('Downloadable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="iwbvel-variations-bulk-actions-downloadable" disabled="disabled">
        <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="yes"><?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="no"><?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
    </select>
    <span class="iwbvel-short-description"><?php esc_html_e('Upgrade to pro version', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
</div>
<div class="iwbvel-form-group" data-name="virtual">
    <label for="iwbvel-variations-bulk-actions-virtual"><?php esc_html_e('Virtual', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="iwbvel-variations-bulk-actions-virtual" data-field="value">
        <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="yes"><?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="no"><?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
    </select>
</div>
<div class="iwbvel-form-group" data-name="manage_stock" data-type="woocommerce_field">
    <label for="iwbvel-variations-bulk-actions-manage-stock"><?php esc_html_e('Manage stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="iwbvel-variations-bulk-actions-manage-stock" data-field="value">
        <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="yes"><?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
        <option value="no"><?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
    </select>
</div>
<div class="iwbvel-form-group" data-name="regular_price" data-type="woocommerce_field">
    <label for="iwbvel-variations-bulk-actions-regular-price"><?php esc_html_e('Regular price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="" data-field="operator">
        <?php
        if (!empty($regular_price_operator)) :
            foreach ($regular_price_operator as $key => $value) :
        ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
        <?php
            endforeach;
        endif;
        ?>
    </select>
    <input type="number" id="iwbvel-variations-bulk-actions-regular-price" data-field="value" value="">
</div>
<div class="iwbvel-form-group" data-name="sale_price" data-type="woocommerce_field">
    <label for="iwbvel-variations-bulk-actions-sale-price"><?php esc_html_e('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="" data-field="operator">
        <?php
        if (!empty($sale_price_operator)) :
            foreach ($sale_price_operator as $key => $value) :
        ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
        <?php
            endforeach;
        endif;
        ?>
    </select>
    <input type="number" id="iwbvel-variations-bulk-actions-sale-price" data-field="value" value="">
</div>
<div class="iwbvel-form-group" data-name="sku" data-type="woocommerce_field">
    <label for="iwbvel-variations-bulk-actions-sku"><?php esc_html_e('SKU', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <select name="" id="" data-field="operator">
        <?php
        if (!empty($edit_text_operator)) :
            foreach ($edit_text_operator as $key => $value) :
        ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
        <?php
            endforeach;
        endif;
        ?>
    </select>
    <input type="text" id="iwbvel-variations-bulk-actions-sku" data-field="value">
    <select data-field="variable" class="iwbvel-bulk-edit-form-variable">
        <?php foreach (iwbvel\classes\helpers\Product_Helper::get_text_variable_options() as $key => $value) : ?>
            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="iwbvel-form-group" data-name="description">
    <label for="iwbvel-variations-bulk-actions-description"><?php esc_html_e('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
    <textarea id="" disabled="disabled"></textarea>
    <span class="iwbvel-short-description"><?php esc_html_e('Upgrade to pro version', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
</div>
<div class="iwbvel-form-group" data-name="image_id">
    <div>
        <label>Image</label>
        <button type="button" disabled="disabled" class="iwbvel-button iwbvel-button-blue iwbvel-float-left">Choose image</button>
        <span class="iwbvel-short-description"><?php esc_html_e('Upgrade to pro version', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
    </div>
</div>