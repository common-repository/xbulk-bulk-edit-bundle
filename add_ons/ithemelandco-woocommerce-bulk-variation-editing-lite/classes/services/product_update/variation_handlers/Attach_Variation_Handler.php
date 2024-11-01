<?php

namespace iwbvel\classes\services\product_update\variation_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\helpers\Product_Helper;
use iwbvel\classes\repositories\History;
use iwbvel\classes\services\product_update\Handler_Interface;

class Attach_Variation_Handler implements Handler_Interface
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
        foreach ($product_ids as $product_id) {
            $product = wc_get_product(intval($product_id));
            if (!($product instanceof \WC_Product_Variable)) {
                continue;
            }

            if (isset($update_data['revert_mode']) && $update_data['revert_mode'] == true) {
                if (!empty($update_data['value'])) {
                    if (!empty($update_data['value']['attribute'])) {
                        $this->attribute_revert($product, $update_data['value']['attribute']);
                    }

                    if (!empty($update_data['value']['variations'])) {
                        $this->variations_revert($update_data['value']['variations']);
                    }

                    if (!empty($update_data['value']['new_variation_ids'])) {
                        $this->variations_remove($update_data['value']['new_variation_ids']);
                    }

                    $product->save();
                }
            } else {
                $attribute_name = sanitize_text_field($update_data['value']['attribute']);
                $terms = array_map('intval', $update_data['value']['terms']);

                // update attributes
                $prev_value['attribute'] = [
                    'name' => $attribute_name,
                    'terms' => $terms,
                ];

                $this->add_attribute_terms($product, $attribute_name, $terms);

                // update variations
                if (!empty($update_data['value']['variations'])) {
                    foreach ($update_data['value']['variations'] as $variation_id => $term_id) {
                        $term = get_term_by('term_id', $term_id, $attribute_name);
                        $variation = wc_get_product(intval($variation_id));

                        if (!($variation instanceof \WC_Product_Variation) || !($term instanceof \WP_Term)) {
                            continue;
                        }

                        $variation_attributes = $variation->get_attributes();
                        $prev_value['variations'][$variation->get_id()] = $variation_attributes;
                        $variation_attributes[strtolower(urlencode($attribute_name))] = $term->slug;
                        $variation->set_attributes($variation_attributes);
                        $variation->save();
                    }

                    $product->save();
                }

                if (!empty($update_data['history_id'])) {
                    $history_repository = History::get_instance();
                    $result = $history_repository->save_history_item([
                        'history_id' => intval($update_data['history_id']),
                        'historiable_id' => intval($product->get_id()),
                        'name' => 'attach_terms',
                        'sub_name' => '',
                        'type' => 'variation',
                        'action' => 'attach_variations',
                        'prev_value' =>  $prev_value,
                        'new_value' => $update_data['value'],
                    ]);

                    if (!$result) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function add_attribute($product, $attribute, $terms)
    {
        $product_attributes = $product->get_attributes();
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        $product_attribute = new \WC_Product_Attribute();
        $attributes = array_column($attribute_taxonomies, 'attribute_id', 'attribute_name');
        $attribute_name = str_replace('pa_', '', $attribute);

        if (!empty($attributes[$attribute_name])) {
            $product_attribute->set_id($attributes[$attribute_name]);
            $product_attribute->set_name($attribute);
            $product_attribute->set_options($terms);
            $product_attribute->set_position(count($product_attributes));
            $product_attribute->set_visible(true);
            $product_attribute->set_variation(true);
            $product_attributes[$attribute] = $product_attribute;
        }
        $product->set_attributes($product_attributes);
        return $product->save();
    }

    private function add_attribute_terms($product, $attribute, $terms)
    {
        $product_attributes = $product->get_attributes();
        if (!isset($product_attributes[$attribute])) {
            $this->add_attribute($product, $attribute, $terms);
        } else {
            $options = $product_attributes[$attribute]->get_options();
            $options = array_unique(array_merge($options, $terms));
            $new_object = new \WC_Product_Attribute();
            $new_object->set_id($product_attributes[$attribute]->get_id());
            $new_object->set_name($product_attributes[$attribute]->get_name());
            $new_object->set_options($options);
            $new_object->set_position($product_attributes[$attribute]->get_position());
            $new_object->set_visible(true);
            $new_object->set_variation(true);
            $product_attributes[$attribute] = $new_object;

            $product->set_attributes($product_attributes);
            $product->save();
        }
    }

    private function attribute_revert($product, $attribute)
    {
        if (empty($attribute['name']) || empty($attribute['terms'])) {
            return false;
        }

        $product_attributes = $product->get_attributes();

        if (isset($product_attributes[$attribute['name']]) && $product_attributes[$attribute['name']] instanceof \WC_Product_Attribute) {
            $options = $product_attributes[$attribute['name']]->get_options();
            if (is_array($attribute['terms'])) {
                $options = array_diff($options, array_map('intval', $attribute['terms']));
            } else {
                $term_key = array_search(sanitize_text_field($attribute['terms']), $options);
                if ($term_key !== false) {
                    unset($options[$term_key]);
                }
            }

            if (empty($options)) {
                unset($product_attributes[$attribute['name']]);
            } else {
                $product_attributes[$attribute['name']]->set_options($options);
            }

            $product->set_attributes($product_attributes);
            return $product->save();
        }

        return true;
    }

    private function variations_revert($variations)
    {
        if (!empty($variations) && is_array($variations)) {
            foreach ($variations as $variation_id => $variation_attributes) {
                $variation = wc_get_product(intval($variation_id));
                if ($variation instanceof \WC_Product_Variation && is_array($variation_attributes)) {
                    $variation->set_attributes($variation_attributes);
                    $variation->save();
                }
            }
        }

        return true;
    }

    private function variations_remove($variation_ids)
    {
        foreach ($variation_ids as $variation_id) {
            wp_delete_post(intval($variation_id), true);
        }

        return true;
    }
}
