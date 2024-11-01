<?php


namespace wcbef\classes\helpers;


class Product
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

    public static function get_tax_query($taxonomy, $terms, $operator = null, $field = null)
    {
        $field = !empty($field) ? $field : 'slug';
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
                        $args['wcbef_general_column_filter'][] = [
                            'field' => 'post_title',
                            'value' => $data['quick_search_text'],
                            'parent_only' => true,
                            'operator' => $data['quick_search_operator']
                        ];
                        break;
                    case 'id':
                        $ids = Product::products_id_parser($data['quick_search_text']);
                        $args['wcbef_general_column_filter'][] = [
                            'field' => 'ID',
                            'value' => $ids,
                            'operator' => "in"
                        ];
                        break;
                }
            }
        } else {
            if (isset($data['product_ids']) && !empty($data['product_ids']['value'])) {
                $ids = Product::products_id_parser($data['product_ids']['value']);
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'ID',
                    'value' => $ids,
                    'parent_only' => ($data['product_ids']['parent_only'] == 'yes') ? true : false,
                    'operator' => "in"
                ];
            }
            if (isset($data['product_title']) && !empty($data['product_title']['value'])) {
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'post_title',
                    'value' => $data['product_title']['value'],
                    'parent_only' => true,
                    'operator' => $data['product_title']['operator']
                ];
            }
            if (isset($data['product_content']) && !empty($data['product_content']['value'])) {
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'post_content',
                    'value' => $data['product_content']['value'],
                    'operator' => $data['product_content']['operator']
                ];
            }
            if (isset($data['product_excerpt']) && !empty($data['product_excerpt']['value'])) {
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'post_excerpt',
                    'value' => $data['product_excerpt']['value'],
                    'operator' => $data['product_excerpt']['operator']
                ];
            }
            if (isset($data['product_slug']) && !empty($data['product_slug']['value'])) {
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'post_name',
                    'value' => urlencode($data['product_slug']['value']),
                    'operator' => $data['product_slug']['operator']
                ];
            }
            if (isset($data['product_sku']) && !empty($data['product_sku']['value'])) {
                $args['wcbef_meta_filter'][] = [
                    'key' => '_sku',
                    'value' => $data['product_sku']['value'],
                    'operator' => $data['product_sku']['operator']
                ];
            }
            if (isset($data['product_url']) && !empty($data['product_url']['value'])) {
                $args['wcbef_meta_filter'][] = [
                    'key' => '_product_url',
                    'value' => $data['product_url']['value'],
                    'operator' => $data['product_url']['operator']
                ];
            }
            if (isset($data['date_created']) && (!empty($data['date_created']['from']) || !empty(!empty($data['date_created']['to'])))) {
                $from = (!empty($data['date_created']['from'])) ? date('Y-m-d H:i:s', strtotime($data['date_created']['from'])) : null;
                $to = (!empty($data['date_created']['to'])) ? date('Y-m-d H:i:s', strtotime($data['date_created']['to'])) : null;
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
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'post_date',
                    'value' => $value,
                    'operator' => $operator,
                ];
            }
            if (isset($data['sale_price_date_from']) && !empty($data['sale_price_date_from']['value'])) {
                $args['wcbef_meta_filter'][] = [
                    'key' => '_sale_price_dates_from',
                    'value' => strtotime($data['sale_price_date_from']['value']),
                    'operator' => '>=',
                ];
            }
            if (isset($data['sale_price_date_to']) && !empty($data['sale_price_date_to']['value'])) {
                $args['wcbef_meta_filter'][] = [
                    'key' => '_sale_price_dates_to',
                    'value' => strtotime($data['sale_price_date_to']['value']),
                    'operator' => '<=',
                ];
            }
            if (isset($data['product_categories']) && !empty($data['product_categories']['value'])) {
                $tax_item = Product::get_tax_query('product_cat', $data['product_categories']['value'], $data['product_categories']['operator']);
                $args['tax_query'][] = [$tax_item];
            }
            if (isset($data['product_tags']) && !empty($data['product_tags']['value'])) {
                $tax_item = Product::get_tax_query('product_tag', $data['product_tags']['value'], $data['product_tags']['operator']);
                $args['tax_query'][] = [$tax_item];
            }
            if (isset($data['product_attributes']) && !empty($data['product_attributes'])) {
                foreach ($data['product_attributes'] as $attribute_item) {
                    $tax_item = Product::get_tax_query($attribute_item['taxonomy'], $attribute_item['value'], $attribute_item['operator']);
                    $args['tax_query'][] = [$tax_item];
                }
            }
            if (isset($data['product_regular_price']) && (!empty($data['product_regular_price']['from']) || !empty($data['product_regular_price']['to']))) {
                $from = (!empty($data['product_regular_price']['from'])) ? floatval($data['product_regular_price']['from']) : null;
                $to = (!empty($data['product_regular_price']['to'])) ? floatval($data['product_regular_price']['to']) : null;
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
                $args['wcbef_meta_filter'][] = [
                    'key' => '_regular_price',
                    'value' => $value,
                    'operator' => $operator
                ];
            }
            if (isset($data['product_sale_price']) && (!empty($data['product_sale_price']['from']) || !empty($data['product_sale_price']['to']))) {
                $from = (!empty($data['product_sale_price']['from'])) ? floatval($data['product_sale_price']['from']) : null;
                $to = (!empty($data['product_sale_price']['to'])) ? floatval($data['product_sale_price']['to']) : null;
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

                $args['wcbef_meta_filter'][] = [
                    'key' => '_sale_price',
                    'value' => $value,
                    'operator' => $operator
                ];
            }
            if (isset($data['product_width']) && (!empty($data['product_width']['from']) || !empty($data['product_width']['to']))) {
                $from = (!empty($data['product_width']['from'])) ? floatval($data['product_width']['from']) : null;
                $to = (!empty($data['product_width']['to'])) ? floatval($data['product_width']['to']) : null;
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
                $args['wcbef_meta_filter'][] = [
                    'key' => '_width',
                    'value' => $value,
                    'operator' => $operator
                ];
            }
            if (isset($data['product_height']) && (!empty($data['product_height']['from']) || !empty($data['product_height']['to']))) {
                $from = (!empty($data['product_height']['from'])) ? floatval($data['product_height']['from']) : null;
                $to = (!empty($data['product_height']['to'])) ? floatval($data['product_height']['to']) : null;
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
                $args['wcbef_meta_filter'][] = [
                    'key' => '_height',
                    'value' => $value,
                    'operator' => $operator
                ];
            }
            if (isset($data['product_length']) && (!empty($data['product_length']['from']) || !empty($data['product_length']['to']))) {
                $from = (!empty($data['product_length']['from'])) ? floatval($data['product_length']['from']) : null;
                $to = (!empty($data['product_length']['to'])) ? floatval($data['product_length']['to']) : null;
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
                $args['wcbef_meta_filter'][] = [
                    'key' => '_length',
                    'value' => $value,
                    'operator' => $operator
                ];
            }
            if (isset($data['product_weight']) && (!empty($data['product_weight']['from']) || !empty($data['product_weight']['to']))) {
                $from = (!empty($data['product_weight']['from'])) ? floatval($data['product_weight']['from']) : null;
                $to = (!empty($data['product_weight']['to'])) ? floatval($data['product_weight']['to']) : null;
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
                $args['wcbef_meta_filter'][] = [
                    'key' => '_weight',
                    'value' => $value,
                    'operator' => $operator
                ];
            }
            if (isset($data['stock_quantity']) && (!empty($data['stock_quantity']['from']) || !empty($data['stock_quantity']['to']))) {
                $from = (!empty($data['stock_quantity']['from'])) ? floatval($data['stock_quantity']['from']) : null;
                $to = (!empty($data['stock_quantity']['to'])) ? floatval($data['stock_quantity']['to']) : null;
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
                $args['wcbef_meta_filter'][] = [
                    'key' => '_stock',
                    'value' => $value,
                    'operator' => $operator
                ];
            }
            if (isset($data['manage_stock']) && !empty($data['manage_stock']['value'])) {
                $args['meta_query'][] = [
                    'key' => '_manage_stock',
                    'value' => $data['manage_stock']['value'],
                    'compare' => '='
                ];
            }
            if (isset($data['product_menu_order']) && !empty($data['product_menu_order']['to'])) {
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'menu_order',
                    'value' => [floatval($data['product_menu_order']['from']), floatval($data['product_menu_order']['to'])],
                    'operator' => 'between'
                ];
            }
            if (isset($data['product_type']) && !empty($data['product_type'])) {
                $tax_item = Product::get_tax_query('product_type', $data['product_type'], 'or');
                $args['tax_query'][] = [$tax_item];
            }
            if (isset($data['product_status']) && !empty($data['product_status'])) {
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'post_status',
                    'value' => esc_sql($data['product_status']),
                    'operator' => 'exact'
                ];
            }
            if (isset($data['stock_status']) && !empty($data['stock_status'])) {
                $args['meta_query'][] = [
                    'key' => '_stock_status',
                    'value' => esc_sql($data['stock_status']),
                    'compare' => '='
                ];
            }
            if (isset($data['featured']) && !empty($data['featured'])) {
                $tax_item = Product::get_tax_query('product_visibility', 'featured', ($data['featured'] == 'yes') ? 'or' : 'not_in');
                $args['tax_query'][] = [$tax_item];
            }
            if (isset($data['downloadable']) && !empty($data['downloadable'])) {
                $args['meta_query'][] = [
                    'key' => '_downloadable',
                    'value' => esc_sql($data['downloadable']),
                    'compare' => '='
                ];
            }
            if (isset($data['backorders']) && !empty($data['backorders'])) {
                $args['meta_query'][] = [
                    'key' => '_backorders',
                    'value' => esc_sql($data['backorders']),
                    'compare' => '='
                ];
            }
            if (isset($data['sold_individually']) && !empty($data['sold_individually'])) {
                $args['meta_query'][] = [
                    'key' => '_sold_individually',
                    'value' => esc_sql($data['sold_individually']),
                    'compare' => '='
                ];
            }
            if (isset($data['author']) && !empty($data['author'])) {
                $args['wcbef_general_column_filter'][] = [
                    'field' => 'post_author',
                    'value' => esc_sql($data['author']),
                    'operator' => 'exact'
                ];
            }
            if (isset($data['catalog_visibility']) && !empty($data['catalog_visibility'])) {
                switch ($data['catalog_visibility']) {
                    case 'visible':
                        $tax_item = Product::get_tax_query('product_visibility', ['exclude-from-catalog', 'exclude-from-search'], 'not_in', 'name');
                        $args['tax_query'][] = [$tax_item];
                        break;
                    case 'catalog':
                        $tax_item = Product::get_tax_query('product_visibility', ['exclude-from-search'], 'or', 'name');
                        $args['tax_query'][] = [$tax_item];
                        $tax_item2 = Product::get_tax_query('product_visibility', ['exclude-from-catalog'], 'not_in', 'name');
                        $args['tax_query'][] = [$tax_item2];
                        break;
                    case 'search':
                        $tax_item = Product::get_tax_query('product_visibility', ['exclude-from-catalog'], 'or', 'name');
                        $args['tax_query'][] = [$tax_item];
                        $tax_item2 = Product::get_tax_query('product_visibility', ['exclude-from-search'], 'not_in', 'name');
                        $args['tax_query'][] = [$tax_item2];
                        break;
                    case 'hidden':
                        $tax_item = Product::get_tax_query('product_visibility', ['exclude-from-catalog', 'exclude-from-search'], 'and', 'name');
                        $args['tax_query'][] = [$tax_item];
                        break;
                }
            }

            if (isset($data['product_custom_fields']) && !empty($data['product_custom_fields'])) {
                foreach ($data['product_custom_fields'] as $custom_field_item) {
                    switch ($custom_field_item['type']) {
                        case 'from-to-date':
                            $from = (!empty($custom_field_item['value'][0])) ? date('Y-m-d H:i:s', strtotime($custom_field_item['value'][0])) : null;
                            $to = (!empty($custom_field_item['value'][1])) ? date('Y-m-d H:i:s', strtotime($custom_field_item['value'][1])) : null;
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
                        $args['wcbef_meta_filter'][] = [
                            'key' => $custom_field_item['taxonomy'],
                            'value' => $value,
                            'operator' => $operator,
                        ];
                    }
                }
            }
        }
        return $args;
    }
}
