<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-filter">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <input type="hidden" id="filter-form-changed" value="">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Form', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-float-side-modal-body">
                    <div class="wpbel-wrap">
                        <ul class="wpbel-tabs-list" data-content-id="wpbel-bulk-edit-filter-tabs-contents">
                            <li><button type="button" class="wpbel-tab-item selected" data-content="bulk-edit-filter-general"><?php esc_html_e('General', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button></li>
                            <?php if ($GLOBALS['wpbel_common']['active_post_type'] != 'page') : ?>
                                <li>
                                    <button type="button" class="wpbel-tab-item" data-content="bulk-edit-filter-categories-tags-taxonomies">
                                        <?php esc_html_e('Categories/Tags/Taxonomies', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                    </button>
                                </li>
                            <?php endif; ?>
                            <li><button type="button" class="wpbel-tab-item" data-content="bulk-edit-filter-date-type"><?php esc_html_e('Date & Type', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button></li>
                            <li><button type="button" class="wpbel-tab-item" data-content="bulk-edit-filter-custom-fields"><?php esc_html_e('Custom Fields', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button></li>
                        </ul>
                        <div class="wpbel-tabs-contents" id="wpbel-bulk-edit-filter-tabs-contents">
                            <div class="selected wpbel-tab-content-item" data-content="bulk-edit-filter-general">
                                <div class="wpbel-form-group" data-name="post_ids">
                                    <label for="wpbel-filter-form-post-ids"><?php esc_html_e('Post ID(s)', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbel-filter-form-post-ids-operator" title="Select Operator" data-field="operator">
                                        <option value="exact"><?php esc_html_e('Exact', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <input type="text" id="wpbel-filter-form-post-ids" data-field="value" placeholder="<?php esc_html_e('for example: 1,2,3 or 1-10 or 1,2,3|10-20', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                    <label class="wpbel-ml10">
                                        <input type="checkbox" id="wpbel-filter-form-post-ids-parent-only" value="yes">
                                        <?php esc_html_e('Only Parent Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                    </label>
                                </div>
                                <div class="wpbel-form-group" data-name="post_title">
                                    <label for="wpbel-filter-form-post-title"><?php esc_html_e('Post Title', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbel-filter-form-post-title-operator" data-field="operator" title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                        <option value="like"><?php esc_html_e('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="exact"><?php esc_html_e('Exact', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="not"><?php esc_html_e('Not', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="begin"><?php esc_html_e('Begin', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="end"><?php esc_html_e('End', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <input type="text" id="wpbel-filter-form-post-title" data-field="value" placeholder="<?php esc_html_e('Post Title ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                </div>
                                <div class="wpbel-form-group" data-name="post_content">
                                    <label for="wpbel-filter-form-post-content"><?php esc_html_e('Post Content', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbel-filter-form-post-content-operator" data-field="operator" title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                        <option value="like"><?php esc_html_e('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="exact"><?php esc_html_e('Exact', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="not"><?php esc_html_e('Not', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="begin"><?php esc_html_e('Begin', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="end"><?php esc_html_e('End', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <input type="text" id="wpbel-filter-form-post-content" data-field="value" placeholder="<?php esc_html_e('Post Content ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>">
                                </div>
                                <div class="wpbel-form-group" data-name="post_excerpt">
                                    <label><?php esc_html_e('Post Excerpt', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                        <option value="like"><?php esc_html_e('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="exact"><?php esc_html_e('Exact', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="not"><?php esc_html_e('Not', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="begin"><?php esc_html_e('Begin', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                        <option value="end"><?php esc_html_e('End', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <input type="text" placeholder="<?php esc_html_e('Post Excerpt ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="post_slug">
                                    <label><?php esc_html_e('Post Slug', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                        <option value="like"><?php esc_html_e('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <input type="text" placeholder="<?php esc_html_e('Post Slug ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="post_url">
                                    <label><?php esc_html_e('Post URL', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                        <option value="like"><?php esc_html_e('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <input type="text" placeholder="<?php esc_html_e('Post URL ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="author">
                                    <label><?php esc_html_e('By Author', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select class="wpbel-input-md" disabled="disabled">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                            </div>
                            <div class="wpbel-tab-content-item" data-content="bulk-edit-filter-categories-tags-taxonomies">
                                <?php if (!empty($taxonomies)) : ?>
                                    <?php foreach ($taxonomies as $name => $taxonomy) : ?>
                                        <div class="wpbel-form-group" data-type="taxonomy" data-taxonomy="<?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? esc_attr($name) : ''; ?>">
                                            <label for="wpbel-filter_form-post-attr-<?php echo esc_attr($name); ?>"><?php echo esc_html($taxonomy['label']); ?></label>
                                            <select <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-filter_form-post-attr-operator-' . esc_attr($name) . '"' : ''; ?> title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" data-field="operator" <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                                <option value="or"><?php esc_html_e('OR', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                                <option value="and"><?php esc_html_e('AND', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                                <option value="not_in"><?php esc_html_e('NOT IN', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                            </select>
                                            <select class="wpbel-select2" data-field="value" <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-filter_form-post-attr-' . esc_attr($name) . '"' : ''; ?> multiple <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                                <?php if (!empty($taxonomy['terms'])) : ?>
                                                    <?php foreach ($taxonomy['terms'] as $value_item) : ?>
                                                        <option value="<?php echo esc_attr($value_item->slug); ?>"><?php echo esc_html($value_item->name); ?></option>
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
                            <div class="wpbel-tab-content-item" data-content="bulk-edit-filter-date-type">
                                <div class="wpbel-form-group" data-name="post_status">
                                    <label><?php esc_html_e('Post Status', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select class="wpbel-input-md" disabled="disabled">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="comment_status">
                                    <label for="wpbel-filter-form-comment-status"><?php esc_html_e('Comment Status', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select class="wpbel-input-md" disabled="disabled">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="ping_status">
                                    <label for="wpbel-filter-form-ping-status"><?php esc_html_e('Allow Pingback', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select class="wpbel-input-md" disabled="disabled">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="sticky">
                                    <label for="wpbel-filter-form-post-sticky"><?php esc_html_e('Sticky', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <select class="wpbel-input-md" disabled="disabled">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                    </select>
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="date_published">
                                    <label><?php esc_html_e('Date Published', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Published From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Published To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="date_published_gmt">
                                    <label><?php esc_html_e('Date Published GMT', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Published GMT From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Published GMT To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="date_modified">
                                    <label><?php esc_html_e('Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Modified From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Modified To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="date_modified_gmt">
                                    <label><?php esc_html_e('Date Modified GMT', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Modified From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Modified To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                                <div class="wpbel-form-group" data-name="menu_order">
                                    <label><?php esc_html_e('Menu Order', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></label>
                                    <input type="number" class="wpbel-input-ft" placeholder="<?php esc_html_e('Menu Order From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <input type="number" class="wpbel-input-ft" placeholder="<?php esc_html_e('Menu Order To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                </div>
                            </div>
                            <div class="wpbel-tab-content-item" data-content="bulk-edit-filter-custom-fields">
                                <?php if (!empty($meta_fields)) : ?>
                                    <?php foreach ($meta_fields as $custom_field) : ?>
                                        <div class="wpbel-form-group">
                                            <label><?php echo esc_html($custom_field['title']); ?></label>
                                            <?php if (in_array($custom_field['main_type'], wpbel\classes\repositories\Meta_Field::get_fields_name_have_operator()) || ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::STRING_TYPE)) : ?>
                                                <select title="<?php esc_html_e('Select Operator', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                                    <option value="like"><?php esc_html_e('Like', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                                </select>
                                                <input type="text" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." disabled="disabled">
                                            <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::NUMBER) : ?>
                                                <input type="number" class="wpbel-input-md" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                                <input type="number" class="wpbel-input-md" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                            <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CHECKBOX) : ?>
                                                <select disabled="disabled">
                                                    <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
                                                </select>
                                            <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CALENDAR) : ?>
                                                <input type="text" class="wpbel-input-md wpbel-datepicker" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('From ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                                <input type="text" class="wpbel-input-md wpbel-datepicker" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('To ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" disabled="disabled">
                                            <?php endif; ?>
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
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
                <div class="wpbel-float-side-modal-footer">
                    <div class="wpbel-tab-footer-left">
                        <button type="button" id="wpbel-filter-form-get-posts" class="wpbel-button wpbel-button-blue wpbel-filter-form-action" data-search-action="pro_search">
                            <?php esc_html_e('Get Posts', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </button>
                        <button type="button" class="wpbel-button wpbel-button-white" id="wpbel-filter-form-reset">
                            <?php esc_html_e('Reset Filters', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </button>
                    </div>
                    <div class="wpbel-tab-footer-right">
                        <input type="text" name="save_filter" id="wpbel-filter-form-save-preset-name" placeholder="Filter Name ..." class="" title="Filter Name">
                        <button type="button" id="wpbel-filter-form-save-preset" class="wpbel-button wpbel-button-blue">
                            <?php esc_html_e('Save Profile', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                        </button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>