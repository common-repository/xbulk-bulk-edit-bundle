<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-alert iwbvel-alert-default" style="border-radius: 4px; font-weight: 600;">
    <span>
        <i class="iwbvel-icon-info1"></i>
        <?php esc_html_e('All of your changes will be saved automatically. e.g. Add Variations, Edit Variations.', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
    </span>
</div>

<div class="iwbvel-alert iwbvel-alert-warning iwbvel-variations-multiple-products-alert" style="display: none; border-radius: 4px; font-weight: 600;">
    <span>
        <i class="iwbvel-icon-info1"></i>
        <?php esc_html_e('Note) If you have selected several products, use the "Attach" tab to edit the attributes and variations, otherwise, all current attributes and variations will be replaced with new ones.', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
    </span>
</div>

<div class="iwbvel-variations-bulk-edit-add-variation-left">
    <div class="iwbvel-combine-attributes">
        <div class="iwbvel-combine-attributes-items-container">
            <ul class="iwbvel-combine-attributes-items"></ul>
            <span class="iwbvel-combine-attributes-items-description">Combine several attributes, example: "Size: all", "Color: red".</span>
        </div>
        <div class="iwbvel-combine-attributes-button">
            <button type="button" class="iwbvel-combine-attributes-generate-button"><?php esc_html_e('Generate', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> <i class="iwbvel-icon-chevron-down"></i></button>
            <div class="single-product">
                <div class="iwbvel-combine-attributes-sub-buttons">
                    <button type="button" class="iwbvel-variations-all-combinations-button" data-type="this-product" data-toggle="modal" data-target="#iwbvel-variations-all-variations-modal"><?php esc_html_e('All Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                    <button type="button" class="iwbvel-variations-individual-variation-button" data-type="this-product" data-toggle="modal" data-target="#iwbvel-variations-individual-variation-modal"><?php esc_html_e('Individual Variation', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                </div>
            </div>
            <div class="multiple-products">
                <div class="iwbvel-combine-attributes-sub-buttons">
                    <button type="button" class="iwbvel-variations-all-combinations-button" data-type="this-product" data-toggle="modal" data-target="#iwbvel-variations-all-variations-modal"><?php esc_html_e('All Variations (This product)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                    <button type="button" class="iwbvel-variations-individual-variation-button" data-type="this-product" data-toggle="modal" data-target="#iwbvel-variations-individual-variation-modal"><?php esc_html_e('Individual Variation (This product)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                    <button type="button" class="iwbvel-variations-all-combinations-button" data-type="all-products" data-toggle="modal" data-target="#iwbvel-variations-all-variations-modal"><?php esc_html_e('All Variations (All products)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                    <button type="button" class="iwbvel-variations-individual-variation-button" data-type="all-products" data-toggle="modal" data-target="#iwbvel-variations-individual-variation-modal"><?php esc_html_e('Individual Variation (All products)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="iwbvel-variations-bulk-actions">
        <div class="iwbvel-variations-bulk-actions-button" title="<?php esc_attr_e('Bulk Actions', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-edit-2"></i>
            <div class="iwbvel-variations-bulk-actions-sub-buttons">
                <button type="button" class="iwbvel-variations-bulk-actions-selected-button"><?php esc_html_e('Selected Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                <button type="button" class="iwbvel-variations-bulk-actions-all-button"><?php esc_html_e('All Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> <span class="iwbvel-variations-from-all-products-label"> <?php esc_html_e('(of Selected Products)', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span></button>
            </div>
        </div>
        <button type="button" class="iwbvel-variations-reload-table" title="<?php esc_attr_e('Reload Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-refresh-cw"></i></button>
        <button type="button" class="iwbvel-variations-table-delete-button" title="<?php esc_attr_e('Delete Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-trash-2"></i></button>
        <a href="#" target="_blank" class="iwbvel-variations-wc-product-edit-button" title="<?php esc_attr_e('Edit via woocommerce', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-edit"></i></a>
    </div>

    <div class="iwbvel-variations-product-selector">
        <button type="button" class="iwbvel-variations-product-selector-prev-button" disabled="disabled" title="<?php esc_attr_e('Prev', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-chevron-left"></i></button>
        <select id="iwbvel-variations-variable-products-selector" title="<?php esc_attr_e('Variable product', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"></select>
        <button type="button" class="iwbvel-variations-product-selector-next-button" title="<?php esc_attr_e('Next', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-chevron-right"></i></button>
    </div>

    <div class="iwbvel-variations-list">
        <?php include "variations-table.php"; ?>
        <div class="iwbvel-variations-pagination"></div>
        <div class="iwbvel-variations-table-loading">
            <p><img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'loading-2.gif'); ?>" width="24"></p>
        </div>
    </div>
</div>

<div class="iwbvel-variations-bulk-edit-add-variation-right">
    <div class="iwbvel-product-attributes-list">
        <div class="iwbvel-variations-attributes-top">
            <h3><?php esc_html_e('Attributes', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h3>
        </div>
        <?php if (!empty($attributes)) : ?>
            <?php foreach ($attributes as $attribute) : ?>
                <div class="iwbvel-product-attribute-item" data-name="<?php echo esc_attr($attribute->attribute_name); ?>" data-label="<?php echo esc_attr($attribute->attribute_label); ?>">
                    <div class="iwbvel-product-attribute-item-label">
                        <label for="iwbvel-attribute-<?php echo esc_attr($attribute->attribute_name); ?>" class="iwbvel-product-attribute-select-all-label">
                            <input type="checkbox" id="iwbvel-attribute-<?php echo esc_attr($attribute->attribute_name); ?>" class="iwbvel-product-attribute-select-all">
                            <i class="iwbvel-icon-check iwbvel-product-attribute-select-all-button" title="<?php echo esc_html__('Select All', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"></i>
                        </label>

                        <span><?php echo esc_html($attribute->attribute_label); ?></span>
                        <button class="iwbvel-product-attribute-add-new-button" data-toggle="modal" data-target="#iwbvel-variations-new-attribute-term-modal" type="button" title="<?php esc_attr_e('Add New Term', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>">
                            <span><?php esc_html_e('New', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                        </button>
                    </div>
                    <div class="iwbvel-product-attribute-item-middle-container">
                        <?php
                        $attribute_terms = get_terms([
                            'taxonomy' => "pa_{$attribute->attribute_name}",
                            'hide_empty' => false,
                        ]);

                        if (!empty($attribute_terms)) :
                        ?>
                            <input type="hidden" name="attributes[pa_<?php echo esc_attr($attribute->attribute_name); ?>][name]" value="pa_<?php echo esc_attr($attribute->attribute_name); ?>">
                            <ul class="iwbvel-product-attribute-item-terms">
                                <?php
                                foreach ($attribute_terms as $attribute_term) {
                                    include IWBVEL_VIEWS_DIR . "variations/add_variations/term-item.php";
                                };
                                ?>
                            </ul>
                        <?php endif; ?>
                        <div class="iwbvel-attribute-item-bottom-items disabled">
                            <label for="<?php echo esc_attr($attribute->attribute_name); ?>-used-for-variations">
                                <i class="iwbvel-icon-check-circle"></i>
                                <i class="iwbvel-icon-circle"></i>
                                <span><?php esc_html_e('Used for variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                <input type="checkbox" name="attributes[pa_<?php echo esc_attr($attribute->attribute_name); ?>][used_for_variations]" id="<?php echo esc_attr($attribute->attribute_name); ?>-used-for-variations" class="iwbvel-attribute-used-for-variations" value="yes">
                            </label>
                            <label for="<?php echo esc_attr($attribute->attribute_name); ?>-visible-on-the-product-page">
                                <i class="iwbvel-icon-check-circle"></i>
                                <i class="iwbvel-icon-circle"></i>
                                <span><?php esc_html_e('Visible on the product page', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></span>
                                <input type="checkbox" name="attributes[pa_<?php echo esc_attr($attribute->attribute_name); ?>][attribute_is_visible]" id="<?php echo esc_attr($attribute->attribute_name); ?>-visible-on-the-product-page" class="iwbvel-attribute-visible-on-the-product-page" value="yes">
                            </label>
                        </div>
                        <div class="iwbvel-variation-add-new-term-loading">
                            <p><img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'loading-2.gif'); ?>" width="26"></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="iwbvel-variation-bulk-edit-loading">
    <p><img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'loading-2.gif'); ?>" width="36"></p>
</div>