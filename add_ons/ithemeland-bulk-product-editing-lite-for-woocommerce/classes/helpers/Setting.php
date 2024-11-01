<?php

namespace wcbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Setting
{
    public static function get_arg_order_by($default_sort, $args)
    {
        switch ($default_sort) {
            case 'id':
                $args['orderby'] = 'ID';
                break;
            case 'title':
                $args['orderby'] = 'post_title';
                break;
            case 'post_date':
                $args['orderby'] = 'post_date';
                break;
            case 'regular_price':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                break;
            case 'sale_price':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                break;
            case 'sku':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_sku';
                break;
            case 'manage_stock':
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = '_manage_stock';
                break;
            case 'stock_quantity':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_stock';
                break;
            case 'stock_status':
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = '_stock_status';
                break;
            case 'width':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_width';
                break;
            case 'height':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_height';
                break;
            case 'length':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_length';
                break;
            case 'weight':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_weight';
                break;
            case 'review_count':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_wc_review_count';
                break;
            case 'average_rating':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_wc_average_rating';
                break;
            case 'date_on_sale_from':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_sale_price_dates_from';
                break;
            case 'date_on_sale_to':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_sale_price_dates_to';
                break;
        }

        return $args;
    }
}
