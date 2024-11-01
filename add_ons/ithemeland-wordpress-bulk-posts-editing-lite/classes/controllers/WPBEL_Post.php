<?php

namespace wpbel\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Flush_Message;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Common;
use wpbel\classes\repositories\Meta_Field;
use wpbel\classes\repositories\Setting;
use wpbel\classes\services\activation\Activation_Service;
use wpbel\classes\services\export\Export_Service;

class WPBEL_Post
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
        add_action('admin_post_wpbel_activation_plugin', [$this, 'activation_plugin']);
        add_action('admin_post_wpbel_switcher', [$this, 'switcher']);
        add_action('admin_post_wpbel_meta_fields', [$this, 'meta_fields']);
        add_action('admin_post_wpbel_settings', [$this, 'settings']);
        add_action('admin_post_wpbel_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_wpbel_export_posts', [$this, 'export_posts']);
    }

    public function activation_plugin()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wpbel_post_nonce')) {
            die('403 Forbidden');
        }

        $message = "Error! Try again";

        if (isset($_POST['activation_type'])) {
            if ($_POST['activation_type'] == 'skip') {
                update_option('wpbel_is_active', 'skipped');
                return $this->redirect('bulk-edit');
            } else {
                if (!empty($_POST['email']) && !empty($_POST['industry'])) {
                    $activation_service = new Activation_Service();
                    $info = $activation_service->activation([
                        'email' => sanitize_email($_POST['email']),
                        'domain' => $_SERVER['SERVER_NAME'],
                        'product_id' => 'wpbel',
                        'product_name' => WPBEL_LABEL,
                        'industry' => sanitize_text_field($_POST['industry']),
                        'multi_site' => is_multisite(),
                        'core_version' => null,
                        'subsystem_version' => WPBEL_VERSION,
                    ]);

                    if (!empty($info) && is_array($info)) {
                        if (!empty($info['result']) && $info['result'] == true) {
                            update_option('wpbel_is_active', 'yes');
                            $message = esc_html__('Success !', 'ithemeland-wordpress-bulk-posts-editing-lite');
                        } else {
                            update_option('wpbel_is_active', 'no');
                            $message = (!empty($info['message'])) ? esc_html($info['message']) : esc_html__('System Error !', 'ithemeland-wordpress-bulk-posts-editing-lite');
                        }
                    } else {
                        update_option('wpbel_is_active', 'no');
                        $message = esc_html__('Connection Timeout! Please Try Again', 'ithemeland-wordpress-bulk-posts-editing-lite');
                    }
                }
            }
        }

        $this->redirect($message);
    }

    public function switcher()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wpbel_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['post_type'])) {
            $common_repository = new Common();
            $common_repository->update([
                'active_post_type' => esc_sql(sanitize_text_field($_POST['post_type'])),
            ]);
        }
        $url = (!empty($_POST['item_id'])) ? add_query_arg(['id' => intval($_POST['item_id'])], WPBEL_PLUGIN_MAIN_PAGE) : '';
        $this->redirect([], $url);
    }

    public function meta_fields()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wpbel_post_nonce')) {
            die('403 Forbidden');
        }

        $meta_fields = [];
        if (isset($_POST['save_meta_fields']) && !empty($_POST['meta_field_key'])) {
            for ($i = 0; $i < count($_POST['meta_field_key']); $i++) {
                $meta_fields[sanitize_text_field($_POST['meta_field_key'][$i])] = [
                    "key" => sanitize_text_field($_POST['meta_field_key'][$i]),
                    "title" => (!empty($_POST['meta_field_title'][$i])) ? sanitize_text_field($_POST['meta_field_title'][$i]) : sanitize_text_field($_POST['meta_field_key'][$i]),
                    "main_type" => sanitize_text_field($_POST['meta_field_main_type'][$i]),
                    "sub_type" => sanitize_text_field($_POST['meta_field_sub_type'][$i]),
                    "key_value" => sanitize_text_field($_POST['meta_field_key_value'][$i]),
                ];
            }
        }

        (new Meta_Field())->update($meta_fields);
        $column_repository = new Column();
        $column_repository->update_meta_field_items();
        $preset = $column_repository->get_preset($column_repository->get_active_columns()['name']);
        $fields = $column_repository->get_columns();
        $columns = [];
        if (!empty($preset['fields'])) {
            foreach ($preset['fields'] as $key => $column) {
                if (isset($fields[$key]) && isset($fields[$key]['options'])) {
                    $column['options'] = $fields[$key]['options'];
                }
                $columns[$key] = $column;
            }
            $column_repository->set_active_columns($column_repository->get_active_columns()['name'], $columns);
        }

        $this->redirect([
            'message' => __('Success !', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function settings()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wpbel_post_nonce')) {
            die('403 Forbidden');
        }

        $setting_repository = new Setting();
        $setting_repository->update(Sanitizer::array($_POST));

        $this->redirect([
            'message' => __('Success !', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function load_column_profile()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wpbel_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['preset_key'])) {
            $preset_key = sanitize_text_field($_POST['preset_key']);
            $checked_columns = Sanitizer::array($_POST["columns"]);
            $checked_columns = array_combine($checked_columns, $checked_columns);
            $column_repository = new Column();
            $columns = [];
            $fields = $column_repository->get_columns();
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
                    foreach ($checked_columns as $column_item) {
                        if (isset($fields[$column_item])) {
                            $checked_column = [
                                'name' => $column_item,
                                'label' => $fields[$column_item]['label'],
                                'title' => $fields[$column_item]['label'],
                                'editable' => $fields[$column_item]['editable'],
                                'content_type' => $fields[$column_item]['content_type'],
                                'background_color' => '#fff',
                                'text_color' => '#444',
                            ];
                            if (isset($fields[$column_item]['sortable'])) {
                                $checked_column['sortable'] = ($fields[$column_item]['sortable']);
                            }
                            if (isset($fields[$column_item]['options'])) {
                                $checked_column['options'] = $fields[$column_item]['options'];
                            }
                            if (isset($fields[$column_item]['field_type'])) {
                                $checked_column['field_type'] = $fields[$column_item]['field_type'];
                            }
                            $columns[$column_item] = $checked_column;
                        }
                    }
                }
            }
            $column_repository->set_active_columns($preset_key, $columns);
        }
        $this->redirect();
    }

    public function export_posts()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'wpbel_post_nonce')) {
            die('403 Forbidden');
        }

        $export_service = new Export_Service();
        return $export_service->export([
            'export_type' => (!empty($_POST['export_type'])) ? sanitize_text_field($_POST['export_type']) : 'csv',
            'posts' => sanitize_text_field($_POST['posts']),
            'item_ids' => (!empty($_POST['item_ids'])) ? array_map('intval', $_POST['item_ids']) : [],
            'fields' => (!empty($_POST['fields'])) ? sanitize_text_field($_POST['fields']) : 'all',
        ]);
    }

    private function redirect($notice = [], $url = null)
    {
        if (!empty($notice) && isset($notice['message'])) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set($notice);
        }

        $url = (!empty($url)) ? $url : WPBEL_PLUGIN_MAIN_PAGE;
        return wp_safe_redirect($url);
    }
}
