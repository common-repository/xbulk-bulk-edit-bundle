<?php

namespace iwbvel\classes\services\product_update\product_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\helpers\Sanitizer;
use iwbvel\classes\helpers\Product_Helper;
use iwbvel\classes\repositories\History;
use iwbvel\classes\repositories\Product;
use iwbvel\classes\services\product_update\Handler_Interface;

class Meta_Field_Handler implements Handler_Interface
{
    private static $instance;

    private $product_ids;
    private $product_repository;
    private $product;
    private $setter_method;
    private $update_data;
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
    }

    public function update($product_ids, $update_data)
    {
        $this->setter_method = $this->get_setter($update_data['name']);
        if (empty($this->setter_method) && empty($product_ids) && !is_array($product_ids)) {
            return false;
        }

        // has update method ?
        if (!method_exists($this, $this->setter_method)) {
            return false;
        };

        $this->update_data = $update_data;
        $this->product_ids = $product_ids;

        foreach ($product_ids as $product_id) {
            if (!isset($this->update_data['value'])) {
                $this->update_data['value'] = '';
            }

            $this->product_repository = Product::get_instance();
            $this->product = $this->product_repository->get_product(intval($product_id));
            if (!($this->product instanceof \WC_Product)) {
                return false;
            }

            // get current value by getter methods
            $getter_method = $this->get_getter($this->update_data['name']);
            $this->current_field_value = (!empty($getter_method) && method_exists($this, $getter_method)) ? $this->{$getter_method}() : '';

            // run update method
            $this->{$this->setter_method}();

            // save history item
            if (!empty($this->update_data['history_id'])) {
                $history_repository = History::get_instance();
                $history_item_result = $history_repository->save_history_item([
                    'history_id' => $this->update_data['history_id'],
                    'historiable_id' => $this->product->get_id(),
                    'name' => $this->update_data['name'],
                    'sub_name' => (!empty($this->update_data['sub_name'])) ? $this->update_data['sub_name'] : '',
                    'type' => $this->update_data['type'],
                    'prev_value' => $this->current_field_value,
                    'new_value' => $this->update_data['value'],
                ]);
                if (!$history_item_result) {
                    return false;
                }
            }
        }

        return true;
    }

    private function get_getter($field_name)
    {
        $getter_methods = $this->get_getter_methods();
        return (!empty($getter_methods[$field_name])) ? $getter_methods[$field_name] : $getter_methods['default_meta_field'];
    }

    private function get_getter_methods()
    {
        return [
            'default_meta_field' => 'get_default_meta_field',
        ];
    }

    private function get_setter($field_name)
    {
        $setter_methods = $this->get_setter_methods();
        return (!empty($setter_methods[$field_name])) ? $setter_methods[$field_name] : $setter_methods['default_meta_field'];
    }

    private function get_setter_methods()
    {
        return [
            'default_meta_field' => 'set_default_meta_field',
            'allow_combination' => 'set_allow_combination',
        ];
    }

    private function get_default_meta_field()
    {
        return (!empty($this->update_data['name'])) ? get_post_meta($this->product->get_id(), $this->update_data['name'], true) : '';
    }

    private function set_default_meta_field()
    {
        // set value with operator
        if (!empty($this->update_data['operator'])) {
            $this->update_data['value'] = Product_Helper::apply_operator($this->current_field_value, $this->update_data);
        }

        return update_post_meta($this->product->get_id(), esc_sql($this->update_data['name']), esc_sql($this->update_data['value']));
    }

    private function set_allow_combination()
    {
        return ($this->product->get_type() == 'variable') ? update_post_meta($this->product->get_id(), esc_sql($this->update_data['name']), esc_sql($this->update_data['value'])) : false;
    }
}
