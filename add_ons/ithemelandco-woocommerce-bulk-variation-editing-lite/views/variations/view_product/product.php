<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($product_object) && $product_object instanceof \WC_Product_Variable) :
    $available_variations = $product_object->get_available_variations();
    $attributes = $product_object->get_variation_attributes();
    if (empty($available_variations)) {
        echo "Not available";
        return;
    }
    $selected_attributes = $product_object->get_default_attributes();
    $variable_image = (!empty($product_object->get_image_id())) ? wp_get_attachment_url($product_object->get_image_id()) : ITBBC_IMAGES_URL . "/woocommerce-placeholder.png";
    $image_items = '';
    $price_items = '';
    $available_attributes = [];

    if (!empty($available_variations)) {
        foreach ($available_variations as $variation) {
            $element_attrs = '';
            if (!empty($variation['attributes'])) {
                foreach ($variation['attributes'] as $name => $term) {
                    $attr_name = str_replace('attribute_pa_', 'pa_', $name);
                    $available_attributes[$attr_name][] = $term;
                    $element_attrs .= 'data-' . esc_attr($attr_name) . '="' . esc_attr($term) . '" ';
                }
            }

            $image_items .= (!empty($variation['image']['url'])) ? '<img class="variation-image" ' . $element_attrs . ' src="' . esc_url($variation['image']['url']) . '">' : '<img class="variation-image" ' . $element_attrs . ' src="' . ITBBC_IMAGES_URL . '/woocommerce-placeholder.png">';
            $price_items .= '<span class="iwbvel-variations-view-product-price-item" ' . $element_attrs . ' >Price: ' . $variation['price_html'] . '</span>';
        }
    }

    $available_attributes = array_map('array_unique', $available_attributes);

?>
    <div class="iwbvel-variations-view-product-left-side">
        <div class="iwbvel-variations-view-product-image">
            <img class="variable-image" src="<?php echo esc_url($variable_image); ?>" alt="<?php echo esc_attr($product_object->get_title()); ?>">
            <?php echo wp_kses($image_items, iwbvel\classes\helpers\Sanitizer::allowed_html()); ?>
        </div>
    </div>
    <div class="iwbvel-variations-view-product-right-side">
        <strong><?php echo esc_html($product_object->get_title()); ?></strong>

        <?php
        if (!empty($attributes)) :
            foreach ($attributes as $attribute_name => $options) :
                if (!isset($available_attributes[$attribute_name])) {
                    continue;
                }
        ?>
                <div class="iwbvel-variations-view-product-variation-attribute">
                    <span><?php echo esc_html(wc_attribute_label($attribute_name)); ?></span>
                    <select class="iwbvel-variations-view-product-variation-attribute-item" data-attribute="<?php echo esc_attr($attribute_name); ?>">
                        <option value="">Choose an option</option>
                        <?php
                        if (!empty($options)) :
                            foreach ($options as $option_slug) :
                                if (!in_array($option_slug, $available_attributes[$attribute_name])) {
                                    continue;
                                }
                                $term = get_term_by('slug', $option_slug, $attribute_name);
                                if (!($term instanceof \WP_Term)) {
                                    continue;
                                }
                        ?>
                                <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
        <?php
            endforeach;
        endif;
        ?>

        <div class="iwbvel-variations-view-product-price">
            <?php echo wp_kses($price_items, iwbvel\classes\helpers\Sanitizer::allowed_html()); ?>
        </div>
    </div>
<?php endif; ?>