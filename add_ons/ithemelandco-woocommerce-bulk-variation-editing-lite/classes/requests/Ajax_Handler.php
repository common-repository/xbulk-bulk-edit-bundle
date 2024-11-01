<?php

namespace iwbvel\classes\requests;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\helpers\Others;
use iwbvel\classes\helpers\Render;
use iwbvel\classes\helpers\Sanitizer;
use iwbvel\classes\helpers\Filter_Helper;
use iwbvel\classes\helpers\Product_Helper;
use iwbvel\classes\helpers\Taxonomy;
use iwbvel\classes\repositories\Column;
use iwbvel\classes\repositories\History;
use iwbvel\classes\repositories\Product;
use iwbvel\classes\repositories\Search;
use iwbvel\classes\repositories\Setting;
use iwbvel\classes\services\filter\Product_Filter_Service;
use iwbvel\classes\services\product_update\Update_Service;

class Ajax_Handler
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
        add_action('wp_ajax_iwbvel_column_manager_add_field', [$this, 'column_manager_add_field']);
        add_action('wp_ajax_iwbvel_column_manager_get_fields_for_edit', [$this, 'column_manager_get_fields_for_edit']);
        add_action('wp_ajax_iwbvel_products_filter', [$this, 'products_filter']);
        add_action('wp_ajax_iwbvel_save_filter_preset', [$this, 'save_filter_preset']);
        add_action('wp_ajax_iwbvel_product_edit', [$this, 'product_edit']);
        add_action('wp_ajax_iwbvel_get_products_name', [$this, 'get_products_name']);
        add_action('wp_ajax_iwbvel_create_new_product', [$this, 'create_new_product']);
        add_action('wp_ajax_iwbvel_get_product_variations', [$this, 'get_product_variations']);
        add_action('wp_ajax_iwbvel_variations_change_page', [$this, 'variations_change_page']);
        add_action('wp_ajax_iwbvel_delete_all_variations_by_variable_ids', [$this, 'delete_all_variations_by_variable_ids']);
        add_action('wp_ajax_iwbvel_delete_variations_by_ids', [$this, 'delete_variations_by_ids']);
        add_action('wp_ajax_iwbvel_delete_variations_by_attribute', [$this, 'delete_variations_by_attribute']);
        add_action('wp_ajax_iwbvel_delete_products', [$this, 'delete_products']);
        add_action('wp_ajax_iwbvel_untrash_products', [$this, 'untrash_products']);
        add_action('wp_ajax_iwbvel_empty_trash', [$this, 'empty_trash']);
        add_action('wp_ajax_iwbvel_duplicate_product', [$this, 'duplicate_product']);
        add_action('wp_ajax_iwbvel_add_product_taxonomy', [$this, 'add_product_taxonomy']);
        add_action('wp_ajax_iwbvel_add_product_attribute', [$this, 'add_product_attribute']);
        add_action('wp_ajax_iwbvel_load_filter_profile', [$this, 'load_filter_profile']);
        add_action('wp_ajax_iwbvel_delete_filter_profile', [$this, 'delete_filter_profile']);
        add_action('wp_ajax_iwbvel_save_column_profile', [$this, 'save_column_profile']);
        add_action('wp_ajax_iwbvel_get_text_editor_content', [$this, 'get_text_editor_content']);
        add_action('wp_ajax_iwbvel_change_count_per_page', [$this, 'change_count_per_page']);
        add_action('wp_ajax_iwbvel_filter_profile_change_use_always', [$this, 'filter_profile_change_use_always']);
        add_action('wp_ajax_iwbvel_get_default_filter_profile_products', [$this, 'get_default_filter_profile_products']);
        add_action('wp_ajax_iwbvel_get_taxonomy_parent_select_box', [$this, 'get_taxonomy_parent_select_box']);
        add_action('wp_ajax_iwbvel_get_product_data', [$this, 'get_product_data']);
        add_action('wp_ajax_iwbvel_get_product_by_ids', [$this, 'get_product_by_ids']);
        add_action('wp_ajax_iwbvel_get_product_files', [$this, 'get_product_files']);
        add_action('wp_ajax_iwbvel_add_new_file_item', [$this, 'add_new_file_item']);
        add_action('wp_ajax_iwbvel_variations_attach_terms', [$this, 'variations_attach_terms']);
        add_action('wp_ajax_iwbvel_variations_swap_terms', [$this, 'variations_swap_terms']);
        add_action('wp_ajax_iwbvel_sort_by_column', [$this, 'sort_by_column']);
        add_action('wp_ajax_iwbvel_clear_filter_data', [$this, 'clear_filter_data']);
        add_action('wp_ajax_iwbvel_get_product_gallery_images', [$this, 'get_product_gallery_images']);
        add_action('wp_ajax_iwbvel_history_change_page', [$this, 'history_change_page']);
        add_action('wp_ajax_iwbvel_add_new_term', [$this, 'add_new_term']);
        add_action('wp_ajax_iwbvel_get_variation', [$this, 'get_variation']);
        add_action('wp_ajax_iwbvel_get_terms_by_attribute_name', [$this, 'get_terms_by_attribute_name']);
        add_action('wp_ajax_iwbvel_get_term_ids_by_attribute_name', [$this, 'get_term_ids_by_attribute_name']);
        add_action('wp_ajax_iwbvel_add_variations', [$this, 'add_variations']);
        add_action('wp_ajax_iwbvel_variations_attributes_edit', [$this, 'variations_attributes_edit']);
        add_action('wp_ajax_iwbvel_default_attributes_update', [$this, 'default_attributes_update']);
        add_action('wp_ajax_iwbvel_get_possible_combinations', [$this, 'get_possible_combinations']);
        add_action('wp_ajax_iwbvel_get_variations_for_attach', [$this, 'get_variations_for_attach']);
    }

    public function get_default_filter_profile_products()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        $filter_data = Filter_Helper::get_active_filter_data();
        $result = $this->product_repository->get_products_list($filter_data, 1);
        $this->make_response([
            'success' => true,
            'filter_data' => $filter_data,
            'products_list' => $result->products_list,
            'status_filters' => $result->status_filters,
            'pagination' => $result->pagination,
            'products_count' => $result->count,
        ]);
    }

    public function products_filter()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST['filter_data'])) {
            $current_page = !empty($_POST['current_page']) ? intval($_POST['current_page']) : 1;
            $filter_result = $this->product_repository->get_products_list(Sanitizer::array($_POST['filter_data']), $current_page);
            $this->make_response([
                'success' => true,
                'products_list' => $filter_result->products_list,
                'status_filters' => $filter_result->status_filters,
                'pagination' => $filter_result->pagination,
                'products_count' => $filter_result->count,
            ]);
        }

        return false;
    }

    public function column_manager_add_field()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST['field_name']) && is_array($_POST['field_name']) && !empty($_POST['field_name'])) {
            $output = '';
            $field_action = sanitize_text_field($_POST['field_action']);
            for ($i = 0; $i < count($_POST['field_name']); $i++) {
                $field_name = sanitize_text_field($_POST['field_name'][$i]);
                $field_label = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                $field_title = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                $output .= Render::html(IWBVEL_VIEWS_DIR . "column_manager/field_item.php", compact('field_name', 'field_label', 'field_action', 'field_title'));
            }
            $this->make_response($output);
        }

        return false;
    }

    public function column_manager_get_fields_for_edit()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
                        $output .= Render::html(IWBVEL_VIEWS_DIR . 'column_manager/field_item.php', $field_info);
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (!empty($_POST['preset_name'])) {
            $filter_item['name'] = sanitize_text_field($_POST['preset_name']);
            $filter_item['date_modified'] = gmdate('Y-m-d H:i:s');
            $filter_item['key'] = 'preset-' . random_int(1000000, 9999999);
            $filter_item['filter_data'] = Sanitizer::array($_POST['filter_data']);
            $save_result = (new Search())->update($filter_item);
            if (!$save_result) {
                return false;
            }
            $new_item = Render::html(IWBVEL_VIEWS_DIR . 'modals/filter_profile_item.php', compact('filter_item'));
            $this->make_response([
                'success' => $save_result,
                'new_item' => $new_item,
            ]);
        }
        return false;
    }

    public function product_edit()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['product_data']) || !is_array($_POST['product_data'])) {
            return false;
        }

        if (!empty($_POST['product_ids'])) {
            if (!empty($_POST['type']) && $_POST['type'] == 'product_variations') {
                $product_ids = [];
                foreach ($_POST['product_ids'] as $variable_id) {
                    $variable_object = wc_get_product(intval($variable_id));
                    if (!($variable_object instanceof \WC_Product_Variable)) {
                        continue;
                    }

                    $product_ids[] = $variable_object->get_children();
                }
            } else {
                $product_ids = array_map('intval', $_POST['product_ids']);
            }
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
        }

        if (empty($product_ids)) {
            return false;
        }

        $product_ids = Others::array_flatten($product_ids);

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'product',
            'product_ids' => $product_ids,
            'product_data' => Sanitizer::array($_POST['product_data']),
            'save_history' => true,
        ]);
        $update_result = $update_service->perform();
        $result = $this->product_repository->get_products_rows($product_ids);
        $histories = $this->history_repository->get_histories();
        $history_count = $this->history_repository->get_history_count();
        $reverted = $this->history_repository->get_latest_reverted();
        
        $histories_rendered = Render::html(IWBVEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
        $history_pagination = Render::html(IWBVEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count'));

        $this->make_response([
            'success' => $update_result,
            'products' => $result->product_rows,
            'product_statuses' => $result->product_statuses,
            'status_filters' => $result->status_filters,
            'history_items' => $histories_rendered,
            'history_pagination' => $history_pagination,
            'reverted' => !empty($reverted),
        ]);
    }

    public function get_variation()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variation_id'])) {
            return $this->make_response(['success' => false]);
        }

        $variation = $this->product_repository->get_product(intval($_POST['variation_id']));
        if (!($variation instanceof \WC_Product_Variation)) {
            return $this->make_response(['success' => false]);
        }

        $variation_data = $variation->get_data();
        $variation_data['is_visible'] = ($variation->get_status() == 'publish');

        if (!empty($variation_data['downloads'])) {
            $downloads = [];
            foreach ($variation_data['downloads'] as $download_object) {
                $downloads[] = $download_object->get_data();
            }
            $variation_data['downloads'] = $downloads;
        }

        return $this->make_response([
            'success' => true,
            'variation' => $variation_data,
        ]);
    }

    public function history_change_page()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['page'])) {
            return false;
        }

        $where = [];

        if (isset($_POST['filters'])) {
            if (isset($_POST['filters']['operation']) && !empty($_POST['filters']['operation'])) {
                $where['operation_type'] = sanitize_text_field($_POST['filters']['operation']);
            }
            if (isset($_POST['filters']['author']) && !empty($_POST['filters']['author'])) {
                $where['user_id'] = sanitize_text_field($_POST['filters']['author']);
            }
            if (isset($_POST['filters']['fields']) && !empty($_POST['filters']['fields'])) {
                $where['fields'] = sanitize_text_field($_POST['filters']['fields']);
            }
            if (isset($_POST['filters']['date'])) {
                $where['operation_date'] = sanitize_text_field($_POST['filters']['date']);
            }
        }

        $per_page = 10;
        $history_count = $this->history_repository->get_history_count($where);
        $current_page = intval($_POST['page']);
        $offset = intval($current_page - 1) * $per_page;
        $histories = $this->history_repository->get_histories($where, $per_page, $offset);
        $histories_rendered = Render::html(IWBVEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
        $history_pagination = Render::html(IWBVEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count', 'per_page', 'current_page'));

        $this->make_response([
            'success' => true,
            'history_items' => $histories_rendered,
            'history_pagination' => $history_pagination,
        ]);
    }

    public function get_products_name()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        $list = [];
        if (!empty($_POST['search'])) {
            $args['iwbvel_general_column_filter'] = [
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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

    public function get_product_variations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST['product_id'])) {
            $product = $this->product_repository->get_product(intval($_POST['product_id']));
            if (!($product instanceof \WC_Product) || $product->get_type() != 'variable') {
                return false;
            }

            $attributes = [];
            $product_attributes = $product->get_attributes();
            if (!empty($product_attributes)) {
                foreach ($product_attributes as $key => $product_attribute) {
                    $attributes[str_replace('pa_', '', $key)] = [
                        'terms' => $product_attribute->get_options(),
                        'visible' => $product_attribute->get_visible(),
                        'variation' => $product_attribute->get_variation(),
                    ];
                }
            }

            $pagination = '';
            $variations = '';
            $current_page = (!empty($_POST['current_page'])) ? intval($_POST['current_page']) : 1;

            $product_children = new \WP_Query([
                'post_parent' => intval($product->get_id()),
                'posts_per_page' => 10,
                'paged' => intval($current_page),
                'paginate' => true,
                'post_type' => 'product_variation',
                'orderby' => 'ID',
                'sort_order' => 'DESC',
                'fields' => 'ids',
            ]);

            $default_attributes = $product->get_default_attributes();
            if ($product_children->posts > 0) {
                foreach ($product_children->posts as $variation_id) {
                    $variation = $this->product_repository->get_product(intval($variation_id));
                    if ($variation->get_status() != 'trash') {
                        $variations .= Render::html(IWBVEL_VIEWS_DIR . 'variations/add_variations/variations-table-row.php', compact('variation', 'default_attributes'));
                    }
                }

                $max_num_pages = $product_children->max_num_pages;
                $pagination = Render::html(IWBVEL_VIEWS_DIR . 'variations/add_variations/pagination.php', compact('max_num_pages', 'current_page'));
            }

            $this->make_response([
                'success' => true,
                'attributes' => $attributes,
                'variations' => $variations,
                'pagination' => $pagination,
            ]);
        }
        return false;
    }

    public function variations_change_page()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST['product_id'])) {
            $product = $this->product_repository->get_product(intval($_POST['product_id']));
            if (!($product instanceof \WC_Product) || $product->get_type() != 'variable') {
                return false;
            }

            $pagination = '';
            $variations = '';
            $current_page = (!empty($_POST['current_page'])) ? intval($_POST['current_page']) : 1;

            $product_children = new \WP_Query([
                'post_parent' => intval($product->get_id()),
                'posts_per_page' => 10,
                'paged' => intval($current_page),
                'paginate' => true,
                'post_type' => 'product_variation',
                'orderby' => 'ID',
                'sort_order' => 'DESC',
                'fields' => 'ids',
            ]);

            $default_attributes = $product->get_default_attributes();
            if ($product_children->posts > 0) {
                foreach ($product_children->posts as $variation_id) {
                    $variation = $this->product_repository->get_product(intval($variation_id));
                    if ($variation->get_status() != 'trash') {
                        $variations .= Render::html(IWBVEL_VIEWS_DIR . 'variations/add_variations/variations-table-row.php', compact('variation', 'default_attributes'));
                    }
                }

                $max_num_pages = $product_children->max_num_pages;
                $pagination = Render::html(IWBVEL_VIEWS_DIR . 'variations/add_variations/pagination.php', compact('max_num_pages', 'current_page'));
            }

            $this->make_response([
                'success' => true,
                'variations' => $variations,
                'pagination' => $pagination,
            ]);
        }
        return false;
    }

    public function delete_all_variations_by_variable_ids()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_ids'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => array_map('intval', $_POST['variable_ids']),
            'product_data' => [
                [
                    'type' => 'variation',
                    'name' => 'delete_variations',
                    'action' => 'delete_all',
                    'value' => 'all'
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $this->make_response([
            'success' => $update_result,
        ]);
    }

    public function delete_variations_by_ids()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_id']) || empty($_POST['variation_ids'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => [intval($_POST['variable_id'])],
            'product_data' => [
                [
                    'type' => 'variation',
                    'name' => 'delete_variations',
                    'action' => 'delete_by_ids',
                    'value' => array_map('intval', $_POST['variation_ids'])
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $this->make_response([
            'success' => $update_result,
        ]);
    }

    public function delete_variations_by_attribute()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_ids']) || empty($_POST['attribute']) || empty($_POST['term'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => array_map('intval', $_POST['variable_ids']),
            'product_data' => [
                [
                    'type' => 'variation',
                    'name' => 'delete_variations',
                    'action' => 'delete_by_term',
                    'value' => [
                        'attribute' => sanitize_text_field($_POST['attribute']),
                        'term' => sanitize_text_field($_POST['term']),
                    ]
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $this->make_response([
            'success' => $update_result,
        ]);
    }

    public function default_attributes_update()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_ids']) || empty($_POST['attributes'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => array_map('intval', $_POST['variable_ids']),
            'product_data' => [
                [
                    'type' => 'variation',
                    'name' => 'default_variation',
                    'action' => 'default_attributes',
                    'value' => [
                        'attributes' => Sanitizer::array($_POST['attributes']),
                    ]
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $this->make_response([
            'success' => $update_result,
        ]);
    }

    public function get_terms_by_attribute_name()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['attribute_name'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $terms = get_terms([
            'taxonomy' => sanitize_text_field($_POST['attribute_name']),
            'hide_empty' => false
        ]);

        $output = '';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                if ($term instanceof \WP_Term) {
                    $output .= '<option value="' . esc_attr($term->slug) . '" data-term-id="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                }
            }
        } else {
            $output .= '<option value="">No term available</option>';
        }

        $this->make_response([
            'success' => true,
            'terms' => $output
        ]);
    }

    public function get_term_ids_by_attribute_name()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['attribute_name'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $terms = get_terms([
            'taxonomy' => sanitize_text_field($_POST['attribute_name']),
            'hide_empty' => false
        ]);

        $output = '';
        if (!empty($terms)) {
            foreach ($terms as $term) {
                if ($term instanceof \WP_Term) {
                    $output .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                }
            }
        } else {
            $output .= '<option value="">No term available</option>';
        }

        $this->make_response([
            'success' => true,
            'terms' => $output
        ]);
    }

    public function delete_products()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (!empty($_POST['delete_type'])) {
            $product_ids = (!empty($_POST['product_ids']) && is_array($_POST['product_ids'])) ? array_map('intval', $_POST['product_ids']) : [];
            $trashed = [];
            switch ($_POST['delete_type']) {
                case 'trash':
                    if (!empty($product_ids)) {
                        foreach ($product_ids as $product_id) {
                            wp_trash_post(intval($product_id));
                        }
                    }
                    break;
                case 'all':
                    if (isset($_POST['filter_data'])) {
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
                        if (!empty($product_ids)) {
                            foreach ($product_ids as $product_id) {
                                $trashed[] = intval($product_id);
                                wp_trash_post(intval($product_id));
                            }
                        }
                    }

                    break;
                case 'permanently':
                    if (!empty($product_ids)) {
                        foreach ($product_ids as $product_id) {
                            wp_delete_post(intval($product_id), true);
                        }
                    }
                    break;
            }

            if (!empty($trashed)) {
                $this->save_history_for_delete($trashed);
            }

            $histories = $this->history_repository->get_histories();
            $history_count = $this->history_repository->get_history_count();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(IWBVEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $history_pagination = Render::html(IWBVEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count'));

            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-variations-editing'),
                'history_items' => $histories_rendered,
                'history_pagination' => $history_pagination,
                'reverted' => !empty($reverted),
                'edited_ids' => $product_ids,
            ]);
        }
        return false;
    }

    public function untrash_products()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        $trash = (!empty($_POST['product_ids'])) ? array_map('intval', $_POST['product_ids']) : $this->product_repository->get_trash();

        if (!empty($trash) && is_array($trash)) {
            foreach ($trash as $product_id) {
                wp_untrash_post(intval($product_id));
            }
        }

        $this->make_response([
            'success' => true,
            'message' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-variations-editing'),
        ]);
    }

    public function empty_trash()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        $trash = $this->product_repository->get_trash();
        if (!empty($trash)) {
            foreach ($trash as $product_id) {
                wp_delete_post(intval($product_id), true);
            }
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-variations-editing'),
            ]);
        }
        return false;
    }

    public function duplicate_product()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
                'success' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-variations-editing'),
            ]);
        }
        return false;
    }

    public function add_product_taxonomy()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
                $taxonomy_items = Taxonomy::iwbvel_product_taxonomy_list(sanitize_text_field($_POST['taxonomy_name']), $checked);
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
                    $attribute_items .= "<li><label><input type='checkbox' class='iwbvel-inline-edit-tax' data-field='value' value='{$item->term_id}' {$checked}>{$item->name}</label></li>";
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

    public function add_new_term()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['attribute_name']) || empty($_POST['term_name'])) {
            return $this->make_response([
                'success' => false
            ]);
        }

        $taxonomy = 'pa_' . sanitize_text_field($_POST['attribute_name']);
        $term_id = wp_insert_category([
            'taxonomy' => $taxonomy,
            'cat_name' => sanitize_text_field($_POST['term_name']),
        ]);

        if ($term_id) {
            $attribute_term = get_term_by('term_id', intval($term_id), $taxonomy);
            $new_term_html = Render::html(IWBVEL_VIEWS_DIR . "variations/add_variations/term-item.php", compact('attribute_term'));
        }

        return ($term_id) ? $this->make_response([
            'success' => true,
            'new_term_html' => $new_term_html,
        ]) : $this->make_response([
            'success' => false
        ]);
    }

    public function add_variations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_ids']) || empty($_POST['attributes']) || empty($_POST['variations'])) {
            return $this->make_response([
                'success' => false
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => array_map('intval', $_POST['variable_ids']),
            'product_data' => [
                [
                    'name' => 'bulk_variation_update',
                    'type' => 'variation',
                    'action' => 'add_variations',
                    'value' => [
                        'product_type' => 'variable',
                        'attributes' => Sanitizer::array($_POST['attributes']),
                        'variations' => Sanitizer::array($_POST['variations']),
                    ]
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $histories = $this->history_repository->get_histories();
        $history_count = $this->history_repository->get_history_count();
        $reverted = $this->history_repository->get_latest_reverted();
        $histories_rendered = Render::html(IWBVEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
        $history_pagination = Render::html(IWBVEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count'));

        $this->make_response([
            'success' => $update_result,
            'reverted' => !empty($reverted),
            'history_items' => $histories_rendered,
            'history_pagination' => $history_pagination,
        ]);
    }

    public function replace_variations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_ids']) || empty($_POST['combinations']) || empty($_POST['attributes'])) {
            return $this->make_response([
                'success' => false
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => array_map('intval', $_POST['variable_ids']),
            'product_data' => [
                [
                    'name' => 'bulk_variation_update',
                    'type' => 'variation',
                    'action' => 'replace_variations',
                    'value' => [
                        'product_type' => 'variable',
                        'combinations' => Sanitizer::array($_POST['combinations']),
                        'attributes' => Sanitizer::array($_POST['attributes']),
                    ]
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $histories = $this->history_repository->get_histories();
        $history_count = $this->history_repository->get_history_count();
        $reverted = $this->history_repository->get_latest_reverted();
        $histories_rendered = Render::html(IWBVEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
        $history_pagination = Render::html(IWBVEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count'));

        $this->make_response([
            'success' => $update_result,
            'reverted' => !empty($reverted),
            'history_items' => $histories_rendered,
            'history_pagination' => $history_pagination,
        ]);
    }

    public function load_filter_profile()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();

            $preset = $search_repository->get_preset(sanitize_text_field($_POST['preset_key']));
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
                'status_filters' => $result->status_filters,
                'pagination' => $result->pagination,
                'products_count' => $result->count,
            ]);
        }
        return false;
    }

    public function delete_filter_profile()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();
            $delete_result = $search_repository->delete(sanitize_text_field($_POST['preset_key']));
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST['preset_key']) && isset($_POST['type'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_fields();
            $preset['date_modified'] = gmdate('Y-m-d H:i:s', time());

            switch ($_POST['type']) {
                case 'save_as_new':
                    $preset['name'] = "Preset " . random_int(100, 999);
                    $preset['key'] = 'preset-' . random_int(1000000, 9999999);
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
                $item = sanitize_text_field($item);
                if (isset($fields[$item])) {
                    $preset['fields'][$item] = [
                        'name' => $item,
                        'label' => $fields[$item]['label'],
                        'title' => $fields[$item]['label'],
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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

    public function change_count_per_page()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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

    public function get_product_files()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

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
                    $output .= Render::html(IWBVEL_VIEWS_DIR . 'bulk_edit/columns_modals/file_item.php', compact('file_item', 'file_id'));
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (isset($_POST)) {
            $file_id = md5(time() . random_int(100, 999));
            $output = Render::html(IWBVEL_VIEWS_DIR . 'bulk_edit/columns_modals/file_item.php', compact('file_id'));
            $this->make_response([
                'success' => true,
                'file_item' => $output,
            ]);
        }
        return false;
    }

    public function variations_attach_terms()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_ids']) || empty($_POST['attribute']) || empty($_POST['terms']) || empty($_POST['variations'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => array_map('intval', $_POST['variable_ids']),
            'product_data' => [
                [
                    'type' => 'variation',
                    'name' => 'attach_terms',
                    'action' => 'attach_variations',
                    'value' => [
                        'attribute' => sanitize_text_field($_POST['attribute']),
                        'terms' => array_map('intval', $_POST['terms']),
                        'variations' => array_map('intval', $_POST['variations']),
                    ]
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $this->make_response([
            'success' => $update_result,
        ]);
    }

    public function variations_swap_terms()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_ids']) || empty($_POST['attribute']) || empty($_POST['from_term']) || empty($_POST['to_term'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => array_map('intval', $_POST['variable_ids']),
            'product_data' => [
                [
                    'type' => 'variation',
                    'name' => 'swap_terms',
                    'action' => 'swap_variations',
                    'value' => [
                        'variation_ids' => (!empty($_POST['variation_ids']) && is_array($_POST['variation_ids'])) ? array_map('intval', $_POST['variation_ids']) : 'all',
                        'attribute' => sanitize_text_field($_POST['attribute']),
                        'from_term' => intval($_POST['from_term']),
                        'to_term' => intval($_POST['to_term']),
                    ]
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $this->make_response([
            'success' => $update_result,
        ]);
    }

    public function variations_attributes_edit()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['variable_id']) || empty($_POST['attributes']) || empty($_POST['variation_id'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $update_service = Update_Service::get_instance();
        $update_service->set_update_data([
            'update_type' => 'variation',
            'product_ids' => [intval($_POST['variable_id'])],
            'product_data' => [
                [
                    'type' => 'variation',
                    'name' => 'attributes_edit',
                    'action' => 'attributes_edit',
                    'value' => [
                        'variation_id' => intval($_POST['variation_id']),
                        'attributes' => array_map('sanitize_text_field', $_POST['attributes']),
                    ]
                ]
            ],
            'save_history' => true,
        ]);

        $update_result = $update_service->perform();

        $this->make_response([
            'success' => $update_result,
        ]);
    }

    public function get_possible_combinations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['attributes'])) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $combinations = Product_Helper::get_combinations(Sanitizer::array(array_map('array_values', $_POST['attributes'])));
        if (empty($combinations)) {
            $this->make_response([
                'success' => false,
            ]);
        }

        $items = Render::html(IWBVEL_VIEWS_DIR . 'variations/add_variations/possible-combinations.php', compact('combinations'));

        $this->make_response([
            'success' => true,
            'items' => $items
        ]);
    }

    public function sort_by_column()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (!empty($_POST['column_name']) && !empty($_POST['sort_type']) && !empty($_POST['filter_data'])) {
            $filter_data = Sanitizer::array($_POST['filter_data']);
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'sort_by' => sanitize_text_field($_POST['column_name']),
                'sort_type' => sanitize_text_field($_POST['sort_type']),
            ]);
            $result = $this->product_repository->get_products_list($filter_data, 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $filter_data,
                'products_list' => $result->products_list,
                'status_filters' => $result->status_filters,
                'pagination' => $result->pagination,
                'products_count' => $result->count,
            ]);
        }
        return false;
    }

    public function clear_filter_data()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        $search_repository = new Search();
        $search_repository->delete_current_data();
        $this->make_response([
            'success' => true,
        ]);
    }

    public function get_product_gallery_images()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iwbvel_ajax_nonce')) {
            die();
        }

        if (empty($_POST['product_id'])) {
            return false;
        }

        $product = $this->product_repository->get_product(intval($_POST['product_id']));
        if (!($product instanceof \WC_Product)) {
            return false;
        }

        $image_ids = $product->get_gallery_image_ids();
        $images = Render::html(IWBVEL_VIEWS_DIR . 'bulk_edit/columns_modals/gallery_image.php', compact('image_ids'));

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
            'operation_date' => gmdate('Y-m-d H:i:s'),
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
        echo (is_array($data)) ? wp_json_encode($data) : wp_kses($data, Sanitizer::allowed_html());
        die();
    }
}
