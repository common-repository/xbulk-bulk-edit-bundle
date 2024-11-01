<?php

namespace wcbel\classes\providers\column;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Meta_Field as Meta_Field_Helper;
use wcbel\classes\helpers\Render;
use wcbel\classes\helpers\Sanitizer;
use wcbel\classes\repositories\ACF_Plugin_Fields;
use wcbel\classes\repositories\Column;
use wcbel\classes\repositories\Product;
use wcbel\classes\repositories\Setting;

class ProductColumnProvider
{
    private static $instance;
    private $sticky_first_columns;
    private $product;
    private $product_object;
    private $column_key;
    private $decoded_column_key;
    private $column_data;
    private $field_type;
    private $fields_method;
    private $acf_fields;
    private $acf_fields_name;
    private $acf_taxonomy_name;
    private $enable_thumbnail_popup;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $setting_repository = new Setting();
        $settings = $setting_repository->get_settings();
        $this->sticky_first_columns = isset($settings['sticky_first_columns']) ? $settings['sticky_first_columns'] : 'yes';
        $this->enable_thumbnail_popup = isset($settings['enable_thumbnail_popup']) ? $settings['enable_thumbnail_popup'] : 'yes';

        $this->field_type = "";
        $this->fields_method = $this->get_fields_method();

        $acf = ACF_Plugin_Fields::get_instance('product');
        $this->acf_fields = $acf->get_fields();
        $this->acf_fields_name = (is_array($this->acf_fields)) ? array_keys($this->acf_fields) : [];
    }

    public function get_item_columns($product_object, $columns)
    {
        if ($product_object instanceof \WC_Product) {
            $this->product_object = $product_object;
            $output['includes'] = [];
            $this->product = (Product::get_instance())->get_product_fields($product_object);
            $output['items'] = '<tr data-item-id="' . esc_attr($this->product['id']) . '" data-item-type="' . esc_attr($this->product['type']) . '">';
            $output['items'] .= $this->get_static_columns();
            if (!empty($columns) && is_array($columns)) {
                foreach ($columns as $column_key => $column_data) {
                    $this->column_key = $column_key;
                    $this->column_data = $column_data;
                    if (in_array($column_key, $this->acf_fields_name)) {
                        $this->column_data['content_type'] = Meta_Field_Helper::get_field_type_by_acf_type($this->acf_fields[$column_key])['column_type'];
                    }
                    $this->decoded_column_key = (substr($this->column_key, 0, 3) == 'pa_') ? strtolower(urlencode($this->column_key)) : urlencode($this->column_key);
                    $field_data = $this->get_field();
                    $output['items'] .= (!empty($field_data['field'])) ? $field_data['field'] : '';
                    if (isset($field_data['includes']) && is_array($field_data['includes'])) {
                        $column_key = $this->column_key;
                        $decoded_column_key = $this->decoded_column_key;
                        $column_data = $this->column_data;
                        $field_type = $this->field_type;
                        $acf_fields = $this->acf_fields;
                        $product = $this->product;
                        $acf_taxonomy_name = $this->acf_taxonomy_name;
                        foreach ($field_data['includes'] as $include) {
                            if (file_exists($include)) {
                                $output['includes'][] = Render::html($include, compact('product', 'column_key', 'decoded_column_key', 'column_data', 'field_type', 'acf_fields', 'acf_taxonomy_name'));
                            }
                        }
                    }
                }
            }
            $output['items'] .= "</tr>";
            return $output;
        }
    }

    private function get_field()
    {
        $output['field'] = '';
        $output['includes'] = [];
        $this->field_type = '';

        $this->set_product_field();
        $color = $this->get_column_colors_style();

        $sub_name = (!empty($this->column_data['sub_name'])) ? $this->column_data['sub_name'] : '';
        $update_type = (!empty($this->column_data['update_type'])) ? $this->column_data['update_type'] : '';
        $output['field'] .= '<td data-item-id="' . esc_attr($this->product['id']) . '" data-item-title="' . esc_attr($this->product['title']) . '" data-col-title="' . esc_attr($this->column_data['title']) . '" data-field="' . esc_attr($this->column_key) . '" data-field-type="' . esc_attr($this->field_type) . '" data-name="' . esc_attr($this->column_data['name']) . '" data-sub-name="' . esc_attr($sub_name) . '" data-update-type="' . esc_attr($update_type) . '" style="' . esc_attr($color['background']) . ' ' . esc_attr($color['text']) . '" ';
        if ($this->column_data['editable'] === true && !in_array($this->column_data['content_type'], ['multi_select', 'multi_select_attribute'])) {
            $output['field'] .= 'data-content-type="' . esc_attr($this->column_data['content_type']) . '" data-action="inline-editable"';
        }
        $output['field'] .= '>';

        if ((!empty($this->column_data['allowed_type']) && in_array($this->product['type'], $this->column_data['allowed_type']))) {
            if ($this->column_data['editable'] === true) {
                $generated = $this->generate_field();
                if (is_array($generated) && isset($generated['field']) && isset($generated['includes'])) {
                    $output['field'] .= $generated['field'];
                    $output['includes'][] = $generated['includes'];
                } else {
                    $output['field'] .= $generated;
                }
            } else {
                if (isset($this->product[$this->decoded_column_key])) {
                    $output['field'] .= (is_array($this->product[$this->decoded_column_key])) ? wp_kses('%s', implode(',', $this->product[$this->decoded_column_key]), Sanitizer::allowed_html_tags()) : wp_kses($this->product[$this->decoded_column_key], Sanitizer::allowed_html_tags());
                } else {
                    $output['field'] .= ' ';
                }
            }
        } else {
            $value = '<i class="wcbel-icon-slash"></i>';
            $output['field'] .= $value;
        }

        $output['field'] .= '</td>';
        return $output;
    }

    private function get_column_colors_style()
    {
        $color['background'] = (!empty($this->column_data['background_color']) && $this->column_data['background_color'] != '#fff' && $this->column_data['background_color'] != '#ffffff') ? 'background:' . esc_attr($this->column_data['background_color']) . ';' : '';
        $color['text'] = (!empty($this->column_data['text_color'])) ? 'color:' . esc_attr($this->column_data['text_color']) . ';' : '';
        return $color;
    }

    private function generate_field()
    {
        if (isset($this->fields_method[$this->column_data['content_type']]) && method_exists($this, $this->fields_method[$this->column_data['content_type']])) {
            return $this->{$this->fields_method[$this->column_data['content_type']]}();
        } else {
            return (is_array($this->product[$this->decoded_column_key])) ? implode(',', $this->product[$this->decoded_column_key]) : $this->product[$this->decoded_column_key];
        }
    }

    private function get_id_column()
    {
        $output = '';
        if (Column::SHOW_ID_COLUMN === true) {
            $id_for_edit = ($this->product['type'] == 'variation') ? $this->product['post_parent'] : $this->product['id'];
            $delete_type = 'trash';
            $delete_label = __('Delete product', 'ithemeland-bulk-product-editing-lite-for-woocommerce');
            $restore_button = '';
            $view_button = '';
            $edit_button = '';

            if ($this->product['status'] == 'trash') {
                $delete_type = 'permanently';
                $delete_label = __('Delete permanently', 'ithemeland-bulk-product-editing-lite-for-woocommerce');
                $restore_button = '<button type="button" style="height: 28px;" class="wcbel-ml5 wcbel-button-flat wcbel-text-green wcbel-float-right wcbel-restore-item-btn" data-item-id="' . esc_attr($this->product['id']) . '" title="' . __('Restore', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . '"><span class="wcbel-icon-rotate-cw"></span></button>';
            } else {
                $view_button = '<a href="' . esc_url(get_the_permalink($this->product['id'])) . '" target="_blank" title="' . __('View on site', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . '" style="height: 28px;" class="wcbel-item-view-icon wcbel-float-right wcbel-ml5"><span class="wcbel-icon-eye1" style="vertical-align: middle;"></span></a>';
                $edit_button = '<a href="' . admin_url("post.php?post=" . esc_attr($id_for_edit) . "&action=edit") . '" target="_blank" class="wcbel-ml5 wcbel-float-right" title="' . __('Edit product', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . '" style="height: 28px;"><span class="wcbel-icon-pencil" style="vertical-align: middle;"></span></a>';
            }

            $sticky_class = ($this->sticky_first_columns == 'yes') ? 'wcbel-td-sticky wcbel-td-sticky-id wcbel-gray-bg' : '';
            $output .= '<td data-item-id="' . esc_attr($this->product['id']) . '" data-item-title="' . esc_attr($this->product['title']) . '" data-col-title="ID" class="' . esc_attr($sticky_class) . '">';
            $output .= '<label class="wcbel-td140">';
            $output .= '<input type="checkbox" class="wcbel-check-item" data-item-type="' . esc_attr($this->product['type']) . '" value="' . esc_attr($this->product['id']) . '" title="Select Item">';
            $output .= esc_html($this->product['id']);
            $output .= $restore_button;
            $output .= $view_button;
            $output .= '<button type="button" class="wcbel-ml5 wcbel-button-flat wcbel-float-right wcbel-text-red wcbel-delete-item-btn" data-delete-type="' . esc_attr($delete_type) . '" data-item-id="' . esc_attr($this->product['id']) . '" title="' . $delete_label . '"><span class="wcbel-icon-trash-2"></span></button>';
            $output .= $edit_button;
            $output .= "</label>";
            $output .= "</td>";
        }
        return $output;
    }

    private function get_static_columns()
    {
        $output = '';
        $output .= $this->get_id_column();
        if (!empty(Column::get_static_columns())) {
            foreach (Column::get_static_columns() as $static_column) {
                $sticky_class = ($this->sticky_first_columns == 'yes') ? 'wcbel-td-sticky wcbel-td-sticky-title wcbel-gray-bg' : '';
                $output .= '<td class="' . esc_attr($sticky_class) . '" data-update-type="woocommerce_field" data-name="' . esc_attr($static_column['field']) . '" data-item-id="' . esc_attr($this->product['id']) . '" data-item-title="' . esc_attr($this->product[$static_column['field']]) . '" data-col-title="' . esc_attr($static_column['title']) . '" data-field="' . esc_attr($static_column['field']) . '" data-field-type="" data-content-type="text" data-action="inline-editable">';
                $output .= '<span data-action="inline-editable" class="wcbel-td160">' . esc_html($this->product[$static_column['field']]) . '</span>';
                $output .= '</td>';
            }
        }
        return $output;
    }

    private function set_product_field()
    {
        if (isset($this->column_data['field_type'])) {
            switch ($this->column_data['field_type']) {
                case 'custom_field':
                    $this->field_type = 'custom_field';
                    $this->product[$this->decoded_column_key] = (isset($this->product['custom_field'][$this->decoded_column_key])) ? $this->product['custom_field'][$this->decoded_column_key][0] : '';
                    break;
                case 'taxonomy':
                    if (substr($this->decoded_column_key, 0, 3) == 'pa_') {
                        $this->product[$this->decoded_column_key] = (isset($this->product['attribute'][$this->decoded_column_key]['options'])) ? $this->product['attribute'][$this->decoded_column_key]['options'] : '';
                    } else {
                        $this->product[$this->decoded_column_key] = ($this->decoded_column_key == 'product_tag') ? wp_get_post_terms($this->product['id'], $this->decoded_column_key, ['fields' => 'names']) : wp_get_post_terms($this->product['id'], $this->decoded_column_key, ['fields' => 'ids']);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    private function get_fields_method()
    {
        return [
            'text' => 'text_field',
            'password' => 'text_field',
            'email' => 'text_field',
            'url' => 'text_field',
            'textarea' => 'textarea_field',
            'image' => 'image_field',
            'gallery' => 'gallery_field',
            'regular_price' => 'regular_price_field',
            'sale_price' => 'sale_price_field',
            'numeric' => 'numeric_field',
            'numeric_without_calculator' => 'numeric_without_calculator_field',
            'checkbox_dual_mode' => 'checkbox_dual_mode_field',
            'checkbox' => 'checkbox_field',
            'radio' => 'radio_field',
            'file' => 'file_field',
            'select_files' => 'select_files_field',
            'select_products' => 'select_products_field',
            'select' => 'select_field',
            'yith_shop_vendor' => 'yith_shop_vendor_field',
            'wc_product_vendor' => 'wc_product_vendor_field',
            'date' => 'date_picker_field',
            'date_picker' => 'date_picker_field',
            'date_time_picker' => 'date_time_picker_field',
            'time_picker' => 'time_picker_field',
            'color_picker' => 'color_picker_field',
            'taxonomy' => 'multi_select_field',
            'multi_select' => 'multi_select_field',
            'yith_product_badge' => 'yith_product_badge_field',
            'ithemeland_badge' => 'ithemeland_badge_field',
            'yikes_custom_product_tabs' => 'yikes_custom_product_tabs_field',
            'it_wc_dynamic_pricing_select_roles' => 'it_wc_dynamic_pricing_select_roles_field',
            'it_pricing_rules_product' => 'it_pricing_rules_product_field',
            'it_wc_dynamic_pricing_all_fields' => 'it_wc_dynamic_pricing_all_fields_field',
        ];
    }

    private function text_field()
    {
        $value = (is_array($this->product[$this->decoded_column_key])) ? implode(',', $this->product[$this->decoded_column_key]) : $this->product[$this->decoded_column_key];
        return "<span data-action='inline-editable' class='wcbel-td160'>" . wp_kses($value, Sanitizer::allowed_html_tags()) . "</span>";
    }

    private function textarea_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-text-editor' class='wcbel-button wcbel-button-white wcbel-load-text-editor' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'><i class='dashicons dashicons-edit'></i></button>";
    }

    private function image_field()
    {
        $image_id = 0;
        if (isset($this->product[$this->decoded_column_key]['id'])) {
            $image_id = intval($this->product[$this->decoded_column_key]['id']);
        }
        if (isset($this->product[$this->decoded_column_key]) && is_numeric($this->product[$this->decoded_column_key])) {
            $image_id = intval($this->product[$this->decoded_column_key]);
        }

        $image_url = wp_get_attachment_image_src($image_id, [40, 40]);
        $image_url = !empty($image_url[0]) ? $image_url[0] : esc_url(WCBEL_IMAGES_URL . "/woocommerce-placeholder-150x150.png");
        $full_size = wp_get_attachment_image_src($image_id, 'full');
        $full_size = (!empty($full_size[0])) ? $full_size[0] : esc_url(WCBEL_IMAGES_URL . "/woocommerce-placeholder.png");

        $image = "<img src='" . esc_url($image_url) . "' alt='' width='40' height='40' />";
        $hover_box_class = ($this->enable_thumbnail_popup == 'yes') ? 'wcbel-thumbnail' : '';
        $output = "<span data-toggle='modal' class='{$hover_box_class}' data-target='#wcbel-modal-image' data-id='wcbel-" . esc_attr($this->column_key) . "-" . esc_attr($this->product['id']) . "' class='wcbel-image-inline-edit' data-full-image-src='" . esc_url($full_size) . "' data-image-id='" . esc_attr($image_id) . "'>";
        if ($this->enable_thumbnail_popup == 'yes') {
            $output .= '<div class="wcbel-original-thumbnail">' . $this->product[$this->decoded_column_key]['medium'] . '</div>';
        }
        $output .= $image;
        $output .= "</span>";
        return $output;
    }

    private function gallery_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-gallery' data-item-name='" . esc_attr($this->product['title']) . "' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "' data-field='" . esc_attr($this->column_key) . "'>Gallery</button>";
    }

    private function regular_price_field()
    {
        $price = ($this->product[$this->decoded_column_key] != '') ? number_format(floatval($this->product[$this->decoded_column_key]), 2) : '';
        $output['field'] = "<span data-action='inline-editable' class='wcbel-numeric-content wcbel-td120'>" . esc_html($price) . "</span><button type='button' data-toggle='modal' class='wcbel-calculator' data-target='#wcbel-modal-" . esc_attr($this->decoded_column_key) . "-" . esc_attr($this->product['id']) . "'></button>";
        $output['includes'] = WCBEL_VIEWS_DIR . 'bulk_edit/columns_modals/regular_price_calculator.php';
        return $output;
    }

    private function sale_price_field()
    {
        $price = ($this->product[$this->decoded_column_key] != '') ? number_format(floatval($this->product[$this->decoded_column_key]), 2) : '';
        $output['field'] = "<span data-action='inline-editable' class='wcbel-numeric-content wcbel-td120'>" . esc_html($price) . "</span><button type='button' data-toggle='modal' class='wcbel-calculator' data-target='#wcbel-modal-" . esc_attr($this->decoded_column_key) . "-" . esc_attr($this->product['id']) . "'></button>";
        $output['includes'] = WCBEL_VIEWS_DIR . 'bulk_edit/columns_modals/sale_price_calculator.php';
        return $output;
    }

    private function numeric_field()
    {
        $field_arr = explode('_-_', $this->decoded_column_key);
        $field = (!empty($field_arr[0])) ? $field_arr[0] : $this->decoded_column_key;
        $value = $this->product[$field];

        if (!empty($field_arr[1]) && is_array($value)) {
            if (!empty($value[0])) {
                $decoded = json_decode($value[0], true);
                $value = (is_array($decoded) && isset($decoded[$field_arr[1]])) ? $decoded[$field_arr[1]] : '';
            } else {
                $value = '';
            }
        }

        return "<span data-action='inline-editable' class='wcbel-numeric-content wcbel-td120'>" . esc_html($value) . "</span><button type='button' data-toggle='modal' class='wcbel-calculator' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field-type='" . esc_attr($this->field_type) . "' data-target='#wcbel-modal-numeric-calculator'></button>";
    }

    private function numeric_without_calculator_field()
    {
        return "<span data-action='inline-editable' class='wcbel-numeric-content wcbel-td120'>" . esc_html($this->product[$this->decoded_column_key]) . "</span>";
    }

    private function checkbox_dual_mode_field()
    {
        $checked =  ($this->product[$this->decoded_column_key] && $this->product[$this->decoded_column_key] !== 'no') ? 'checked="checked"' : '';
        return "<label><input type='checkbox' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' value='yes' class='wcbel-dual-mode-checkbox wcbel-inline-edit-action' " . esc_attr($checked) . "><span>" . __('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</span></label>";
    }

    private function checkbox_field()
    {
        $output = "";
        if (!empty($this->acf_fields[$this->decoded_column_key]['choices']) && is_array($this->acf_fields[$this->decoded_column_key]['choices'])) {
            foreach ($this->acf_fields[$this->decoded_column_key]['choices'] as $choice_key => $choice_value) {
                $selected = isset($this->product[$this->decoded_column_key]) ? unserialize($this->product[$this->decoded_column_key]) : null;
                $checked = !empty($selected) && is_array($selected) && in_array($choice_key, $selected) ? 'checked="checked"' : '';
                $output = "<label><input type='checkbox' name='" . esc_attr($this->decoded_column_key . '-' . $this->product['id']) . "' value='" . esc_attr($choice_key) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' class='wcbel-dual-mode-checkbox wcbel-inline-edit-action' " . esc_attr($checked) . ">" . esc_html($choice_value) . "</label>";
            }
        }
        return $output;
    }

    private function radio_field()
    {
        $output = '';
        if (!empty($this->acf_fields[$this->decoded_column_key]['choices']) && is_array($this->acf_fields[$this->decoded_column_key]['choices'])) {
            foreach ($this->acf_fields[$this->decoded_column_key]['choices'] as $choice_key => $choice_value) {
                $checked = isset($this->product[$this->decoded_column_key]) && $this->product[$this->decoded_column_key] == $choice_key ? 'checked="checked"' : '';
                $output .= "<label><input type='radio' name='" . esc_attr($this->decoded_column_key . '-' . $this->product['id']) . "' value='" . esc_attr($choice_key) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' class='wcbel-dual-mode-checkbox wcbel-inline-edit-action' " . esc_attr($checked) . ">" . esc_html($choice_value) . "</label>";
            }
        }

        return $output;
    }

    private function file_field()
    {
        $file_id = (isset($this->product[$this->decoded_column_key])) ? intval($this->product[$this->decoded_column_key]) : null;
        $file_url = wp_get_attachment_url($file_id);
        $file_url = !empty($file_url) ? esc_url($file_url) : '';
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-file' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-file-id='" . $file_id . "' data-file-url='" . $file_url . "'>Select File</button>";
    }

    private function select_files_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-select-files' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>Files</button>";
    }

    private function select_products_field()
    {
        $children_ids = '';
        switch ($this->column_key) {
            case '_children':
                $children_ids = (!empty(unserialize($this->product['custom_field'][$this->column_key][0]))) ? implode(',', unserialize($this->product['custom_field'][$this->column_key][0])) : '';
                break;
            case 'upsell_ids':
            case 'cross_sell_ids':
                if (!empty($this->product[$this->column_key]) && is_array($this->product[$this->column_key])) {
                    $children_ids = implode(',', $this->product[$this->column_key]);
                }
                break;
        }
        return  "<button type='button' data-toggle='modal' data-target='#wcbel-modal-select-products' class='wcbel-button wcbel-button-white' data-children-ids='" . esc_attr($children_ids) . "' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>Products</button>";
    }

    private function select_field()
    {
        $output = "<select class='wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' data-field-type='" . esc_attr($this->field_type) . "'>";
        if (!empty($this->column_data['options'])) {
            if ($this->column_key  == 'tax_class' && $this->product['type'] == 'variation') {
                $this->column_data['options'] = ['parent' => __('Same as parent')] + $this->column_data['options'];
                $variation_tax_class = $this->product['custom_field']['_tax_class'][0];
            }

            if ($this->column_key == 'shipping_class') {
                $this->column_data['options'] = [
                    -1 => 'No Shipping Class',
                ];

                $shipping_items = wc()->shipping()->get_shipping_classes();
                if (!empty($shipping_items)) {
                    foreach ($shipping_items as $shipping_class) {
                        $this->column_data['options'][$shipping_class->term_id] = $shipping_class->name;
                    }
                }
            }

            foreach ($this->column_data['options'] as $option_key => $option_value) {
                if (!empty($variation_tax_class)) {
                    $selected = ($option_key == $variation_tax_class) ? 'selected' : '';
                } else {
                    if (is_array($this->product[$this->decoded_column_key])) {
                        $selected = (in_array($option_key, $this->product[$this->decoded_column_key])) ? 'selected' : '';
                    } else {
                        $selected = ($option_key == $this->product[$this->decoded_column_key]) ? 'selected' : '';
                    }
                }
                $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
            }
        } else {
            if ($this->column_data['field_type'] == 'custom_field') {
                $meta_fields = get_option('wcbel_meta_fields', []);
                if (!empty($meta_fields[$this->column_data['name']]) && !empty($meta_fields[$this->column_data['name']]['key_value'])) {
                    $options = Meta_Field_Helper::key_value_field_to_array($meta_fields[$this->column_data['name']]['key_value']);
                    if (!empty($options) && is_array($options)) {
                        foreach ($options as $option_key => $option_value) {
                            $selected = isset($this->product[$this->decoded_column_key]) && $this->product[$this->decoded_column_key] == $option_key ? 'selected' : '';
                            $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                        }
                    }
                }
            }
        }

        if (!empty($this->acf_fields[$this->decoded_column_key]['choices']) && is_array($this->acf_fields[$this->decoded_column_key]['choices'])) {
            foreach ($this->acf_fields[$this->decoded_column_key]['choices'] as $choice_key => $choice_value) {
                $selected = isset($this->product[$this->decoded_column_key]) && $this->product[$this->decoded_column_key] == $choice_key ? 'selected' : '';
                $output .= "<option value='" . esc_attr($choice_key) . "' $selected>" . esc_html($choice_value) . "</option>";
            }
        }

        if (!empty($this->acf_fields[$this->decoded_column_key]['taxonomy'])) {
            $options = $this->get_taxonomy_terms($this->acf_fields[$this->decoded_column_key]['taxonomy']);
            if (!empty($options) && count($options)) {
                foreach ($options as $option_key => $option_value) {
                    $selected = isset($this->product[$this->decoded_column_key]) && $this->product[$this->decoded_column_key] == $option_key ? 'selected' : '';
                    $output .= "<option value='" . esc_attr($option_key) . "' $selected>" . esc_html($option_value) . "</option>";
                }
            }
        }
        $output .= '</select>';

        return $output;
    }

    private function yith_shop_vendor_field()
    {
        $output = "<select class='wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' data-field-type='" . esc_attr($this->field_type) . "'>";
        $output .= "<option value=''>" . __('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</option>";
        $product_repository = Product::get_instance();
        $yith_shop_vendor_object = $product_repository->get_yith_vendors();
        if (!empty($yith_shop_vendor_object)) {
            foreach ($yith_shop_vendor_object as $vendor) {
                if ($vendor instanceof \WP_Term) {
                    $selected = (in_array($vendor->slug, $this->product[$this->decoded_column_key])) ? 'selected' : '';
                    $output .= "<option value='{$vendor->slug}' $selected>{$vendor->name}</option>";
                }
            }
        }
        $output .= '</select>';
        return $output;
    }

    private function wc_product_vendor_field()
    {
        $output = "<select class='wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' data-field-type='" . esc_attr($this->field_type) . "'>";
        $output .= "<option value=''>" . __('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</option>";
        $product_repository = Product::get_instance();
        $wc_product_vendor_object = $product_repository->get_wc_product_vendors();
        if (!empty($wc_product_vendor_object)) {
            foreach ($wc_product_vendor_object as $vendor) {
                if ($vendor instanceof \WP_Term) {
                    $selected = (in_array($vendor->slug, $this->product[$this->decoded_column_key])) ? 'selected' : '';
                    $output .= "<option value='{$vendor->slug}' $selected>{$vendor->name}</option>";
                }
            }
        }
        $output .= '</select>';
        return $output;
    }

    private function date_picker_field()
    {
        $date = (!empty($this->product[$this->decoded_column_key])) ? gmdate('Y/m/d', strtotime($this->product[$this->decoded_column_key])) : '';
        $clear_button = ($this->decoded_column_key != 'date_created') ? "<button type='button' class='wcbel-clear-date-btn wcbel-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' value=''><img src='" . esc_url(WCBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
        return "<input type='text' class='wcbel-datepicker wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html_tags());
    }

    private function date_time_picker_field()
    {
        $date = (!empty($this->product[$this->decoded_column_key])) ? gmdate('Y/m/d H:i', strtotime($this->product[$this->decoded_column_key])) : '';
        $clear_button = "<button type='button' class='wcbel-clear-date-btn wcbel-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' value=''><img src='" . esc_url(WCBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>";
        return "<input type='text' class='wcbel-datetimepicker wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html_tags());
    }

    private function time_picker_field()
    {
        $date = (!empty($this->product[$this->decoded_column_key])) ? gmdate('H:i', strtotime($this->product[$this->decoded_column_key])) : '';
        $clear_button = "<button type='button' class='wcbel-clear-date-btn wcbel-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' value=''><img src='" . esc_url(WCBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>";
        return "<input type='text' class='wcbel-timepicker wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html_tags());
    }

    private function color_picker_field()
    {
        return "<input type='text' class='wcbel-color-picker-field wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($this->product[$this->decoded_column_key]) . "'><button type='button' class='wcbel-inline-edit-color-action'>" . __('Apply', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</button>";
    }

    private function multi_select_field()
    {
        if (isset($this->acf_fields[$this->column_key]['taxonomy'])) {
            $values = '';
            $this->acf_taxonomy_name = esc_attr($this->acf_fields[$this->column_key]['taxonomy']);
            $checked_ids = !is_array(isset($this->product[$this->column_key])) ? unserialize($this->product[$this->column_key]) : $this->product[$this->column_key];
            if (!empty($checked_ids)) {
                $checked = get_terms([
                    'taxonomy' => $this->acf_taxonomy_name,
                    'hide_empty' => false,
                    'include' => $checked_ids,
                    'fields' => 'id=>name'
                ]);
            } else {
                $checked = [];
            }
            if (!empty($checked) && is_array($checked)) {
                $checked_iteration = 1;
                foreach ($checked as $id => $name) {
                    $separate = '';
                    if ($checked_iteration < count($checked)) {
                        $separate = ", ";
                    }
                    $values .= '<span class="wcbel-category-item">' . esc_html($name) . $separate . ' </span>';
                    $checked_iteration++;
                }
            }
        } else {
            $values = get_the_term_list($this->product['id'], $this->column_key, '<span class="wcbel-category-item">', ', </span><span class="wcbel-category-item">', '</span>');
        }

        if (mb_substr($this->decoded_column_key, 0, 3) == 'pa_') {
            $attributes = (!empty($this->product['custom_field']['_product_attributes'][0])) ? unserialize($this->product['custom_field']['_product_attributes'][0]) : [];
            $is_visible = (isset($attributes[$this->decoded_column_key]['is_visible']) && $attributes[$this->decoded_column_key]['is_visible'] == true) ? 'true' : 'false';
            $is_variation = (isset($attributes[$this->decoded_column_key]['is_variation']) && $attributes[$this->decoded_column_key]['is_variation'] == true) ? 'true' : 'false';

            $output['field'] = "<span data-toggle='modal' class='wcbel-is-attribute-modal wcbel-product-attribute' data-is-visible='{$is_visible}' data-is-variation='{$is_variation}' data-target='#wcbel-modal-attribute-" . esc_attr($this->column_key) . "-" . esc_attr($this->product['id']) . "' data-item-id='" . esc_attr($this->product['id']) . "'>";
            $output['field'] .= (!empty($values)) ? strip_tags(wp_kses($values, Sanitizer::allowed_html_tags()), '<span><ul><label><li>') : 'No items';
            $output['field'] .= "</span>";
            $output['includes'] = WCBEL_VIEWS_DIR . 'bulk_edit/columns_modals/product_attribute.php';
        } else {
            if (isset($this->acf_fields[$this->column_key]['taxonomy'])) {
                $output['field'] = "<span data-toggle='modal' class='wcbel-is-taxonomy-modal wcbel-acf-taxonomy-multi-select' data-target='#wcbel-modal-multi-select-" . esc_attr($this->column_key) . "-" . esc_attr($this->product['id']) . "' data-item-id='" . esc_attr($this->product['id']) . "'>";
                $output['field'] .= (!empty($values)) ? strip_tags(wp_kses($values, Sanitizer::allowed_html_tags()), '<span><ul><label><li>') : 'No items';
                $output['field'] .= "</span>";
                $output['includes'] = WCBEL_VIEWS_DIR . 'bulk_edit/columns_modals/acf_taxonomy_multi_select.php';
            } else {
                $output['field'] = "<span data-toggle='modal' class='wcbel-is-taxonomy-modal wcbel-product-taxonomy' data-target='#wcbel-modal-taxonomy-" . esc_attr($this->column_key) . "-" . esc_attr($this->product['id']) . "' data-item-id='" . esc_attr($this->product['id']) . "'>";
                $output['field'] .= (!empty($values)) ? strip_tags(wp_kses($values, Sanitizer::allowed_html_tags()), '<span><ul><label><li>') : 'No items';
                $output['field'] .= "</span>";
                $output['includes'] = WCBEL_VIEWS_DIR . 'bulk_edit/columns_modals/product_taxonomy.php';
            }
        }

        return $output;
    }

    private function yith_product_badge_field()
    {
        $output = "";
        if (defined("YITH_WCBM_INIT")) {
            // is premium plugin - multiple
            $output = "<button type='button' data-toggle='modal' data-target='#wcbel-modal-product-badges' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . __('Badges', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</button>";
        } else {
            // is free plugin - single
            $product_badges = get_posts(['post_type' => 'yith-wcbm-badge', 'posts_per_page' => -1, 'order' => 'ASC']);
            $output = "<select class='wcbel-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->product['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' data-field-type='" . esc_attr($this->field_type) . "'>";
            $output .= "<option value=''>" . __('No badge', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</option>";
            if (!empty($product_badges)) {
                foreach ($product_badges as $badge) {
                    if ($badge instanceof \WP_Post) {
                        if (is_array($this->product[$this->decoded_column_key])) {
                            $selected = (in_array($badge->ID, $this->product[$this->decoded_column_key])) ? 'selected' : '';
                        } else {
                            $selected = ($badge->ID == $this->product[$this->decoded_column_key]) ? 'selected' : '';
                        }
                        $output .= "<option value='{$badge->ID}' $selected>{$badge->post_title}</option>";
                    }
                }
            }
            $output .= '</select>';
        }

        return $output;
    }

    private function ithemeland_badge_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-ithemeland-badge' class='wcbel-button wcbel-button-white wcbel-ithemeland-badge-button' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . __('iThemeland badge', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</button>";
    }

    private function yikes_custom_product_tabs_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-yikes-custom-product-tabs' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . __('Custom Tabs', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</button>";
    }

    private function it_wc_dynamic_pricing_all_fields_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-it-wc-dynamic-pricing-all-fields' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "'data-item-type='" . esc_attr($this->product['type']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . __('All Fields', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</button>";
    }

    private function it_pricing_rules_product_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-it-wc-dynamic-pricing' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . __('Set Price', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</button>";
    }

    private function it_wc_dynamic_pricing_select_roles_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wcbel-modal-it-wc-dynamic-pricing-select-roles' class='wcbel-button wcbel-button-white' data-item-id='" . esc_attr($this->product['id']) . "' data-item-name='" . esc_attr($this->product['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . __('Select Roles', 'ithemeland-bulk-product-editing-lite-for-woocommerce') . "</button>";
    }

    private function get_taxonomy_terms($taxonomy)
    {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        $options = [];
        if (!empty($terms) && count($terms)) {
            foreach ($terms as $term) {
                if ($term instanceof \WP_Term) {
                    $options[$term->term_id] = $term->name;
                }
            }
        }

        return $options;
    }
}
