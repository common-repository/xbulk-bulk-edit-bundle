<?php

namespace wcbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Operator
{
    public static function edit_text($extra = [])
    {
        $operators =  [
            'text_new' => __('New', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'text_append' => __('Append', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'text_prepend' => __('Prepend', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'text_delete' => __('Delete', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'text_replace' => __('Replace', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
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
            'taxonomy_append' => __('Append', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'taxonomy_replace' => __('Replace', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'taxonomy_delete' => __('Delete', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }

    public static function edit_number()
    {
        return [
            'number_new' => __('Set New', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'number_clear' => __('Clear Value', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'number_formula' => __('Formula', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'increase_by_value' => __('Increase by value', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'decrease_by_value' => __('Decrease by value', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'increase_by_percent' => __('Increase by %', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'decrease_by_percent' => __('Decrease by %', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }

    public static function edit_regular_price()
    {
        return [
            'increase_by_value_from_sale' => __('Increase by value (From sale)', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'increase_by_percent_from_sale' => __('Increase by % (From sale)', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }

    public static function edit_sale_price()
    {
        return [
            'decrease_by_value_from_regular' => __('Decrease by value (From regular)', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'decrease_by_percent_from_regular' => __('Decrease by % (From regular)', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }

    public static function filter_text()
    {
        return [
            'like' => __('Like', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'exact' => __('Exact', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'not' => __('Not', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'begin' => __('Begin', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'end' => __('End', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
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
