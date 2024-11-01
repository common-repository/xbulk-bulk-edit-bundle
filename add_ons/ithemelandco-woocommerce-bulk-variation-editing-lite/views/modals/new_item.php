<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-new-item">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2 id="iwbvel-new-item-title"></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-form-group">
                            <label class="iwbvel-label-big" for="iwbvel-new-item-count" id="iwbvel-new-item-description"></label>
                            <input type="number" class="iwbvel-input-numeric-sm iwbvel-m0" id="iwbvel-new-item-count" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                        </div>
                        <div id="iwbvel-new-item-extra-fields">
                            <?php if (!empty($new_item_extra_fields)) : ?>
                                <?php foreach ($new_item_extra_fields as $extra_field) : ?>
                                    <div class="iwbvel-form-group">
                                        <?php
                                        echo wp_kses($extra_field['label'], iwbvel\classes\helpers\Sanitizer::allowed_html());
                                        echo wp_kses($extra_field['field'], iwbvel\classes\helpers\Sanitizer::allowed_html());
                                        ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue" id="iwbvel-create-new-item"><?php esc_html_e('Create', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>