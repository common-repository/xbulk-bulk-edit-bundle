<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Meta_Fields;
use wpbel\classes\helpers\Post_Helper;

class Column
{
    const SHOW_ID_COLUMN = true;
    const DEFAULT_PROFILE_NAME = 'default';

    private $columns_option_name;
    private $active_columns_option_name;

    public function __construct(string $post_type = "")
    {
        $post_type = (!empty($post_type)) ? $post_type : $GLOBALS['wpbel_common']['active_post_type'];
        $this->set_option_name($post_type);
    }

    public function update(array $data)
    {
        if (!isset($data['key'])) {
            return false;
        }

        $presets = $this->get_presets();
        $presets[$data['key']] = $data;
        return update_option($this->columns_option_name, $presets);
    }

    public function delete($preset_key)
    {
        $presets = $this->get_presets();
        if (is_array($presets) && array_key_exists($preset_key, $presets)) {
            unset($presets[$preset_key]);
        }
        return update_option($this->columns_option_name, $presets);
    }

    public function get_preset($preset_key)
    {
        $presets = $this->get_presets();
        return (isset($presets[$preset_key])) ? $presets[$preset_key] : false;
    }

    public function get_presets()
    {
        return get_option($this->columns_option_name);
    }

    public function get_presets_fields()
    {
        $presets_fields = [];
        $presets = $this->get_presets();
        if (!empty($presets)) {
            foreach ($presets as $key => $preset) {
                $presets_fields[$key] = (!empty($preset['checked'])) ? $preset['checked'] : [];
            }
        }

        return $presets_fields;
    }

    public function set_active_columns(string $profile_name, array $columns, string $option_name = "")
    {
        $option_name = (!empty($option_name)) ? esc_sql($option_name) : $this->active_columns_option_name;
        return update_option($option_name, ['name' => $profile_name, 'fields' => $columns]);
    }

    public function get_active_columns()
    {
        return get_option($this->active_columns_option_name);
    }

    public function delete_active_columns()
    {
        return delete_option($this->active_columns_option_name);
    }

    public function has_column_fields()
    {
        $columns = get_option($this->columns_option_name);
        return !empty($columns['default']['fields']);
    }

    private function set_option_name(string $post_type)
    {
        $post_type = esc_sql(sanitize_text_field($post_type));
        $this->columns_option_name = $this->get_column_fields_option_name($post_type);
        $this->active_columns_option_name = $this->get_active_columns_option_name($post_type);
    }

