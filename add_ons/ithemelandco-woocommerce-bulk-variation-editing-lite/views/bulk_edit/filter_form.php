<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-filter">

    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <input type="hidden" id="filter-form-changed" value="">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Form', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-tabs">
                            <div class="iwbvel-tabs-navigation">
                                <nav class="iwbvel-tabs-navbar">
                                    <ul class="iwbvel-tabs-list" data-content-id="iwbvel-bulk-edit-filter-tabs-contents">
                                        <?php if (!empty($filter_form_tabs_title) && is_array($filter_form_tabs_title)) : ?>
                                            <?php $filter_tab_title_counter = 1; ?>
                                            <?php foreach ($filter_form_tabs_title as $tab_key => $tab_label) : ?>
                                                <li><button type="button" class="<?php echo ($filter_tab_title_counter == 1) ? 'selected' : ''; ?> iwbvel-tab-item" data-content="<?php echo esc_attr($tab_key); ?>"><?php echo esc_html($tab_label); ?></button></li>
                                                <?php $filter_tab_title_counter++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="iwbvel-tabs-contents iwbvel-mt15" id="iwbvel-bulk-edit-filter-tabs-contents">
                                <?php if (!empty($filter_form_tabs_content)) : ?>
                                    <?php foreach ($filter_form_tabs_content as $tab_key => $filter_tab) : ?>
                                        <?php echo (!empty($filter_tab['wrapper_start'])) ? wp_kses($filter_tab['wrapper_start'], iwbvel\classes\helpers\Sanitizer::allowed_html()) : ''; ?>
                                        <?php
                                        if (!empty($filter_tab['fields_top']) && is_array($filter_tab['fields_top'])) {
                                            foreach ($filter_tab['fields_top'] as $top_item) {
                                                echo wp_kses($top_item, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($filter_tab['tabs_titles'])) : ?>
                                            <ul class="iwbvel-sub-tab-titles">
                                                <?php foreach ($filter_tab['tabs_titles'] as $sub_tab_key => $sub_tab_title) : ?>
                                                    <li><button type="button" class="iwbvel-sub-tab-title" data-content="<?php echo esc_attr($sub_tab_key); ?>"><?php echo esc_html($sub_tab_title); ?></button></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (!empty($filter_tab['tabs_content'])) : ?>
                                            <div class="iwbvel-sub-tab-contents">
                                                <?php foreach ($filter_tab['tabs_content'] as $sub_tab_content_key => $fields_group) : ?>
                                                    <div class="iwbvel-sub-tab-content" data-content="<?php echo esc_attr($sub_tab_content_key); ?>">
                                                        <?php
                                                        if (!empty($fields_group) && is_array($fields_group)) :
                                                            foreach ($fields_group as $fields) :
                                                                if (!empty($fields) && is_array($fields)) :
                                                                    foreach ($fields as $field_key => $field_items) : ?>
                                                                        <div class="iwbvel-form-group" data-name="<?php echo esc_attr($field_key); ?>">
                                                                            <?php
                                                                            if (!empty($field_items) && is_array($field_items)) :
                                                                                foreach ($field_items as $field) {
                                                                                    echo wp_kses($field, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                                                                }
                                                                            endif; ?>
                                                                        </div>
                                                        <?php
                                                                    endforeach;
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($filter_tab['fields']) && is_array($filter_tab['fields'])) : ?>
                                            <?php foreach ($filter_tab['fields'] as $field_key => $field_items) : ?>
                                                <?php
                                                if (!empty($field_items) && is_array($field_items)) :
                                                    $wrap_attributes = '';
                                                    if (!empty($field_items['wrap_attributes'])) {
                                                        $wrap_attributes = $field_items['wrap_attributes'];
                                                        unset($field_items['wrap_attributes']);
                                                    }
                                                ?>
                                                    <div class="iwbvel-form-group" data-name="<?php echo esc_attr($field_key); ?>" <?php echo wp_kses($wrap_attributes, iwbvel\classes\helpers\Sanitizer::allowed_html()) ?>>
                                                        <?php foreach ($field_items as $field_html) : ?>
                                                            <?php echo wp_kses($field_html, iwbvel\classes\helpers\Sanitizer::allowed_html()); ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php echo (!empty($filter_tab['wrapper_end'])) ? wp_kses($filter_tab['wrapper_end'], iwbvel\classes\helpers\Sanitizer::allowed_html()) : ''; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-float-side-modal-footer">
                    <div class="iwbvel-tab-footer-left">
                        <button type="button" id="iwbvel-filter-form-get-products" class="iwbvel-button iwbvel-button-blue iwbvel-filter-form-action" data-search-action="pro_search">
                            <?php esc_html_e('Get products', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                        <button type="button" class="iwbvel-button iwbvel-button-white" id="iwbvel-filter-form-reset">
                            <?php esc_html_e('Reset Filters', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                    </div>
                    <div class="iwbvel-tab-footer-right">
                        <input type="text" name="save_filter" id="iwbvel-filter-form-save-preset-name" placeholder="Filter Name ..." class="" title="Filter Name">
                        <button type="button" id="iwbvel-filter-form-save-preset" class="iwbvel-button iwbvel-button-blue">
                            <?php esc_html_e('Save Profile', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>