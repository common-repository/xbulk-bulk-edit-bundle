<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-new-product-attribute">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('New Product Attribute', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-new-product-attribute-item-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-form-group">
                            <div class="iwbvel-new-product-attribute-form-group">
                                <label for="iwbvel-new-product-attribute-name"><?php esc_html_e('Name', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <input type="text" id="iwbvel-new-product-attribute-name" placeholder="<?php esc_html_e('Attribute Name ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                            </div>
                            <div class="iwbvel-new-product-attribute-form-group">
                                <label for="iwbvel-new-product-attribute-slug"><?php esc_html_e('Slug', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <input type="text" id="iwbvel-new-product-attribute-slug" placeholder="<?php esc_html_e('Attribute Slug ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                            </div>
                            <div class="iwbvel-new-product-attribute-form-group">
                                <label for="iwbvel-new-product-attribute-description"><?php esc_html_e('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <textarea id="iwbvel-new-product-attribute-description" rows="8" placeholder="<?php esc_html_e('Description ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue" id="iwbvel-create-new-product-attribute" data-field="">
                        <?php esc_html_e('Create', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>