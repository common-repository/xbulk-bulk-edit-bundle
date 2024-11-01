<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($attribute_name)) : ?>
    <?php if (!empty($values)) : ?>
        <?php foreach ($values as $value_item) : ?>
            <option value="<?php echo intval($value_item->term_id); ?>"><?php echo esc_html(urldecode($value_item->name)); ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>