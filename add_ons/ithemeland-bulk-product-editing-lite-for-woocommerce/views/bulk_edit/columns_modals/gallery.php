<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-gallery">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Gallery Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-gallery-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-inline-gallery-edit">
                            <div class="wcbel-inline-image-preview">
                                <div class="wcbel-inline-edit-gallery-item">
                                    <button type="button" class="wcbel-open-uploader wcbel-inline-edit-gallery-add-image" data-item-id="" data-target="inline-edit-gallery" data-type="multiple">
                                        <i class="wcbel-icon-plus1"></i>
                                    </button>
                                </div>
                                <div id="wcbel-modal-gallery-items"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" id="wcbel-modal-gallery-apply" data-item-id="" data-content-type="gallery" class="wcbel-button wcbel-button-blue wcbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>