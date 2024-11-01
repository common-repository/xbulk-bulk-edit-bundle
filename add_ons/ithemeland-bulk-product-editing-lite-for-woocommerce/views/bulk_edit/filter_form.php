<?php

use wcbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-filter">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <input type="hidden" id="filter-form-changed" value="">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Form', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-tabs">
                            <div class="wcbel-tabs-navigation">
                                <nav class="wcbel-tabs-navbar">
                                    <ul class="wcbel-tabs-list" data-content-id="wcbel-bulk-edit-filter-tabs-contents">
                                        <?php if (!empty($filter_form_tabs_title) && is_array($filter_form_tabs_title)) : ?>
                                            <?php $filter_tab_title_counter = 1; ?>
                                            <?php foreach ($filter_form_tabs_title as $tab_key => $tab_label) : ?>
                                                <li><button type="button" class="<?php echo ($filter_tab_title_counter == 1) ? 'selected' : ''; ?> wcbel-tab-item" data-content="<?php echo esc_attr($tab_key); ?>"><?php echo esc_attr($tab_label); ?></button></li>
                                                <?php $filter_tab_title_counter++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wcbel-tabs-contents wcbel-mt15" id="wcbel-bulk-edit-filter-tabs-contents">
                                <?php if (!empty($filter_form_tabs_content)) : ?>
                                    <?php foreach ($filter_form_tabs_content as $tab_key => $filter_tab) : ?>
                                        <?php echo (!empty($filter_tab['wrapper_start'])) ? wp_kses($filter_tab['wrapper_start'], Sanitizer::allowed_html_tags()) : ''; ?>
                                        <?php
                                        if (!empty($filter_tab['fields_top']) && is_array($filter_tab['fields_top'])) {
                                            foreach ($filter_tab['fields_top'] as $top_item) {
                                                echo wp_kses($top_item, Sanitizer::allowed_html_tags());
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($filter_tab['tabs_titles'])) : ?>
                                            <ul class="wcbel-sub-tab-titles">
                                                <?php foreach ($filter_tab['tabs_titles'] as $sub_tab_key => $sub_tab_title) : ?>
                                                    <li><button type="button" class="wcbel-sub-tab-title" data-content="<?php echo esc_attr($sub_tab_key); ?>"><?php echo esc_html($sub_tab_title); ?></button></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (!empty($filter_tab['tabs_content'])) : ?>
                                            <div class="wcbel-sub-tab-contents">
                                                <?php foreach ($filter_tab['tabs_content'] as $sub_tab_content_key => $fields_group) : ?>
                                                    <div class="wcbel-sub-tab-content" data-content="<?php echo esc_attr($sub_tab_content_key); ?>">
                                                        <?php
                                                        if (!empty($fields_group) && is_array($fields_group)) :
                                                            foreach ($fields_group as $fields) :
                                                                if (!empty($fields) && is_array($fields)) :
                                                                    foreach ($fields as $field_key => $field_items) : ?>
                                                                        <div class="wcbel-form-group" data-name="<?php echo esc_attr($field_key); ?>">
                                                                            <?php
                                                                            if (!empty($field_items) && is_array($field_items)) :
                                                                                foreach ($field_items as $field) {
                                                                                    echo wp_kses($field, Sanitizer::allowed_html_tags());
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
                                                    <div class="wcbel-form-group" data-name="<?php echo esc_attr($field_key); ?>" <?php echo wp_kses($wrap_attributes, Sanitizer::allowed_html_tags()); ?>>
                                                        <?php foreach ($field_items as $field_html) : ?>
                                                            <?php echo wp_kses($field_html, Sanitizer::allowed_html_tags()); ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php echo (!empty($filter_tab['wrapper_end'])) ? wp_kses($filter_tab['wrapper_end'], Sanitizer::allowed_html_tags()) : ''; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbel-float-side-modal-footer">
                    <div class="wcbel-tab-footer-left">
                        <button type="button" id="wcbel-filter-form-get-products" class="wcbel-button wcbel-button-blue wcbel-filter-form-action" data-search-action="pro_search">
                            <?php esc_html_e('Get products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                        <button type="button" class="wcbel-button wcbel-button-white" id="wcbel-filter-form-reset">
                            <?php esc_html_e('Reset Filters', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                    </div>
                    <div class="wcbel-tab-footer-right">
                        <input type="text" name="save_filter" id="wcbel-filter-form-save-preset-name" placeholder="Filter Name ..." class="" title="Filter Name">
                        <button type="button" id="wcbel-filter-form-save-preset" class="wcbel-button wcbel-button-blue">
                            <?php esc_html_e('Save Profile', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>