<?php

namespace wcbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Formula;
use wcbel\classes\repositories\Product;

class Product_Helper
{
    public static function round($value, $round)
    {
        $division = intval('1' . str_repeat('0', wc_get_price_decimals()));
        switch ($round) {
            case 5:
            case 10:
                $value += floatval($round / $division);
                $decimals = floatval($value - floor($value));
                $value = floor($value) + ($decimals - floatval(intval(($decimals * $division) . '') % $round) / $division);
                break;
            case 9:
            case 19:
            case 29:
            case 39:
            case 49:
            case 59:
            case 69:
            case 79:
            case 89:
            case 99:
                $value = intval($value) + floatval($round / $division);
                break;
            default:
                break;
        }

        return $value;
    }

    public static function products_id_parser($ids)
    {
        $output = '';
        $ids_array = explode('|', $ids);
        if (is_array($ids_array) && !empty($ids_array)) {
            foreach ($ids_array as $item) {
                $output .= self::parser($item);
            }
        } else {
            $output .= self::parser($ids_array);
        }

        return rtrim($output, ',');
    }

    private static function parser($ids_string)
    {
        $output = '';
        if (strpos($ids_string, '-') > 0) {
            $from_to = explode('-', $ids_string);
            if (isset($from_to[0]) && isset($from_to[1])) {
                for ($i = intval($from_to[0]); $i <= intval($from_to[1]); $i++) {
                    $output .= $i . ',';
                }
            }
        } else {
            $output = $ids_string . ',';
        }

        return $output;
    }

    public static function apply_operator($old_value, $data)
    {
        if (empty($data['operator'])) {
            return $data['value'];
        }

        $data['value'] = (!empty($data['operator_type'])) ? self::apply_calculator_operator($old_value, $data) : self::apply_default_operator($old_value, $data);
        $data['value'] = (isset($data['round']) && !empty($data['round'])) ? self::round($data['value'], $data['round']) : $data['value'];

        return $data['value'];
    }

    private static function apply_calculator_operator($old_value, $data)
    {
        $old_value = floatval($old_value);
        $data['value'] = floatval($data['value']);
        $data['sale_price'] = (isset($data['sale_price'])) ? floatval($data['sale_price']) : 0;
        $data['regular_price'] = (isset($data['regular_price'])) ? floatval($data['regular_price']) : 0;

        switch ($data['operator_type']) {
            case 'n':
                switch ($data['operator']) {
                    case '+':
                        $data['value'] += $old_value;
                        break;
                    case '-':
                        $data['value'] = $old_value - $data['value'];
                        break;
                    case 'sp+':
                        $data['value'] += $data['sale_price'];
                        break;
                    case 'rp-':
                        $data['value'] = $data['regular_price'] - $data['value'];
                        break;
                }
                break;
            case '%':
                switch ($data['operator']) {
                    case '+':
                        $data['value'] = $old_value + ($old_value * $data['value'] / 100);
                        break;
                    case '-':
                        $data['value'] = $old_value - ($old_value * $data['value'] / 100);
                        break;
                    case 'sp+':
                        $data['value'] = $data['sale_price'] + ($data['sale_price'] * $data['value'] / 100);
                        break;
                    case 'rp-':
                        $data['value'] = $data['regular_price'] - ($data['regular_price'] * $data['value'] / 100);
                        break;
                }
                break;
        }

        return $data['value'];
    }

