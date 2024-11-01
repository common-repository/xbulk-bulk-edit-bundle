<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-import-export">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('Import/Export', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wpbel-wrap">
                        <div class="wpbel-tab-middle-content">
                            <div class="wpbel-alert wpbel-alert-default">
                                <span><?php esc_html_e('Import/Export posts as (CSV/XML) files', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>.</span>
                            </div>
                            <div class="wpbel-export">
                                <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                                    <?php wp_nonce_field('wpbel_post_nonce'); ?>
                                    <input type="hidden" name="action" value="wpbel_export_posts">
                                    <div id="wpbel-export-items-selected"></div>
                                    <div class="wpbel-export-fields">
                                        <div class="wpbel-export-field-item">
                                            <strong class="label"><?php esc_html_e('Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></strong>
                                            <label class="wpbel-export-radio">
                                                <input type="radio" name="posts" value="all" checked="checked" id="wpbel-export-all-items-in-table">
                                                <?php esc_html_e('All Posts In Table', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                            </label>
                                            <label class="wpbel-export-radio">
                                                <input type="radio" name="posts" id="wpbel-export-only-selected-items" value="selected" disabled="disabled">
                                                <?php esc_html_e('Only Selected posts', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                            </label>
                                        </div>
                                        <div class="wpbel-export-field-item">
                                            <strong class="label"><?php esc_html_e('Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></strong>
                                            <label class="wpbel-export-radio">
                                                <input type="radio" name="fields" value="all" checked="checked">
                                                <?php esc_html_e('All Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                            </label>
                                            <label class="wpbel-export-radio">
                                                <input type="radio" name="fields" value="visible">
                                                <?php esc_html_e('Only Visible Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                            </label>
                                        </div>
                                        <div class="wpbel-export-field-item">
                                            <label class="label" for="wpbel-export-delimiter"><?php esc_html_e('Delimiter', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                            <select name="export_delimiter" id="wpbel-export-delimiter">
                                                <option value=",">,</option>
                                            </select>
                                        </div>
                                        <div class="wpbel-export-field-item">
                                            <label class="label" for="wpbel-export-type"><?php esc_html_e('Export Type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                            <select name="export_type" id="wpbel-export-type">
                                                <option value="csv">CSV</option>
                                                <option value="xml">XML</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="wpbel-export-buttons">
                                        <div class="wpbel-export-buttons-left">
                                            <button type="submit" class="wpbel-button wpbel-button-lg wpbel-button-blue" id="wpbel-export-posts">
                                                <i class="wpbel-icon-filter1"></i>
                                                <span><?php esc_html_e('Export Now', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="wpbel-import">
                                <div class="wpbel-import-content">
                                    <p><?php esc_html_e("If you have posts in another system, you can import those into this site. ", 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></p>
                                </div>
                                <div class="wpbel-import-buttons">
                                    <div class="wpbel-import-buttons-left">
                                        <a href="<?php echo esc_url(admin_url("import.php")); ?>" target="_blank" class="wpbel-button wpbel-button-lg wpbel-button-blue">
                                            <i class="wpbel-icon-filter1"></i>
                                            <span><?php esc_html_e('Import Now', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
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
</div>