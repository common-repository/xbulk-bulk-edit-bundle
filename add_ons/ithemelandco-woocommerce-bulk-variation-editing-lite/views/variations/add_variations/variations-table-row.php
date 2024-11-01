<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($variation) && $variation instanceof \WC_Product_Variation) {
    $image_id = $variation->get_image_id();
    $full_size = wp_get_attachment_image_src($image_id, 'full');
    $full_size = (!empty($full_size[0])) ? $full_size[0] : esc_url(IWBVEL_IMAGES_URL . "/woocommerce-placeholder.png");
    $image_url = wp_get_attachment_image_src($variation->get_image_id(), [40, 40]);
    $image_url = !empty($image_url[0]) ? $image_url[0] : esc_url(IWBVEL_IMAGES_URL . "/woocommerce-placeholder-150x150.png");

    $attribute_input = '';
    $variation_attributes = $variation->get_variation_attributes();
    $attributes = '<div class="iwbvel-variations-table-name">';
    if (!empty($variation_attributes)) {
        $is_default = true;
        $i = 0;
        foreach ($variation_attributes as $attribute_key => $attribute_slug) {
            $attribute_key = str_replace('attribute_', '', $attribute_key);
            $attribute_input .= '<input type="hidden" class="iwbvel-variation-attributes-inputs" data-attribute="' . esc_attr($attribute_key) . '" data-term="' . esc_attr($attribute_slug) . '" name="variations[' . intval($variation->get_id()) . '][attributes][' . esc_attr($attribute_key) . ']" value="' . esc_attr($attribute_slug) . '">';
            if (empty($default_attributes) || !isset($default_attributes[$attribute_key]) || $default_attributes[$attribute_key] != $attribute_slug) {
                $is_default = false;
            }

            if ($i > 0) {
                $attributes .= '<br>';
            }

            $attribute_label = esc_html(wc_attribute_label($attribute_key));
            $attribute_label = (!empty($attribute_label)) ? $attribute_label : ucfirst(str_replace('pa_', '', $attribute_key));
            if ($attribute_slug === '') {
                $attributes .= $attribute_label . ': ' . strtolower(esc_html__('Any ', 'woocommerce')) . esc_html($attribute_label);
            } else {
                $term = get_term_by('slug', $attribute_slug, $attribute_key);
                if ($term instanceof \WP_Term) {
                    $attributes .= '<strong>' . $attribute_label . '</strong>: <span>' .  $term->name . '</span>';
                }
            }

            $i++;
        }
    }
    $attributes .= '</div>';

    $row = '';
    ob_start();
    include IWBVEL_VIEWS_DIR . "variations/add_variations/variations-raw-table-row.php";
    $row = ob_get_clean();
    $row = str_replace('{id}', esc_attr($variation->get_id()), $row);
    $row = str_replace('{attributes}', $attribute_input, $row);
    $row = str_replace('{name}', $attributes, $row);
    $row = str_replace('{enable_checked}', ($variation->get_status() == 'publish') ? 'checked="checked"' : '', $row);
    $row = str_replace('{default_checked}', (isset($is_default) && $is_default) ? 'checked="checked"' : '', $row);
    $row = str_replace('{regular_price}', esc_html($variation->get_regular_price()), $row);
    $row = str_replace('{sale_price}', esc_html($variation->get_sale_price()), $row);
    $row = str_replace('{stock_quantity}', esc_html($variation->get_stock_quantity()), $row);
    $row = str_replace('{thumbnail}', esc_url($image_url), $row);
    $row = str_replace('{thumbnail_full_size}', esc_url($full_size), $row);
    echo wp_kses($row, iwbvel\classes\helpers\Sanitizer::allowed_html());
}
