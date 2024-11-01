<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-history">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('History', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-float-side-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-alert wpbel-alert-default">
                            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                        </div>
                        <div class="wpbel-alert wpbel-alert-danger">
                            <span class="wpbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                            <a href="<?php echo esc_url(WPBEL_UPGRADE_URL); ?>"><?php echo esc_html(WPBEL_UPGRADE_TEXT); ?></a>
                        </div>
                        <div class="wpbel-history-filter">
                            <div class="wpbel-history-filter-fields">
                                <div class="wpbel-history-filter-field-item">
                                    <label for="wpbel-history-filter-operation"><?php esc_html_e('Operation', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbel-history-filter-operation">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <?php if (!empty($history_types = \wpbel\classes\repositories\History::get_operation_types())) : ?>
                                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wpbel-history-filter-field-item">
                                    <label for="wpbel-history-filter-author"><?php esc_html_e('Author', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbel-history-filter-author">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <?php if (!empty($users)) : ?>
                                            <?php foreach ($users as $user_item) : ?>
                                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wpbel-history-filter-field-item">
                                    <label for="wpbel-history-filter-fields"><?php esc_html_e('Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" id="wpbel-history-filter-fields" placeholder="for example: ID">
                                </div>
                                <div class="wpbel-history-filter-field-item wpbel-history-filter-field-date">
                                    <label><?php esc_html_e('Date', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" id="wpbel-history-filter-date-from" class="wpbel-datepicker wpbel-date-from" data-to-id="wpbel-history-filter-date-to" placeholder="<?php esc_html_e('From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                    <input type="text" id="wpbel-history-filter-date-to" class="wpbel-datepicker" placeholder="<?php esc_html_e('To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                </div>
                            </div>
                            <div class="wpbel-history-filter-buttons">
                                <div class="wpbel-history-filter-buttons-left">
                                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-blue" disabled="disabled">
                                        <i class="wpbel-icon-filter1"></i>
                                        <span><?php esc_html_e('Apply Filters', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                    </button>
                                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-gray" disabled="disabled">
                                        <i class="wpbel-icon-rotate-cw"></i>
                                        <span><?php esc_html_e('Reset Filters', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                    </button>
                                </div>
                                <div class="wpbel-history-filter-buttons-right">
                                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                        <?php wp_nonce_field('wpbel_post_nonce'); ?>
                                        <button type="button" disabled="disabled" class="wpbel-button wpbel-button-lg wpbel-button-red">
                                            <i class="wpbel-icon-trash-2"></i>
                                            <span><?php esc_html_e('Clear History', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="wpbel-history-items">
                            <h3><?php esc_html_e('Column(s)', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h3>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                <?php wp_nonce_field('wpbel_post_nonce'); ?>
                                <div class="wpbel-table-border-radius">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php esc_html_e('History Name', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                                <th><?php esc_html_e('Author', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                                <th class="wpbel-mw125"><?php esc_html_e('Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                                <th class="wpbel-mw250"><?php esc_html_e('Actions', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php include 'history_items.php'; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="wpbel-history-pagination-container">
                                    <?php include 'history_pagination.php'; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>