<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Operator
{
    public static function edit_text($extra = [])
    {
        $operators =  [
            'text_new' => __('New', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'text_append' => __('Append', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'text_prepend' => __('Prepend', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'text_delete' => __('Delete', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'text_replace' => __('Replace', 'ithemeland-wordpress-bulk-posts-editing-lite'),
        ];

        if (!empty($extra) && is_array($extra)) {
            foreach ($extra as $key => $label) {
                $operators[sanitize_text_field($key)] = sanitize_text_field($label);
            }
        }

        return $operators;
    }

    public static function edit_taxonomy()
    {
        return [
            'taxonomy_append' => __('Append', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'taxonomy_replace' => __('Replace', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'taxonomy_delete' => __('Delete', 'ithemeland-wordpress-bulk-posts-editing-lite'),
        ];
    }

    public static function edit_number()
    {
        return [
            'number_new' => __('Set New', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'number_clear' => __('Clear Value', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'number_formula' => __('Formula', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'increase_by_value' => __('Increase by value', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'decrease_by_value' => __('Decrease by value', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'increase_by_percent' => __('Increase by %', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'decrease_by_percent' => __('Decrease by %', 'ithemeland-wordpress-bulk-posts-editing-lite'),
        ];
    }

    public static function edit_regular_price()
    {
        return [
            'increase_by_value_from_sale' => __('Increase by value (From sale)', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'increase_by_percent_from_sale' => __('Increase by % (From sale)', 'ithemeland-wordpress-bulk-posts-editing-lite'),
        ];
    }

    public static function edit_sale_price()
    {
        return [
            'decrease_by_value_from_regular' => __('Decrease by value (From regular)', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'decrease_by_percent_from_regular' => __('Decrease by % (From regular)', 'ithemeland-wordpress-bulk-posts-editing-lite'),
        ];
    }

    public static function filter_text()
    {
        return [
            'like' => __('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'exact' => __('Exact', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'not' => __('Not', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'begin' => __('Begin', 'ithemeland-wordpress-bulk-posts-editing-lite'),
            'end' => __('End', 'ithemeland-wordpress-bulk-posts-editing-lite'),
        ];
    }

    public static function filter_multi_select()
    {
        return [
            'or' => 'OR',
            'and' => 'And',
            'not_in' => 'Not IN',
        ];
    }

    public static function round_items()
    {
        return [
            5 => 5,
            10 => 10,
            19 => 19,
            29 => 29,
            39 => 39,
            49 => 49,
            59 => 59,
            69 => 69,
            79 => 79,
            89 => 89,
            99 => 99
        ];
    }
}
