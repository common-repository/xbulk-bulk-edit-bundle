<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-modal" id="wpbel-modal-image">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Image Edit', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> - <span id="wpbel-modal-image-item-title" class="wpbel-modal-item-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-inline-image-edit">
                            <button type="button" class="wpbel-inline-uploader wpbel-open-uploader" data-target="inline-edit" data-type="single" data-id="" data-item-id="">
                                <i class="wpbel-icon-pencil"></i>
                            </button>
                            <div class="wpbel-inline-image-preview" data-image-preview-id=""></div>
                            <input type="hidden" id="" class="wpbel-image-preview-hidden-input">
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-button-type="save" data-content-type="image" class="wpbel-button wpbel-button-blue wpbel-edit-action-with-button" data-toggle="modal-close" data-image-url="" data-image-id="">
                        <?php esc_html_e('Apply Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                    <button type="button" class="wpbel-button wpbel-button-red wpbel-edit-action-with-button" data-button-type="remove" data-item-id="" data-image-url="<?php echo esc_url(WPBEL_IMAGES_URL . "no-image.png"); ?>" data-field="" data-image-id="0" data-toggle="modal-close">
                        <?php esc_html_e('Remove Image', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>