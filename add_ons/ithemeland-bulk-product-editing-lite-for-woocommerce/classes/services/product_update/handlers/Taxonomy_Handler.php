<?php

namespace wcbel\classes\services\product_update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Product_Helper;
use wcbel\classes\repositories\History;
use wcbel\classes\repositories\Product;
use wcbel\classes\services\product_update\Handler_Interface;

class Taxonomy_Handler implements Handler_Interface
{
    private static $instance;

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
        $this->product_repository = Product::get_instance();

        foreach ($product_ids as $product_id) {
            if (!isset($this->update_data['value'])) {
                $this->update_data['value'] = '';
            }

            $this->product = $this->product_repository->get_product(intval($product_id));
            if (!($this->product instanceof \WC_Product)) {
                return false;
            }

            $this->current_field_value = (!empty($this->update_data['name'])) ? wp_get_post_terms($this->product->get_id(), $this->update_data['name'], ['fields' => 'ids']) : '';

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
                    'extra_fields' => [
                        'used_for_variations' => [
                            'prev' => (isset($this->update_data['used_for_variations_prev'])) ? $this->update_data['used_for_variations_prev'] : '',
                            'new' => (isset($this->update_data['used_for_variations'])) ? $this->update_data['used_for_variations'] : '',
                        ],
                        'attribute_is_visible' => [
                            'prev' => (isset($this->update_data['attribute_is_visible_prev'])) ? $this->update_data['attribute_is_visible_prev'] : '',
                            'new' => (isset($this->update_data['attribute_is_visible'])) ? $this->update_data['attribute_is_visible'] : '',
                        ],
                    ],
                ]);
                if (!$history_item_result) {
                    return false;
                }
            }
        }

        return true;
    }

    private function get_setter($field_name)
    {
        $setter_methods = $this->get_setter_methods();
        return (!empty($setter_methods[$field_name])) ? $setter_methods[$field_name] : $setter_methods['default_taxonomy'];
    }

    private function get_setter_methods()
    {
        return [
            'default_taxonomy' => 'set_default_taxonomy',
        ];
    }

    private function set_default_taxonomy()
    {
        return (substr($this->update_data['name'], 0, 3) == 'pa_') ? $this->product_attribute_update() : $this->taxonomy_update();
    }

    private function taxonomy_update()
    {
        if (!empty($this->update_data['operator'])) {
            $this->update_data['value'] = Product_Helper::apply_operator($this->current_field_value, $this->update_data);
        }
        return wp_set_post_terms($this->product->get_id(), $this->update_data['value'], $this->update_data['name'], false);
    }

    private function product_attribute_update()
    {
        $attr = new \WC_Product_Attribute();
        $attributes_result = $this->product->get_attributes();
        $product_attributes = (!empty($attributes_result) ? $attributes_result : []);
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $this->update_data['value'] = (!empty($this->update_data['value']) && is_array($this->update_data['value'])) ? array_map('intval', $this->update_data['value']) : [];
        if (is_array($attribute_taxonomies) && !empty($attribute_taxonomies)) {
            if (!empty($product_attributes) && isset($product_attributes[strtolower(urlencode($this->update_data['name']))])) {
                $old_attr = $product_attributes[strtolower(urlencode($this->update_data['name']))];
                if ($old_attr->get_name() == $this->update_data['name']) {
                    $value = Product_Helper::apply_operator($old_attr->get_options(), $this->update_data);
                    if (!empty($value)) {
                        // for history
                        $this->update_data['used_for_variations_prev'] = ($old_attr->get_variation()) ? 'yes' : 'no';
                        $this->update_data['attribute_is_visible_prev'] = ($old_attr->get_visible()) ? 'yes' : 'no';

                        $attr->set_id($old_attr->get_id());
                        $attr->set_name($old_attr->get_name());
                        $attr->set_options($value);
                        $attr->set_position($old_attr->get_position());

                        if (isset($this->update_data['attribute_is_visible']) && $this->update_data['attribute_is_visible'] != '') {
                            $attr->set_visible($this->update_data['attribute_is_visible'] == 'yes');
                        } else {
                            $attr->set_visible($old_attr->get_visible());
                        }

                        if (isset($this->update_data['used_for_variations']) && $this->update_data['used_for_variations'] != '') {
                            $attr->set_variation($this->update_data['used_for_variations'] == 'yes');
                        } else {
                            $attr->set_variation($old_attr->get_variation());
                        }

                        $product_attributes[strtolower(urlencode($this->update_data['name']))] = $attr;
                    } else {
                        unset($product_attributes[strtolower(urlencode($this->update_data['name']))]);
                    }
                }
            } else {
                $attrs = array_column($attribute_taxonomies, 'attribute_id', 'attribute_name');
                $attribute_name = str_replace('pa_', '', $this->update_data['name']);
                if (!empty($attrs[$attribute_name])) {
                    $attribute_id = $attrs[$attribute_name];
                    $attr->set_id($attribute_id);
                    $attr->set_name($this->update_data['name']);
                    $attr->set_options($this->update_data['value']);
                    $attr->set_position(count($product_attributes));
                    $attr->set_visible((isset($this->update_data['attribute_is_visible']) && $this->update_data['attribute_is_visible'] == 'yes') ? 1 : 0);
                    $attr->set_variation(isset($this->update_data['used_for_variations']) && $this->update_data['used_for_variations'] == 'yes');
                    $product_attributes[] = $attr;
                }
            }
        }

        $this->product->set_attributes($product_attributes);
        return $this->product->save();
    }
}
