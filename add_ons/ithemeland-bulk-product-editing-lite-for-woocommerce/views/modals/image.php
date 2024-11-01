<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-modal" id="wcbel-modal-image">
    <div class="wcbel-modal-container">
        <div class="wcbel-modal-box wcbel-modal-box-sm">
            <div class="wcbel-modal-content">
                <div class="wcbel-modal-title">
                    <h2><?php esc_html_e('Image Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span id="wcbel-modal-image-item-title" class="wcbel-modal-item-title"></span></h2>
                    <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-inline-image-edit">
                            <button type="button" class="wcbel-inline-uploader wcbel-open-uploader" data-target="inline-edit" data-type="single" data-id="" data-item-id="">
                                <i class="wcbel-icon-pencil"></i>
                            </button>
                            <div class="wcbel-inline-image-preview" data-image-preview-id=""></div>
                            <input type="hidden" id="" class="wcbel-image-preview-hidden-input">
                        </div>
                    </div>
                </div>
                <div class="wcbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-button-type="save" data-content-type="image" class="wcbel-button wcbel-button-blue wcbel-edit-action-with-button" data-toggle="modal-close" data-image-url="" data-image-id="">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                    <button type="button" class="wcbel-button wcbel-button-red wcbel-edit-action-with-button" data-button-type="remove" data-item-id="" data-image-url="<?php echo esc_url(WCBEL_IMAGES_URL . "no-image.png"); ?>" data-field="" data-image-id="0" data-toggle="modal-close">
                        <?php esc_html_e('Remove Image', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>