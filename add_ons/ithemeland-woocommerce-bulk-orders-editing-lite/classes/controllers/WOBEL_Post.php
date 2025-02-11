<?php

namespace wobel\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wobel\classes\helpers\Sanitizer;
use wobel\classes\repositories\Flush_Message;
use wobel\classes\repositories\Column;
use wobel\classes\repositories\Order;
use wobel\classes\repositories\Search;
use wobel\classes\repositories\Setting;
use wobel\classes\services\activation\Activation_Service;

class WOBEL_Post
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
        add_action('admin_post_wobel_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_wobel_settings', [$this, 'settings']);
        add_action('admin_post_wobel_export_orders', [$this, 'export_orders']);
        add_action('admin_post_wobel_import_orders', [$this, 'import_orders']);
        add_action('admin_post_wobel_save_column_profile', [$this, 'save_column_profile']);
        add_action('admin_post_wobel_activation_plugin', [$this, 'activation_plugin']);
    }

    public function load_column_profile()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wobel_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['preset_key'])) {
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
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wobel_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['settings'])) {
            $setting_repository = new Setting();
            $setting_repository->update(Sanitizer::array($_POST['settings']));
        }

        $this->redirect([
            'message' => __('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function export_orders()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wobel_post_nonce')) {
            die('403 Forbidden');
        }

        $file_name = "wobel-order-export-" . time() . '.csv';
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");
        $file = fopen('php://output', 'w');
        fwrite($file, chr(239) . chr(187) . chr(191)); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
        $search_repository = new Search();
        $current_data = $search_repository->get_current_data();
        $last_filter_data = !empty($current_data['last_filter_data']) ? $current_data['last_filter_data'] : null;
        $column_repository = new Column();
        $order_repository = Order::get_instance();

        if (isset($_POST['orders'])) {
            switch ($_POST['orders']) {
                case 'all':
                    $args = \wobel\classes\helpers\Order_Helper::set_filter_data_items($last_filter_data, [
                        'fields' => 'ids',
                    ]);
                    $orders = $order_repository->get_orders($args);
                    $order_ids = $orders->orders;
                    break;
                case 'selected':
                    $order_ids = isset($_POST['item_ids']) ? $_POST['item_ids'] : [];
                    break;
            }

            switch ($_POST['fields']) {
                case 'all':
                    $columns = $column_repository->get_fields();
                    $columns['_transaction_id'] = ['label' => "Transaction ID"];
                    $columns['order_notes'] = ['label' => "Order Notes"];
                    break;
                case 'visible':
                    $columns = $column_repository->get_active_columns()['fields'];
                    break;
            }

            $except_columns = $order_repository->get_except_columns_for_export();
            if (!empty($except_columns) && is_array($except_columns)) {
                foreach ($except_columns as $except_column) {
                    if (isset($columns[$except_column])) {
                        unset($columns[$except_column]);
                    }
                }
            }

            if (!empty($order_ids)) {
                $header[] = "id";
                if (!empty($columns)) {
                    foreach ($columns as $column_key => $column) {
                        $header[] = $column_key;
                    }
                }
                fputcsv($file, $header);

                foreach ($order_ids as $order_id) {
                    $output = [];
                    $order_object = $order_repository->get_order(intval($order_id));
                    if ($order_object instanceof \WC_Order) {
                        $order = $order_repository->order_to_array_for_export($order_object);
                        if (!empty($order) && is_array($order)) {
                            $output[] = $order['id'];
                            if (!empty($columns)) {
                                foreach ($columns as $column_key => $column_item) {
                                    if (!isset($column_item['field_type'])) {
                                        if (isset($order[$column_key])) {
                                            $output[] = (is_array($order[$column_key])) ? (string) implode(',', $order[$column_key]) : (string) $order[$column_key];
                                        } else {
                                            $output[] = " ";
                                        }
                                    } else {
                                        switch ($column_item['field_type']) {
                                            case 'custom_field':
                                                $meta_value = $order_object->get_meta($column_key);
                                                if (!empty($meta_value)) {
                                                    if (isset($column_item['content_type']) && $column_item['content_type'] == 'file') {
                                                        $output[] = serialize($meta_value);
                                                    } else {
                                                        $output[] = (is_array($meta_value)) ? sanitize_text_field(implode(',', $meta_value)) : sanitize_text_field($meta_value);
                                                    }
                                                } else {
                                                    $output[] = " ";
                                                }
                                                break;
                                            default:
                                                if (isset($order[$column_key])) {
                                                    $output[] = (is_array($order[$column_key])) ? (string) implode(',', $order[$column_key]) : (string) $order[$column_key];
                                                } else {
                                                    $output[] = " ";
                                                }
                                                break;
                                        }
                                    }
                                }
                                fputcsv($file, $output);
                            }
                        }
                    }
                }
            }
        }
        die();
    }

    public function import_orders()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wobel_post_nonce')) {
            die('403 Forbidden');
        }

        $wobel_upload_dir = wp_upload_dir('wobel')['path'];
        $notice = [
            'message' => __('Error ! Please Try again', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'type' => 'danger'
        ];

        if (isset($_FILES["import_file"]) && !empty($_FILES['import_file']['type']) && in_array($_FILES['import_file']['type'], ['text/csv', 'application/vnd.ms-excel'])) {
            $target_file = $wobel_upload_dir . '/' . time() . wp_rand(100, 999) . '.csv';
            $result = move_uploaded_file($_FILES["import_file"]["tmp_name"], $target_file); // phpcs:ignore Generic.PHP.ForbiddenFunctions.Found
            if ($result) {
                $notice = [
                    'message' => __('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    'type' => 'success'
                ];
                $order_repository = Order::get_instance();
                $order_repository->import_from_csv($target_file);
            }
        }

        $this->redirect($notice);
    }

    public function activation_plugin()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wobel_post_nonce')) {
            die('403 Forbidden');
        }

        $message = "Error! Try again";

        if (isset($_POST['activation_type'])) {
            if ($_POST['activation_type'] == 'skip') {
                update_option('wobel_is_active', 'skipped');
                return $this->redirect('bulk-edit');
            } else {
                if (!empty($_POST['email']) && !empty($_POST['industry'])) {
                    $activation_service = new Activation_Service();
                    $info = $activation_service->activation([
                        'email' => sanitize_email($_POST['email']),
                        'domain' => $_SERVER['SERVER_NAME'],
                        'product_id' => 'wobel',
                        'product_name' => WOBEL_LABEL,
                        'industry' => sanitize_text_field($_POST['industry']),
                        'multi_site' => is_multisite(),
                        'core_version' => null,
                        'subsystem_version' => WOBEL_VERSION,
                    ]);

                    if (!empty($info) && is_array($info)) {
                        if (!empty($info['result']) && $info['result'] == true) {
                            update_option('wobel_is_active', 'yes');
                            $message = esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite');
                        } else {
                            update_option('wobel_is_active', 'no');
                            $message = (!empty($info['message'])) ? esc_html($info['message']) : esc_html__('System Error !', 'ithemeland-woocommerce-bulk-orders-editing-lite');
                        }
                    } else {
                        update_option('wobel_is_active', 'no');
                        $message = esc_html__('Connection Timeout! Please Try Again', 'ithemeland-woocommerce-bulk-orders-editing-lite');
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

        return wp_redirect(WOBEL_PLUGIN_MAIN_PAGE);
    }
}
