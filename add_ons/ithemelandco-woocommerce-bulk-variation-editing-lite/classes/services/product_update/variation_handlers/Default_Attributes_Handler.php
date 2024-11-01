<?php

namespace iwbvel\classes\services\product_update\variation_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\repositories\History;
use iwbvel\classes\services\product_update\Handler_Interface;

class Default_Attributes_Handler implements Handler_Interface
{
    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function update($product_ids, $update_data)
    {
        if (empty($update_data['value']['attributes'])) {
            return false;
        }

        foreach ($product_ids as $product_id) {
            $product = wc_get_product(intval($product_id));
            if (!($product instanceof \WC_Product_Variable)) {
                continue;
            }

            $prev_value['attributes'] = $product->get_default_attributes();
            $product->set_default_attributes($update_data['value']['attributes']);
            $product->save();

            if (!empty($update_data['history_id'])) {
                $history_repository = History::get_instance();
                $result = $history_repository->save_history_item([
                    'history_id' => intval($update_data['history_id']),
                    'historiable_id' => intval($product->get_id()),
                    'name' => 'default_variation',
                    'sub_name' => '',
                    'type' => 'variation',
                    'action' => 'default_attributes',
                    'prev_value' =>  $prev_value,
                    'new_value' => $update_data['value'],
                ]);

                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }
}
