<?php

namespace iwbvel\classes\bootstrap;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\requests\Ajax_Handler;
use iwbvel\classes\requests\Post_Handler;
use iwbvel\classes\helpers\Lang_Helper;
use iwbvel\classes\repositories\Option;
use iwbvel\classes\repositories\Search;
use iwbvel\classes\repositories\Setting;

class IWBVEL
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

        IWBVEL_Top_Banners::register();

        $this->reset_filter_data();
        Ajax_Handler::register_callback();
        Post_Handler::register_callback();
        (new Option())->update_options('iwbvel', ['iwbvel_is_active', 'iwbvel_meta_fields']);
        (new IWBVEL_Meta_Fields())->init();
        (new IWBVEL_Custom_Queries())->init();
    }

    public static function iwbvel_woocommerce_required()
    {
        include IWBVEL_VIEWS_DIR . 'alerts/iwbvel_woocommerce_required.php';
    }

    private function reset_filter_data()
    {
        $reset_version = '1.0.0';
        $last_reset = get_option('iwbvel_reset_filter_data_version', null);

        if (empty($last_reset) || version_compare($last_reset, $reset_version, '<')) {
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => [],
            ]);

            update_option('iwbvel_reset_filter_data_version', $reset_version);
        }
    }

    public static function iwbvel_wp_init()
    {
        $version = get_option('iwbvel-version');
        if (empty($version) || $version != IWBVEL_VERSION) {
            update_option('iwbvel-version', IWBVEL_VERSION);
        }
        // load textdomain
        load_plugin_textdomain('ithemelandco-woocommerce-bulk-variation-editing-lite', false, IWBVEL_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        if (defined('WBEBL_NAME')) {
            add_submenu_page('wbebl', esc_html__('Woo Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'), esc_html__('Woo Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'), 'manage_woocommerce', 'iwbvel', ['iwbvel\classes\controllers\IWBVEL_Bulk_Variations', 'init'], 1);
        } else {
            add_menu_page(esc_html__('iT Woo Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'), sprintf('%s', '<span style="color: #627ddd;font-weight: 900;">iT</span> Woo Variations'), 'manage_woocommerce', 'iwbvel', ['iwbvel\classes\controllers\IWBVEL_Bulk_Variations', 'init'], esc_url(IWBVEL_IMAGES_URL . 'iwbvel_icon.svg'), 2);
        }
    }

    public function enqueue_scripts($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'iwbvel') {
            if (IWBVEL_Verification::is_active() || defined('WBEBL_NAME')) {
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
        wp_enqueue_style('iwbvel-reset', IWBVEL_CSS_URL . 'reset.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-icomoon', IWBVEL_CSS_URL . 'icomoon.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-datepicker', IWBVEL_CSS_URL . 'bootstrap-material-datetimepicker.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-select2', IWBVEL_CSS_URL . 'select2.min.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-sweetalert', IWBVEL_CSS_URL . 'sweetalert.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-jquery-ui', IWBVEL_CSS_URL . 'jquery-ui.min.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-tipsy', IWBVEL_CSS_URL . 'jquery.tipsy.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-datetimepicker', IWBVEL_CSS_URL . 'jquery.datetimepicker.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-main', IWBVEL_CSS_URL . 'style.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-variations', IWBVEL_CSS_URL . 'variations.css', [], IWBVEL_VERSION);
        wp_enqueue_style('wp-color-picker');

        // Scripts
        wp_enqueue_script('iwbvel-datetimepicker', IWBVEL_JS_URL . 'jquery.datetimepicker.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-functions', IWBVEL_JS_URL . 'functions.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-global-functions', IWBVEL_JS_URL . 'global-functions.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-variation-functions', IWBVEL_JS_URL . 'variation-functions.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-select2', IWBVEL_JS_URL . 'select2.min.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-moment', IWBVEL_JS_URL . 'moment-with-locales.min.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-tipsy', IWBVEL_JS_URL . 'jquery.tipsy.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-bootstrap_datepicker', IWBVEL_JS_URL . 'bootstrap-material-datetimepicker.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-sweetalert', IWBVEL_JS_URL . 'sweetalert.min.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-main', IWBVEL_JS_URL . 'main.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-variation-events', IWBVEL_JS_URL . 'variation-events.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-global-events', IWBVEL_JS_URL . 'global-events.js', ['jquery'], IWBVEL_VERSION);
        wp_localize_script('iwbvel-main', 'IWBVEL_DATA', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'wc_product_edit_link' => admin_url("post.php?post={id}&action=edit"),
            'nonce' => wp_create_nonce('iwbvel_ajax_nonce'),
            'strings' => [
                'please_select_one_item' => __('Please select one product', 'ithemelandco-woocommerce-bulk-variation-editing-lite')
            ],
            'iwbvel_settings' => $setting_repository->get_settings()
        ]);

        wp_localize_script('iwbvel-variation-events', 'IWBVEL_VARIATION_DATA', [
            'html' => $this->get_variation_data_html(),
        ]);
        wp_localize_script('iwbvel-global-events', 'iwbvelTranslate', Lang_Helper::get_js_strings());
        wp_enqueue_media();
        wp_enqueue_editor();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('wp-color-picker');
    }

    private function get_variation_data_html()
    {
        ob_start();
        include IWBVEL_VIEWS_DIR . "variations/add_variations/bulk_actions/file-item.php";
        $file_item = ob_get_clean();

        ob_start();
        include IWBVEL_VIEWS_DIR . "variations/add_variations/variations-raw-table-row.php";
        $raw_table_row = ob_get_clean();

        return [
            'empty_table' => '<tr><td colspan="9">No data available</td></tr>',
            'raw_table_row' => $raw_table_row,
            'file_item' => $file_item,
            'thumbnail_default' => esc_url(IWBVEL_IMAGES_URL . "/woocommerce-placeholder-150x150.png"),
            'thumbnail_default_full_size' =>  esc_url(IWBVEL_IMAGES_URL . "/woocommerce-placeholder.png"),
            'combine_item' => '<li class="iwbvel-attribute-selected-term-item {iwbvel-new-item}" data-attribute="" data-term="">
                <span class="iwbvel-combine-attribute-item-taxonomy"></span>
                <span class="iwbvel-combine-attribute-item-term"></span>
                <button type="button" class="iwbvel-attribute-selected-term-remove"><i class="iwbvel-icon-x"></i></button>
            </li>',
        ];
    }

    private function activation_enqueue_scripts()
    {
        wp_enqueue_style('iwbvel-reset', IWBVEL_CSS_URL . 'reset.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-sweetalert', IWBVEL_CSS_URL . 'sweetalert.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-main', IWBVEL_CSS_URL . 'style.css', [], IWBVEL_VERSION);
        wp_enqueue_style('iwbvel-activation', IWBVEL_CSS_URL . 'activation.css', [], IWBVEL_VERSION);

        wp_enqueue_script('iwbvel-sweetalert', IWBVEL_JS_URL . 'sweetalert.min.js', ['jquery'], IWBVEL_VERSION);
        wp_enqueue_script('iwbvel-activation', IWBVEL_JS_URL . 'activation.js', ['jquery'], IWBVEL_VERSION);
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
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($query);
        }
    }

    public static function activate()
    {
        if (!defined('WBEBL_NAME')) {
            update_option('iwbvel-version', IWBVEL_VERSION);

            self::create_tables();
        }
    }

    public static function deactivate()
    {
        // 
    }
}
