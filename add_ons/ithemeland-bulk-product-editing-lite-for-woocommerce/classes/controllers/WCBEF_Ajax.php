<?php

namespace wcbef\classes\controllers;

use wcbef\classes\helpers\Others;
use wcbef\classes\helpers\Render;
use wcbef\classes\helpers\Sanitizer;
use wcbef\classes\helpers\Filter_Helper;
use wcbef\classes\helpers\Meta_Fields;
use wcbef\classes\helpers\Taxonomy;
use wcbef\classes\repositories\Column;
use wcbef\classes\repositories\History;
use wcbef\classes\repositories\Meta_Field;
use wcbef\classes\repositories\Product;
use wcbef\classes\repositories\Search;
use wcbef\classes\repositories\Setting;
use wcbef\classes\services\filter\Product_Filter_Service;
use wcbef\classes\services\product_update\Product_Update_Service;

class WCBEF_Ajax
{
    private static $instance;
    private $product_repository;
    private $history_repository;

    public static function register_callback()
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
        add_action('wp_ajax_wcbef_add_meta_keys_by_product_id', [$this, 'add_meta_keys_by_product_id']);
        add_action('wp_ajax_wcbef_column_manager_add_field', [$this, 'column_manager_add_field']);
        add_action('wp_ajax_wcbef_column_manager_get_fields_for_edit', [$this, 'column_manager_get_fields_for_edit']);
        add_action('wp_ajax_wcbef_products_filter', [$this, 'products_filter']);
        add_action('wp_ajax_wcbef_save_filter_preset', [$this, 'save_filter_preset']);
        add_action('wp_ajax_wcbef_product_edit', [$this, 'product_edit']);
        add_action('wp_ajax_wcbef_get_products_name', [$this, 'get_products_name']);
        add_action('wp_ajax_wcbef_create_new_product', [$this, 'create_new_product']);
        add_action('wp_ajax_wcbef_get_attribute_values', [$this, 'get_attribute_values']);
        add_action('wp_ajax_wcbef_get_attribute_values_for_delete', [$this, 'get_attribute_values_for_delete']);
        add_action('wp_ajax_wcbef_get_attribute_values_for_attach', [$this, 'get_attribute_values_for_attach']);
        add_action('wp_ajax_wcbef_get_product_variations', [$this, 'get_product_variations']);
        add_action('wp_ajax_wcbef_get_product_variations_for_attach', [$this, 'get_product_variations_for_attach']);
        add_action('wp_ajax_wcbef_set_products_variations', [$this, 'set_products_variations']);
        add_action('wp_ajax_wcbef_delete_products_variations', [$this, 'delete_products_variations']);
        add_action('wp_ajax_wcbef_delete_products', [$this, 'delete_products']);
        add_action('wp_ajax_wcbef_untrash_products', [$this, 'untrash_products']);
        add_action('wp_ajax_wcbef_empty_trash', [$this, 'empty_trash']);
        add_action('wp_ajax_wcbef_duplicate_product', [$this, 'duplicate_product']);
        add_action('wp_ajax_wcbef_add_product_taxonomy', [$this, 'add_product_taxonomy']);
        add_action('wp_ajax_wcbef_add_product_attribute', [$this, 'add_product_attribute']);
        add_action('wp_ajax_wcbef_load_filter_profile', [$this, 'load_filter_profile']);
        add_action('wp_ajax_wcbef_delete_filter_profile', [$this, 'delete_filter_profile']);
        add_action('wp_ajax_wcbef_save_column_profile', [$this, 'save_column_profile']);
        add_action('wp_ajax_wcbef_get_text_editor_content', [$this, 'get_text_editor_content']);
        add_action('wp_ajax_wcbef_change_count_per_page', [$this, 'change_count_per_page']);
        add_action('wp_ajax_wcbef_filter_profile_change_use_always', [$this, 'filter_profile_change_use_always']);
        add_action('wp_ajax_wcbef_get_default_filter_profile_products', [$this, 'get_default_filter_profile_products']);
        add_action('wp_ajax_wcbef_get_taxonomy_parent_select_box', [$this, 'get_taxonomy_parent_select_box']);
        add_action('wp_ajax_wcbef_get_product_data', [$this, 'get_product_data']);
        add_action('wp_ajax_wcbef_get_product_by_ids', [$this, 'get_product_by_ids']);
        add_action('wp_ajax_wcbef_get_product_files', [$this, 'get_product_files']);
        add_action('wp_ajax_wcbef_add_new_file_item', [$this, 'add_new_file_item']);
        add_action('wp_ajax_wcbef_variation_attaching', [$this, 'variation_attaching']);
        add_action('wp_ajax_wcbef_sort_by_column', [$this, 'sort_by_column']);
        add_action('wp_ajax_wcbef_clear_filter_data', [$this, 'clear_filter_data']);
        add_action('wp_ajax_wcbef_get_product_gallery_images', [$this, 'get_product_gallery_images']);
    }

    public function get_default_filter_profile_products()
    {
        $filter_data = Filter_Helper::get_active_filter_data();
        $result = $this->product_repository->get_products_list($filter_data, 1);
        $this->make_response([
            'success' => true,
            'filter_data' => $filter_data,
            'products_list' => $result->products_list,
            'pagination' => $result->pagination,
            'products_count' => $result->count,
        ]);
    }

    public function products_filter()
    {
        if (isset($_POST['filter_data'])) {
            $current_page = !empty($_POST['current_page']) ? intval($_POST['current_page']) : 1;
            $filter_result = $this->product_repository->get_products_list(Sanitizer::array($_POST['filter_data']), $current_page);
            $this->make_response([
                'success' => true,
                'products_list' => $filter_result->products_list,
                'pagination' => $filter_result->pagination,
                'products_count' => $filter_result->count,
            ]);
        }
        return false;
    }

    public function add_meta_keys_by_product_id()
    {
        if (isset($_POST)) {
            $product_id = intval($_POST['product_id']);
            $product = wc_get_product($product_id);
            if (!($product instanceof \WC_Product)) {
                die();
            }
            $meta_keys = Meta_Fields::remove_default_meta_keys(array_keys(get_post_meta($product_id)));
            $output = "";
            if (!empty($meta_keys)) {
                foreach ($meta_keys as $meta_key) {
                    $meta_field['key'] = $meta_key;
                    $meta_fields_main_types = Meta_Field::get_main_types();
                    $meta_fields_sub_types = Meta_Field::get_sub_types();
                    $output .= Render::html(WCBEF_VIEWS_DIR . "meta_field/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
                }
            }

            $this->make_response($output);
        }
        return false;
    }

    public function column_manager_add_field()
    {
        if (isset($_POST)) {
            if (isset($_POST['field_name']) && is_array($_POST['field_name']) && !empty($_POST['field_name'])) {
                $output = '';
                $field_action = $_POST['field_action'];
                for ($i = 0; $i < count($_POST['field_name']); $i++) {
                    $field_name = sanitize_text_field($_POST['field_name'][$i]);
                    $field_label = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                    $field_title = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                    $output .= Render::html(WCBEF_VIEWS_DIR . "column_manager/field_item.php", compact('field_name', 'field_label', 'field_action', 'field_title'));
                }
                $this->make_response($output);
            }
        }

        return false;
    }

    public function column_manager_get_fields_for_edit()
    {
        if (isset($_POST['preset_key'])) {
            $preset = (new Column())->get_preset(sanitize_text_field($_POST['preset_key']));
            if ($preset) {
                $output = '';
                $fields = [];
                if (isset($preset['fields'])) {
                    foreach ($preset['fields'] as $field) {
                        $field_info = [
                            'field_name' => $field['name'],
                            'field_label' => $field['label'],
                            'field_title' => $field['title'],
                            'field_background_color' => $field['background_color'],
                            'field_text_color' => $field['text_color'],
                            'field_action' => "edit",
                        ];
                        $fields[] = sanitize_text_field($field['name']);
                        $output .= Render::html(WCBEF_VIEWS_DIR . 'column_manager/field_item.php', $field_info);
                    }
                }

                $this->make_response([
                    'html' => $output,
                    'fields' => implode(',', $fields),
                ]);
            }
        }

        return false;
    }

    public function save_filter_preset()
    {
        if (!empty($_POST['preset_name'])) {
            $data = $_POST['filter_data'];
            $filter_item['name'] = sanitize_text_field($_POST['preset_name']);
            $filter_item['date_modified'] = date('Y-m-d H:i:s');
            $filter_item['key'] = 'preset-' . rand(1000000, 9999999);
            $filter_item['filter_data'] = $data;
            $save_result = (new Search())->update($filter_item);
            if (!$save_result) {
                return false;
            }
            $new_item = Render::html(WCBEF_VIEWS_DIR . 'modals/filter_profile_item.php', compact('filter_item'));
            $this->make_response([
                'success' => $save_result,
                'new_item' => $new_item,
            ]);
        }
        return false;
    }

    public function product_edit()
    {
        if (empty($_POST['product_data']) || !is_array($_POST['product_data'])) {
            return false;
        }

        if (!empty($_POST['product_ids'])) {
            $product_ids = array_map('intval', $_POST['product_ids']);
        } elseif (!empty($_POST['filter_data'])) {
            $product_filter_service = Product_Filter_Service::get_instance();
            $filtered_products = $product_filter_service->get_filtered_products(Sanitizer::array($_POST['filter_data']), [
                'posts_per_page' => -1,
                'fields' => 'ids',
            ]);

            $product_ids = [];
            if (!empty($filtered_products['product_ids'])) {
                $product_ids[] = $filtered_products['product_ids'];
            }
            if (!empty($filtered_products['variation_ids'])) {
                $product_ids[] = $filtered_products['variation_ids'];
            }

            $product_ids = Others::array_flatten($product_ids);
        } else {
            return false;
        }

        $update_service = Product_Update_Service::get_instance();
        $update_service->set_update_data([
            'product_ids' => $product_ids,
            'product_data' => Sanitizer::array($_POST['product_data']),
            'save_history' => true,
        ]);
        $update_result = $update_service->perform();

        $result = $this->product_repository->get_products_rows($product_ids);
        $histories = $this->history_repository->get_histories();
        $history_count = $this->history_repository->get_history_count();

        $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));

        $this->make_response([
            'success' => $update_result,
            'products' => $result->product_rows,
            'product_statuses' => $result->product_statuses,
            'history_items' => $histories_rendered,
        ]);
    }

    public function get_products_name()
    {
        $list = [];
        if (!empty($_POST['search'])) {
            $args['wcbef_general_column_filter'] = [
                [
                    'field' => 'post_title',
                    'value' => sanitize_text_field($_POST['search']),
                    'operator' => 'like',
                ],
            ];

            $products = $this->product_repository->get_products($args);
            if (!empty($products->posts)) {
                foreach ($products->posts as $post) {
                    $product = $this->product_repository->get_product($post->ID);
                    if ($product instanceof \WC_Product) {
                        $list['results'][] = [
                            'id' => $product->get_id(),
                            'text' => $product->get_title(),
                        ];
                    }
                }
            }
        }

        $this->make_response($list);
    }

    public function create_new_product()
    {
        if (isset($_POST) && !empty($_POST['count'])) {
            $products = [];
            for ($i = 1; $i <= intval($_POST['count']); $i++) {
                $products[] = $this->product_repository->create();
            }
            $this->make_response([
                'success' => true,
                'product_ids' => $products,
            ]);
        }
    }

    public function get_attribute_values()
    {
        if (isset($_POST['attribute_name'])) {
            $output = '';
            $attribute_name = sanitize_text_field($_POST['attribute_name']);
            $values = get_terms([
                'taxonomy' => "pa_{$attribute_name}",
                'hide_empty' => false,
            ]);

            if (!empty($values) && count($values) > 0) {
                $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item.php', compact('values', 'attribute_name'));
            }

            $this->make_response([
                'success' => true,
                'attribute_item' => $output,
            ]);
        }
        return false;
    }

    public function get_attribute_values_for_delete()
    {
        if (isset($_POST['attribute_name'])) {
            $output = '';
            $attribute_name = sanitize_text_field($_POST['attribute_name']);
            $values = get_terms([
                'taxonomy' => "pa_{$attribute_name}",
                'hide_empty' => false,
            ]);

            if (!empty($values) && count($values) > 0) {
                $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item_for_delete.php', compact('values', 'attribute_name'));
            }

            $this->make_response([
                'success' => true,
                'attribute_item' => $output,
            ]);
        }
        return false;
    }

    public function get_attribute_values_for_attach()
    {
        if (isset($_POST['attribute_name'])) {
            $output = '';
            $attribute_name = sanitize_text_field($_POST['attribute_name']);
            $values = get_terms([
                'taxonomy' => "pa_{$attribute_name}",
                'hide_empty' => false,
            ]);

            if (!empty($values) && count($values) > 0) {
                $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item_for_attach.php', compact('values', 'attribute_name'));
            }

            $this->make_response([
                'success' => true,
                'attribute_items' => $output,
            ]);
        }
        return false;
    }

    public function get_product_variations()
    {
        if (isset($_POST['product_id'])) {
            $variations_output = '';
            $attributes_output = '';
            $individual_output = '';
            $variations_single_delete_output = '';
            $product = $this->product_repository->get_product(intval($_POST['product_id']));
            if (!($product instanceof \WC_Product) || $product->get_type() != 'variable') {
                return false;
            }

            $product_attributes = $product->get_attributes();
            if (!empty($product_attributes)) {
                foreach ($product_attributes as $key => $product_attribute) {
                    if (!($product_attribute instanceof \WC_Product_Attribute)) {
                        continue;
                    }

                    if ($product_attribute->get_variation() !== true) {
                        continue;
                    }

                    $selected_values = [];
                    $selected_items[] = urldecode(mb_substr($key, 3));
                    $attribute_selected_items = get_the_terms($product->get_id(), urldecode($key));
                    $attribute_name = mb_substr(urldecode($key), 3);
                    if (is_array($attribute_selected_items)) {
                        $individual_output .= "<div data-id='wcbef-variation-bulk-edit-attribute-item-{$attribute_name}'><select class='wcbef-variation-bulk-edit-manual-item' data-attribute-name='{$attribute_name}'>";
                        foreach ($attribute_selected_items as $attribute_selected_item) {
                            $selected_values[] = urldecode($attribute_selected_item->slug);
                            $individual_output .= "<option value='" . urldecode($attribute_selected_item->slug) . "'>{$attribute_selected_item->name}</option>";
                        }
                        $individual_output .= '</select></div>';
                    }
                    $values = get_terms(['taxonomy' => urldecode($key), 'hide_empty' => false]);
                    $attributes_output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item.php', compact('selected_values', 'attribute_name', 'values'));
                }
            }
            $product_children = $product->get_children();
            if ($product_children > 0) {
                $default_variation = implode(' | ', array_map('urldecode', $product->get_default_attributes()));
                $i = 1;
                foreach ($product_children as $child) {
                    $variation = $this->product_repository->get_product(intval($child));
                    $variation_id = $variation->get_id();
                    $attributes = $variation->get_attributes();
                    $val = [];
                    $variation_attributes_labels = [];
                    if (!empty($attributes)) {
                        foreach ($attributes as $key => $attribute) {
                            $val[] = str_replace('pa_', '', $key) . ',' . $attribute;
                            $variation_attributes_labels[] = (!empty($attribute)) ? urldecode($attribute) : 'Any ' . urldecode($key);
                        }
                    }
                    $variation_attributes = (!empty($variation_attributes_labels)) ? implode(' | ', $variation_attributes_labels) : '';
                    $attribute_value = implode('&&', $val);
                    $variations_output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/variation_item.php', compact('variation_attributes', 'default_variation', 'attribute_value', 'variation_id'));
                    $variations_single_delete_output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/variation_item_single_delete.php', compact('variation_attributes', 'variation_id'));
                    $i++;
                }
            }

            $this->make_response([
                'success' => true,
                'variations' => $variations_output,
                'attributes' => $attributes_output,
                'individual' => $individual_output,
                'selected_items' => $selected_items,
                'variations_single_delete' => $variations_single_delete_output,
            ]);
        }
        return false;
    }

    public function set_products_variations()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                $product = $this->product_repository->get_product(intval($product_id));
                if (!($product instanceof \WC_Product_Variable) || $product->get_type() != 'variable') {
                    $product = new \WC_Product_Variable($product->get_id());
                    $product->save();
                }

                $new_attributes = [];
                if (!empty($_POST['variations']) && is_array($_POST['variations'])) {
                    if (!empty($_POST['attributes'])) {
                        foreach ($_POST['attributes'] as $attribute_item) {
                            if (!isset($attribute_item[0]) && !isset($attribute_item[1])) {
                                continue;
                            }

                            if (!empty($attribute_item[1]) && is_array($attribute_item[1])) {
                                $new_attributes['pa_' . $attribute_item[0]] = $attribute_item[1][0];
                            }

                            $params = [
                                'field' => 'pa_' . $attribute_item[0],
                                'value' => (is_array($attribute_item[1])) ? array_map('intval', $attribute_item[1]) : [],
                                'operator' => 'taxonomy_append',
                                'used_for_variations' => 'yes',
                                'attribute_is_visible' => 'yes'
                            ];

                            $this->product_repository->product_attribute_update($product->get_id(), $params);
                        }
                    }

                    $var = [];
                    $menu_order = 0;

                    $old_variations = [];
                    $product_variations = $product->get_children();
                    if (!empty($product_variations) && is_array($product_variations)) {
                        foreach ($product_variations as $variation_id) {
                            $variation = wc_get_product(intval($variation_id));
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
                                        if (!empty($new_attributes[$key]) && !empty(intval($new_attributes[$key]))) {
                                            $term_object = get_term_by('term_id', intval($new_attributes[$key]), $key);
                                            if ($term_object instanceof \WP_Term) {
                                                $value = $term_object->slug;
                                            }
                                        }
                                    }

                                    $name .= str_replace('pa_', '', $key) . ',' . $value;
                                }

                                $old_variations[$variation->get_id()] = $name;
                            }
                        }
                    }

                    foreach ($_POST['variations'] as $variations_item) {
                        if (isset($variations_item[0]) && !empty($variations_item[0])) {
                            $variation_object = null;

                            if (!empty($variation_id = array_search($variations_item[0], $old_variations))) {
                                $variation_object = wc_get_product(intval($variation_id));
                            }

                            if (empty($variation_object) && !empty($variations_item[1])) {
                                $variation_object = wc_get_product(intval($variations_item[1]));
                            }

                            $variations = explode('&&', $variations_item[0]);
                            if (!is_array($variations) && empty($variations)) {
                                continue;
                            }

                            foreach ($variations as $variation_item) {
                                $variation = explode(',', $variation_item);
                                if (isset($variation[0]) && isset($variation[1])) {
                                    $key = strtolower(urlencode($variation[0]));
                                    $var["attribute_pa_{$key}"] = strtolower(urlencode($variation[1]));
                                }
                            }

                            if (empty($variation_object) || !($variation_object instanceof \WC_Product_Variation)) {
                                $variation_object = new \WC_Product_Variation();
                                $variation_object->set_parent_id($product->get_id());
                            }

                            $variation_object->set_attributes($var);
                            $variation_object->set_menu_order($menu_order);
                            $variation_object->save();
                        }

                        $menu_order++;
                    }

                    $default_var = [];
                    $default_variations = (isset($_POST['default_variation'])) ? $_POST['default_variation'] : null;
                    if (!empty($default_variations)) {
                        $default_variation_items = explode('&&', $default_variations);
                        if (!is_array($default_variation_items) && empty($default_variation_items)) {
                            return false;
                        }
                        foreach ($default_variation_items as $default_variation_item) {
                            $default_variation = explode(',', $default_variation_item);
                            if (isset($default_variation[0]) && isset($default_variation[1])) {
                                $key = strtolower(urlencode($default_variation[0]));
                                $default_var["pa_{$key}"] = strtolower(urlencode($default_variation[1]));
                            }
                        }

                        $product->set_default_attributes($default_var);
                        $product->save();
                    }
                }
            }

            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function delete_products_variations()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids']) && !empty($_POST['variations']) && !empty($_POST['delete_type'])) {
            $variations = [];

            if (isset($_POST['variations']) && is_array($_POST['variations'])) {
                $variations = array_map('intval', $_POST['variations']);
            }

            foreach ($_POST['product_ids'] as $product_id) {
                $product = $this->product_repository->get_product(intval($product_id));
                if (!($product instanceof \WC_Product_Variable) || $product->get_type() != 'variable') {
                    return false;
                }
                $product_variations = $product->get_children();
                if (count($product_variations) > 0) {
                    foreach ($product_variations as $variation_id) {
                        $variation = $this->product_repository->get_product(intval($variation_id));
                        if (!($variation instanceof \WC_Product_Variation)) {
                            return false;
                        }
                        switch ($_POST['delete_type']) {
                            case 'all_variations':
                                wp_delete_post(intval($variation->get_id()), true);
                                break;
                            case 'single_product':
                                if (is_array($variations) && in_array($variation_id, $variations)) {
                                    wp_delete_post(intval($variation->get_id()), true);
                                }
                                break;
                            case 'multiple_product':
                                $delete_variation = Others::array_flatten($variations);
                                $product_variation = $variation->get_variation_attributes();
                                if (Others::array_equal($delete_variation, $product_variation)) {
                                    wp_delete_post(intval($variation->get_id()), true);
                                }
                                break;
                        }
                    }
                }
            }
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function delete_products()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids']) && !empty($_POST['delete_type'])) {
            $products_ids = array_map('intval', $_POST['product_ids']);
            $trashed = [];
            switch ($_POST['delete_type']) {
                case 'trash':
                    foreach ($products_ids as $product_id) {
                        $trashed[] = intval($product_id);
                        wp_trash_post(intval($product_id));
                    }
                    break;
                case 'permanently':
                    foreach ($products_ids as $product_id) {
                        wp_delete_post(intval($product_id), true);
                    }
                    break;
            }

            if (!empty($trashed)) {
                $this->save_history_for_delete($trashed);
            }

            $histories = $this->history_repository->get_histories();
            $history_count = $this->history_repository->get_history_count();
            $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));

            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', 'woocommerce-bulk-edit-free'),
                'history_items' => $histories_rendered,
                'edited_ids' => $products_ids,
            ]);
        }
        return false;
    }

    public function untrash_products()
    {
        $trash = (!empty($_POST['product_ids'])) ? $_POST['product_ids'] : $this->product_repository->get_trash();

        if (!empty($trash) && is_array($trash)) {
            foreach ($trash as $product_id) {
                wp_untrash_post(intval($product_id));
            }
        }

        $this->make_response([
            'success' => true,
            'message' => esc_html__('Success !', 'woocommerce-bulk-edit-free'),
        ]);
    }

    public function empty_trash()
    {
        $trash = $this->product_repository->get_trash();
        if (!empty($trash)) {
            foreach ($trash as $product_id) {
                wp_delete_post(intval($product_id), true);
            }
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', 'woocommerce-bulk-edit-free'),
            ]);
        }
        return false;
    }

    public function duplicate_product()
    {
        if (isset($_POST['product_ids']) && !empty($_POST['product_ids']) && !empty($_POST['duplicate_number'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                $product = $this->product_repository->get_product(intval($product_id));
                if (!($product instanceof \WC_Product)) {
                    return false;
                }

                for ($i = 1; $i <= intval($_POST['duplicate_number']); $i++) {
                    $new_product = new \WC_Admin_Duplicate_Product();
                    $new_product->product_duplicate($product);
                }
            }

            $this->make_response([
                'success' => esc_html__('Success !', 'woocommerce-bulk-edit-free'),
            ]);
        }
        return false;
    }

    public function add_product_taxonomy()
    {
        if (!empty($_POST['taxonomy_info']) && !empty($_POST['taxonomy_name']) && !empty($_POST['taxonomy_info']['name'])) {
            $result = wp_insert_category([
                'taxonomy' => sanitize_text_field($_POST['taxonomy_name']),
                'cat_name' => sanitize_text_field($_POST['taxonomy_info']['name']),
                'category_nicename' => sanitize_text_field($_POST['taxonomy_info']['slug']),
                'category_description' => sanitize_text_field($_POST['taxonomy_info']['description']),
                'category_parent' => intval($_POST['taxonomy_info']['parent']),
            ]);
            $checked = wp_get_post_terms(intval($_POST['taxonomy_info']['product_id']), sanitize_text_field($_POST['taxonomy_name']), [
                'fields' => 'ids',
            ]);
            if (!empty($result)) {
                $taxonomy_items = Taxonomy::wcbef_product_taxonomy_list(sanitize_text_field($_POST['taxonomy_name']), $checked);
                $this->make_response([
                    'success' => true,
                    'product_id' => intval($_POST['taxonomy_info']['product_id']),
                    'taxonomy_items' => $taxonomy_items,
                ]);
            }
        }
    }

    public function add_product_attribute()
    {
        if (!empty($_POST['attribute_info']) && !empty($_POST['attribute_name']) && !empty($_POST['attribute_info']['name'])) {
            $result = wp_insert_category([
                'taxonomy' => sanitize_text_field($_POST['attribute_name']),
                'cat_name' => sanitize_text_field($_POST['attribute_info']['name']),
                'category_nicename' => sanitize_text_field($_POST['attribute_info']['slug']),
                'category_description' => sanitize_text_field($_POST['attribute_info']['description']),
            ]);
            $items = get_terms([
                'taxonomy' => sanitize_text_field($_POST['attribute_name']),
                'hide_empty' => false,
            ]);
            $product_terms = wp_get_post_terms(intval($_POST['attribute_info']['product_id']), sanitize_text_field($_POST['attribute_name']), [
                'fields' => 'ids',
            ]);
            $attribute_items = '';
            if (!empty($items)) {
                foreach ($items as $item) {
                    $checked = (is_array($product_terms) && in_array($item->term_id, $product_terms)) ? 'checked="checked"' : '';
                    $attribute_items .= "<div><label><input type='checkbox' class='wcbef-inline-edit-tax' value='{$item->term_id}' {$checked}>{$item->name}</label></div>";
                }
            }
            if (!empty($result)) {
                $this->make_response([
                    'success' => true,
                    'product_id' => intval($_POST['category_info']['product_id']),
                    'attribute_items' => $attribute_items,
                ]);
            }
        }
        return false;
    }

    public function load_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();

            $preset = $search_repository->get_preset($_POST['preset_key']);
            if (!isset($preset['filter_data'])) {
                return false;
            }
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => $preset['filter_data'],
            ]);
            $result = $this->product_repository->get_products_list($preset['filter_data'], 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $preset['filter_data'],
                'products_list' => $result->products_list,
                'pagination' => $result->pagination,
                'products_count' => $result->count,
            ]);
        }
        return false;
    }

    public function delete_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();
            $delete_result = $search_repository->delete($_POST['preset_key']);
            if (!$delete_result) {
                return false;
            }

            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function save_column_profile()
    {
        if (isset($_POST['preset_key']) && isset($_POST['type'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_fields();
            $preset['date_modified'] = date('Y-m-d H:i:s', time());

            switch ($_POST['type']) {
                case 'save_as_new':
                    $preset['name'] = "Preset " . rand(100, 999);
                    $preset['key'] = 'preset-' . rand(1000000, 9999999);
                    break;
                case 'update_changes':
                    $preset_item = $column_repository->get_preset(sanitize_text_field($_POST['preset_key']));
                    if (!$preset_item) {
                        return false;
                    }
                    $preset['name'] = sanitize_text_field($preset_item['name']);
                    $preset['key'] = sanitize_text_field($preset_item['key']);
                    break;
            }

            $preset['fields'] = [];
            foreach ($_POST['items'] as $item) {
                if (isset($fields[$item])) {
                    $preset['fields'][$item] = [
                        'name' => sanitize_text_field($item),
                        'label' => sanitize_text_field($fields[$item]['label']),
                        'title' => sanitize_text_field($fields[$item]['label']),
                        'editable' => $fields[$item]['editable'],
                        'content_type' => $fields[$item]['content_type'],
                        'allowed_type' => $fields[$item]['allowed_type'],
                        'update_type' => $fields[$item]['update_type'],
                        'background_color' => '#fff',
                        'text_color' => '#444',
                    ];
                    if (isset($fields[$item]['sortable'])) {
                        $preset["fields"][$item]['sortable'] = $fields[$item]['sortable'];
                    }
                    if (isset($fields[$item]['sub_name'])) {
                        $preset["fields"][$item]['sub_name'] = $fields[$item]['sub_name'];
                    }
                    if (isset($fields[$item]['options'])) {
                        $preset["fields"][$item]['options'] = $fields[$item]['options'];
                    }
                    if (isset($fields[$item]['field_type'])) {
                        $preset["fields"][$item]['field_type'] = $fields[$item]['field_type'];
                    }
                    $preset['checked'][] = $item;
                }
            }

            $column_repository->update($preset);
            $column_repository->set_active_columns($preset['key'], $preset['fields']);
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function get_text_editor_content()
    {
        if (isset($_POST['product_id']) && isset($_POST['field'])) {
            $field = sanitize_text_field($_POST['field']);
            $field_type = sanitize_text_field($_POST['field_type']);

            $product_object = $this->product_repository->get_product(intval($_POST['product_id']));
            if (!($product_object instanceof \WC_Product)) {
                return false;
            }
            $product = $this->product_repository->get_product_fields($product_object);
            switch ($field_type) {
                case 'meta_field':
                case 'custom_field':
                    $value = (isset($product[$field_type][$field])) ? $product[$field_type][$field][0] : '';
                    break;
                default:
                    $value = $product[$field];
                    break;
            }

            $this->make_response([
                'success' => true,
                'content' => $value,
            ]);
        }
        return false;
    }

    public function history_filter()
    {
        if (isset($_POST['filters'])) {
            $where = [];
            if (isset($_POST['filters']['operation']) && !empty($_POST['filters']['operation'])) {
                $where['operation_type'] = $_POST['filters']['operation'];
            }
            if (isset($_POST['filters']['author']) && !empty($_POST['filters']['author'])) {
                $where['user_id'] = $_POST['filters']['author'];
            }
            if (isset($_POST['filters']['fields']) && !empty($_POST['filters']['fields'])) {
                $where['fields'] = $_POST['filters']['fields'];
            }
            if (isset($_POST['filters']['date'])) {
                $where['operation_date'] = $_POST['filters']['date'];
            }

            $histories = $this->history_repository->get_histories($where);
            $history_count = $this->history_repository->get_history_count($where);

            $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));

            $this->make_response([
                'success' => true,
                'history_items' => $histories_rendered,
            ]);
        }
        return false;
    }

    public function change_count_per_page()
    {
        if (isset($_POST['count_per_page'])) {
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'count_per_page' => intval($_POST['count_per_page']),
            ]);
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function filter_profile_change_use_always()
    {
        if (isset($_POST['preset_key'])) {
            (new Search())->update_use_always(sanitize_text_field($_POST['preset_key']));
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function get_taxonomy_parent_select_box()
    {
        if (isset($_POST['taxonomy']) && $_POST['taxonomy'] != 'product_tag') {
            $taxonomies = get_terms(['taxonomy' => sanitize_text_field($_POST['taxonomy']), 'hide_empty' => false]);
            $options = '<option value="-1">None</option>';
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    $term_id = intval($taxonomy->term_id);
                    $taxonomy_name = sanitize_text_field($taxonomy->name);
                    $options .= "<option value='{$term_id}'>{$taxonomy_name}</option>";
                }
            }
            $this->make_response([
                'success' => true,
                'options' => $options,
            ]);
        }
        return false;
    }

    public function get_product_data()
    {
        if (isset($_POST['product_id'])) {
            $product_object = $this->product_repository->get_product(intval($_POST['product_id']));
            $product_data = $this->product_repository->get_product_fields($product_object);
            $attributes = [];
            if (!empty($product_data['attribute'])) {
                foreach ($product_data['attribute'] as $attribute) {
                    $attributes[$attribute['name']] = (!empty($attribute['options'])) ? $attribute['options'] : [];
                }
            }
            $product_data['attribute'] = $attributes;

            $this->make_response([
                'success' => true,
                'product_data' => $product_data,
            ]);
        }
        return false;
    }

    public function get_product_by_ids()
    {
        if (empty($_POST['product_ids']) || !is_array($_POST['product_ids'])) {
            return false;
        }

        $products = [];
        $product_object = $this->product_repository->get_product_object_by_ids(['include' => array_map('intval', $_POST['product_ids'])]);
        if (!empty($product_object)) {
            foreach ($product_object as $product) {
                $products[$product->get_id()] = $product->get_title();
            }
        }

        $this->make_response([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function get_product_variations_for_attach()
    {
        if (isset($_POST['product_id'])) {
            $product = $this->product_repository->get_product(intval($_POST['product_id']));
            if (!($product instanceof \WC_Product_Variable)) {
                return false;
            }

            $variations = '';
            $attribute_items = get_terms(['taxonomy' => sanitize_text_field('pa_' . $_POST['attribute'])]);
            $attribute_item = sanitize_text_field($_POST['attribute_item']);
            $product_children = $product->get_children();
            if ($product_children > 0) {
                foreach ($product_children as $child) {
                    $variation = $this->product_repository->get_product(intval($child));
                    $variation_id = $variation->get_id();
                    $attributes = $variation->get_attributes();
                    $variation_attributes_labels = [];
                    if (!empty($attributes)) {
                        foreach ($attributes as $key => $attribute) {
                            $variation_attributes_labels[] = (!empty($attribute)) ? urldecode($attribute) : 'Any ' . urldecode($key);
                        }
                    }
                    $variation_attributes = (!empty($variation_attributes_labels)) ? implode(' | ', $variation_attributes_labels) : '';
                    $variations .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/variation_item_for_attach.php', compact('variation_attributes', 'variation_id', 'attribute_items', 'attribute_item'));
                }
            }

            $this->make_response([
                'success' => true,
                'variations' => $variations,
            ]);
        }
        return false;
    }

    public function get_product_files()
    {
        if (!empty($_POST['product_id'])) {
            $output = '';
            $product = $this->product_repository->get_product(intval($_POST['product_id']));
            if (!($product instanceof \WC_Product)) {
                return false;
            }
            $files = $product->get_downloads();
            if (!empty($files)) {
                foreach ($files as $file_item) {
                    $file_id = $file_item->get_id();
                    $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/file_item.php', compact('file_item', 'file_id'));
                }

                $this->make_response([
                    'success' => true,
                    'files' => $output,
                ]);
            }
            return false;
        }
        return false;
    }

    public function add_new_file_item()
    {
        if (isset($_POST)) {
            $file_id = md5(time() . rand(100, 999));
            $output = Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/file_item.php', compact('file_id'));
            $this->make_response([
                'success' => true,
                'file_item' => $output,
            ]);
        }
        return false;
    }

    public function variation_attaching()
    {
        if (isset($_POST['variation_id'])) {
            $product_repository = Product::get_instance();
            $attribute_key = 'pa_' . sanitize_text_field($_POST['attribute_key']);
            $variation_ids = array_map('intval', $_POST['variation_id']);
            $attribute_items = array_map('intval', $_POST['attribute_item']);

            if (!is_array($variation_ids) && !is_array($attribute_items)) {
                return false;
            }
            for ($i = 0; $i < count($variation_ids); $i++) {
                // save new attribute
                $variation = $product_repository->get_product(intval($variation_ids[$i]));
                if (!($variation instanceof \WC_Product_Variation)) {
                    return false;
                }
                $params = [
                    'field' => $attribute_key,
                    'value' => [intval($attribute_items[$i])],
                    'operator' => 'taxonomy_append',
                    'used_for_variations' => 'yes',
                ];
                $result = $product_repository->product_attribute_update($variation->get_parent_id(), $params);

                // save new combination
                $term = get_term(intval($attribute_items[$i]));
                if ($term instanceof \WP_Term && $result) {
                    $variation = $product_repository->get_product(intval($variation_ids[$i]));
                    $combinations = $variation->get_attributes();
                    $combinations[strtolower(urlencode($attribute_key))] = $term->slug;
                    $variation->set_attributes($combinations);
                    $variation->save();
                }
            }
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function sort_by_column()
    {
        if (!empty($_POST['column_name']) && !empty($_POST['sort_type']) && !empty($_POST['filter_data'])) {
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'sort_by' => sanitize_text_field($_POST['column_name']),
                'sort_type' => sanitize_text_field($_POST['sort_type']),
            ]);
            $result = $this->product_repository->get_products_list($_POST['filter_data'], 1);
            $this->make_response([
                'success' => true,
                'filter_data' => Sanitizer::array($_POST['filter_data']),
                'products_list' => $result->products_list,
                'pagination' => $result->pagination,
                'products_count' => $result->count,
            ]);
        }
        return false;
    }

    public function clear_filter_data()
    {
        $search_repository = new Search();
        $search_repository->delete_current_data();
        $this->make_response([
            'success' => true,
        ]);
    }

    public function get_product_gallery_images()
    {
        if (empty($_POST['product_id'])) {
            return false;
        }

        $product = $this->product_repository->get_product(intval($_POST['product_id']));
        if (!($product instanceof \WC_Product)) {
            return false;
        }

        $image_ids = $product->get_gallery_image_ids();
        $images = Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/gallery_image.php', compact('image_ids'));

        $this->make_response([
            'success' => true,
            'images' => $images,
        ]);
    }

    private function save_history_for_delete($product_ids)
    {
        if (empty($product_ids) || !is_array($product_ids)) {
            return false;
        }

        $create_history = $this->history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize(['product_delete']),
            'operation_type' => History::BULK_OPERATION,
            'operation_date' => date('Y-m-d H:i:s'),
        ]);

        if (!$create_history) {
            return false;
        }

        foreach ($product_ids as $product_id) {
            $this->history_repository->save_history_item([
                'history_id' => intval($create_history),
                'historiable_id' => intval($product_id),
                'name' => 'product_delete',
                'type' => 'product_action',
                'prev_value' => 'untrash',
                'new_value' => 'trash',
            ]);
        }

        return true;
    }

    private function make_response($data)
    {
        echo (is_array($data)) ? json_encode($data) : sprintf('%s', $data);
        die();
    }
}
