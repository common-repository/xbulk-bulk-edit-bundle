<?php if (!empty($filter_item)) : ?>
    <tr class="<?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'wcbef-filter-profile-loaded' : ''; ?>">
        <td>
            <span class="wcbef-history-name"><?php echo esc_html($filter_item['name']); ?></span>
        </td>
        <td><?php echo esc_html(date('Y M d', strtotime($filter_item['date_modified']))); ?></td>
        <td>
            <input type="radio" class="wcbef-filter-profile-use-always-item" name="use_always" value="<?php echo esc_attr($filter_item['key']); ?>" <?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'checked="checked"' : ''; ?> title="<?php esc_html_e('Use it constantly', 'woocommerce-bulk-edit-free'); ?>">
        </td>
        <td>
            <button type="button" class="wcbef-button wcbef-button-blue wcbef-bulk-edit-filter-profile-load" value="<?php echo esc_attr($filter_item['key']); ?>">
                <i class="lni lni-cloud-download"></i>
                Load
            </button>
            <?php if ($filter_item['key'] != 'default') : ?>
                <button type="button" class="wcbef-button wcbef-button-red wcbef-bulk-edit-filter-profile-delete" value="<?php echo esc_attr($filter_item['key']); ?>">
                    <i class="lni lni-trash"></i>
                    <?php esc_html_e('Delete', 'woocommerce-bulk-edit-free'); ?>
                </button>
            <?php endif; ?>
        </td>
    </tr>
<?php endif; ?>