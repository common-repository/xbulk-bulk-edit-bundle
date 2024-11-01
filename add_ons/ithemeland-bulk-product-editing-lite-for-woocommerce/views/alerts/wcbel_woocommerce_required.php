<?php

use wcbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function wcbel_woocommerce_required_error()
{
    echo wp_kses('<div class="notice notice-error"><p>' . esc_html__('"iThemeland WooCommerce Bulk Product Editing" Plugin needs "WooCommerce" Plugin, Please Install/Activate that.', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . '</p></div>', Sanitizer::allowed_html_tags());
}

add_action('admin_notices', 'wcbel_woocommerce_required_error');
