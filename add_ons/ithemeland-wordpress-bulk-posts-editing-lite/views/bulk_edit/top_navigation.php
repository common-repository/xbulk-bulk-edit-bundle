<div class="wpbel-top-nav">
    <div class="wpbel-top-nav-buttons" id="wpbel-bulk-edit-navigation">
        <div class="wpbel-top-nav-buttons-group">
            <button type="button" id="wpbel-bulk-edit-bulk-edit-btn" data-toggle="modal" data-target="#wpbel-modal-bulk-edit" class="wpbel-button-blue" data-fetch-post="<?php echo (!empty($settings['fetch_data_in_bulk'])) ? esc_attr($settings['fetch_data_in_bulk']) : ''; ?>">
                <?php esc_html_e('Bulk Edit', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
            </button>
        </div>
        <div class="wpbel-top-nav-buttons-border"></div>
        <div class="wpbel-top-nav-buttons-group">
            <button type="button" data-toggle="modal" data-target="#wpbel-modal-column-profiles">
                <?php esc_html_e('Column Profile', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
            </button>
            <button type="button" data-toggle="modal" data-target="#wpbel-modal-filter-profiles">
                <?php esc_html_e('Filter Profiles', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
            </button>
            <?php $reset_filters_visibility = ((!empty($filter_profile_use_always) && $filter_profile_use_always != 'default') || (!empty($last_filter_data))) ? "display:inline-table" : "display:none"; ?>
            <button type="button" id="wpbel-bulk-edit-reset-filter" class="wpbel-button-blue" <?php echo 'style="' . esc_attr($reset_filters_visibility) . '"'; ?>>
                <?php esc_html_e('Reset Filter', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
            </button>
        </div>
        <div class="wpbel-top-nav-buttons-border"></div>
        <div class="wpbel-top-nav-buttons-group">
            <button type="button" title="Undo latest history" class="wpbel-button-blue" <?php echo (empty($histories)) ? 'disabled="disabled"' : ''; ?> disabled="disabled">
                <?php esc_html_e('Undo', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
            </button>
            <button type="button" title="Redo" class="wpbel-button-blue" <?php echo (empty($reverted)) ? 'disabled="disabled"' : ''; ?> disabled="disabled">
                <?php esc_html_e('Redo', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
            </button>
            <button type="button" data-toggle="modal" data-post-type="<?php echo esc_attr($GLOBALS['wpbel_common']['active_post_type']); ?>" data-target="#wpbel-modal-new-item"><?php esc_html_e('New ' . str_replace('_', ' ', ucfirst(esc_attr($GLOBALS['wpbel_common']['active_post_type']))), 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button>
        </div>
        <div class="wpbel-top-nav-buttons-border"></div>
        <div class="wpbel-bulk-edit-form-selection-tools">
            <div class="wpbel-top-nav-buttons-group">
                <button type="button" id="wpbel-bulk-edit-unselect"><?php esc_html_e('Unselect', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button>
                <button type="button" id="wpbel-bulk-edit-duplicate" data-toggle="modal" data-target="#wpbel-modal-item-duplicate"><?php esc_html_e('Duplicate', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                </button>
                <div class="wpbel-bulk-edit-delete-item">
                    <span>
                        <?php esc_html_e('Delete', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        <i class="lni lni-chevron-down"></i>
                    </span>
                    <div class="wpbel-bulk-edit-delete-item-buttons" style="display: none;">
                        <ul>
                            <li class="wpbel-bulk-edit-delete-action" data-delete-type="trash">
                                <?php esc_html_e('Move to trash', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                            </li>
                            <li class="wpbel-bulk-edit-delete-action" data-delete-type="permanently">
                                <?php esc_html_e('Permanently', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="wpbel-top-nav-buttons-border"></div>
        </div>
        <div class="wpbel-top-nav-buttons-group">
            <label>
                <input type="checkbox" id="wpbel-inline-edit-bind">
                <?php esc_html_e('Bind Edit', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                <i title="Set the value of edited item to all selected items" class="dashicons dashicons-info"></i>
            </label>
        </div>
    </div>
    <div class="wpbel-top-nav-filters-switcher">
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbel-switcher-form">
            <input type="hidden" name="action" value="wpbel_switcher">
            <?php wp_nonce_field(); ?>
            <label for="wpbel-switcher"><?php esc_html_e('Select post type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
            <select id="wpbel-switcher" name="post_type" title="<?php esc_attr_e('Select post type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                <?php if (!empty($post_types)) : ?>
                    <?php foreach ($post_types as $post_type_key => $post_type_label) : ?>
                        <option value="<?php echo esc_attr($post_type_key) ?>" <?php echo ($GLOBALS['wpbel_common']['active_post_type'] == $post_type_key) ? esc_attr('selected') : ''; ?>>
                            <?php echo esc_html($post_type_label); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </form>
    </div>
    <div class="wpbel-top-nav-filters">
        <div class="wpbel-top-nav-filters-left">
            <div class="wpbel-top-nav-filters-per-page">
                <select id="wpbel-quick-per-page" title="The number of posts per page">
                    <?php foreach (wpbel\classes\helpers\Setting_Helper::get_count_per_page_items() as $count_per_page_item) : ?>
                        <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if (isset($current_settings['count_per_page']) && $current_settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                            <?php echo esc_html($count_per_page_item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (isset($settings['show_quick_search']) && $settings['show_quick_search'] == 'yes') : ?>
                <?php $quick_search_input = (isset($last_filter_data) && !empty($last_filter_data['search_type']) && $last_filter_data['search_type'] == 'quick_search') ? $last_filter_data : ''; ?>
                <div class="wpbel-top-nav-filters-search">
                    <input type="text" id="wpbel-quick-search-text" placeholder="<?php esc_html_e('Quick Search ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" title="Quick Search" value="<?php echo (isset($quick_search_input['quick_search_text'])) ? esc_html($quick_search_input['quick_search_text']) : '' ?>">
                    <select id="wpbel-quick-search-field" title="Select Field">
                        <option value="title" <?php echo (isset($quick_search_input['quick_search_field']) && $quick_search_input['quick_search_field'] == 'title') ? 'selected' : '' ?>>
                            <?php esc_html_e('Title', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </option>
                        <option value="id" <?php echo (isset($quick_search_input['quick_search_field']) && $quick_search_input['quick_search_field'] == 'id') ? 'selected' : '' ?>>
                            <?php esc_html_e('ID', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </option>
                    </select>
                    <select id="wpbel-quick-search-operator" title="Select Operator">
                        <option value="like" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'like') ? 'selected' : '' ?>>
                            <?php esc_html_e('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </option>
                        <option value="exact" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'exact') ? 'selected' : '' ?>>
                            <?php esc_html_e('Exact', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </option>
                        <option value="not" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'not') ? 'selected' : '' ?>>
                            <?php esc_html_e('Not', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </option>
                        <option value="begin" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'begin') ? 'selected' : '' ?>>
                            <?php esc_html_e('Begin', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </option>
                        <option value="end" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'end') ? 'selected' : '' ?>>
                            <?php esc_html_e('End', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </option>
                    </select>
                    <button type="button" id="wpbel-quick-search-button" class="wpbel-filter-form-action" data-search-action="quick_search">
                        <i class="lni lni-funnel"></i>
                    </button>
                    <?php if (!empty($quick_search_input)) : ?>
                        <button type="button" id="wpbel-quick-search-reset" class="wpbel-button wpbel-button-blue">Reset Filter</button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="wpbel-items-pagination">
            <?php include 'pagination.php'; ?>
        </div>
    </div>
</div>