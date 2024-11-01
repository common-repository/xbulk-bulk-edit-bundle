<?php $product = (!empty($product)) ? $product : $parent; ?>
<?php if (!empty($product) && class_exists('WC_Product') && $product instanceof WC_Product) : ?>
    <tr data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-product-type="<?php echo esc_attr($product->get_type()); ?>">
        <td data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-product-title="<?php echo esc_attr($product->get_name()); ?>" data-col-title="ID" <?php echo (wcbef\classes\helpers\Session::has('sticky_first_columns') && wcbef\classes\helpers\Session::get('sticky_first_columns') == 'yes') ? 'class="wcbef-td-sticky wcbef-td-sticky-id wcbef-white-bg"' : ''; ?>>
            <label class="wcbef-td70">
                <input type="checkbox" class="wcbef-check-item wcbef-product-id" data-product-type="<?php echo $product->get_type(); ?>" value="<?php echo $product->get_id(); ?>" title="Select Item">
                <?php echo esc_html($product->get_id()); ?>
                <a href="<?php echo admin_url("post.php?post=" . esc_attr($product->get_id()) . "&action=edit"); ?>" target="_blank" class="wcbef-ml5" title="Edit Product"><span class="lni lni-pencil-alt"></span></a>
                <a href="<?php echo get_the_permalink(intval(esc_attr($product->get_id()))); ?>" target="_blank" title="View on site" class="wcbef-product-view-icon wcbef-ml5"><span class="lni lni-display"></span></a>
            </label>
        </td>
        <td <?php echo (wcbef\classes\helpers\Session::has('sticky_first_columns') && wcbef\classes\helpers\Session::get('sticky_first_columns') == 'yes') ? 'class="wcbef-td-sticky wcbef-td-sticky-title wcbef-white-bg"' : ''; ?> data-product-id='<?php echo esc_attr($product->get_id()); ?>' data-product-title="<?php echo esc_attr($product->get_name()); ?>" data-col-title="Product Title" data-field="post_title" data-field-type="" data-content-type="text" data-action="inline-editable">
            <span data-action='inline-editable' class='wcbef-td160'><?php echo esc_html($product->get_name()); ?></span>
        </td>
        <?php echo \wcbef\classes\helpers\Columns::get_product_columns($product, $columns); ?>
    </tr>
<?php endif; ?>