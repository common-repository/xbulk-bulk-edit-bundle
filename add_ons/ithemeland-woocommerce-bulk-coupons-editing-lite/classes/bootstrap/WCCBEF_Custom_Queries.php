<?php

namespace wccbef\classes\bootstrap;

use wccbef\classes\helpers\Others;

class WCCBEF_Custom_Queries
{
    public function init()
    {
        add_filter('posts_where', [$this, 'general_column_filter'], 10, 2);
        add_filter('posts_where', [$this, 'meta_filter'], 10, 2);
    }

    public function general_column_filter($where, $wp_query)
    {
        global $wpdb;
        $query = "SELECT posts.ID, posts.post_parent FROM {$wpdb->posts} AS posts WHERE 1 = 1 ";
        if ($search_term = $wp_query->get('wccbef_general_column_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $column = $this->get_posts_column_name($item['field']);
                    $value = (is_array($item['value'])) ? esc_sql($item['value']) : trim(esc_sql($item['value']));
                    switch ($item['operator']) {
                        case 'like':
                            $query .= $wpdb->prepare(" AND ({$column} LIKE %s)", '%' . $value . '%');
                            break;
                        case 'exact':
                            $query .= $wpdb->prepare(" AND ({$column} = %s)", $value);
                            break;
                        case 'not':
                            $query .= $wpdb->prepare(" AND ({$column} != %s)", $value);
                            break;
                        case 'begin':
                            $query .= $wpdb->prepare(" AND ({$column} LIKE %s)", $value . '%');
                            break;
                        case 'end':
                            $query .= $wpdb->prepare(" AND ({$column} LIKE %s)", '%' . $value);
                            break;
                        case 'in':
                            if (!is_array($value)) {
                                $value = explode(',', $value);
                            }
                            if (!empty($value)) {
                                $in_operator_placeholders_arr = (is_numeric($value[0])) ? array_fill(0, count($value), '%d') : array_fill(0, count($value), '%s');
                                $in_operator_placeholders = implode(', ', $in_operator_placeholders_arr);
                                $query .= $wpdb->prepare(" AND ({$column} IN ($in_operator_placeholders))", $value);
                            }
                            break;
                        case 'or':
                            if (is_array($value)) {
                                $query .= " AND (";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $query .= $wpdb->prepare("({$column} = %s)", $value_item);
                                    if (count($value) > $i) {
                                        $query .= " OR ";
                                    }
                                    $i++;
                                }
                                $query .= ")";
                            }
                            break;
                        case 'not_in':
                            if (!is_array($value)) {
                                $value = explode(',', $value);
                            }
                            if (!empty($value)) {
                                $not_in_operator_placeholders_arr = (is_numeric($value[0])) ? array_fill(0, count($value), '%d') : array_fill(0, count($value), '%s');
                                $not_in_operator_placeholders = implode(', ', $not_in_operator_placeholders_arr);
                                $query .= $wpdb->prepare(" AND ({$column} NOT IN ($not_in_operator_placeholders))", $value);
                            }
                            break;
                        case 'between':
                            if (is_numeric($value[1])) {
                                $query .= $wpdb->prepare(" AND ({$column} BETWEEN %d AND %d)", [$value[0], $value[1]]);
                            } else {
                                $query .= $wpdb->prepare(" AND ({$column} BETWEEN %s AND %s)", [$value[0], $value[1]]);
                            }
                            break;
                        case '>':
                            $query .= $wpdb->prepare(" AND ({$column} > %d)", $value);
                            break;
                        case '<':
                            $query .= $wpdb->prepare(" AND ({$column} < %d)", $value);
                            break;
                        case '>_with_quotation':
                            $query .= $wpdb->prepare(" AND ({$column} > %s)", $value);
                            break;
                        case '<_with_quotation':
                            $query .= $wpdb->prepare(" AND ({$column} < %s)", $value);
                            break;
                    }

                    $coupons = $wpdb->get_results($query, ARRAY_N);
                    $coupons = array_unique(Others::array_flatten($coupons, 'int'));
                    if ($coupon_key = array_search(0, $coupons) !== false) {
                        unset($coupons[$coupon_key]);
                    }

                    $coupons = (!empty($coupons)) ? $coupons : [0];
                    $placeholders = array_fill(0, count($coupons), '%d');
                    $coupons_placeholders = implode(', ', $placeholders);

                    $where .= $wpdb->prepare(" AND ({$wpdb->posts}.ID IN ({$coupons_placeholders}))", $coupons);
                }
            }
        }

        return $where;
    }

    private function get_posts_column_name($field)
    {
        return "posts." . sanitize_text_field(esc_sql($field));
    }

    public function meta_filter($where, $wp_query)
    {
        global $wpdb;
        $query = "SELECT posts.ID, posts.post_parent FROM {$wpdb->posts} AS posts LEFT JOIN $wpdb->postmeta AS postmeta ON (posts.ID = postmeta.post_id) WHERE 1 = 1";

        if ($search_term = $wp_query->get('wccbef_meta_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $key = esc_sql($item['key']);
                    $value = esc_sql($item['value']);
                    switch ($item['operator']) {
                        case 'like':
                            if (is_array($value)) {
                                $query .= " AND (";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $query .= $wpdb->prepare("(postmeta.meta_key = %s AND postmeta.meta_value LIKE %s)", [$key, '%' . $value_item . '%']);
                                    if (count($value) > $i) {
                                        $query .= " OR ";
                                    }
                                    $i++;
                                }
                                $query .= ")";
                            } else {
                                $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value LIKE %s)" . [$key, '%' . $value . '%']);
                            }
                            break;
                        case 'or':
                            if (is_array($value)) {
                                $query .= " AND (";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $query .= $wpdb->prepare("(postmeta.meta_key = %s AND (postmeta.meta_value = %s OR postmeta.meta_value LIKE %s))", [$key, $value_item, '%,' . $value_item . '%']);
                                    if (count($value) > $i) {
                                        $query .= " OR ";
                                    }
                                    $i++;
                                }
                                $query .= ")";
                            } else {
                                $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value LIKE %s)", [$key, '%' . $value . '%']);
                            }
                            break;
                        case 'and':
                        case 'not_in':
                            if (is_array($value)) {
                                $query .= " AND (";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $query .= $wpdb->prepare("(postmeta.meta_key = %s AND (postmeta.meta_value = %s OR postmeta.meta_value LIKE %s))", [$key, $value_item, '%,' . $value_item . '%']);
                                    if (count($value) > $i) {
                                        $query .= " AND ";
                                    }
                                    $i++;
                                }
                                $query .= ")";
                            } else {
                                $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value LIKE %s)", [$key, '%' . $value . '%']);
                            }
                            break;
                        case 'exact':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value = %s)", [$key, $value]);
                            break;
                        case 'not':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value != %s)", [$key, $value]);
                            break;
                        case 'begin':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value LIKE %s)", [$key, $value . '%']);
                            break;
                        case 'end':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value LIKE %s)", [$key, '%' . $value]);
                            break;
                        case 'in':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value IN (%s))", [$key, $value]);
                            break;
                        case 'between':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value BETWEEN %d AND %d)", [$key, $value[0], $value[1]]);
                            break;
                        case 'between_with_quotation':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value BETWEEN %s AND %s)", [$key, $value[0], $value[1]]);
                            break;
                        case '<=':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value <= %d)", [$key, $value]);
                            break;
                        case '>=':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value >= %d)", [$key, $value]);
                            break;
                        case '<=_with_quotation':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value <= %s)", [$key, $value]);
                            break;
                        case '>=_with_quotation':
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value >= %s)", [$key, $value]);
                            break;
                        default:
                            $query .= $wpdb->prepare(" AND (postmeta.meta_key = %s AND postmeta.meta_value = %s)", [$key, $value]);
                            break;
                    }
                    $type = (isset($item['type'])) ? sanitize_text_field($item['type']) : 'shop_coupon';
                    if ($type == 'product') {
                        $query .= $wpdb->prepare(" AND posts.post_type IN (%s, %s)", ['product', 'product_variation']);
                    } else {
                        $query .= $wpdb->prepare(" AND posts.post_type = %s", sanitize_text_field($type));
                    }

                    $coupons = $wpdb->get_results($query, ARRAY_N);
                    $coupons = array_unique(Others::array_flatten($coupons, 'int'));
                    if ($coupon_key = array_search(0, $coupons) !== false) {
                        unset($coupons[$coupon_key]);
                    }

                    $coupons = (!empty($coupons)) ? $coupons : [0];
                    $placeholders = array_fill(0, count($coupons), '%d');
                    $coupons_placeholders = implode(', ', $placeholders);

                    $operator = ($item['operator'] == 'not_in') ? 'NOT IN' : 'IN';
                    $where .= $wpdb->prepare(" AND ({$wpdb->posts}.ID {$operator} ({$coupons_placeholders}))", $coupons);
                }
            }
        }
        return $where;
    }
}
