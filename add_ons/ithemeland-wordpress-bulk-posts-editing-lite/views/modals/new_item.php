<?php

use wpbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-modal" id="wpbel-modal-new-item">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2 id="wpbel-new-item-title"></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-form-group">
                            <label class="wpbel-label-big" for="wpbel-new-item-count" id="wpbel-new-item-description"></label>
                            <input type="number" class="wpbel-input-numeric-sm wpbel-m0" id="wpbel-new-item-count" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                        </div>
                        <div id="wpbel-new-item-extra-fields">
                            <?php if (!empty($new_item_extra_fields)) : ?>
                                <?php foreach ($new_item_extra_fields as $extra_field) : ?>
                                    <div class="wpbel-form-group">
                                        <?php echo wp_kses($extra_field['label'], Sanitizer::allowed_html_tags()); ?>
                                        <?php echo wp_kses($extra_field['field'], Sanitizer::allowed_html_tags()); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" class="wpbel-button wpbel-button-blue" id="wpbel-create-new-item"><?php esc_html_e('Create', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>