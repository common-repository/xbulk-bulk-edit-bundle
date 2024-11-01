<?php

namespace wcbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\controllers\WCBEL_Ajax;
use wcbel\classes\controllers\WCBEL_Post;
use wcbel\classes\helpers\Lang_Helper;
use wcbel\classes\repositories\Option;
use wcbel\classes\repositories\Search;
use wcbel\classes\repositories\Setting;

class WCBEL
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_filter('safe_style_css', function ($styles) {
            $styles[] = 'display';
            return $styles;
        });

        WCBEL_Top_Banners::register();

        $this->reset_filter_data();
        WCBEL_Ajax::register_callback();
        WCBEL_Post::register_callback();
        (new Option())->update_options('wcbel', ['wcbel_meta_fields']);
        (new WCBEL_Meta_Fields())->init();
        (new WCBEL_Custom_Queries())->init();
    }

    public static function wcbel_woocommerce_required()
    {
        include WCBEL_VIEWS_DIR . 'alerts/wcbel_woocommerce_required.php';
    }

    private function reset_filter_data()
    {
        $reset_version = '1.0.0';
        $last_reset = get_option('wcbel_reset_filter_data_version', null);

        if (empty($last_reset) || version_compare($last_reset, $reset_version, '<')) {
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => [],
            ]);

            update_option('wcbel_reset_filter_data_version', $reset_version);
        }
    }

    public static function wcbel_wp_init()
    {
        $version = get_option('wcbel-version');
        if (empty($version) || $version != WCBEL_VERSION) {
            update_option('wcbel-version', WCBEL_VERSION);
        }
        // load textdomain
        load_plugin_textdomain('ithemeland-bulk-product-editing-lite-for-woocommerce', false, WCBEL_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        if (defined('WBEBL_NAME')) {
            add_submenu_page('wbebl', esc_html__('Woo Products', 'ithemeland-woocommerce-bulk-coupons-editing-lite'), esc_html__('Woo Products', 'ithemeland-woocommerce-bulk-coupons-editing-lite'), 'manage_woocommerce', 'wcbel', ['wcbel\classes\controllers\Woocommerce_Bulk_Edit', 'init'], 1);
        } else {
            add_menu_page(esc_html__('iT Woo Products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'), sprintf('%s', '<span style="color: #627ddd;font-weight: 900;">iT</span> Woo Products'), 'manage_woocommerce', 'wcbel', ['wcbel\classes\controllers\Woocommerce_Bulk_Edit', 'init'], WCBEL_IMAGES_URL . 'wcbel_icon.svg', 2);
        }
    }

    public function enqueue_scripts($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wcbel') {
            if (WCBEL_Verification::is_active() || defined('WBEBL_NAME')) {
                $this->main_enqueue_scripts();
            } else {
                $this->activation_enqueue_scripts();
            }
        }
    }

    private function main_enqueue_scripts()
    {
        $setting_repository = new Setting();
        // Styles
        wp_enqueue_style('wcbel-reset', WCBEL_CSS_URL . 'reset.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-icomoon', WCBEL_CSS_URL . 'icomoon.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-datepicker', WCBEL_CSS_URL . 'bootstrap-material-datetimepicker.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-select2', WCBEL_CSS_URL . 'select2.min.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-sweetalert', WCBEL_CSS_URL . 'sweetalert.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-jquery-ui', WCBEL_CSS_URL . 'jquery-ui.min.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-tipsy', WCBEL_CSS_URL . 'jquery.tipsy.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-datetimepicker', WCBEL_CSS_URL . 'jquery.datetimepicker.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-main', WCBEL_CSS_URL . 'style.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-main', WCBEL_CSS_URL . 'style.css', [], WCBEL_VERSION);
        wp_enqueue_style('wp-color-picker');

        // Scripts
        wp_enqueue_script('wcbel-datetimepicker', WCBEL_JS_URL . 'jquery.datetimepicker.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-functions', WCBEL_JS_URL . 'functions.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-functions', WCBEL_JS_URL . 'functions.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-select2', WCBEL_JS_URL . 'select2.min.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-moment', WCBEL_JS_URL . 'moment-with-locales.min.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-tipsy', WCBEL_JS_URL . 'jquery.tipsy.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-bootstrap_datepicker', WCBEL_JS_URL . 'bootstrap-material-datetimepicker.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-sweetalert', WCBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-main', WCBEL_JS_URL . 'main.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-main', WCBEL_JS_URL . 'main.js', ['jquery'], WCBEL_VERSION);
        wp_localize_script('wcbel-main', 'WCBEL_DATA', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('wcbel_ajax_nonce'),
            'strings' => [
                'please_select_one_item' => __('Please select one product', 'ithemeland-bulk-product-editing-lite-for-woocommerce')
            ],
            'wcbel_settings' => $setting_repository->get_settings()
        ]);
        wp_localize_script('wcbel-main', 'wcbelTranslate', Lang_Helper::get_js_strings());
        wp_enqueue_media();
        wp_enqueue_editor();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('wp-color-picker');
    }

    private function activation_enqueue_scripts()
    {
        wp_enqueue_style('wcbel-reset', WCBEL_CSS_URL . 'reset.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-sweetalert', WCBEL_CSS_URL . 'sweetalert.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-main', WCBEL_CSS_URL . 'style.css', [], WCBEL_VERSION);
        wp_enqueue_style('wcbel-activation', WCBEL_CSS_URL . 'activation.css', [], WCBEL_VERSION);

        wp_enqueue_script('wcbel-sweetalert', WCBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WCBEL_VERSION);
        wp_enqueue_script('wcbel-activation', WCBEL_JS_URL . 'activation.js', ['jquery'], WCBEL_VERSION);
    }

    private static function create_tables()
    {
        global $wpdb;
        $history_table_name = esc_sql($wpdb->prefix . 'itbbc_history');
        $history_items_table_name = esc_sql($wpdb->prefix . 'itbbc_history_items');
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
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
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
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
            $result = $wpdb->get_results($wpdb->prepare("SELECT DATA_TYPE as itbbc_field_type FROM information_schema.columns WHERE table_name = %s AND column_name = %s", $history_items_table_name, 'field')); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            if (!empty($result[0]->itbbc_field_type) && $result[0]->itbbc_field_type != 'longtext') {
                $wpdb->query("ALTER TABLE {$history_items_table_name} MODIFY field longtext"); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            }
        }

        if (!empty($query)) {
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($query); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        }
    }

    public static function activate()
    {
        if (!defined('WBEBL_NAME')) {
            update_option('wcbel-version', WCBEL_VERSION);

            self::create_tables();
        }
    }

    public static function deactivate()
    {
        // 
    }
}
