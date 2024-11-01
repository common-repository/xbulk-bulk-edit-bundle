<div class="wccbef-top-nav">
    <div class="wccbef-top-nav-buttons" id="wccbef-bulk-edit-navigation">
        <div class="wccbef-top-nav-buttons-group">
            <button type="button" id="wccbef-bulk-edit-bulk-edit-btn" data-toggle="modal" data-target="#wccbef-modal-bulk-edit" class="wccbef-button-blue" data-fetch-coupon="<?php echo (isset($settings['fetch_data_in_bulk'])) ? esc_attr($settings['fetch_data_in_bulk']) : ''; ?>">
                <?php esc_html_e('Bulk Edit', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
            </button>
        </div>
        <div class="wccbef-top-nav-buttons-border"></div>
        <div class="wccbef-top-nav-buttons-group">
            <button type="button" data-toggle="modal" data-target="#wccbef-modal-column-profiles">
                <?php esc_html_e('Column Profile', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
            </button>
            <button type="button" data-toggle="modal" data-target="#wccbef-modal-filter-profiles">
                <?php esc_html_e('Filter Profiles', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
            </button>
            <?php $reset_filters_visibility = (!empty($filter_profile_use_always) && $filter_profile_use_always != 'default') ? "display:inline-table" : "display:none"; ?>
            <button type="button" id="wccbef-bulk-edit-reset-filter" class="wccbef-button-blue" <?php echo 'style="' . esc_attr($reset_filters_visibility) . '"'; ?>>
                <?php esc_html_e('Reset Filter', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
            </button>
        </div>
        <div class="wccbef-top-nav-buttons-border"></div>
        <div class="wccbef-top-nav-buttons-group">
            <button type="button" title="Undo latest history" id="" class="wccbef-button-blue" disabled="disabled">
                <?php esc_html_e('Undo', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
            </button>
            <button type="button" title="Redo" class="wccbef-button-blue" id="" disabled="disabled">
                <?php esc_html_e('Redo', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
            </button>
            <button type="button" data-toggle="modal" data-target="#wccbef-modal-new-item"><?php esc_html_e('New Coupon', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?></button>
        </div>
        <div class="wccbef-top-nav-buttons-border"></div>
        <div class="wccbef-bulk-edit-form-selection-tools">
            <div class="wccbef-top-nav-buttons-group">
                <button type="button" id="wccbef-bulk-edit-unselect"><?php esc_html_e('Unselect', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?></button>
                <button type="button" id="wccbef-bulk-edit-duplicate" data-toggle="modal" data-target="#wccbef-modal-item-duplicate"><?php esc_html_e('Duplicate', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                </button>
                <div class="wccbef-bulk-edit-delete-item">
                    <span>
                        <?php esc_html_e('Delete', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        <i class="lni lni-chevron-down"></i>
                    </span>
                    <div class="wccbef-bulk-edit-delete-item-buttons" style="display: none;">
                        <ul>
                            <li class="wccbef-bulk-edit-delete-action" data-delete-type="trash">
                                <?php esc_html_e('Move to trash', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                            </li>
                            <li class="wccbef-bulk-edit-delete-action" data-delete-type="permanently">
                                <?php esc_html_e('Permanently', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="wccbef-top-nav-buttons-border"></div>
        </div>
        <div class="wccbef-top-nav-buttons-group">
            <label>
                <input type="checkbox" id="wccbef-inline-edit-bind">
                <?php esc_html_e('Bind Edit', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                <i title="Set the value of edited coupon to all selected coupons" class="dashicons dashicons-info"></i>
            </label>
        </div>
    </div>
    <div class="wccbef-top-nav-filters">
        <div class="wccbef-top-nav-status-filter"></div>
        <div class="wccbef-top-nav-filters-left">
            <div class="wccbef-top-nav-filters-per-page">
                <select id="wccbef-quick-per-page" title="The number of coupons per page">
                    <?php foreach (wccbef\classes\helpers\Setting::get_count_per_page_items() as $count_per_page_item) : ?>
                        <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if (isset($current_settings['count_per_page']) && $current_settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                            <?php echo esc_html($count_per_page_item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (isset($settings['show_quick_search']) && $settings['show_quick_search'] == 'yes') : ?>
                <?php $quick_search_input = (isset($last_filter_data) && !empty($last_filter_data['search_type']) && $last_filter_data['search_type'] == 'quick_search') ? $last_filter_data : ''; ?>
                <div class="wccbef-top-nav-filters-search">
                    <input type="text" id="wccbef-quick-search-text" placeholder="<?php esc_html_e('Quick Search ...', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>" title="Quick Search" value="<?php echo (isset($quick_search_input['quick_search_text'])) ? esc_html($quick_search_input['quick_search_text']) : '' ?>">
                    <select id="wccbef-quick-search-field" title="Select Field">
                        <option value="id" <?php echo (isset($quick_search_input['quick_search_field']) && $quick_search_input['quick_search_field'] == 'id') ? 'selected' : '' ?>>
                            <?php esc_html_e('ID', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        </option>
                        <option value="title" <?php echo (isset($quick_search_input['quick_search_field']) && $quick_search_input['quick_search_field'] == 'post_title') ? 'selected' : '' ?>>
                            <?php esc_html_e('Title', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        </option>
                    </select>
                    <select id="wccbef-quick-search-operator" title="Select Operator">
                        <option value="like" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'like') ? 'selected' : '' ?>>
                            <?php esc_html_e('Like', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        </option>
                        <option value="exact" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'exact') ? 'selected' : '' ?>>
                            <?php esc_html_e('Exact', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        </option>
                        <option value="not" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'not') ? 'selected' : '' ?>>
                            <?php esc_html_e('Not', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        </option>
                        <option value="begin" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'begin') ? 'selected' : '' ?>>
                            <?php esc_html_e('Begin', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        </option>
                        <option value="end" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'end') ? 'selected' : '' ?>>
                            <?php esc_html_e('End', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?>
                        </option>
                    </select>
                    <button type="button" id="wccbef-quick-search-button" class="wccbef-filter-form-action" data-search-action="quick_search">
                        <i class="lni lni-funnel"></i>
                    </button>
                    <button type="button" id="wccbef-quick-search-reset" class="wccbef-button wccbef-button-blue" style="<?php echo (empty($quick_search_input)) ? 'display:none' : 'display:inline-table'; ?>">Reset Filter</button>
                </div>
            <?php endif; ?>
        </div>
        <div class="wccbef-items-pagination">
            <?php include 'pagination.php'; ?>
        </div>
    </div>
</div>