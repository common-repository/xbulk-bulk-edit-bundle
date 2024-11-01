<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal iwbvel-modal-in-float-side" id="iwbvel-variations-new-attribute-term-modal">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('New term', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-form-group">
                            <label for="iwbvel-variations-new-attribute-term-name" class="iwbvel-label-big"><?php esc_html_e('Term name ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                            <input type="text" style="width: 100%;" id="iwbvel-variations-new-attribute-term-name" placeholder="<?php esc_attr_e('Enter name ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue iwbvel-new-attribute-button" data-toggle="modal-close">
                        <?php esc_html_e('Add', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                    <button type="button" class="iwbvel-button iwbvel-button-gray" data-toggle="modal-close">
                        <?php esc_html_e('Cancel', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>