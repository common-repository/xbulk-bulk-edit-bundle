<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-import-export">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Import/Export', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wcbel-wrap">
                        <div class="wcbel-alert wcbel-alert-default">
                            <span><?php esc_html_e('Import/Export products as CSV files', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>.</span>
                        </div>
                        <div class="wcbel-export">
                            <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                                <?php wp_nonce_field('wcbel_post_nonce'); ?>
                                <input type="hidden" name="action" value="wcbel_export_products">
                                <div id="wcbel-export-items-selected"></div>
                                <div class="wcbel-export-fields">
                                    <div class="wcbel-export-field-item">
                                        <strong class="label"><?php esc_html_e('Products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></strong>
                                        <label class="wcbel-export-radio">
                                            <input type="radio" name="products" value="all" checked="checked" id="wcbel-export-all-items-in-table">
                                            <?php esc_html_e('All Products In Table', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                        </label>
                                        <label class="wcbel-export-radio">
                                            <input type="radio" name="products" id="wcbel-export-only-selected-items" value="selected" disabled="disabled">
                                            <?php esc_html_e('Only Selected products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                        </label>
                                    </div>
                                    <div class="wcbel-export-field-item">
                                        <strong class="label"><?php esc_html_e('Fields', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></strong>
                                        <label class="wcbel-export-radio">
                                            <input type="radio" name="fields" value="all" checked="checked">
                                            <?php esc_html_e('All Fields', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                        </label>
                                        <label class="wcbel-export-radio">
                                            <input type="radio" name="fields" value="visible">
                                            <?php esc_html_e('Only Visible Fields', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                        </label>
                                    </div>
                                    <div class="wcbel-export-field-item">
                                        <label class="label" for="wcbel-export-delimiter"><?php esc_html_e('Delimiter', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                        <select name="wcbel_export_delimiter" id="wcbel-export-delimiter">
                                            <option value=",">,</option>
                                            <option value=";">;</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="wcbel-export-buttons">
                                    <div class="wcbel-export-buttons-left">
                                        <button type="submit" class="wcbel-button wcbel-button-lg wcbel-button-blue" id="wcbel-export-products">
                                            <i class="wcbel-icon-filter1"></i>
                                            <span><?php esc_html_e('Export Now', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="wcbel-import">
                            <div class="wcbel-import-content">
                                <p><?php esc_html_e("If you have products in another system, you can import those into this site. ", 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></p>
                            </div>
                            <div class="wcbel-import-buttons">
                                <div class="wcbel-import-buttons-left">
                                    <a href="<?php echo esc_url(admin_url("edit.php?post_type=product&page=product_importer")); ?>" target="_blank" class="wcbel-button wcbel-button-lg wcbel-button-blue">
                                        <i class="wcbel-icon-filter1"></i>
                                        <span><?php esc_html_e('Import Now', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>