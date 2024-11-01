<?php if (!empty($product)) : ?>
    <div class="wcbef-modal" id="wcbef-modal-_thumbnail_id-<?php echo esc_attr($product['id']); ?>">
        <div class="wcbef-modal-container">
            <div class="wcbef-modal-box wcbef-modal-box-sm">
                <div class="wcbef-modal-content">
                    <div class="wcbef-modal-title">
                        <h2><?php esc_html_e('Image Edit', 'woocommerce-bulk-edit-free'); ?> - <span class="wcbef-modal-product-title"><?php echo esc_attr($product['post_title']); ?></span></h2>
                        <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                            <i class="lni lni-close"></i>
                        </button>
                    </div>
                    <div class="wcbef-modal-body">
                        <div class="wcbef-wrap">
                            <div class="wcbef-inline-image-edit">
                                <button type="button" class="wcbef-inline-uploader wcbef-open-uploader" data-target="inline-edit" data-type="single" data-id="wcbef-image-<?php echo esc_attr($product['id']); ?>" data-product-id="<?php echo esc_attr($product['id']); ?>">
                                    <i class="lni lni-pencil"></i>
                                </button>
                                <div class="wcbef-inline-image-preview" data-image-preview-id="wcbef-image-<?php echo esc_attr($product['id']); ?>">
                                    <?php echo sprintf('%s', $product['_thumbnail_id']['big']); ?>
                                </div>
                                <input type="hidden" id="wcbef-image-<?php echo esc_attr($product['id']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="wcbef-modal-footer">
                        <button type="button" data-product-id="<?php echo esc_attr($product['id']); ?>" data-field="_thumbnail_id" data-button-type="save" data-content-type="image" class="wcbef-button wcbef-button-blue wcbef-edit-action-with-button" data-toggle="modal-close" data-image-url="<?php echo (function_exists('wp_get_attachment_image_src') && isset(wp_get_attachment_image_src($product['_thumbnail_id']['id'])[0])) ? wp_get_attachment_image_src(esc_html($product['_thumbnail_id']['id'])[0]) : '' ?>" data-image-id="<?php echo esc_html($product['_thumbnail_id']['id']); ?>">
                            <?php esc_html_e('Apply Changes', 'woocommerce-bulk-edit-free'); ?>
                        </button>
                        <button type="button" class="wcbef-button wcbef-button-red wcbef-edit-action-with-button" data-button-type="remove" data-product-id="<?php echo esc_attr($product['id']); ?>" data-image-url="<?php echo esc_url(plugin_dir_url("woocommerce/assets/images/placeholder.png")) ?>" data-field="_thumbnail_id" data-image-id="" data-toggle="modal-close">
                            <?php esc_html_e('Remove Image', 'woocommerce-bulk-edit-free'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>