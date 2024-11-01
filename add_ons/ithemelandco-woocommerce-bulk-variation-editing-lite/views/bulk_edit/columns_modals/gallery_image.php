<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!empty($image_ids) && is_array($image_ids)) :
?>
    <?php foreach ($image_ids as $image_id) : ?>
        <div class="iwbvel-inline-edit-gallery-item">
            <?php echo wp_get_attachment_image(intval($image_id)); ?>
            <input type="hidden" class="iwbvel-inline-edit-gallery-image-ids" value="<?php echo intval($image_id); ?>">
            <button type="button" class="iwbvel-inline-edit-gallery-image-item-delete">x</button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>