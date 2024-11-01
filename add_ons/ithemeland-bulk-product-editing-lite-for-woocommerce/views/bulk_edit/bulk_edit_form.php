<?php

use wcbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-bulk-edit">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-tabs">
                            <div class="wcbel-tabs-navigation">
                                <nav class="wcbel-tabs-navbar">
                                    <ul class="wcbel-tabs-list" data-content-id="wcbel-bulk-edit-tabs">
                                        <?php if (!empty($bulk_edit_form_tabs_title) && is_array($bulk_edit_form_tabs_title)) : ?>
                                            <?php $bulk_edit_tab_title_counter = 1; ?>
                                            <?php foreach ($bulk_edit_form_tabs_title as $tab_key => $tab_label) : ?>
                                                <li><button class="<?php echo ($bulk_edit_tab_title_counter == 1) ? 'selected' : ''; ?> wcbel-tab-item" data-content="<?php echo esc_attr($tab_key); ?>" type="button"><?php echo esc_attr($tab_label); ?></button></li>
                                                <?php $bulk_edit_tab_title_counter++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wcbel-tabs-contents wcbel-mt15" id="wcbel-bulk-edit-tabs">
                                <?php if (!empty($bulk_edit_form_tabs_content)) : ?>
                                    <?php foreach ($bulk_edit_form_tabs_content as $tab_key => $bulk_edit_tab) : ?>
                                        <?php echo (!empty($bulk_edit_tab['wrapper_start'])) ? wp_kses($bulk_edit_tab['wrapper_start'], Sanitizer::allowed_html_tags()) : ''; ?>
                                        <?php
                                        if (!empty($bulk_edit_tab['fields_top']) && is_array($bulk_edit_tab['fields_top'])) {
                                            foreach ($bulk_edit_tab['fields_top'] as $top_item) {
                                                echo wp_kses($top_item, Sanitizer::allowed_html_tags());
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($bulk_edit_tab['tabs_titles'])) : ?>
                                            <ul class="wcbel-sub-tab-titles">
                                                <?php foreach ($bulk_edit_tab['tabs_titles'] as $sub_tab_key => $sub_tab_title) : ?>
                                                    <li><button type="button" class="wcbel-sub-tab-title" data-content="<?php echo esc_attr($sub_tab_key); ?>"><?php echo esc_html($sub_tab_title); ?></button></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (!empty($bulk_edit_tab['tabs_content'])) : ?>
                                            <div class="wcbel-sub-tab-contents">
                                                <?php foreach ($bulk_edit_tab['tabs_content'] as $sub_tab_content_key => $fields_group) : ?>
                                                    <div class="wcbel-sub-tab-content" data-content="<?php echo esc_attr($sub_tab_content_key); ?>">
                                                        <?php
                                                        if (!empty($fields_group) && is_array($fields_group)) :
                                                            foreach ($fields_group as $group_key => $fields) :
                                                                if (!empty($fields) && is_array($fields)) :
                                                                    if (!empty($fields['header']) && is_array($fields['header'])) {
                                                                        foreach ($fields['header'] as $header_item) {
                                                                            echo wp_kses($header_item, Sanitizer::allowed_html_tags());
                                                                        }
                                                                    }
                                                                    foreach ($fields as $field_items) :
                                                                        if (!empty($field_items['html']) && is_array($field_items['html'])) : ?>
                                                                            <div class="wcbel-form-group" <?php echo (!empty($field_items['wrap_attributes'])) ? wp_kses($field_items['wrap_attributes'], Sanitizer::allowed_html_tags()) : ''; ?>>
                                                                                <?php
                                                                                foreach ($field_items['html'] as $field) {
                                                                                    echo wp_kses($field, Sanitizer::allowed_html_tags());
                                                                                }
                                                                                ?>
                                                                            </div>
                                                        <?php
                                                                        endif;
                                                                    endforeach;
                                                                    if (!empty($fields['footer']) && is_array($fields['footer'])) {
                                                                        foreach ($fields['footer'] as $footer_item) {
                                                                            echo wp_kses($footer_item, Sanitizer::allowed_html_tags());
                                                                        }
                                                                    }
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php
                                        endif;
                                        if (!empty($bulk_edit_tab['fields']) && is_array($bulk_edit_tab['fields'])) :
                                            foreach ($bulk_edit_tab['fields'] as $field_key => $field_items) :
                                                if (!empty($field_items) && is_array($field_items)) : ?>
                                                    <div class="wcbel-form-group" <?php echo (!empty($field_items['wrap_attributes'])) ? wp_kses($field_items['wrap_attributes'], Sanitizer::allowed_html_tags()) : ''; ?>>
                                                        <div>
                                                            <?php
                                                            if (!empty($field_items['header']) && is_array($field_items['header'])) {
                                                                foreach ($field_items['header'] as $header_item) {
                                                                    echo wp_kses($header_item, Sanitizer::allowed_html_tags());
                                                                }
                                                            }

                                                            if (!empty($field_items['html']) && is_array($field_items['html'])) :
                                                                foreach ($field_items['html'] as $field_html) :
                                                                    echo wp_kses($field_html, Sanitizer::allowed_html_tags());
                                                                endforeach;
                                                            endif;

                                                            if (!empty($field_items['footer']) && is_array($field_items['footer'])) {
                                                                foreach ($field_items['footer'] as $footer_item) {
                                                                    echo wp_kses($footer_item, Sanitizer::allowed_html_tags());
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                <?php
                                                endif;
                                            endforeach;
                                        endif;
                                        echo (!empty($bulk_edit_tab['wrapper_end'])) ? wp_kses($bulk_edit_tab['wrapper_end'], Sanitizer::allowed_html_tags()) : '';
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbel-float-side-modal-footer">
                    <button type="button" class="wcbel-button wcbel-button-blue" id="wcbel-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                    <button type="button" class="wcbel-button wcbel-button-white" id="wcbel-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>