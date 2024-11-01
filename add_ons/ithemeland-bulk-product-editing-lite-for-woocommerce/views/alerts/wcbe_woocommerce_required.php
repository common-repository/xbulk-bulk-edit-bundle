<?php
function wcbef_woocommerce_required_error()
{
    $class = 'notice notice-error';
    $message = esc_html__('"iThemeland WooCommerce Bulk Product Editing" Plugin needs "WooCommerce" Plugin, Please Install/Activate that.');
    printf('<div class="%1$s"><p>%2$s</p></div>', sanitize_text_field($class), sanitize_text_field($message));
}

add_action('admin_notices', 'wcbef_woocommerce_required_error');
