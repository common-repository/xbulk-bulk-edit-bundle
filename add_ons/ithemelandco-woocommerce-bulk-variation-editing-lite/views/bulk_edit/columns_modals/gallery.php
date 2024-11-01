<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-gallery">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Gallery Edit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-gallery-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-inline-gallery-edit">
                            <div class="iwbvel-inline-image-preview">
                                <div class="iwbvel-inline-edit-gallery-item">
                                    <button type="button" class="iwbvel-open-uploader iwbvel-inline-edit-gallery-add-image" data-item-id="" data-target="inline-edit-gallery" data-type="multiple">
                                        <i class="iwbvel-icon-plus1"></i>
                                    </button>
                                </div>
                                <div id="iwbvel-modal-gallery-items"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" id="iwbvel-modal-gallery-apply" data-item-id="" data-content-type="gallery" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>