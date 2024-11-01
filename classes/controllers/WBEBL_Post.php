<?php

namespace wbebl\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wbebl\classes\repositories\flush_message\Flush_Message;
use wbebl\classes\services\activation\Activation_Service;

class WBEBL_Post
{
    private static $instance;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('admin_post_wbebl_activation_plugin', [$this, 'activation_plugin']);
    }

    public function activation_plugin()
    {
        $message = "Error! Try again";

        if (isset($_POST['activation_type'])) {
            if ($_POST['activation_type'] == 'skip') {
                update_option('wbebl_is_active', 'skipped');
                return $this->redirect(WBEBL_DASHBOARD_URL);
            } else {
                if (!empty($_POST['email']) && !empty($_POST['industry'])) {
                    $activation_service = new Activation_Service();
                    $info = $activation_service->activation([
                        'email' => sanitize_email($_POST['email']),
                        'domain' => $_SERVER['SERVER_NAME'],
                        'product_id' => 'wbebl',
                        'product_name' => WBEBL_LABEL,
                        'industry' => sanitize_text_field($_POST['industry']),
                        'multi_site' => is_multisite(),
                        'core_version' => null,
                        'subsystem_version' => WBEBL_VERSION,
                    ]);

                    if (!empty($info) && is_array($info)) {
                        if (!empty($info['result']) && $info['result'] == true) {
                            update_option('wbebl_is_active', 'yes');
                            $message = esc_html__('Success !', 'xbulk-bulk-edit-bundle');
                        } else {
                            update_option('wbebl_is_active', 'no');
                            $message = (!empty($info['message'])) ? esc_html($info['message']) : esc_html__('System Error !', 'xbulk-bulk-edit-bundle');
                        }
                    } else {
                        update_option('wbebl_is_active', 'no');
                        $message = esc_html__('Connection Timeout! Please Try Again', 'xbulk-bulk-edit-bundle');
                    }
                }
            }
        }

        $this->redirect(WBEBL_DASHBOARD_URL, $message);
    }

    private function redirect($url, $message = '')
    {
        if (!empty($message)) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set(['message' => $message]);
        }

        return wp_redirect($url);
    }
}
