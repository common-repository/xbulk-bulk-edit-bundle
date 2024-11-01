<?php

namespace wpbel\classes\providers\column;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Setting;
use wpbel\classes\helpers\Meta_Field as Meta_Field_Helper;
use wpbel\classes\helpers\Render;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Meta_Field;

class PostColumnProvider
{
    private static $instance;

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

    public function get_item_columns($item, $columns)
    {
        if ($item instanceof \WP_Post) {
            $output['includes'] = [];
            $post_repository = new Post();
            $setting_repository = new Setting();
            $settings = $setting_repository->get_settings();
            $sticky_first_columns = isset($settings['sticky_first_columns']) ? $settings['sticky_first_columns'] : 'yes';
            $post_object = $item;
            $post = $post_repository->get_post_fields($post_object);
            $output['items'] = '<tr data-item-id="' . esc_attr($post['id']) . '" data-item-type="' . esc_attr($post['post_type']) . '">';

            if (Column::SHOW_ID_COLUMN === true) {
                $delete_type = 'trash';
                $delete_label = esc_html__('Delete post', 'ithemeland-wordpress-bulk-posts-editing-lite');
                $restore_button = '';
                $view_button = '';
                $edit_button = '';

                if ($post['post_status'] == 'trash') {
                    $delete_type = 'permanently';
                    $delete_label = esc_html__('Delete permanently', 'ithemeland-wordpress-bulk-posts-editing-lite');
                    $restore_button = '<button type="button" style="height: 28px;" class="wpbel-ml5 wpbel-button-flat wpbel-text-green wpbel-float-right wpbel-restore-item-btn" data-item-id="' . esc_attr($post['id']) . '" title="' . esc_html__('Restore', 'ithemeland-wordpress-bulk-posts-editing-lite') . '"><span class="wpbel-icon-rotate-cw"></span></button>';
                } else {
                    $view_button = '<a href="' . esc_url(get_the_permalink($post['id'])) . '" target="_blank" style="height: 28px;" title="View on site" class="wpbel-item-view-icon wpbel-ml5 wpbel-float-right"><span style="vertical-align: middle;" class="wpbel-icon-eye1"></span></a>';
                    $edit_button = '<a href="' . admin_url("post.php?post=" . intval($post['id']) . "&action=edit") . '" style="height: 28px;" target="_blank" class="wpbel-ml5 wpbel-float-right" title="Edit Post"><span style="vertical-align: middle;" class="wpbel-icon-pencil"></span></a>';
                }

                $sticky_class = ($sticky_first_columns == 'yes') ? 'wpbel-td-sticky wpbel-td-sticky-id wpbel-gray-bg' : '';
                $output['items'] .= '<td data-item-id="' . esc_attr($post['id']) . '" data-item-title="' . esc_attr($post['post_title']) . '" data-col-title="ID" class="' . esc_attr($sticky_class) . '">';
                $output['items'] .= '<label class="wpbel-td140">';
                $output['items'] .= '<input type="checkbox" class="wpbel-check-item" data-item-type="' . esc_attr($post['post_type']) . '" value="' . esc_attr($post['id']) . '" title="Select Item">';
                $output['items'] .= intval($post['id']);
                $output['items'] .= $restore_button;
                $output['items'] .= $view_button;
                $output['items'] .= '<button type="button" class="wpbel-ml5 wpbel-button-flat wpbel-text-red wpbel-float-right wpbel-delete-item-btn" data-delete-type="' . esc_attr($delete_type) . '" data-item-id="' . esc_attr($post['id']) . '" title="' . $delete_label . '"><span class="wpbel-icon-trash-2"></span></button>';
                $output['items'] .= $edit_button;
                $output['items'] .= "</label>";
                $output['items'] .= "</td>";
            }
            if (!empty(Column::get_static_columns())) {
                foreach (Column::get_static_columns() as $static_column) {
                    $sticky_class = ($sticky_first_columns == 'yes') ? 'wpbel-td-sticky wpbel-td-sticky-title wpbel-gray-bg' : '';
                    $output['items'] .= '<td class="' . esc_attr($sticky_class) . '" data-item-id="' . esc_attr($post['id']) . '" data-item-title="' . esc_attr($post[$static_column['field']]) . '" data-col-title="' . esc_attr($static_column['title']) . '" data-field="' . esc_attr($static_column['field']) . '" data-field-type="" data-content-type="text" data-action="inline-editable">';
                    $output['items'] .= '<span data-action="inline-editable" class="wpbel-td160">' . wp_kses($post[$static_column['field']], Sanitizer::allowed_html_tags()) . '</span>';
                    $output['items'] .= '</td>';
                }
            }
            if (!empty($columns) && is_array($columns)) {
                foreach ($columns as $key => $column) {
                    $key_decoded = $key;
                    $field_type = '';
                    $key = urlencode($key);
                    if (isset($column['field_type'])) {
                        switch ($column['field_type']) {
                            case 'custom_field':
                                $field_type = 'custom_field';
                                $post[$key] = (isset($post['custom_field'][$key])) ? $post['custom_field'][$key][0] : '';
                                break;
                            default:
                                break;
                        }
                    }
                    $background_color = (!empty($column['background_color']) && $column['background_color'] != '#fff' && $column['background_color'] != '#ffffff') ? 'background:' . esc_attr($column['background_color']) . ';' : '';
                    $text_color = (!empty($column['text_color'])) ? 'color:' . esc_attr($column['text_color']) . ';' : '';
                    $output['items'] .= '<td data-item-id="' . esc_attr($post['id']) . '" data-item-title="' . esc_attr($post['post_title']) . '" data-col-title="' . esc_attr($column['title']) . '" data-field="' . esc_attr($key_decoded) . '" data-field-type="' . esc_attr($field_type) . '" style="' . esc_attr($background_color) . ' ' . esc_attr($text_color) . '" ';
                    if ($column['editable'] === true && !in_array($column['content_type'], ['multi_select', 'multi_select_attribute'])) {
                        $output['items'] .= 'data-content-type="' . esc_attr($column['content_type']) . '" data-action="inline-editable"';
                    }
                    $output['items'] .= '>';

                    if ($column['editable'] === true) {
                        switch ($column['content_type']) {
                            case 'text':
                                $value = (is_array($post[$key])) ? implode(',', $post[$key]) : $post[$key];
                                $output['items'] .= "<span data-action='inline-editable' class='wpbel-td160'>" . esc_html($value) . "</span>";
                                break;
                            case 'textarea':
                                $output['items'] .= "<button type='button' data-toggle='modal' data-target='#wpbel-modal-text-editor' class='wpbel-button wpbel-button-white wpbel-load-text-editor' data-item-id='" . esc_attr($post['id']) . "' data-item-name='" . esc_attr($post['post_title']) . "' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "'><i class='dashicons dashicons-edit'></i></button>";
                                break;
                            case 'image':
                                $image = !empty($post[$key]['small']) ? wp_kses($post[$key]['small'], Sanitizer::allowed_html_tags()) : '<img src="' . esc_url(WPBEL_IMAGES_URL . "no-image-small.png") . '" width="40" height="40">';
                                $image_id = !empty($post[$key]['id']) ? $post[$key]['id'] : '';
                                $full_size = wp_get_attachment_image_src($image_id, 'full');
                                $full_size = (!empty($full_size[0])) ? $full_size[0] : esc_url(WPBEL_IMAGES_URL . "no-image.png");
                                $output['items'] .= "<span data-toggle='modal' data-target='#wpbel-modal-image' data-id='wpbel-" . esc_attr($key) . "-" . esc_attr($post['id']) . "' class='wpbel-image-inline-edit' data-full-image-src='" . esc_url($full_size) . "' data-image-id='" . esc_attr($image_id) . "'>{$image}</span>";
                                break;
                            case 'numeric':
                                $output['items'] .= "<span data-action='inline-editable' class='wpbel-numeric-content wpbel-td120'>" . esc_html($post[$key]) . "</span><button type='button' data-toggle='modal' class='wpbel-calculator' data-field='" . esc_attr($key_decoded) . "' data-item-id='" . esc_attr($post['id']) . "' data-item-name='" . esc_attr($post['post_title']) . "' data-field-type='" . esc_attr($field_type) . "' data-target='#wpbel-modal-numeric-calculator'></button>";
                                break;
                            case 'numeric_without_calculator':
                                $output['items'] .= "<span data-action='inline-editable' class='wpbel-numeric-content wpbel-td120'>" . esc_html($post[$key]) . "</span>";
                                break;
                            case 'checkbox':
                                $checked = ($post[$key]) ? 'checked="checked"' : '';
                                $label = ($post[$key]) ? 'Yes' : 'No';
                                $output['items'] .= "<label><input type='checkbox' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' value='yes' class='wpbel-dual-mode-checkbox wpbel-inline-edit-action' " . esc_attr($checked) . "><span>" . esc_html($label) . "</span></label>";
                                break;
                            case 'select':
                                $output['items'] .= "<select class='wpbel-inline-edit-action' data-field='" . esc_attr($key_decoded) . "' data-item-id='" . esc_attr($post['id']) . "' title='Select " . esc_attr($column['label']) . "'>";
                                if (!empty($column['options'])) {
                                    foreach ($column['options'] as $option_key => $option_value) {
                                        $selected = ($option_key == $post[$key]) ? 'selected' : '';
                                        $output['items'] .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                                    }
                                } else {
                                    if ($column['field_type'] == 'custom_field') {
                                        $meta_field_repo = new Meta_Field();
                                        $meta_fields = $meta_field_repo->get();
                                        if (!empty($meta_fields[$column['name']]) && !empty($meta_fields[$column['name']]['key_value'])) {
                                            $options = Meta_Field_Helper::key_value_field_to_array($meta_fields[$column['name']]['key_value']);
                                            if (!empty($options) && is_array($options)) {
                                                foreach ($options as $option_key => $option_value) {
                                                    $selected = isset($post[$key_decoded]) && $post[$key_decoded] == $option_key ? 'selected' : '';
                                                    $output['items'] .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                                                }
                                            }
                                        }
                                    }
                                }
                                $output['items'] .= '</select>';
                                break;
                            case 'select_post':
                                $output['items'] .= "<button type='button' data-toggle='modal' data-target='#wpbel-modal-select-post' class='wpbel-button wpbel-button-white' data-parent-id='" . esc_attr($post['post_parent']) . "' data-item-id='" . esc_attr($post['id']) . "' data-item-name='" . esc_attr($post['post_title']) . "' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "'>Select</button>";
                                break;
                            case 'date':
                                $date = (!empty($post[$key])) ? gmdate('Y/m/d', strtotime($post[$key])) : '';
                                $clear_button = ($key != 'post_date') ? "<button type='button' class='wpbel-clear-date-btn wpbel-inline-edit-clear-date' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' value=''><img src='" . esc_url(WPBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
                                $output['items'] .= "<input type='text' class='wpbel-datepicker wpbel-inline-edit-action' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' title='Select " . esc_attr($column['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html_tags());
                                break;
                            case 'date_time':
                                $date = (!empty($post[$key])) ? gmdate('Y/m/d H:i', strtotime($post[$key])) : '';
                                $clear_button = ($key != 'post_date') ? "<button type='button' class='wpbel-clear-date-btn wpbel-inline-edit-clear-date' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' value=''><img src='" . esc_url(WPBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
                                $output['items'] .= "<input type='text' class='wpbel-datetimepicker wpbel-inline-edit-action' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' title='Select " . esc_attr($column['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html_tags());
                                break;
                            case 'multi_select':
                                $values = get_the_term_list($post['id'], $key_decoded, '<span class="wpbel-category-item">', ', </span><span class="wpbel-category-item">', '</span>');
                                $output['items'] .= "<span data-toggle='modal' class='wpbel-is-taxonomy-modal wpbel-post-taxonomy' data-target='#wpbel-modal-taxonomy-" . esc_attr($key_decoded) . "-" . esc_attr($post['id']) . "' data-item-id='" . esc_attr($post['id']) . "'>";
                                $output['items'] .= (!empty($values)) ? strip_tags(wp_kses($values, Sanitizer::allowed_html_tags()), '<span>') : 'No items';
                                $output['items'] .= "</span>";
                                $output['includes'][] = Render::html(WPBEL_VIEWS_DIR . 'bulk_edit/columns_modals/post_taxonomy.php', compact('post', 'key_decoded', 'key', 'field_type'));
                                break;
                            default:
                                $value = (is_array($post[$key])) ? implode(',', $post[$key]) : $post[$key];
                                $output['items'] .= $value;
                                break;
                        }
                    } else {
                        if (!empty($post[$key])) {
                            $output['items'] .= (is_array($post[$key])) ? wp_kses(implode(',', $post[$key]), Sanitizer::allowed_html_tags()) : wp_kses($post[$key], Sanitizer::allowed_html_tags());
                        } else {
                            $output['items'] .= ' ';
                        }
                    }

                    $output['items'] .= '</td>';
                }
            }
            $output['items'] .= "</tr>";

            return $output;
        }
    }
}
