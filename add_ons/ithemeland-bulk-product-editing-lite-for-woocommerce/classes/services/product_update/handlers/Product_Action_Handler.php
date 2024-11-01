<?php

namespace wcbel\classes\services\product_update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\services\product_update\Handler_Interface;

class Product_Action_Handler implements Handler_Interface
{
    private static $instance;

    private $product_id;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function update($product_ids, $update_data)
    {
        $methods = $this->get_methods();
        $method = (!empty($methods[$update_data['value']])) ? $methods[$update_data['value']] : '';
        if (empty($method) || !method_exists($this, $method)) {
            return false;
        }

        foreach ($product_ids as $product_id) {
            $this->product_id = intval($product_id);
            $this->{$method}();
        }

        return true;
    }

    private function get_methods()
    {
        return [
            'trash' => 'delete_product',
            'untrash' => 'restore_product'
        ];
    }

    private function delete_product()
    {
        return wp_trash_post($this->product_id);
    }

    private function restore_product()
    {
        return wp_untrash_post($this->product_id);
    }
}
