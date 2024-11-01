<?php

namespace wbebl\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Add_Ons
{
    const WOO_COUPONS = 'ithemeland-woocommerce-bulk-coupons-editing-lite/ithemeland-woocommerce-bulk-coupons-editing-lite.php';
    const WOO_ORDERS = 'ithemeland-woocommerce-bulk-orders-editing-lite/ithemeland-woocommerce-bulk-orders-editing-lite.php';
    const WOO_PRODUCTS = 'ithemeland-bulk-product-editing-lite-for-woocommerce/ithemeland-bulk-product-editing-lite-for-woocommerce.php';
    const WOO_VARIATIONS = 'ithemelandco-woocommerce-bulk-variation-editing-lite/ithemelandco-woocommerce-bulk-variation-editing-lite.php';
    const WP_POSTS = 'ithemeland-wordpress-bulk-posts-editing-lite/ithemeland-wordpress-bulk-posts-editing-lite.php';

    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        $is_active = WBEBL_Verification::is_active();
        if ($is_active) {
            $this->plugins_init();
        }
    }

    private function plugins_init()
    {
        $plugins = $this->get_plugins();
        foreach ($plugins as $plugin => $init_class) {
            $plugin_file = WBEBL_ADD_ONS_DIR . $plugin;
            if (file_exists($plugin_file)) {
                include $plugin_file;
                if (class_exists($init_class) && method_exists($init_class, 'init')) {
                    $init_class::init();
                }
            }
        }
    }

    private function get_plugins()
    {
        return [
            self::WOO_COUPONS => '\wccbel\classes\bootstrap\WCCBEL',
            self::WOO_ORDERS => '\wobel\classes\bootstrap\WOBEL',
            self::WOO_PRODUCTS => '\wcbel\classes\bootstrap\WCBEL',
            self::WOO_VARIATIONS => '\iwbvel\classes\bootstrap\IWBVEL',
            self::WP_POSTS => '\wpbel\classes\bootstrap\WPBEL',
        ];
    }
}
