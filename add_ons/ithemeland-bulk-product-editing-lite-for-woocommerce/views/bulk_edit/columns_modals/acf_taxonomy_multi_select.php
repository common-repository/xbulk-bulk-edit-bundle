<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($product) && !empty($column_key)) : ?>
    <div class="wcbel-modal" id="wcbel-modal-multi-select-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>">
        <div class="wcbel-modal-container">
            <div class="wcbel-modal-box wcbel-modal-box-sm">
                <div class="wcbel-modal-content">
                    <div class="wcbel-modal-title">
                        <h2><?php esc_attr_e('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?> - <span class="wcbel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="wcbel-modal-close" data-toggle="modal-close">
                            <i class="wcbel-icon-x"></i>
                        </button>
                    </div>
                    <div class="wcbel-modal-body">
                        <div class="wcbel-wrap">
                            <div class="wcbel-inline-multi-select">
                                <?php
                                if (isset($acf_fields[$column_key]['taxonomy'])) :
                                    if (isset($product[$column_key])) {
                                        $checked = !is_array(isset($product[$column_key])) ? unserialize($product[$column_key]) : $product[$column_key];
                                    }

                                    $options = get_terms([
                                        'taxonomy' => $acf_fields[$column_key]['taxonomy'],
                                        'hide_empty' => false,
                                        'fields' => 'id=>name'
                                    ]);
                                endif;
                                ?>
                                <select class="wcbel-select2 wcbel-w100p wcbel-modal-acf-taxonomy-multi-select-value" data-placeholder="<?php esc_html_e('Select ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" multiple>
                                    <?php
                                    if (!empty($options) && count($options)) :
                                        foreach ($options as $option_key => $option_value) :
                                    ?>
                                            <option value="<?php echo esc_attr($option_key); ?>" <?php echo (!empty($checked) && is_array($checked) && in_array($option_key, $checked)) ? 'selected' : ''; ?>><?php echo esc_html($option_value); ?></option>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="wcbel-modal-footer">
                        <button type="button" data-item-id="<?php echo intval($product['id']); ?>" data-name="<?php echo esc_attr($column_key); ?>" data-update-type="meta_field" data-content-type="multi_select" class="wcbel-button wcbel-button-blue wcbel-edit-action-with-button" data-toggle="modal-close">
                            <?php esc_html_e('Apply Changes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>