<?php

namespace wcbef\classes\controllers;

use wcbef\classes\helpers\Sanitizer;
use wcbef\classes\repositories\Flush_Message;
use wcbef\classes\repositories\Column;
use wcbef\classes\repositories\History;
use wcbef\classes\repositories\Meta_Field;
use wcbef\classes\repositories\Setting;
use wcbef\classes\services\activation\Activation_Service;
use wcbef\classes\services\export\Export_Service;

class WCBEF_Post
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
        add_action('admin_post_wcbef_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_wcbef_settings', [$this, 'settings']);
        add_action('admin_post_wcbef_export_products', [$this, 'export_products']);
    }

    public function meta_fields()
    {
        $meta_fields = [];
        if (isset($_POST['save_meta_fields']) && !empty($_POST['meta_field_key'])) {
            for ($i = 0; $i < count($_POST['meta_field_key']); $i++) {
                $main_type = sanitize_text_field($_POST['meta_field_main_type'][$i]);
                $sub_type = sanitize_text_field($_POST['meta_field_sub_type'][$i]);
                $key_value = sanitize_text_field($_POST['meta_field_key_value'][$i]);

                $meta_fields[sanitize_text_field($_POST['meta_field_key'][$i])] = [
                    "key" => sanitize_text_field($_POST['meta_field_key'][$i]),
                    "title" => (!empty($_POST['meta_field_title'][$i])) ? sanitize_text_field($_POST['meta_field_title'][$i]) : sanitize_text_field($_POST['meta_field_key'][$i]),
                    "main_type" => $main_type,
                    "sub_type" => $sub_type,
                    "key_value" => $key_value,
                ];
            }
        }

        (new Meta_Field())->update($meta_fields);
        $column_repository = new Column();
        $column_repository->update_meta_field_items();
        $preset = $column_repository->get_preset($column_repository->get_active_columns()['name']);
        $fields = $column_repository->get_fields();
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
        $this->redirect('meta-fields', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
    }

    public function column_manager_new_preset()
    {
        if (isset($_POST['save_preset']) && !empty($_POST['field_name']) && is_array($_POST['field_name'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_fields();
            if (!empty($fields)) {
                $preset['name'] = esc_sql($_POST['preset_name']);
                $preset['date_modified'] = date('Y-m-d H:i:s', time());
                $preset['key'] = 'preset-' . rand(1000000, 9999999);
                if (!empty($_POST['field_name']) && is_array($_POST['field_name'])) {
                    for ($i = 0; $i < count($_POST['field_name']); $i++) {
                        if (isset($fields[$_POST['field_name'][$i]])) {
                            $preset["fields"][esc_sql($_POST['field_name'][$i])] = [
                                'name' => esc_sql($_POST['field_name'][$i]),
                                'label' => esc_sql($_POST['field_label'][$i]),
                                'title' => (!empty($_POST['field_title'][$i])) ? esc_sql($_POST['field_title'][$i]) : esc_sql($_POST['field_label'][$i]),
                                'editable' => $fields[$_POST['field_name'][$i]]['editable'],
                                'content_type' => $fields[$_POST['field_name'][$i]]['content_type'],
                                'allowed_type' => $fields[$_POST['field_name'][$i]]['allowed_type'],
                                'update_type' => $fields[$_POST['field_name'][$i]]['update_type'],
                                'background_color' => $_POST['field_background_color'][$i],
                                'text_color' => $_POST['field_text_color'][$i],
                            ];
                            if (isset($fields[$_POST['field_name'][$i]]['field_type'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['field_type'] = $fields[$_POST['field_name'][$i]]['field_type'];
                            }
                            if (isset($fields[$_POST['field_name'][$i]]['sub_name'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['sub_name'] = $fields[$_POST['field_name'][$i]]['sub_name'];
                            }
                            if (isset($fields[$_POST['field_name'][$i]]['sortable'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['sortable'] = $fields[$_POST['field_name'][$i]]['sortable'];
                            }
                            if (isset($fields[$_POST['field_name'][$i]]['options'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['options'] = $fields[$_POST['field_name'][$i]]['options'];
                            }
                            $preset['checked'][] = esc_sql($_POST['field_name'][$i]);
                        }
                    }
                    $column_repository->update($preset);
                }
            }
        }
        $this->redirect('column-manager', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
    }

    public function column_manager_edit_preset()
    {
        if (isset($_POST['edit_preset'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_fields();
            if (!empty($fields)) {
                $preset["fields"] = [];
                $preset['name'] = esc_sql($_POST['preset_name']);
                $preset['date_modified'] = date('Y-m-d H:i:s', time());
                $preset['key'] = $_POST['preset_key'];
                if (!empty($_POST['field_name']) && is_array($_POST['field_name'])) {
                    for ($i = 0; $i < count($_POST['field_name']); $i++) {
                        if (isset($fields[$_POST['field_name'][$i]])) {
                            $preset["fields"][esc_sql($_POST['field_name'][$i])] = [
                                'name' => esc_sql($_POST['field_name'][$i]),
                                'label' => esc_sql($_POST['field_label'][$i]),
                                'title' => (!empty($_POST['field_title'][$i])) ? esc_sql($_POST['field_title'][$i]) : esc_sql($_POST['field_label'][$i]),
                                'editable' => $fields[$_POST['field_name'][$i]]['editable'],
                                'content_type' => $fields[$_POST['field_name'][$i]]['content_type'],
                                'allowed_type' => $fields[$_POST['field_name'][$i]]['allowed_type'],
                                'update_type' => $fields[$_POST['field_name'][$i]]['update_type'],
                                'background_color' => $_POST['field_background_color'][$i],
                                'text_color' => $_POST['field_text_color'][$i],
                            ];
                            if (isset($fields[$_POST['field_name'][$i]]['sortable'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['sortable'] = $fields[$_POST['field_name'][$i]]['sortable'];
                            }
                            if (isset($fields[$_POST['field_name'][$i]]['sub_name'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['sub_name'] = $fields[$_POST['field_name'][$i]]['sub_name'];
                            }
                            if (isset($fields[$_POST['field_name'][$i]]['options'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['options'] = $fields[$_POST['field_name'][$i]]['options'];
                            }
                            if (isset($fields[$_POST['field_name'][$i]]['field_type'])) {
                                $preset["fields"][esc_sql($_POST['field_name'][$i])]['field_type'] = $fields[$_POST['field_name'][$i]]['field_type'];
                            }
                            $preset['checked'][] = esc_sql($_POST['field_name'][$i]);
                        }
                    }
                    $column_repository->update($preset);
                    $column_repository->set_active_columns($preset['key'], $preset['fields']);
                }
            }
        }
        $this->redirect('column-manager', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
    }

    public function column_manager_delete_preset()
    {
        $column_repository = new Column();
        if (isset($_POST['delete_key'])) {
            if ($column_repository->get_active_columns()['name'] == $_POST['delete_key']) {
                $column_repository->delete_active_columns();
            }
            $column_repository->delete(esc_sql($_POST['delete_key']));
        }

        $this->redirect('column-manager', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
    }

    public function load_column_profile()
    {
        if (isset($_POST['preset_key'])) {
            $preset_key = esc_sql($_POST['preset_key']);
            $checked_columns = esc_sql($_POST["columns"]);
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
                                'name' => esc_sql($fields[$diff_item]['name']),
                                'label' => esc_sql($fields[$diff_item]['label']),
                                'title' => esc_sql($fields[$diff_item]['label']),
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

    public function history_action()
    {
        $history_repository = History::get_instance();
        if (isset($_POST['delete'])) {
            $history_repository->delete_history(intval(esc_sql($_POST['delete'])));
            $this->redirect('history', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
        }

        if (isset($_POST['revert'])) {
            $history_repository->revert(esc_sql($_POST['revert']));
            $this->redirect('history', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
        }

        return false;
    }

    public function clear_all_history()
    {
        if (isset($_POST)) {
            $history_repository = History::get_instance();
            $history_repository->clear_all();
            $this->redirect('history', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
        }
        return false;
    }

    public function settings()
    {
        if (isset($_POST['settings'])) {
            $setting_repository = new Setting();
            $setting_repository->update($_POST['settings']);
        }

        $this->redirect('settings', esc_html__('Success !', 'woocommerce-bulk-edit-free'));
    }

    public function export_products()
    {
        if (empty($_POST['products']) || empty($_POST['fields'])) {
            $this->redirect('import-export', esc_html__('Error ! try again', 'woocommerce-bulk-edit-free'));
        }

        $export_service = Export_Service::get_instance();
        $export_service->set_data([
            'delimiter' => sanitize_text_field($_POST['wcbef_export_delimiter']),
            'select_type' => sanitize_text_field($_POST['products']),
            'field_type' => sanitize_text_field($_POST['fields']),
            'selected_ids' => isset($_POST['item_ids']) ? Sanitizer::array($_POST['item_ids']) : [],
        ]);
        $export_service->perform();

        $this->redirect('import-export');
    }

    private function redirect($active_tab = null, $message = null)
    {
        $hash = '';
        if (!is_null($active_tab)) {
            $hash = $active_tab;
        }

        if (!is_null($message)) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set(['message' => $message, 'hash' => $hash]);
        }

        return wp_redirect(WCBEF_PLUGIN_MAIN_PAGE . '#' . $hash);
    }
}
