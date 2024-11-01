<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-bulk-edit">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-float-side-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-tabs">
                            <div class="wpbel-tabs-navigation">
                                <nav class="wpbel-tabs-navbar">
                                    <ul class="wpbel-tabs-list" data-content-id="wpbel-bulk-edit-tabs">
                                        <li><button type="button" class="wpbel-tab-item selected" data-content="general"><?php esc_html_e('General', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button></li>
                                        <?php if ($GLOBALS['wpbel_common']['active_post_type'] != 'page') : ?>
                                            <li>
                                                <button type="button" class="wpbel-tab-item" data-content="categories-tags-taxonomies">
                                                    <?php esc_html_e('Categories/Tags/Taxonomies', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                                </button>
                                            </li>
                                        <?php endif; ?>
                                        <li><button type="button" class="wpbel-tab-item" data-content="date-type"><?php esc_html_e('Date & Type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button></li>
                                        <li><button type="button" class="wpbel-tab-item" data-content="custom-fields"><?php esc_html_e('Custom Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wpbel-tabs-contents wpbel-mt30" id="wpbel-bulk-edit-tabs">
                                <div class="selected wpbel-tab-content-item" data-content="general">
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label for="wpbel-bulk-edit-form-post-title"><?php esc_html_e('Post Title', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                            <select title="Select Operator" id="wpbel-bulk-edit-form-post-title-operator" data-field="operator">
                                                <?php if (!empty($edit_text_operators)) : ?>
                                                    <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                                        <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <option value="text_remove_duplicate"><?php esc_html_e('Remove Duplicate', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                            </select>
                                            <input type="text" id="wpbel-bulk-edit-form-post-title" data-field="value" placeholder="<?php esc_html_e('Post Title ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                            <?php include "variable.php"; ?>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label for="wpbel-bulk-edit-form-post-slug"><?php esc_html_e('Post Slug', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Post Slug ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label><?php esc_html_e('Post Password', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Post Password ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label><?php esc_html_e('Description', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Description ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label>
                                                <?php esc_html_e('Short Description', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                            </label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <textarea placeholder="<?php esc_html_e('Short Description ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled"></textarea>
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Menu Order', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <input type="number" placeholder="<?php esc_html_e('Menu Order ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" class="wpbel-input-md" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group wpbel-select-child-md">
                                        <label><?php esc_html_e('Post Parent', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <select class="wpbel-select2 wpbel-ml5" disabled="disabled">
                                            <option value=""><?php esc_html_e('No Parent', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Comment Status', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Allow Pingback', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Author', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Image', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <button type="button" data-type="single" class="wpbel-button wpbel-button-blue wpbel-ml10 wpbel-h43 wpbel-float-left" disabled="disabled">
                                            <?php esc_html_e('Choose Image', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                        </button>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                </div>
                                <div class="wpbel-tab-content-item" data-content="categories-tags-taxonomies">
                                    <?php if (!empty($taxonomies)) : ?>
                                        <?php foreach ($taxonomies as $name => $taxonomy) : ?>
                                            <div class="wpbel-bulk-edit-form-group" data-type="taxonomy" data-taxonomy="<?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? esc_attr($name) : ''; ?>">
                                                <label for="wpbel-bulk-edit-form-post-attr-<?php echo esc_attr($name); ?>"><?php echo esc_html($taxonomy['label']); ?></label>
                                                <select <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-bulk-edit-form-post-attr-operator-' . esc_attr($name) . '"' : ''; ?> title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" data-field="operator" <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                                    <?php if (!empty($edit_taxonomy_operators)) : ?>
                                                        <?php foreach ($edit_taxonomy_operators as $operator_name => $operator_label) : ?>
                                                            <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <select class="wpbel-select2" data-field="value" <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-bulk-edit-form-post-attr-' . esc_attr($name) . '"' : ''; ?> multiple <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                                    <?php if (!empty($taxonomy['terms'])) : ?>
                                                        <?php foreach ($taxonomy['terms'] as $value_item) : ?>
                                                            <option value="<?php echo esc_attr(($name != 'category') ? $value_item->name : $value_item->term_id); ?>"><?php echo esc_html($value_item->name); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? '<span class="wpbel-short-description">Upgrade to pro version</span>' : ''; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="wpbel-alert wpbel-alert-warning">
                                            <span><?php esc_html_e('There is not any added Custom Taxonomies', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="wpbel-tab-content-item" data-content="date-type">
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Post Type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select Type ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label for="wpbel-bulk-edit-form-post-post-status"><?php esc_html_e('Post Status', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <select class="wpbel-input-md" title="Select" data-field="value" id="wpbel-bulk-edit-form-post-post-status">
                                            <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                            <?php if (!empty($post_statuses)) : ?>
                                                <?php foreach ($post_statuses as $post_status_name => $post_status_label) : ?>
                                                    <option value="<?php echo esc_attr($post_status_name); ?>"><?php echo esc_html($post_status_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Sticky', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Published', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Published ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Published GMT', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Published GMT ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Modified ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Modified GMT', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Modified GMT ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Post URL', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                        <input type="text" placeholder="Post URL ..." class="wpbel-input-md" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                </div>
                                <div class="wpbel-tab-content-item" data-content="custom-fields">
                                    <?php if (!empty($meta_fields)) : ?>
                                        <?php foreach ($meta_fields as $custom_field) : ?>
                                            <div class="wpbel-bulk-edit-form-group" data-type="custom_field" data-taxonomy="<?php echo esc_attr($custom_field['key']); ?>">
                                                <div>
                                                    <label><?php echo esc_html($custom_field['title']); ?></label>
                                                    <?php if (in_array($custom_field['main_type'], wpbel\classes\repositories\Meta_Field::get_fields_name_have_operator()) || ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::STRING_TYPE)) : ?>
                                                        <select disabled="disabled">
                                                            <?php if (!empty($edit_text_operators)) : ?>
                                                                <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="text" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." disabled="disabled">
                                                    <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::NUMBER) : ?>
                                                        <select disabled="disabled">
                                                            <?php if (!empty($edit_number_operators)) : ?>
                                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="number" class="wpbel-input-md" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." disabled="disabled">
                                                    <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CHECKBOX) : ?>
                                                        <select disabled="disabled">
                                                            <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                                        </select>
                                                    <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CALENDAR) : ?>
                                                        <input type="text" class="wpbel-input-md wpbel-datepicker" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." disabled="disabled">
                                                    <?php endif; ?>
                                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="wpbel-alert wpbel-alert-warning">
                                            <span><?php esc_html_e('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbel-float-side-modal-footer">
                    <button type="button" class="wpbel-button wpbel-button-blue" id="wpbel-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                    <button type="button" class="wpbel-button wpbel-button-white" id="wpbel-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>