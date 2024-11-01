<?php

namespace wcbel\classes\services\product_update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Product_Helper;
use wcbel\classes\repositories\History;
use wcbel\classes\repositories\Product;
use wcbel\classes\services\product_update\Handler_Interface;

class Woocommerce_Handler implements Handler_Interface
{
    private static $instance;

    private $product;
    private $update_data;
    private $setter_method;
    private $deleted_ids;
    private $created_ids;
    private $product_repository;
    private $current_field_value;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->product_repository = Product::get_instance();
    }

    public function update($product_ids, $update_data)
    {
        $this->setter_method = $this->get_setter($update_data['name']);
        if (empty($this->setter_method) && empty($product_ids) && !is_array($product_ids)) {
            return false;
        }

        foreach ($product_ids as $product_id) {
            $product = $this->product_repository->get_product(intval($product_id));
            if (!($product instanceof \WC_Product)) {
                return false;
            }

            $this->product = $product;
            $this->update_data = $update_data;

            // has update method ?
            if (!is_object(${$this->setter_method['object']}) || !method_exists(${$this->setter_method['object']}, $this->setter_method['method'])) {
                return false;
            };

            $getter_method = $this->get_woocommerce_getter($this->update_data['name']);
            if ($getter_method == 'get_date_created') {
                $date_time = method_exists($product, $getter_method) ? $product->{$getter_method}() : '';
                $this->current_field_value = (method_exists($date_time, 'date')) ? $date_time->date('Y/m/d H:i') : $date_time;
            } else {
                $this->current_field_value = method_exists($product, $getter_method) ? $product->{$getter_method}() : '';
            }

            // replace text variable
            if (!is_numeric($this->update_data['value']) && !is_array($this->update_data['value'])) {
                $this->update_data['value'] = Product_Helper::apply_variable($product, $this->update_data['value']);
            }
            if (!empty($this->update_data['replace'])) {
                $this->update_data['replace'] = Product_Helper::apply_variable($product, $this->update_data['replace']);
            }

            // set value with operator
            if (!empty($this->update_data['operator'])) {
                $this->set_value_with_operator();
            }

            // run update method
            try {
                ${$this->setter_method['object']}->{$this->setter_method['method']}($this->update_data['value']);
                if (method_exists(${$this->setter_method['object']}, 'save')) {
                    ${$this->setter_method['object']}->save();
                }
            } catch (\Exception $e) {
                return false;
            }

            // save history item
            if (!empty($this->update_data['history_id'])) {
                $result = $this->save_history();
                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }

    private function set_product_regular_price($value)
    {
        if ($value <= $this->product->get_sale_price()) {
            $this->product->set_sale_price('');
        }
        $value = ($value == '') ? '' : floatval($value);
        $this->product->set_regular_price($value);
        $this->product->save();
    }

    private function set_date_on_sale_from($value)
    {
        $this->product->set_date_on_sale_from(strtotime($value));
        $this->product->save();
    }

    private function set_date_on_sale_to($value)
    {
        $this->product->set_date_on_sale_to(strtotime($value));
        $this->product->save();
    }

    private function set_product_sale_price($value)
    {
        $regular_price = floatval($this->product->get_regular_price());
        if (empty($regular_price)) {
            return;
        }
        $value = ($value == '') ? '' : floatval($value);
        if (!empty($value)) {
            $value = (floatval($value) >= $regular_price) ? floatval($regular_price - 0.01) : floatval($value);
        }
        $this->product->set_sale_price($value);

        if ($value == '') {
            $this->save_history([
                'name' => 'date_on_sale_from',
                'prev_value' => $this->product->get_date_on_sale_from(),
                'new_value' => '',
            ]);

            $this->save_history([
                'name' => 'date_on_sale_to',
                'prev_value' => $this->product->get_date_on_sale_to(),
                'new_value' => '',
            ]);

            $this->product->set_date_on_sale_from('');
            $this->product->set_date_on_sale_to('');
        }

        $this->product->save();
    }

    private function set_product_stock_quantity($value)
    {
        $this->product->set_manage_stock(true);
        $this->product->set_stock_quantity($value);
        $this->product->save();
    }

    private function set_product_downloadable_files($value)
    {
        if (is_array($value) && !empty($value['files_name']) && !empty($value['files_url'])) {
            $downloads = [];
            $files_name = esc_sql($value['files_name']);
            $files_url = esc_sql($value['files_url']);
            for ($i = 0; $i < count($files_name); $i++) {
                $md5 = md5($files_url[$i]);
                $download_file = new \WC_Product_Download();
                $download_file->set_id($md5);
                $download_file->set_name($files_name[$i]);
                $download_file->set_file($files_url[$i]);
                if ($download_file->is_allowed_filetype()) {
                    $downloads[$md5] = $download_file;
                }
            }
            if (!empty($downloads)) {
                $this->product->set_downloads($downloads);
                $this->product->save();
            }
        }
    }

    private function set_product_type($value)
    {
        if ($this->product->get_type() == 'variable' && $value != 'variable') {
            $variations = $this->product->get_children();
            if (!empty($variations) && is_array($variations)) {
                foreach ($variations as $variation) {
                    wp_delete_post(intval($variation));
                }
            }
        }

        $product_classname = \WC_Product_Factory::get_product_classname($this->product->get_id(), sanitize_text_field($value));
        $new_product = new $product_classname($this->product->get_id());
        return $new_product->save();
    }

    private function get_setter($field_name)
    {
        $methods = $this->get_setter_methods();
        return (!empty($methods[$field_name])) ? $methods[$field_name] : null;
    }

    private function get_woocommerce_getter($field_name)
    {
        $methods = $this->get_woocommerce_getter_methods();
        return (!empty($methods[$field_name])) ? $methods[$field_name] : null;
    }

    private function get_setter_methods()
    {
        return [
            'title' => [
                'object' => 'product',
                'method' => 'set_name',
            ],
            'description' => [
                'object' => 'product',
                'method' => 'set_description',
            ],
            'short_description' => [
                'object' => 'product',
                'method' => 'set_short_description',
            ],
            'status' => [
                'object' => 'product',
                'method' => 'set_status',
            ],
            'date_created' => [
                'object' => 'product',
                'method' => 'set_date_created',
            ],
            'manage_stock' => [
                'object' => 'product',
                'method' => 'set_manage_stock',
            ],
            'image_id' => [
                'object' => 'product',
                'method' => 'set_image_id',
            ],
            'regular_price' => [
                'object' => 'this',
                'method' => 'set_product_regular_price',
            ],
            'sale_price' => [
                'object' => 'this',
                'method' => 'set_product_sale_price',
            ],
            'catalog_visibility' => [
                'object' => 'product',
                'method' => 'set_catalog_visibility',
            ],
            'slug' => [
                'object' => 'product',
                'method' => 'set_slug',
            ],
            'sku' => [
                'object' => 'product',
                'method' => 'set_sku',
            ],
            'purchase_note' => [
                'object' => 'product',
                'method' => 'set_purchase_note',
            ],
            'menu_order' => [
                'object' => 'product',
                'method' => 'set_menu_order',
            ],
            'sold_individually' => [
                'object' => 'product',
                'method' => 'set_sold_individually',
            ],
            'reviews_allowed' => [
                'object' => 'product',
                'method' => 'set_reviews_allowed',
            ],
            'gallery_image_ids' => [
                'object' => 'product',
                'method' => 'set_gallery_image_ids',
            ],
            'date_on_sale_from' => [
                'object' => 'this',
                'method' => 'set_date_on_sale_from',
            ],
            'date_on_sale_to' => [
                'object' => 'this',
                'method' => 'set_date_on_sale_to',
            ],
            'tax_status' => [
                'object' => 'product',
                'method' => 'set_tax_status',
            ],
            'tax_class' => [
                'object' => 'product',
                'method' => 'set_tax_class',
            ],
            'shipping_class' => [
                'object' => 'product',
                'method' => 'set_shipping_class_id',
            ],
            'width' => [
                'object' => 'product',
                'method' => 'set_width',
            ],
            'height' => [
                'object' => 'product',
                'method' => 'set_height',
            ],
            'length' => [
                'object' => 'product',
                'method' => 'set_length',
            ],
            'weight' => [
                'object' => 'product',
                'method' => 'set_weight',
            ],
            'stock_status' => [
                'object' => 'product',
                'method' => 'set_stock_status',
            ],
            'stock_quantity' => [
                'object' => 'this',
                'method' => 'set_product_stock_quantity',
            ],
            'low_stock_amount' => [
                'object' => 'product',
                'method' => 'set_low_stock_amount',
            ],
            'product_type' => [
                'object' => 'this',
                'method' => 'set_product_type',
            ],
            'backorders' => [
                'object' => 'product',
                'method' => 'set_backorders',
            ],
            'featured' => [
                'object' => 'product',
                'method' => 'set_featured',
            ],
            'virtual' => [
                'object' => 'product',
                'method' => 'set_virtual',
            ],
            'downloadable' => [
                'object' => 'product',
                'method' => 'set_downloadable',
            ],
            'downloadable_files' => [
                'object' => 'this',
                'method' => 'set_product_downloadable_files',
            ],
            'download_limit' => [
                'object' => 'product',
                'method' => 'set_download_limit',
            ],
            'download_expiry' => [
                'object' => 'product',
                'method' => 'set_download_expiry',
            ],
            'total_sales' => [
                'object' => 'product',
                'method' => 'set_total_sales',
            ],
            'review_count' => [
                'object' => 'product',
                'method' => 'set_review_count',
            ],
            'average_rating' => [
                'object' => 'product',
                'method' => 'set_average_rating',
            ],
            'upsell_ids' => [
                'object' => 'product',
                'method' => 'set_upsell_ids',
            ],
            'cross_sell_ids' => [
                'object' => 'product',
                'method' => 'set_cross_sell_ids',
            ],
            'default_attributes' => [
                'object' => 'product',
                'method' => 'set_default_attributes',
            ],
        ];
    }

    private function get_woocommerce_getter_methods()
    {
        return [
            'title' => 'get_title',
            'product_type' => 'get_type',
            'description' => 'get_description',
            'short_description' => 'get_short_description',
            'status' => 'get_status',
            'date_created' => 'get_date_created',
            'manage_stock' => 'get_manage_stock',
            'image_id' => 'get_image_id',
            'regular_price' => 'get_regular_price',
            'sale_price' => 'get_sale_price',
            'catalog_visibility' => 'get_catalog_visibility',
            'slug' => 'get_slug',
            'sku' => 'get_sku',
            'purchase_note' => 'get_purchase_note',
            'menu_order' => 'get_menu_order',
            'sold_individually' => 'get_sold_individually',
            'reviews_allowed' => 'get_reviews_allowed',
            'gallery_image_ids' => 'get_gallery_image_ids',
            'date_on_sale_from' => 'get_date_on_sale_from',
            'date_on_sale_to' => 'get_date_on_sale_to',
            'tax_status' => 'get_tax_status',
            'tax_class' => 'get_tax_class',
            'shipping_class' => 'get_shipping_class',
            'width' => 'get_width',
            'height' => 'get_height',
            'length' => 'get_length',
            'weight' => 'get_weight',
            'stock_status' => 'get_stock_status',
            'stock_quantity' => 'get_stock_quantity',
            'low_stock_amount' => 'get_low_stock_amount',
            'backorders' => 'get_backorders',
            'featured' => 'get_featured',
            'virtual' => 'get_virtual',
            'downloadable' => 'get_downloadable',
            'downloadable_files' => 'get_downloads',
            'download_limit' => 'get_download_limit',
            'download_expiry' => 'get_download_expiry',
            'total_sales' => 'get_total_sales',
            'review_count' => 'get_review_count',
            'average_rating' => 'get_average_rating',
            'upsell_ids' => 'get_upsell_ids',
            'cross_sell_ids' => 'get_cross_sell_ids',
        ];
    }

    private function set_value_with_operator()
    {
        if ($this->update_data['name'] == 'regular_price') {
            $this->update_data['sale_price'] = $this->product->get_sale_price();
        }
        if ($this->update_data['name'] == 'sale_price') {
            $this->update_data['regular_price'] = $this->product->get_regular_price();
        }

        $this->update_data['value'] = Product_Helper::apply_operator($this->current_field_value, $this->update_data);
    }

    private function save_history($data = [])
    {
        $name = (!empty($data['name'])) ? $data['name'] : $this->update_data['name'];
        $prev_value = (!empty($data['prev_value'])) ? $data['prev_value'] : $this->current_field_value;
        $new_value = (!empty($data['new_value'])) ? sanitize_text_field($data['new_value']) : $this->update_data['value'];

        $history_repository = History::get_instance();
        return $history_repository->save_history_item([
            'history_id' => $this->update_data['history_id'],
            'historiable_id' => $this->product->get_id(),
            'name' => $name,
            'sub_name' => (!empty($this->update_data['sub_name'])) ? $this->update_data['sub_name'] : '',
            'type' => $this->update_data['type'],
            'deleted_ids' => $this->deleted_ids,
            'created_ids' => $this->created_ids,
            'prev_value' => $prev_value,
            'new_value' => $new_value,
        ]);
    }
}
