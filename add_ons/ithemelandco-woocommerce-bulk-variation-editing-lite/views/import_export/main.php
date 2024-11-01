<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-import-export">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Import/Export', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-alert iwbvel-alert-default">
                            <span><?php esc_html_e('Import/Export products as CSV files', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>.</span>
                        </div>
                        <div class="iwbvel-export">
                            <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                                <input type="hidden" name="action" value="iwbvel_export_products">
                                <?php wp_nonce_field('iwbvel_export_products'); ?>
                                <div id="iwbvel-export-items-selected"></div>
                                <div class="iwbvel-export-fields">
                                    <div class="iwbvel-export-field-item">
                                        <strong class="label"><?php esc_html_e('Products', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></strong>
                                        <label class="iwbvel-export-radio">
                                            <input type="radio" name="products" value="all" checked="checked" id="iwbvel-export-all-items-in-table">
                                            <?php esc_html_e('All Products In Table', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                        </label>
                                        <label class="iwbvel-export-radio">
                                            <input type="radio" name="products" id="iwbvel-export-only-selected-items" value="selected" disabled="disabled">
                                            <?php esc_html_e('Only Selected products', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                        </label>
                                    </div>
                                    <div class="iwbvel-export-field-item">
                                        <strong class="label"><?php esc_html_e('Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></strong>
                                        <label class="iwbvel-export-radio">
                                            <input type="radio" name="fields" value="all" checked="checked">
                                            <?php esc_html_e('All Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                        </label>
                                        <label class="iwbvel-export-radio">
                                            <input type="radio" name="fields" value="visible">
                                            <?php esc_html_e('Only Visible Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                        </label>
                                    </div>
                                    <div class="iwbvel-export-field-item">
                                        <label class="label" for="iwbvel-export-delimiter"><?php esc_html_e('Delimiter', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                        <select name="iwbvel_export_delimiter" id="iwbvel-export-delimiter">
                                            <option value=",">,</option>
                                            <option value=";">;</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="iwbvel-export-buttons">
                                    <div class="iwbvel-export-buttons-left">
                                        <button type="submit" class="iwbvel-button iwbvel-button-lg iwbvel-button-blue" id="iwbvel-export-products">
                                            <i class="iwbvel-icon-filter1"></i>
                                            <span><?php esc_html_e('Export Now', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="iwbvel-import">
                            <div class="iwbvel-import-content">
                                <p><?php esc_html_e("If you have products in another system, you can import those into this site. ", 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></p>
                            </div>
                            <div class="iwbvel-import-buttons">
                                <div class="iwbvel-import-buttons-left">
                                    <a href="<?php echo esc_url(admin_url("edit.php?post_type=product&page=product_importer")); ?>" target="_blank" class="iwbvel-button iwbvel-button-lg iwbvel-button-blue">
                                        <i class="iwbvel-icon-filter1"></i>
                                        <span><?php esc_html_e('Import Now', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
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