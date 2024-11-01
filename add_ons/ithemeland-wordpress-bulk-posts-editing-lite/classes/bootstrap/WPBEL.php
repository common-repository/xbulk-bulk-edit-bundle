<?php

namespace wpbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\controllers\WPBEL_Ajax;
use wpbel\classes\controllers\WPBEL_Post;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Common;
use wpbel\classes\repositories\Option;
use wpbel\classes\repositories\Search;
use wpbel\classes\repositories\Setting;

class WPBEL
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
        if (!current_user_can('edit_posts')) {
            return;
        }

        add_filter('safe_style_css', function ($styles) {
            $styles[] = 'display';
            return $styles;
        });

        WPBEL_Top_Banners::register();

        (new Option())->update_options('wpbel', ['wpbel_meta_fields']);

        $this->set_common_items();
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        WPBEL_Ajax::register_callback();
        WPBEL_Post::register_callback();
        (new WPBEL_Custom_Queries())->init();
        (new WPBEL_Meta_Fields())->init();
    }

    private function set_common_items()
    {
        $common_repository = new Common();
        $common_items = $common_repository->get_items();
        if (!isset($common_items['active_post_type'])) {
            $common_repository->update([
                'active_post_type' => "post"
            ]);
        }
        $GLOBALS['wpbel_common'] = $common_repository->get_items();
    }

    public static function wpbel_wp_init()
    {
        // load textdomain
        load_plugin_textdomain('ithemeland-wordpress-bulk-posts-editing-lite', false, WPBEL_LANGUAGES_DIR);

        $version = get_option('wpbel-version');
        if (empty($version) || $version != WPBEL_VERSION) {
            update_option('wpbel-version', WPBEL_VERSION);
        }
    }

    public function add_menu()
    {
        if (defined('WBEBL_NAME')) {
            add_submenu_page('wbebl', esc_html__('WP Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'), esc_html__('WP Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'), 'edit_posts', 'wpbel', ['wpbel\classes\controllers\Wordpress_Posts_Bulk_Edit', 'init'], 1);
        } else {
            add_menu_page(esc_html__('iT WP Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'), sprintf('%s', '<span style="color: #627ddd;font-weight: 900;">iT</span> WP Posts'), 'edit_posts', 'wpbel', ['wpbel\classes\controllers\Wordpress_Posts_Bulk_Edit', 'init'], WPBEL_IMAGES_URL . 'wpbel_icon.svg', 2);
        }
    }

    public function enqueue_scripts($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wpbel') {
            if (WPBEL_Verification::is_active() || defined('WBEBL_NAME')) {
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
        wp_enqueue_style('wpbel-reset', WPBEL_CSS_URL . 'reset.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-icomoon', WPBEL_CSS_URL . 'icomoon.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-datepicker', WPBEL_CSS_URL . 'bootstrap-material-datetimepicker.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-select2', WPBEL_CSS_URL . 'select2.min.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-sweetalert', WPBEL_CSS_URL . 'sweetalert.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-jquery-ui', WPBEL_CSS_URL . 'jquery-ui.min.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-tipsy', WPBEL_CSS_URL . 'jquery.tipsy.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-datetimepicker', WPBEL_CSS_URL . 'jquery.datetimepicker.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-main', WPBEL_CSS_URL . 'style.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-main', WPBEL_CSS_URL . 'style.css', [], WPBEL_VERSION);
        wp_enqueue_style('wp-color-picker');

        // Scripts
        wp_enqueue_script('wpbel-datetimepicker', WPBEL_JS_URL . 'jquery.datetimepicker.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-functions', WPBEL_JS_URL . 'functions.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-functions', WPBEL_JS_URL . 'functions.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-select2', WPBEL_JS_URL . 'select2.min.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-moment', WPBEL_JS_URL . 'moment-with-locales.min.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-tipsy', WPBEL_JS_URL . 'jquery.tipsy.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-bootstrap_datepicker', WPBEL_JS_URL . 'bootstrap-material-datetimepicker.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-sweetalert', WPBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-main', WPBEL_JS_URL . 'main.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-main', WPBEL_JS_URL . 'main.js', ['jquery'], WPBEL_VERSION);
        wp_localize_script('wpbel-main', 'WPBEL_DATA', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('wpbel_ajax_nonce'),
            'strings' => [
                'please_select_one_item' => __('Please select one post', 'ithemeland-woocommerce-bulk-posts-editing')
            ],
            'wpbel_settings' => $setting_repository->get_settings()
        ]);
        wp_enqueue_media();
        wp_enqueue_editor();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('wp-color-picker');
    }

    private function activation_enqueue_scripts()
    {
        wp_enqueue_style('wpbel-reset', WPBEL_CSS_URL . 'reset.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-sweetalert', WPBEL_CSS_URL . 'sweetalert.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-main', WPBEL_CSS_URL . 'style.css', [], WPBEL_VERSION);
        wp_enqueue_style('wpbel-activation', WPBEL_CSS_URL . 'activation.css', [], WPBEL_VERSION);

        wp_enqueue_script('wpbel-sweetalert', WPBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WPBEL_VERSION);
        wp_enqueue_script('wpbel-activation', WPBEL_JS_URL . 'activation.js', ['jquery'], WPBEL_VERSION);
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
        self::create_tables();

        // set plugin version
        update_option('wpbel-version', WPBEL_VERSION);

        // set default column profile
        (new Column('post'))->set_default_columns();

        // set default filter profile
        (new Search('post'))->set_default_item();
    }

    public static function deactivate()
    {
        // 
    }
}
