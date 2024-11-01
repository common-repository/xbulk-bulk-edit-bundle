<?php

namespace iwbvel\classes\repositories;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\helpers\Meta_Fields;

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
        $this->columns_option_name = "iwbvel_column_fields";
        $this->active_columns_option_name = 'iwbvel_active_columns';
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
                'title' => esc_attr__('Product Title', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
        return update_option('iwbvel_column_fields', $fields);
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

        return apply_filters('iwbvel_column_fields', [
            'post_parent' => [
                'label' => __('Parent', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => false,
                'content_type' => 'numeric_without_calculator',
                'allowed_type' => ['variation'],
                'field_type' => 'general',
            ],
            'image_id' => [
                'name' => 'image_id',
                'label' => __('Thumb', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'gallery_image_ids' => [
                'name' => 'gallery_image_ids',
                'label' => __('Gallery', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'gallery',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'slug' => [
                'name' => 'slug',
                'label' => __('Slug', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'description' => [
                'name' => 'description',
                'label' => __('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'short_description' => [
                'name' => 'short_description',
                'label' => __('Short Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'status' => [
                'name' => 'status',
                'label' => __('Status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $product_statuses,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'product_type' => [
                'name' => 'product_type',
                'label' => __('Product Type', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_types(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            '_product_url' => [
                'name' => '_product_url',
                'label' => __('Product URL', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'allowed_type' => ['external'],
                'field_type' => 'general',
                'update_type' => 'meta_field',
            ],
            '_button_text' => [
                'name' => '_button_text',
                'label' => __('Button Text', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'meta_field',
            ],
            'catalog_visibility' => [
                'name' => 'catalog_visibility',
                'label' => __('Catalog Visibility', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_visibility_options(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'featured' => [
                'name' => 'featured',
                'label' => __('Featured', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'regular_price' => [
                'name' => 'regular_price',
                'label' => __('Regular price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sale_price' => [
                'name' => 'sale_price',
                'label' => __('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'sale_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_from' => [
                'name' => 'date_on_sale_from',
                'label' => __('Sale time from', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_on_sale_to' => [
                'name' => 'date_on_sale_to',
                'label' => __('Sale time to', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            '_children' => [
                'name' => '_children',
                'label' => __('Grouped Products', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['grouped'],
                'field_type' => 'general',
                'update_type' => 'meta_field',
            ],
            'downloadable' => [
                'name' => 'downloadable',
                'label' => __('Downloadable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variation', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'downloadable_files' => [
                'name' => 'downloadable_files',
                'label' => __('Downloadable Files', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select_files',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_limit' => [
                'name' => 'download_limit',
                'label' => __('Download limit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'download_expiry' => [
                'name' => 'download_expiry',
                'label' => __('Download expiry', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'tax_status' => [
                'name' => 'tax_status',
                'label' => __('Tax status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Tax class', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $tax_classes,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'sku' => [
                'name' => 'sku',
                'label' => __('SKU', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'manage_stock' => [
                'name' => 'manage_stock',
                'label' => __('Manage stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_quantity' => [
                'name' => 'stock_quantity',
                'label' => __('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'stock_status' => [
                'name' => 'stock_status',
                'label' => __('Stock status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Allow backorders', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_backorder_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'sold_individually' => [
                'name' => 'sold_individually',
                'label' => __('Sold individually', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped'],
                'field_type' => 'inventory',
                'update_type' => 'woocommerce_field',
            ],
            'weight' => [
                'name' => 'weight',
                'label' => __('Weight', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'length' => [
                'name' => 'length',
                'label' => __('Length', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'width' => [
                'name' => 'width',
                'label' => __('Width', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'height' => [
                'name' => 'height',
                'label' => __('Height', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_class' => [
                'name' => 'shipping_class',
                'label' => __('Shipping class', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $shipping_classes,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'upsell_ids' => [
                'name' => 'upsell_ids',
                'label' => __('Up-sells', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'linked_products',
                'update_type' => 'woocommerce_field',
            ],
            'cross_sell_ids' => [
                'name' => 'cross_sell_ids',
                'label' => __('Cross-sells', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'linked_products',
                'update_type' => 'woocommerce_field',
            ],
            'purchase_note' => [
                'name' => 'purchase_note',
                'label' => __('Purchase note', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'advanced',
                'update_type' => 'woocommerce_field',
            ],
            'menu_order' => [
                'name' => 'menu_order',
                'label' => __('Menu order', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'advanced',
                'update_type' => 'woocommerce_field',
            ],
            'reviews_allowed' => [
                'name' => 'reviews_allowed',
                'label' => __('Reviews allowed', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'advanced',
                'update_type' => 'woocommerce_field',
            ],
            'virtual' => [
                'name' => 'virtual',
                'label' => __('Virtual', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'checkbox_dual_mode',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'post_author' => [
                'name' => 'post_author',
                'label' => __('Author', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $authors,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'wp_posts_field',
            ],
            'total_sales' => [
                'name' => 'total_sales',
                'label' => __('Total sales', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'review_count' => [
                'name' => 'review_count',
                'label' => __('Review count', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'average_rating' => [
                'name' => 'average_rating',
                'label' => __('Average rating', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_created' => [
                'name' => 'date_created',
                'label' => __('Date Published', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Short Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Short Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Product Type', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Product Type', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Regular Price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Regular Price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('SKU', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('SKU', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Manage Stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Manage Stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Stock status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Stock status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Gallery', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Gallery', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Regular Price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Regular Price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Sale Price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Sale Price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Sale time from', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Sale time from', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Sale time to', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Sale time to', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('SKU', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('SKU', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Manage stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Manage stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Stock status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Stock status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Virtual', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Virtual', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Downloadable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Downloadable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Downloadable Files', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Downloadable Files', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Download limit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Download limit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Download expiry', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Download expiry', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Tax class', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Tax class', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Allow backorders', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Allow backorders', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Weight', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Weight', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Length', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Length', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Width', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Width', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Height', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Height', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Parent', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Parent', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Manage stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Manage stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Stock quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Stock status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Stock status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Regular price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Regular price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Sale time from', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Sale time from', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Sale time to', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Sale time to', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Downloadable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Downloadable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Downloadable Files', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Downloadable Files', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Download limit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Download limit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Download expiry', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Download expiry', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Up-sells', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Up-sells', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Cross-sells', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Cross-sells', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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
                'label' => __('Grouped Products', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'title' => __('Grouped Products', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
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

    public function set_wc_min_max_quantities_fields($fields)
    {
        $plugin_fields = $this->get_wc_min_max_quantities_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_yith_min_max_quantities_fields($fields)
    {
        $plugin_fields = $this->get_yith_min_max_quantities_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_yith_vendors_fields($fields)
    {
        $plugin_fields = $this->get_yith_vendors_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_wc_vendors_fields($fields)
    {
        $plugin_fields = $this->get_wc_vendors_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_yith_cost_of_goods_fields($fields)
    {
        $plugin_fields = $this->get_yith_cost_of_goods_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_wc_cost_of_goods_fields($fields)
    {
        $plugin_fields = $this->get_wc_cost_of_goods_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_woo_multi_currency_fields($fields)
    {
        $plugin_fields = $this->get_woo_multi_currency_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_yith_badge_management_premium_fields($fields)
    {
        $plugin_fields = $this->get_yith_badge_management_premium_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_yith_badge_management_free_fields($fields)
    {
        $plugin_fields = $this->get_yith_badge_management_free_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_wc_advanced_product_labels_fields($fields)
    {
        $plugin_fields = $this->get_wc_advanced_product_labels_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_yikes_custom_product_tabs_fields($fields)
    {
        $plugin_fields = $this->get_yikes_custom_product_tabs_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    public function set_it_wc_dynamic_pricing_fields($fields)
    {
        $plugin_fields = $this->get_it_wc_dynamic_pricing_fields();
        if (!empty($plugin_fields)) {
            foreach ($plugin_fields as $key => $items) {
                $fields[$key] = $items;
            }
        }

        return $fields;
    }

    private function get_wc_min_max_quantities_fields()
    {
        $fields['minimum_allowed_quantity'] = [
            'name' => 'minimum_allowed_quantity',
            'label' => __('Minimum quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['maximum_allowed_quantity'] = [
            'name' => 'maximum_allowed_quantity',
            'label' => __('Maximum quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['group_of_quantity'] = [
            'name' => 'group_of_quantity',
            'label' => __('Group of quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['allow_combination'] = [
            'name' => 'allow_combination',
            'label' => __('Allow combination', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['variable'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['minmax_do_not_count'] = [
            'name' => 'minmax_do_not_count',
            'label' => __('Order rules: Do not count', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['minmax_cart_exclude'] = [
            'name' => 'minmax_cart_exclude',
            'label' => __('Order rules: Exclude', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['minmax_category_group_of_exclude'] = [
            'name' => 'minmax_category_group_of_exclude',
            'label' => __('Category rules: Exclude', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['min_max_rules'] = [
            'name' => 'min_max_rules',
            'label' => __('Min/Max Rules', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['variation'],
            'field_type' => 'woocommerce_min_max_quantities',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_yith_min_max_quantities_fields()
    {
        $fields['_ywmmq_product_minimum_quantity'] = [
            'name' => '_ywmmq_product_minimum_quantity',
            'label' => __('Minimum quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'yith_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['_ywmmq_product_maximum_quantity'] = [
            'name' => '_ywmmq_product_maximum_quantity',
            'label' => __('Maximum quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'yith_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['_ywmmq_product_step_quantity'] = [
            'name' => '_ywmmq_product_step_quantity',
            'label' => __('Groups of quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'yith_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['_ywmmq_product_exclusion'] = [
            'name' => '_ywmmq_product_exclusion',
            'label' => __('Exclude product', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'yith_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['_ywmmq_product_quantity_limit_override'] = [
            'name' => '_ywmmq_product_quantity_limit_override',
            'label' => __('Override product', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'yith_min_max_quantities',
            'update_type' => 'meta_field',
        ];
        $fields['_ywmmq_product_quantity_limit_variations_override'] = [
            'name' => '_ywmmq_product_quantity_limit_variations_override',
            'label' => __('Enable variation', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable', 'external', 'variation'],
            'field_type' => 'yith_min_max_quantities',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_yith_vendors_fields()
    {
        $fields['yith_shop_vendor'] = [
            'name' => 'yith_shop_vendor',
            'label' => __('Yith vendor', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'yith_shop_vendor',
            'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
            'field_type' => 'yith_vendors',
            'update_type' => 'taxonomy',
        ];
        $fields['_product_commission'] = [
            'name' => '_product_commission',
            'label' => __('Product commission', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
            'field_type' => 'yith_vendors',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_wc_vendors_fields()
    {
        $fields['wcpv_product_vendors'] = [
            'name' => 'wcpv_product_vendors',
            'label' => __('WooCommerce vendor', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'wc_product_vendor',
            'allowed_type' => ['simple', 'variable'],
            'field_type' => 'woocommerce_vendors',
            'update_type' => 'taxonomy',
        ];
        $fields['_wcpv_product_taxes'] = [
            'name' => '_wcpv_product_taxes',
            'label' => __('Tax Handling', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'select',
            'options' => [
                '' => __('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'keep-tax' => __('Keep taxes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'pass-tax' => __('Pass taxes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
                'split-tax' => __('Split taxes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            ],
            'allowed_type' => ['simple', 'variable'],
            'field_type' => 'woocommerce_vendors',
            'update_type' => 'meta_field',
        ];
        $fields['_wcpv_product_pass_shipping'] = [
            'name' => '_wcpv_product_pass_shipping',
            'label' => __('Pass shipping', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable'],
            'field_type' => 'woocommerce_vendors',
            'update_type' => 'meta_field',
        ];
        $fields['_wcpv_product_commission'] = [
            'name' => '_wcpv_product_commission',
            'label' => __('Product commission', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variable', 'variation'],
            'field_type' => 'woocommerce_vendors',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_yith_cost_of_goods_fields()
    {
        $fields['yith_cog_cost'] = [
            'name' => 'yith_cog_cost',
            'label' => __('Yith Cost of goods', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variation', 'variable'],
            'field_type' => 'yith_cost_of_goods',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_wc_cost_of_goods_fields()
    {
        $fields['_wc_cog_cost'] = [
            'name' => '_wc_cog_cost',
            'label' => __('WC Cost of goods', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'numeric',
            'allowed_type' => ['simple', 'variation', 'variable'],
            'field_type' => 'woocommerce_cost_of_goods',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_woo_multi_currency_fields()
    {
        $fields = [];

        $woo_multi_currency_params = get_option('woo_multi_currency_params');
        if (!empty($woo_multi_currency_params) && isset($woo_multi_currency_params['enable_fixed_price']) && intval($woo_multi_currency_params['enable_fixed_price']) === 1) {
            // delete default currency
            if (!empty($woo_multi_currency_params['currency'][0])) {
                unset($woo_multi_currency_params['currency'][0]);
            }

            // get active currencies
            if (!empty($woo_multi_currency_params['currency'])) {
                if (!empty($woo_multi_currency_params['currency']) && is_array($woo_multi_currency_params['currency'])) {
                    foreach ($woo_multi_currency_params['currency'] as $currency) {
                        $fields['_regular_price_wmcp_-_' . $currency] = [
                            'name' => '_regular_price_wmcp',
                            'sub_name' => $currency,
                            'label' => esc_html__('Regular price', 'ithemelandco-woocommerce-bulk-variation-editing-lite') . '(' . esc_html($currency) . ')',
                            'editable' => true,
                            'content_type' => 'numeric',
                            'allowed_type' => ['simple', 'variation', 'variable'],
                            'field_type' => 'woo_multi_currency',
                            'update_type' => 'meta_field',
                        ];
                        $fields['_sale_price_wmcp_-_' . $currency] = [
                            'name' => '_sale_price_wmcp',
                            'sub_name' => $currency,
                            'label' => esc_html__('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite') . '(' . esc_html($currency) . ')',
                            'editable' => true,
                            'content_type' => 'numeric',
                            'allowed_type' => ['simple', 'variation', 'variable'],
                            'field_type' => 'woo_multi_currency',
                            'update_type' => 'meta_field',
                        ];
                    }
                }
            }
        }

        return $fields;
    }

    private function get_yith_badge_management_premium_fields()
    {
        $fields = [];

        $fields['_yith_wcbm_product_meta_-_id_badge'] = [
            'name' => '_yith_wcbm_product_meta',
            'sub_name' => 'id_badge',
            'label' => __('Product badge', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'yith_product_badge',
            'allowed_type' => ['simple', 'grouped', 'external', 'variable'],
            'field_type' => 'yith_badge_management',
            'update_type' => 'meta_field',
        ];
        $fields['_yith_wcbm_product_meta_-_start_date'] = [
            'name' => '_yith_wcbm_product_meta',
            'sub_name' => 'start_date',
            'label' => __('Starting date', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'date',
            'allowed_type' => ['simple', 'grouped', 'external', 'variable'],
            'field_type' => 'yith_badge_management',
            'update_type' => 'meta_field',
        ];
        $fields['_yith_wcbm_product_meta_-_end_date'] = [
            'name' => '_yith_wcbm_product_meta',
            'sub_name' => 'end_date',
            'label' => __('Ending date', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'date',
            'allowed_type' => ['simple', 'grouped', 'external', 'variable'],
            'field_type' => 'yith_badge_management',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_yith_badge_management_free_fields()
    {
        $fields = [];

        $fields['_yith_wcbm_product_meta_-_id_badge'] = [
            'name' => '_yith_wcbm_product_meta',
            'sub_name' => 'id_badge',
            'label' => __('Product badge', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'yith_product_badge',
            'allowed_type' => ['simple', 'grouped', 'external', 'variable'],
            'field_type' => 'yith_badge_management',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_wc_advanced_product_labels_fields()
    {
        $fields = [];

        $fields['ithemeland_badge'] = [
            'name' => 'ithemeland_badge',
            'label' => __('iThemeland badge', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'ithemeland_badge',
            'allowed_type' => ['simple', 'grouped', 'external', 'variable'],
            'field_type' => 'ithemeland_badge',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_yikes_custom_product_tabs_fields()
    {
        $fields = [];

        $fields['yikes_custom_product_tabs'] = [
            'name' => 'yikes_custom_product_tabs',
            'label' => __('Custom Tabs', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'yikes_custom_product_tabs',
            'allowed_type' => ['simple', 'grouped', 'external', 'variable'],
            'field_type' => 'yikes_custom_product_tabs',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }

    private function get_it_wc_dynamic_pricing_fields()
    {
        $fields = [];

        $fields['it_product_disable_discount'] = [
            'name' => 'it_product_disable_discount',
            'label' => __('Disable Discount', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variation'],
            'field_type' => 'it_wc_dynamic_pricing',
            'update_type' => 'meta_field',
        ];

        $fields['pricing_rules_product'] = [
            'name' => 'pricing_rules_product',
            'label' => __('Price For Each Role', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'it_pricing_rules_product',
            'allowed_type' => ['simple', 'variation'],
            'field_type' => 'it_wc_dynamic_pricing',
            'update_type' => 'meta_field',
        ];

        $fields['it_product_hide_price_unregistered'] = [
            'name' => 'it_product_hide_price_unregistered',
            'label' => __('Hide price (unregistered)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'checkbox_dual_mode',
            'allowed_type' => ['simple', 'variable'],
            'field_type' => 'it_wc_dynamic_pricing',
            'update_type' => 'meta_field',
        ];

        $fields['it_pricing_product_price_user_role'] = [
            'name' => 'it_pricing_product_price_user_role',
            'label' => __('Hide Price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'it_wc_dynamic_pricing_select_roles',
            'allowed_type' => ['simple', 'variable'],
            'field_type' => 'it_wc_dynamic_pricing',
            'update_type' => 'meta_field',
        ];

        $fields['it_pricing_product_add_to_cart_user_role'] = [
            'name' => 'it_pricing_product_add_to_cart_user_role',
            'label' => __('Hide Add to cart', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'it_wc_dynamic_pricing_select_roles',
            'allowed_type' => ['simple', 'variable'],
            'field_type' => 'it_wc_dynamic_pricing',
            'update_type' => 'meta_field',
        ];

        $fields['it_pricing_product_hide_user_role'] = [
            'name' => 'it_pricing_product_hide_user_role',
            'label' => __('Hide Product', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'it_wc_dynamic_pricing_select_roles',
            'allowed_type' => ['simple', 'variable'],
            'field_type' => 'it_wc_dynamic_pricing',
            'update_type' => 'meta_field',
        ];

        $fields['it_wc_dynamic_pricing_all_fields'] = [
            'name' => 'it_wc_dynamic_pricing_all_fields',
            'label' => __('All Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'),
            'editable' => true,
            'content_type' => 'it_wc_dynamic_pricing_all_fields',
            'allowed_type' => ['simple', 'variable', 'variation'],
            'field_type' => 'it_wc_dynamic_pricing',
            'update_type' => 'meta_field',
        ];

        return $fields;
    }
}
