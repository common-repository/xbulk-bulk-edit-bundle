<?php

namespace wcbel\classes\services\product_update;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Product_Helper;
use wcbel\classes\repositories\History;
use wcbel\classes\repositories\Product;

class Variation_Update implements Update_Interface
{
    private static $instance;

    private $product_repository;
    private $product_children;
    private $product;
    private $product_ids;
    private $update_data;
    private $save_history;
    private $history_repository;
    private $prev_value;
    private $new_value;

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
        $this->history_repository = History::get_instance();
    }

    public function set_update_data($data)
    {
        if (!isset($data['product_ids']) || empty($data['product_data']) || !is_array($data['product_data'])) {
            return false;
        }

        $this->product_ids = array_unique($data['product_ids']);
        $this->update_data = $data['product_data'];
        $this->save_history = (!empty($data['save_history']));
    }

    public function perform()
    {
        if (empty($this->product_ids) || !is_array($this->product_ids) || empty($this->update_data) || !is_array($this->update_data)) {
            return false;
        }

        foreach ($this->update_data as $update_data) {
            if (!isset($update_data['value']['attributes']) || !isset($update_data['value']['variations'])) {
                return false;
            }

            // save history
            if ($this->save_history) {
                $history_id = $this->save_history();
                if (empty($history_id)) {
                    return false;
                }
            }

            foreach ($this->product_ids as $product_id) {
                $this->product = $this->product_repository->get_product(intval($product_id));
                if (!($this->product instanceof \WC_Product)) {
                    continue;
                }

                $this->product_children = $this->product->get_children();

                if ($this->save_history) {
                    $this->new_value = [];
                    $this->prev_value = [
                        'product_type' => $this->product->get_type(),
                        'default_variation' => $this->product->get_default_attributes(),
                        'attributes' => [],
                        'variations' => [],
                    ];

                    $this->set_prev_variations();
                }

                if (isset($update_data['value']['product_type']) && $update_data['value']['product_type'] != $this->product->get_type()) {
                    $this->new_value['product_type'] = sanitize_text_field($update_data['value']['product_type']);
                    $product_classname = \WC_Product_Factory::get_product_classname($this->product->get_id(), sanitize_text_field($update_data['value']['product_type']));
                    $this->product = new $product_classname($this->product->get_id());
                    $this->product->save();
                }

                if (isset($update_data['value']['attributes'])) {
                    $this->attribute_update($update_data['value']['attributes']);
                }

                if (isset($update_data['value']['variations'])) {
                    $this->variation_update($update_data['value']['variations']);
                }

                if (!empty($update_data['value']['default_variation'])) {
                    $this->default_variation_update($update_data['value']['default_variation']);
                }

                if (!empty($history_id)) {
                    $result = $this->save_history_item([
                        'history_id' => intval($history_id),
                        'product_id' => intval($this->product->get_id()),
                        'name' => 'bulk_variation_update',
                        'type' => 'variation',
                        'prev_value' => $this->prev_value,
                        'new_value' => $this->new_value,
                    ]);

                    if (!$result) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function attribute_update($new_attributes)
    {
        $product_attributes = $this->product->get_attributes();
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $this->new_value['attributes'] = $new_attributes;

        if (!empty($new_attributes) && is_array($new_attributes)) {
            $new_attributes_name = array_column($new_attributes, 'name');
        }

        if (!empty($product_attributes)) {
            foreach ($product_attributes as $key => $product_attribute) {
                if (!($product_attribute instanceof \WC_Product_Attribute)) {
                    continue;
                }

                $this->prev_value['attributes'][$key] = [
                    'name' => $product_attribute->get_name(),
                    'type' => 'taxonomy',
                    'value' => $product_attribute->get_options(),
                    'operator' => 'taxonomy_replace',
                    'used_for_variations' => $product_attribute->get_variation(),
                    'attribute_is_visible' => $product_attribute->get_visible(),
                ];

                if (!empty($new_attributes_name) && !in_array($key, $new_attributes_name)) {
                    unset($product_attributes[$key]);
                }
            }
        }

        if (!empty($new_attributes)) {
            foreach ($new_attributes as $new_attribute) {
                $attr = new \WC_Product_Attribute();
                $new_attribute['value'] = (!empty($new_attribute['value']) && is_array($new_attribute['value'])) ? array_map('intval', $new_attribute['value']) : [];
                if (is_array($attribute_taxonomies) && !empty($attribute_taxonomies)) {
                    if (!empty($product_attributes) && isset($product_attributes[strtolower(urlencode($new_attribute['name']))])) {
                        $old_attr = $product_attributes[strtolower(urlencode($new_attribute['name']))];
                        if ($old_attr->get_name() == $new_attribute['name']) {
                            $value = Product_Helper::apply_operator($old_attr->get_options(), $new_attribute);
                            if (!empty($value)) {
                                $attr->set_id($old_attr->get_id());
                                $attr->set_name($old_attr->get_name());
                                $attr->set_options($value);
                                $attr->set_position($old_attr->get_position());

                                if (isset($new_attribute['attribute_is_visible']) && $new_attribute['attribute_is_visible'] != '') {
                                    $attr->set_visible($new_attribute['attribute_is_visible'] == 'yes');
                                } else {
                                    $attr->set_visible($old_attr->get_visible());
                                }

                                if (isset($new_attribute['used_for_variations']) && $new_attribute['used_for_variations'] != '') {
                                    $attr->set_variation($new_attribute['used_for_variations'] == 'yes');
                                } else {
                                    $attr->set_variation($old_attr->get_variation());
                                }

                                $product_attributes[strtolower(urlencode($new_attribute['name']))] = $attr;
                            } else {
                                unset($product_attributes[strtolower(urlencode($new_attribute['name']))]);
                            }
                        }
                    } else {
                        $attrs = array_column($attribute_taxonomies, 'attribute_id', 'attribute_name');
                        $attribute_name = str_replace('pa_', '', $new_attribute['name']);
                        if (!empty($attrs[$attribute_name])) {
                            $attribute_id = $attrs[$attribute_name];
                            $attr->set_id($attribute_id);
                            $attr->set_name($new_attribute['name']);
                            $attr->set_options($new_attribute['value']);
                            $attr->set_position(count($product_attributes));
                            $attr->set_visible((isset($new_attribute['attribute_is_visible']) && $new_attribute['attribute_is_visible'] == 'yes') ? 1 : 0);
                            $attr->set_variation(isset($new_attribute['used_for_variations']) && $new_attribute['used_for_variations'] == 'yes');
                            $product_attributes[$new_attribute['name']] = $attr;
                        }
                    }
                }
            }
        } else {
            $product_attributes = [];
        }

        $this->product->set_attributes($product_attributes);
        return $this->product->save();
    }

    private function variation_update($new_variations)
    {
        $old_variations = [];
        if (!empty($this->product_children) && is_array($this->product_children)) {
            foreach ($this->product_children as $variation_id) {
                $variation = $this->product_repository->get_product(intval($variation_id));
                if (!($variation instanceof \WC_Product_Variation)) {
                    continue;
                }

                $attributes = $variation->get_attributes();
                if (!empty($attributes)) {
                    $name = '';
                    foreach ($attributes as $key => $value) {
                        if (!empty($name)) {
                            $name .= '&&';
                        }

                        if (empty($value)) {
                            if (!empty($this->prev_value['attributes']) && !empty($this->prev_value['attributes'][$key]) && $this->prev_value['attributes'][$key] instanceof \WC_Product_Attribute) {
                                $attribute_options = $this->prev_value['attributes'][$key]->get_options();
                                if (!empty($attribute_options[0])) {
                                    $term_object = get_term_by('term_id', intval($attribute_options[0]), $key);
                                    if ($term_object instanceof \WP_Term) {
                                        $value = $term_object->slug;
                                    }
                                }
                            }
                        }

                        if (!empty($value)) {
                            $name .= 'attribute_' . $key . ',' . $value;
                        }
                    }
                    $old_variations[$variation->get_id()] = $name;
                }
            }
        }

        $menu_order = 0;
        $this->new_value['variations'] = [];

        if (!empty($new_variations)) {
            foreach ($new_variations as $variation_item) {
                if (empty($variation_item['items']) || !is_array($variation_item['items'])) {
                    continue;
                }

                $variation_object = null;
                if (!empty($variation_item['variation_id'])) {
                    $variation_object = $this->product_repository->get_product(intval($variation_item['variation_id']));
                    if ($variation_object instanceof \WC_Product_Variation && $variation_object->get_status() == 'trash') {
                        wp_untrash_post(intval($variation_object->get_id()));
                        $variation_object = $this->product_repository->get_product(intval($variation_item['variation_id']));
                    }
                }

                if (empty($variation_object) || !($variation_object instanceof \WC_Product_Variation)) {
                    $items_string = str_replace('=', ',', http_build_query($variation_item['items'], '', '&&'));
                    if (!empty($variation_id = array_search($items_string, $old_variations))) {
                        $variation_object = $this->product_repository->get_product(intval($variation_id));
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

                $this->new_value['variations'][$variation_object->get_id()] = [
                    'variation_id' => $variation_object->get_id(),
                    'items' => $variation_item['items']
                ];

                $variation_object->set_attributes($variation_item['items']);
                $variation_object->set_menu_order($menu_order);
                $variation_object->save();

                $menu_order++;
            }
        } else {
            $this->new_value['variations'] = [];
        }

        if (!empty($old_variations)) {
            foreach (array_keys($old_variations) as $variation_id) {
                wp_trash_post(intval($variation_id));
            }
        }

        return true;
    }

    private function default_variation_update($default_variation)
    {
        $this->new_value['default_variation'] = $default_variation;
        $this->product->set_default_attributes($default_variation);
        return $this->product->save();
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
                    $this->prev_value['variations'][intval($variation_id)]['variation_id'] = intval($variation_id);
                    foreach ($attributes as $key => $value) {
                        if (!empty($value)) {
                            $this->prev_value['variations'][intval($variation_id)]['items']['attribute_' . $key] = $value;
                        }
                    }
                }
            }
        }
    }

    private function save_history()
    {
        $fields = array_column($this->update_data, 'name');
        $history_id = $this->history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize($fields),
            'operation_type' => History::BULK_OPERATION,
            'operation_date' => gmdate('Y-m-d H:i:s'),
        ]);

        return $history_id;
    }

    private function save_history_item($data)
    {
        return $this->history_repository->save_history_item([
            'history_id' => $data['history_id'],
            'historiable_id' => $data['product_id'],
            'name' => $data['name'],
            'sub_name' => (!empty($data['sub_name'])) ? $data['sub_name'] : '',
            'type' => $data['type'],
            'prev_value' => $data['prev_value'],
            'new_value' => $data['new_value'],
        ]);
    }
}
