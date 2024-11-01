<?php
/*
Plugin Name: iThemeland Bulk Product Editing Lite For WooCommerce
Plugin URI: https://www.ithemelandco.com/Plugins/Pro-Bulk-Editing/woocommerce-bulk-product-editing
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by WooCommerce Bulk Product Editor.
Author: iThemelandco
Tested up to: WP 5.3
Requires PHP: 5.4
Tags: woocommerce,woocommerce bulk edit,bulk edit,bulk,products bulk editor
Text Domain: ithemeland-bulk-product-editing-lite-for-woocommerce
Requires Plugins: woocommerce
WC requires at least: 3.9
WC tested up to: 8.9
Requires at least: 4.4
Version: 3.8.0
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

if (defined('WCBEL_NAME')) {
    return false;
}

require_once __DIR__ . '/vendor/autoload.php';

define('WCBEL_NAME', 'ithemeland-bulk-product-editing-lite-for-woocommerce');
define('WCBEL_LABEL', 'iThemeland Bulk Product Editing Lite For WooCommerce');
define('WCBEL_DESCRIPTION', 'Be professionals with managing data in the reliable and flexible way!');
define('WCBEL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WCBEL_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=wcbel'));
define('WCBEL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WCBEL_LIB_DIR', trailingslashit(WCBEL_DIR . 'classes/lib'));
define('WCBEL_VIEWS_DIR', trailingslashit(WCBEL_DIR . 'views'));
define('WCBEL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('WCBEL_ASSETS_DIR', trailingslashit(WCBEL_DIR . 'assets'));
define('WCBEL_ASSETS_URL', trailingslashit(WCBEL_URL . 'assets'));
define('WCBEL_CSS_URL', trailingslashit(WCBEL_ASSETS_URL . 'css'));
define('WCBEL_IMAGES_URL', trailingslashit(WCBEL_ASSETS_URL . 'images'));
define('WCBEL_JS_URL', trailingslashit(WCBEL_ASSETS_URL . 'js'));
define('WCBEL_VERSION', '3.8.0');
define('WCBEL_UPGRADE_URL', 'https://ithemelandco.com/plugins/woocommerce-bulk-product-editing?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=user-lite-buy');
define('WCBEL_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['wcbel\classes\bootstrap\WCBEL', 'activate']);
register_deactivation_hook(__FILE__, ['wcbel\classes\bootstrap\WCBEL', 'deactivate']);

add_action('init', ['wcbel\classes\bootstrap\WCBEL', 'wcbel_wp_init']);

// compatible with woocommerce custom order tables
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

add_action('plugins_loaded', function () {
    if (!class_exists('WooCommerce')) {
        wcbel\classes\bootstrap\WCBEL::wcbel_woocommerce_required();
    } else {
        \wcbel\classes\bootstrap\WCBEL::init();
    }
}, PHP_INT_MAX);
