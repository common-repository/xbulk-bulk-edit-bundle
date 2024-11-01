<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($product) && !empty($decoded_column_key) && !empty($column_key)) : ?>
    <div class="wcbel-modal" id="wcbel-modal-attribute-<?php echo esc_attr($column_key); ?>-<?php echo esc_html($product['id']); ?>">
        <div class="wcbel-modal-container">
            <div class="wcbel-modal-box wcbel-modal-box-sm">
                <div class="wcbel-modal-content">
                    <div class="wcbel-modal-title">
                        <h2><?php esc_attr_e('Attribute Edit', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?> - <span class="wcbel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                            <i class="wcbel-icon-x"></i>
                        </button>
                    </div>
                    <div class="wcbel-modal-top-search">
                        <div class="wcbel-wrap">
                            <input class="wcbel-search-in-list" title="<?php esc_attr_e('Type for search', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>" data-id="#wcbel-modal-attribute-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" type="text" placeholder="<?php esc_html_e('Type for search ...', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>">
                        </div>
                    </div>
                    <div class="wcbel-modal-body wcbel-pt0">
                        <div class="wcbel-modal-body-content">
                            <div class="wcbel-wrap">
                                <div class="wcbel-product-items-list">
                                    <div class="wcbel-product-attribute-checkboxes">
                                        <label>
                                            <input type="hidden" class="is-visible-prev" value="">
                                            <input type="checkbox" class="is-visible" value="">
                                            <?php esc_html_e('Visible on the product page', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>
                                        </label>
                                        <label>
                                            <input type="hidden" class="is-variation-prev" value="">
                                            <input type="checkbox" class="is-variation" value="">
                                            <?php esc_html_e('Used for variations', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>
                                        </label>
                                    </div>
                                    <ul>
                                        <?php $attribute_items = get_terms(['taxonomy' => $column_key, 'hide_empty' => false]); ?>
                                        <?php if (!empty($attribute_items)) : ?>
                                            <?php foreach ($attribute_items as $attribute_item) : ?>
                                                <?php
                                                $current_terms = wp_get_post_terms($product['id'], $column_key, ['fields' => 'ids']);
                                                ?>
                                                <li>
                                                    <label>
                                                        <input type="checkbox" data-field="value" class="wcbel-inline-edit-attribute-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" value="<?php echo esc_attr($attribute_item->term_id) ?>" <?php echo (is_array($current_terms) && in_array($attribute_item->term_id, $current_terms)) ? 'checked="checked"' : ""; ?>>
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
                    <div class="wcbel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($product['id']); ?>" data-name="<?php echo esc_attr($column_data['name']); ?>" data-update-type="<?php echo esc_attr($column_data['update_type']); ?>" data-field="<?php echo esc_attr($column_key); ?>" data-toggle="modal-close" class="wcbel-button wcbel-button-blue wcbel-inline-edit-attribute-save">
                            <?php esc_html_e('Apply Changes', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>
                        </button>
                        <button type="button" class="wcbel-button wcbel-button-white wcbel-inline-edit-add-new-attribute" data-item-id="<?php echo esc_attr($product['id']); ?>" data-field="<?php echo esc_attr($column_key); ?>" data-item-name="<?php echo esc_attr($product['title']); ?>" data-toggle="modal" data-target="#wcbel-modal-new-product-attribute">
                            <?php esc_attr_e('Add New', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>