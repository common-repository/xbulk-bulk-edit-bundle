<?php

namespace iwbvel\classes\services\product_update\variation_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\repositories\History;
use iwbvel\classes\services\product_update\Handler_Interface;

class Swap_Variation_Handler implements Handler_Interface
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
        if (!empty($update_data['value']['attribute']) || empty($update_data['value']['from_term']) || empty($update_data['value']['to_term']))
            foreach ($product_ids as $product_id) {
                $product = wc_get_product(intval($product_id));
                if (!($product instanceof \WC_Product_Variable)) {
                    continue;
                }

                $attribute_name = sanitize_text_field($update_data['value']['attribute']);
                $from_term = intval($update_data['value']['from_term']);
                $to_term = intval($update_data['value']['to_term']);
                $product_variations = (!empty($update_data['value']['variation_ids']) && is_array($update_data['value']['variation_ids'])) ? array_map('intval', $update_data['value']['variation_ids']) : $product->get_children();

                if (!empty($update_data['history_id'])) {
                    $prev_value = [
                        'variation_ids' => $update_data['value']['variation_ids'],
                        'attribute' => $attribute_name,
                        'from_term' => $to_term,
                        'to_term' => $from_term,
                    ];
                }

                // update product attribute
                $update_result = $this->attribute_update($product, [
                    'variation_ids' => $update_data['value']['variation_ids'],
                    'attribute_name' => $attribute_name,
                    'from_term' => $from_term,
                    'to_term' => $to_term,
                ]);

                if (!$update_result) {
                    continue;
                }

                // update variations
                if (!empty($product_variations)) {
                    foreach ($product_variations as $variation_id) {
                        $variation = wc_get_product(intval($variation_id));
                        if (!($variation instanceof \WC_Product_Variation)) {
                            continue;
                        }

                        $variation_attributes = $variation->get_attributes();
                        if (!isset($variation_attributes[strtolower(urlencode($attribute_name))])) {
                            continue;
                        }

                        $from_term_object = get_term_by('term_id', $from_term, $attribute_name);
                        $to_term_object = get_term_by('term_id', $to_term, $attribute_name);
                        if (!($from_term_object instanceof \WP_Term) || !($to_term_object instanceof \WP_Term)) {
                            continue;
                        }

                        if ($variation_attributes[strtolower(urlencode($attribute_name))] == $from_term_object->slug) {
                            $variation_attributes[strtolower(urlencode($attribute_name))] = $to_term_object->slug;
                            $variation->set_attributes($variation_attributes);
                            $variation->save();
                        }
                    }
                }

                if (!empty($update_data['history_id'])) {
                    $history_repository = History::get_instance();
                    $result = $history_repository->save_history_item([
                        'history_id' => intval($update_data['history_id']),
                        'historiable_id' => intval($product->get_id()),
                        'name' => 'swap_terms',
                        'sub_name' => '',
                        'type' => 'variation',
                        'action' => 'swap_variations',
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
        $product_attributes = $product->get_attributes();
        $attribute_object = $product_attributes[$data['attribute_name']];
        if (empty($attribute_object) || !($attribute_object instanceof \WC_Product_Attribute)) {
            return false;
        }

        $attribute_options = $attribute_object->get_options();
        $term_key = array_search($data['from_term'], $attribute_options);
        if ($term_key === false) {
            return false;
        }

        $attribute_options[$term_key] = $data['to_term'];

        $new_object = new \WC_Product_Attribute();
        $new_object->set_id($attribute_object->get_id());
        $new_object->set_name($attribute_object->get_name());
        $new_object->set_options(array_unique($attribute_options));
        $new_object->set_position($attribute_object->get_position());
        $new_object->set_visible(true);
        $new_object->set_variation(true);

        $product_attributes[$data['attribute_name']] = $new_object;
        $product->set_attributes($product_attributes);
        return $product->save();
    }
}
