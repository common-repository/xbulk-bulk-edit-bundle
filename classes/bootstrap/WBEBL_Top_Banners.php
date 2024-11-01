<?php

namespace wbebl\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wbebl\classes\helpers\Sanitizer;

class WBEBL_Top_Banners
{
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if (get_option('wbebl_free_gifts_plugin_banner_dismissed', 'no') == 'no') {
            add_action('admin_notices', [$this, 'set_free_gifts_plugin_banner']);
            add_action('admin_post_wbebl_free_gifts_plugin_banner_dismiss', [$this, 'free_gifts_plugin_banner_dismiss']);
        }
    }

    public function set_free_gifts_plugin_banner()
    {
        $url = 'https://ithemelandco.com/plugins/free-gifts-for-woocommerce?utm_source=pro_plugins&utm_medium=plugin_links&utm_campaign=user-pro-buy';
        $output = '<style>
        .wbebl-dismiss-banner{
            position: absolute;
            top: 5px;
            right: 5px;
            color:#868686;
            border:0;
            padding: 0;
            background:transparent;
            cursor:pointer;
        }

        .wbebl-dismiss-banner i{
            color:#fff;
            font-size: 16px;
            vertical-align: middle;
        }

        .wbebl-dismiss-banner:hover,
        .wbebl-dismiss-banner:focus{
            color:#fff;
        }

        .wbebl-wrap{
            width: 94%;
            margin: 0 3%;
        }
        </style>';
        $wrap = (!empty($_GET['page']) && in_array($_GET['page'], ['wbebl', 'wbebl-add-ons', 'wcbe', 'wccbe', 'wobe', 'wpbe', 'wbebl', 'wbebll', 'wbebll-reports', 'wbebl-reports'])) ? 'wbebl-wrap' : 'wrap';
        $output .= '<div class="' . $wrap . '"><div style="width: 100%; height: 100px; display: inline-block; text-align: left; margin: 10px 0; background: url(' . WBEBL_ASSETS_URL . 'images/gift-bulk-banner-middle.jpg) repeat-x;">';
        $output .= '<a style="width: 100%; float: left; position: relative;" href="' . esc_url($url) . '" target="_blank">';
        $output .= '<img style="float: left;" src="' . WBEBL_ASSETS_URL . 'images/gift-bulk-banner-start.jpg">';
        $output .= '<img style="float: right;" src="' . WBEBL_ASSETS_URL . 'images/gift-bulk-banner-end.jpg">';
        $output .= '<form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="wbebl_free_gifts_plugin_banner_dismiss"><button class="wbebl-dismiss-banner" type="submit"><i class="dashicons dashicons-dismiss"></i></button></form>';
        $output .= '</a>';
        $output .= '</div></div>';

        echo wp_kses($output, Sanitizer::allowed_html_tags());
    }

    public function free_gifts_plugin_banner_dismiss()
    {
        update_option('wbebl_free_gifts_plugin_banner_dismissed', 'yes');
        return wp_safe_redirect(wp_get_referer());
    }
}
