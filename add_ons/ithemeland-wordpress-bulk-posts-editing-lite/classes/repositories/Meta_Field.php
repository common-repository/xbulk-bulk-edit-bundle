<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Post_Helper;

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

    private $meta_fields_option_name;

    public function __construct(string $post_type = "")
    {
        $post_type = (!empty($post_type)) ? $post_type : $GLOBALS['wpbel_common']['active_post_type'];
        $this->set_option_name($post_type);
    }

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
            self::TEXTINPUT => __('TextInput', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::TEXTAREA => __('TextArea', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::CHECKBOX => __('Checkbox', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::RADIO => __('Radio', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::ARRAY_TYPE => __('Array', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::CALENDAR => __('Calendar', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::EMAIL => __('Email', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::PASSWORD => __('Password', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::URL => __('Url', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::IMAGE => __('Image', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::FILE => __('File', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::EDITOR => __('Editor', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::SELECT => __('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'),
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
            self::STRING_TYPE => __('String', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            self::NUMBER => __('Number', 'ithemeland-wordpress-bulk-posts-editing-lite'),
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

    private function set_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->meta_fields_option_name = "wpbel_{$post_type}_meta_fields";
    }
}
