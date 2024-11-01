<?php

namespace wcbef\classes\helpers;

use \wcbef\classes\repositories\Product;

class Columns
{
    public static function get_product_columns(\WC_Product $product_object, $columns)
    {
        if (empty($columns)) {
            return false;
        }
        $output = '';
        $product = (new Product())->get_product_fields($product_object);
        foreach ($columns as $key => $column) {
            $includes = [];
            $key_decoded = $key;
            $meta_field = '';
            $key = (substr($key, 0, 3) == 'pa_') ? strtolower(urlencode($key)) : urlencode($key);
            if (isset($column['field_type'])) {
                switch ($column['field_type']) {
                    case 'meta_field':
                        $meta_field = 'meta_field';
                        $product[$key] = (isset($product['meta_field'][$key])) ? $product['meta_field'][$key][0] : '';
                        break;
                    case 'attribute':
                        $product[$key] = (isset($product['attribute'][$key]['options'])) ? $product['attribute'][$key]['options'] : '';
                        break;
                    default:
                        break;
                }
            }
            $background_color = (!empty($column['background_color']) && $column['background_color'] != '#fff' && $column['background_color'] != '#ffffff') ? 'background:' . $column['background_color'] . ';' : '';
            $text_color = (!empty($column['text_color'])) ? 'color:' . $column['text_color'] . ';' : '';
            $output .= '<td data-product-id="' . esc_attr($product['id']) . '" data-product-title="' . esc_attr($product['post_title']) . '" data-col-title="' . esc_attr($column['title']) . '" data-field="' . esc_attr($key_decoded) . '" data-field-type="' . esc_attr($meta_field) . '" style="' . esc_attr($background_color) . ' ' . esc_attr($text_color) . '"';
            if ($column['editable'] === true && !in_array($column['content_type'], ['multi_select', 'multi_select_attribute'])) {
                $output .= 'data-content-type="' . esc_attr($column['content_type']) . '" data-action="inline-editable"';
            }
            $output .= '>';
            if ((!empty($column['allowed_type']) && in_array($product['type'], $column['allowed_type']))) {
                if ($column['editable'] === true) {
                    switch ($column['content_type']) {
                        case 'text':
                            $value = (is_array($product[$key])) ? implode(',', $product[$key]) : $product[$key];
                            $output .= "<span data-action='inline-editable' class='wcbef-td160'>" . esc_attr($value) . "</span>";
                            break;
                        case 'textarea':
                            $output .= "<button type='button' data-toggle='modal' data-target='#wcbef-modal-text-editor' class='wcbef-button wcbef-button-white wcbef-load-text-editor wcbef-td160' data-product-id='" . esc_attr($product['id']) . "' data-product-name='" . esc_attr($product['post_title']) . "' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($meta_field) . "'>Content</button>";
                            break;
                        case 'image':
                            $output .= "<span data-toggle='modal' data-target='#wcbef-modal-" . esc_attr($key_decoded) . "-" . esc_attr($product['id']) . "' data-id='wcbef-image-" . esc_attr($product['id']) . "' class='wcbef-image-inline-edit'>" . sprintf('%s', $product[$key]['small']) . "</span>";
                            $includes[] = WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/thumbnail.php';
                            break;
                        case 'gallery':
                            $output .= "<button type='button' data-toggle='modal' data-target='#wcbef-modal-gallery-" . esc_attr($product['id']) . "' class='wcbef-button wcbef-button-white' data-product-id='" . esc_attr($product['id']) . "' data-field='" . esc_attr($key_decoded) . "'>Gallery</button>";
                            $includes[] = WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/gallery.php';
                            break;
                        case 'regular_price':
                            $price = ($product[$key] != '') ? number_format(floatval($product[$key]), 2) : '';
                            $output .= "<span data-action='inline-editable' class='wcbef-numeric-content wcbef-td120'>" . esc_html($price) . "</span><button type='button' data-toggle='modal' class='wcbef-calculator' data-target='#wcbef-modal-" . esc_attr($key) . "-" . esc_attr($product['id']) . "'></button>";
                            $includes[] = WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/regular_price_calculator.php';
                            break;
                        case 'sale_price':
                            $price = ($product[$key] != '') ? number_format(floatval($product[$key]), 2) : '';
                            $output .= "<span data-action='inline-editable' class='wcbef-numeric-content wcbef-td120'>" . esc_html($price) . "</span><button type='button' data-toggle='modal' class='wcbef-calculator' data-target='#wcbef-modal-" . esc_attr($key) . "-" . esc_attr($product['id']) . "'></button>";
                            $includes[] = WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/sale_price_calculator.php';
                            break;
                        case 'numeric':
                            $output .= "<span data-action='inline-editable' class='wcbef-numeric-content wcbef-td120'>" . esc_html($product[$key]) . "</span><button type='button' data-toggle='modal' class='wcbef-calculator' data-field='" . esc_attr($key_decoded) . "' data-product-id='" . esc_attr($product['id']) . "' data-product-name='" . esc_attr($product['post_title']) . "' data-field-type='" . esc_attr($meta_field) . "' data-target='#wcbef-modal-numeric-calculator'></button>";
                            break;
                        case 'numeric_without_calculator':
                            $output .= "<span data-action='inline-editable' class='wcbef-numeric-content wcbef-td120'>" . esc_html($product[$key]) . "</span>";
                            break;
                        case 'checkbox':
                            $checked = ($product[$key]) ? 'checked="checked"' : '';
                            $output .= "<label><input type='checkbox' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($meta_field) . "' data-product-id='" . esc_attr($product['id']) . "' value='yes' class='wcbef-dual-mode-checkbox wcbef-inline-edit-action' " . esc_attr($checked) . "><span>" . esc_html__('Yes', 'woocommerce-bulk-edit-free') . "</span></label>";
                            break;
                        case 'select_files':
                            $output .= "<button type='button' data-toggle='modal' data-target='#wcbef-modal-select-files' class='wcbef-button wcbef-button-white' data-product-id='" . esc_attr($product['id']) . "' data-product-name='" . esc_attr($product['post_title']) . "' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($meta_field) . "'>Files</button>";
                            break;
                        case 'select_products':
                            $children_ids = '';
                            switch ($key_decoded) {
                                case '_children':
                                    $children_ids = (!empty(unserialize($product['meta_field'][$key_decoded][0]))) ? implode(',', unserialize($product['meta_field'][$key_decoded][0])) : '';
                                    break;
                                case 'upsell_ids':
                                case 'cross_sell_ids':
                                    if (!empty($product[$key_decoded]) && is_array($product[$key_decoded])) {
                                        $children_ids = implode(',', $product[$key_decoded]);
                                    }
                                    break;
                            }
                            $output .= "<button type='button' data-toggle='modal' data-target='#wcbef-modal-select-products' class='wcbef-button wcbef-button-white' data-children-ids='" . esc_attr($children_ids) . "' data-product-id='" . esc_attr($product['id']) . "' data-product-name='" . esc_attr($product['post_title']) . "' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($meta_field) . "'>Products</button>";
                            break;
                        case 'select':
                            $output .= "<select class='wcbef-inline-edit-action' data-field='" . esc_attr($key_decoded) . "' data-product-id='" . esc_attr($product['id']) . "' title='Select " . esc_attr($column['label']) . "'>";
                            if (!empty($column['options'])) {
                                foreach ($column['options'] as $option_key => $option_value) {
                                    $selected = ($option_key == $product[$key]) ? 'selected' : '';
                                    $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                                }
                            }
                            $output .= '</select>';
                            break;
                        case 'date':
                            $date = (!empty($product[$key])) ? date('Y/m/d', strtotime($product[$key])) : '';
                            $clear_button = ($key != 'post_date') ? "<button type='button' class='wcbef-clear-date-btn wcbef-inline-edit-clear-date' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($meta_field) . "' data-product-id='" . esc_attr($product['id']) . "' value=''>clear</button>" : '';
                            $output .= "<input type='text' class='wcbef-datepicker wcbef-inline-edit-action' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($meta_field) . "' data-product-id='" . esc_attr($product['id']) . "' title='Select " . esc_attr($column['label']) . "' value='" . esc_html($date) . "'>" . sprintf('%s', $clear_button);
                            break;
                        case 'multi_select':
                            $values = get_the_term_list($product['id'], $key_decoded, '<span class="wcbef-category-item">', '</span><span class="wcbef-category-item">', '</span>');
                            if (isset($column['field_type'])) {
                                if (mb_substr($key, 0, 3) == 'pa_') {
                                    $output .= "<span data-toggle='modal' class='wcbef-is-attribute-modal wcbef-product-attribute' data-target='#wcbef-modal-attribute-" . esc_attr($key_decoded) . "-" . esc_attr($product['id']) . "' data-product-id='" . esc_attr($product['id']) . "'>";
                                    $output .= (!empty($values)) ? strip_tags(sprintf('%s', $values), '<span>') : 'No items';
                                    $output .= "</span>";
                                    $includes[] = WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/product_attribute.php';
                                } else {
                                    $output .= "<span data-toggle='modal' class='wcbef-is-taxonomy-modal wcbef-product-taxonomy' data-target='#wcbef-modal-taxonomy-" . esc_attr($key_decoded) . "-" . esc_attr($product['id']) . "' data-product-id='" . esc_attr($product['id']) . "'>";
                                    $output .= (!empty($values)) ? strip_tags(sprintf('%s', $values), '<span>') : 'No items';
                                    $output .= "</span>";
                                    $includes[] = WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/product_taxonomy.php';
                                }
                            } else {
                                switch ($key) {
                                    case 'product_tag':
                                    case 'product_cat':
                                        $output .= "<span data-toggle='modal' class='wcbef-is-taxonomy-modal wcbef-product-taxonomy' data-target='#wcbef-modal-taxonomy-" . esc_attr($key_decoded) . "-" . esc_attr($product['id']) . "' data-product-id='" . esc_attr($product['id']) . "'>";
                                        $output .= (!empty($values)) ? strip_tags(sprintf('%s', $values), '<span>') : 'No items';
                                        $output .= "</span>";
                                        $includes[] = WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/product_taxonomy.php';
                                        break;
                                    default:
                                        break;
                                }
                            }
                            break;
                        default:
                            $value = (is_array($product[$key])) ? implode(',', $product[$key]) : $product[$key];
                            $output .= $value;
                            break;
                    }
                } else {
                    if (!empty($product[$key])) {
                        $output .= (is_array($product[$key])) ? esc_html(implode(',', $product[$key])) : esc_html($product[$key]);
                    } else {
                        $output .= ' ';
                    }
                }
            } else {
                $value = "<img src='" . WCBEF_IMAGES_URL . "no_entry.png''>";
                $output .= $value;
            }
            $output .= '</td>';
            if (!empty($includes)) {
                foreach ($includes as $include) {
                    include $include;
                }
            }
        }

        return $output;
    }

