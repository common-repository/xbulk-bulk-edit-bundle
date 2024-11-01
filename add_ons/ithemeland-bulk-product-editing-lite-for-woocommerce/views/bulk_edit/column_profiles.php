<div class="wcbef-modal" id="wcbef-modal-column-profiles">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-lg">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Column Profiles', 'woocommerce-bulk-edit-free'); ?></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="wcbef_load_column_profile">
                    <div class="wcbef-modal-body">
                        <div class="wcbef-wrap">
                            <div class="wcbef-alert wcbef-alert-default">
                                <span><?php esc_html_e('You can load saved column profile presets through Column Manager. You can change the columns and save your changes too.', 'woocommerce-bulk-edit-free'); ?></span>
                            </div>
                            <div class="wcbef-column-profiles-choose">
                                <label for="wcbef-column-profiles-choose"><?php esc_html_e('Choose Preset', 'woocommerce-bulk-edit-free'); ?></label>
                                <select id="wcbef-column-profiles-choose" name="preset_key">
                                    <?php if (!empty($column_manager_presets)) : ?>
                                        <?php foreach ($column_manager_presets as $column_manager_preset) : ?>
                                            <?php if ($i == 0) {
                                                $first_key = $column_manager_preset['key'];
                                            } ?>
                                            <option value="<?php echo esc_attr($column_manager_preset['key']); ?>" <?php echo (\wcbef\classes\helpers\Session::get('wcbef_active_columns_key') == $column_manager_preset['key']) ? 'selected' : ''; ?>><?php echo esc_html($column_manager_preset['name']); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <label class="wcbef-column-profile-select-all">
                                    <input type="checkbox" id="wcbef-column-profile-select-all" data-profile-name="<?php echo esc_attr(\wcbef\classes\helpers\Session::get('wcbef_active_columns_key')); ?>">
                                    <span><?php esc_html_e('Select All', 'woocommerce-bulk-edit-free'); ?></span>
                                </label>
                            </div>
                            <div class="wcbef-column-profiles-fields">
                                <?php if (!empty($column_manager_presets)) : ?>
                                    <?php foreach ($column_manager_presets as $column_manager_preset) : ?>
                                        <?php if (!empty($column_fields)) : ?>
                                            <div class="wcbef-column-profile-fields" <?php echo (\wcbef\classes\helpers\Session::get('wcbef_active_columns_key') != $column_manager_preset['key']) ? 'style="display:none"' : ''; ?> data-content="<?php echo esc_attr($column_manager_preset['key']); ?>">
                                                <ul>
                                                    <?php foreach ($column_fields as $name => $column_field) : ?>
                                                        <li>
                                                            <label>
                                                                <input type="checkbox" name="columns_<?php echo esc_attr($column_manager_preset['key']); ?>[]" value="<?php echo esc_attr($name); ?>" <?php echo (!empty($column_manager_preset['checked']) && in_array($name, $column_manager_preset['checked'])) ? 'checked="checked"' : ''; ?>>
                                                                <?php echo esc_html($column_field['label']); ?>
                                                            </label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="wcbef-modal-footer">
                        <button type="submit" class="wcbef-button wcbef-button-blue wcbef-float-left" id="wcbef-column-profiles-apply" data-preset-key="<?php echo (!empty($first_key)) ? $first_key : ''; ?>">
                            <?php esc_html_e('Apply To Table', 'woocommerce-bulk-edit-free'); ?>
                        </button>
                        <div class="wcbef-column-profile-search wcbef-float-right">
                            <label for="wcbef-column-profile-search"><?php esc_html_e('Search', 'woocommerce-bulk-edit-free'); ?> </label>
                            <input type="text" id="wcbef-column-profile-search" placeholder="<?php esc_html_e('Search Column ...', 'woocommerce-bulk-edit-free'); ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>