<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-new-product-taxonomy">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('New Product Taxonomy', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-new-product-taxonomy-product-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-form-group">
                            <div class="wcbel-new-product-taxonomy-form-group">
                                <label for="wcbel-new-product-taxonomy-name"><?php esc_html_e('Name', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <input type="text" id="wcbel-new-product-taxonomy-name" placeholder="<?php esc_html_e('Taxonomy Name ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                            </div>
                            <div class="wcbel-new-product-taxonomy-form-group">
                                <label for="wcbel-new-product-taxonomy-slug"><?php esc_html_e('Slug', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <input type="text" id="wcbel-new-product-taxonomy-slug" placeholder="<?php esc_html_e('Taxonomy Slug ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                            </div>
                            <div class="wcbel-new-product-taxonomy-form-group">
                                <label for="wcbel-new-product-taxonomy-parent"><?php esc_html_e('Parent', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <select id="wcbel-new-product-taxonomy-parent">
                                    <option value="-1"><?php esc_html_e('None', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
                                </select>
                            </div>
                            <div class="wcbel-new-product-taxonomy-form-group">
                                <label for="wcbel-new-product-taxonomy-description"><?php esc_html_e('Description', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                <textarea id="wcbel-new-product-taxonomy-description" rows="8" placeholder="<?php esc_html_e('Description ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce') ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" class="wcbel-button wcbel-button-blue" id="wcbel-create-new-product-taxonomy">
                        <?php esc_html_e('Create', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>