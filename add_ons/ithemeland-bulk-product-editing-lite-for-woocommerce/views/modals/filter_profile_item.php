<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($filter_item)) : ?>
    <tr class="<?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'wcbel-filter-profile-loaded' : ''; ?>">
        <td>
            <span class="wcbel-history-name"><?php echo esc_html($filter_item['name']); ?></span>
        </td>
        <td><?php echo esc_html(gmdate('Y M d', strtotime($filter_item['date_modified']))); ?></td>
        <td>
            <input type="radio" class="wcbel-filter-profile-use-always-item" name="use_always" value="<?php echo esc_attr($filter_item['key']); ?>" <?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'checked="checked"' : ''; ?> title="<?php esc_html_e('Use it constantly', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        </td>
        <td>
            <button type="button" class="wcbel-button wcbel-button-blue wcbel-bulk-edit-filter-profile-load" value="<?php echo esc_attr($filter_item['key']); ?>">
                <i class="wcbel-icon-download-cloud"></i>
                Load
            </button>
            <?php if ($filter_item['key'] != 'default') : ?>
                <button type="button" class="wcbel-button wcbel-button-red wcbel-bulk-edit-filter-profile-delete" value="<?php echo esc_attr($filter_item['key']); ?>">
                    <i class="wcbel-icon-trash-2"></i>
                    <?php esc_html_e('Delete', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                </button>
            <?php endif; ?>
        </td>
    </tr>
<?php endif; ?>