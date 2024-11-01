<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-modal" id="wpbel-modal-select-post">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Select Post', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> - <span id="wpbel-modal-select-post-item-title" class="wpbel-modal-item-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-inline-select-post">
                            <select id="wpbel-select-post-value" class="wpbel-select2 wpbel-get-posts-ajax"></select>
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-content-type="select_post" class="wpbel-button wpbel-button-blue wpbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>