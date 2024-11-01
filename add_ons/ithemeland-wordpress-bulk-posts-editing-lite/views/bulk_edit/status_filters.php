<?php

use wpbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($post_statuses) && is_array($post_statuses) && !empty($post_counts_by_status) && is_array($post_counts_by_status)) : ?>
    <ul>
        <li><button type="button" disabled data-status="all" class="wpbel-bulk-edit-status-filter-item all"><?php esc_html_e('All', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> (<?php echo isset($post_counts_by_status['all']) ? intval($post_counts_by_status['all']) : 0 ?>)</button></li>
        <?php foreach ($post_statuses as $status_key => $status_label) : ?>
            <?php if (isset($post_counts_by_status[$status_key])) : ?>
                <li><button disabled type="button" data-status="<?php echo esc_attr($status_key); ?>" class="wpbel-bulk-edit-status-filter-item <?php echo esc_attr($status_key); ?>"><?php echo wp_kses($status_label . ' (' . $post_counts_by_status[$status_key] . ')', Sanitizer::allowed_html_tags()); ?></button></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>