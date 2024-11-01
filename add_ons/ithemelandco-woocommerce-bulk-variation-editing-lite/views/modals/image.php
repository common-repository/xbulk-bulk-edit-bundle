<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-image">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Image Edit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-image-item-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-inline-image-edit">
                            <button type="button" class="iwbvel-inline-uploader iwbvel-open-uploader" data-target="inline-edit" data-type="single" data-id="" data-item-id="">
                                <i class="iwbvel-icon-pencil"></i>
                            </button>
                            <div class="iwbvel-inline-image-preview" data-image-preview-id=""></div>
                            <input type="hidden" id="" class="iwbvel-image-preview-hidden-input">
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-button-type="save" data-content-type="image" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-with-button" data-toggle="modal-close" data-image-url="" data-image-id="">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                    <button type="button" class="iwbvel-button iwbvel-button-red iwbvel-edit-action-with-button" data-button-type="remove" data-item-id="" data-image-url="<?php echo esc_url(IWBVEL_IMAGES_URL . "no-image.png"); ?>" data-field="" data-image-id="0" data-toggle="modal-close">
                        <?php esc_html_e('Remove Image', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>