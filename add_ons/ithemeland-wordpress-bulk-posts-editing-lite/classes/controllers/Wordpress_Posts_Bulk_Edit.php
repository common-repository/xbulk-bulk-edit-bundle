<?php

namespace wpbel\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Operator;
use wpbel\classes\repositories\Flush_Message;
use wpbel\classes\bootstrap\WPBEL_Verification;
use wpbel\classes\helpers\Meta_Fields;
use wpbel\classes\providers\post\PostProvider;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\History;
use wpbel\classes\repositories\Meta_Field;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Search;
use wpbel\classes\repositories\Setting;
use wpbel\classes\repositories\User_Repository;

class Wordpress_Posts_Bulk_Edit
{
    private static $instance;

    private $flush_message_repository;
    private $column_presets_fields;
    private $default_presets;
    private $plugin_data;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        $this->flush_message_repository = new Flush_Message();
        if (!WPBEL_Verification::is_active() && !defined('WBEBL_NAME')) {
            return $this->activation_page();
        }

        $this->set_plugin_data();

        add_filter('wpbel_top_navigation_buttons', [$this, 'add_navigation_buttons']);

        $this->view();
    }

    private function activation_page()
    {
        $plugin_key = 'wpbel';
        $plugin_name = __('Ithemeland Wordpress Bulk Posts Editing Lite', 'ithemeland-wordpress-bulk-posts-editing-lite');
        $plugin_description = WPBEL_DESCRIPTION;
        $flush_message = $this->flush_message_repository->get();

        include_once WPBEL_VIEWS_DIR . "activation/main.php";
    }

    public function set_plugin_data()
    {
        $history_repository = new History();
        $search_repository = new Search();
        $column_repository = new Column();
        $setting_repository = new Setting();
        $meta_field_repository = new Meta_Field();
        $post_repository = new Post();
        $userRepository = User_Repository::get_instance();

        if (empty($setting_repository->get_settings())) {
            $setting_repository->set_default_settings();
        }

        if (!$column_repository->has_column_fields()) {
            $column_repository->set_default_columns();
        }

        if (!$search_repository->has_search_options()) {
            $search_repository->set_default_item();
        }

        $settings = $setting_repository->get_settings();

        if (!isset($settings['close_popup_after_applying'])) {
            $settings['close_popup_after_applying'] = 'no';
            $settings = $setting_repository->update($settings);
        }

        $current_settings = $setting_repository->update_current_settings([
            'sort_by' => $settings['default_sort_by'],
            'sort_type' => $settings['default_sort'],
        ]);

        if (!isset($current_settings['count_per_page'])) {
            $current_settings = $setting_repository->update_current_settings([
                'count_per_page' => isset($settings['count_per_page']) ? $settings['count_per_page'] : 10,
            ]);
        }

        if (!isset($current_settings['sticky_first_columns'])) {
            $current_settings = $setting_repository->update_current_settings([
                'sticky_first_columns' => isset($settings['sticky_first_columns']) ? $settings['sticky_first_columns'] : 'yes',
            ]);
        }

        if (!$column_repository->get_active_columns()) {
            $column_repository->set_default_active_columns();
        }

        $this->column_presets_fields = $column_repository->get_presets_fields();
        $this->default_presets = $column_repository::get_default_columns_name();

        $active_columns_array = $column_repository->get_active_columns();
        if (isset($this->column_presets_fields[$active_columns_array['name']])) {
            $this->column_presets_fields[$active_columns_array['name']] = array_keys($active_columns_array['fields']);
        }

        if ($GLOBALS['wpbel_common']['active_post_type'] == 'custom_post') {
            $custom_post_types = $post_repository->get_custom_post_types();
            $field = "<select id='wpbel-new-item-select-custom-post' class='wpbel-input-md wpbel-w500 wpbel-m0' required>";
            $field .= "<option value=''>Select</option>";
            if (!empty($custom_post_types)) {
                foreach ($custom_post_types as $post_type_key => $post_type_label) {
                    $field .= "<option value='" . sanitize_text_field($post_type_key) . "'>" . sanitize_text_field($post_type_label) . "</option>";
                }
            }
            $field .= "</select>";
            $new_item_extra_fields = [
                [
                    'label' => "<label class='wpbel-label-big' for='wpbel-new-item-select-custom-post'> Select Custom Post </label>",
                    'field' => $field,
                ],
            ];
        }

        $taxonomies = $post_repository->get_taxonomies();
        $except_taxonomies = Meta_Fields::get_except_taxonomies();
        if (!empty($except_taxonomies)) {
            foreach ($except_taxonomies as $except_taxonomy) {
                if (isset($taxonomies[$except_taxonomy])) {
                    unset($taxonomies[$except_taxonomy]);
                }
            }
        }

        $current_data = $search_repository->get_current_data();

        $this->plugin_data = [
            'plugin_key' => 'wpbel',
            'version' => WPBEL_VERSION,
            'title' => __('Ithemeland Wordpress Bulk Posts Editing Lite', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'doc_link' => 'https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/pro/wordpress-bulk-posts-editing/documentation.pdf',
            'flush_message' => $this->flush_message_repository->get(),
            'settings' => $settings,
            'current_settings' => $current_settings,
            'columns' => $active_columns_array['fields'],
            'active_columns_key' => $active_columns_array['name'],
            'last_filter_data' => (isset($current_data['last_filter_data'])) ? $current_data['last_filter_data'] : null,
            'default_columns_name' => $column_repository::get_default_columns_name(),
            'items_loading' => true,
            'count_per_page_items' => $setting_repository->get_count_per_page_items(),
            'sort_by' => $current_settings['sort_by'],
            'sort_type' => $current_settings['sort_type'],
            'sticky_first_columns' => $settings['sticky_first_columns'],
            'item_provider' => PostProvider::get_instance(),
            'show_id_column' => $column_repository::SHOW_ID_COLUMN,
            'filter_profile_use_always' => $search_repository->get_use_always(),
            'histories' => $history_repository->get_histories(),
            'history_count' => $history_repository->get_history_count(),
            'reverted' => $history_repository->get_latest_reverted(),
            'meta_fields_main_types' => Meta_Field::get_main_types(),
            'meta_fields_sub_types' => Meta_Field::get_sub_types(),
            'meta_fields' => $meta_field_repository->get(),
            'grouped_fields' => $column_repository->get_grouped_columns(),
            'column_items' => $column_repository->get_columns(),
            'new_item_extra_fields' => (!empty($new_item_extra_fields)) ? $new_item_extra_fields : '',
            'column_manager_presets' => $column_repository->get_presets(),
            'filters_preset' => $search_repository->get_presets(),
            'active_post_type' => $GLOBALS['wpbel_common']['active_post_type'],
            'post_type_name' => ucfirst(str_replace('_', ' ', $GLOBALS['wpbel_common']['active_post_type'])),
            'users' => $userRepository->get_users(),
            'edit_text_operators' => Operator::edit_text(),
            'edit_taxonomy_operators' => Operator::edit_taxonomy(),
            'edit_number_operators' => Operator::edit_number(),
            'taxonomies' => $taxonomies,
            'post_statuses' => $post_repository->get_post_statuses(),
            'post_types' => $post_repository->get_post_types(),
            'all_post_types' => $post_repository->get_post_types(),
        ];
    }

    private function view()
    {
        $this->print_script();

        extract($this->plugin_data);
        include_once WPBEL_VIEWS_DIR . "layouts/main.php";
    }

    public function add_navigation_buttons($output)
    {
        if (empty($output)) {
            $output = '';
        }

        $last_filter_data = $this->plugin_data['last_filter_data'];
        $settings = $this->plugin_data['settings'];
        $current_settings = $this->plugin_data['current_settings'];
        $post_types = $this->plugin_data['post_types'];
        $active_post_type = $this->plugin_data['active_post_type'];

        ob_start();
        include WPBEL_VIEWS_DIR . "navigation/buttons.php";
        $output .= ob_get_clean();

        return $output;
    }

    public function print_script()
    {
        $id_in_url = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
        $type_in_url = (isset($_GET['type']) && !is_null($_GET['type'])) ? sanitize_text_field($_GET['type']) : '';
        echo "
        <script>
            var itemIdInUrl = " . esc_attr($id_in_url) . ";
            var itemTypeInUrl = '" . esc_attr($type_in_url) . "';
            var defaultPresets = " . wp_json_encode($this->default_presets) . ";
            var columnPresetsFields = " . wp_json_encode($this->column_presets_fields) . ";
        </script>";
    }
}
