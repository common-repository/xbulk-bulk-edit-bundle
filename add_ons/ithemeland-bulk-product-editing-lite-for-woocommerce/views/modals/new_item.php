<?php

use wcbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-new-item">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2 id="wcbel-new-item-title"></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-form-group">
                            <label class="wcbel-label-big" for="wcbel-new-item-count" id="wcbel-new-item-description"></label>
                            <input type="number" class="wcbel-input-numeric-sm wcbel-m0" id="wcbel-new-item-count" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                        </div>
                        <div id="wcbel-new-item-extra-fields">
                            <?php if (!empty($new_item_extra_fields)) : ?>
                                <?php foreach ($new_item_extra_fields as $extra_field) : ?>
                                    <div class="wcbel-form-group">
                                        <?php echo wp_kses($extra_field['label'], Sanitizer::allowed_html_tags()); ?>
                                        <?php echo wp_kses($extra_field['field'], Sanitizer::allowed_html_tags()); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" class="wcbel-button wcbel-button-blue" id="wcbel-create-new-item"><?php esc_html_e('Create', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>