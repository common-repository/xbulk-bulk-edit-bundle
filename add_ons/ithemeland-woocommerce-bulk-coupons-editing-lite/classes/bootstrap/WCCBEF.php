<?php

namespace wccbef\classes\bootstrap;

use wccbef\classes\controllers\WCCBEF_Ajax;
use wccbef\classes\controllers\WCCBEF_Post;
use wccbef\classes\controllers\Woo_Coupon_Controller;
use wccbef\classes\repositories\Option;

class WCCBEF
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

        WCCBEF_Ajax::register_callback();
        WCCBEF_Post::register_callback();
        (new WCCBEF_Meta_Fields())->init();
        (new WCCBEF_Custom_Queries())->init();

        // update all options
        (new Option())->update_options('wccbef');

        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);
    }

    public static function wccbef_woocommerce_required()
    {
        include WCCBEF_VIEWS_DIR . 'alerts/wccbef_woocommerce_required.php';
    }

    public static function wccbef_wp_init()
    {
        $version = get_option('wccbef_version');
        if (empty($version) || $version != WCCBEF_VERSION) {
            update_option('wccbef_version', WCCBEF_VERSION);
        }

        // load textdomain
        load_plugin_textdomain('ithemeland-woocommerce-bulk-coupon-editing-lite', false, WCCBEF_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        add_submenu_page('wbebl', esc_html__('Woo Coupons', WBEBL_NAME), esc_html__('Woo Coupons', WBEBL_NAME), 'manage_woocommerce', 'wccbef', [new Woo_Coupon_Controller(), 'index'], 1);
    }

    public function load_assets($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wccbef') {
            // Styles
            wp_enqueue_style('wccbef-reset', WCCBEF_CSS_URL . 'reset.css');
            wp_enqueue_style('wccbef-LineIcons', WCCBEF_CSS_URL . 'LineIcons.min.css');
            wp_enqueue_style('wccbef-select2', WCCBEF_CSS_URL . 'select2.min.css');
            wp_enqueue_style('wccbef-sweetalert', WCCBEF_CSS_URL . 'sweetalert.css');
            wp_enqueue_style('wccbef-jquery-ui', WCCBEF_CSS_URL . 'jquery-ui.min.css');
            wp_enqueue_style('wccbef-tipsy', WCCBEF_CSS_URL . 'jquery.tipsy.css');
            wp_enqueue_style('wccbef-datetimepicker', WCCBEF_CSS_URL . 'jquery.datetimepicker.css');
            wp_enqueue_style('wccbef-scrollbar', WCCBEF_CSS_URL . 'jquery.scrollbar.css');
            wp_enqueue_style('wccbef-main', WCCBEF_CSS_URL . 'style.css', [], '2.1.2');

            // Scripts
            wp_enqueue_script('wccbef-datetimepicker', WCCBEF_JS_URL . 'jquery.datetimepicker.js', ['jquery']);
            wp_enqueue_script('wccbef-functions', WCCBEF_JS_URL . 'functions.js', ['jquery'], '6.7');
            wp_enqueue_script('wccbef-select2', WCCBEF_JS_URL . 'select2.min.js', ['jquery']);
            wp_enqueue_script('wccbef-moment', WCCBEF_JS_URL . 'moment-with-locales.min.js', ['jquery']);
            wp_enqueue_script('wccbef-tipsy', WCCBEF_JS_URL . 'jquery.tipsy.js', ['jquery']);
            wp_enqueue_script('wccbef-scrollbar', WCCBEF_JS_URL . 'jquery.scrollbar.min.js', ['jquery']);
            wp_enqueue_script('wccbef-sweetalert', WCCBEF_JS_URL . 'sweetalert.min.js', ['jquery']);
            wp_enqueue_script('wccbef-main', WCCBEF_JS_URL . 'main.js', ['jquery'], '6.7');
            wp_localize_script('wccbef-main', 'WCCBEF_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'wp_nonce' => wp_create_nonce(),
            ]);
            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
        }
    }

    public static function activate()
    {
        // set plugin version
        update_option('wccbef_version', WCCBEF_VERSION);
    }

    public static function deactivate()
    {
        $option_repository = new Option();
        $option_repository->delete_options_with_like_name('wccbef');
    }
}