    private function get_column_fields_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        return "wpbel_{$post_type}_column_fields";
    }

    private function get_active_columns_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        return "wpbel_{$post_type}_active_columns";
    }

    public static function get_static_columns()
    {
        return [
            'post_title' => [
                'field' => 'post_title',
                'title' => esc_attr__('Title', 'ithemeland-wordpress-bulk-posts-editing-lite')
            ]
        ];
    }

    public static function get_columns_title()
    {
        return [];
    }

    public function update_meta_field_items()
    {
        $presets = $this->get_presets();
        $meta_fields = (new Meta_Field())->get();
        if (!empty($presets)) {
            foreach ($presets as $preset) {
                if (!empty($preset['fields'])) {
                    foreach ($preset['fields'] as $field) {
                        if (isset($field['field_type'])) {
                            if (isset($meta_fields[$field['name']])) {
                                $preset['fields'][$field['name']]['content_type'] = Meta_Fields::get_meta_field_type($meta_fields[$field['name']]['main_type'], $meta_fields[$field['name']]['sub_type']);
                                $this->update($preset);
                            }
                        }
                    }
                }
            }
        }
    }

    public function set_default_columns()
    {
        $post_repository = new Post();
        $post_types = $post_repository->get_post_types();
        if (!empty($post_types)) {
            foreach ($post_types as $post_type => $label) {
                $post_type = Post_Helper::get_post_type_name($post_type);
                $method = "set_{$post_type}_default_columns";
                $this->{$method}();
            }
        }
    }

    private function set_post_default_columns()
    {
        $columns['default'] = [
            'name' => 'Default',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'fields' => $this->get_default_post_columns_default(),
            'checked' => array_keys($this->get_default_post_columns_default()),
        ];
        return update_option($this->get_column_fields_option_name('post'), $columns);
    }

    private function set_page_default_columns()
    {
        $columns['default'] = [
            'name' => 'Default',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'fields' => $this->get_default_page_columns_default(),
            'checked' => array_keys($this->get_default_page_columns_default()),
        ];
        return update_option($this->get_column_fields_option_name('page'), $columns);
    }

    private function set_custom_post_default_columns()
    {
        $columns['default'] = [
            'name' => 'Default',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'fields' => $this->get_default_custom_post_columns_default(),
            'checked' => array_keys($this->get_default_custom_post_columns_default()),
        ];
        return update_option($this->get_column_fields_option_name('custom_post'), $columns);
    }

    public function get_grouped_columns()
    {
        $grouped_columns = [];
        $columns = $this->get_columns();
        if (!empty($columns)) {
            foreach ($columns as $key => $column) {
                if (isset($column['field_type'])) {
                    switch ($column['field_type']) {
                        case 'taxonomy':
                            $grouped_columns['Taxonomies'][$key] = $column;
                            break;
                        case 'custom_field':
                            $grouped_columns['Custom Fields'][$key] = $column;
                            break;
                    }
                } else {
                    $grouped_columns['General Fields'][$key] = $column;
                }
            }
        }
        return $grouped_columns;
    }

    public function get_columns(string $post_type = "")
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $methods = $this->get_columns_methods();
        return (isset($methods[$post_type])) ? $this->{$methods[$post_type]}() : false;
    }

    private function get_columns_methods()
    {
        $post_repository = new Post();
        $post_types = $post_repository->get_post_types();
        $methods = [];
        if (!empty($post_types)) {
            foreach ($post_types as $post_type => $label) {
                $post_type = Post_Helper::get_post_type_name($post_type);
                $methods[$post_type] = "get_{$post_type}_columns";
            }
        }
        return $methods;
    }

    private function get_post_columns()
    {
        $post_columns = $this->get_all_columns();
        return apply_filters($this->get_column_fields_option_name('post'), $post_columns);
    }

    private function get_page_columns()
    {
        $post_columns = $this->get_columns_by_keys([
            '_thumbnail_id',
            'post_content',
            'post_excerpt',
            'post_name',
            'comment_status',
            'status',
            'ping_status',
            'post_password',
            'post_type',
            'menu_order',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_modified',
            'post_modified_gmt'
        ]);
        return apply_filters($this->get_column_fields_option_name('page'), $post_columns);
    }

    private function get_custom_post_columns()
    {
        $post_columns = $this->get_all_columns();
        return apply_filters($this->get_column_fields_option_name('custom_post'), $post_columns);
    }

    private function get_columns_by_keys(array $keys)
    {
        $output = [];
        $columns = $this->get_all_columns();
        foreach ($keys as $key) {
            if (isset($columns[$key])) {
                $output[$key] = $columns[$key];
            }
        }
        return $output;
    }

    private function get_all_columns()
    {
        $userRepository = User_Repository::get_instance();
        $users = $userRepository->get_users();

        $authors = [];
        if (!empty($users)) {
            foreach ($users as $user_item) {
                $authors[$user_item->ID] = $user_item->user_login;
            }
        }

        $post_types = (new Post())->get_post_types();

        return [
            '_thumbnail_id' => [
                'label' => esc_html__('Thumbnail', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Thumbnail', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'image',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_content' => [
                'label' => esc_html__('Description', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Description', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'textarea',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_excerpt' => [
                'label' => esc_html__('Short Description', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Short Description', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'textarea',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_name' => [
                'label' => esc_html__('Slug', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Slug', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'comment_status' => [
                'label' => esc_html__('Comment Status', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Comment Status', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => [
                    'open' => esc_html__('Open', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                    'closed' => esc_html__('Closed', 'ithemeland-wordpress-bulk-posts-editing-lite')
                ],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_status' => [
                'label' => esc_html__('Status', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Status', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => get_post_statuses(),
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'ping_status' => [
                'label' => esc_html__('Ping Status', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Ping Status', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => [
                    'open' => esc_html__('Open', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                    'closed' => esc_html__('Closed', 'ithemeland-wordpress-bulk-posts-editing-lite')
                ],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_password' => [
                'label' => esc_html__('Post Password', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Post Password', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_type' => [
                'label' => esc_html__('Post Type', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Post Type', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $post_types,
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'menu_order' => [
                'label' => esc_html__('Menu order', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Menu order', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'numeric',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_author' => [
                'label' => esc_html__('Author', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Author', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $authors,
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_date' => [
                'label' => esc_html__('Date Published', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Date Published', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_date_gmt' => [
                'label' => esc_html__('GMT Date Published', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('GMT Date Published', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_modified' => [
                'label' => esc_html__('Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_modified_gmt' => [
                'label' => esc_html__('GMT Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('GMT Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_parent' => [
                'label' => esc_html__('Parent', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Parent', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select_post',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'sticky' => [
                'label' => esc_html__('Sticky', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Sticky', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => [
                    'yes' => esc_html__('Yes', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                    'no' => esc_html__('No', 'ithemeland-wordpress-bulk-posts-editing-lite')
                ],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_url' => [
                'label' => esc_html__('Post URL', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Post URL', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'ping_status' => [
                'label' => esc_html__('Allow Pingback', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'title' => esc_html__('Allow Pingback', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => [
                    'open' => esc_html__('Yes', 'ithemeland-wordpress-bulk-posts-editing-lite'),
                    'closed' => esc_html__('No', 'ithemeland-wordpress-bulk-posts-editing-lite')
                ],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
        ];
    }

    public static function get_default_columns_name()
    {
        return [
            'default',
        ];
    }

    private function get_default_post_columns_default()
    {
        return $this->get_columns_by_keys([
            '_thumbnail_id',
            'post_content',
            'post_excerpt',
            'post_date'
        ]);
    }

    private function get_default_page_columns_default()
    {
        return $this->get_columns_by_keys([
            '_thumbnail_id',
            'post_content',
            'post_excerpt',
            'post_date'
        ]);
    }

    private function get_default_custom_post_columns_default()
    {
        return $this->get_columns_by_keys([
            '_thumbnail_id',
            'post_content',
            'post_excerpt',
            'post_date'
        ]);
    }

    public function set_default_active_columns()
    {
        $post_type = Post_Helper::get_post_type_name($GLOBALS['wpbel_common']['active_post_type']);
        $method = "get_default_{$post_type}_columns_default";
        $this->set_active_columns(self::DEFAULT_PROFILE_NAME, $this->{$method}());
        return true;
    }
}
