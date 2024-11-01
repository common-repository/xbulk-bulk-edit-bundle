<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($filter_item)) : ?>
    <tr class="<?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'iwbvel-filter-profile-loaded' : ''; ?>">
        <td>
            <span class="iwbvel-history-name"><?php echo esc_html($filter_item['name']); ?></span>
        </td>
        <td><?php echo esc_html(gmdate('Y M d', strtotime($filter_item['date_modified']))); ?></td>
        <td>
            <input type="radio" class="iwbvel-filter-profile-use-always-item" name="use_always" value="<?php echo esc_attr($filter_item['key']); ?>" <?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'checked="checked"' : ''; ?> title="<?php esc_html_e('Use it constantly', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
        </td>
        <td>
            <button type="button" class="iwbvel-button iwbvel-button-blue iwbvel-bulk-edit-filter-profile-load" value="<?php echo esc_attr($filter_item['key']); ?>">
                <i class="iwbvel-icon-download-cloud"></i>
                Load
            </button>
            <?php if ($filter_item['key'] != 'default') : ?>
                <button type="button" class="iwbvel-button iwbvel-button-red iwbvel-bulk-edit-filter-profile-delete" value="<?php echo esc_attr($filter_item['key']); ?>">
                    <i class="iwbvel-icon-trash-2"></i>
                    <?php esc_html_e('Delete', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </button>
            <?php endif; ?>
        </td>
    </tr>
<?php endif; ?>