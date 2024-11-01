<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($product) && !empty($column_key)) : ?>
    <div class="iwbvel-modal" id="iwbvel-modal-taxonomy-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>">
        <div class="iwbvel-modal-container">
            <div class="iwbvel-modal-box iwbvel-modal-box-sm">
                <div class="iwbvel-modal-content">
                    <div class="iwbvel-modal-title">
                        <h2><?php esc_attr_e('Taxonomy Edit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span class="iwbvel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                            <i class="iwbvel-icon-x"></i>
                        </button>
                    </div>
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-modal-top-search">
                            <input class="iwbvel-search-in-list" title="Type for search" data-id="#iwbvel-modal-taxonomy-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" type="text" placeholder="<?php esc_html_e('Type for search', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> ...">
                        </div>
                    </div>
                    <div class="iwbvel-modal-body">
                        <div class="iwbvel-wrap">
                            <div class="iwbvel-product-items-list">
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
                                $taxonomy_items = iwbvel\classes\helpers\Taxonomy::iwbvel_product_taxonomy_list($taxonomy_name, $checked);
                                ?>
                                <?php if (!empty($taxonomy_items)) : ?>
                                    <?php echo wp_kses($taxonomy_items, iwbvel\classes\helpers\Sanitizer::allowed_html()); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="iwbvel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($product['id']); ?>" data-name="<?php echo esc_attr($column_data['name']); ?>" data-update-type="<?php echo esc_attr($column_data['update_type']); ?>" data-field="<?php echo esc_attr($column_key); ?>" data-field-type="<?php echo (!empty($field_type)) ? esc_attr($field_type) : ''; ?>" data-toggle="modal-close" class="iwbvel-button iwbvel-button-blue iwbvel-inline-edit-taxonomy-save">
                            <?php esc_attr_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                        <button type="button" class="iwbvel-button iwbvel-button-white iwbvel-inline-edit-add-new-taxonomy" data-closest-id="iwbvel-modal-taxonomy-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>" data-item-id="<?php echo esc_attr($product['id']); ?>" data-item-name="<?php echo esc_attr($product['title']); ?>" data-field="<?php echo esc_attr($taxonomy_name); ?>" data-toggle="modal" data-target="#iwbvel-modal-new-product-taxonomy">
                            <?php esc_attr_e('Add New', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>