<?php

namespace wcbel\classes\services\product_update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Sanitizer;
use wcbel\classes\helpers\Product_Helper;
use wcbel\classes\repositories\History;
use wcbel\classes\repositories\Product;
use wcbel\classes\services\product_update\Handler_Interface;

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
            'it_wc_dynamic_pricing_all_fields' => 'get_it_wc_dynamic_pricing_all_fields',
            'pricing_rules_product' => 'get_pricing_rules_product',
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
            'min_max_rules' => 'set_min_max_rules',
            'yith_cog_cost' => 'set_yith_cog_cost',
            '_wc_cog_cost' => 'set_wc_cog_cost',
            '_regular_price_wmcp' => 'set_regular_price_wmcp',
            '_sale_price_wmcp' => 'set_sale_price_wmcp',
            '_yith_wcbm_product_meta' => 'set_yith_wcbm_product_meta',
            'yikes_woo_products_tabs' => 'set_yikes_woo_products_tabs',
            'pricing_rules_product' => 'set_pricing_rules_product',
            'it_wc_dynamic_pricing_all_fields' => 'set_it_wc_dynamic_pricing_all_fields',
        ];
    }

    private function get_default_meta_field()
    {
        return (!empty($this->update_data['name'])) ? get_post_meta($this->product->get_id(), $this->update_data['name'], true) : '';
    }

    private function get_it_wc_dynamic_pricing_all_fields()
    {
        $pricing_rules_name = ($this->product->get_type() == 'variation') ? "pricing_rules_variation" : "pricing_rules_product";
        return [
            'it_product_disable_discount' => get_post_meta($this->product->get_id(), 'it_product_disable_discount', true),
            'it_product_hide_price_unregistered' => get_post_meta($this->product->get_id(), 'it_product_hide_price_unregistered', true),
            'it_pricing_product_price_user_role' => get_post_meta($this->product->get_id(), 'it_pricing_product_price_user_role', true),
            'it_pricing_product_add_to_cart_user_role' => get_post_meta($this->product->get_id(), 'it_pricing_product_add_to_cart_user_role', true),
            'it_pricing_product_hide_user_role' => get_post_meta($this->product->get_id(), 'it_pricing_product_hide_user_role', true),
            'pricing_rules_product' => get_post_meta($this->product->get_id(), $pricing_rules_name, true),
        ];
    }

    private function get_pricing_rules_product()
    {
        $pricing_rules_name = ($this->product->get_type() == 'variation') ? "pricing_rules_variation" : "pricing_rules_product";
        return get_post_meta($this->product->get_id(), $pricing_rules_name, true);
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

    private function set_min_max_rules()
    {
        return ($this->product->get_type() == 'variation') ? update_post_meta($this->product->get_id(), esc_sql($this->update_data['name']), esc_sql($this->update_data['value'])) : false;
    }

    private function set_yith_cog_cost()
    {
        $meta_key = ($this->product->get_type() == 'variable') ? 'yith_cog_cost_variable' : 'yith_cog_cost';
        $this->update_data['value'] = Product_Helper::apply_operator($this->current_field_value, $this->update_data);
        update_post_meta($this->product->get_id(), $meta_key, $this->update_data['value']);

        if ($this->product->get_type() == 'variation') {
            $parent = $this->product_repository->get_product($this->product->get_parent_id());
            if ($parent instanceof \WC_Product_Variable) {
                $variations_cost = [];
                $children = $parent->get_visible_children();
                if (!empty($children && is_array($children))) {
                    foreach ($children as $child) {
                        $cost = get_post_meta(intval($child), 'yith_cog_cost', true);
                        if (!empty($cost)) {
                            $variations_cost[] = intval($cost);
                        }
                    }
                }

                $children_min = min($variations_cost);
                $children_max = max($variations_cost);
                update_post_meta($parent->get_id(), 'yith_cog_cost', $children_min);
                update_post_meta($parent->get_id(), 'yith_cog_min_variation_cost', intval($children_min));
                update_post_meta($parent->get_id(), 'yith_cog_max_variation_cost', intval($children_max));
            }
        }

        return true;
    }

    private function set_wc_cog_cost()
    {
        $meta_key = ($this->product->get_type() == 'variable') ? '_wc_cog_cost_variable' : '_wc_cog_cost';
        $this->update_data['value'] = Product_Helper::apply_operator($this->current_field_value, $this->update_data);
        update_post_meta($this->product->get_id(), $meta_key, $this->update_data['value']);

        if ($this->product->get_type() == 'variation') {
            $parent = $this->product_repository->get_product($this->product->get_parent_id());
            if ($parent instanceof \WC_Product_Variable) {
                $variations_cost = [];
                $children = $parent->get_visible_children();
                if (!empty($children && is_array($children))) {
                    foreach ($children as $child) {
                        $cost = get_post_meta(intval($child), '_wc_cog_cost', true);
                        if (!empty($cost)) {
                            $variations_cost[] = intval($cost);
                        }
                    }
                }

                $children_min = min($variations_cost);
                $children_max = max($variations_cost);
                update_post_meta($parent->get_id(), '_wc_cog_cost', $children_min);
                update_post_meta($parent->get_id(), '_wc_cog_min_variation_cost', intval($children_min));
                update_post_meta($parent->get_id(), '_wc_cog_max_variation_cost', intval($children_max));
            }
        }

        return true;
    }

    private function set_regular_price_wmcp()
    {
        if (empty($this->update_data['sub_name'])) {
            return false;
        }

        $origin_value = json_decode($this->current_field_value, true);
        if (!empty($origin_value[$this->update_data['sub_name']])) {
            $this->update_data['value'] = Product_Helper::apply_operator($origin_value[$this->update_data['sub_name']], $this->update_data);
        }
        $origin_value[$this->update_data['sub_name']] = $this->update_data['value'];
        $this->update_data['value'] = $origin_value;
        return update_post_meta($this->product->get_id(), '_regular_price_wmcp', wp_json_encode($origin_value));
    }

    private function set_sale_price_wmcp()
    {
        if (empty($this->update_data['sub_name'])) {
            return false;
        }

        $origin_value = json_decode($this->current_field_value, true);
        if (!empty($origin_value[$this->update_data['sub_name']])) {
            $this->update_data['value'] = Product_Helper::apply_operator($origin_value[$this->update_data['sub_name']], $this->update_data);
        }
        $origin_value[$this->update_data['sub_name']] = $this->update_data['value'];
        $this->update_data['value'] = $origin_value;
        return update_post_meta($this->product->get_id(), '_sale_price_wmcp', wp_json_encode($origin_value));
    }

    private function set_yith_wcbm_product_meta()
    {
        if (empty($this->update_data['sub_name'])) {
            return false;
        }

        $origin_value = $this->current_field_value;
        if (!is_array($origin_value)) {
            $origin_value = [];
        }
        $origin_value[$this->update_data['sub_name']] = $this->update_data['value'];
        $this->update_data['value'] = $origin_value;
        return update_post_meta($this->product->get_id(), '_yith_wcbm_product_meta', $origin_value);
    }

    private function set_yikes_woo_products_tabs()
    {
        $tabs = [];
        $global_tabs = [];
        $global_applied = get_option('yikes_woo_reusable_products_tabs_applied');

        if (is_array($this->update_data['value'])) {
            foreach ($this->update_data['value'] as $tab) {
                if (isset($tab['title']) && isset($tab['content'])) {
                    $tabs[] = [
                        'id' => strtolower(str_replace(' ', '-', sanitize_text_field($tab['title']))),
                        'title' => sanitize_text_field($tab['title']),
                        'content' => (!empty($tab['content'])) ? sprintf('%s', $tab['content']) : '',
                    ];
                    if (!empty($tab['global_tab'])) {
                        $global_tabs[] = [
                            'global_id' => intval($tab['global_tab']),
                            'tab_id' => strtolower(str_replace(' ', '-', sanitize_text_field($tab['title'])))
                        ];
                    }
                }
            }
        }

        update_post_meta($this->product->get_id(), 'yikes_woo_products_tabs', $tabs);

        if (!empty($global_tabs)) {
            $global_applied[$this->product->get_id()] = [];
            foreach ($global_tabs as $global_tab) {
                $global_applied[$this->product->get_id()][] = [
                    'post_id' => $this->product->get_id(),
                    'reusable_tab_id' => intval($global_tab['global_id']),
                    'tab_id' => sanitize_text_field($global_tab['tab_id'])
                ];
            }
        }

        return update_option('yikes_woo_reusable_products_tabs_applied', $global_applied);
    }

    private function set_pricing_rules_product()
    {
        if (empty($this->update_data['value'])) {
            return false;
        }

        $prices['price_rule'] = [];

        $items = (!empty($this->update_data['value']['price_rule'])) ? $this->update_data['value']['price_rule'] : $this->update_data['value'];
        if ($this->product->get_type() == 'variation') {
            $pricing_rules_name = "pricing_rules_variation";
            $amount_key = "price";
        } else {
            $pricing_rules_name = "pricing_rules_product";
            $amount_key = "amount";
        }

        if (is_array($items)) {
            foreach ($items as $key => $value) {
                $field_name = (!empty($value['field'])) ? $value['field'] : $key;
                if (!empty($field_name)) {
                    if (!empty($value['amount'])) {
                        $price = sanitize_text_field($value['amount']);
                    } elseif (!empty($value['price'])) {
                        $price = sanitize_text_field($value['price']);
                    } else {
                        continue;
                    }

                    $prices['price_rule'][sanitize_text_field($field_name)][$amount_key] = sanitize_text_field($price);
                }
            }
        } else if (!empty($this->update_data['sub_name'])) {
            $prices = (!empty($this->current_field_value)) ? $this->current_field_value : ['price_rule' => []];
            $prices['price_rule'][sanitize_text_field($this->update_data['sub_name'])][$amount_key] = sanitize_text_field($this->update_data['value']);
        }

        $this->update_data['value'] = $prices;
        return update_post_meta($this->product->get_id(), $pricing_rules_name, $prices);
    }

    private function set_it_wc_dynamic_pricing_all_fields()
    {
        if (!is_array($this->update_data['value'])) {
            return false;
        }

        if ($this->product->get_type() == 'variation') {
            $pricing_rules_name = "pricing_rules_variation";
            $amount_key = "price";
        } else {
            $pricing_rules_name = "pricing_rules_product";
            $amount_key = "amount";
        }

        // update fields
        update_post_meta($this->product->get_id(), "it_product_disable_discount", (isset($this->update_data['value']['it_product_disable_discount'])) ? sanitize_text_field($this->update_data['value']['it_product_disable_discount']) : 'no');
        update_post_meta($this->product->get_id(), "it_product_hide_price_unregistered", (isset($this->update_data['value']['it_product_hide_price_unregistered'])) ? sanitize_text_field($this->update_data['value']['it_product_hide_price_unregistered']) : 'no');
        update_post_meta($this->product->get_id(), "it_pricing_product_price_user_role", (!empty($this->update_data['value']['it_pricing_product_price_user_role'])) ? Sanitizer::array($this->update_data['value']['it_pricing_product_price_user_role']) : []);
        update_post_meta($this->product->get_id(), "it_pricing_product_add_to_cart_user_role", (!empty($this->update_data['value']['it_pricing_product_add_to_cart_user_role'])) ? Sanitizer::array($this->update_data['value']['it_pricing_product_add_to_cart_user_role']) : []);
        update_post_meta($this->product->get_id(), "it_pricing_product_hide_user_role", (!empty($this->update_data['value']['it_pricing_product_hide_user_role'])) ? Sanitizer::array($this->update_data['value']['it_pricing_product_hide_user_role']) : []);

        $prices['price_rule'] = [];
        if (is_array($this->update_data['value']['pricing_rules_product']) && !empty($this->update_data['value']['pricing_rules_product'])) {
            foreach ($this->update_data['value']['pricing_rules_product'] as $key => $value) {
                if (!empty($value['amount'])) {
                    $price = sanitize_text_field($value['amount']);
                } elseif (!empty($value['price'])) {
                    $price = sanitize_text_field($value['price']);
                } else {
                    continue;
                }

                $role_name = (!empty($value['field'])) ? sanitize_text_field($value['field']) : sanitize_text_field($key);
                $prices['price_rule'][$role_name][$amount_key] = $price;
            }
        }

        update_post_meta($this->product->get_id(), $pricing_rules_name, $prices);

        return true;
    }
}
