<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-top-nav-filters">
    <div class="iwbvel-top-nav-filters-left">
        <?php $quick_search_input = (isset($last_filter_data) && !empty($last_filter_data['search_type']) && $last_filter_data['search_type'] == 'quick_search') ? $last_filter_data : ''; ?>
        <div class="iwbvel-top-nav-filters-search">
            <input type="text" id="iwbvel-quick-search-text" placeholder="<?php esc_html_e('Quick Search ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" title="Quick Search" value="<?php echo (isset($quick_search_input['quick_search_text'])) ? esc_attr($quick_search_input['quick_search_text']) : '' ?>">
            <select id="iwbvel-quick-search-field" title="Select Field">
                <option value="title" <?php echo (isset($quick_search_input['quick_search_field']) && $quick_search_input['quick_search_field'] == 'title') ? 'selected' : '' ?>>
                    <?php esc_html_e('Title', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </option>
                <option value="id" <?php echo (isset($quick_search_input['quick_search_field']) && $quick_search_input['quick_search_field'] == 'id') ? 'selected' : '' ?>>
                    <?php esc_html_e('ID', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </option>
            </select>
            <select id="iwbvel-quick-search-operator" title="Select Operator">
                <option value="like" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'like') ? 'selected' : '' ?>>
                    <?php esc_html_e('Like', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </option>
                <option value="exact" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'exact') ? 'selected' : '' ?>>
                    <?php esc_html_e('Exact', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </option>
                <option value="not" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'not') ? 'selected' : '' ?>>
                    <?php esc_html_e('Not', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </option>
                <option value="begin" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'begin') ? 'selected' : '' ?>>
                    <?php esc_html_e('Begin', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </option>
                <option value="end" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'end') ? 'selected' : '' ?>>
                    <?php esc_html_e('End', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                </option>
            </select>
            <button type="button" id="iwbvel-quick-search-button" class="iwbvel-filter-form-action" data-search-action="quick_search">
                <i class="iwbvel-icon-filter1"></i>
            </button>
            <button type="button" id="iwbvel-quick-search-reset" class="iwbvel-button iwbvel-button-blue" style="<?php echo (empty($quick_search_input)) ? 'display:none' : 'display:inline-table'; ?>">Reset Filter</button>
        </div>
        <div class="iwbvel-top-nav-divider"></div>

        <div class="iwbvel-status-filter-container">
            <button type="button" class="iwbvel-status-filter-button" title="<?php esc_attr_e('Status Filter', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                <?php esc_html_e('Statuses', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> <span class="iwbvel-status-filter-selected-name"></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z" />
                </svg>
            </button>

            <div class="iwbvel-top-nav-status-filter"></div>
        </div>
    </div>
</div>