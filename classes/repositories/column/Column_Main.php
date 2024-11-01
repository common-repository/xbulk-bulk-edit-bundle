<?php

namespace wbebl\classes\repositories\column;

class Column_Main
{
    protected $columns_option_name;
    protected $active_columns_option_name;

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
}
