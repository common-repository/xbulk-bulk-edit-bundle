<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-bulk-edit">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-tabs">
                            <div class="iwbvel-tabs-navigation">
                                <nav class="iwbvel-tabs-navbar">
                                    <ul class="iwbvel-tabs-list" data-content-id="iwbvel-bulk-edit-tabs">
                                        <?php if (!empty($bulk_edit_form_tabs_title) && is_array($bulk_edit_form_tabs_title)) : ?>
                                            <?php $bulk_edit_tab_title_counter = 1; ?>
                                            <?php foreach ($bulk_edit_form_tabs_title as $tab_key => $tab_label) : ?>
                                                <li><button type="button" class="<?php echo ($bulk_edit_tab_title_counter == 1) ? 'selected' : ''; ?> iwbvel-tab-item" data-content="<?php echo esc_attr($tab_key); ?>"><?php echo esc_html($tab_label); ?></button></li>
                                                <?php $bulk_edit_tab_title_counter++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="iwbvel-tabs-contents iwbvel-mt15" id="iwbvel-bulk-edit-tabs">
                                <?php if (!empty($bulk_edit_form_tabs_content)) : ?>
                                    <?php foreach ($bulk_edit_form_tabs_content as $tab_key => $bulk_edit_tab) : ?>
                                        <?php echo (!empty($bulk_edit_tab['wrapper_start'])) ? wp_kses($bulk_edit_tab['wrapper_start'], iwbvel\classes\helpers\Sanitizer::allowed_html()) : ''; ?>
                                        <?php
                                        if (!empty($bulk_edit_tab['fields_top']) && is_array($bulk_edit_tab['fields_top'])) {
                                            foreach ($bulk_edit_tab['fields_top'] as $top_item) {
                                                echo wp_kses($top_item, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($bulk_edit_tab['tabs_titles'])) : ?>
                                            <ul class="iwbvel-sub-tab-titles">
                                                <?php foreach ($bulk_edit_tab['tabs_titles'] as $sub_tab_key => $sub_tab_title) : ?>
                                                    <li><button type="button" class="iwbvel-sub-tab-title" data-content="<?php echo esc_attr($sub_tab_key); ?>"><?php echo esc_html($sub_tab_title); ?></button></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (!empty($bulk_edit_tab['tabs_content'])) : ?>
                                            <div class="iwbvel-sub-tab-contents">
                                                <?php foreach ($bulk_edit_tab['tabs_content'] as $sub_tab_content_key => $fields_group) : ?>
                                                    <div class="iwbvel-sub-tab-content" data-content="<?php echo esc_attr($sub_tab_content_key); ?>">
                                                        <?php
                                                        if (!empty($fields_group) && is_array($fields_group)) :
                                                            foreach ($fields_group as $group_key => $fields) :
                                                                if (!empty($fields) && is_array($fields)) :
                                                                    if (!empty($fields['header']) && is_array($fields['header'])) {
                                                                        foreach ($fields['header'] as $header_item) {
                                                                            echo wp_kses($header_item, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                                                        }
                                                                    }
                                                                    foreach ($fields as $field_items) :
                                                                        if (!empty($field_items['html']) && is_array($field_items['html'])) : ?>
                                                                            <div class="iwbvel-form-group" <?php echo (!empty($field_items['wrap_attributes'])) ? wp_kses($field_items['wrap_attributes'], iwbvel\classes\helpers\Sanitizer::allowed_html()) : ''; ?>>
                                                                                <?php
                                                                                foreach ($field_items['html'] as $field) {
                                                                                    echo wp_kses($field, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                                                                }
                                                                                ?>
                                                                            </div>
                                                        <?php
                                                                        endif;
                                                                    endforeach;
                                                                    if (!empty($fields['footer']) && is_array($fields['footer'])) {
                                                                        foreach ($fields['footer'] as $footer_item) {
                                                                            echo wp_kses($footer_item, iwbvel\classes\helpers\Sanitizer::allowed_html());
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
                                                    <div class="iwbvel-form-group" <?php echo (!empty($field_items['wrap_attributes'])) ? wp_kses($field_items['wrap_attributes'], iwbvel\classes\helpers\Sanitizer::allowed_html()) : ''; ?>>
                                                        <div>
                                                            <?php
                                                            if (!empty($field_items['header']) && is_array($field_items['header'])) {
                                                                foreach ($field_items['header'] as $header_item) {
                                                                    echo wp_kses($header_item, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                                                }
                                                            }

                                                            if (!empty($field_items['html']) && is_array($field_items['html'])) :
                                                                foreach ($field_items['html'] as $field_html) :
                                                                    echo wp_kses($field_html, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                                                endforeach;
                                                            endif;

                                                            if (!empty($field_items['footer']) && is_array($field_items['footer'])) {
                                                                foreach ($field_items['footer'] as $footer_item) {
                                                                    echo wp_kses($footer_item, iwbvel\classes\helpers\Sanitizer::allowed_html());
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                <?php
                                                endif;
                                            endforeach;
                                        endif;
                                        echo (!empty($bulk_edit_tab['wrapper_end'])) ? wp_kses($bulk_edit_tab['wrapper_end'], iwbvel\classes\helpers\Sanitizer::allowed_html()) : '';
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-float-side-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue" id="iwbvel-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                    <button type="button" class="iwbvel-button iwbvel-button-white" id="iwbvel-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>