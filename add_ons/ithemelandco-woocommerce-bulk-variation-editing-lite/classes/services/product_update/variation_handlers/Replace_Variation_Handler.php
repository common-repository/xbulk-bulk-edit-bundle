<?php

namespace iwbvel\classes\services\product_update\variation_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\repositories\History;
use iwbvel\classes\repositories\Product;
use iwbvel\classes\services\product_update\Handler_Interface;

class Replace_Variation_Handler implements Handler_Interface
{
    private static $instance;

    private $product;
    private $product_children;
    private $prev_value;
    private $combinations;
    private $attributes;
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

                    if (!empty($update_data['value']['variations'])) {
                        $variation_ids = $this->product->get_children();

                        foreach ($update_data['value']['variations'] as $variation_attributes) {
                            if (!isset($variation_attributes['variation_id']) || empty($variation_attributes['attributes'] || !is_array($variation_attributes['attributes']))) {
                                continue;
                            }

                            wp_untrash_post(intval($variation_attributes['variation_id']));
                            $variation = wc_get_product(intval($variation_attributes['variation_id']));
                            if (!($variation instanceof \WC_Product_Variation)) {
                                continue;
                            }

                            $key = array_search($variation_attributes['variation_id'], $variation_ids);
                            if ($key !== false) {
                                unset($variation_ids[$key]);
                            }

                            $variation->set_attributes($variation_attributes['attributes']);
                            $variation->save();
                        }

                        if (!empty($variation_ids)) {
                            foreach ($variation_ids as $delete_id) {
                                wp_delete_post(intval($delete_id), true);
                            }
                        }
                    }
                    return $this->product->save();
                }
            } else {
                if (empty($update_data['value']['combinations']) || empty($update_data['value']['attributes'])) {
                    return false;
                }

                $this->product_children = $this->product->get_children();

                if (!empty($update_data['history_id'])) {
                    $this->prev_value = [
                        'product_type' => $this->product->get_type(),
                        'attributes' => [],
                        'variations' => [],
                    ];

                    $this->set_prev_attributes();
                    $this->set_prev_variations();
                }

                if (isset($update_data['value']['product_type']) && $update_data['value']['product_type'] != $this->product->get_type()) {
                    $product_classname = \WC_Product_Factory::get_product_classname($this->product->get_id(), sanitize_text_field($update_data['value']['product_type']));
                    $this->product = new $product_classname($this->product->get_id());
                    $this->product->save();
                }
                $this->attributes = $update_data['value']['attributes'];
                $this->combinations = $update_data['value']['combinations'];

                $this->attributes_update();
                $this->variations_update();

                if (!empty($update_data['history_id'])) {
                    $result = $this->save_history_item([
                        'history_id' => intval($update_data['history_id']),
                        'product_id' => intval($this->product->get_id()),
                        'name' => 'bulk_variation_update',
                        'type' => 'variation',
                        'action' => 'replace_variations',
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

    private function attributes_update()
    {
        $product_attributes = $this->product->get_attributes();
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if (!empty($product_attributes)) {
            foreach ($product_attributes as $name => $attribute) {
                if (!isset($this->attributes[$name])) {
                    unset($product_attributes[$name]);
                }
            }
        }

        foreach ($this->attributes as $name => $terms) {
            if (empty($terms['ids'])) {
                if (isset($product_attributes[$name])) {
                    unset($product_attributes[$name]);
                }
                continue;
            }

            if (empty($product_attributes) || !isset($product_attributes[$name])) {
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if (!empty($attribute_taxonomies)) {
                    $attrs = array_column($attribute_taxonomies, 'attribute_id', 'attribute_name');
                    $attribute_name = str_replace('pa_', '', $name);
                    if (!empty($attrs[$attribute_name])) {
                        $new_object = new \WC_Product_Attribute();
                        $new_object->set_id($attrs[$attribute_name]);
                        $new_object->set_name($name);
                        $new_object->set_options(array_map('intval', $terms['ids']));
                        $new_object->set_position((!empty($product_attributes)) ? count($product_attributes) : 0);
                        $new_object->set_visible(true);
                        $new_object->set_variation(true);
                        $product_attributes[$name] = $new_object;
                    }
                }
            } else {
                $new_object = new \WC_Product_Attribute();
                $new_object->set_id($product_attributes[$name]->get_id());
                $new_object->set_name($product_attributes[$name]->get_name());
                $new_object->set_options(array_map('intval', $terms['ids']));
                $new_object->set_position(count($product_attributes));
                $new_object->set_visible(true);
                $new_object->set_variation(true);
                $product_attributes[$name] = $new_object;
            }

            $this->attributes[$name] = $terms['slugs'];
        }

        $this->product->set_attributes($product_attributes);
        return $this->product->save();
    }

    private function variations_update()
    {
        $product_attributes = $this->product->get_attributes();
        $old_variations = [];
        if (!empty($this->product_children) && is_array($this->product_children)) {
            foreach ($this->product_children as $variation_id) {
                $variation = $this->product_repository->get_product(intval($variation_id));
                if (!($variation instanceof \WC_Product_Variation)) {
                    continue;
                }

                $variation_attributes = $variation->get_attributes();
                if (!empty($variation_attributes)) {
                    foreach ($variation_attributes as $key => $value) {
                        if (empty($value)) {
                            if (!empty($product_attributes) && isset($product_attributes[$key])) {
                                $options = $product_attributes[$key]->get_options();
                                if (!empty($options[0])) {
                                    $term_object = get_term_by('term_id', intval($options[0]), $key);
                                    if ($term_object instanceof \WP_Term) {
                                        $variation_attributes[$key] = $term_object->slug;
                                    }
                                }
                            }
                        }
                    }
                    $old_variations[$variation->get_id()] = $variation_attributes;
                }
            }
        }

        $menu_order = 0;
        foreach ($this->combinations as $combination) {
            if (empty($combination) || !is_array($combination)) {
                continue;
            }

            $variation_object = null;

            if (!empty($old_variations)) {
                foreach ($old_variations as $variation_id => $items) {
                    if ($items == $combination) {
                        $variation_object = $this->product_repository->get_product(intval($variation_id));
                        if ($variation_object->get_status() == 'trash') {
                            $variation_object = null;
                        }
                    }
                }
            }

            if (empty($variation_object) || !($variation_object instanceof \WC_Product_Variation)) {
                $variation_object = new \WC_Product_Variation();
                $variation_object->set_parent_id($this->product->get_id());
                $variation_object->save();
            }

            if (isset($old_variations[$variation_object->get_id()])) {
                unset($old_variations[$variation_object->get_id()]);
            }

            $variation_object->set_attributes($combination);
            $variation_object->set_menu_order($menu_order);
            $variation_object->save();

            $menu_order++;
        }

        if (!empty($old_variations)) {
            foreach (array_keys($old_variations) as $variation_id) {
                wp_trash_post(intval($variation_id));
            }
        }

        return true;
    }

    private function set_prev_attributes()
    {
        $product_attributes = $this->product->get_attributes();
        if (!empty($product_attributes)) {
            foreach ($product_attributes as $key => $attribute) {
                if (!($attribute instanceof \WC_Product_Attribute)) {
                    continue;
                }

                $this->prev_value['attributes'][$key] = [
                    'name' => $attribute->get_name(),
                    'value' => $attribute->get_options(),
                    'used_for_variations' => $attribute->get_variation(),
                    'attribute_is_visible' => $attribute->get_visible(),
                ];
            }
        }
    }

    private function set_prev_variations()
    {
        if (!empty($this->product_children) && is_array($this->product_children)) {
            foreach ($this->product_children as $variation_id) {
                $variation = $this->product_repository->get_product(intval($variation_id));
                if (!($variation instanceof \WC_Product_Variation)) {
                    continue;
                }

                $attributes = $variation->get_attributes();
                if (!empty($attributes)) {
                    $this->prev_value['variations'][intval($variation_id)] = [
                        'variation_id' => intval($variation_id),
                        'attributes' => $attributes,
                    ];
                }
            }
        }
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
