<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-settings">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Settings', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="float: left; width: 100%; height: 100%;">
                    <?php wp_nonce_field('wcbel_post_nonce'); ?>
                    <div class="wcbel-float-side-modal-body">
                        <div class="wcbel-wrap">
                            <input type="hidden" name="action" value="wcbel_settings">
                            <div class="wcbel-alert wcbel-alert-default">
                                <span><?php esc_html_e('You can set bulk editor settings', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-count-per-page"><?php esc_html_e('Count Per Page', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[count_per_page]" id="wcbel-settings-count-per-page" title="The number of products per page">
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
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-default-sort-by"><?php esc_html_e('Default Sort By', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select id="wcbel-settings-default-sort-by" class="wcbel-input-md" name="settings[default_sort_by]">
                                    <option value="id" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'id') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('ID', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="title" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'title') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Title', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="regular_price" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'regular_price') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Regular price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="sale_price" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'sale_price') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Sale price', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="sku" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'sku') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('SKU', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="manage_stock" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'manage_stock') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Manage Stock', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="stock_quantity" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'stock_quantity') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Stock Quantity', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="stock_status" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'stock_status') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Stock Status', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-default-sort"><?php esc_html_e('Default Sort', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[default_sort]" id="wcbel-settings-default-sort" class="wcbel-input-md">
                                    <option value="ASC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'ASC') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('ASC', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="DESC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'DESC') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('DESC', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-close-popup-after-applying"><?php esc_html_e('Close popup after applying', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[close_popup_after_applying]" id="wcbel-settings-close-popup-after-applying" class="wcbel-input-md">
                                    <option value="yes" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-sticky-first-columns"><?php esc_html_e("Sticky 'ID' & 'Title' Columns", 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[sticky_first_columns]" id="wcbel-settings-sticky-first-columns" class="wcbel-input-md">
                                    <option value="yes" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-display-full-columns-title"><?php esc_html_e('Display Columns Label', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[display_full_columns_title]" id="wcbel-settings-display-full-columns-title" class="wcbel-input-md">
                                    <option value="yes" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Completely', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('In short', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-enable-thumbnail-popup"><?php esc_html_e('Enable Thumbnail Popup', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[enable_thumbnail_popup]" id="wcbel-settings-enable-thumbnail-popup" class="wcbel-input-md">
                                    <option value="yes" <?php echo (isset($settings['enable_thumbnail_popup']) && $settings['enable_thumbnail_popup'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="no" <?php echo (isset($settings['enable_thumbnail_popup']) && $settings['enable_thumbnail_popup'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-keep-filled-data-in-bulk-edit-form"><?php esc_html_e('Keep filled data in bulk edit form', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[keep_filled_data_in_bulk_edit_form]" id="wcbel-settings-keep-filled-data-in-bulk-edit-form" class="wcbel-input-md">
                                    <option value="no" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="yes" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wcbel-form-group">
                                <label for="wcbel-settings-show-only-filtered-variations"><?php esc_html_e('Show Only Filtered Variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select name="settings[show_only_filtered_variations]" id="wcbel-settings-show-only-filtered-variations" class="wcbel-input-md">
                                    <option value="no" <?php echo (isset($settings['show_only_filtered_variations']) && $settings['show_only_filtered_variations'] == 'no') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('No', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                    <option value="yes" <?php echo (isset($settings['show_only_filtered_variations']) && $settings['show_only_filtered_variations'] == 'yes') ? 'selected' : ''; ?>>
                                        <?php esc_html_e('Yes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="wcbel-float-side-modal-footer">
                        <button type="submit" class="wcbel-button wcbel-button-blue">
                            <?php $img = WCBEL_IMAGES_URL . 'save.svg'; ?>
                            <img src="<?php echo esc_url($img); ?>" alt="">
                            <span><?php esc_html_e('Save Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>