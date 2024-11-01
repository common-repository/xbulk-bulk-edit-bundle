<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Post_Helper
{
    public static function round($value, $round_item)
    {
        $division = intval('1' . str_repeat('0', wc_get_price_decimals()));
        switch ($round_item) {
            case 5:
            case 10:
                $value += floatval($round_item / $division);
                $decimals = floatval($value - floor($value));
                $value = floor($value) + ($decimals - floatval(intval(($decimals * $division) . '') % $round_item) / $division);
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
                $value = intval($value) + floatval($round_item / $division);
                break;
            default:
                break;
        }

        return $value;
    }

    public static function posts_id_parser($ids)
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

    public static function get_tax_query($taxonomy, $terms, $operator = null, $field = null)
    {
        $field = !empty($field) ? $field : 'term_id';
        $values = (is_array($terms)) ? array_map('urldecode', $terms) : $terms;
        switch ($operator) {
            case null:
                $tax_item = [
                    'taxonomy' => urldecode($taxonomy),
                    'field' => $field,
                    'terms' => $values,
                    'operator' => 'AND'
                ];
                break;
            case 'or':
                $tax_item = [
                    'taxonomy' => urldecode($taxonomy),
                    'field' => $field,
                    'terms' => $values,
                    'operator' => 'IN'
                ];
                break;
            case 'and':
                $tax_item['relation'] = 'AND';
                if (is_array($values) && !empty($values)) {
                    foreach ($values as $value) {
                        $tax_item[] = [
                            'taxonomy' => urldecode($taxonomy),
                            'field' => $field,
                            'terms' => [$value],
                        ];
                    }
                }
                break;
            case 'not_in':
                $tax_item = [
                    'taxonomy' => urldecode($taxonomy),
                    'field' => $field,
                    'terms' => $values,
                    'operator' => 'NOT IN'
                ];
                break;
        }
        return $tax_item;
    }

    public static function set_filter_data_items($data, $others = null)
    {
        $args = [];
        if (!is_null($others)) {
            $args = $others;
        }
        if (isset($data['search_type']) && $data['search_type'] == 'quick_search') {
            if (isset($data['quick_search_text']) && !empty($data['quick_search_text'])) {
                switch ($data['quick_search_field']) {
                    case 'title':
                        $args['wpbel_general_column_filter'][] = [
                            'field' => 'post_title',
                            'value' => $data['quick_search_text'],
                            'parent_only' => true,
                            'operator' => $data['quick_search_operator']
                        ];
                        break;
                    case 'id':
                        $ids = self::posts_id_parser($data['quick_search_text']);
                        $args['wpbel_general_column_filter'][] = [
                            'field' => 'ID',
                            'value' => $ids,
                            'operator' => "in"
                        ];
                        break;
                }
            }
        } else {
            if (isset($data['post_ids']) && !empty($data['post_ids']['value'])) {
                $ids = self::posts_id_parser($data['post_ids']['value']);
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'ID',
                    'value' => $ids,
                    'parent_only' => ($data['post_ids']['parent_only'] == 'yes') ? true : false,
                    'operator' => "in"
                ];
            }
            if (isset($data['post_title']) && !empty($data['post_title']['value'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_title',
                    'value' => $data['post_title']['value'],
                    'parent_only' => true,
                    'operator' => $data['post_title']['operator']
                ];
            }
            if (isset($data['post_content']) && !empty($data['post_content']['value'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_content',
                    'value' => $data['post_content']['value'],
                    'operator' => $data['post_content']['operator']
                ];
            }
            if (isset($data['post_excerpt']) && !empty($data['post_excerpt']['value'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_excerpt',
                    'value' => $data['post_excerpt']['value'],
                    'operator' => $data['post_excerpt']['operator']
                ];
            }
            if (isset($data['post_name']) && !empty($data['post_name']['value'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_name',
                    'value' => urlencode($data['post_name']['value']),
                    'operator' => $data['post_name']['operator']
                ];
            }
            if (isset($data['post_url']) && !empty($data['post_url']['value'])) {
                $args['wpbel_meta_filter'][] = [
                    'key' => '_post_url',
                    'value' => $data['post_url']['value'],
                    'operator' => $data['post_url']['operator']
                ];
            }
            if (isset($data['post_date']) && (!empty($data['post_date']['from']) || !empty(!empty($data['post_date']['to'])))) {
                $from = (!empty($data['post_date']['from'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_date']['from'])) : null;
                $to = (!empty($data['post_date']['to'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_date']['to'])) : null;
                if (!empty($from) & !empty($to)) {
                    $value = [$from, $to];
                    $operator = 'between';
                } else if (!empty($from)) {
                    $value = $from;
                    $operator = '>_with_quotation';
                } else {
                    $value = $to;
                    $operator = '<_with_quotation';
                }
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_date',
                    'value' => $value,
                    'operator' => $operator,
                ];
            }
            if (isset($data['post_date_gmt']) && (!empty($data['post_date_gmt']['from']) || !empty(!empty($data['post_date_gmt']['to'])))) {
                $from = (!empty($data['post_date_gmt']['from'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_date_gmt']['from'])) : null;
                $to = (!empty($data['post_date_gmt']['to'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_date_gmt']['to'])) : null;
                if (!empty($from) & !empty($to)) {
                    $value = [$from, $to];
                    $operator = 'between';
                } else if (!empty($from)) {
                    $value = $from;
                    $operator = '>_with_quotation';
                } else {
                    $value = $to;
                    $operator = '<_with_quotation';
                }
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_date_gmt',
                    'value' => $value,
                    'operator' => $operator,
                ];
            }
            if (isset($data['post_published']) && (!empty($data['post_published']['from']) || !empty(!empty($data['post_published']['to'])))) {
                $from = (!empty($data['post_published']['from'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_published']['from'])) : null;
                $to = (!empty($data['post_published']['to'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_published']['to'])) : null;
                if (!empty($from) & !empty($to)) {
                    $value = [$from, $to];
                    $operator = 'between';
                } else if (!empty($from)) {
                    $value = $from;
                    $operator = '>_with_quotation';
                } else {
                    $value = $to;
                    $operator = '<_with_quotation';
                }
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_published',
                    'value' => $value,
                    'operator' => $operator,
                ];
            }
            if (isset($data['post_published_gmt']) && (!empty($data['post_published_gmt']['from']) || !empty(!empty($data['post_published_gmt']['to'])))) {
                $from = (!empty($data['post_published_gmt']['from'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_published_gmt']['from'])) : null;
                $to = (!empty($data['post_published_gmt']['to'])) ? gmdate('Y-m-d H:i:s', strtotime($data['post_published_gmt']['to'])) : null;
                if (!empty($from) & !empty($to)) {
                    $value = [$from, $to];
                    $operator = 'between';
                } else if (!empty($from)) {
                    $value = $from;
                    $operator = '>_with_quotation';
                } else {
                    $value = $to;
                    $operator = '<_with_quotation';
                }
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_published_gmt',
                    'value' => $value,
                    'operator' => $operator,
                ];
            }
            if (isset($data['menu_order']) && !empty($data['menu_order']['to'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'menu_order',
                    'value' => [floatval($data['menu_order']['from']), floatval($data['menu_order']['to'])],
                    'operator' => 'between'
                ];
            }
            if (isset($data['post_status']) && !empty($data['post_status'])) {
                $args['post_status'] = esc_sql($data['post_status']);
            }
            if (isset($data['comment_status']) && !empty($data['comment_status'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'comment_status',
                    'value' => esc_sql($data['comment_status']),
                    'operator' => 'exact'
                ];
            }
            if (isset($data['ping_status']) && !empty($data['ping_status'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'ping_status',
                    'value' => esc_sql($data['ping_status']),
                    'operator' => 'exact'
                ];
            }
            if (isset($data['post_author']) && !empty($data['post_author'])) {
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'post_author',
                    'value' => esc_sql($data['post_author']),
                    'operator' => 'exact'
                ];
            }
            if (isset($data['sticky']) && !empty($data['sticky'])) {
                $stickies = get_option('sticky_posts');
                $args['wpbel_general_column_filter'][] = [
                    'field' => 'sticky',
                    'value' => $stickies,
                    'operator' => $data['sticky']
                ];
            }
            if (isset($data['custom_fields']) && !empty($data['custom_fields'])) {
                foreach ($data['custom_fields'] as $custom_field_item) {
                    switch ($custom_field_item['type']) {
                        case 'from-to-date':
                            $from = (!empty($custom_field_item['value'][0])) ? gmdate('Y-m-d H:i:s', strtotime($custom_field_item['value'][0])) : null;
                            $to = (!empty($custom_field_item['value'][1])) ? gmdate('Y-m-d H:i:s', strtotime($custom_field_item['value'][1])) : null;
                            if (empty($from) && empty($to)) {
                                $value = null;
                                $operator = null;
                                break;
                            }
                            if (!empty($from) & !empty($to)) {
                                $value = [$from, $to];
                                $operator = 'between_with_quotation';
                            } else if (!empty($from)) {
                                $value = $from;
                                $operator = '>=_with_quotation';
                            } else {
                                $value = $to;
                                $operator = '<=_with_quotation';
                            }
                            break;
                        case 'from-to-number':
                            $from = (!empty($custom_field_item['value'][0])) ? floatval($custom_field_item['value'][0]) : null;
                            $to = (!empty($custom_field_item['value'][1])) ? floatval($custom_field_item['value'][1]) : null;
                            if (empty($from) && empty($to)) {
                                $value = null;
                                $operator = null;
                                break;
                            }
                            if (!empty($from) & !empty($to)) {
                                $value = [$from, $to];
                                $operator = 'between';
                            } else if (!empty($from)) {
                                $value = $from;
                                $operator = '>=';
                            } else {
                                $value = $to;
                                $operator = '<=';
                            }
                            break;
                        case 'text':
                            $operator = $custom_field_item['operator'];
                            $value = $custom_field_item['value'];
                            break;
                        case 'select':
                            $operator = null;
                            $value = $custom_field_item['value'];
                            break;
                    }
                    if ($value) {
                        $args['wpbel_meta_filter'][] = [
                            'key' => $custom_field_item['taxonomy'],
                            'value' => $value,
                            'operator' => $operator,
                        ];
                    }
                }
            }
            if (isset($data['taxonomies']) && !empty($data['taxonomies'])) {
                foreach ($data['taxonomies'] as $tax_data) {
                    if (!empty($tax_data['value'])) {
                        $tax_item = self::get_tax_query($tax_data['taxonomy'], $tax_data['value'], $tax_data['operator']);
                        $args['tax_query'][] = $tax_item;
                    }
                }
            }
        }
        return $args;
    }

    public static function get_post_type_name(string $post_type = '')
    {
        $post_type = (!empty($post_type)) ? $post_type : $GLOBALS['wpbel_common']['active_post_type'];
        return (in_array($post_type, ['post', 'page'])) ? $post_type : 'custom_post';
    }
}
