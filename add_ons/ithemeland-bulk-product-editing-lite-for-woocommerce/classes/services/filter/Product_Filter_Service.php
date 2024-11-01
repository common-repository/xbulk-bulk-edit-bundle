<?php

namespace wcbel\classes\services\filter;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Sanitizer;
use wcbel\classes\helpers\Product_Helper;
use wcbel\classes\repositories\Product;

class Product_Filter_Service
{
    private static $instance;

    private $field_methods;
    private $query_args;
    private $filter_data;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->field_methods = $this->get_field_methods();
    }

    public function get_filtered_products($data, $args)
    {
        $this->create_query($data, $args);

        $product_repository = Product::get_instance();

        $parent_args = $this->get_query_for_parents();
        $parents = $product_repository->get_products($parent_args);
        $parent_products = $parents->posts;

        if (!empty($parent_products)) {
            $variation_args = $this->get_query_for_variations();
            $variation_args['post_parent__in'] = $parent_products;
            $variations = $product_repository->get_products($variation_args);
            $variation_products = $variations->posts;
        }

        return [
            'max_num_pages' => !empty($parents->max_num_pages) ? $parents->max_num_pages : 0,
            'found_posts' => (!empty($variations)) ? intval($parents->found_posts) + intval($variations->found_posts) : intval($parents->found_posts),
            'product_ids' => (!empty($parent_products)) ? $parent_products : [],
            'variation_ids' => (!empty($variation_products)) ? $variation_products : [],
        ];
    }

    private function create_query($data, $args)
    {
        $this->query_args = (!empty($args)) ? Sanitizer::array($args) : [];
        $this->filter_data = $data;

        if (is_array($this->filter_data) && !empty($this->filter_data)) {
            if (isset($this->filter_data['search_type']) && $this->filter_data['search_type'] == 'quick_search') {
                if (!empty($this->filter_data['quick_search_text'])) {
                    switch ($this->filter_data['quick_search_field']) {
                        case 'title':
                            $this->query_args['wcbel_general_column_filter'][] = [
                                'field' => 'post_title',
                                'value' => $this->filter_data['quick_search_text'],
                                'parent_only' => true,
                                'operator' => $this->filter_data['quick_search_operator']
                            ];
                            break;
                        case 'id':
                            $ids = Product_Helper::products_id_parser($this->filter_data['quick_search_text']);
                            $this->query_args['wcbel_general_column_filter'][] = [
                                'field' => 'ID',
                                'value' => $ids,
                                'operator' => "in"
                            ];
                            break;
                    }
                }
            } else {
                foreach ($this->field_methods as $field => $method) {
                    if (
                        !empty($this->filter_data[$field]) && (!is_array($this->filter_data[$field])
                            || (in_array($field, ['product_taxonomies', 'product_attributes', 'product_custom_fields', '_regular_price_wmcp', '_sale_price_wmcp']))
                            || (!empty($this->filter_data[$field]['value']))
                            || ((isset($this->filter_data[$field]['from']) && $this->filter_data[$field]['from'] != '') || (isset($this->filter_data[$field]['to']) && $this->filter_data[$field]['to'] != ''))
                        )
                    ) {
                        $this->{$method}();
                    }
                }
            }
        }
    }

    private function get_query_for_parents()
    {
        $query_args = $this->query_args;
        $query_args['post_type'] = ['product'];

        if (!empty($query_args['simple_product_attributes']) && is_array($query_args['simple_product_attributes'])) {
            if (!isset($query_args['tax_query']) || !is_array($query_args['tax_query'])) {
                $query_args['tax_query'] = [];
            }

            foreach ($query_args['simple_product_attributes'] as $item) {
                $query_args['tax_query'][] = $item;
            }
            unset($query_args['simple_product_attributes']);
        }

        if (!empty($query_args['variation_product_attributes'])) {
            unset($query_args['variation_product_attributes']);
        }

        return $query_args;
    }

    private function get_query_for_variations()
    {
        $query_args = $this->query_args;
        $query_args['post_type'] = ['product_variation'];

        if (!empty($query_args['variation_product_attributes']) && is_array($query_args['variation_product_attributes'])) {
            if (!isset($query_args['meta_query']) || !is_array($query_args['meta_query'])) {
                $query_args['meta_query'] = [];
            }

            foreach ($query_args['variation_product_attributes'] as $item) {
                $query_args['meta_query'][] = $item;
            }

            $query_args['posts_per_page'] = -1;
            unset($query_args['variation_product_attributes']);
        }

        if (!empty($query_args['simple_product_attributes'])) {
            unset($query_args['simple_product_attributes']);
        }

        if (!empty($query_args['tax_query'])) {
            unset($query_args['tax_query']);
        }

        return $query_args;
    }

    private function get_field_methods()
    {
        return [
            'product_ids' => 'product_ids_filter',
            'product_title' => 'product_title_filter',
            'product_content' => 'product_content_filter',
            'product_excerpt' => 'product_excerpt_filter',
            'product_slug' => 'product_slug_filter',
            'product_sku' => 'product_sku_filter',
            'product_url' => 'product_url_filter',
            'date_created' => 'date_created_filter',
            'sale_price_date_from' => 'sale_price_date_from_filter',
            'sale_price_date_to' => 'sale_price_date_to_filter',
            'product_taxonomies' => 'product_taxonomies_filter',
            'product_attributes' => 'product_attributes_filter',
            'product_regular_price' => 'product_regular_price_filter',
            'product_sale_price' => 'product_sale_price_filter',
            'product_width' => 'product_width_filter',
            'product_height' => 'product_height_filter',
            'product_length' => 'product_length_filter',
            'product_weight' => 'product_weight_filter',
            'stock_quantity' => 'stock_quantity_filter',
            'low_stock_amount' => 'low_stock_amount_filter',
            'manage_stock' => 'manage_stock_filter',
            'product_menu_order' => 'product_menu_order_filter',
            'product_type' => 'product_type_filter',
            'product_status' => 'product_status_filter',
            'stock_status' => 'stock_status_filter',
            'featured' => 'featured_filter',
            'downloadable' => 'downloadable_filter',
            'backorders' => 'backorders_filter',
            'sold_individually' => 'sold_individually_filter',
            'author' => 'author_filter',
            'catalog_visibility' => 'catalog_visibility_filter',
            'minimum_allowed_quantity' => 'minimum_allowed_quantity_filter',
            'maximum_allowed_quantity' => 'maximum_allowed_quantity_filter',
            'group_of_quantity' => 'group_of_quantity_filter',
            'minmax_do_not_count' => 'minmax_do_not_count_filter',
            'minmax_cart_exclude' => 'minmax_cart_exclude_filter',
            'minmax_category_group_of_exclude' => 'minmax_category_group_of_exclude_filter',
            '_ywmmq_product_minimum_quantity' => 'ywmmq_product_minimum_quantity_filter',
            '_ywmmq_product_maximum_quantity' => 'ywmmq_product_maximum_quantity_filter',
            '_ywmmq_product_step_quantity' => 'ywmmq_product_step_quantity_filter',
            '_ywmmq_product_exclusion' => 'ywmmq_product_exclusion_filter',
            '_ywmmq_product_quantity_limit_override' => 'ywmmq_product_quantity_limit_override_filter',
            '_ywmmq_product_quantity_limit_variations_override' => 'ywmmq_product_quantity_limit_variations_override_filter',
            '_product_commission' => 'product_commission_filter',
            'yith_shop_vendor' => 'yith_shop_vendor_filter',
            '_wcpv_product_commission' => 'wcpv_product_commission_filter',
            '_wcpv_product_taxes' => 'wcpv_product_taxes_filter',
            '_wcpv_product_pass_shipping' => 'wcpv_product_pass_shipping_filter',
            'wcpv_product_vendors' => 'wcpv_product_vendors_filter',
            'yith_cog_cost' => 'yith_cog_cost_filter',
            '_wc_cog_cost' => 'wc_cog_cost_filter',
            '_regular_price_wmcp' => 'regular_price_wmcp_filter',
            '_sale_price_wmcp' => 'sale_price_wmcp_filter',
            '_yith_wcbm_product_meta_-_id_badge' => 'yith_wcbm_product_meta_badge_filter',
            '_yith_wcbm_product_meta_-_start_date' => 'yith_wcbm_product_meta_start_date_filter',
            '_yith_wcbm_product_meta_-_end_date' => 'yith_wcbm_product_meta_end_date_filter',
            'product_custom_fields' => 'product_custom_fields_filter',
        ];
    }

    private function product_ids_filter()
    {
        $ids = Product_Helper::products_id_parser($this->filter_data['product_ids']['value']);
        $this->query_args['wcbel_general_column_filter'][] = [
            'field' => 'ID',
            'value' => $ids,
            'parent_only' => (isset($this->filter_data['product_ids']['parent_only']) && $this->filter_data['product_ids']['parent_only'] == 'yes') ? true : false,
            'operator' => "in"
        ];
    }

    private function product_title_filter()
    {
        $this->query_args['wcbel_general_column_filter'][] = [
            'field' => 'post_title',
            'value' => $this->filter_data['product_title']['value'],
            'parent_only' => false,
            'operator' => $this->filter_data['product_title']['operator']
        ];
    }

    private function product_content_filter()
    {
        $this->query_args['wcbel_general_column_filter'][] = [
            'field' => 'post_content',
            'value' => $this->filter_data['product_content']['value'],
            'operator' => $this->filter_data['product_content']['operator']
        ];
    }

    private function product_excerpt_filter()
    {
        $this->query_args['wcbel_general_column_filter'][] = [
            'field' => 'post_excerpt',
            'value' => $this->filter_data['product_excerpt']['value'],
            'operator' => $this->filter_data['product_excerpt']['operator']
        ];
    }

    private function product_slug_filter()
    {
        $this->query_args['wcbel_general_column_filter'][] = [
            'field' => 'post_name',
            'value' => urlencode($this->filter_data['product_slug']['value']),
            'operator' => $this->filter_data['product_slug']['operator']
        ];
    }

    private function product_sku_filter()
    {
        $this->query_args['wcbel_meta_filter'][] = [
            'key' => '_sku',
            'value' => $this->filter_data['product_sku']['value'],
            'operator' => $this->filter_data['product_sku']['operator']
        ];
    }

    private function product_url_filter()
    {
        $this->query_args['wcbel_meta_filter'][] = [
            'key' => '_product_url',
            'value' => $this->filter_data['product_url']['value'],
            'operator' => $this->filter_data['product_url']['operator']
        ];
    }

    private function date_created_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'date_created',
            'query_arg_name' => 'wcbel_general_column_filter',
            'query_arg_key' => 'post_date',
        ]);
    }

    private function sale_price_date_from_filter()
    {
        $this->query_args['wcbel_meta_filter'][] = [
            'key' => '_sale_price_dates_from',
            'value' => strtotime($this->filter_data['sale_price_date_from']['value']),
            'operator' => '>=',
        ];
    }

    private function sale_price_date_to_filter()
    {
        $this->query_args['wcbel_meta_filter'][] = [
            'key' => '_sale_price_dates_to',
            'value' => strtotime($this->filter_data['sale_price_date_to']['value']),
            'operator' => '<=',
        ];
    }

    private function product_taxonomies_filter()
    {
        foreach ($this->filter_data['product_taxonomies'] as $taxonomy_item) {
            if (!empty($taxonomy_item['value'])) {
                $tax_item = $this->get_tax_query($taxonomy_item['taxonomy'], $taxonomy_item['value'], $taxonomy_item['operator']);
                $this->query_args['tax_query'][] = $tax_item;
            }
        }
    }

    private function product_attributes_filter()
    {
        foreach ($this->filter_data['product_attributes'] as $attribute_item) {
            if (!empty($attribute_item['value'])) {
                // for simple products
                $tax_item = $this->get_tax_query($attribute_item['key'], $attribute_item['value'], $attribute_item['operator'], 'slug');
                $this->query_args['simple_product_attributes'][] = $tax_item;

                // for variations
                $meta_item = $this->get_meta_query('attribute_' . $attribute_item['key'], $attribute_item['value'], $attribute_item['operator']);
                $this->query_args['variation_product_attributes'][] = $meta_item;
            }
        }
    }

    private function product_regular_price_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'product_regular_price',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_regular_price',
        ]);
    }

    private function product_sale_price_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'product_sale_price',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_sale_price',
        ]);
    }

    private function product_width_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'product_width',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_width',
        ]);
    }

    private function product_height_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'product_height',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_height',
        ]);
    }

    private function product_length_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'product_length',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_length',
        ]);
    }

    private function product_weight_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'product_weight',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_weight',
        ]);
    }

    private function stock_quantity_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'stock_quantity',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_stock',
        ]);
    }

    private function low_stock_amount_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'low_stock_amount',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_low_stock_amount',
        ]);
    }

    private function manage_stock_filter()
    {
        $this->query_args['meta_query'][] = [
            'key' => '_manage_stock',
            'value' => $this->filter_data['manage_stock']['value'],
            'compare' => '='
        ];
    }

    private function product_menu_order_filter()
    {
        $this->query_args['wcbel_general_column_filter'][] = [
            'field' => 'menu_order',
            'value' => [floatval($this->filter_data['product_menu_order']['from']), floatval($this->filter_data['product_menu_order']['to'])],
            'operator' => 'between'
        ];
    }

    private function product_type_filter()
    {
        $tax_item = $this->get_tax_query('product_type', $this->filter_data['product_type'], 'or', 'slug');
        $this->query_args['tax_query'][] = [$tax_item];
    }

    private function product_status_filter()
    {
        $this->query_args['post_status'] = esc_sql($this->filter_data['product_status']);
    }

    private function stock_status_filter()
    {
        $this->query_args['meta_query'][] = [
            'key' => '_stock_status',
            'value' => esc_sql($this->filter_data['stock_status']),
            'compare' => '='
        ];
    }

    private function featured_filter()
    {
        $tax_item = $this->get_tax_query('product_visibility', 'featured', ($this->filter_data['featured'] == 'yes') ? 'or' : 'not_in');
        $this->query_args['tax_query'][] = [$tax_item];
    }

    private function downloadable_filter()
    {
        $this->query_args['meta_query'][] = [
            'key' => '_downloadable',
            'value' => esc_sql($this->filter_data['downloadable']),
            'compare' => '='
        ];
    }

    private function backorders_filter()
    {
        $this->query_args['meta_query'][] = [
            'key' => '_backorders',
            'value' => esc_sql($this->filter_data['backorders']),
            'compare' => '='
        ];
    }

    private function sold_individually_filter()
    {
        $this->query_args['meta_query'][] = [
            'key' => '_sold_individually',
            'value' => esc_sql($this->filter_data['sold_individually']),
            'compare' => '='
        ];
    }

    private function author_filter()
    {
        $this->query_args['wcbel_general_column_filter'][] = [
            'field' => 'post_author',
            'value' => esc_sql($this->filter_data['author']),
            'operator' => 'exact'
        ];
    }

    private function catalog_visibility_filter()
    {
        switch ($this->filter_data['catalog_visibility']) {
            case 'visible':
                $tax_item = $this->get_tax_query('product_visibility', ['exclude-from-catalog', 'exclude-from-search'], 'not_in', 'name');
                $this->query_args['tax_query'][] = [$tax_item];
                break;
            case 'catalog':
                $tax_item = $this->get_tax_query('product_visibility', ['exclude-from-search'], 'or', 'name');
                $this->query_args['tax_query'][] = [$tax_item];
                $tax_item2 = $this->get_tax_query('product_visibility', ['exclude-from-catalog'], 'not_in', 'name');
                $this->query_args['tax_query'][] = [$tax_item2];
                break;
            case 'search':
                $tax_item = $this->get_tax_query('product_visibility', ['exclude-from-catalog'], 'or', 'name');
                $this->query_args['tax_query'][] = [$tax_item];
                $tax_item2 = $this->get_tax_query('product_visibility', ['exclude-from-search'], 'not_in', 'name');
                $this->query_args['tax_query'][] = [$tax_item2];
                break;
            case 'hidden':
                $tax_item = $this->get_tax_query('product_visibility', ['exclude-from-catalog', 'exclude-from-search'], 'and', 'name');
                $this->query_args['tax_query'][] = [$tax_item];
                break;
        }
    }

    private function minimum_allowed_quantity_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'minimum_allowed_quantity',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => 'minimum_allowed_quantity',
        ]);
    }

    private function maximum_allowed_quantity_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'maximum_allowed_quantity',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => 'maximum_allowed_quantity',
        ]);
    }

    private function group_of_quantity_filter()
    {
        $this->set_from_to_query([
            'filter_key' => 'group_of_quantity',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => 'group_of_quantity',
        ]);
    }

    private function minmax_do_not_count_filter()
    {
        if ($this->filter_data['minmax_do_not_count']['value'] == 'yes') {
            $this->query_args['meta_query'][] = [
                'key' => 'minmax_do_not_count',
                'value' => 'yes',
                'compare' => '='
            ];
        } else {
            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => 'minmax_do_not_count',
                    'value' => 'no',
                    'compare' => '='
                ],
                [
                    'key' => 'minmax_do_not_count',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    private function minmax_cart_exclude_filter()
    {
        if ($this->filter_data['minmax_cart_exclude']['value'] == 'yes') {
            $this->query_args['meta_query'][] = [
                'key' => 'minmax_cart_exclude',
                'value' => 'yes',
                'compare' => '='
            ];
        } else {
            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => 'minmax_cart_exclude',
                    'value' => 'no',
                    'compare' => '='
                ],
                [
                    'key' => 'minmax_cart_exclude',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    private function minmax_category_group_of_exclude_filter()
    {
        if ($this->filter_data['minmax_category_group_of_exclude']['value'] == 'yes') {
            $this->query_args['meta_query'][] = [
                'key' => 'minmax_category_group_of_exclude',
                'value' => 'yes',
                'compare' => '='
            ];
        } else {
            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => 'minmax_category_group_of_exclude',
                    'value' => 'no',
                    'compare' => '='
                ],
                [
                    'key' => 'minmax_category_group_of_exclude',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    private function ywmmq_product_minimum_quantity_filter()
    {
        $this->set_from_to_query([
            'filter_key' => '_ywmmq_product_minimum_quantity',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_ywmmq_product_minimum_quantity',
        ]);
    }

    private function ywmmq_product_maximum_quantity_filter()
    {
        $this->set_from_to_query([
            'filter_key' => '_ywmmq_product_maximum_quantity',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_ywmmq_product_maximum_quantity',
        ]);
    }

    private function ywmmq_product_step_quantity_filter()
    {
        $this->set_from_to_query([
            'filter_key' => '_ywmmq_product_step_quantity',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_ywmmq_product_step_quantity',
        ]);
    }

    private function ywmmq_product_exclusion_filter()
    {
        if ($this->filter_data['_ywmmq_product_exclusion']['value'] == 'yes') {
            $this->query_args['meta_query'][] = [
                'key' => '_ywmmq_product_exclusion',
                'value' => 'yes',
                'compare' => '='
            ];
        } else {
            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => '_ywmmq_product_exclusion',
                    'value' => 'no',
                    'compare' => '='
                ],
                [
                    'key' => '_ywmmq_product_exclusion',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    private function ywmmq_product_quantity_limit_override_filter()
    {
        if ($this->filter_data['_ywmmq_product_quantity_limit_override']['value'] == 'yes') {
            $this->query_args['meta_query'][] = [
                'key' => '_ywmmq_product_quantity_limit_override',
                'value' => 'yes',
                'compare' => '='
            ];
        } else {
            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => '_ywmmq_product_quantity_limit_override',
                    'value' => 'no',
                    'compare' => '='
                ],
                [
                    'key' => '_ywmmq_product_quantity_limit_override',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    private function ywmmq_product_quantity_limit_variations_override_filter()
    {
        if ($this->filter_data['_ywmmq_product_quantity_limit_variations_override']['value'] == 'yes') {
            $this->query_args['meta_query'][] = [
                'key' => '_ywmmq_product_quantity_limit_variations_override',
                'value' => 'yes',
                'compare' => '='
            ];
        } else {
            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => '_ywmmq_product_quantity_limit_variations_override',
                    'value' => 'no',
                    'compare' => '='
                ],
                [
                    'key' => '_ywmmq_product_quantity_limit_variations_override',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    private function product_commission_filter()
    {
        $this->set_from_to_query([
            'filter_key' => '_product_commission',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_product_commission',
        ]);
    }

    private function yith_shop_vendor_filter()
    {
        $this->query_args['tax_query'][] = [
            'taxonomy' => 'yith_shop_vendor',
            'field' => 'slug',
            'terms' => $this->filter_data['yith_shop_vendor']['value'],
            'operator' => ($this->filter_data['yith_shop_vendor']['operator'] == 'or') ? 'IN' : 'NOT IN',
        ];
    }

    private function wcpv_product_commission_filter()
    {
        $this->set_from_to_query([
            'filter_key' => '_wcpv_product_commission',
            'query_arg_name' => 'wcbel_meta_filter',
            'query_arg_key' => '_wcpv_product_commission',
        ]);
    }

    private function wcpv_product_taxes_filter()
    {
        $this->query_args['meta_query'][] = [
            'key' => '_wcpv_product_taxes',
            'value' => sanitize_text_field($this->filter_data['_wcpv_product_taxes']['value']),
            'compare' => '='
        ];
    }

    private function wcpv_product_pass_shipping_filter()
    {
        if ($this->filter_data['_wcpv_product_pass_shipping']['value'] == 'yes') {
            $this->query_args['meta_query'][] = [
                'key' => '_wcpv_product_pass_shipping',
                'value' => 'yes',
                'compare' => '='
            ];
        } else {
            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => '_wcpv_product_pass_shipping',
                    'value' => 'no',
                    'compare' => '='
                ],
                [
                    'key' => '_wcpv_product_pass_shipping',
                    'compare' => 'NOT EXISTS'
                ]
            ];
        }
    }

    private function wcpv_product_vendors_filter()
    {
        $this->query_args['tax_query'][] = [
            'taxonomy' => 'wcpv_product_vendors',
            'field' => 'slug',
            'terms' => $this->filter_data['wcpv_product_vendors']['value'],
            'operator' => ($this->filter_data['wcpv_product_vendors']['operator'] == 'or') ? 'IN' : 'NOT IN',
        ];
    }

    private function yith_cog_cost_filter()
    {
        $from = (isset($this->filter_data['yith_cog_cost']['from']) && $this->filter_data['yith_cog_cost']['from'] != '') ? floatval($this->filter_data['yith_cog_cost']['from']) : null;
        $to = (isset($this->filter_data['yith_cog_cost']['to']) && $this->filter_data['yith_cog_cost']['to'] != '') ? floatval($this->filter_data['yith_cog_cost']['to']) : null;
        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'BETWEEN';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = '>=';
        } else {
            $value = $to;
            $operator = '<=';
        }

        $this->query_args['meta_query'][] = [
            'relation' => 'OR',
            [
                'key' => 'yith_cog_cost',
                'value' => $value,
                'type' => 'numeric',
                'compare' => $operator
            ],
            [
                'key' => 'yith_cog_cost_variable',
                'value' => $value,
                'type' => 'numeric',
                'compare' => $operator
            ]
        ];
    }

    private function wc_cog_cost_filter()
    {
        $from = (isset($this->filter_data['_wc_cog_cost']['from']) && $this->filter_data['_wc_cog_cost']['from'] != '') ? floatval($this->filter_data['_wc_cog_cost']['from']) : null;
        $to = (isset($this->filter_data['_wc_cog_cost']['to']) && $this->filter_data['_wc_cog_cost']['to'] != '') ? floatval($this->filter_data['_wc_cog_cost']['to']) : null;
        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'BETWEEN';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = '>=';
        } else {
            $value = $to;
            $operator = '<=';
        }

        $this->query_args['meta_query'][] = [
            'relation' => 'OR',
            [
                'key' => '_wc_cog_cost',
                'value' => $value,
                'type' => 'numeric',
                'compare' => $operator
            ],
            [
                'key' => '_wc_cog_cost_variable',
                'value' => $value,
                'type' => 'numeric',
                'compare' => $operator
            ]
        ];
    }

    private function regular_price_wmcp_filter()
    {
        foreach ($this->filter_data['_regular_price_wmcp'] as $regular_item) {
            if (!empty($regular_item['name']) && ((isset($regular_item['from']) && $regular_item['from'] != '') || (isset($regular_item['to']) && $regular_item['to'] != ''))) {
                $field_name_arr = explode('_-_', $regular_item['name']);
                if (!empty($field_name_arr[1])) {
                    $from = (!empty($regular_item['from'])) ? floatval($regular_item['from']) : null;
                    $to = (!empty($regular_item['to'])) ? floatval($regular_item['to']) : null;

                    if (!empty($from) & !empty($to)) {
                        $value = [$from, $to];
                        $operator = 'json_between';
                    } else if (!empty($from)) {
                        $value = $from;
                        $operator = 'json_>=';
                    } else {
                        $value = $to;
                        $operator = 'json_<=';
                    }

                    $this->query_args['wcbel_meta_filter'][] = [
                        'key' => '_regular_price_wmcp',
                        'json_key' => $field_name_arr[1],
                        'value' => $value,
                        'operator' => $operator
                    ];
                }
            }
        }
    }

    private function sale_price_wmcp_filter()
    {
        foreach ($this->filter_data['_sale_price_wmcp'] as $regular_item) {
            if (!empty($regular_item['name']) && ((isset($regular_item['from']) && $regular_item['from'] != '') || (isset($regular_item['to']) && $regular_item['to'] != ''))) {
                $field_name_arr = explode('_-_', $regular_item['name']);
                if (!empty($field_name_arr[1])) {
                    $from = (!empty($regular_item['from'])) ? floatval($regular_item['from']) : null;
                    $to = (!empty($regular_item['to'])) ? floatval($regular_item['to']) : null;

                    if (!empty($from) & !empty($to)) {
                        $value = [$from, $to];
                        $operator = 'json_between';
                    } else if (!empty($from)) {
                        $value = $from;
                        $operator = 'json_>=';
                    } else {
                        $value = $to;
                        $operator = 'json_<=';
                    }

                    $this->query_args['wcbel_meta_filter'][] = [
                        'key' => '_sale_price_wmcp',
                        'json_key' => $field_name_arr[1],
                        'value' => $value,
                        'operator' => $operator
                    ];
                }
            }
        }
    }

    private function yith_wcbm_product_meta_badge_filter()
    {
        switch ($this->filter_data['_yith_wcbm_product_meta_-_id_badge']['operator']) {
            case 'or':
                $operator = 'like';
                break;
            case 'and':
                $operator = 'like_and';
                break;
            case 'not_in':
                $operator = 'not_like';
                break;
            default:
                $operator = 'like';
        }
        $this->query_args['wcbel_meta_filter'][] = [
            'key' => '_yith_wcbm_product_meta',
            'item_key' => 'id_badge',
            'value' => $this->filter_data['_yith_wcbm_product_meta_-_id_badge']['value'],
            'before_str' => ':"',
            'after_str' => '";',
            'operator' => $operator,
        ];
    }

    private function yith_wcbm_product_meta_start_date_filter()
    {
        $from = (isset($this->filter_data['_yith_wcbm_product_meta_-_start_date']['from']) && $this->filter_data['_yith_wcbm_product_meta_-_start_date']['from'] != '') ? sanitize_text_field($this->filter_data['_yith_wcbm_product_meta_-_start_date']['from']) : null;
        $to = (isset($this->filter_data['_yith_wcbm_product_meta_-_start_date']['to']) && $this->filter_data['_yith_wcbm_product_meta_-_start_date']['to'] != '') ? sanitize_text_field($this->filter_data['_yith_wcbm_product_meta_-_start_date']['to']) : null;
        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'serialized_date_between';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = 'serialized_date_>=';
        } else {
            $value = $to;
            $operator = 'serialized_date_<=';
        }
        $this->query_args['wcbel_meta_filter'][] = [
            'key' => '_yith_wcbm_product_meta',
            'item_key' => 'start_date',
            'value' => $value,
            'operator' => $operator
        ];
    }

    private function yith_wcbm_product_meta_end_date_filter()
    {
        $from = (isset($this->filter_data['_yith_wcbm_product_meta_-_end_date']['from']) && $this->filter_data['_yith_wcbm_product_meta_-_end_date']['from'] != '') ? sanitize_text_field($this->filter_data['_yith_wcbm_product_meta_-_end_date']['from']) : null;
        $to = (isset($this->filter_data['_yith_wcbm_product_meta_-_end_date']['to']) && $this->filter_data['_yith_wcbm_product_meta_-_end_date']['to'] != '') ? sanitize_text_field($this->filter_data['_yith_wcbm_product_meta_-_end_date']['to']) : null;
        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'serialized_date_between';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = 'serialized_date_>=';
        } else {
            $value = $to;
            $operator = 'serialized_date_<=';
        }

        $this->query_args['wcbel_meta_filter'][] = [
            'key' => '_yith_wcbm_product_meta',
            'item_key' => 'end_date',
            'value' => $value,
            'operator' => $operator
        ];
    }

    private function product_custom_fields_filter()
    {
        foreach ($this->filter_data['product_custom_fields'] as $custom_field_item) {
            switch ($custom_field_item['type']) {
                case 'from-to-date':
                    $from = (!empty($custom_field_item['value'][0])) ? gmdate('Y/m/d', strtotime($custom_field_item['value'][0])) : null;
                    $to = (!empty($custom_field_item['value'][1])) ? gmdate('Y/m/d', strtotime($custom_field_item['value'][1])) : null;
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
                case 'from-to-time':
                    $from = (!empty($custom_field_item['value'][0])) ? gmdate('H:i', strtotime($custom_field_item['value'][0])) : null;
                    $to = (!empty($custom_field_item['value'][1])) ? gmdate('H:i', strtotime($custom_field_item['value'][1])) : null;
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
                    $value = (isset($custom_field_item['value'])) ? $custom_field_item['value'] : null;
                    break;
                case 'select':
                    $operator = "like";
                    $value = (!empty($custom_field_item['value'])) ? $custom_field_item['value'] : null;
                    break;
            }

            if (!empty($value)) {
                if (is_array($value) && $custom_field_item['type'] == 'select') {
                    $values = [];
                    foreach ($value as $value_item) {
                        if (!empty($value_item)) {
                            $values[] = $value_item;
                        }
                    }
                    if (!empty($values)) {
                        $this->query_args['wcbel_meta_filter'][] = [
                            'key' => $custom_field_item['taxonomy'],
                            'value' => $values,
                            'operator' => $operator,
                        ];
                    }
                } else {
                    $this->query_args['wcbel_meta_filter'][] = [
                        'key' => $custom_field_item['taxonomy'],
                        'value' => $value,
                        'operator' => $operator,
                    ];
                }
            }
        }
    }

    private function get_tax_query($taxonomy, $terms, $operator = null, $field = null)
    {
        $field = !empty($field) ? $field : 'term_id';
        $taxonomy = urlencode($taxonomy);

        switch ($operator) {
            case null:
                $tax_item = [
                    'taxonomy' => $taxonomy,
                    'field' => $field,
                    'terms' => $terms,
                    'operator' => 'AND'
                ];
                break;
            case 'or':
                $tax_item = [
                    'taxonomy' => $taxonomy,
                    'field' => $field,
                    'terms' => $terms,
                    'operator' => 'IN'
                ];
                break;
            case 'and':
                $tax_item['relation'] = 'AND';
                if (is_array($terms) && !empty($terms)) {
                    foreach ($terms as $value) {
                        $tax_item[] = [
                            'taxonomy' => $taxonomy,
                            'field' => $field,
                            'terms' => [$value],
                        ];
                    }
                }
                break;
            case 'not_in':
                $tax_item = [
                    'taxonomy' => $taxonomy,
                    'field' => $field,
                    'terms' => $terms,
                    'operator' => 'NOT IN'
                ];
                break;
        }
        return $tax_item;
    }

    private function get_meta_query($meta_key, $value, $operator = null)
    {
        $meta_key = urlencode($meta_key);

        switch ($operator) {
            case null:
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => 'AND'
                ];
                break;
            case 'or':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => 'IN'
                ];
                break;
            case 'and':
                $meta_query['relation'] = 'AND';
                if (is_array($value) && !empty($value)) {
                    foreach ($value as $value_item) {
                        $meta_query[] = [
                            'key' => $meta_key,
                            'value' => [$value_item],
                        ];
                    }
                }
                break;
            case 'not_in':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => 'NOT IN'
                ];
                break;
        }
        return $meta_query;
    }

    private function set_from_to_query($data)
    {
        $from = (isset($this->filter_data[$data['filter_key']]['from']) && $this->filter_data[$data['filter_key']]['from'] != '') ? floatval($this->filter_data[$data['filter_key']]['from']) : null;
        $to = (isset($this->filter_data[$data['filter_key']]['to']) && $this->filter_data[$data['filter_key']]['to'] != '') ? floatval($this->filter_data[$data['filter_key']]['to']) : null;

        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'between';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = '>=';
        } else {
            $value = $to;
            $operator = '<=';
        }

        $this->query_args[$data['query_arg_name']][] = [
            'key' => $data['query_arg_key'],
            'value' => $value,
            'operator' => $operator
        ];
    }
}
