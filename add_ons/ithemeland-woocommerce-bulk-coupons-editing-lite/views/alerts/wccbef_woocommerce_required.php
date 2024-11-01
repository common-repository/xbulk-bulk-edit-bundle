<?php
function wccbef_woocommerce_required_error()
{
    $class = 'notice notice-error';
    $message = esc_html__('"iThemeland WooCommerce Bulk Coupons Editing Lite" Plugin needs "WooCommerce" Plugin, Please Install/Activate that.');
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
}

add_action('admin_notices', 'wccbef_woocommerce_required_error');
