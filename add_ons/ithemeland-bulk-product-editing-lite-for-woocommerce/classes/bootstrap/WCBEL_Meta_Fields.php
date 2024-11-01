<?php

namespace wcbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\repositories\Meta_Field;
use wcbel\classes\repositories\Product;

class WCBEL_Meta_Fields
{
    public function init()
    {
        add_filter('wcbel_column_fields', [$this, 'add_meta_fields_to_column_manager']);
        add_filter('wcbel_column_fields', [$this, 'add_attributes_to_column_manager']);
    }

    public function add_meta_fields_to_column_manager($fields)
    {
        $meta_fields = (new Meta_Field())->get();
        if (!empty($meta_fields)) {
            foreach ($meta_fields as $meta_field) {
                switch ($meta_field['main_type']) {
                    case "textinput":
                        if ($meta_field['sub_type'] == 'string') {
                            $content_type = 'text';
                        } else {
                            $content_type = 'numeric';
                        }
                        break;
                    case 'textarea':
                    case 'editor':
                        $content_type = 'textarea';
                        break;
                    case 'array':
                        $content_type = 'select';
                        break;
                    case 'calendar':
                        $content_type = 'date';
                        break;
                    default:
                        $content_type = sanitize_text_field($meta_field['main_type']);
                        break;
                }
                $fields[$meta_field['key']] = [
                    'name' => $meta_field['key'],
                    'field_type' => 'custom_field',
                    'label' => $meta_field['title'],
                    'editable' => true,
                    'content_type' => $content_type,
                    'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                    'update_type' => 'meta_field'
                ];
            }
        }
        return $fields;
    }

    public function add_attributes_to_column_manager($fields)
    {
        $taxonomies = (Product::get_instance())->get_taxonomies();
        if (empty($taxonomies)) {
            return $fields;
        }

        foreach ($taxonomies as $key => $taxonomy) {
            $fields[$key] = [
                'name' => $key,
                'label' => $taxonomy['label'],
                'editable' => true,
                'content_type' => 'multi_select',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'update_type' => 'taxonomy'
            ];

            $fields[$key]['field_type'] = (strpos($key, 'pa_') !== false) ? 'attribute' : 'taxonomy';
        }

        return $fields;
    }
}
