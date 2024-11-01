<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!($attribute_term instanceof \WP_Term)) {
    return false;
}
?>

<li>
    <label>
        <input type="checkbox" class="iwbvel-product-attribute-term-item" name="attributes[<?php echo esc_attr($attribute_term->taxonomy); ?>][value][]" data-term-name="<?php echo esc_attr($attribute_term->name); ?>" data-term-slug="<?php echo esc_attr($attribute_term->slug); ?>" value="<?php echo esc_attr($attribute_term->term_id); ?>">
        <span><?php echo esc_html($attribute_term->name); ?></span>
    </label>
</li>