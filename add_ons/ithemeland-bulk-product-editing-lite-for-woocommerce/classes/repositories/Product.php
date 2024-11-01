<?php

namespace wcbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Others;
use wcbel\classes\helpers\Pagination;
use wcbel\classes\helpers\Render;
use wcbel\classes\helpers\Meta_Fields;
use wcbel\classes\helpers\Product_Helper;
use wcbel\classes\providers\column\ProductColumnProvider;
use wcbel\classes\providers\product\ProductProvider;
use wcbel\classes\services\filter\Product_Filter_Service;

class Product
{
    private static $instance;

    private $wpdb;
    private $column_repository;
    private $shipping_classes;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->column_repository = new Column();
    }

    public function get_product($product_id)
    {
        return wc_get_product(intval($product_id));
    }

    public function get_product_statuses()
    {
        $statuses = get_post_statuses();
        $statuses['trash'] = esc_html__('Trash', 'ithemeland-bulk-product-editing-lite-for-woocommerce');
        return $statuses;
    }

    public function get_product_ids_by_custom_query($join, $where, $types_in = 'all')
    {
        switch ($types_in) {
            case 'all':
                $types = "'product','product_variation'";
                break;
            case 'product':
                $types = "'product'";
                break;
            case 'product_variation':
                $types = "'product_variation'";
                break;
        }

        $where = (!empty($where)) ? "AND ({$where})" : '';
        $query = "SELECT posts.ID, posts.post_parent FROM {$this->wpdb->posts} AS posts {$join} WHERE posts.post_type IN ({$types}) {$where}";
        $products = $this->wpdb->get_results($query, ARRAY_N); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $products = array_unique(Others::array_flatten($products, 'int'));
        if (($key = array_search(0, $products)) !== false) {
            unset($products[$key]);
        }
        return implode(',', $products);
    }

    public function get_products($args = [])
    {
        if (!isset($args['post_type'])) {
            $args['post_type'] = ['product'];
        }

        if (!isset($args['posts_per_page'])) {
            $args['posts_per_page'] = -1;
        }

        $posts = new \WP_Query($args);
        return $posts;
    }

    public function get_product_object_by_ids($args)
    {
        if (!isset($args['limit'])) {
            $args['limit'] = -1;
        }
        if (!isset($args['type'])) {
            $args['type'] = array_merge(array_keys(wc_get_product_types()), ['variation']);
        }

        return wc_get_products($args);
    }

    public function get_products_list($data, $active_page)
    {
        $search_repository = new Search();
        $search_repository->update_current_data([
            'last_filter_data' => $data,
        ]);

        $settings_repository = new Setting();
        $settings = $settings_repository->get_settings();
        $settings_sort_by = (isset($settings['default_sort_by'])) ? $settings['default_sort_by'] : '';
        $settings_sort_type = (isset($settings['default_sort'])) ? $settings['default_sort'] : '';
        $current_settings = $settings_repository->get_current_settings();
        $column_name = isset($current_settings['sort_by']) ? $current_settings['sort_by'] : $settings_sort_by;
        $sort_type = isset($current_settings['sort_type']) ? $current_settings['sort_type'] : $settings_sort_type;
        $sticky_first_columns = $settings['sticky_first_columns'];
        $show_only_filtered_variations = (isset($settings['show_only_filtered_variations'])) ? $settings['show_only_filtered_variations'] : 'no';

        $args = \wcbel\classes\helpers\Setting::get_arg_order_by(sanitize_text_field($column_name), [
            'order' => sanitize_text_field($sort_type),
            'posts_per_page' => $current_settings['count_per_page'],
            'paged' => $active_page,
            'paginate' => true,
            'fields' => 'ids',
        ]);

        $parent_only = (isset($data['product_ids']['parent_only']) && $data['product_ids']['parent_only'] == 'yes') ? 'yes' : 'no';
        if ($parent_only == 'yes') {
            $args['post_parent'] = 0;
        }

        $product_filter_service = Product_Filter_Service::get_instance();
        $filtered = $product_filter_service->get_filtered_products($data, $args);

        if (empty($filtered['product_ids']) && empty($filtered['variation_ids'])) {
            $items = [];
        } else {
            $items = [
                'parents' => ($show_only_filtered_variations == 'no') ? $filtered['product_ids'] : [],
                'variations' => $filtered['variation_ids'],
            ];
        }

        $max_num_pages = $filtered['max_num_pages'];

        $item_provider = ProductProvider::get_instance();
        $show_id_column = $this->column_repository::SHOW_ID_COLUMN;
        $next_static_columns = $this->column_repository::get_static_columns();
        $columns_title = $this->column_repository::get_columns_title();
        $columns = $this->get_active_columns();

        $sort_type = $current_settings['sort_type'];
        $sort_by = $current_settings['sort_by'];
        $display_full_columns_title = $settings['display_full_columns_title'];
        $products_list = Render::html(WCBEL_VIEWS_DIR . 'data_table/items.php', compact('parent_only', 'item_provider', 'display_full_columns_title', 'items', 'columns', 'sort_type', 'sort_by', 'show_id_column', 'next_static_columns', 'columns_title', 'sticky_first_columns'));
        if ((!empty($max_num_pages)) && !empty($active_page)) {
            $pagination = Pagination::init($active_page, $max_num_pages);
        }

        $result = new \stdClass();
        $result->products_list = $products_list;
        $result->pagination = $pagination;
        $result->status_filters = $this->get_status_filters();
        $result->count = $filtered['found_posts'];
        return $result;
    }

    public function get_products_rows($product_ids)
    {
        if (!is_array($product_ids)) {
            return false;
        }

        $settings_repository = new Setting();
        $settings = $settings_repository->get_settings();
        $sticky_first_columns = $settings['sticky_first_columns'];
        $column_provider = ProductColumnProvider::get_instance();
        $show_id_column = $this->column_repository::SHOW_ID_COLUMN;
        $next_static_columns = $this->column_repository::get_static_columns();
        $columns = $this->get_active_columns();

        $product_rows = [];
        $includes = [];
        $product_statuses = [];

        if (!empty($product_ids)) {
            foreach ($product_ids as $product_id) {
                $item = $this->get_product(intval($product_id));
                $item_columns = $column_provider->get_item_columns($item, $columns);
                $product_statuses[intval($product_id)] = $item->get_status();
                if (is_array($item_columns) && isset($item_columns['items'])) {
                    $product_rows[intval($product_id)] = $item_columns['items'];
                    $includes[] = $item_columns['includes'];
                } else {
                    $product_rows[intval($product_id)] = $item_columns;
                }
            }
        }

        $result = new \stdClass();
        $result->product_rows = $product_rows;
        $result->product_statuses = $product_statuses;
        $result->includes = $includes;
        $result->status_filters = $this->get_status_filters();
        return $result;
    }

    private function get_active_columns()
    {
        $columns = $this->column_repository->get_active_columns()['fields'];
        $deactivated_columns = $this->column_repository->get_deactivated_columns();
        if (!empty($columns) && is_array($columns)) {
            foreach ($columns as $column_key => $column) {
                $exploded = explode('_-_', $column_key);
                $col_key = (!empty($exploded[0])) ? $exploded[0] : $column_key;
                if (in_array($col_key, $deactivated_columns) || in_array($column_key, $deactivated_columns)) {
                    unset($columns[$column_key]);
                }
            }
        }

        return $columns;
    }

    private function get_status_filters()
    {
        $product_counts_by_status = $this->get_product_counts_group_by_status();
        $product_statuses = $this->get_product_statuses();
        return Render::html(WCBEL_VIEWS_DIR . "bulk_edit/status_filters.php", compact('product_counts_by_status', 'product_statuses'));
    }

    public function product_attribute_update($product_id, $data)
    {
        if (is_array($data) && !empty($data)) {
            $product = $this->get_product($product_id);
            if (!($product instanceof \WC_Product)) {
                return false;
            }

            $attr = new \WC_Product_Attribute();
            $attributes_result = $product->get_attributes();
            $product_attributes = (!empty($attributes_result) ? $attributes_result : []);
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            $data['value'] = (is_array($data['value'])) ? array_map('intval', $data['value']) : [];
            if (is_array($attribute_taxonomies) && !empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $attribute_taxonomy) {
                    if (!empty($product_attributes) && isset($product_attributes[strtolower(urlencode($data['field']))])) {
                        $old_attr = $product_attributes[strtolower(urlencode($data['field']))];
                        if ($old_attr->get_name() == $data['field']) {
                            $value = Product_Helper::apply_operator($old_attr->get_options(), $data);
                            $attr->set_id($old_attr->get_id());
                            $attr->set_name($old_attr->get_name());
                            $attr->set_options($value);
                            $attr->set_position($old_attr->get_position());

                            if (isset($data['attribute_is_visible']) && $data['attribute_is_visible'] != '') {
                                $attr->set_visible($data['attribute_is_visible'] == 'yes');
                            } else {
                                $attr->set_visible($old_attr->get_visible());
                            }

                            if (isset($data['used_for_variations']) && $data['used_for_variations'] != '') {
                                $attr->set_variation($data['used_for_variations'] == 'yes');
                            } else {
                                $attr->set_variation($old_attr->get_variation());
                            }

                            $product_attributes[] = $attr;
                        }
                    } else {
                        if ('pa_' . $attribute_taxonomy->attribute_name == $data['field']) {
                            $attr->set_id($attribute_taxonomy->attribute_id);
                            $attr->set_name('pa_' . $attribute_taxonomy->attribute_name);
                            $attr->set_options($data['value']);
                            $attr->set_position(count($product_attributes));
                            $attr->set_visible(1);
                            if (isset($data['used_for_variations'])) {
                                if ($data['used_for_variations'] == 'yes') {
                                    $attr->set_variation(true);
                                } else {
                                    $attr->set_variation(false);
                                }
                            }
                            $product_attributes[] = $attr;
                        }
                    }
                }
            }

            $product->set_attributes($product_attributes);
            $product->save();
            return true;
        }
        return false;
    }

    public function get_attributes()
    {
        return wc_get_attribute_taxonomies();
    }

    public function get_taxonomies()
    {
        $output = [];
        $taxonomies = get_object_taxonomies('product', 'objects');
        $default_taxonomies = Meta_Fields::get_default_taxonomies();
        foreach ($taxonomies as $taxonomy) {
            if (taxonomy_exists($taxonomy->name) && !in_array($taxonomy->name, $default_taxonomies)) {
                $output[$taxonomy->name] = [
                    'label' => $taxonomy->labels->singular_name,
                    'terms' => get_terms([
                        'taxonomy' => $taxonomy->name,
                        'hide_empty' => false,
                    ]),
                ];
            }
        }
        return $output;
    }

    public function get_grouped_taxonomies()
    {
        $output['taxonomy'] = [];
        $output['attribute'] = [];
        $taxonomies = get_object_taxonomies('product', 'objects');
        $default_taxonomies = Meta_Fields::get_default_taxonomies();
        foreach ($taxonomies as $taxonomy) {
            if (taxonomy_exists($taxonomy->name) && !in_array($taxonomy->name, $default_taxonomies)) {
                $tax_type = \wcbel\classes\helpers\Meta_Fields::get_taxonomy_type($taxonomy->name);
                $output[$tax_type][$taxonomy->name] = [
                    'label' => $taxonomy->label,
                    'terms' => get_terms([
                        'taxonomy' => $taxonomy->name,
                        'hide_empty' => false,
                    ]),
                ];
            }
        }
        return $output;
    }

    public function get_taxonomy_groups()
    {
        return [
            'taxonomy' => esc_html__('Taxonomy'),
            'attribute' => esc_html__('Attribute'),
        ];
    }

    public function get_product_taxonomies($product_id)
    {
        $output = [];
        $taxonomies = get_post_taxonomies(intval($product_id));
        if (!empty($taxonomies) && is_array($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $terms = wc_get_product_term_ids(intval($product_id), $taxonomy);
                if (!empty($terms) && is_array($terms)) {
                    $output[$taxonomy] = $terms;
                }
            }
        }

        return $output;
    }

    public function get_product_fields($product_object)
    {
        if (!($product_object instanceof \WC_Product)) {
            return [];
        }

        $post_object = get_post($product_object->get_id());
        $post_meta = get_post_meta($product_object->get_id());
        $product_taxonomy = $this->get_product_taxonomies($product_object->get_id());
        $variation_name = ($product_object->get_type() == 'variation') ? "variation_" : '';
        $cog_variable = ($product_object->get_type() == 'variable') ? "_variable" : '';

        $yith_badge = (!empty($post_meta['_yith_wcbm_product_meta'][0])) ? unserialize($post_meta['_yith_wcbm_product_meta'][0]) : [];
        return [
            'id' => $product_object->get_id(),
            'post_parent' => $product_object->get_parent_id(),
            'type' => $product_object->get_type(),
            'title' => $product_object->get_name(),
            'slug' => $product_object->get_slug(),
            'description' => wpautop($product_object->get_description()),
            'short_description' => wpautop($product_object->get_short_description()),
            'date_created' => (!empty($product_object->get_date_created()) && !empty($product_object->get_date_created()->date('Y/m/d H:i'))) ? $product_object->get_date_created()->format('Y/m/d H:i') : '',
            'status' => $product_object->get_status(),
            'regular_price' => $product_object->get_regular_price(),
            'sale_price' => $product_object->get_sale_price(),
            'image_id' => [
                'id' => $product_object->get_image_id(),
                'small' => $product_object->get_image([40, 40]),
                'medium' => $product_object->get_image([300, 300]),
                'big' => $product_object->get_image([600, 600]),
            ],
            'gallery_image_ids' => $product_object->get_gallery_image_ids(),
            'manage_stock' => $product_object->get_manage_stock(),
            'product_cat' => $product_object->get_category_ids(),
            'product_tag' => $product_object->get_tag_ids(),
            'catalog_visibility' => $product_object->get_catalog_visibility(),
            'featured' => $product_object->get_featured(),
            'date_on_sale_from' => (!empty($product_object->get_date_on_sale_from()) && !empty($product_object->get_date_on_sale_from()->date('Y/m/d'))) ? $product_object->get_date_on_sale_from()->format('Y/m/d') : '',
            'date_on_sale_to' => (!empty($product_object->get_date_on_sale_to()) && !empty($product_object->get_date_on_sale_to()->date('Y/m/d'))) ? $product_object->get_date_on_sale_to()->format('Y/m/d') : '',
            'downloadable' => $product_object->get_downloadable(),
            'sku' => $product_object->get_sku(),
            'stock_status' => $product_object->get_stock_status(),
            'sold_individually' => $product_object->get_sold_individually(),
            'shipping_class' => $product_object->get_shipping_class_id(),
            'upsell_ids' => $product_object->get_upsell_ids(),
            'cross_sell_ids' => $product_object->get_cross_sell_ids(),
            'purchase_note' => $product_object->get_purchase_note(),
            'reviews_allowed' => $product_object->get_reviews_allowed(),
            'average_rating' => $product_object->get_average_rating(),
            'virtual' => $product_object->get_virtual(),
            'download_limit' => $product_object->get_download_limit(),
            'download_expiry' => $product_object->get_download_expiry(),
            'stock_quantity' => $product_object->get_stock_quantity(),
            'low_stock_amount' => $product_object->get_low_stock_amount(),
            'tax_class' => $product_object->get_tax_class(),
            'tax_status' => $product_object->get_tax_status(),
            'width' => $product_object->get_width(),
            'height' => $product_object->get_height(),
            'length' => $product_object->get_length(),
            'weight' => $product_object->get_weight(),
            'backorders' => $product_object->get_backorders(),
            'menu_order' => $product_object->get_menu_order(),
            'total_sales' => $product_object->get_total_sales(),
            'review_count' => $product_object->get_review_count(),
            'product_type' => $product_object->get_type(),
            '_button_text' => (!empty($post_meta['_button_text'])) ? $post_meta['_button_text'] : '',
            '_product_url' => (!empty($post_meta['_product_url'])) ? $post_meta['_product_url'] : '',
            '_children' => (!empty($post_meta['_children'])) ? $post_meta['_children'] : '',
            'downloadable_files' => $product_object->get_downloads(),
            'post_author' => $post_object->post_author,
            'minimum_allowed_quantity' => (!empty($post_meta[$variation_name . 'minimum_allowed_quantity'][0])) ? $post_meta[$variation_name . 'minimum_allowed_quantity'][0] : '',
            'maximum_allowed_quantity' => (!empty($post_meta[$variation_name . 'maximum_allowed_quantity'][0])) ? $post_meta[$variation_name . 'maximum_allowed_quantity'][0] : '',
            'group_of_quantity' => (!empty($post_meta[$variation_name . 'group_of_quantity'][0])) ? $post_meta[$variation_name . 'group_of_quantity'][0] : '',
            'minmax_do_not_count' => (!empty($post_meta[$variation_name . 'minmax_do_not_count'][0])) ? $post_meta[$variation_name . 'minmax_do_not_count'][0] : '',
            'minmax_cart_exclude' => (!empty($post_meta[$variation_name . 'minmax_cart_exclude'][0])) ? $post_meta[$variation_name . 'minmax_cart_exclude'][0] : '',
            'minmax_category_group_of_exclude' => (!empty($post_meta[$variation_name . 'minmax_category_group_of_exclude'][0])) ? $post_meta[$variation_name . 'minmax_category_group_of_exclude'][0] : '',
            'min_max_rules' => (!empty($post_meta['min_max_rules'][0])) ? $post_meta['min_max_rules'][0] : '',
            'allow_combination' => (!empty($post_meta['allow_combination'][0])) ? $post_meta['allow_combination'][0] : '',
            '_ywmmq_product_minimum_quantity' => (!empty($post_meta['_ywmmq_product_minimum_quantity'][0])) ? $post_meta['_ywmmq_product_minimum_quantity'][0] : '',
            '_ywmmq_product_maximum_quantity' => (!empty($post_meta['_ywmmq_product_maximum_quantity'][0])) ? $post_meta['_ywmmq_product_maximum_quantity'][0] : '',
            '_ywmmq_product_step_quantity' => (!empty($post_meta['_ywmmq_product_step_quantity'][0])) ? $post_meta['_ywmmq_product_step_quantity'][0] : '',
            '_ywmmq_product_exclusion' => (!empty($post_meta['_ywmmq_product_exclusion'][0])) ? $post_meta['_ywmmq_product_exclusion'][0] : '',
            '_ywmmq_product_quantity_limit_override' => (!empty($post_meta['_ywmmq_product_quantity_limit_override'][0])) ? $post_meta['_ywmmq_product_quantity_limit_override'][0] : '',
            '_ywmmq_product_quantity_limit_variations_override' => (!empty($post_meta['_ywmmq_product_quantity_limit_variations_override'][0])) ? $post_meta['_ywmmq_product_quantity_limit_variations_override'][0] : '',
            '_product_commission' => (!empty($post_meta['_product_commission'][0])) ? $post_meta['_product_commission'][0] : '',
            'yith_shop_vendor' => wp_get_post_terms($product_object->get_id(), 'yith_shop_vendor', ['fields' => 'id=>slug']),
            '_wcpv_product_commission' => (!empty($post_meta['_wcpv_product_commission'][0])) ? $post_meta['_wcpv_product_commission'][0] : '',
            '_wcpv_product_taxes' => (!empty($post_meta['_wcpv_product_taxes'][0])) ? $post_meta['_wcpv_product_taxes'][0] : '',
            '_wcpv_product_pass_shipping' => (!empty($post_meta['_wcpv_product_pass_shipping'][0])) ? $post_meta['_wcpv_product_pass_shipping'][0] : '',
            'wcpv_product_vendors' => wp_get_post_terms($product_object->get_id(), 'wcpv_product_vendors', ['fields' => 'id=>slug']),
            'yith_cog_cost' => (!empty($post_meta['yith_cog_cost' . $cog_variable][0])) ? $post_meta['yith_cog_cost' . $cog_variable][0] : '',
            '_wc_cog_cost' => (!empty($post_meta['_wc_cog_cost' . $cog_variable][0])) ? $post_meta['_wc_cog_cost' . $cog_variable][0] : '',
            '_regular_price_wmcp' => (!empty($post_meta['_regular_price_wmcp'])) ? $post_meta['_regular_price_wmcp'] : [],
            '_sale_price_wmcp' => (!empty($post_meta['_sale_price_wmcp'])) ? $post_meta['_sale_price_wmcp'] : [],
            '_yith_wcbm_product_meta' => (!empty($post_meta['_yith_wcbm_product_meta'])) ? $post_meta['_yith_wcbm_product_meta'] : [],
            '_yith_wcbm_product_meta_-_id_badge' => (!empty($yith_badge['id_badge'])) ? $yith_badge['id_badge'] : [],
            '_yith_wcbm_product_meta_-_start_date' => (!empty($yith_badge['start_date'])) ? $yith_badge['start_date'] : [],
            '_yith_wcbm_product_meta_-_end_date' => (!empty($yith_badge['end_date'])) ? $yith_badge['end_date'] : [],
            'it_product_disable_discount' => (!empty($post_meta['it_product_disable_discount'][0])) ? $post_meta['it_product_disable_discount'][0] : [],
            'it_product_hide_price_unregistered' => (!empty($post_meta['it_product_hide_price_unregistered'][0])) ? $post_meta['it_product_hide_price_unregistered'][0] : [],
            'custom_field' => $post_meta,
            'taxonomy' => $product_taxonomy,
        ];
    }

    public function create($data = [])
    {
        $product = new \WC_Product();
        $product->set_name((isset($data['name'])) ? $data['name'] : 'New Product');
        $product->set_status('draft');
        return $product->save();
    }

    public function get_product_counts_group_by_status()
    {
        $output = [];
        $all = 0;
        $result = $this->wpdb->get_results("SELECT post_status AS 'status',COUNT(*) AS 'count' FROM {$this->wpdb->posts} WHERE post_type = 'product' AND post_status NOT IN ('auto-draft') GROUP BY post_status", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        if (!empty($result) && is_array($result)) {
            foreach ($result as $item) {
                if (isset($item['status']) && isset($item['count'])) {
                    if ($item['status'] !== 'trash') {
                        $all += $item['count'];
                    }
                    $output[$item['status']] = $item['count'];
                }
            }
        }
        $output['all'] = intval($all);
        return $output;
    }

    public function get_status_color($status)
    {
        $status_colors = $this->get_status_colors();
        return (isset($status_colors[$status])) ? $status_colors[$status] : null;
    }

    private function get_status_colors()
    {
        return [
            'draft' => '#a3b7a3',
            'pending' => '#80e045',
            'private' => '#f9c662',
            'publish' => '#6ca9d6',
            'trash' => '#808080',
        ];
    }

    public function get_tax_classes()
    {
        $tax_classes[''] = esc_html__('Standard', 'ithemeland-bulk-product-editing-lite-for-woocommerce');
        foreach (\WC_Tax::get_tax_classes() as $tax_class) {
            $name = str_replace(' ', '-', strtolower($tax_class));
            $tax_classes[$name] = $tax_class;
        }
        return $tax_classes;
    }

    public function get_yith_vendors()
    {
        $yith_shop_vendor_object = [];
        if (defined("YITH_WPV_INIT")) {
            $yith_shop_vendor_object = get_terms([
                'taxonomy' => 'yith_shop_vendor',
                'hide_empty' => false,
            ]);
        }

        return $yith_shop_vendor_object;
    }

    public function get_wc_product_vendors()
    {
        $wc_shop_vendor_object = [];
        if (class_exists("WC_Product_Vendors")) {
            $wc_shop_vendor_object = get_terms([
                'taxonomy' => 'wcpv_product_vendors',
                'hide_empty' => false,
            ]);
        }

        return $wc_shop_vendor_object;
    }

    public function get_ithemeland_badge_fields()
    {
        return [
            '_unique_label_type',
            '_unique_label_shape',
            '_unique_label_advanced',
            '_unique_label_text',
            '_unique_label_badge_icon',
            '_unique-custom-background',
            '_unique-custom-text',
            '_unique_label_align',
            '_unique_label_image',
            '_unique_label_class',
            '_unique_label_font_size',
            '_unique_label_line_height',
            '_unique_label_width',
            '_unique_label_height',
            '_unique_label_border_style',
            '_unique_label_border_width_top',
            '_unique_label_border_width_right',
            '_unique_label_border_width_bottom',
            '_unique_label_border_width_left',
            '_unique_label_border_color',
            '_unique_label_border_r_tl',
            '_unique_label_border_r_tr',
            '_unique_label_border_r_br',
            '_unique_label_border_r_bl',
            '_unique_label_padding_top',
            '_unique_label_padding_right',
            '_unique_label_padding_bottom',
            '_unique_label_padding_left',
            '_unique_label_opacity',
            '_unique_label_rotation_x',
            '_unique_label_rotation_y',
            '_unique_label_rotation_z',
            '_unique_label_pos_top',
            '_unique_label_pos_right',
            '_unique_label_pos_bottom',
            '_unique_label_pos_left',
            '_unique_label_time',
            '_unique_label_start_date',
            '_unique_label_end_date',
            '_unique_label_exclude',
            '_unique_label_flip_text_h',
            '_unique_label_flip_text_v',
        ];
    }

    public function get_product_ids_with_like_names($product_ids = [])
    {
        $product_id_query = "";

        if (!empty($product_ids)) {
            $product_ids = implode(',', array_map('intval', $product_ids));
            $product_id_query = "AND ID IN ($product_ids)";
        }

        return $this->wpdb->get_results("SELECT GROUP_CONCAT(ID) as product_ids, count(*) as product_count FROM {$this->wpdb->posts} WHERE post_type = 'product' AND post_status != 'trash' {$product_id_query} GROUP BY post_title HAVING product_count > 1 ORDER BY product_count", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    }

    public function get_trash()
    {
        $args = [
            'post_type' => ['product', 'product_variation'],
            'post_status' => 'trash',
            'fields' => 'ids',
        ];

        $products = $this->get_products($args);
        return $products->posts;
    }

    public function set_shipping_classes()
    {
        $this->shipping_classes = [];

        $shipping_classes = wc()->shipping()->get_shipping_classes();
        if (!empty($shipping_classes)) {
            foreach ($shipping_classes as $shipping_class) {
                if ($shipping_class instanceof \WP_Term) {
                    $this->shipping_classes[$shipping_class->term_id] = $shipping_class->name;
                }
            }
        }
    }

    public function get_shipping_classes()
    {
        return $this->shipping_classes;
    }
}
