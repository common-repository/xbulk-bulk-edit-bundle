<?php if (!empty($post)) : ?>
    <div class="wpbel-modal" id="wpbel-modal-_thumbnail_id-<?php echo esc_attr($post['id']); ?>">
        <div class="wpbel-modal-container">
            <div class="wpbel-modal-box wpbel-modal-box-sm">
                <div class="wpbel-modal-content">
                    <div class="wpbel-modal-title">
                        <h2><?php esc_html_e('Image Edit', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> - <span class="wpbel-modal-item-title"><?php echo esc_attr($post['post_title']); ?></span></h2>
                        <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                            <i class="lni lni-close"></i>
                        </button>
                    </div>
                    <div class="wpbel-modal-body">
                        <div class="wpbel-wrap">
                            <div class="wpbel-inline-image-edit">
                                <button type="button" class="wpbel-inline-uploader wpbel-open-uploader" data-target="inline-edit" data-type="single" data-id="wpbel-image-<?php echo esc_attr($post['id']); ?>" data-item-id="<?php echo esc_attr($post['id']); ?>">
                                    <i class="lni lni-pencil"></i>
                                </button>
                                <div class="wpbel-inline-image-preview" data-image-preview-id="wpbel-image-<?php echo esc_attr($post['id']); ?>">
                                    <?php echo !empty($post['_thumbnail_id']['big']) ? sprintf('%s', $post['_thumbnail_id']['big']) : '<img src="' . esc_url(WPBEL_IMAGES_URL . "no-image.png") . '">'; ?>
                                </div>
                                <input type="hidden" id="wpbel-image-<?php echo esc_attr($post['id']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="wpbel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($post['id']); ?>" data-field="_thumbnail_id" data-button-type="save" data-content-type="image" class="wpbel-button wpbel-button-blue wpbel-edit-action-with-button" data-toggle="modal-close" data-image-url="<?php echo (function_exists('wp_get_attachment_image_src') && isset(wp_get_attachment_image_src($post['_thumbnail_id']['id'])[0])) ? wp_get_attachment_image_src(esc_html($post['_thumbnail_id']['id'])[0]) : '' ?>" data-image-id="<?php echo esc_html($post['_thumbnail_id']['id']); ?>">
                            <?php esc_html_e('Apply Changes', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </button>
                        <button type="button" class="wpbel-button wpbel-button-red wpbel-edit-action-with-button" data-button-type="remove" data-item-id="<?php echo esc_attr($post['id']); ?>" data-image-url="<?php echo esc_url(WPBEL_IMAGES_URL . "no-image.png"); ?>" data-field="_thumbnail_id" data-image-id="0" data-toggle="modal-close">
                            <?php esc_html_e('Remove Image', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>