    public static function get_default_columns_name()
    {
        return [
            'default1',
            'default2',
            'default3',
        ];
    }

    public static function get_default_columns1()
    {
        return [
            '_thumbnail_id' => [
                'label' => esc_html__('Thumbnail', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Thumbnail', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_content' => [
                'label' => esc_html__('Description', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Description', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'manage_stock' => [
                'label' => esc_html__('Manage Stock', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Manage Stock', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'checkbox',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'regular_price' => [
                'label' => esc_html__('Regular Price', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Regular Price', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_status' => [
                'label' => esc_html__('Status', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Status', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'select',
                'options' => get_post_statuses(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_date' => [
                'label' => esc_html__('Post Published', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Post Published', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'product_cat' => [
                'label' => esc_html__('Categories', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Categories', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'multi_select',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
        ];
    }

    public static function get_default_columns2()
    {
        return [
            '_thumbnail_id' => [
                'label' => esc_html__('Thumbnail', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Thumbnail', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'gallery' => [
                'label' => esc_html__('Gallery', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Gallery', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'gallery',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'regular_price' => [
                'label' => esc_html__('Regular Price', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Regular Price', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'sale_price' => [
                'label' => esc_html__('Sale Price', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Sale Price', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'sale_price',
                'allowed_type' => ['simple', 'variation', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_status' => [
                'label' => esc_html__('Status', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Status', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'select',
                'options' => get_post_statuses(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
        ];
    }

    public static function get_default_columns3()
    {
        return [
            '_thumbnail_id' => [
                'label' => esc_html__('Thumbnail', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Thumbnail', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_content' => [
                'label' => esc_html__('Description', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Description', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_excerpt' => [
                'label' => esc_html__('Short Description', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Short Description', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_status' => [
                'label' => esc_html__('Status', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Status', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'select',
                'options' => get_post_statuses(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_date' => [
                'label' => esc_html__('Post Published', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('Post Published', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'sku' => [
                'label' => esc_html__('SKU', 'woocommerce-bulk-edit-free'),
                'title' => esc_html__('SKU', 'woocommerce-bulk-edit-free'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
        ];
    }
}
