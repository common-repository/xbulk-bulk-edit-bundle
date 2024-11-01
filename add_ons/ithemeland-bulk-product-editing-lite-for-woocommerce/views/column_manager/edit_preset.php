<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <?php wp_nonce_field('wcbel_post_nonce'); ?>
    <input type="hidden" name="action" value="wcbel_column_manager_edit_preset">
    <input type="hidden" name="preset_key" id="wcbel-column-manager-edit-preset-key" value="">
    <div class="wcbel-modal" id="wcbel-modal-column-manager-edit-preset">
        <div class="wcbel-modal-container">
            <div class="wcbel-modal-box wcbel-modal-box-lg">
                <div class="wcbel-modal-content">
                    <div class="wcbel-modal-title">
                        <h2><?php esc_html_e('Edit Column Preset', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                        <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                            <i class="wcbel-icon-x"></i>
                        </button>
                    </div>
                    <div class="wcbel-modal-body">
                        <div class="wcbel-wrap">
                            <div class="wcbel-column-manager-new-profile wcbel-mt0">
                                <div class="wcbel-column-manager-new-profile-left">
                                    <label class="wcbel-column-manager-check-all-fields-btn" data-action="edit">
                                        <input type="checkbox" class="wcbel-column-manager-check-all-fields">
                                        <span><?php esc_html_e('Select All', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                    </label>
                                    <input type="text" title="Search Field" data-action="edit" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" class="wcbel-column-manager-search-field">
                                    <div class="wcbel-column-manager-available-fields" data-action="edit">
                                        <ul>
                                            <?php if (!empty($column_items)) : ?>
                                                <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                    <li data-name="<?php echo esc_attr($column_key); ?>">
                                                        <label>
                                                            <input type="checkbox" data-name="<?php echo esc_attr($column_key); ?>" data-type="field" value="<?php echo esc_attr($column_field['label']); ?>">
                                                            <?php echo esc_html($column_field['label']); ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="wcbel-column-manager-new-profile-middle">
                                    <div class="wcbel-column-manager-middle-buttons">
                                        <div>
                                            <button type="button" data-action="edit" class="wcbel-button wcbel-button-lg wcbel-button-square-lg wcbel-button-blue wcbel-column-manager-add-field">
                                                <i class="wcbel-icon-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="wcbel-column-manager-new-profile-right">
                                    <div class="wcbel-column-manager-right-top">
                                        <input type="text" title="Profile Name" class="wcbel-w100p" id="wcbel-column-manager-edit-preset-name" name="preset_name" placeholder="<?php esc_html_e('Profile name ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                                    </div>
                                    <div class="wcbel-column-manager-added-fields wcbel-table-border-radius wcbel-mt10" data-action="edit">
                                        <div class="items"></div>
                                        <img src="<?php echo esc_url(WCBEL_IMAGES_URL . 'loading.gif'); ?>" alt="" class="wcbel-box-loading wcbel-hide">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wcbel-modal-footer">
                        <button type="submit" name="edit_preset" class="wcbel-button wcbel-button-blue"><?php esc_html_e('Save Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>