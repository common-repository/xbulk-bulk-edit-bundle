<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select class="wcbel-bulk-edit-form-variable" title="<?php esc_html_e('Select Variable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-field="variable">
    <option value=""><?php esc_html_e('Variable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="title"><?php esc_html_e('Title', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="id"><?php esc_html_e('ID', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="sku"><?php esc_html_e('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="menu_order"><?php esc_html_e('Menu Order', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="parent_id"><?php esc_html_e('Parent ID', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="parent_title"><?php esc_html_e('Parent Title', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="parent_sku"><?php esc_html_e('Parent SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="regular_price"><?php esc_html_e('Regular Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
    <option value="sale_price"><?php esc_html_e('Sale Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
</select>