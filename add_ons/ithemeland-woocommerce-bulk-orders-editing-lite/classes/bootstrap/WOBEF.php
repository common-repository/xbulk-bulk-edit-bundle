<?php

namespace wobef\classes\bootstrap;

use wobef\classes\controllers\WOBEF_Ajax;
use wobef\classes\controllers\WOBEF_Post;
use wobef\classes\controllers\Woo_Order_Controller;
use wobef\classes\repositories\Option;

class WOBEF
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

        WOBEF_Ajax::register_callback();
        WOBEF_Post::register_callback();
        (new WOBEF_Meta_Fields())->init();
        (new WOBEF_Custom_Queries())->init();

        // update all options
        (new Option())->update_options('wobef');

        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);
    }

    public static function wobef_woocommerce_required()
    {
        include WOBEF_VIEWS_DIR . 'alerts/wobef_woocommerce_required.php';
    }

    public static function wobef_wp_init()
    {
        $version = get_option('wobef_version');
        if (empty($version) || $version != WOBEF_VERSION) {
            update_option('wobef_version', WOBEF_VERSION);
        }

        // load textdomain
        load_plugin_textdomain('ithemeland-woocommerce-bulk-order-editing-lite', false, WOBEF_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        add_submenu_page('wbebl', esc_html__('Woo Orders', WBEBL_NAME), esc_html__('Woo Orders', WBEBL_NAME), 'manage_woocommerce', 'wobef', [new Woo_Order_Controller(), 'index'], 1);
    }

    public function load_assets($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wobef') {
            // Styles
            wp_enqueue_style('wobef-reset', WOBEF_CSS_URL . 'reset.css');
            wp_enqueue_style('wobef-LineIcons', WOBEF_CSS_URL . 'LineIcons.min.css');
            wp_enqueue_style('wobef-select2', WOBEF_CSS_URL . 'select2.min.css');
            wp_enqueue_style('wobef-sweetalert', WOBEF_CSS_URL . 'sweetalert.css');
            wp_enqueue_style('wobef-jquery-ui', WOBEF_CSS_URL . 'jquery-ui.min.css');
            wp_enqueue_style('wobef-tipsy', WOBEF_CSS_URL . 'jquery.tipsy.css');
            wp_enqueue_style('wobef-datetimepicker', WOBEF_CSS_URL . 'jquery.datetimepicker.css');
            wp_enqueue_style('wobef-scrollbar', WOBEF_CSS_URL . 'jquery.scrollbar.css');
            wp_enqueue_style('wobef-main', WOBEF_CSS_URL . 'style.css', [], '2.1.0');
            wp_enqueue_style('wp-color-picker');

            // Scripts
            wp_enqueue_script('wobef-datetimepicker', WOBEF_JS_URL . 'jquery.datetimepicker.js', ['jquery']);
            wp_enqueue_script('wobef-functions', WOBEF_JS_URL . 'functions.js', ['jquery'], '6.7');
            wp_enqueue_script('wobef-select2', WOBEF_JS_URL . 'select2.min.js', ['jquery']);
            wp_enqueue_script('wobef-moment', WOBEF_JS_URL . 'moment-with-locales.min.js', ['jquery']);
            wp_enqueue_script('wobef-tipsy', WOBEF_JS_URL . 'jquery.tipsy.js', ['jquery']);
            wp_enqueue_script('wobef-scrollbar', WOBEF_JS_URL . 'jquery.scrollbar.min.js', ['jquery']);
            wp_enqueue_script('wobef-sweetalert', WOBEF_JS_URL . 'sweetalert.min.js', ['jquery']);
            wp_enqueue_script('wobef-main', WOBEF_JS_URL . 'main.js', ['jquery'], '6.7');
            wp_localize_script('wobef-main', 'WOBEF_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'wp_nonce' => wp_create_nonce(),
            ]);
            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('wp-color-picker');
        }
    }

    public static function activate()
    {
        update_option('wobef_version', WOBEF_VERSION);
    }

    public static function deactivate()
    {
        // clear options
        $option_repository = new Option();
        $option_repository->delete_options_with_like_name('wobef');
    }
}
