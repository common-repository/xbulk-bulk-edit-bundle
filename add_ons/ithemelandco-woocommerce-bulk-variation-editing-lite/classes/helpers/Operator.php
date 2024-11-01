<?php

namespace iwbvel\classes\helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Operator
{
    public static function edit_text($extra = [])
    {
        $operators =  [
            'text_new' => __('New', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'text_append' => __('Append', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'text_prepend' => __('Prepend', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'text_delete' => __('Delete', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'text_replace' => __('Replace', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
            'taxonomy_append' => __('Append', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'taxonomy_replace' => __('Replace', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'taxonomy_delete' => __('Delete', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }

    public static function edit_number()
    {
        return [
            'number_new' => __('Set New', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'number_clear' => __('Clear Value', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'number_formula' => __('Formula', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'increase_by_value' => __('Increase by value', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'decrease_by_value' => __('Decrease by value', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'increase_by_percent' => __('Increase by %', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'decrease_by_percent' => __('Decrease by %', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }

    public static function edit_regular_price()
    {
        return [
            'increase_by_value_from_sale' => __('Increase by value (From sale)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'increase_by_percent_from_sale' => __('Increase by % (From sale)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }

    public static function edit_sale_price()
    {
        return [
            'decrease_by_value_from_regular' => __('Decrease by value (From regular)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'decrease_by_percent_from_regular' => __('Decrease by % (From regular)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
        ];
    }

    public static function filter_text()
    {
        return [
            'like' => __('Like', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'exact' => __('Exact', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'not' => __('Not', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'begin' => __('Begin', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'end' => __('End', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
