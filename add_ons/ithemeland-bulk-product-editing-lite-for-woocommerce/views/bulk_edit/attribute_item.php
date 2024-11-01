<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($attribute_name)) : ?>
    <div class='wcbel-variation-bulk-edit-attribute-item' data-id='<?php echo esc_attr($attribute_name); ?>'>
        <label for='wcbel-variation-bulk-edit-attribute-item-<?php echo esc_attr($attribute_name); ?>'><?php echo esc_html($attribute_name); ?></label>
        <select id='wcbel-variation-bulk-edit-attribute-item-<?php echo esc_attr($attribute_name); ?>' data-attribute-name='<?php echo esc_attr($attribute_name); ?>' class='wcbel-select2-ajax' multiple>
            <?php if (!empty($values)) : ?>
                <?php foreach ($values as $value_item) : ?>
                    <option value="<?php echo esc_attr(urldecode($value_item->slug)); ?>" <?php echo (!empty($selected_values) && is_array($selected_values) && in_array(urldecode($value_item->slug), $selected_values)) ? 'selected' : ''; ?> data-id="<?php echo esc_attr($value_item->term_id); ?>"><?php echo esc_html(urldecode($value_item->name)); ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
<?php endif; ?>