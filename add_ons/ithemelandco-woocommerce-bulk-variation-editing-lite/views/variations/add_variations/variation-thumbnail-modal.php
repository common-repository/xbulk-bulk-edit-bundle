<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal iwbvel-modal-in-float-side" id="iwbvel-variation-thumbnail-modal">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Variation thumbnail', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-inline-image-edit">
                            <button type="button" class="iwbvel-inline-uploader iwbvel-open-uploader" data-target="variations-inline-edit" data-type="single" data-id="" data-item-id="">
                                <i class="iwbvel-icon-pencil"></i>
                            </button>
                            <div class="iwbvel-inline-image-preview"></div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" data-name="image_id" data-update-type="woocommerce_field" data-item-id="" data-button-type="save" class="iwbvel-button iwbvel-button-blue iwbvel-variations-table-thumbnail-inline-edit-button" data-toggle="modal-close" data-image-url="" data-image-id="">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                    <button type="button" data-name="image_id" data-update-type="woocommerce_field" class="iwbvel-button iwbvel-button-red iwbvel-variations-table-thumbnail-inline-edit-button" data-button-type="remove" data-item-id="" data-image-url="<?php echo esc_url(IWBVEL_IMAGES_URL . "no-image.png"); ?>" data-field="image_id" data-image-id="0" data-toggle="modal-close">
                        <?php esc_html_e('Remove Image', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>