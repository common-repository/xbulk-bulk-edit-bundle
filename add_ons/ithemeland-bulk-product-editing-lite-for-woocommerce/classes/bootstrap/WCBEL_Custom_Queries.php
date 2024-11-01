<?php

namespace wcbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\repositories\Product;

class WCBEL_Custom_Queries
{
    public function init()
    {
        add_filter('posts_where', [$this, 'general_column_filter'], 10, 2);
        add_filter('posts_where', [$this, 'meta_filter'], 10, 2);
    }

    public function general_column_filter($where, $wp_query)
    {
        global $wpdb;
        if ($search_term = $wp_query->get('wcbel_general_column_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $field = esc_sql($item['field']);
                    $value = trim(esc_sql($item['value']));
                    switch ($item['operator']) {
                        case 'like':
                            $custom_where = "(posts.{$field} LIKE '%{$value}%')";
                            break;
                        case 'exact':
                            $custom_where = "(posts.{$field} = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(posts.{$field} != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(posts.{$field} LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(posts.{$field} LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(posts.{$field} IN ({$value}))";
                            break;
                        case 'not_in':
                            $custom_where = "(posts.{$field} NOT IN ({$value}))";
                            break;
                        case 'between':
                            $value = (is_numeric($value[1])) ? "{$value[0]} AND {$value[1]}" : "'{$value[0]}' AND '{$value[1]}'";
                            $custom_where = "(posts.{$field} BETWEEN {$value})";
                            break;
                        case '>':
                            $custom_where = "(posts.{$field} > {$value})";
                            break;
                        case '<':
                            $custom_where = "(posts.{$field} < {$value})";
                            break;
                        case '>_with_quotation':
                            $custom_where = "(posts.{$field} > '{$value}')";
                            break;
                        case '<_with_quotation':
                            $custom_where = "(posts.{$field} < '{$value}')";
                            break;
                    }

                    $product_repository = Product::get_instance();
                    if (isset($item['parent_only']) && $item['parent_only'] === true) {
                        $products_ids = $product_repository->get_product_ids_by_custom_query('', $custom_where, 'product');
                    } else {
                        $products_ids = $product_repository->get_product_ids_by_custom_query('', $custom_where);
                    }

                    $ids = (!empty($products_ids)) ? $products_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }

        return $where;
    }

    public function meta_filter($where, $wp_query)
    {
        global $wpdb;
        $product_repository = Product::get_instance();
        $join = "LEFT JOIN $wpdb->postmeta AS postmeta ON (posts.ID = postmeta.post_id)";
        if ($search_term = $wp_query->get('wcbel_meta_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $key = esc_sql($item['key']);
                    $value = esc_sql($item['value']);
                    switch ($item['operator']) {
                        case 'like':
                            $before = (!empty($item['before_str'])) ? sanitize_text_field($item['before_str']) : '';
                            $after = (!empty($item['after_str'])) ? sanitize_text_field($item['after_str']) : '';

                            if (is_array($value)) {
                                $custom_where = "(";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $custom_where .= "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$before}{$value_item}{$after}%')";
                                    if (count($value) > $i) {
                                        $custom_where .= " OR ";
                                    }
                                    $i++;
                                }
                                $custom_where .= ")";
                            } else {
                                $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$before}{$value}{$after}%')";
                            }
                            break;
                        case 'like_and':
                            $before = (!empty($item['before_str'])) ? sanitize_text_field($item['before_str']) : '';
                            $after = (!empty($item['after_str'])) ? sanitize_text_field($item['after_str']) : '';

                            if (is_array($value)) {
                                $custom_where = "(";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $custom_where .= "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$before}{$value_item}{$after}%')";
                                    if (count($value) > $i) {
                                        $custom_where .= " AND ";
                                    }
                                    $i++;
                                }
                                $custom_where .= ")";
                            } else {
                                $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$before}{$value}{$after}%')";
                            }
                            break;
                        case 'not_like':
                            $before = (!empty($item['before_str'])) ? sanitize_text_field($item['before_str']) : '';
                            $after = (!empty($item['after_str'])) ? sanitize_text_field($item['after_str']) : '';

                            if (is_array($value)) {
                                $custom_where = "(";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $custom_where .= "(postmeta.meta_key = '{$key}' AND postmeta.meta_value NOT LIKE '%{$before}{$value_item}{$after}%')";
                                    if (count($value) > $i) {
                                        $custom_where .= " AND ";
                                    }
                                    $i++;
                                }
                                $custom_where .= ")";
                            } else {
                                $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$before}{$value}{$after}%')";
                            }
                            break;
                        case 'exact':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value IN ({$value}))";
                            break;
                        case 'between':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN {$value[0]} AND {$value[1]})";
                            break;
                        case 'serialized_date_between':
                            if (!empty($item['item_key'])) {
                                $custom_where = "(postmeta.meta_key = '$key' AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(postmeta.meta_value, (INSTR(postmeta.meta_value, '" . sanitize_text_field($item['item_key']) . "') + CHAR_LENGTH('" . sanitize_text_field($item['item_key']) . "') + 1 )), '\"', 2), '\"', -1) as DATE) BETWEEN '{$value[0]}' AND '{$value[1]}')";
                            }
                            break;
                        case 'json_between':
                            if (!empty($item['json_key'])) {
                                $custom_where = "(postmeta.meta_key = '$key' AND CAST(JSON_EXTRACT(postmeta.meta_value, '$.{$item['json_key']}') as UNSIGNED) BETWEEN {$value[0]} AND {$value[1]})";
                            }
                            break;
                        case 'between_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN '{$value[0]}' AND '{$value[1]}')";
                            break;
                        case '<=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= {$value})";
                            break;
                        case '>=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= {$value})";
                            break;
                        case 'json_<=':
                            if (!empty($item['json_key'])) {
                                $custom_where = "(postmeta.meta_key = '$key' AND CAST(JSON_EXTRACT(postmeta.meta_value, '$.{$item['json_key']}') as UNSIGNED) <= {$value})";
                            }
                            break;
                        case 'json_>=':
                            if (!empty($item['json_key'])) {
                                $custom_where = "(postmeta.meta_key = '$key' AND CAST(JSON_EXTRACT(postmeta.meta_value, '$.{$item['json_key']}') as UNSIGNED) >= {$value})";
                            }
                            break;
                        case 'serialized_date_<=':
                            if (!empty($item['item_key'])) {
                                $custom_where = "(postmeta.meta_key = '$key' AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(postmeta.meta_value, (INSTR(postmeta.meta_value, '" . sanitize_text_field($item['item_key']) . "') + CHAR_LENGTH('" . sanitize_text_field($item['item_key']) . "') + 1 )), '\"', 2), '\"', -1) as DATE) <= '{$value}')";
                            }
                            break;
                        case 'serialized_date_>=':
                            if (!empty($item['item_key'])) {
                                $custom_where = "(postmeta.meta_key = '$key' AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(postmeta.meta_value, (INSTR(postmeta.meta_value, '" . sanitize_text_field($item['item_key']) . "') + CHAR_LENGTH('" . sanitize_text_field($item['item_key']) . "') + 1 )), '\"', 2), '\"', -1) as DATE) >= '{$value}')";
                            }
                            break;
                        case '<=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= '{$value}')";
                            break;
                        case '>=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= '{$value}')";
                            break;
                        default:
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value = '{$value}')";
                            break;
                    }
                    $products_ids = $product_repository->get_product_ids_by_custom_query($join, $custom_where);
                    $ids = (!empty($products_ids)) ? $products_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }
        return $where;
    }
}
