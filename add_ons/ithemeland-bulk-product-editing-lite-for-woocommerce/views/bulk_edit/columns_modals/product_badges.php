<div class="wcbef-modal" id="wcbe-modal-product-badges">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-sm">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Product badges', 'ithemeland-woocommerce-bulk-product-editing'); ?> - <span id="wcbe-modal-product-badges-item-title" class="wcbef-modal-item-title"></span></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <div class="wcbef-form-group">
                            <label for="wcbe-modal-product-badge-items"><?php esc_html_e('Product badges'); ?></label>
                            <select class="wcbef-select2" id="wcbe-modal-product-badge-items" multiple data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-woocommerce-bulk-product-editing'); ?>">
                                <?php
                                $badges = get_posts(['post_type' => 'yith-wcbm-badge', 'posts_per_page' => -1, 'order' => 'ASC']);
                                if (!empty($badges)) {
                                    foreach ($badges as $badge) {
                                        if ($badge instanceof \WP_Post) {
                                            echo "<option value='{$badge->ID}'>{$badge->post_title}</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" id="wcbe-modal-product-badges-apply" data-item-id="" data-field="" data-content-type="select_files" class="wcbef-button wcbef-button-blue" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-woocommerce-bulk-product-editing'); ?>
                    </button>
                    <button type="button" class="wcbef-button wcbef-button-gray wcbef-float-right" data-toggle="modal-close">
                        <?php esc_html_e('Close', 'ithemeland-woocommerce-bulk-product-editing'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>