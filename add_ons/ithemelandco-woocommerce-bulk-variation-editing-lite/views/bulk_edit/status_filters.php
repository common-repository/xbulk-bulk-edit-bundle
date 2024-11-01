<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($product_statuses) && is_array($product_statuses) && !empty($product_counts_by_status) && is_array($product_counts_by_status)) : ?>
    <ul>
        <li><button type="button" data-status="all" class="iwbvel-bulk-edit-status-filter-item all"><?php esc_html_e('All', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> (<?php echo isset($product_counts_by_status['all']) ? intval($product_counts_by_status['all']) : 0 ?>)</button></li>
        <?php foreach ($product_statuses as $status_key => $status_label) : ?>
            <?php if (isset($product_counts_by_status[$status_key])) : ?>
                <li><button type="button" data-status="<?php echo esc_attr($status_key); ?>" class="iwbvel-bulk-edit-status-filter-item <?php echo esc_attr($status_key); ?>"><?php echo esc_html($status_label . ' (' . $product_counts_by_status[$status_key] . ')'); ?></button></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>