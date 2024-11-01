<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-new-product-attribute">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('New Product Attribute', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-new-product-attribute-item-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-form-group">
                            <div class="wcbel-new-product-attribute-form-group">
                                <label for="wcbel-new-product-attribute-name"><?php esc_html_e('Name', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <input type="text" id="wcbel-new-product-attribute-name" placeholder="<?php esc_html_e('Attribute Name ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                            </div>
                            <div class="wcbel-new-product-attribute-form-group">
                                <label for="wcbel-new-product-attribute-slug"><?php esc_html_e('Slug', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <input type="text" id="wcbel-new-product-attribute-slug" placeholder="<?php esc_html_e('Attribute Slug ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                            </div>
                            <div class="wcbel-new-product-attribute-form-group">
                                <label for="wcbel-new-product-attribute-description"><?php esc_html_e('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <textarea id="wcbel-new-product-attribute-description" rows="8" placeholder="<?php esc_html_e('Description ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" class="wcbel-button wcbel-button-blue" id="wcbel-create-new-product-attribute" data-field="">
                        <?php esc_html_e('Create', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>