<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-modal" id="wpbel-modal-new-post-taxonomy">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('New Post Taxonomy', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> - <span id="wpbel-modal-new-post-taxonomy-post-title" class="wpbel-modal-item-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-form-group">
                            <div class="wpbel-new-post-taxonomy-form-group">
                                <label for="wpbel-new-post-taxonomy-name"><?php esc_html_e('Name', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                <input type="text" id="wpbel-new-post-taxonomy-name" placeholder="<?php esc_html_e('Taxonomy Name ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                            </div>
                            <div class="wpbel-new-post-taxonomy-form-group">
                                <label for="wpbel-new-post-taxonomy-slug"><?php esc_html_e('Slug', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                <input type="text" id="wpbel-new-post-taxonomy-slug" placeholder="<?php esc_html_e('Taxonomy Slug ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                            </div>
                            <div class="wpbel-new-post-taxonomy-form-group">
                                <label for="wpbel-new-post-taxonomy-parent"><?php esc_html_e('Parent', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                <select id="wpbel-new-post-taxonomy-parent">
                                    <option value="-1"><?php esc_html_e('None', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                </select>
                            </div>
                            <div class="wpbel-new-post-taxonomy-form-group">
                                <label for="wpbel-new-post-taxonomy-description"><?php esc_html_e('Description', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                <textarea id="wpbel-new-post-taxonomy-description" rows="8" placeholder="<?php esc_html_e('Description ...', 'ithemeland-wordpress-bulk-posts-editing-lite') ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" class="wpbel-button wpbel-button-blue" id="wpbel-create-new-post-taxonomy">
                        <?php esc_html_e('Create', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>