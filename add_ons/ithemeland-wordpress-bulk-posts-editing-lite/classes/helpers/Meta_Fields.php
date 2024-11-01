<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Meta_Field;

class Meta_Fields
{
    public static function get_default_meta_key()
    {
        return [];
    }

    public static function get_except_taxonomies()
    {
        return [
            'product_visibility',
            'product_type',
            'product_tag',
            'product_cat',
            'product_shipping_class',
            'post_format'
        ];
    }

    public static function remove_default_meta_keys(array $meta_keys)
    {
        return array_diff($meta_keys, self::get_default_meta_key());
    }

    public static function get_meta_field_type($main_type, $sub_type)
    {
        $type = '';
        switch ($main_type) {
            case Meta_Field::TEXTINPUT:
                switch ($sub_type) {
                    case Meta_Field::STRING_TYPE:
                        $type = 'text';
                        break;
                    case Meta_Field::NUMBER:
                        $type = 'numeric';
                        break;
                }
                break;
            case Meta_Field::TEXTAREA:
                $type = 'textarea';
                break;
            case Meta_Field::ARRAY_TYPE:
                $type = 'text';
                break;
            case Meta_Field::CHECKBOX:
                $type = 'checkbox';
                break;
            case Meta_Field::CALENDAR:
                $type = 'date';
                break;
        }
        return $type;
    }
}
