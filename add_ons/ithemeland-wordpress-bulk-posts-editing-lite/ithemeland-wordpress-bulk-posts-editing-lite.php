<?php
/*
Plugin Name: iThemeland Wordpress Bulk Posts Editing Lite
Plugin URI: https://www.ithemelandco.com/Plugins/Pro-Bulk-Editing/wordpress-posts-bulk-editing-lite
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by Wordpress Bulk Post Editor.
Author: iThemelandco
Tested up to: WP 5.3
Requires PHP: 5.4
Tags: wordpress bulk edit,bulk edit,bulk,posts bulk editor
Text Domain: ithemeland-wordpress-bulk-posts-editing-lite
Domain Path: /languages
Version: 4.3.0
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

if (defined('WPBEL_NAME')) {
    return false;
}

require_once __DIR__ . '/vendor/autoload.php';

define('WPBEL_NAME', 'ithemeland-wordpress-bulk-posts-editing-lite');
define('WPBEL_LABEL', 'Ithemeland Wordpress Bulk Posts Editing Lite');
define('WPBEL_DESCRIPTION', 'Be professionals with managing data in the reliable and flexible way!');
define('WPBEL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WPBEL_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=wpbel'));
define('WPBEL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WPBEL_LIB_DIR', trailingslashit(WPBEL_DIR . 'classes/lib'));
define('WPBEL_VIEWS_DIR', trailingslashit(WPBEL_DIR . 'views'));
define('WPBEL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('WPBEL_ASSETS_DIR', trailingslashit(WPBEL_DIR . 'assets'));
define('WPBEL_ASSETS_URL', trailingslashit(WPBEL_URL . 'assets'));
define('WPBEL_CSS_URL', trailingslashit(WPBEL_ASSETS_URL . 'css'));
define('WPBEL_IMAGES_URL', trailingslashit(WPBEL_ASSETS_URL . 'images'));
define('WPBEL_JS_URL', trailingslashit(WPBEL_ASSETS_URL . 'js'));
define('WPBEL_VERSION', '4.3.0');
define('WPBEL_UPGRADE_URL', 'https://ithemelandco.com/plugins/wordpress-bulk-posts-editing?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=user-lite-buy');
define('WPBEL_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['wpbel\classes\bootstrap\WPBEL', 'activate']);
register_deactivation_hook(__FILE__, ['wpbel\classes\bootstrap\WPBEL', 'deactivate']);
add_action('init', ['wpbel\classes\bootstrap\WPBEL', 'wpbel_wp_init']);

add_action('plugins_loaded', function () {
    \wpbel\classes\bootstrap\WPBEL::init();
}, PHP_INT_MAX);
