<?php

namespace wcbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Meta_Fields;

class Column
{
    const SHOW_ID_COLUMN = true;
    const DEFAULT_PROFILE_NAME = 'default';

    private $columns_option_name;
    private $active_columns_option_name;
    private $deactivated_columns;

    public function __construct()
    {
        $this->deactivated_columns = [];
        $this->columns_option_name = "wcbel_column_fields";
        $this->active_columns_option_name = 'wcbel_active_columns';
    }

    public function update(array $data)
    {
        if (!isset($data['key'])) {
            return false;
        }

        $presets = $this->get_presets();
        $presets[$data['key']] = $data;
        return update_option($this->columns_option_name, $presets);
    }

    public function delete($preset_key)
    {
        $presets = $this->get_presets();
        if (is_array($presets) && array_key_exists($preset_key, $presets)) {
            unset($presets[$preset_key]);
        }
        return update_option($this->columns_option_name, $presets);
    }

    public function get_preset($preset_key)
    {
        $presets = $this->get_presets();
        return (isset($presets[$preset_key])) ? $presets[$preset_key] : false;
    }

    public function get_presets()
    {
        return get_option($this->columns_option_name);
    }

    public function get_presets_fields()
    {
        $presets_fields = [];
        $presets = $this->get_presets();
        if (!empty($presets)) {
            foreach ($presets as $key => $preset) {
                $presets_fields[$key] = (!empty($preset['checked'])) ? $preset['checked'] : [];
            }
        }

        return $presets_fields;
    }

    public function set_active_columns(string $profile_name, array $columns, string $option_name = "")
    {
        $option_name = (!empty($option_name)) ? esc_sql($option_name) : $this->active_columns_option_name;
        return update_option($option_name, ['name' => $profile_name, 'fields' => $columns]);
    }

    public function get_active_columns()
    {
        return get_option($this->active_columns_option_name);
    }

    public function delete_active_columns()
    {
        return delete_option($this->active_columns_option_name);
    }

    public function has_column_fields()
    {
        $columns = get_option($this->columns_option_name);
        return !empty($columns['default']['fields']);
    }

