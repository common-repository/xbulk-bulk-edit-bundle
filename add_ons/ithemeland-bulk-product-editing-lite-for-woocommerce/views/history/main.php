<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-history">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('History', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-alert wcbel-alert-default">
                            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                        </div>
                        <div class="wcbel-alert wcbel-alert-danger">
                            <span class="wcbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                            <a href="<?php echo esc_url(WCBEL_UPGRADE_URL); ?>"><?php echo esc_html(WCBEL_UPGRADE_TEXT); ?></a>
                        </div>
                        <div class="wcbel-history-filter">
                            <div class="wcbel-history-filter-fields">
                                <div class="wcbel-history-filter-field-item">
                                    <label for="wcbel-history-filter-operation"><?php esc_html_e('Operation', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                    <select id="wcbel-history-filter-operation">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
                                        <?php if (!empty($history_types = \wcbel\classes\repositories\History::get_operation_types())) : ?>
                                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wcbel-history-filter-field-item">
                                    <label for="wcbel-history-filter-author"><?php esc_html_e('Author', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                    <select id="wcbel-history-filter-author">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
                                        <?php if (!empty($users)) : ?>
                                            <?php foreach ($users as $user_item) : ?>
                                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wcbel-history-filter-field-item">
                                    <label for="wcbel-history-filter-fields"><?php esc_html_e('Fields', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                    <input type="text" id="wcbel-history-filter-fields" placeholder="for example: ID">
                                </div>
                                <div class="wcbel-history-filter-field-item wcbel-history-filter-field-date">
                                    <label><?php esc_html_e('Date', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                    <input type="text" id="wcbel-history-filter-date-from" class="wcbel-datepicker wcbel-date-from" data-to-id="wcbel-history-filter-date-to" placeholder="<?php esc_html_e('From ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                                    <input type="text" id="wcbel-history-filter-date-to" class="wcbel-datepicker" placeholder="<?php esc_html_e('To ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
                                </div>
                            </div>
                            <div class="wcbel-history-filter-buttons">
                                <div class="wcbel-history-filter-buttons-left">
                                    <button type="button" disabled="disabled" class="wcbel-button wcbel-button-lg wcbel-button-blue">
                                        <i class="wcbel-icon-filter1"></i>
                                        <span><?php esc_html_e('Apply Filters', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                    </button>
                                    <button type="button" disabled="disabled" class="wcbel-button wcbel-button-lg wcbel-button-gray">
                                        <i class="wcbel-icon-rotate-cw"></i>
                                        <span><?php esc_html_e('Reset Filters', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                    </button>
                                </div>
                                <div class="wcbel-history-filter-buttons-right">
                                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                        <?php wp_nonce_field('wcbel_post_nonce'); ?>
                                        <input type="hidden" name="action" value="<?php echo esc_attr($plugin_key . '_clear_all_history'); ?>">
                                        <button disabled="disabled" type="button" class="wcbel-button wcbel-button-lg wcbel-button-red">
                                            <i class="wcbel-icon-trash-2"></i>
                                            <span><?php esc_html_e('Clear History', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="wcbel-history-items">
                            <h3><?php esc_html_e('Column(s)', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h3>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wcbel-history-items">
                                <?php wp_nonce_field('wcbel_post_nonce'); ?>
                                <input type="hidden" name="action" value="<?php echo esc_attr($plugin_key . '_history_action'); ?>">
                                <input type="hidden" name="" value="" id="wcbel-history-clicked-id">
                                <div class="wcbel-table-border-radius">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php esc_html_e('History Name', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                                <th><?php esc_html_e('Author', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                                <th class="wcbel-mw125"><?php esc_html_e('Date Modified', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                                <th class="wcbel-mw250"><?php esc_html_e('Actions', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php include 'history_items.php'; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="wcbel-history-pagination-container">
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