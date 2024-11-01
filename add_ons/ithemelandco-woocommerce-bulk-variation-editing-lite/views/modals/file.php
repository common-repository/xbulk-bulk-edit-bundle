<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-file">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-lg">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Select File', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-select-file-item-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-inline-select-files">
                            <div class="iwbvel-modal-select-files-file-item">
                                <input type="text" class="iwbvel-inline-edit-file-url iwbvel-w60p" id="iwbvel-file-url" placeholder="File Url ..." value="">
                                <button type="button" class="iwbvel-button iwbvel-button-white iwbvel-open-uploader iwbvel-inline-edit-choose-file" data-type="single" data-target="inline-file-custom-field"><?php esc_html_e('Choose File', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                                <input type="hidden" id="iwbvel-file-id" value="">
                                <button type="button" class="iwbvel-button iwbvel-button-white" id="iwbvel-modal-file-clear"><?php esc_html_e('Clear', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" id="iwbvel-modal-file-apply" data-item-id="" data-field="" data-content-type="file" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>