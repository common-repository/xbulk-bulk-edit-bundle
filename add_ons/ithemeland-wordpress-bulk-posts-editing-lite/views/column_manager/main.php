<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-column-manager">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('Column Manager', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wpbel-wrap">
                        <div class="wpbel-tab-middle-content">
                            <div class="wpbel-alert wpbel-alert-default">
                                <span><?php esc_html_e('Mange columns of table. You can Create your customize presets and use them in column profile section.', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                            </div>
                            <div class="wpbel-column-manager-items">
                                <h3><?php esc_html_e('Column Profiles', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h3>
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbel-column-manager-delete-preset-form">
                                    <?php wp_nonce_field('wpbel_post_nonce'); ?>
                                    <input type="hidden" name="action" value="wpbel_column_manager_delete_preset">
                                    <input type="hidden" name="delete_key" id="wpbel_column_manager_delete_preset_key">
                                    <div class="wpbel-table-border-radius">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php esc_html_e('Profile Name', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                                    <th><?php esc_html_e('Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                                    <th><?php esc_html_e('Actions', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($column_manager_presets)) : ?>
                                                    <?php $i = 1 ?>
                                                    <?php foreach ($column_manager_presets as $key => $column_manager_preset) : ?>
                                                        <tr>
                                                            <td><?php echo intval($i); ?></td>
                                                            <td>
                                                                <span class="wpbel-history-name"><?php echo (isset($column_manager_preset['name'])) ? esc_html($column_manager_preset['name']) : ''; ?></span>
                                                            </td>
                                                            <td><?php echo (isset($column_manager_preset['date_modified'])) ? esc_html(gmdate('d M Y', strtotime($column_manager_preset['date_modified']))) : ''; ?></td>
                                                            <td>
                                                                <?php if (!in_array($key, \wpbel\classes\repositories\Column::get_default_columns_name())) : ?>
                                                                    <button type="button" class="wpbel-button wpbel-button-blue wpbel-column-manager-edit-field-btn" data-toggle="modal" data-target="#wpbel-modal-column-manager-edit-preset" value="<?php echo esc_attr($key); ?>" data-preset-name="<?php echo (isset($column_manager_preset['name'])) ? esc_attr($column_manager_preset['name']) : ''; ?>">
                                                                        <i class="wpbel-icon-pencil"></i>
                                                                        <?php esc_html_e('Edit', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                                                    </button>
                                                                    <button type="button" name="delete_preset" class="wpbel-button wpbel-button-red wpbel-column-manager-delete-preset" value="<?php echo esc_attr($key); ?>">
                                                                        <i class="wpbel-icon-trash-2"></i>
                                                                        <?php esc_html_e('Delete', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                                                    </button>
                                                                <?php else : ?>
                                                                    <i class="wpbel-icon-lock1"></i>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <div class="wpbel-column-manager-new-profile">
                                <h3 class="wpbel-column-manager-section-title"><?php esc_html_e('Create New Profile', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h3>
                                <div class="wpbel-column-manager-new-profile-left">
                                    <input type="text" title="<?php esc_html_e('Search Field', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" data-action="new" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" class="wpbel-column-manager-search-field">
                                    <div class="wpbel-column-manager-available-fields" data-action="new">
                                        <label class="wpbel-column-manager-check-all-fields-btn" data-action="new">
                                            <input type="checkbox" class="wpbel-column-manager-check-all-fields">
                                            <span><?php esc_html_e('Select All', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></span>
                                        </label>
                                        <ul>
                                            <?php if (!empty($column_items)) : ?>
                                                <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                    <li data-name="<?php echo esc_attr($column_key); ?>" data-added="false">
                                                        <label>
                                                            <input type="checkbox" data-type="field" data-name="<?php echo esc_attr($column_key); ?>" value="<?php echo esc_attr($column_field['label']); ?>">
                                                            <?php echo esc_html($column_field['label']); ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="wpbel-column-manager-new-profile-middle">
                                    <div class="wpbel-column-manager-middle-buttons">
                                        <div>
                                            <button type="button" data-action="new" data-type="checked" class="wpbel-button wpbel-button-lg wpbel-button-square-lg wpbel-button-blue wpbel-column-manager-add-field">
                                                <i class="wpbel-icon-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbel-column-manager-add-new-preset">
                                    <?php wp_nonce_field('wpbel_post_nonce'); ?>
                                    <input type="hidden" name="action" value="wpbel_column_manager_new_preset">
                                    <div class="wpbel-column-manager-new-profile-right">
                                        <div class="wpbel-column-manager-right-top">
                                            <input type="text" title="Profile Name" id="wpbel-column-manager-new-preset-name" name="preset_name" placeholder="Profile name ..." required>
                                            <button type="submit" name="save_preset" id="wpbel-column-manager-new-preset-btn" class="wpbel-button wpbel-button-lg wpbel-button-blue">
                                                <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'save.svg'); ?>" alt="">
                                                <?php esc_html_e('Save Preset', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
                                            </button>
                                        </div>
                                        <div class="wpbel-column-manager-added-fields-wrapper">
                                            <p class="wpbel-column-manager-empty-text"><?php esc_html_e('Please add your columns here', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></p>
                                            <div class="wpbel-column-manager-added-fields" data-action="new">
                                                <div class="items"></div>
                                                <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading.gif'); ?>" alt="" class="wpbel-box-loading wpbel-hide">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>