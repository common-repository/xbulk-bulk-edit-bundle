<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-meta-fields">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Meta Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-alert iwbvel-alert-default">
                            <span><?php esc_html_e('You can add new products meta fields in two ways: 1- Individually 2- Get from other product.', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                        </div>
                        <div class="iwbvel-alert iwbvel-alert-danger">
                            <span class="iwbvel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                            <a href="<?php echo esc_url(IWBVEL_UPGRADE_URL); ?>"><?php echo esc_html(IWBVEL_UPGRADE_TEXT); ?></a>
                        </div>
                        <div class="iwbvel-meta-fields-left">
                            <div class="iwbvel-meta-fields-manual">
                                <label for="iwbvel-meta-fields-manual_key_name"><?php esc_html_e('Manually', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <div class="iwbvel-meta-fields-manual-field">
                                    <input type="text" disabled="disabled" placeholder="<?php esc_html_e('Enter Meta Key ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                    <button type="button" class="iwbvel-button iwbvel-button-square iwbvel-button-blue" disabled="disabled">
                                        <i class="iwbvel-icon-plus1 iwbvel-m0"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="iwbvel-meta-fields-automatic">
                                <label for="iwbvel-add-meta-fields-product-id"><?php esc_html_e('Automatically From product', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <div class="iwbvel-meta-fields-automatic-field">
                                    <input type="text" disabled="disabled" placeholder="<?php esc_html_e('Enter Product ID ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                    <button type="button" class="iwbvel-button iwbvel-button-square iwbvel-button-blue" disabled="disabled">
                                        <i class="iwbvel-icon-plus1 iwbvel-m0"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="iwbvel-meta-fields-right" id="iwbvel-meta-fields-items">
                            <p class="iwbvel-meta-fields-empty-text" <?php echo (!empty($meta_fields)) ? 'style="display:none";' : ''; ?>><?php esc_html_e("Please add your meta key manually", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?><br> <?php esc_html_e("OR", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?><br><?php esc_html_e("From another product", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></p>

                            <div class="droppable-helper"></div>
                        </div>
                        <div class="iwbvel-meta-fields-buttons">
                            <div class="iwbvel-meta-fields-buttons-left">
                                <button type="button" disabled="disabled" class="iwbvel-button iwbvel-button-lg iwbvel-button-blue">
                                    <?php $img = esc_url(IWBVEL_IMAGES_URL . 'save.svg'); ?>
                                    <img src="<?php echo esc_url($img); ?>" alt="">
                                    <span><?php esc_html_e('Save Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>