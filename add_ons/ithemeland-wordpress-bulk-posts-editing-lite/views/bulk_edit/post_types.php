<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-top-nav-filters-switcher">
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbel-switcher-form">
        <?php wp_nonce_field('wpbel_post_nonce'); ?>
        <input type="hidden" name="action" value="wpbel_switcher">
        <input type="hidden" name="item_id" value="<?php echo (!empty($_GET['id'])) ? intval($_GET['id']) : 0; ?>">
        <label for="wpbel-switcher"><?php esc_html_e('Select post type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
        <select id="wpbel-switcher" name="post_type" title="<?php esc_html_e('Select post type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
            <?php if (!empty($post_types)) : ?>
                <?php foreach ($post_types as $post_type_key => $post_type_label) : ?>
                    <option value="<?php echo esc_attr($post_type_key) ?>" <?php echo ($GLOBALS['wpbel_common']['active_post_type'] == $post_type_key) ? 'selected' : ''; ?>>
                        <?php echo esc_html($post_type_label); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </form>
</div>