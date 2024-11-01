<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($product) && !empty($decoded_column_key) && !empty($column_key)) : ?>
    <div class="iwbvel-modal" id="iwbvel-modal-attribute-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>">
        <div class="iwbvel-modal-container">
            <div class="iwbvel-modal-box iwbvel-modal-box-sm">
                <div class="iwbvel-modal-content">
                    <div class="iwbvel-modal-title">
                        <h2><?php esc_attr_e('Attribute Edit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span class="iwbvel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                            <i class="iwbvel-icon-x"></i>
                        </button>
                    </div>
                    <div class="iwbvel-modal-top-search">
                        <div class="iwbvel-wrap">
                            <input class="iwbvel-search-in-list" title="<?php esc_attr_e('Type for search', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" data-id="#iwbvel-modal-attribute-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" type="text" placeholder="<?php esc_html_e('Type for search ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                        </div>
                    </div>
                    <div class="iwbvel-modal-body iwbvel-pt0">
                        <div class="iwbvel-modal-body-content">
                            <div class="iwbvel-wrap">
                                <div class="iwbvel-product-items-list">
                                    <div class="iwbvel-product-attribute-checkboxes">
                                        <label>
                                            <input type="hidden" class="is-visible-prev" value="">
                                            <input type="checkbox" class="is-visible" value="">
                                            <?php esc_html_e('Visible on the product page', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                        </label>
                                        <label>
                                            <input type="hidden" class="is-variation-prev" value="">
                                            <input type="checkbox" class="is-variation" value="">
                                            <?php esc_html_e('Used for variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                                        </label>
                                    </div>
                                    <ul>
                                        <?php $attribute_items = get_terms(['taxonomy' => $column_key, 'hide_empty' => false]); ?>
                                        <?php if (!empty($attribute_items)) : ?>
                                            <?php foreach ($attribute_items as $attribute_item) : ?>
                                                <?php
                                                $current_terms = wp_get_post_terms($product['id'], $column_key, ['fields' => 'ids']);
                                                if (is_array($current_terms) && in_array($attribute_item->term_id, $current_terms)) {
                                                    $checked = 'checked="checked"';
                                                } else {
                                                    $checked = '';
                                                }
                                                ?>
                                                <li>
                                                    <label>
                                                        <input type="checkbox" data-field="value" class="iwbvel-inline-edit-attribute-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" value="<?php echo esc_attr($attribute_item->term_id) ?>" <?php echo wp_kses($checked, iwbvel\classes\helpers\Sanitizer::allowed_html()); ?>>
                                                        <?php echo esc_html($attribute_item->name); ?>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="iwbvel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($product['id']); ?>" data-name="<?php echo esc_attr($column_data['name']); ?>" data-update-type="<?php echo esc_attr($column_data['update_type']); ?>" data-field="<?php echo esc_attr($column_key); ?>" data-toggle="modal-close" class="iwbvel-button iwbvel-button-blue iwbvel-inline-edit-attribute-save">
                            <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                        <button type="button" class="iwbvel-button iwbvel-button-white iwbvel-inline-edit-add-new-attribute" data-item-id="<?php echo esc_attr($product['id']); ?>" data-field="<?php echo esc_attr($column_key); ?>" data-item-name="<?php echo esc_attr($product['title']); ?>" data-toggle="modal" data-target="#iwbvel-modal-new-product-attribute">
                            <?php esc_attr_e('Add New', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>