<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-settings">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('Settings', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="height: calc(100% - 45px);">
                    <?php wp_nonce_field('wpbel_post_nonce'); ?>
                    <input type="hidden" name="action" value="wpbel_settings">
                    <div class="wpbel-float-side-modal-body">
                        <div class="wpbel-wrap">
                            <input type="hidden" name="action" value="wpbel_settings">
                            <div class="wpbel-tab-middle-content">
                                <div class="wpbel-alert wpbel-alert-default">
                                    <span><?php esc_html_e('You can set bulk editor settings', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                </div>
                                <div class="wpbel-form-group">
                                    <label for="wpbel-settings-count-per-page"><?php esc_html_e('Count Per Page', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select name="count_per_page" id="wpbel-settings-count-per-page" title="The number of posts per page">
                                        <?php
                                        if (!empty($count_per_page_items)) :
                                            foreach ($count_per_page_items as $count_per_page_item) :
                                        ?>
                                                <option value="<?php echo intval($count_per_page_item); ?>" <?php if (isset($settings['count_per_page']) && $settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                                                    <?php echo esc_html($count_per_page_item); ?>
                                                </option>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                                <div class="wpbel-form-group">
                                    <label for="wpbel-settings-default-sort-by"><?php esc_html_e('Default Sort By', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbel-settings-default-sort-by" class="wpbel-input-md" name="default_sort_by">
                                        <option value="id" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'id') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('ID', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="title" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'title') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Title', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbel-form-group">
                                    <label for="wpbel-settings-default-sort"><?php esc_html_e('Default Sort', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select name="default_sort" id="wpbel-settings-default-sort" class="wpbel-input-md">
                                        <option value="ASC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'ASC') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('ASC', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="DESC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'DESC') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('DESC', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbel-form-group">
                                    <label for="wpbel-settings-close-popup-after-applying"><?php esc_html_e('Close popup after applying', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[close_popup_after_applying]" id="wpbel-settings-close-popup-after-applying" class="wpbel-input-md">
                                        <option value="yes" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbel-form-group">
                                    <label for="wpbel-settings-sticky-first-columns"><?php esc_html_e("Sticky 'ID' & 'Title' Columns", 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select name="sticky_first_columns" id="wpbel-settings-sticky-first-columns" class="wpbel-input-md">
                                        <option value="yes" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbel-form-group">
                                    <label for="wpbel-settings-display-full-columns-title"><?php esc_html_e('Display Columns Label', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select name="display_full_columns_title" id="wpbel-settings-display-full-columns-title" class="wpbel-input-md">
                                        <option value="yes" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Completely', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('In short', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbel-form-group">
                                    <label for="wpbel-settings-keep-filled-data-in-bulk-edit-form"><?php esc_html_e('Keep filled data in bulk edit form', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select name="keep_filled_data_in_bulk_edit_form" id="wpbel-settings-keep-filled-data-in-bulk-edit-form" class="wpbel-input-md">
                                        <option value="yes" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpbel-float-side-modal-footer">
                        <button type="submit" class="wpbel-button wpbel-button-blue">
                            <?php $img = WPBEL_IMAGES_URL . 'save.svg'; ?>
                            <img src="<?php echo esc_url($img); ?>" alt="">
                            <span><?php esc_html_e('Save Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>