<?php

namespace wcbel\classes\providers\product;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\providers\column\ProductColumnProvider;
use wcbel\classes\repositories\Product;
use wcbel\classes\repositories\Search;
use wcbel\classes\repositories\Setting;

class ProductProvider
{
    private static $instance = null;
    private $column_provider;
    private $settings;
    private $items;
    private $filter_data;
    private $product_repository;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->column_provider = ProductColumnProvider::get_instance();

        $setting_repository = new Setting();
        $this->settings = $setting_repository->get_settings();

        $this->product_repository = Product::get_instance();

        $search_repository = new Search();
        $current_data = $search_repository->get_current_data();
        $this->filter_data = (!empty($current_data['last_filter_data'])) ? $current_data['last_filter_data'] : [];
    }

    public function get_items($items, $columns)
    {
        $output['items'] = '';
        $output['includes'] = [];
        $this->items = $items;
        if (empty($this->items['parents']) && empty($this->items['variations'])) {
            return null;
        }

        $show_only_filtered_variations = (isset($this->settings['show_only_filtered_variations'])) ? $this->settings['show_only_filtered_variations'] : 'no';

        if (!empty($this->items['parents'])) {
            $products = $this->product_repository->get_product_object_by_ids([
                'include' => array_map('intval', $this->items['parents']),
                'post_status' => ['any', 'trash'],
            ]);

            if (!empty($products)) {
                foreach ($products as $product) {
                    if ($product->get_type() == 'variable') {
                        $output = $this->get_output($product, $columns, $output);
                        $output = $this->get_children_output($product, $columns, $output);
                    } else {
                        $output = $this->get_output($product, $columns, $output);
                    }
                }
            }
        }

        if (!empty($this->items['variations'])) {
            $variations = $this->product_repository->get_product_object_by_ids([
                'include' => array_map('intval', $this->items['variations']),
                'post_status' => ['any', 'trash'],
            ]);

            foreach ($variations as $variation) {
                if ($variation instanceof \WC_Product_Variation) {
                    if ($show_only_filtered_variations == 'yes') {
                        $output = $this->get_output($variation, $columns, $output);
                    } else {
                        if (!in_array($variation->get_parent_id(), $this->items['parents'])) {
                            $parent_object = $this->product_repository->get_product($variation->get_parent_id());
                            if ($parent_object instanceof \WC_Product) {
                                $output = $this->get_output($parent_object, $columns, $output);
                                $output = $this->get_children_output($parent_object, $columns, $output);
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }

    private function get_output($item, $columns, $output)
    {
        if (!isset($output['items'])) {
            $output['items'] = '';
        }

        if (!isset($output['includes'])) {
            $output['includes'] = [];
        }

        $result = $this->column_provider->get_item_columns($item, $columns);
        if (is_array($result) && isset($result['items'])) {
            $output['items'] .= $result['items'];
            $output['includes'][] = $result['includes'];
        } else {
            $output['items'] .= $result;
        }

        return $output;
    }

    private function get_children_output($product, $columns, $output)
    {
        $children_ids = $product->get_children();
        if (!empty($children_ids) && is_array($children_ids)) {
            $children_ids = (!empty($this->filter_data['product_attributes'])) ? array_intersect($this->items['variations'], $children_ids) : $children_ids;
            if (!empty($children_ids)) {
                $children = $this->product_repository->get_product_object_by_ids(['include' => array_map('intval', $children_ids)]);
                foreach ($children as $child) {
                    if (($key = array_search($child->get_id(), $this->items['variations'])) !== false) {
                        unset($this->items['variations'][$key]);
                    }
                    $output = $this->get_output($child, $columns, $output);
                }
            }
        }

        return $output;
    }
}
