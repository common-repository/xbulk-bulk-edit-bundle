<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-history">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('History', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-alert iwbvel-alert-default">
                            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                        </div>
                        <div class="iwbvel-alert iwbvel-alert-danger">
                            <span class="iwbvel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                            <a href="<?php echo esc_url(IWBVEL_UPGRADE_URL); ?>"><?php echo esc_html(IWBVEL_UPGRADE_TEXT); ?></a>
                        </div>
                        <div class="iwbvel-history-filter">
                            <div class="iwbvel-history-filter-fields">
                                <div class="iwbvel-history-filter-field-item">
                                    <label for="iwbvel-history-filter-operation"><?php esc_html_e('Operation', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                    <select id="iwbvel-history-filter-operation">
                                        <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
                                        <?php if (!empty($history_types = \iwbvel\classes\repositories\History::get_operation_types())) : ?>
                                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="iwbvel-history-filter-field-item">
                                    <label for="iwbvel-history-filter-author"><?php esc_html_e('Author', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                    <select id="iwbvel-history-filter-author">
                                        <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
                                        <?php if (!empty($users)) : ?>
                                            <?php foreach ($users as $user_item) : ?>
                                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="iwbvel-history-filter-field-item">
                                    <label for="iwbvel-history-filter-fields"><?php esc_html_e('Fields', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                    <input type="text" id="iwbvel-history-filter-fields" placeholder="for example: ID">
                                </div>
                                <div class="iwbvel-history-filter-field-item iwbvel-history-filter-field-date">
                                    <label><?php esc_html_e('Date', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></label>
                                    <input type="text" id="iwbvel-history-filter-date-from" class="iwbvel-datepicker iwbvel-date-from" data-to-id="iwbvel-history-filter-date-to" placeholder="<?php esc_html_e('From ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                    <input type="text" id="iwbvel-history-filter-date-to" class="iwbvel-datepicker" placeholder="<?php esc_html_e('To ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                                </div>
                            </div>
                            <div class="iwbvel-history-filter-buttons">
                                <div class="iwbvel-history-filter-buttons-left">
                                    <button type="button" class="iwbvel-button iwbvel-button-lg iwbvel-button-blue" disabled="disabled">
                                        <i class="iwbvel-icon-filter1"></i>
                                        <span><?php esc_html_e('Apply Filters', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                    </button>
                                    <button type="button" class="iwbvel-button iwbvel-button-lg iwbvel-button-gray" disabled="disabled">
                                        <i class="iwbvel-icon-rotate-cw"></i>
                                        <span><?php esc_html_e('Reset Filters', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                    </button>
                                </div>
                                <div class="iwbvel-history-filter-buttons-right">
                                    <button type="button" disabled="disabled" class="iwbvel-button iwbvel-button-lg iwbvel-button-red">
                                        <i class="iwbvel-icon-trash-2"></i>
                                        <span><?php esc_html_e('Clear History', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="iwbvel-history-items">
                            <h3><?php esc_html_e('Column(s)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h3>
                            <input type="hidden" name="" value="" id="iwbvel-history-clicked-id">
                            <div class="iwbvel-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php esc_html_e('History Name', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Author', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th class="iwbvel-mw125"><?php esc_html_e('Date Modified', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th class="iwbvel-mw250"><?php esc_html_e('Actions', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php include 'history_items.php'; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="iwbvel-history-pagination-container">
                                <?php include 'history_pagination.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>