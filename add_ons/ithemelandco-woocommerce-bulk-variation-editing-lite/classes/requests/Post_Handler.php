<?php

namespace iwbvel\classes\requests;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\helpers\Sanitizer;
use iwbvel\classes\repositories\Flush_Message;
use iwbvel\classes\repositories\Column;
use iwbvel\classes\repositories\Setting;
use iwbvel\classes\services\activation\Activation_Service;
use iwbvel\classes\services\export\Export_Service;

class Post_Handler
{
    private static $instance;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_post_iwbvel_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_iwbvel_settings', [$this, 'settings']);
        add_action('admin_post_iwbvel_export_products', [$this, 'export_products']);
        add_action('admin_post_iwbvel_save_column_profile', [$this, 'save_column_profile']);
        add_action('admin_post_iwbvel_activation_plugin', [$this, 'activation_plugin']);
    }

    public function load_column_profile()
    {
        if (isset($_POST['preset_key']) && check_admin_referer('iwbvel_load_column_profile')) {
            $preset_key = sanitize_text_field($_POST['preset_key']);
            $checked_columns = Sanitizer::array($_POST["columns"]);
            $checked_columns = array_combine($checked_columns, $checked_columns);
            $column_repository = new Column();
            $columns = [];
            $fields = $column_repository->get_fields();
            $preset_columns = $column_repository->get_preset($preset_key);
            if (!empty($checked_columns) && is_array($checked_columns)) {
                if (!empty($preset_columns['fields'])) {
                    foreach ($preset_columns['fields'] as $column_key => $preset_column) {
                        if (isset($checked_columns[$column_key])) {
                            $columns[$column_key] = $preset_column;
                            unset($checked_columns[$column_key]);
                        }
                    }
                }
                if (!empty($checked_columns)) {
                    foreach ($checked_columns as $diff_item) {
                        if (isset($fields[$diff_item])) {
                            $checked_column = [
                                'name' => $fields[$diff_item]['name'],
                                'label' => $fields[$diff_item]['label'],
                                'title' => $fields[$diff_item]['label'],
                                'editable' => $fields[$diff_item]['editable'],
                                'content_type' => $fields[$diff_item]['content_type'],
                                'allowed_type' => $fields[$diff_item]['allowed_type'],
                                'update_type' => $fields[$diff_item]['update_type'],
                                'background_color' => '#fff',
                                'text_color' => '#444',
                            ];
                            if (isset($fields[$diff_item]['sortable'])) {
                                $checked_column['sortable'] = ($fields[$diff_item]['sortable']);
                            }
                            if (isset($fields[$diff_item]['sub_name'])) {
                                $checked_column['sub_name'] = ($fields[$diff_item]['sub_name']);
                            }
                            if (isset($fields[$diff_item]['options'])) {
                                $checked_column['options'] = $fields[$diff_item]['options'];
                            }
                            if (isset($fields[$diff_item]['field_type'])) {
                                $checked_column['field_type'] = $fields[$diff_item]['field_type'];
                            }
                            $columns[$diff_item] = $checked_column;
                        }
                    }
                }
            }
            $column_repository->set_active_columns($preset_key, $columns);
        }

        $this->redirect();
    }

    public function settings()
    {
        if (!check_admin_referer('iwbvel_settings')) {
            die();
        }

        if (isset($_POST['settings'])) {
            $setting_repository = new Setting();
            $setting_repository->update(Sanitizer::array($_POST['settings']));
        }

        $this->redirect([
            'message' => __('Success !', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function export_products()
    {
        if (!check_admin_referer('iwbvel_export_products')) {
            die();
        }

        if (empty($_POST['products']) || empty($_POST['fields'])) {
            $this->redirect([
                'message' => __('Error ! try again', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'type' => 'danger'
            ]);
        }

        $export_service = Export_Service::get_instance();
        $export_service->set_data([
            'delimiter' => sanitize_text_field($_POST['iwbvel_export_delimiter']),
            'select_type' => sanitize_text_field($_POST['products']),
            'field_type' => sanitize_text_field($_POST['fields']),
            'selected_ids' => isset($_POST['item_ids']) ? Sanitizer::array($_POST['item_ids']) : [],
        ]);
        $export_service->perform();

        $this->redirect();
    }

    public function activation_plugin()
    {
        if (!check_admin_referer('iwbvel_activation_plugin')) {
            die();
        }

        $message = "Error! Try again";

        if (isset($_POST['activation_type'])) {
            if ($_POST['activation_type'] == 'skip') {
                update_option('iwbvel_is_active', 'skipped');
                return $this->redirect('bulk-edit');
            } else {
                if (!empty($_POST['email']) && !empty($_POST['industry'])) {
                    $activation_service = new Activation_Service();
                    $info = $activation_service->activation([
                        'email' => sanitize_email($_POST['email']),
                        'domain' => sanitize_text_field($_SERVER['SERVER_NAME']),
                        'product_id' => 'iwbvel',
                        'product_name' => IWBVEL_LABEL,
                        'industry' => sanitize_text_field($_POST['industry']),
                        'multi_site' => is_multisite(),
                        'core_version' => null,
                        'subsystem_version' => IWBVEL_VERSION,
                    ]);

                    if (!empty($info) && is_array($info)) {
                        if (!empty($info['result']) && $info['result'] == true) {
                            update_option('iwbvel_is_active', 'yes');
                            $message = esc_html__('Success !', 'ithemelandco-woocommerce-bulk-variation-editing-lite');
                        } else {
                            update_option('iwbvel_is_active', 'no');
                            $message = (!empty($info['message'])) ? esc_html($info['message']) : esc_html__('System Error !', 'ithemelandco-woocommerce-bulk-variation-editing-lite');
                        }
                    } else {
                        update_option('iwbvel_is_active', 'no');
                        $message = esc_html__('Connection Timeout! Please Try Again', 'ithemelandco-woocommerce-bulk-variation-editing-lite');
                    }
                }
            }
        }

        $this->redirect($message);
    }

    private function redirect($notice = [])
    {
        if (!empty($notice) && isset($notice['message'])) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set($notice);
        }

        return wp_redirect(IWBVEL_PLUGIN_MAIN_PAGE);
    }
}
