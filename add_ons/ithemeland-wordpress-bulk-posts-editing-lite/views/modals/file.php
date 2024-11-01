<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-modal" id="wpbel-modal-file">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-lg">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Select File', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> - <span id="wpbel-modal-select-file-item-title" class="wpbel-modal-item-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-inline-select-files">
                            <div class="wcbe-modal-select-files-file-item">
                                <input type="text" class="wpbel-inline-edit-file-url wpbel-w60p" id="wpbel-file-url" placeholder="File Url ..." value="">
                                <button type="button" class="wpbel-button wpbel-button-white wpbel-open-uploader wpbel-inline-edit-choose-file" data-type="single" data-target="inline-file-custom-field"><?php esc_html_e('Choose File', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button>
                                <input type="hidden" id="wpbel-file-id" value="">
                                <button type="button" class="wpbel-button wpbel-button-white" id="wpbel-modal-file-clear"><?php esc_html_e('Clear', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" id="wpbel-modal-file-apply" data-item-id="" data-field="" data-content-type="file" class="wpbel-button wpbel-button-blue wpbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>