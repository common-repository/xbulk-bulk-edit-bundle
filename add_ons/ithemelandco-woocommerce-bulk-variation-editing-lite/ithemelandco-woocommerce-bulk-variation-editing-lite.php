<?php
/*
Plugin Name: iThemeland Bulk Variation Editing Lite For WooCommerce
Plugin URI: https://ithemelandco.com/plugins/woocommerce-variations-bulk-edit
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by WooCommerce Bulk Product Editor.
Author: iThemelandco
Tested up to: WP 5.8.1
Requires PHP: 5.4
Tags: woocommerce,woocommerce bulk edit,bulk edit,bulk,variations bulk editor
Text Domain: ithemelandco-woocommerce-bulk-variation-editing-lite
Domain Path: /languages
Requires Plugins: woocommerce
WC requires at least: 3.9
WC tested up to: 8.9
Requires at least: 4.4
Version: 1.1.0
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

if (defined('IWBVEL_NAME')) {
    return false;
}

require_once __DIR__ . '/vendor/autoload.php';

define('IWBVEL_NAME', 'ithemelandco-woocommerce-bulk-variation-editing-lite');
define('IWBVEL_LABEL', 'iThemeland Bulk Variation Editing Lite For WooCommerce');
define('IWBVEL_DESCRIPTION', 'Be professionals with managing data in the reliable and flexible way!');
define('IWBVEL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('IWBVEL_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=iwbvel'));
define('IWBVEL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('IWBVEL_LIB_DIR', trailingslashit(IWBVEL_DIR . 'classes/lib'));
define('IWBVEL_VIEWS_DIR', trailingslashit(IWBVEL_DIR . 'views'));
define('IWBVEL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('IWBVEL_ASSETS_DIR', trailingslashit(IWBVEL_DIR . 'assets'));
define('IWBVEL_ASSETS_URL', trailingslashit(IWBVEL_URL . 'assets'));
define('IWBVEL_CSS_URL', trailingslashit(IWBVEL_ASSETS_URL . 'css'));
define('IWBVEL_IMAGES_URL', trailingslashit(IWBVEL_ASSETS_URL . 'images'));
define('IWBVEL_JS_URL', trailingslashit(IWBVEL_ASSETS_URL . 'js'));
define('IWBVEL_VERSION', '1.1.0');
define('IWBVEL_UPGRADE_URL', 'https://ithemelandco.com/plugins/woocommerce-variations-bulk-edit?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=user-lite-buy');
define('IWBVEL_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['iwbvel\classes\bootstrap\IWBVEL', 'activate']);
register_deactivation_hook(__FILE__, ['iwbvel\classes\bootstrap\IWBVEL', 'deactivate']);

// compatible with woocommerce custom order tables
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

add_action('init', ['iwbvel\classes\bootstrap\IWBVEL', 'iwbvel_wp_init']);

add_action('plugins_loaded', function () {
    if (!class_exists('WooCommerce')) {
        iwbvel\classes\bootstrap\IWBVEL::iwbvel_woocommerce_required();
    } else {
        \iwbvel\classes\bootstrap\IWBVEL::init();
    }
}, PHP_INT_MAX);
