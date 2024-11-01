<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-modal" id="wpbel-modal-numeric-calculator">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Calculator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> - <span id="wpbel-modal-numeric-calculator-item-title" class="wpbel-modal-product-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <select id="wpbel-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" id="wpbel-numeric-calculator-value" title="<?php esc_html_e('Value', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-field-type="" data-toggle="modal-close" class="wpbel-button wpbel-button-blue wpbel-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>