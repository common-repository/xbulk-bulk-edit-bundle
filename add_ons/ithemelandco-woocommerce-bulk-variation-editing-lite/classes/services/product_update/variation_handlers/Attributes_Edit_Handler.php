<?php

namespace iwbvel\classes\services\product_update\variation_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\repositories\History;
use iwbvel\classes\services\product_update\Handler_Interface;

class Attributes_Edit_Handler implements Handler_Interface
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
        if (!empty($update_data['value']['attributes']) || empty($update_data['value']['variation_id']))
            foreach ($product_ids as $product_id) {
                $product = wc_get_product(intval($product_id));
                if (!($product instanceof \WC_Product_Variable)) {
                    continue;
                }

                // update product attribute
                $update_result = $this->attribute_update($product, [
                    'variation_id' => intval($update_data['value']['variation_id']),
                    'attributes' => $update_data['value']['attributes']
                ]);

                if (!$update_result) {
                    continue;
                }

                // update variations
                $variation = wc_get_product(intval($update_data['value']['variation_id']));
                if (!($variation instanceof \WC_Product_Variation) || $variation->get_parent_id() != $product->get_id()) {
                    continue;
                }

                $prev_value = [
                    'variation_id' => $variation->get_id,
                    'attributes' => $variation->get_attributes()
                ];

                $variation->set_attributes($update_data['value']['attributes']);
                $variation->save();

                if (!empty($update_data['history_id'])) {
                    $history_repository = History::get_instance();
                    $result = $history_repository->save_history_item([
                        'history_id' => intval($update_data['history_id']),
                        'historiable_id' => intval($product->get_id()),
                        'name' => 'attributes_edit',
                        'sub_name' => '',
                        'type' => 'variation',
                        'action' => 'attributes_edit',
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

    private function attribute_update($product, $data)
    {
        if (empty($data['attributes'])) {
            return false;
        }

        $product_attributes = $product->get_attributes();

        foreach ($data['attributes'] as $name => $term) {
            $term_object = get_term_by('slug', sanitize_text_field($term), sanitize_text_field($name));
            if (!($term_object instanceof \WP_Term)) {
                continue;
            }

            if (!isset($product_attributes[$name])) {
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if (!empty($attribute_taxonomies)) {
                    $attrs = array_column($attribute_taxonomies, 'attribute_id', 'attribute_name');
                    $attribute_name = str_replace('pa_', '', $name);
                    if (!empty($attrs[$attribute_name])) {
                        $new_object = new \WC_Product_Attribute();
                        $new_object->set_id($attrs[$attribute_name]);
                        $new_object->set_name($name);
                        $new_object->set_options([$term_object->term_id]);
                        $new_object->set_position(count($product_attributes));
                        $new_object->set_visible(true);
                        $new_object->set_variation(true);
                        $product_attributes[$name] = $new_object;
                    }
                }
            } else {
                $options = $product_attributes[$name]->get_options();

                if (!in_array($term_object->term_id, $options)) {
                    $options[] = $term_object->term_id;
                    $new_object = new \WC_Product_Attribute();
                    $new_object->set_id($product_attributes[$name]->get_id());
                    $new_object->set_name($product_attributes[$name]->get_name());
                    $new_object->set_options($options);
                    $new_object->set_position($product_attributes[$name]->get_position());
                    $new_object->set_visible(true);
                    $new_object->set_variation(true);
                    $product_attributes[$name] = $new_object;
                }
            }
        }

        $product->set_attributes($product_attributes);
        return $product->save();
    }
}
