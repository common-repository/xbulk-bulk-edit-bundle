<?php

use iwbvel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function iwbvel_woocommerce_required_error()
{
    $message = esc_html__('"iThemeland WooCommerce Bulk Product Editing" Plugin needs "WooCommerce" Plugin, Please Install/Activate that.');
    echo wp_kses('<div class="notice notice-error"><p>' . $message . '</p></div>', Sanitizer::allowed_html());
}

add_action('admin_notices', 'iwbvel_woocommerce_required_error');
