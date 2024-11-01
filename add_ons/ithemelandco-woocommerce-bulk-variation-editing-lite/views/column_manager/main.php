<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-column-manager">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Column Manager', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-alert iwbvel-alert-default">
                            <span><?php esc_html_e('Mange columns of table. You can Create your customize presets and use them in column profile section.', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                        </div>
                        <div class="iwbvel-column-manager-items">
                            <h3><?php esc_html_e('Column Profiles', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h3>
                            <div class="iwbvel-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php esc_html_e('Profile Name', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Date Modified', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Actions', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($column_manager_presets)) : ?>
                                            <?php $i = 1 ?>
                                            <?php foreach ($column_manager_presets as $key => $column_manager_preset) : ?>
                                                <tr>
                                                    <td><?php echo intval($i); ?></td>
                                                    <td>
                                                        <span class="iwbvel-history-name"><?php echo (isset($column_manager_preset['name'])) ? esc_html($column_manager_preset['name']) : ''; ?></span>
                                                    </td>
                                                    <td><?php echo (isset($column_manager_preset['date_modified'])) ? esc_html(gmdate('d M Y', strtotime($column_manager_preset['date_modified']))) : ''; ?></td>
                                                    <td>
                                                        <?php if (!in_array($key, \iwbvel\classes\repositories\Column::get_default_columns_name())) : ?>
                                                            <button type="button" class="iwbvel-button iwbvel-button-blue" disabled="disabled">
                                                                <i class="iwbvel-icon-pencil"></i>
                                                                <?php esc_html_e('Edit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                                            </button>
                                                            <button type="button" disabled="disabled" class="iwbvel-button iwbvel-button-red">
                                                                <i class="iwbvel-icon-trash-2"></i>
                                                                <?php esc_html_e('Delete', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                                            </button>
                                                        <?php else : ?>
                                                            <i class="iwbvel-icon-lock1"></i>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="iwbvel-column-manager-new-profile">
                            <h3 class="iwbvel-column-manager-section-title"><?php esc_html_e('Create New Profile', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h3>
                            <div class="iwbvel-column-manager-new-profile-left">
                                <input type="text" disabled="disabled" title="<?php esc_html_e('Search Field', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" placeholder="<?php esc_html_e('Search Field ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" class="iwbvel-column-manager-search-field">
                                <div class="iwbvel-column-manager-available-fields">
                                    <label class="iwbvel-column-manager-check-all-fields-btn">
                                        <input type="checkbox" class="iwbvel-column-manager-check-all-fields" disabled="disabled">
                                        <span><?php esc_html_e('Select All', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                    </label>
                                    <ul>
                                        <?php if (!empty($column_items)) : ?>
                                            <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                <li data-name="<?php echo esc_attr($column_key); ?>" data-added="false">
                                                    <label>
                                                        <input type="checkbox" disabled="disabled">
                                                        <?php echo esc_html($column_field['label']); ?>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="iwbvel-column-manager-new-profile-middle">
                                <div class="iwbvel-column-manager-middle-buttons">
                                    <div>
                                        <button type="button" disabled="disabled" class="iwbvel-button iwbvel-button-lg iwbvel-button-square-lg iwbvel-button-blue">
                                            <i class="iwbvel-icon-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="iwbvel-column-manager-new-profile-right">
                                <div class="iwbvel-column-manager-right-top">
                                    <input type="text" title="Profile Name" name="preset_name" placeholder="Profile name ..." disabled="disabled">
                                    <button type="submit" name="save_preset" class="iwbvel-button iwbvel-button-lg iwbvel-button-blue" disabled="disabled">
                                        <img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'save.svg'); ?>" alt="">
                                        <?php esc_html_e('Save Preset', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                    </button>
                                </div>
                                <div class="iwbvel-column-manager-added-fields-wrapper">
                                    <p class="iwbvel-column-manager-empty-text"><?php esc_html_e('Please add your columns here', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></p>
                                    <div class="iwbvel-column-manager-added-fields">
                                        <div class="items"></div>
                                        <img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'loading.gif'); ?>" alt="" class="iwbvel-box-loading iwbvel-hide">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>