<?php

namespace iwbvel\classes\repositories;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Meta_Field 
{
    const TEXTINPUT = "textinput";
    const TEXTAREA = "textarea";
    const CHECKBOX = "checkbox";
    const RADIO = "radio";
    const ARRAY_TYPE = "array";
    const CALENDAR = "calendar";
    const EMAIL = "email";
    const PASSWORD = "password";
    const URL = "url";
    const IMAGE = "image";
    const FILE = "file";
    const EDITOR = "editor";
    const SELECT = "select";
    const MULTI_SELECT = "multi_select";
    const TAXONOMY = "taxonomy";
    const COLOR = "color_picker";
    const DATE = "date_picker";
    const DATE_TIME = "date_time_picker";
    const TIME = "time_picker";

    const STRING_TYPE = "string";
    const NUMBER = "number";

    private $meta_fields_option_name = "iwbvel_meta_fields";

    public static function get_fields_name_have_operator()
    {
        return [
            self::TEXTAREA,
            self::EDITOR,
            self::EMAIL,
            self::PASSWORD,
            self::URL,
            self::ARRAY_TYPE,
        ];
    }

    public static function get_main_types()
    {
        return [
            self::TEXTINPUT => __('TextInput', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::TEXTAREA => __('TextArea', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::CHECKBOX => __('Checkbox', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::RADIO => __('Radio', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::ARRAY_TYPE => __('Array', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::CALENDAR => __('Calendar', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::EMAIL => __('Email', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::PASSWORD => __('Password', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::URL => __('Url', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::IMAGE => __('Image', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::FILE => __('File', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::EDITOR => __('Editor', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::SELECT => __('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }

    public static function get_supported_acf_field_types()
    {
        return [
            'text',
            'textarea',
            'number',
            'checkbox',
            'radio',
            'email',
            'image',
            'file',
            'select',
            'multi_select',
            'wysiwyg',
            'password',
            'url',
            'taxonomy',
            'date_picker',
            'date_time_picker',
            'time_picker',
            'color_picker',
        ];
    }

    public static function get_sub_types()
    {
        return [
            self::STRING_TYPE => __('String', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            self::NUMBER => __('Number', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }

    public function update(array $meta_fields)
    {
        return update_option($this->meta_fields_option_name, $meta_fields);
    }

    public function get()
    {
        $meta_fields = get_option($this->meta_fields_option_name);
        return !empty($meta_fields) ? $meta_fields : [];
    }
}
