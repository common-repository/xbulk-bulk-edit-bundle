<?php
/*
Plugin Name: xbulk bulk edit bundle
Description: Editing Date in WordPress is very painful. Manage all of them professionally.
Author: iThemelandco
Tested up to: WP 5.3
Requires PHP: 5.4
Tags: bulk edit,bulk,bulk editor
Text Domain: xbulk-bulk-edit-bundle
Domain Path: /languages
Version: 2.1.1
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

if (defined('WBEB_PRO')) {
    $page_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    deactivate_plugins(['xbulk-bulk-edit-bundle-lite/xbulk-bulk-edit-bundle-lite.php'], true);
    header("Location: {$page_url}");
    die();
}

require_once __DIR__ . "/vendor/autoload.php";

add_action('init', ['\wbebl\classes\bootstrap\Bundle', 'wp_init']);
add_action('wp_loaded', ['\wbebl\classes\bootstrap\Bundle', 'wp_loaded']);

register_activation_hook(__FILE__, ['\wbebl\classes\bootstrap\Bundle', 'activate']);
register_deactivation_hook(__FILE__, ['\wbebl\classes\bootstrap\Bundle', 'deactivate']);

add_action('plugins_loaded', function () {
    \wbebl\classes\bootstrap\Bundle::deactivate_plugins();

    define('WBEBL_NAME', 'xbulk-bulk-edit-bundle-lite');
    define('WBEB_LABEL', 'xbulk bulk edit bundle');
    define('WBEB_DESCRIPTION', 'Be professionals with managing data in the reliable and flexible way!');
    define('WBEBL_IS_BUNDLE', true);
    define('WBEBL_PLUGINS_DIR', trailingslashit(ABSPATH . 'wp-content/plugins'));
    define('WBEBL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
    define('WBEBL_ADD_ONS_DIR', trailingslashit(WBEBL_DIR . 'add_ons'));
    define('WBEBL_URL', trailingslashit(plugin_dir_url(__FILE__)));
    define('WBEBL_DASHBOARD_URL', admin_url('admin.php?page=wbebl'));
    define('WBEBL_ACTIVE_PLUGIN_URL', admin_url('admin.php?page=wbebl-active-plugin'));
    define('WBEBL_VIEWS_DIR', trailingslashit(WBEBL_DIR . 'views'));
    define('WBEBL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
    define('WBEBL_LIB_DIR', trailingslashit(WBEBL_DIR . 'lib'));
    define('WBEBL_ASSETS_DIR', trailingslashit(WBEBL_DIR . 'assets'));
    define('WBEBL_ASSETS_URL', trailingslashit(WBEBL_URL . 'assets'));
    define('WBEBL_CSS_URL', trailingslashit(WBEBL_ASSETS_URL . 'css'));
    define('WBEBL_IMAGES_URL', trailingslashit(WBEBL_ASSETS_URL . 'images'));
    define('WBEBL_JS_URL', trailingslashit(WBEBL_ASSETS_URL . 'js'));
    define('WBEBL_VERSION', '2.1.1');

    \wbebl\classes\bootstrap\Bundle::init();
}, PHP_INT_MAX);
