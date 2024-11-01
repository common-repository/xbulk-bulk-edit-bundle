<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <?php wp_nonce_field('wpbel_post_nonce'); ?>
    <input type="hidden" name="action" value="wpbel_column_manager_edit_preset">
    <input type="hidden" name="preset_key" id="wpbel-column-manager-edit-preset-key" value="">
    <div class="wpbel-modal" id="wpbel-modal-column-manager-edit-preset">
        <div class="wpbel-modal-container">
            <div class="wpbel-modal-box wpbel-modal-box-lg">
                <div class="wpbel-modal-content">
                    <div class="wpbel-modal-title">
                        <h2><?php esc_html_e('Edit Column Preset', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                        <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                            <i class="wpbel-icon-x"></i>
                        </button>
                    </div>
                    <div class="wpbel-modal-body">
                        <div class="wpbel-wrap">
                            <div class="wpbel-column-manager-new-profile wpbel-mt0">
                                <div class="wpbel-column-manager-new-profile-left">
                                    <label class="wpbel-column-manager-check-all-fields-btn" data-action="edit">
                                        <input type="checkbox" class="wpbel-column-manager-check-all-fields">
                                        <span><?php esc_html_e('Select All', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                    </label>
                                    <input type="text" title="Search Field" data-action="edit" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" class="wpbel-column-manager-search-field">
                                    <div class="wpbel-column-manager-available-fields" data-action="edit">
                                        <ul>
                                            <?php if (!empty($column_items)) : ?>
                                                <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                    <li data-name="<?php echo esc_attr($column_key); ?>">
                                                        <label>
                                                            <input type="checkbox" data-name="<?php echo esc_attr($column_key); ?>" value="<?php echo esc_attr($column_field['label']); ?>">
                                                            <?php echo esc_html($column_field['label']); ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="wpbel-column-manager-new-profile-middle">
                                    <div class="wpbel-column-manager-middle-buttons">
                                        <div>
                                            <button type="button" data-action="edit" class="wpbel-button wpbel-button-lg wpbel-button-square-lg wpbel-button-blue wpbel-column-manager-add-field">
                                                <i class="wpbel-icon-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="wpbel-column-manager-new-profile-right">
                                    <div class="wpbel-column-manager-right-top">
                                        <input type="text" title="Profile Name" class="wpbel-w100p" id="wpbel-column-manager-edit-preset-name" name="preset_name" placeholder="<?php esc_html_e('Profile name ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                    </div>
                                    <div class="wpbel-column-manager-added-fields wpbel-table-border-radius wpbel-mt10" data-action="edit">
                                        <div class="items"></div>
                                        <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading.gif'); ?>" alt="" class="wpbel-box-loading wpbel-hide">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpbel-modal-footer">
                        <button type="submit" name="edit_preset" class="wpbel-button wpbel-button-blue"><?php esc_html_e('Save Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>