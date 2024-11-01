<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-item-duplicate">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Duplicate', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-form-group">
                            <label class="iwbvel-label-big" for="iwbvel-bulk-edit-duplicate-number">
                                <?php esc_html_e('Enter how many item(s) to Duplicate!', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                            </label>
                            <input type="number" class="iwbvel-input-numeric-sm" id="iwbvel-bulk-edit-duplicate-number" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue" id="iwbvel-bulk-edit-duplicate-start">
                        <?php esc_html_e('Start Duplicate', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>