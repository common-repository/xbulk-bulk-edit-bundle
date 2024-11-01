<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-settings">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Settings', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="float: left; width: 100%; height: 100%;">
                    <div class="iwbvel-float-side-modal-body">
                        <div class="iwbvel-wrap">
                            <input type="hidden" name="action" value="iwbvel_settings">
                            <?php wp_nonce_field('iwbvel_settings'); ?>
                            <div class="iwbvel-alert iwbvel-alert-default">
                                <span><?php esc_html_e('You can set bulk editor settings', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-count-per-page"><?php esc_html_e('Count Per Page', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[count_per_page]" id="iwbvel-settings-count-per-page" title="The number of products per page">
                                    <?php
                                    if (!empty($count_per_page_items)) :
                                        foreach ($count_per_page_items as $count_per_page_item) :
                                    ?>
                                            <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if (isset($settings['count_per_page']) && $settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                                                <?php echo esc_html($count_per_page_item); ?>
                                            </option>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-default-sort-by"><?php esc_html_e('Default Sort By', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select id="iwbvel-settings-default-sort-by" class="iwbvel-input-md" name="settings[default_sort_by]">
                                    <option value="id" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'id') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('ID', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="title" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'title') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Title', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="regular_price" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'regular_price') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Regular price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="sale_price" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'sale_price') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="sku" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'sku') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('SKU', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="manage_stock" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'manage_stock') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Manage Stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="stock_quantity" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'stock_quantity') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Stock Quantity', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="stock_status" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'stock_status') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Stock Status', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-default-sort"><?php esc_html_e('Default Sort', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[default_sort]" id="iwbvel-settings-default-sort" class="iwbvel-input-md">
                                    <option value="ASC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'ASC') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('ASC', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="DESC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'DESC') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('DESC', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-close-popup-after-applying"><?php esc_html_e('Close popup after applying', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[close_popup_after_applying]" id="iwbvel-settings-close-popup-after-applying" class="iwbvel-input-md">
                                    <option value="yes" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-sticky-first-columns"><?php esc_html_e("Sticky 'ID' & 'Title' Columns", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[sticky_first_columns]" id="iwbvel-settings-sticky-first-columns" class="iwbvel-input-md">
                                    <option value="yes" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-display-full-columns-title"><?php esc_html_e('Display Columns Label', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[display_full_columns_title]" id="iwbvel-settings-display-full-columns-title" class="iwbvel-input-md">
                                    <option value="yes" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Completely', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('In short', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-enable-thumbnail-popup"><?php esc_html_e('Enable Thumbnail Popup', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[enable_thumbnail_popup]" id="iwbvel-settings-enable-thumbnail-popup" class="iwbvel-input-md">
                                    <option value="yes" <?php echo (isset($settings['enable_thumbnail_popup']) && $settings['enable_thumbnail_popup'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['enable_thumbnail_popup']) && $settings['enable_thumbnail_popup'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-keep-filled-data-in-bulk-edit-form"><?php esc_html_e('Keep filled data in bulk edit form', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[keep_filled_data_in_bulk_edit_form]" id="iwbvel-settings-keep-filled-data-in-bulk-edit-form" class="iwbvel-input-md">
                                    <option value="no" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="yes" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="iwbvel-form-group">
                                <label for="iwbvel-settings-show-only-filtered-variations"><?php esc_html_e('Show Only Filtered Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select name="settings[show_only_filtered_variations]" id="iwbvel-settings-show-only-filtered-variations" class="iwbvel-input-md">
                                    <option value="no" <?php echo (isset($settings['show_only_filtered_variations']) && $settings['show_only_filtered_variations'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                    <option value="yes" <?php echo (isset($settings['show_only_filtered_variations']) && $settings['show_only_filtered_variations'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="iwbvel-float-side-modal-footer">
                        <button type="submit" class="iwbvel-button iwbvel-button-blue">
                            <img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'save.svg'); ?>" alt="">
                            <span><?php esc_html_e('Save Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>