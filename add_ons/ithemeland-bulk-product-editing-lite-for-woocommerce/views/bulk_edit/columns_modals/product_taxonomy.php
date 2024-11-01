<?php

use wcbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($product) && !empty($column_key)) : ?>
    <div class="wcbel-modal" id="wcbel-modal-taxonomy-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>">
        <div class="wcbel-modal-container">
            <div class="wcbel-modal-box wcbel-modal-box-sm">
                <div class="wcbel-modal-content">
                    <div class="wcbel-modal-title">
                        <h2><?php esc_attr_e('Taxonomy Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span class="wcbel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                            <i class="wcbel-icon-x"></i>
                        </button>
                    </div>
                    <div class="wcbel-wrap">
                        <div class="wcbel-modal-top-search">
                            <input class="wcbel-search-in-list" title="Type for search" data-id="#wcbel-modal-taxonomy-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" type="text" placeholder="<?php esc_html_e('Type for search', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> ...">
                        </div>
                    </div>
                    <div class="wcbel-modal-body">
                        <div class="wcbel-wrap">
                            <div class="wcbel-product-items-list">
                                <?php
                                $checked = [];
                                $taxonomy_name = '';
                                if (isset($acf_fields[$column_key]['taxonomy'])) {
                                    if (isset($product[$column_key])) {
                                        $checked = !is_array(isset($product[$column_key])) ? unserialize($product[$column_key]) : $product[$column_key];
                                        $taxonomy_name = $acf_taxonomy_name;
                                    }
                                } else {
                                    $field = ($column_key == 'product_tag') ? 'slugs' : 'ids';
                                    $checked = wp_get_post_terms(intval($product['id']), esc_sql($column_key), ['fields' => $field]);
                                    $taxonomy_name = $column_key;
                                }
                                $checked = ($checked) ? $checked : [];
                                $taxonomy_items = wcbel\classes\helpers\Taxonomy::wcbel_product_taxonomy_list($taxonomy_name, $checked);

                                if (!empty($taxonomy_items)) {
                                    echo wp_kses($taxonomy_items, Sanitizer::allowed_html_tags());
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="wcbel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($product['id']); ?>" data-name="<?php echo esc_attr($column_data['name']); ?>" data-update-type="<?php echo esc_attr($column_data['update_type']); ?>" data-field="<?php echo esc_attr($column_key); ?>" data-field-type="<?php echo (!empty($field_type)) ? esc_attr($field_type) : ''; ?>" data-toggle="modal-close" class="wcbel-button wcbel-button-blue wcbel-inline-edit-taxonomy-save">
                            <?php esc_attr_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                        <button type="button" class="wcbel-button wcbel-button-white wcbel-inline-edit-add-new-taxonomy" data-closest-id="wcbel-modal-taxonomy-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" data-item-id="<?php echo esc_attr($product['id']); ?>" data-item-name="<?php echo esc_attr($product['title']); ?>" data-field="<?php echo esc_attr($taxonomy_name); ?>" data-toggle="modal" data-target="#wcbel-modal-new-product-taxonomy">
                            <?php esc_attr_e('Add New', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>