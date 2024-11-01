<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-variation-bulk-edit-current-item">
    <label class="wcbel-variation-bulk-edit-current-item-name">
        <input type="checkbox" name="variation_item[]" value="<?php echo (!empty($variation_id)) ? esc_attr($variation_id) : ''; ?>">
        <span><?php echo (!empty($variation_attributes)) ? esc_html($variation_attributes) : ""; ?></span>
    </label>
</div>