    private static function apply_default_operator($old_value, $data)
    {
        switch ($data['operator']) {
            case 'text_append':
                $data['value'] = $old_value . $data['value'];
                break;
            case 'text_prepend':
                $data['value'] = $data['value'] . $old_value;
                break;
            case 'text_new':
                $data['value'] = $data['value'];
                break;
            case 'text_delete':
                $data['value'] = str_replace($data['value'], '', $old_value);
                break;
            case 'text_replace':
                if (isset($data['value'])) {
                    $data['value'] = ($data['sensitive'] == 'yes') ? str_replace($data['value'], $data['replace'], $old_value) : str_ireplace($data['value'], $data['replace'], $old_value);
                } else {
                    $data['value'] = $old_value;
                }
                break;
            case 'text_remove_duplicate':
                $data['value'] = $old_value;
                break;
            case 'taxonomy_append':
                $data['value'] = array_unique(array_merge($old_value, $data['value']));
                break;
            case 'taxonomy_replace':
                $data['value'] = $data['value'];
                break;
            case 'taxonomy_delete':
                $data['value'] = array_values(array_diff($old_value, $data['value']));
                break;
            case 'number_new':
                $data['value'] = $data['value'];
                break;
            case 'number_delete':
                $data['value'] = str_replace($data['value'], '', $old_value);
                break;
            case 'number_clear':
                $data['value'] = '';
                break;
            case 'number_formula':
                $formulaCalculator = new Formula();
                if (empty($old_value)) {
                    $old_value = 0;
                }
                $data['value'] = $formulaCalculator->calculate(strtolower($data['value']), ['x' => $old_value]);
                break;
            case 'increase_by_value':
                $data['value'] = floatval($old_value) + floatval($data['value']);
                break;
            case 'decrease_by_value':
                $data['value'] = floatval($old_value) - floatval($data['value']);
                break;
            case 'increase_by_percent':
                $data['value'] = floatval($old_value) + floatval(floatval($old_value) * floatval($data['value']) / 100);
                break;
            case 'decrease_by_percent':
                $data['value'] = floatval($old_value) - floatval(floatval($old_value) * floatval($data['value']) / 100);
                break;
            case 'increase_by_value_from_sale':
                $data['value'] = (isset($data['sale_price'])) ? floatval($data['sale_price']) + floatval($data['value']) : $data;
                break;
            case 'increase_by_percent_from_sale':
                $data['value'] = (isset($data['sale_price'])) ? floatval($data['sale_price']) + floatval(floatval($data['sale_price']) * floatval($data['value']) / 100) : $data;
                break;
            case 'decrease_by_value_from_regular':
                $data['value'] = (isset($data['regular_price'])) ? floatval($data['regular_price']) - floatval($data['value']) : $data;
                break;
            case 'decrease_by_percent_from_regular':
                $data['value'] = (isset($data['regular_price'])) ? floatval($data['regular_price']) - (floatval($data['regular_price']) * floatval($data['value']) / 100) : $data;
                break;
        }

        return $data['value'];
    }

    public static function apply_variable($product, $value)
    {
        if (!($product instanceof \WC_Product)) {
            return $value;
        }
        $product_repository = Product::get_instance();
        $parent = $product_repository->get_product(intval($product->get_parent_id()));
        $value = str_replace('{title}', $product->get_title(), $value);
        $value = str_replace('{id}', $product->get_id(), $value);
        $value = str_replace('{sku}', $product->get_sku(), $value);
        $value = str_replace('{menu_order}', $product->get_menu_order(), $value);
        $value = str_replace('{parent_id}', $product->get_parent_id(), $value);
        $value = str_replace('{regular_price}', $product->get_regular_price(), $value);
        $value = str_replace('{sale_price}', $product->get_sale_price(), $value);
        if ($parent instanceof \WC_Product) {
            $value = str_replace('{parent_title}', $parent->get_title(), $value);
            $value = str_replace('{parent_sku}', $product->get_sku(), $value);
        } else {
            $value = str_replace('{parent_title}', '', $value);
            $value = str_replace('{parent_sku}', '', $value);
        }
        return $value;
    }

    public static function get_text_variable_options()
    {
        return [
            '' => __('Variable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'title' => __('Title', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'id' => __('ID', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'sku' => __('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'menu_order' => __('Menu Order', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'parent_id' => __('Parent ID', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'parent_title' => __('Parent Title', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'parent_sku' => __('Parent SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'regular_price' => __('Regular Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            'sale_price' => __('Sale Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
        ];
    }
}
