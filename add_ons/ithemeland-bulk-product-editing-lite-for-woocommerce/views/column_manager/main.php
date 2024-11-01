<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-column-manager">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Column Manager', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-alert wcbel-alert-default">
                            <span><?php esc_html_e('Mange columns of table. You can Create your customize presets and use them in column profile section.', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                        </div>
                        <div class="wcbel-column-manager-items">
                            <h3><?php esc_html_e('Column Profiles', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h3>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wcbel-column-manager-delete-preset-form">
                                <?php wp_nonce_field('wcbel_post_nonce'); ?>
                                <input type="hidden" name="action" value="wcbel_column_manager_delete_preset">
                                <input type="hidden" name="delete_key" id="wcbel_column_manager_delete_preset_key">
                                <div class="wcbel-table-border-radius">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php esc_html_e('Profile Name', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                                <th><?php esc_html_e('Date Modified', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                                <th><?php esc_html_e('Actions', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($column_manager_presets)) : ?>
                                                <?php $i = 1 ?>
                                                <?php foreach ($column_manager_presets as $key => $column_manager_preset) : ?>
                                                    <tr>
                                                        <td><?php echo esc_html($i); ?></td>
                                                        <td>
                                                            <span class="wcbel-history-name"><?php echo (isset($column_manager_preset['name'])) ? esc_html($column_manager_preset['name']) : ''; ?></span>
                                                        </td>
                                                        <td><?php echo (isset($column_manager_preset['date_modified'])) ? esc_html(gmdate('d M Y', strtotime($column_manager_preset['date_modified']))) : ''; ?></td>
                                                        <td>
                                                            <?php if (!in_array($key, \wcbel\classes\repositories\Column::get_default_columns_name())) : ?>
                                                                <button type="button" class="wcbel-button wcbel-button-blue wcbel-column-manager-edit-field-btn" data-toggle="modal" data-target="#wcbel-modal-column-manager-edit-preset" value="<?php echo esc_attr($key); ?>" data-preset-name="<?php echo (isset($column_manager_preset['name'])) ? esc_attr($column_manager_preset['name']) : ''; ?>">
                                                                    <i class="wcbel-icon-pencil"></i>
                                                                    <?php esc_html_e('Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                                                </button>
                                                                <button type="button" name="delete_preset" class="wcbel-button wcbel-button-red wcbel-column-manager-delete-preset" value="<?php echo esc_attr($key); ?>">
                                                                    <i class="wcbel-icon-trash-2"></i>
                                                                    <?php esc_html_e('Delete', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                                                </button>
                                                            <?php else : ?>
                                                                <i class="wcbel-icon-lock1"></i>
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
                        <div class="wcbel-column-manager-new-profile">
                            <h3 class="wcbel-column-manager-section-title"><?php esc_html_e('Create New Profile', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h3>
                            <div class="wcbel-column-manager-new-profile-left">
                                <input type="text" title="<?php esc_html_e('Search Field', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-action="new" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" class="wcbel-column-manager-search-field">
                                <div class="wcbel-column-manager-available-fields" data-action="new">
                                    <label class="wcbel-column-manager-check-all-fields-btn" data-action="new">
                                        <input type="checkbox" class="wcbel-column-manager-check-all-fields">
                                        <span><?php esc_html_e('Select All', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
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
                            <div class="wcbel-column-manager-new-profile-middle">
                                <div class="wcbel-column-manager-middle-buttons">
                                    <div>
                                        <button type="button" data-action="new" data-type="checked" class="wcbel-button wcbel-button-lg wcbel-button-square-lg wcbel-button-blue wcbel-column-manager-add-field">
                                            <i class="wcbel-icon-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wcbel-column-manager-add-new-preset">
                                <?php wp_nonce_field('wcbel_post_nonce'); ?>
                                <input type="hidden" name="action" value="wcbel_column_manager_new_preset">
                                <div class="wcbel-column-manager-new-profile-right">
                                    <div class="wcbel-column-manager-right-top">
                                        <input type="text" title="Profile Name" id="wcbel-column-manager-new-preset-name" name="preset_name" placeholder="Profile name ..." required>
                                        <button type="submit" name="save_preset" id="wcbel-column-manager-new-preset-btn" class="wcbel-button wcbel-button-lg wcbel-button-blue">
                                            <img src="<?php echo esc_url(WCBEL_IMAGES_URL . 'save.svg'); ?>" alt="">
                                            <?php esc_html_e('Save Preset', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                        </button>
                                    </div>
                                    <div class="wcbel-column-manager-added-fields-wrapper">
                                        <p class="wcbel-column-manager-empty-text"><?php esc_html_e('Please add your columns here', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></p>
                                        <div class="wcbel-column-manager-added-fields" data-action="new">
                                            <div class="items"></div>
                                            <img src="<?php echo esc_url(WCBEL_IMAGES_URL . 'loading.gif'); ?>" alt="" class="wcbel-box-loading wcbel-hide">
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