    public static function get_static_columns()
    {
        return [
            'title' => [
                'field' => 'title',
                'title' => esc_attr__('Product Title', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
            ],
        ];
    }

    public function get_deactivated_columns()
    {
        return $this->deactivated_columns;
    }

    private function set_deactivated_columns($columns)
    {
        if (!empty($columns) && is_array($columns)) {
            foreach ($columns as $column) {
                $this->deactivated_columns[] = sanitize_text_field($column);
            }
        }
    }

    public static function get_columns_title()
    {
        return [
            'stock_quantity' => "Set Stock quantity. If this is a variable product this <br> value will be used to control stock for all variations, unless you define stock <br>at variation level. <br> Note: if to set count of products in Stock quantity, Manage stock option automatically set as TRUE!",
            'stock_status' => 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend. Note: Does Not work if the product Manage stock option is not activated!',
            'date_on_sale_from' => 'The sale will start at 00:00:00 of "From" date and end at 23:59:59 of "To" date.',
            'tax_status' => 'Define whether or not the entire product <br> is taxable, or just the cost of shipping it.',
            'tax_class' => 'Choose a tax class for this product. Tax <br> classes are used to apply different tax rates specific <br> to certain types of product.',
            'sku' => "SKU refers to a Stock-keeping unit, a unique <br> identifier  for each distinct product and <br> service that can be purchased.",
            'backorders' => 'If managing stock, this controls whether or not <br> backorders are allowed. If enabled, stock quantity can go below 0.',
            'shipping_class' => 'Shipping classes are used by certain shipping <br> methods to group similar products.',
            'upsell_ids' => 'Upsells are products which you recommend <br> instead of the currently viewed product, for example <br>, products that are more profitable or better quality or more expensive.',
            'cross_sell_ids' => 'Cross-sells are products which you promote <br> in the cart, based on the current product.',
            'purchase_note' => 'Enter an optional note to send the customer <br> after purchase.',
            'download_limit' => 'Leave blank for unlimited re-downloads.',
            'download_expiry' => 'Enter the number of days before a download <br> link expires, or leave blank.',
            'sold_individually' => 'Enable this to only allow one of this <br> item to be bought in a single order',
            'product_url' => 'Enter the external URL to the product.',
            'button_text' => 'This text will be shown on the button <br> linking to the external product.',
            'catalog_visibility' => 'This setting determines which shop <br> pages products will be listed on',
            'virtual' => 'Virtual products are intangible and are not shipped.',
            'downloadable' => 'Downloadable products give access to a file upon purchase.',
        ];
    }

    public function update_meta_field_items()
    {
        $presets = $this->get_presets();
        $meta_fields = (new Meta_Field())->get();
        if (!empty($presets)) {
            foreach ($presets as $preset) {
                if (!empty($preset['fields'])) {
                    foreach ($preset['fields'] as $field) {
                        if (isset($field['field_type'])) {
                            if (isset($meta_fields[$field['name']])) {
                                $preset['fields'][$field['name']]['content_type'] = Meta_Fields::get_meta_field_type($meta_fields[$field['name']]['main_type'], $meta_fields[$field['name']]['sub_type']);
                                $this->update($preset);
                            }
                        }
                    }
                }
            }
        }
    }

    public function set_default_columns()
    {
        $fields['default'] = [
            'name' => 'Default',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'fields' => $this->get_default_columns_default(),
            'checked' => array_keys($this->get_default_columns_default()),
        ];
        $fields['variations'] = [
            'name' => 'For variations fields only',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'variations',
            'fields' => $this->get_default_columns_variations(),
            'checked' => array_keys($this->get_default_columns_variations()),
        ];
        $fields['stock'] = [
            'name' => 'Stock',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'stock',
            'fields' => $this->get_default_columns_stock(),
            'checked' => array_keys($this->get_default_columns_stock()),
        ];
        $fields['prices'] = [
            'name' => 'Prices',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'prices',
            'fields' => $this->get_default_columns_prices(),
            'checked' => array_keys($this->get_default_columns_prices()),
        ];
        $fields['attachments'] = [
            'name' => 'Downloads, Cross-sells, Up-sells, Grouped',
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'attachments',
            'fields' => $this->get_default_columns_attachments(),
            'checked' => array_keys($this->get_default_columns_attachments()),
        ];
        return update_option('wcbel_column_fields', $fields);
    }

    public function get_grouped_fields()
    {
        $grouped_fields = [];
        $fields = $this->get_fields();
        if (!empty($fields)) {
            foreach ($fields as $key => $field) {
                if (isset($field['field_type'])) {
                    switch ($field['field_type']) {
                        case 'general':
                            $grouped_fields['General'][$key] = $field;
                            break;
                        case 'advanced':
                            $grouped_fields['Advanced'][$key] = $field;
                            break;
                        case 'linked_products':
                            $grouped_fields['Linked Products'][$key] = $field;
                            break;
                        case 'shipping':
                            $grouped_fields['Shipping'][$key] = $field;
                            break;
                        case 'inventory':
                            $grouped_fields['Inventory'][$key] = $field;
                            break;
                        case 'taxonomy':
                            $grouped_fields['Taxonomies'][$key] = $field;
                            break;
                        case 'attribute':
                            $grouped_fields['Attributes'][$key] = $field;
                            break;
                        case 'custom_field':
                            $grouped_fields['Custom Fields'][$key] = $field;
                            break;
                        case 'woocommerce_min_max_quantities':
                            $grouped_fields['compatibles']['WooCommerce Min/Max quantities'][$key] = $field;
                            break;
                        case 'yith_min_max_quantities':
                            $grouped_fields['compatibles']['Yith Min/Max quantities'][$key] = $field;
                            break;
                        case 'woocommerce_vendors':
                            $grouped_fields['compatibles']['WooCommerce vendors'][$key] = $field;
                            break;
                        case 'yith_vendors':
                            $grouped_fields['compatibles']['Yith vendors'][$key] = $field;
                            break;
                        case 'yith_cost_of_goods':
                            $grouped_fields['compatibles']['Yith Cost of goods'][$key] = $field;
                            break;
                        case 'woocommerce_cost_of_goods':
                            $grouped_fields['compatibles']['Woocommerce Cost of goods'][$key] = $field;
                            break;
                        case 'woo_multi_currency':
                            $grouped_fields['compatibles']['Woo multi currency'][$key] = $field;
                            break;
                        case 'yith_badge_management':
                            $grouped_fields['compatibles']['Yith badge management'][$key] = $field;
                            break;
                        case 'ithemeland_badge':
                            $grouped_fields['compatibles']['iThemeland badge'][$key] = $field;
                            break;
                        case 'yikes_custom_product_tabs':
                            $grouped_fields['compatibles']['Yikes Custom product tabs'][$key] = $field;
                            break;
                        case 'it_wc_dynamic_pricing':
                            $grouped_fields['compatibles']['iThemeland WooCommerce Dynamic Pricing'][$key] = $field;
                            break;
                    }
                } else {
                    $grouped_fields['General'][$key] = $field;
                }
            }
        }
        return $grouped_fields;
    }

    public function get_fields()
    {
        $product_repository = Product::get_instance();
        $tax_classes = $product_repository->get_tax_classes();
        $product_statuses = $product_repository->get_product_statuses();
        $shipping_items = wc()->shipping()->get_shipping_classes();
        $shipping_classes = [
            -1 => 'No Shipping Class',
        ];
        if (!empty($shipping_items)) {
            foreach ($shipping_items as $shipping_class) {
                $shipping_classes[$shipping_class->term_id] = $shipping_class->name;
            }
        }

        $users = get_users();
        $authors = [];
        if (!empty($users)) {
            foreach ($users as $user_item) {
                $authors[$user_item->ID] = $user_item->user_login;
            }
        }

        return apply_filters('wcbel_column_fields', [
            'post_parent' => [
                'label' => __('Parent', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => false,
                'content_type' => 'numeric_without_calculator',
                'allowed_type' => ['variation'],
                'field_type' => 'general',
            ],
            'image_id' => [
                'name' => 'image_id',
                'label' => __('Thumb', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'gallery_image_ids' => [
                'name' => 'gallery_image_ids',
                'label' => __('Gallery', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'gallery',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'slug' => [
                'name' => 'slug',
                'label' => __('Slug', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'description' => [
                'name' => 'description',
                'label' => __('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'short_description' => [
                'name' => 'short_description',
                'label' => __('Short Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'status' => [
                'name' => 'status',
                'label' => __('Status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $product_statuses,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'product_type' => [
                'name' => 'product_type',
                'label' => __('Product Type', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_types(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            '_product_url' => [
                'name' => '_product_url',
                'label' => __('Product URL', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'text',
                'allowed_type' => ['external'],
                'field_type' => 'general',
                'update_type' => 'meta_field',
            ],
            '_button_text' => [
                'name' => '_button_text',
                'label' => __('Button Text', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'meta_field',
            ],
            'catalog_visibility' => [
                'name' => 'catalog_visibility',
                'label' => __('Catalog Visibility', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_visibility_options(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'featured' => [
                'name' => 'featured',
                'label' => __('Featured', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'regular_price' => [
                'name' => 'regular_price',
                'label' => __('Regular price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sale_price' => [
                'name' => 'sale_price',
                'label' => __('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'sale_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_from' => [
                'name' => 'date_on_sale_from',
                'label' => __('Sale time from', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_to' => [
                'name' => 'date_on_sale_to',
                'label' => __('Sale time to', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            '_children' => [
                'name' => '_children',
                'label' => __('Grouped Products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['grouped'],
                'field_type' => 'general',
                'update_type' => 'meta_field',
            ],
            'downloadable' => [
                'name' => 'downloadable',
                'label' => __('Downloadable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variation', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'downloadable_files' => [
                'name' => 'downloadable_files',
                'label' => __('Downloadable Files', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_files',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_limit' => [
                'name' => 'download_limit',
                'label' => __('Download limit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_expiry' => [
                'name' => 'download_expiry',
                'label' => __('Download expiry', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'tax_status' => [
                'name' => 'tax_status',
                'label' => __('Tax status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => [
                    'taxable' => 'Taxable',
                    'shipping' => 'Shipping Only',
                    'none' => 'None',
                ],
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'tax_class' => [
                'name' => 'tax_class',
                'label' => __('Tax class', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $tax_classes,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sku' => [
                'name' => 'sku',
                'label' => __('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'manage_stock' => [
                'name' => 'manage_stock',
                'label' => __('Manage stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_quantity' => [
                'name' => 'stock_quantity',
                'label' => __('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'low_stock_amount' => [
                'name' => 'low_stock_amount',
                'label' => __('Low stock threshold', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_status' => [
                'name' => 'stock_status',
                'label' => __('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_stock_status_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'backorders' => [
                'name' => 'backorders',
                'label' => __('Allow backorders', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_backorder_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'sold_individually' => [
                'name' => 'sold_individually',
                'label' => __('Sold individually', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'weight' => [
                'name' => 'weight',
                'label' => __('Weight', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'length' => [
                'name' => 'length',
                'label' => __('Length', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'width' => [
                'name' => 'width',
                'label' => __('Width', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'height' => [
                'name' => 'height',
                'label' => __('Height', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_class' => [
                'name' => 'shipping_class',
                'label' => __('Shipping class', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $shipping_classes,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'upsell_ids' => [
                'name' => 'upsell_ids',
                'label' => __('Up-sells', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'linked_products',
                'update_type' => 'woocommerce_field',
            ],
            'cross_sell_ids' => [
                'name' => 'cross_sell_ids',
                'label' => __('Cross-sells', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'linked_products',
                'update_type' => 'woocommerce_field',
            ],
            'purchase_note' => [
                'name' => 'purchase_note',
                'label' => __('Purchase note', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'advanced',
                'update_type' => 'woocommerce_field',
            ],
            'menu_order' => [
                'name' => 'menu_order',
                'label' => __('Menu order', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'advanced',
                'update_type' => 'woocommerce_field',
            ],
            'reviews_allowed' => [
                'name' => 'reviews_allowed',
                'label' => __('Reviews allowed', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'advanced',
                'update_type' => 'woocommerce_field',
            ],
            'virtual' => [
                'name' => 'virtual',
                'label' => __('Virtual', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'post_author' => [
                'name' => 'post_author',
                'label' => __('Author', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $authors,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'wp_posts_field',
            ],
            'total_sales' => [
                'name' => 'total_sales',
                'label' => __('Total sales', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'review_count' => [
                'name' => 'review_count',
                'label' => __('Review count', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'average_rating' => [
                'name' => 'average_rating',
                'label' => __('Average rating', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_created' => [
                'name' => 'date_created',
                'label' => __('Date Published', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time_picker',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
        ]);
    }

    public function set_default_active_columns()
    {
        return $this->set_active_columns(self::DEFAULT_PROFILE_NAME, self::get_default_columns_default());
    }

    public static function get_default_columns_name()
    {
        return [
            'default',
            'variations',
            'stock',
            'prices',
            'attachments',
        ];
    }

    public static function get_default_columns_default()
    {
        $product_repository = Product::get_instance();
        $product_statuses = $product_repository->get_product_statuses();

        return [
            'image_id' => [
                'name' => 'image_id',
                'label' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'description' => [
                'name' => 'description',
                'label' => __('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'short_description' => [
                'name' => 'short_description',
                'label' => __('Short Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Short Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'product_type' => [
                'name' => 'product_type',
                'label' => __('Product Type', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Product Type', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_types(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'status' => [
                'name' => 'status',
                'label' => __('Status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $product_statuses,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'regular_price' => [
                'name' => 'regular_price',
                'label' => __('Regular Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Regular Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sale_price' => [
                'name' => 'sale_price',
                'label' => __('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'sale_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sku' => [
                'name' => 'sku',
                'label' => __('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'manage_stock' => [
                'name' => 'manage_stock',
                'label' => __('Manage Stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Manage Stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_quantity' => [
                'name' => 'stock_quantity',
                'label' => __('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'low_stock_amount' => [
                'name' => 'low_stock_amount',
                'label' => __('Low stock threshold', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Low stock threshold', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_status' => [
                'name' => 'stock_status',
                'label' => __('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_stock_status_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'gallery_image_ids' => [
                'name' => 'gallery_image_ids',
                'label' => __('Gallery', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Gallery', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'gallery',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
        ];
    }

    public static function get_default_columns_variations()
    {
        $product_repository = Product::get_instance();
        $tax_classes = $product_repository->get_tax_classes();
        return [
            'image_id' => [
                'name' => 'image_id',
                'label' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'stock_quantity' => [
                'name' => 'stock_quantity',
                'label' => __('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'low_stock_amount' => [
                'name' => 'low_stock_amount',
                'label' => __('Low stock threshold', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Low stock threshold', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'description' => [
                'name' => 'description',
                'label' => __('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'regular_price' => [
                'name' => 'regular_price',
                'label' => __('Regular Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Regular Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sale_price' => [
                'name' => 'sale_price',
                'label' => __('Sale Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Sale Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'sale_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_from' => [
                'name' => 'date_on_sale_from',
                'label' => __('Sale time from', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Sale time from', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_to' => [
                'name' => 'date_on_sale_to',
                'label' => __('Sale time to', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Sale time to', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sku' => [
                'name' => 'sku',
                'label' => __('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'manage_stock' => [
                'name' => 'manage_stock',
                'label' => __('Manage stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Manage stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_status' => [
                'name' => 'stock_status',
                'label' => __('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_stock_status_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'virtual' => [
                'name' => 'virtual',
                'label' => __('Virtual', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Virtual', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'downloadable' => [
                'name' => 'downloadable',
                'label' => __('Downloadable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Downloadable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variation', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'downloadable_files' => [
                'name' => 'downloadable_files',
                'label' => __('Downloadable Files', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Downloadable Files', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_files',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_limit' => [
                'name' => 'download_limit',
                'label' => __('Download limit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Download limit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_expiry' => [
                'name' => 'download_expiry',
                'label' => __('Download expiry', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Download expiry', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'tax_class' => [
                'name' => 'tax_class',
                'label' => __('Tax class', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Tax class', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $tax_classes,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'backorders' => [
                'name' => 'backorders',
                'label' => __('Allow backorders', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Allow backorders', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_backorder_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'weight' => [
                'name' => 'weight',
                'label' => __('Weight', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Weight', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'length' => [
                'name' => 'length',
                'label' => __('Length', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Length', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'width' => [
                'name' => 'width',
                'label' => __('Width', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Width', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'height' => [
                'name' => 'height',
                'label' => __('Height', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Height', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'post_parent' => [
                'name' => 'post_parent',
                'label' => __('Parent', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Parent', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => false,
                'content_type' => 'numeric_without_calculator',
                'allowed_type' => ['variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
            ],
        ];
    }

    public static function get_default_columns_stock()
    {
        return [
            'image_id' => [
                'name' => 'image_id',
                'label' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'manage_stock' => [
                'name' => 'manage_stock',
                'label' => __('Manage stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Manage stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_quantity' => [
                'name' => 'stock_quantity',
                'label' => __('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Stock quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'low_stock_amount' => [
                'name' => 'low_stock_amount',
                'label' => __('Low stock threshold', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Low stock threshold', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_status' => [
                'name' => 'stock_status',
                'label' => __('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Stock status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_stock_status_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
        ];
    }

    public static function get_default_columns_prices()
    {
        return [
            'image_id' => [
                'name' => 'image_id',
                'label' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'regular_price' => [
                'name' => 'regular_price',
                'label' => __('Regular price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Regular price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sale_price' => [
                'name' => 'sale_price',
                'label' => __('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'sale_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_from' => [
                'name' => 'date_on_sale_from',
                'label' => __('Sale time from', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Sale time from', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_to' => [
                'name' => 'date_on_sale_to',
                'label' => __('Sale time to', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Sale time to', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
        ];
    }

    public static function get_default_columns_attachments()
    {
        return [
            'image_id' => [
                'name' => 'image_id',
                'label' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Thumbnail', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'downloadable' => [
                'name' => 'downloadable',
                'label' => __('Downloadable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Downloadable', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variation', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'downloadable_files' => [
                'name' => 'downloadable_files',
                'label' => __('Downloadable Files', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Downloadable Files', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_files',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_limit' => [
                'name' => 'download_limit',
                'label' => __('Download limit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Download limit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_expiry' => [
                'name' => 'download_expiry',
                'label' => __('Download expiry', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Download expiry', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'upsell_ids' => [
                'name' => 'upsell_ids',
                'label' => __('Up-sells', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Up-sells', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'linked_products',
                'update_type' => 'woocommerce_field',
            ],
            'cross_sell_ids' => [
                'name' => 'cross_sell_ids',
                'label' => __('Cross-sells', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Cross-sells', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'linked_products',
                'update_type' => 'woocommerce_field',
            ],
            '_children' => [
                'name' => '_children',
                'label' => __('Grouped Products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'title' => __('Grouped Products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['grouped'],
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'meta_field',
            ],
        ];
    }
}
