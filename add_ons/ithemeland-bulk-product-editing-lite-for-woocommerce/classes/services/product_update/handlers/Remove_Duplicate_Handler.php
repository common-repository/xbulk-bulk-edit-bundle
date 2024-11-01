<?php

namespace wcbel\classes\services\product_update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Others;
use wcbel\classes\repositories\History;
use wcbel\classes\repositories\Product;
use wcbel\classes\services\product_update\Handler_Interface;

class Remove_Duplicate_Handler implements Handler_Interface
{
    private static $instance;

    private $deleted_ids;
    private $update_data;
    private $product_ids;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->deleted_ids = [];
    }

    public function update($product_ids, $update_data)
    {
        $method = $this->get_method($update_data['value']);
        if (empty($method)) {
            return false;
        }

        $this->product_ids = $product_ids;
        $this->update_data = $update_data;

        // action
        return $this->{$method}();
    }

    private function get_method($value)
    {
        $methods = $this->get_methods();
        return (!empty($methods[$value]) && method_exists($this, $methods[$value])) ? $methods[$value] : '';
    }

    private function get_methods()
    {
        return [
            'trash' => 'trash_product',
            'untrash' => 'untrash_product'
        ];
    }

    private function trash_product()
    {
        $ids = (!empty($this->update_data['deleted_ids']) && is_array($this->update_data['deleted_ids'])) ? $this->update_data['deleted_ids'] : $this->get_product_ids_with_like_names();

        if (!empty($ids)) {
            foreach ($ids as $product_id) {
                wp_trash_post(intval($product_id));
                $this->deleted_ids[] = intval($product_id);
            }
        }

        // save history item
        if (!empty($this->update_data['history_id'])) {
            $history_repository = History::get_instance();
            $history_item_result = $history_repository->save_history_item([
                'history_id' => $this->update_data['history_id'],
                'historiable_id' => 0,
                'name' => $this->update_data['name'],
                'sub_name' => (!empty($this->update_data['sub_name'])) ? $this->update_data['sub_name'] : '',
                'type' => $this->update_data['type'],
                'deleted_ids' => $this->deleted_ids,
                'prev_value' => 'untrash',
                'new_value' => 'trash',
            ]);

            if (!$history_item_result) {
                return false;
            }
        }

        return true;
    }

    private function get_product_ids_with_like_names()
    {
        $output = [];
        $product_repository = Product::get_instance();
        $product_ids = (!empty($this->product_ids) && is_array($this->product_ids) && !empty($this->product_ids[0])) ? $this->product_ids : [];
        $products = $product_repository->get_product_ids_with_like_names($product_ids);

        if (!empty($products)) {
            // move to trash
            foreach ($products as $product) {
                $ids = explode(',', $product['product_ids']);
                if (!empty($ids)) {
                    $ids = array_reverse($ids);
                    // unset last product
                    if (!empty($ids[0])) {
                        unset($ids[0]);
                    }

                    $output[] = $ids;
                }
            }
        }

        return Others::array_flatten($output);
    }

    private function untrash_product()
    {
        if (empty($this->update_data['deleted_ids'])) {
            return false;
        }

        foreach ($this->update_data['deleted_ids'] as $product_id) {
            wp_untrash_post(intval($product_id));
            $this->deleted_ids[] = intval($product_id);
        }

        return true;
    }
}
