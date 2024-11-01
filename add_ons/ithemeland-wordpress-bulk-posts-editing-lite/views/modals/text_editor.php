<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-modal" id="wpbel-modal-text-editor">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-lg">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Content Edit', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> - <span id="wpbel-modal-text-editor-item-title" class="wpbel-modal-item-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <?php wp_editor("", 'wpbel-text-editor'); ?>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" data-field="" data-item-id="" data-content-type="textarea" id="wpbel-text-editor-apply" class="wpbel-button wpbel-button-blue wpbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>