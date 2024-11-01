<?php

namespace wbebl\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wbebl\classes\controllers\Active_Plugin_Controller;
use wbebl\classes\controllers\Dashboard_Controller;
use wbebl\classes\controllers\WBEBL_Post;
use wbebl\classes\helpers\Others;

class Bundle
{
    private static $instance = null;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        WBEBL_Buttons_List::init();
        WBEBL_Post::register_callback();
        Add_Ons::init();
    }

    public static function deactivate_plugins()
    {
        if (!function_exists('deactivate_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $active_plugins = [];
        $plugins = [
            Add_Ons::WOO_COUPONS,
            Add_Ons::WOO_ORDERS,
            Add_Ons::WOO_PRODUCTS,
            Add_Ons::WOO_VARIATIONS,
            Add_Ons::WP_POSTS,
        ];

        foreach ($plugins as $plugin) {
            if (is_plugin_active($plugin)) {
                $active_plugins[] = $plugin;
            }
        }

        if (!empty($active_plugins) && count($active_plugins)) {
            $page_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            deactivate_plugins($active_plugins, true);
            wp_redirect($page_url);
            die();
        }

        return true;
    }

    public function add_menu()
    {
        add_menu_page(esc_html__('iT X-Bulk Lite', 'xbulk-bulk-edit-bundle'), sprintf('%s', '<span style="color: #627ddd;font-weight: 900;">iT</span> X-Bulk Lite'), 'manage_options', 'wbebl', [new Dashboard_Controller(), 'index'], WBEBL_IMAGES_URL . 'wbebl_icon.svg', 2);
        add_submenu_page('wbebl', __('Dashboard', 'xbulk-bulk-edit-bundle'), __('Dashboard', 'xbulk-bulk-edit-bundle'), 'manage_options', 'wbebl');
        if (!Others::isAllowedDomain()) {
            add_submenu_page('wbebl', __('Active Plugin', 'xbulk-bulk-edit-bundle'), __('Active Plugin', 'xbulk-bulk-edit-bundle'), 'manage_options', 'wbebl-active-plugin', [new Active_Plugin_Controller(), 'index'], 25);
        }
    }

    public function enqueue_scripts($page)
    {
        if (!empty($_GET['page'])) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            wp_enqueue_style('wbebl-reset', WBEBL_CSS_URL . 'reset.css', [], WBEBL_VERSION);

            if ($_GET['page'] == 'wbebl') { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
                wp_enqueue_style('wbebl-wbebl', WBEBL_CSS_URL . 'wbebl.css', [], WBEBL_VERSION);
            }

            if ($_GET['page'] == 'wbebl-active-plugin') { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
                wp_enqueue_style('wbebl-activation', WBEBL_CSS_URL . 'activation.css', [], WBEBL_VERSION);

                wp_enqueue_script('wbebl-activation', WBEBL_JS_URL . 'activation.js', ['jquery'], WBEBL_VERSION);
            }
        }
    }

    public static function activate()
    {
        //
    }

    public static function deactivate()
    {
        //
    }

    public static function wp_init()
    {
        $version = get_option('wbebl-pro-version');
        if (empty($version) || $version != WBEBL_VERSION) {
            update_option('wbebl-pro-version', WBEBL_VERSION);
            self::create_tables();
        }

        // load textdomain
        load_plugin_textdomain('xbulk-bulk-edit-bundle', false, WBEBL_LANGUAGES_DIR);
    }

    public static function wp_loaded()
    {
    }

    private static function create_tables()
    {
        global $wpdb;
        $history_table_name = esc_sql($wpdb->prefix . 'itbbc_history');
        $history_items_table_name = esc_sql($wpdb->prefix . 'itbbc_history_items');
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) { //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $query .= "CREATE TABLE {$history_table_name} (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  fields text NOT NULL,
                  operation_type varchar(32) NOT NULL,
                  operation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  reverted tinyint(1) NOT NULL DEFAULT '0',
                  sub_system varchar(64) NOT NULL,
                  PRIMARY KEY (id),
                  INDEX (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        $history_items_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_items_table_name));
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) { //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $query .= "CREATE TABLE {$history_items_table_name} (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      history_id int(11) NOT NULL,
                      historiable_id int(11) NOT NULL,
                      field longtext,
                      prev_value longtext,
                      new_value longtext,
                      PRIMARY KEY (id),
                      INDEX (history_id),
                      INDEX (historiable_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

            $query .= "ALTER TABLE {$history_items_table_name} ADD CONSTRAINT itbbc_history_items_history_id_relation FOREIGN KEY (history_id) REFERENCES {$history_table_name} (id) ON DELETE CASCADE ON UPDATE CASCADE;";
        } else {
            $result = $wpdb->get_results("SELECT DATA_TYPE as itbbc_field_type FROM information_schema.columns WHERE table_name = '{$history_items_table_name}' AND column_name = 'field'"); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            if (!empty($result[0]->itbbc_field_type) && $result[0]->itbbc_field_type != 'longtext') {
                $wpdb->query("ALTER TABLE {$history_items_table_name} MODIFY field longtext"); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            }
        }

        if (!empty($query)) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta($query);
        }
    }
}
