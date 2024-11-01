<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-meta-fields">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('Meta Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wpbel-wrap">
                        <div class="wpbel-tab-middle-content">
                            <div class="wpbel-alert wpbel-alert-default">
                                <span><?php esc_html_e('You can add new posts meta fields in two ways: 1- Individually 2- Get from other post.', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                            </div>
                            <div class="wpbel-alert wpbel-alert-danger">
                                <span class="wpbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                <a href="<?php echo esc_url(WPBEL_UPGRADE_URL); ?>"><?php echo esc_html(WPBEL_UPGRADE_TEXT); ?></a>
                            </div>
                            <div class="wpbel-meta-fields-left">
                                <div class="wpbel-meta-fields-manual">
                                    <label for="wpbel-meta-fields-manual_key_name"><?php esc_html_e('Manually', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <div class="wpbel-meta-fields-manual-field">
                                        <input type="text" disabled="disabled" placeholder="<?php esc_html_e('Enter Meta Key ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                        <button type="button" class="wpbel-button wpbel-button-square wpbel-button-blue" disabled="disabled">
                                            <i class="wpbel-icon-plus1 wpbel-m0"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="wpbel-meta-fields-automatic">
                                    <label for="wpbel-meta-fields-automatic"><?php esc_html_e('Automatically From post', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <div class="wpbel-meta-fields-automatic-field">
                                        <input type="text" disabled="disabled" placeholder="<?php esc_html_e('Enter Post ID ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                        <button type="button" class="wpbel-button wpbel-button-square wpbel-button-blue" disabled="disabled">
                                            <i class="wpbel-icon-plus1 wpbel-m0"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                <?php wp_nonce_field('wpbel_post_nonce'); ?>
                                <div class="wpbel-meta-fields-right" id="wpbel-meta-fields-items">
                                    <p class="wpbel-meta-fields-empty-text" <?php echo (!empty($meta_fields)) ? 'style="display:none";' : ''; ?>>
                                        <?php echo esc_html__('Please add your meta key manually', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        <br>
                                        <?php echo esc_html__('OR', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        <br>
                                        <?php echo esc_html__('From another post', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                    </p>
                                    <?php if (!empty($meta_fields)) : ?>
                                        <?php foreach ($meta_fields as $meta_field) : ?>
                                            <?php include WPBEL_VIEWS_DIR . 'meta_field/meta_field_item.php'; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="droppable-helper"></div>
                                </div>
                                <div class="wpbel-meta-fields-buttons">
                                    <div class="wpbel-meta-fields-buttons-left">
                                        <button type="button" disabled="disabled" class="wpbel-button wpbel-button-lg wpbel-button-blue">
                                            <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'save.svg'); ?>" alt="">
                                            <span><?php esc_html_e('Save Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>