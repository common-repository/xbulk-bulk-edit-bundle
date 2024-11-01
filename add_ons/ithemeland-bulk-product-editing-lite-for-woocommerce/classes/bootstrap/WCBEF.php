<?php

namespace wcbef\classes\bootstrap;

use wcbef\classes\controllers\WCBEF_Ajax;
use wcbef\classes\controllers\WCBEF_Post;
use wcbef\classes\helpers\Lang_Helper;
use wcbef\classes\controllers\Woocommerce_Bulk_Edit_Free;
use wcbef\classes\repositories\Search;
use wcbef\classes\repositories\Setting;

class WCBEF
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
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);

        $this->reset_filter_data();
        WCBEF_Ajax::register_callback();
        WCBEF_Post::register_callback();
        (new WCBEF_Meta_Fields())->init();
        (new WCBEF_Custom_Queries())->init();
    }

    public static function wcbef_woocommerce_required()
    {
        include WCBEF_VIEWS_DIR . 'alerts/wcbef_woocommerce_required.php';
    }

    private function reset_filter_data()
    {
        $reset_version = '2.8.2';
        $last_reset = get_option('wcbef_reset_filter_data_version', null);

        if (empty($last_reset) || version_compare($last_reset, $reset_version, '<')) {
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => [],
            ]);

            update_option('wcbef_reset_filter_data_version', $reset_version);
        }
    }

    public static function wcbef_wp_init()
    {
        $version = get_option('wcbef_version');
        if (empty($version) || $version != WCBEF_VERSION) {
            update_option('wcbef_version', WCBEF_VERSION);
        }
        // load textdomain
        load_plugin_textdomain('woocommerce-bulk-edit-free', false, WCBEF_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        add_submenu_page('wbebl', esc_html__('Woo Products', WBEBL_NAME), esc_html__('Woo Products', WBEBL_NAME), 'manage_woocommerce', 'wcbef', [new Woocommerce_Bulk_Edit_Free(), 'index'], 1);
    }

    public function load_assets($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wcbef') {
            $setting_repository = new Setting();

            // Styles
            wp_enqueue_style('wcbef-reset', WCBEF_CSS_URL . 'reset.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-LineIcons', WCBEF_CSS_URL . 'LineIcons.min.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-datepicker', WCBEF_CSS_URL . 'bootstrap-material-datetimepicker.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-select2', WCBEF_CSS_URL . 'select2.min.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-sweetalert', WCBEF_CSS_URL . 'sweetalert.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-jquery-ui', WCBEF_CSS_URL . 'jquery-ui.min.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-tipsy', WCBEF_CSS_URL . 'jquery.tipsy.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-datetimepicker', WCBEF_CSS_URL . 'jquery.datetimepicker.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-scrollbar', WCBEF_CSS_URL . 'jquery.scrollbar.css', [], WCBEF_VERSION);
            wp_enqueue_style('wcbef-main', WCBEF_CSS_URL . 'style.css', [], WCBEF_VERSION);
            wp_enqueue_style('wp-color-picker');

            // Scripts
            wp_enqueue_script('wcbef-datetimepicker', WCBEF_JS_URL . 'jquery.datetimepicker.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-functions', WCBEF_JS_URL . 'functions.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-functions', WCBEF_JS_URL . 'functions.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-select2', WCBEF_JS_URL . 'select2.min.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-moment', WCBEF_JS_URL . 'moment-with-locales.min.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-tipsy', WCBEF_JS_URL . 'jquery.tipsy.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-scrollbar', WCBEF_JS_URL . 'jquery.scrollbar.min.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-bootstrap_datepicker', WCBEF_JS_URL . 'bootstrap-material-datetimepicker.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-sweetalert', WCBEF_JS_URL . 'sweetalert.min.js', ['jquery'], WCBEF_VERSION);
            wp_enqueue_script('wcbef-main', WCBEF_JS_URL . 'main.js', ['jquery'], WCBEF_VERSION);
            wp_localize_script('wcbef-main', 'WCBEF_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'wp_nonce' => wp_create_nonce(),
                'wcbef_settings' => $setting_repository->get_settings()
            ]);
            wp_localize_script('wcbef-main', 'wcbefTranslate', Lang_Helper::get_js_strings());
            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('wp-color-picker');
        }
    }

    public static function activate()
    {
        if (class_exists('wcbef\classes\bootstrap\WCBEF')) {
            // set plugin version
            update_option('wcbef_version', WCBEF_VERSION);
        }
    }

    public static function deactivate()
    {
        // 
    }
}
