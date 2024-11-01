<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!empty($product) && !empty($column_key)) :
?>
    <div class="iwbvel-modal" id="iwbvel-modal-multi-select-<?php echo esc_attr($column_key); ?>-<?php echo esc_attr($product['id']); ?>">
        <div class="iwbvel-modal-container">
            <div class="iwbvel-modal-box iwbvel-modal-box-sm">
                <div class="iwbvel-modal-content">
                    <div class="iwbvel-modal-title">
                        <h2><?php esc_attr_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> - <span class="iwbvel-modal-item-title"><?php echo esc_html($product['title']); ?></span></h2>
                        <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                            <i class="iwbvel-icon-x"></i>
                        </button>
                    </div>
                    <div class="iwbvel-modal-body">
                        <div class="iwbvel-wrap">
                            <div class="iwbvel-inline-multi-select">
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
                                <select class="iwbvel-select2 iwbvel-w100p iwbvel-modal-acf-taxonomy-multi-select-value" data-placeholder="<?php esc_html_e('Select ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" multiple>
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
                    <div class="iwbvel-modal-footer">
                        <button type="button" data-item-id="<?php echo intval($product['id']); ?>" data-name="<?php echo esc_attr($column_key); ?>" data-update-type="meta_field" data-content-type="multi_select" class="iwbvel-button iwbvel-button-blue iwbvel-edit-action-with-button" data-toggle="modal-close">
                            <?php esc_html_e('Apply Changes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>