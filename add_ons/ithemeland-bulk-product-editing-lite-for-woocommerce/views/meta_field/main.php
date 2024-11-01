<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-meta-fields">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Meta Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-alert wcbel-alert-default">
                            <span><?php esc_html_e('You can add new products meta fields in two ways: 1- Individually 2- Get from other product.', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                        </div>
                        <div class="wcbel-alert wcbel-alert-danger">
                            <span class="wcbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                            <a href="<?php echo esc_url(WCBEL_UPGRADE_URL); ?>"><?php echo esc_html(WCBEL_UPGRADE_TEXT); ?></a>
                        </div>
                        <div class="wcbel-meta-fields-left">
                            <div class="wcbel-meta-fields-manual">
                                <label for="wcbel-meta-fields-manual_key_name"><?php esc_html_e('Manually', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <div class="wcbel-meta-fields-manual-field">
                                    <input type="text" disabled="disabled" placeholder="<?php esc_html_e('Enter Meta Key ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                    <button type="button" class="wcbel-button wcbel-button-square wcbel-button-blue" disabled="disabled">
                                        <i class="wcbel-icon-plus1 wcbel-m0"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="wcbel-meta-fields-automatic">
                                <label for="wcbel-add-meta-fields-product-id"><?php esc_html_e('Automatically From product', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <div class="wcbel-meta-fields-automatic-field">
                                    <input type="text" disabled="disabled" placeholder="<?php esc_html_e('Enter Product ID ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                    <button type="button" class="wcbel-button wcbel-button-square wcbel-button-blue" disabled="disabled">
                                        <i class="wcbel-icon-plus1 wcbel-m0"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                            <?php wp_nonce_field('wcbel_post_nonce'); ?>
                            <div class="wcbel-meta-fields-right" id="wcbel-meta-fields-items">
                                <p class="wcbel-meta-fields-empty-text" <?php echo (!empty($meta_fields)) ? 'style="display:none";' : ''; ?>><?php esc_html_e("Please add your meta key manually", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?><br> <?php esc_html_e("OR", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?><br><?php esc_html_e("From another product", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></p>

                                <div class="droppable-helper"></div>
                            </div>
                            <div class="wcbel-meta-fields-buttons">
                                <div class="wcbel-meta-fields-buttons-left">
                                    <button type="button" disabled="disabled" class="wcbel-button wcbel-button-lg wcbel-button-blue">
                                        <?php $img = WCBEL_IMAGES_URL . 'save.svg'; ?>
                                        <img src="<?php echo esc_url($img); ?>" alt="">
                                        <span><?php esc_html_e('Save Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
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