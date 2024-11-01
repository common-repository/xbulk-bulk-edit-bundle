<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-new-product-taxonomy">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('New Product Taxonomy', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-new-product-taxonomy-product-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-form-group">
                            <div class="iwbvel-new-product-taxonomy-form-group">
                                <label for="iwbvel-new-product-taxonomy-name"><?php esc_html_e('Name', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <input type="text" id="iwbvel-new-product-taxonomy-name" placeholder="<?php esc_html_e('Taxonomy Name ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                            </div>
                            <div class="iwbvel-new-product-taxonomy-form-group">
                                <label for="iwbvel-new-product-taxonomy-slug"><?php esc_html_e('Slug', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <input type="text" id="iwbvel-new-product-taxonomy-slug" placeholder="<?php esc_html_e('Taxonomy Slug ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                            </div>
                            <div class="iwbvel-new-product-taxonomy-form-group">
                                <label for="iwbvel-new-product-taxonomy-parent"><?php esc_html_e('Parent', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <select id="iwbvel-new-product-taxonomy-parent">
                                    <option value="-1"><?php esc_html_e('None', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
                                </select>
                            </div>
                            <div class="iwbvel-new-product-taxonomy-form-group">
                                <label for="iwbvel-new-product-taxonomy-description"><?php esc_html_e('Description', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                <textarea id="iwbvel-new-product-taxonomy-description" rows="8" placeholder="<?php esc_html_e('Description ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite') ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue" id="iwbvel-create-new-product-taxonomy">
                        <?php esc_html_e('Create', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>