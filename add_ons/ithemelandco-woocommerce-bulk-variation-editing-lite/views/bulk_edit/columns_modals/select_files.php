<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-select-files">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-lg">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Select Files', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-select-files-item-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <button type="button" id="iwbvel-modal-select-files-add-file-item" class="iwbvel-button iwbvel-button-green iwbvel-mb10"><?php esc_html_e('Add File', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                        <div class="iwbvel-inline-select-files"></div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" id="iwbvel-modal-select-files-apply" data-item-id="" data-field="" data-content-type="select_files" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>