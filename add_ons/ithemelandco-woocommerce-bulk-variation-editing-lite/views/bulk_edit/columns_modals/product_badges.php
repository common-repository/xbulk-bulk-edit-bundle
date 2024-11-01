<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal" id="iwbvel-modal-product-badges">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Product badges', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span id="iwbvel-modal-product-badges-item-title" class="iwbvel-modal-item-title"></span></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-form-group">
                            <label for="iwbvel-modal-product-badge-items"><?php esc_html_e('Product badges', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                            <select class="iwbvel-select2" id="iwbvel-modal-product-badge-items" multiple data-placeholder="<?php esc_html_e('Select ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                <?php
                                $badges = get_posts(['post_type' => 'yith-wcbm-badge', 'posts_per_page' => -1, 'order' => 'ASC']);
                                if (!empty($badges)) {
                                    foreach ($badges as $badge) {
                                        if ($badge instanceof \WP_Post) {
                                            echo '<option value="' . esc_attr($badge->ID) . '">' . esc_html($badge->post_title) . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" id="iwbvel-modal-product-badges-apply" data-item-id="" data-field="" data-content-type="select_files" class="iwbvel-button iwbvel-button-blue" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                    <button type="button" class="iwbvel-button iwbvel-button-gray iwbvel-float-right" data-toggle="modal-close">
                        <?php esc_html_e('Close', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>