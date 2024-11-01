<div class="wcbef-top-nav">
    <div class="wcbef-top-nav-buttons" id="wcbef-bulk-edit-navigation">
        <div class="wcbef-top-nav-buttons-group">
            <button type="button" id="wcbef-bulk-edit-bulk-edit-btn" data-toggle="modal" data-target="#wcbef-modal-bulk-edit" class="wcbef-button-blue">
                <?php esc_html_e('Bulk Edit', 'woocommerce-bulk-edit-free'); ?>
            </button>
            <button type="button" class="wcbef-bulk-edit-variations" data-toggle="modal" data-target="#wcbef-modal-variation-bulk-edit">
                <?php esc_html_e('Variations', 'woocommerce-bulk-edit-free'); ?>
            </button>
        </div>
        <div class="wcbef-top-nav-buttons-border"></div>
        <div class="wcbef-top-nav-buttons-group">
            <button type="button" data-toggle="modal" data-target="#wcbef-modal-column-profiles">
                <?php esc_html_e('Column Profile', 'woocommerce-bulk-edit-free'); ?>
            </button>
            <button type="button" data-toggle="modal" data-target="#wcbef-modal-filter-profiles">
                <?php esc_html_e('Filter Profiles', 'woocommerce-bulk-edit-free'); ?>
            </button>
            <?php $visibility = (!empty($filter_profile_use_always) && $filter_profile_use_always != 'default') ? "display:block" : "display:none"; ?>
            <button type="button" id="wcbef-bulk-edit-reset-filter" class="wcbef-button-blue" <?php echo 'style="' . esc_attr($visibility) . '"'; ?>>
                <?php esc_html_e('Reset Filter', 'woocommerce-bulk-edit-free'); ?>
            </button>
        </div>
        <div class="wcbef-top-nav-buttons-border"></div>
        <div class="wcbef-top-nav-buttons-group">
            <button type="button" title="Undo latest history" class="wcbef-button-blue" disabled="disabled">
                <?php esc_html_e('Undo', 'woocommerce-bulk-edit-free'); ?>
            </button>
            <button type="button" title="Redo" class="wcbef-button-blue" disabled="disabled">
                <?php esc_html_e('Redo', 'woocommerce-bulk-edit-free'); ?>
            </button>
            <button type="button" data-toggle="modal" data-target="#wcbef-modal-new-item"><?php esc_html_e('New Product', 'woocommerce-bulk-edit-free'); ?></button>
        </div>
        <div class="wcbef-top-nav-buttons-border"></div>
        <div class="wcbef-bulk-edit-form-select-tools">
            <div class="wcbef-top-nav-buttons-group">
                <button type="button" id="wcbef-bulk-edit-unselect"><?php esc_html_e('Unselect', 'woocommerce-bulk-edit-free'); ?></button>
                <button type="button" id="wcbef-bulk-edit-duplicate" data-toggle="modal" data-target="#wcbef-modal-product-duplicate"><?php esc_html_e('Duplicate', 'woocommerce-bulk-edit-free'); ?>
                </button>
                <div class="wcbef-bulk-edit-delete-product">
                    <span>
                        <?php esc_html_e('Delete', 'woocommerce-bulk-edit-free'); ?>
                        <i class="lni lni-chevron-down"></i>
                    </span>
                    <div class="wcbef-bulk-edit-delete-product-buttons" style="display: none;">
                        <ul>
                            <li class="wcbef-bulk-edit-delete-action" data-delete-type="trash">
                                <?php esc_html_e('Move to trash', 'woocommerce-bulk-edit-free'); ?>
                            </li>
                            <li class="wcbef-bulk-edit-delete-action" data-delete-type="permanently">
                                <?php esc_html_e('Permanently', 'woocommerce-bulk-edit-free'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="wcbef-top-nav-buttons-border"></div>
        </div>
        <div class="wcbef-top-nav-buttons-group">
            <label>
                <input type="checkbox" id="wcbef-bulk-edit-show-variations">
                <?php esc_html_e('Variation', 'woocommerce-bulk-edit-free'); ?>
                <i title="In this mode, all of variations will be appear below <br> the Variable products in separate rows." class="dashicons dashicons-info"></i>
            </label>
            <label id="wcbef-bulk-edit-select-all-variations-tools">
                <input type="checkbox" id="wcbef-bulk-edit-select-all-variations">
                <?php esc_html_e('Select All Variations', 'woocommerce-bulk-edit-free'); ?>
            </label>
            <label>
                <input type="checkbox" id="wcbef-inline-edit-bind">
                <?php esc_html_e('Bind Edit', 'woocommerce-bulk-edit-free'); ?>
                <i title="Set the value of edited product to all selected products" class="dashicons dashicons-info"></i>
            </label>
        </div>
    </div>
    <div class="wcbef-top-nav-filters">
        <div class="wcbef-top-nav-filters-left">
            <div class="wcbef-top-nav-filters-per-page">
                <select id="wcbef-quick-per-page" title="The number of products per page">
                    <?php foreach (\wcbef\classes\helpers\Setting::get_count_per_page_items() as $count_per_page_item) : ?>
                        <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if ((!empty($current_settings['count_per_page'])) && $current_settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                            <?php echo esc_html($count_per_page_item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (isset($settings['show_quick_search']) && $settings['show_quick_search'] == 'yes') : ?>
                <div class="wcbef-top-nav-filters-search">
                    <input type="text" id="wcbef-quick-search-text" placeholder="<?php esc_html_e('Quick Search ...', 'woocommerce-bulk-edit-free'); ?>" title="Quick Search" value="<?php echo (isset($_GET['wcbef-search-text'])) ? esc_html($_GET['wcbef-search-text']) : '' ?>">
                    <select id="wcbef-quick-search-field" title="Select Field">
                        <option value="title" <?php echo (isset($_GET['wcbef-search-field']) && $_GET['wcbef-search-field'] == 'title') ? 'selected' : '' ?>>
                            <?php esc_html_e('Title', 'woocommerce-bulk-edit-free'); ?>
                        </option>
                        <option value="id" <?php echo (isset($_GET['wcbef-search-field']) && $_GET['wcbef-search-field'] == 'id') ? 'selected' : '' ?>>
                            <?php esc_html_e('ID', 'woocommerce-bulk-edit-free'); ?>
                        </option>
                    </select>
                    <select id="wcbef-quick-search-operator" title="Select Operator">
                        <option value="like" <?php echo (isset($_GET['wcbef-search-operator']) && $_GET['wcbef-search-operator'] == 'like') ? 'selected' : '' ?>>
                            <?php esc_html_e('Like', 'woocommerce-bulk-edit-free'); ?>
                        </option>
                        <option value="exact" <?php echo (isset($_GET['wcbef-search-operator']) && $_GET['wcbef-search-operator'] == 'exact') ? 'selected' : '' ?>>
                            <?php esc_html_e('Exact', 'woocommerce-bulk-edit-free'); ?>
                        </option>
                        <option value="not" <?php echo (isset($_GET['wcbef-search-operator']) && $_GET['wcbef-search-operator'] == 'not') ? 'selected' : '' ?>>
                            <?php esc_html_e('Not', 'woocommerce-bulk-edit-free'); ?>
                        </option>
                        <option value="begin" <?php echo (isset($_GET['wcbef-search-operator']) && $_GET['wcbef-search-operator'] == 'begin') ? 'selected' : '' ?>>
                            <?php esc_html_e('Begin', 'woocommerce-bulk-edit-free'); ?>
                        </option>
                        <option value="end" <?php echo (isset($_GET['wcbef-search-operator']) && $_GET['wcbef-search-operator'] == 'end') ? 'selected' : '' ?>>
                            <?php esc_html_e('End', 'woocommerce-bulk-edit-free'); ?>
                        </option>
                    </select>
                    <button type="button" id="wcbef-quick-search-button" class="wcbef-filter-form-action" data-search-action="quick_search">
                        <i class="lni lni-funnel"></i>
                    </button>
                </div>
            <?php endif; ?>
        </div>
        <div class="wcbef-products-pagination">
            <?php include 'pagination.php'; ?>
        </div>
    </div>
</div>