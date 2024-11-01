<?php

namespace wccbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Setting
{
    private $settings_option_name;
    private $current_settings_option_name;

    public function __construct()
    {
        $this->settings_option_name = "wccbel_settings";
        $this->current_settings_option_name = "wccbel_current_settings";
    }

    public function update($data = [])
    {
        $settings = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $settings[sanitize_text_field($key)] = sanitize_text_field($value);
            }
        }

        $this->set_current_settings($settings);
        update_option($this->settings_option_name, $settings);
        return $this->get_settings();
    }

    public function get_settings()
    {
        return get_option($this->settings_option_name, []);
    }

    public function set_default_settings()
    {
        return $this->update([
            'count_per_page' => 10,
            'default_sort_by' => 'id',
            'default_sort' => "DESC",
            'show_quick_search' => 'yes',
            'close_popup_after_applying' => 'no',
            'sticky_first_columns' => 'yes',
            'display_full_columns_title' => 'yes',
            'keep_filled_data_in_bulk_edit_form' => 'no',
            'fetch_data_in_bulk' => 'no',
        ]);
    }

    public function get_current_settings()
    {
        return get_option($this->current_settings_option_name, []);
    }

    public function update_current_settings($current_settings)
    {
        $old_current_settings = $this->get_current_settings();
        if (!empty($current_settings)) {
            foreach ($current_settings as $setting_key => $setting_value) {
                $old_current_settings[$setting_key] = $setting_value;
            }
        }
        update_option($this->current_settings_option_name, $old_current_settings);
        return $this->get_current_settings();
    }

    public function delete_current_settings()
    {
        return delete_option($this->current_settings_option_name);
    }

    public function get_count_per_page_items()
    {
        return [
            '10',
            '25',
            '50',
            '75',
            '100',
            '500',
            '1000',
        ];
    }

    public function set_current_settings($settings)
    {
        $this->update_current_settings([
            'count_per_page' => isset($settings['count_per_page']) ? $settings['count_per_page'] : 10,
            'sticky_first_columns' => isset($settings['sticky_first_columns']) ? $settings['sticky_first_columns'] : 'yes',
        ]);
    }
}
