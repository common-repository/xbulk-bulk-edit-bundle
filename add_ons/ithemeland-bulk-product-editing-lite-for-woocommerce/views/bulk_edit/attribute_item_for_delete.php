<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($attribute_name)) : ?>
    <div class='wcbel-variation-bulk-edit-attribute-item' data-id='<?php echo "wcbel-variation-bulk-edit-delete-attribute-item-" . esc_attr($attribute_name); ?>'>
        <label><?php echo esc_html($attribute_name); ?></label>
        <select title="Select attribute" data-name="<?php echo esc_attr($attribute_name); ?>" class="wcbel-w100p">
            <?php if (!empty($values)) : ?>
                <?php foreach ($values as $value_item) : ?>
                    <option value="<?php echo esc_attr(urldecode($value_item->slug)); ?>"><?php echo esc_html(urldecode($value_item->name)); ?></option>';
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
<?php endif; ?>