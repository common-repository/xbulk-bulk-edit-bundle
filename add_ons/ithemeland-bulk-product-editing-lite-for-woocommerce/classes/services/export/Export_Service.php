<?php

namespace wcbel\classes\services\export;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Others;
use wcbel\classes\helpers\Product_Helper;
use wcbel\classes\repositories\Column;
use wcbel\classes\repositories\Product;
use wcbel\classes\repositories\Search;
use wcbel\classes\services\filter\Product_Filter_Service;

class Export_Service
{
    private static $instance;

    private $export_file;
    private $product_repository;
    private $field_labels;
    private $data;
    private $product_ids;
    private $products;
    private $columns;
    private $table_header;
    private $table_body;
    private $delimiter;

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
        $this->field_labels = $this->get_field_labels();
    }

    public function set_data($data)
    {
        $this->data = $data;
        $this->delimiter = (!empty($data['delimiter'])) ? sanitize_text_field($data['delimiter']) : ',';
    }

    public function perform()
    {
        if (!defined('WC_ABSPATH')) {
            return false;
        }

        $this->set_product_ids();
        if (empty($this->product_ids) || !is_array($this->product_ids)) {
            return false;
        }

        $this->set_columns();
        if (empty($this->columns) || !is_array($this->columns)) {
            return false;
        }

        $this->set_product_objects();
        $this->set_table_data();
        $this->set_header();

        fputcsv($this->export_file, $this->table_header, $this->delimiter);
        if (!empty($this->table_body) && is_array($this->table_body)) {
            foreach ($this->table_body as $row) {
                if (!empty($row) && is_array($row)) {
                    fputcsv($this->export_file, $row, $this->delimiter);
                }
            }
        }

        die();
    }

    private function set_product_ids()
    {
        switch ($this->data['select_type']) {
            case 'all':
                $search_repository = new Search();
                $last_filter_data = isset($search_repository->get_current_data()['last_filter_data']) ? $search_repository->get_current_data()['last_filter_data'] : null;
                $product_filter_service = Product_Filter_Service::get_instance();
                $filtered_products = $product_filter_service->get_filtered_products($last_filter_data, [
                    'order' => 'ASC',
                    'fields' => 'ids',
                ]);

                $product_ids = [];
                if (!empty($filtered_products['product_ids'])) {
                    $product_ids[] = $filtered_products['product_ids'];
                }
                if (!empty($filtered_products['variation_ids'])) {
                    $product_ids[] = $filtered_products['variation_ids'];
                }
                $this->product_ids = Others::array_flatten($product_ids);
                break;
            case 'selected':
                $this->product_ids = isset($this->data['selected_ids']) ? $this->data['selected_ids'] : [];
                break;
            default:
                $this->product_ids = null;
        }
    }

    private function set_columns()
    {
        $column_repository = new Column();
        switch ($this->data['field_type']) {
            case 'all':
                $product_meta_keys = $this->get_product_meta_keys();
                $this->columns = (!empty($product_meta_keys) && is_array($product_meta_keys)) ? $column_repository->get_fields() + $product_meta_keys : $column_repository->get_fields();
                break;
            case 'visible':
                $this->columns = $column_repository->get_active_columns()['fields'];
                break;
            default:
                $this->columns = [];
        }
    }

    private function set_product_objects()
    {
        if (!empty($this->product_ids)) {
            foreach ($this->product_ids as $product_id) {
                $product_object = $this->product_repository->get_product(intval($product_id));
                if (!($product_object instanceof \WC_Product)) {
                    continue;
                }

                if ($product_object instanceof \WC_Product_Variation && in_array($product_object->get_parent_id(), $this->product_ids)) {
                    continue;
                }

                $this->products[$product_object->get_id()] = $product_object;

                $attributes = $product_object->get_attributes();
                if (!empty($attributes) && count($attributes)) {
                    foreach ($attributes as $attribute_name => $attribute) {
                        if (empty($this->columns[$attribute_name]) && is_a($attribute, 'WC_Product_Attribute')) {
                            $this->columns[$attribute_name] = [
                                'name' => $attribute_name,
                                'label' => wc_attribute_label($attribute->get_name(), $product_object),
                                'field_type' => 'attribute',
                            ];
                        }
                    }
                }
            }
        }
    }

    private function set_table_data()
    {
        if (!class_exists('WC_Product_CSV_Exporter') && defined("WC_ABSPATH")) {
            include_once WC_ABSPATH . 'includes/export/class-wc-product-csv-exporter.php';
        }

        if (!empty($this->products)) {
            foreach ($this->products as $product_object) {
                $this->set_row($product_object);

                if ($product_object->is_type('variable')) {
                    $variation_ids = $product_object->get_children();
                    if (!empty($variation_ids)) {
                        foreach ($variation_ids as $variation_id) {
                            $variation = $this->product_repository->get_product(intval($variation_id));
                            if ($variation instanceof \WC_Product) {
                                $this->set_row($variation);
                            }
                        }
                    }
                }
            }
        }

        $this->set_table_header();
    }

    private function set_row($product_object)
    {
        $product = $this->product_repository->get_product_fields($product_object);
        $table_body = [];
        $table_body[] = (!empty($product['id'])) ? intval($product['id']) : '';
        $table_body[] = (!empty($product['type'])) ? $product['type'] : '';
        $table_body[] = (isset($product['title'])) ? $product['title'] : '';
        $table_body[] = (!empty($product['post_parent'])) ? 'id:' . intval($product['post_parent']) : '';

        if (!empty($this->columns)) {
            foreach ($this->columns as $field => $column_item) {
                if (!empty($column_item['field_type']) && !in_array($field, $this->except_fields())) {
                    $field_encoded = strtolower(urlencode($field));
                    switch ($column_item['field_type']) {
                        case 'it_wc_dynamic_pricing':
                        case 'yikes_custom_product_tabs':
                        case 'ithemeland_badge':
                        case 'woo_multi_currency':
                        case 'woocommerce_cost_of_goods':
                        case 'yith_badge_management':
                        case 'yith_cost_of_goods':
                        case 'woocommerce_vendors':
                        case 'yith_vendors':
                        case 'yith_min_max_quantities':
                        case 'woocommerce_min_max_quantities':
                        case 'custom_field':
                        case 'meta_field':
                            $table_body[] = (isset($product['custom_field'][$field_encoded])) ? $product['custom_field'][$field_encoded][0] : '';
                            break;
                        case 'taxonomy':
                        case 'attribute':
                            if (in_array($field, ['product_cat', 'product_tag'])) {
                                $wc_exporter = new \WC_Product_CSV_Exporter();
                                $table_body[] = (!empty($wc_exporter) && !empty($product['taxonomy'][$field_encoded])) ? $wc_exporter->format_term_ids($product['taxonomy'][$field_encoded], $field) : '';
                            } else {
                                $default_attributes = $product_object->get_default_attributes();
                                $attributes = $product_object->get_attributes();
                                if (!empty($attributes[$field_encoded])) {
                                    $attribute = $attributes[$field_encoded];
                                    if (is_a($attribute, 'WC_Product_Attribute')) {
                                        if ($attribute->is_taxonomy()) {
                                            $terms = $attribute->get_terms();
                                            $values = array();
                                            foreach ($terms as $term) {
                                                $values[] = $term->name;
                                            }
                                            $value = $this->implode_values($values);
                                            $global = 1;
                                        } else {
                                            $value = $this->implode_values($attribute->get_options());
                                            $global = 0;
                                        }
                                        $visible = $attribute->get_visible();
                                    } else {
                                        if (0 === strpos($field_encoded, 'pa_')) {
                                            $option_term = get_term_by('slug', $attribute, $field_encoded);
                                            $value = $option_term && !is_wp_error($option_term) ? str_replace(',', '\\,', $option_term->name) : str_replace(',', '\\,', $attribute);
                                            $global = 1;
                                        } else {
                                            $value = str_replace(',', '\\,', $attribute);
                                            $global = 0;
                                        }
                                        $visible = '';
                                    }

                                    $table_body[] = (!empty($column_item['label'])) ? $column_item['label'] : $field;
                                    $table_body[] = $value;
                                    $table_body[] = $visible;
                                    $table_body[] = $global;
                                    $table_body[] = (!empty($default_attributes[$field_encoded])) ? $default_attributes[$field_encoded] : '';
                                } else {
                                    $table_body[] = "";
                                    $table_body[] = "";
                                    $table_body[] = "";
                                    $table_body[] = "";
                                    $table_body[] = "";
                                }
                            }
                            break;
                        default:
                            switch ($field) {
                                case "downloadable_files":
                                    if (!empty($product[$field]) && is_array($product[$field])) {
                                        foreach ($product[$field] as $download) {
                                            if ($download instanceof \WC_Product_Download) {
                                                $table_body[] = $download->get_name();
                                                $table_body[] = $download->get_file();
                                            } else {
                                                $table_body[] = "";
                                                $table_body[] = "";
                                            }
                                        }
                                    } else {
                                        $table_body[] = "";
                                        $table_body[] = "";
                                    }
                                    break;
                                case "image_id":
                                    $image = wp_get_attachment_image_src($product[$field]['id'], 'original');
                                    $table_body[] = isset($image[0]) ? $image[0] : '';
                                    break;
                                case "status":
                                    $table_body[] = (!empty($product[$field]) && $product[$field] == 'publish') ? '1' : '-1';
                                    break;
                                case "stock_status":
                                    $table_body[] = (!empty($product[$field]) && $product[$field] == 'instock') ? '1' : '0';
                                    break;
                                case "tax_class":
                                    if ($product['type'] == 'variation') {
                                        $table_body[] = 'parent';
                                    } else {
                                        $table_body[] = (!empty($product[$field])) ? $product[$field] : '';
                                    }
                                    break;
                                case "featured":
                                    $table_body[] = (!empty($product[$field])) ? '1' : '0';
                                    break;
                                default:
                                    if (is_array($product[$field])) {
                                        $table_body[] = (!empty($product[$field])) ? implode(',', $product[$field]) : '';
                                    } else {
                                        $table_body[] = (!empty($product[$field])) ? $product[$field] : '';
                                    }
                                    break;
                            }
                            break;
                    }
                }
            }
        }

        $this->table_body[] = $table_body;
    }

    private function set_header()
    {
        $file_name = "wcbel-product-export-" . time() . '.csv';
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");
        $this->export_file = fopen('php://output', 'w');
        fwrite($this->export_file, chr(239) . chr(187) . chr(191)); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
    }

    private function set_table_header()
    {
        $attribute_counter = 1;
        $download_counter = 1;
        $table_header = [];

        $table_header[] = 'ID';
        $table_header[] = 'Type';
        $table_header[] = 'Name';
        $table_header[] = 'Parent';

        foreach ($this->columns as $field => $column) {
            if (!empty($column['field_type']) && !in_array($field, $this->except_fields())) {
                $label = $this->get_field_label($field);
                switch ($column['field_type']) {
                    case 'custom_field':
                    case 'meta_field':
                        $table_header[] = "Meta: {$field}";
                        break;
                    case 'taxonomy':
                    case 'attribute':
                        if (in_array($field, ['product_cat', 'product_tag'])) {
                            $table_header[] = (!empty($label)) ? $label : $column['label'];
                        } else {
                            $table_header[] = "Attribute {$attribute_counter} name";
                            $table_header[] = "Attribute {$attribute_counter} value(s)";
                            $table_header[] = "Attribute {$attribute_counter} visible";
                            $table_header[] = "Attribute {$attribute_counter} global";
                            $table_header[] = "Attribute {$attribute_counter} default";
                            $attribute_counter++;
                        }
                        break;
                    default:
                        if ($field == 'downloadable_files') {
                            $table_header[] = "downloads:name" . $download_counter;
                            $table_header[] = "downloads:url" . $download_counter;
                            $download_counter++;
                        } else if ($field == 'stock_quantity') {
                            $table_header[] = 'Stock';
                        } else if ($field == 'reviews_allowed') {
                            $table_header[] = 'Allow customer reviews?';
                        } else {
                            $table_header[] = (!empty($label)) ? $label : $column['label'];
                        }
                        break;
                }
            }
        }

        $this->table_header = $table_header;
    }

    private function get_field_label($field)
    {
        return (!empty($this->field_labels[$field])) ? $this->field_labels[$field] : null;
    }

    private function except_fields()
    {
        return [
            'date_created',
            'post_parent',
            'product_type',
            'gallery_image_ids',
            'slug',
            'downloadable',
            'post_author',
        ];
    }

    private function get_field_labels()
    {
        return [
            'image_id' => 'Images',
            'status' => 'Published',
            '_product_url' => 'External URL',
            'catalog_visibility' => 'Visibility in catalog',
            'date_on_sale_from' => 'Date sale price starts',
            'date_on_sale_to' => 'Date sale price ends',
            'sold_individually' => 'Sold individually?',
            'stock_status' => 'In stock?',
            'backorders' => 'Backorders allowed?',
            'upsell_ids' => 'Upsells',
            'download_expiry' => 'Download expiry days',
            'menu_order' => 'Position',
            'product_cat' => 'Categories',
            'product_tag' => 'Tags',
            'featured' => 'Is featured?',
        ];
    }

    private function get_product_meta_keys()
    {
        global $wpdb;
        $query = "
        SELECT DISTINCT($wpdb->postmeta.meta_key)
        FROM $wpdb->posts
        LEFT JOIN $wpdb->postmeta
        ON $wpdb->posts.ID = $wpdb->postmeta.post_id
        WHERE $wpdb->posts.post_type = '%s'";

        $meta_keys = $wpdb->get_col($wpdb->prepare($query, 'product')); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $output = [];
        if (!empty($meta_keys) && is_array($meta_keys)) {
            $except_meta_keys = $this->except_meta_keys();
            foreach ($meta_keys as $meta_key) {
                if (!in_array($meta_key, $except_meta_keys)) {
                    $output[$meta_key] = [
                        'name' => $meta_key,
                        'field_type' => 'custom_field',
                        'label' => $meta_key,
                    ];
                }
            }
        }

        return $output;
    }

    private function except_meta_keys()
    {
        return [
            '_regular_price',
            '_sale_price',
            'total_sales',
            '_tax_status',
            '_tax_class',
            '_manage_stock',
            '_backorders',
            '_sold_individually',
            '_virtual',
            '_downloadable',
            '_download_limit',
            '_download_expiry',
            '_thumbnail_id',
            '_stock',
            '_stock_status',
            '_wc_average_rating',
            '_price',
            '_edit_lock',
            '_edit_last',
            '_children',
            '_product_attributes',
            '_default_attributes',
            '_wc_review_count',
            '_product_version',
        ];
    }

    private function implode_values($values)
    {
        $values_to_implode = array();

        foreach ($values as $value) {
            $value = (string) is_scalar($value) ? $value : '';
            $values_to_implode[] = str_replace(',', '\\,', $value);
        }

        return implode(', ', $values_to_implode);
    }
}
