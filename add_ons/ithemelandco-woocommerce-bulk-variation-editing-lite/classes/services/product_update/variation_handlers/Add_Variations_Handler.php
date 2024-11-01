<?php

namespace iwbvel\classes\services\product_update\variation_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\repositories\History;
use iwbvel\classes\repositories\Product;
use iwbvel\classes\services\product_update\Handler_Interface;

class Add_Variations_Handler implements Handler_Interface
{
    private static $instance;

    private $product;
    private $prev_value;
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
        $this->product_repository = Product::get_instance();
    }

    public function update($product_ids, $update_data)
    {
        if (empty($product_ids) || !is_array($product_ids) || empty($update_data) || !is_array($update_data)) {
            return false;
        }

        foreach ($product_ids as $product_id) {
            $this->product = $this->product_repository->get_product(intval($product_id));
            if (!($this->product instanceof \WC_Product)) {
                continue;
            }

            if (isset($update_data['revert_mode']) && $update_data['revert_mode'] == true) {
                if (!empty($update_data['value'])) {
                    if (!empty($update_data['value']['attributes'])) {
                        $this->attribute_revert($this->product, $update_data['value']['attributes']);
                    }

                    if (!empty($update_data['value']['new_variation_ids'])) {
                        foreach ($update_data['value']['new_variation_ids'] as $variation_id) {
                            wp_trash_post(intval($variation_id));
                        }
                    }

                    $this->product->save();
                }
            } else {
                if (empty($update_data['value']['variations']) || empty($update_data['value']['attributes'])) {
                    return false;
                }

                $this->prev_value = [
                    'product_type' => $this->product->get_type(),
                    'new_variation_ids' => [],
                    'attributes' => [],
                ];

                if (isset($update_data['value']['product_type']) && $update_data['value']['product_type'] != $this->product->get_type()) {
                    $product_classname = \WC_Product_Factory::get_product_classname($this->product->get_id(), sanitize_text_field($update_data['value']['product_type']));
                    $this->product = new $product_classname($this->product->get_id());
                    $this->product->save();
                }

                $this->set_prev_attributes();

                $this->attributes_update($update_data['value']['attributes']);

                foreach ($update_data['value']['variations'] as $variation_attributes) {
                    $this->prev_value['new_variation_ids'][] = $this->add_variation($variation_attributes);
                }

                if (!empty($update_data['history_id'])) {
                    $result = $this->save_history_item([
                        'history_id' => intval($update_data['history_id']),
                        'product_id' => intval($this->product->get_id()),
                        'name' => 'bulk_variation_update',
                        'type' => 'variation',
                        'action' => 'add_variations',
                        'prev_value' => $this->prev_value,
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

    private function set_prev_attributes()
    {
        $product_attributes = $this->product->get_attributes();
        if (!empty($product_attributes)) {
            foreach ($product_attributes as $key => $product_attribute) {
                if (!($product_attribute instanceof \WC_Product_Attribute)) {
                    continue;
                }

                $this->prev_value['attributes'][$key] = [
                    'name' => $product_attribute->get_name(),
                    'value' => $product_attribute->get_options(),
                    'used_for_variations' => $product_attribute->get_variation(),
                    'attribute_is_visible' => $product_attribute->get_visible(),
                ];
            }
        }
    }

    private function attributes_update($attributes)
    {
        $product_attributes = $this->product->get_attributes();
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if (!empty($product_attributes)) {
            foreach ($product_attributes as $name => $attribute) {
                if (!isset($attributes[$name])) {
                    unset($product_attributes[$name]);
                }
            }
        }

        foreach ($attributes as $name => $term_ids) {
            if (empty($product_attributes) || !isset($product_attributes[$name])) {
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if (!empty($attribute_taxonomies)) {
                    $attrs = array_column($attribute_taxonomies, 'attribute_id', 'attribute_name');
                    $attribute_name = str_replace('pa_', '', $name);
                    if (!empty($attrs[$attribute_name])) {
                        $new_object = new \WC_Product_Attribute();
                        $new_object->set_id($attrs[$attribute_name]);
                        $new_object->set_name($name);
                        $new_object->set_options(array_map('intval', $term_ids));
                        $new_object->set_position((!empty($product_attributes)) ? count($product_attributes) : 0);
                        $new_object->set_visible(true);
                        $new_object->set_variation(true);
                        $product_attributes[$name] = $new_object;
                    }
                }
            } else {
                $options = $product_attributes[$name]->get_options();
                $new_object = new \WC_Product_Attribute();
                $new_object->set_id($product_attributes[$name]->get_id());
                $new_object->set_name($product_attributes[$name]->get_name());
                $new_object->set_options(array_merge(array_map('intval', $term_ids), $options));
                $new_object->set_position(count($product_attributes));
                $new_object->set_visible(true);
                $new_object->set_variation(true);
                $product_attributes[$name] = $new_object;
            }
        }

        $this->product->set_attributes($product_attributes);
        return $this->product->save();
    }

    private function add_variation($attributes)
    {
        $variation_object = new \WC_Product_Variation();
        $variation_object->set_parent_id($this->product->get_id());
        $variation_object->set_attributes($attributes);
        $variation_object->set_menu_order(count($this->product->get_children()));

        return $variation_object->save();
    }

    private function attribute_revert($product, $attributes)
    {
        if (empty($attributes)) {
            return false;
        }

        $product_attributes = $product->get_attributes();

        if (!empty($product_attributes)) {
            foreach ($product_attributes as $name => $attribute) {
                if (!isset($attributes[$name])) {
                    unset($product_attributes[$name]);
                }
            }
        }

        foreach ($attributes as $attribute) {
            $attr = new \WC_Product_Attribute();
            if (!empty($product_attributes) && isset($product_attributes[strtolower(urlencode($attribute['name']))])) {
                $old_attr = $product_attributes[strtolower(urlencode($attribute['name']))];
                if ($old_attr->get_name() == $attribute['name']) {
                    if (!empty($attribute['value'])) {
                        $attr->set_id($old_attr->get_id());
                        $attr->set_name($old_attr->get_name());
                        $attr->set_options($attribute['value']);
                        $attr->set_position($old_attr->get_position());

                        if (isset($attribute['attribute_is_visible']) && $attribute['attribute_is_visible'] != '') {
                            $attr->set_visible($attribute['attribute_is_visible'] == 'yes');
                        } else {
                            $attr->set_visible($old_attr->get_visible());
                        }

                        if (isset($attribute['used_for_variations']) && $attribute['used_for_variations'] != '') {
                            $attr->set_variation($attribute['used_for_variations'] == 'yes');
                        } else {
                            $attr->set_variation($old_attr->get_variation());
                        }

                        $product_attributes[strtolower(urlencode($attribute['name']))] = $attr;
                    } else {
                        unset($product_attributes[strtolower(urlencode($attribute['name']))]);
                    }
                }
            } else {
                $attrs = array_column(wc_get_attribute_taxonomies(), 'attribute_id', 'attribute_name');
                $attribute_name = str_replace('pa_', '', $attribute['name']);
                if (!empty($attrs[$attribute_name])) {
                    $attr->set_id($attrs[$attribute_name]);
                    $attr->set_name($attribute['name']);
                    $attr->set_options($attribute['value']);
                    $attr->set_position(count($attribute));
                    $attr->set_visible((isset($attribute['attribute_is_visible']) && $attribute['attribute_is_visible'] == 'yes') ? 1 : 0);
                    $attr->set_variation(isset($attribute['used_for_variations']) && $attribute['used_for_variations'] == 'yes');
                    $product_attributes[$attribute['name']] = $attr;
                }
            }
        }
        $product->set_attributes($product_attributes);
        return $product->save();
    }

    private function save_history_item($data)
    {
        $history_repository = History::get_instance();
        return $history_repository->save_history_item([
            'history_id' => $data['history_id'],
            'historiable_id' => $data['product_id'],
            'name' => $data['name'],
            'sub_name' => (!empty($data['sub_name'])) ? $data['sub_name'] : '',
            'type' => $data['type'],
            'action' => $data['action'],
            'prev_value' => $data['prev_value'],
            'new_value' => $data['new_value'],
        ]);
    }
}
