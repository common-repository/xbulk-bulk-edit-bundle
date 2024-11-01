<?php

namespace iwbvel\classes\services\product_update\variation_handlers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\repositories\History;
use iwbvel\classes\services\product_update\Handler_Interface;

class Delete_Variation_Handler implements Handler_Interface
{
    private static $instance;

    private $product_ids;
    private $update_data;
    private $prev_value;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function update($product_ids, $update_data)
    {
        $this->product_ids = $product_ids;
        $this->update_data = $update_data;

        switch ($update_data['action']) {
            case 'delete_by_term':
                $this->delete_by_term();
                break;
            case 'delete_by_ids':
                $this->delete_by_ids();
                break;
            case 'delete_all':
                $this->delete_all();
                break;
        }
    }

    private function delete_by_term()
    {
        foreach ($this->product_ids as $product_id) {
            $product = wc_get_product(intval($product_id));
            if (!($product instanceof \WC_Product_Variable)) {
                continue;
            }

            if (isset($this->update_data['revert_mode']) && $this->update_data['revert_mode'] == true) {
                if (!empty($this->update_data['value'])) {
                    if (!empty($this->update_data['value']['attributes'])) {
                        $this->attribute_revert($product, $this->update_data['value']['attributes']);
                    }

                    if (!empty($this->update_data['value']['variations'])) {
                        foreach ($this->update_data['value']['variations'] as $variation_id) {
                            wp_untrash_post(intval($variation_id));
                        }
                    }
                }

                $product->save();
            } else {
                $product_variations = $product->get_children();
                if (empty($product_variations)) {
                    continue;
                }

                if (!empty($this->update_data['history_id'])) {
                    $this->prev_value['variations'] = $product_variations;
                }

                $this->attribute_update([
                    'product' => $product,
                    'attribute' => $this->update_data['value']['attribute'],
                    'term' => $this->update_data['value']['term']
                ]);

                $deleted_ids = [];
                foreach ($product_variations as $key => $variation_id) {
                    $variation = wc_get_product(intval($variation_id));
                    if ($variation instanceof \WC_Product_Variation) {
                        $variation_attributes = $variation->get_attributes();

                        if (
                            !empty($variation_attributes)
                            && is_array($variation_attributes)
                            && isset($variation_attributes[sanitize_text_field($this->update_data['value']['attribute'])])
                        ) {
                            if ($variation_attributes[sanitize_text_field($this->update_data['value']['attribute'])] == sanitize_text_field($this->update_data['value']['term'])) {
                                $deleted_ids[] = $variation_id;
                                continue;
                            }

                            if ($this->update_data['value']['term'] == 'all_terms') {
                                if (count($variation_attributes) === 1) {
                                    $deleted_ids[] = $variation_id;
                                }

                                if (count($variation_attributes) > 1) {
                                    unset($variation_attributes[sanitize_text_field($this->update_data['value']['attribute'])]);
                                    $variation->set_attributes($variation_attributes);
                                    $variation->save();
                                }
                            }
                        }
                    }
                }

                if (!empty($deleted_ids)) {
                    foreach ($deleted_ids as $variation_id) {
                        wp_trash_post(intval($variation_id));
                    }
                }

                if (!empty($this->update_data['history_id'])) {
                    $result = $this->save_history_item([
                        'history_id' => intval($this->update_data['history_id']),
                        'product_id' => intval($product->get_id()),
                        'name' => 'delete_variations',
                        'type' => 'variation',
                        'action' => 'delete_by_term',
                        'prev_value' =>  $this->prev_value,
                        'new_value' => $this->update_data['value'],
                    ]);

                    if (!$result) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function delete_by_ids()
    {
        foreach ($this->product_ids as $variable_id) {
            if (isset($this->update_data['revert_mode']) && $this->update_data['revert_mode'] == true) {
                if (!empty($this->update_data['value'])) {
                    foreach ($this->update_data['value'] as $variation_id) {
                        wp_untrash_post(intval($variation_id));
                    }
                }
            } else {
                if (!empty($this->update_data['value'])) {
                    if (!empty($this->update_data['history_id'])) {
                        $this->prev_value = $this->update_data['value'];
                    }

                    foreach ($this->update_data['value'] as $variation_id) {
                        wp_trash_post(intval($variation_id));
                    }

                    if (!empty($this->update_data['history_id'])) {
                        $result = $this->save_history_item([
                            'history_id' => intval($this->update_data['history_id']),
                            'product_id' => intval($variable_id),
                            'name' => 'delete_variations',
                            'type' => 'variation',
                            'action' => 'delete_by_ids',
                            'prev_value' => $this->prev_value,
                            'new_value' => $this->update_data['value'],
                        ]);

                        if (!$result) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    private function delete_all()
    {
        foreach ($this->product_ids as $variable_id) {
            if (isset($this->update_data['revert_mode']) && $this->update_data['revert_mode'] == true) {
                if (!empty($this->update_data['value']) && !empty($this->update_data['value']['variations'])) {
                    foreach ($this->update_data['value']['variations'] as $variation_id) {
                        wp_untrash_post(intval($variation_id));
                    }
                }
            } else {
                $variations = get_posts([
                    'post_parent' => intval($variable_id),
                    'post_type' => 'product_variation',
                    'numberposts' => -1,
                    'fields' => 'ids',
                    'post_status' => 'any'
                ]);

                if (!empty($variations)) {
                    if (!empty($this->update_data['history_id'])) {
                        $this->prev_value['variations'] = $variations;
                    }

                    foreach ($variations as $variation_id) {
                        wp_trash_post(intval($variation_id));
                    }

                    if (!empty($this->update_data['history_id'])) {
                        $result = $this->save_history_item([
                            'history_id' => intval($this->update_data['history_id']),
                            'product_id' => intval($variable_id),
                            'name' => 'delete_variations',
                            'type' => 'variation',
                            'action' => 'delete_all',
                            'prev_value' => $this->prev_value,
                            'new_value' => $this->update_data['value'],
                        ]);

                        if (!$result) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    private function attribute_update($data)
    {
        if (!isset($data['product']) || !($data['product'] instanceof \WC_Product_Variable) || !isset($data['attribute']) || !isset($data['term'])) {
            return false;
        }

        $product_attributes = $data['product']->get_attributes();
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
        if (isset($product_attributes[$data['attribute']])) {
            if ($data['term'] == 'all_terms') {
                unset($product_attributes[$data['attribute']]);
            } else {
                if (
                    !empty($product_attributes[$data['attribute']])
                    && $product_attributes[$data['attribute']] instanceof \WC_Product_Attribute
                ) {
                    $term = get_term_by('slug', sanitize_text_field($data['term']), sanitize_text_field($data['attribute']));
                    if ($term instanceof \WP_Term) {
                        $options = $product_attributes[$data['attribute']]->get_options();
                        $option_key = array_search($term->term_id, $options);
                        if ($option_key !== false) {
                            unset($options[$option_key]);
                            $product_attributes[$data['attribute']]->set_options($options);
                        }
                    }
                }
            }

            $data['product']->set_attributes($product_attributes);
            return $data['product']->save();
        }

        return true;
    }

    private function attribute_revert($product, $attributes)
    {
        if (empty($attributes)) {
            return false;
        }

        $product_attributes = $product->get_attributes();
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
