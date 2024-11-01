<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Post_Helper;

class Search
{
    private $filter_profile_option_name;
    private $use_always_table;
    private $current_data_option_name;

    public function __construct($post_type = "")
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->set_option_name($post_type);
    }

    public function update(array $data)
    {
        if (!isset($data['key'])) {
            return false;
        }

        $presets = $this->get_presets();
        $presets[$data['key']] = $data;
        return update_option($this->filter_profile_option_name, $presets);
    }

    public function delete($preset_key)
    {
        $presets = $this->get_presets();
        if (is_array($presets) && array_key_exists($preset_key, $presets)) {
            unset($presets[$preset_key]);
        }
        return update_option($this->filter_profile_option_name, $presets);
    }

    public function get_preset($preset_key)
    {
        $presets = $this->get_presets();
        return (isset($presets[esc_sql($preset_key)])) ? $presets[esc_sql($preset_key)] : false;
    }

    public function get_presets()
    {
        return get_option($this->filter_profile_option_name);
    }

    public function update_use_always(string $preset_key, string $option_name = '')
    {
        $option_name = (!empty($option_name)) ? esc_sql($option_name) : $this->use_always_table;
        return update_option($option_name, esc_sql($preset_key));
    }

    public function get_use_always()
    {
        return get_option($this->use_always_table);
    }

    public function get_current_data()
    {
        return get_option($this->current_data_option_name);
    }

    public function update_current_data($current_data)
    {
        if (empty($current_data) || !is_array($current_data)) {
            return false;
        }

        $old_current_data = $this->get_current_data();
        if (empty($old_current_data) || !is_array($old_current_data)) {
            $old_current_data = [];
        }
        foreach ($current_data as $data_key => $data_value) {
            $old_current_data[esc_sql($data_key)] = esc_sql($data_value);
        }

        return update_option($this->current_data_option_name, $old_current_data);
    }

    public function delete_current_data()
    {
        return delete_option($this->current_data_option_name);
    }

    public function has_search_options()
    {
        $filters = get_option($this->filter_profile_option_name);
        $use_always = get_option($this->use_always_table);

        return (!empty($filters) && !empty($use_always));
    }

    private function set_option_name(string $post_type)
    {
        $post_type = esc_sql(sanitize_text_field($post_type));
        $this->filter_profile_option_name = $this->get_filter_profile_option_name($post_type);
        $this->use_always_table = $this->get_filter_profile_use_always_option_name($post_type);
        $this->current_data_option_name = "wpbel_{$post_type}_filter_profile_current_data";
    }

    private function get_filter_profile_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        return "wpbel_{$post_type}_filter_profile";
    }

    private function get_filter_profile_use_always_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        return "wpbel_{$post_type}_filter_profile_use_always";
    }

    public function set_default_item()
    {
        $post_repository = new Post();
        $post_types = $post_repository->get_post_types();
        if (!empty($post_types)) {
            foreach ($post_types as $post_type => $label) {
                $post_type = Post_Helper::get_post_type_name($post_type);
                $method = "set_{$post_type}_default_filter";
                $this->{$method}();
            }
        }
    }

    private function set_post_default_filter()
    {
        $default_item['default'] = [
            'name' => esc_html__('All Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'filter_data' => []
        ];
        $this->update_use_always('default', $this->get_filter_profile_use_always_option_name('post'));
        return update_option($this->get_filter_profile_option_name('post'), $default_item);
    }

    private function set_page_default_filter()
    {
        $default_item['default'] = [
            'name' => esc_html__('All Pages', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'filter_data' => []
        ];
        $this->update_use_always('default', $this->get_filter_profile_use_always_option_name('page'));
        return update_option($this->get_filter_profile_option_name('page'), $default_item);
    }

    private function set_custom_post_default_filter()
    {
        $default_item['default'] = [
            'name' => esc_html__('All Custom Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'filter_data' => []
        ];
        $this->update_use_always('default', $this->get_filter_profile_use_always_option_name('custom_post'));
        return update_option($this->get_filter_profile_option_name('custom_post'), $default_item);
    }
}
