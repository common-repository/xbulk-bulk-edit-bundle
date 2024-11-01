<?php

namespace wpbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Meta_Field;
use wpbel\classes\repositories\Post;

class WPBEL_Meta_Fields
{
    public function init()
    {
        add_filter('wpbel_post_column_fields', [$this, 'add_custom_fields']);
        add_filter('wpbel_page_column_fields', [$this, 'add_custom_fields']);
        add_filter('wpbel_custom_post_column_fields', [$this, 'add_custom_fields']);
        add_filter('wpbel_post_column_fields', [$this, 'add_taxonomies']);
        add_filter('wpbel_custom_post_column_fields', [$this, 'add_taxonomies']);
    }

    public function add_custom_fields($fields)
    {
        $meta_fields = (new Meta_Field())->get();
        if (!empty($meta_fields)) {
            foreach ($meta_fields as $meta_field) {
                $content_type = '';
                switch ($meta_field['main_type']) {
                    case "textinput":
                        if ($meta_field['sub_type'] == 'string') {
                            $content_type = 'text';
                        } else {
                            $content_type = 'numeric';
                        }
                        break;
                    case 'textarea':
                        $content_type = 'textarea';
                        break;
                    case 'checkbox':
                        $content_type = 'checkbox';
                        break;
                    case 'array':
                        $content_type = 'select';
                        break;
                    case 'calendar':
                        $content_type = 'date';
                        break;
                }
                $fields[$meta_field['key']] = [
                    'field_type' => 'custom_field',
                    'label' => $meta_field['title'],
                    'editable' => true,
                    'content_type' => $content_type,
                ];
            }
        }
        return $fields;
    }

    public function add_taxonomies($fields)
    {
        $taxonomies = (new Post())->get_taxonomies();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $key => $taxonomy) {
                $fields[$key] = [
                    'label' => $taxonomy['label'],
                    'editable' => true,
                    'content_type' => 'multi_select',
                ];
                $fields[$key]['field_type'] = 'taxonomy';
            }
        }
        return $fields;
    }
}
