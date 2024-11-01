<table id="wcbef-products-list">
    <thead>
        <tr>
            <?php
            if ('id' == \wcbef\classes\helpers\Session::get('wcbef_sort_by')) {
                if (\wcbef\classes\helpers\Session::get('wcbef_sort_type') == 'asc') {
                    $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                } else {
                    $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                }
            } else {
                $img =  WCBEF_IMAGES_URL . "/sortable.png";
                $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
            }
            ?>
            <th class="wcbef-td70 <?php echo (wcbef\classes\helpers\Session::has('sticky_first_columns') && wcbef\classes\helpers\Session::get('sticky_first_columns') == 'yes') ? 'wcbef-td-sticky wcbef-td-sticky-id' : ''; ?>">
                <input type="checkbox" class="wcbef-check-item-main" title="<?php esc_html_e('Select All', 'woocommerce-bulk-edit-free'); ?>">
                <label data-column-name="id" class="wcbef-sortable-column"><?php esc_html_e('ID', 'woocommerce-bulk-edit-free'); ?><span class="wcbef-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></span>
            </th>
            <?php
            if ('title' == \wcbef\classes\helpers\Session::get('wcbef_sort_by')) {
                if (\wcbef\classes\helpers\Session::get('wcbef_sort_type') == 'asc') {
                    $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                } else {
                    $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                }
            } else {
                $img =  WCBEF_IMAGES_URL . "/sortable.png";
                $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
            }
            ?>
            <th data-column-name="title" class="wcbef-sortable-column wcbef-td120 <?php echo (wcbef\classes\helpers\Session::has('sticky_first_columns') && wcbef\classes\helpers\Session::get('sticky_first_columns') == 'yes') ? 'wcbef-td-sticky wcbef-td-sticky-title' : ''; ?>"><?php esc_html_e('Product Title', 'woocommerce-bulk-edit-free'); ?><span class="wcbef-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></th>
            <?php if (!empty($columns)) : ?>
                <?php foreach ($columns as $column_name => $column) : ?>
                    <?php switch ($column_name) {
                        case 'stock_quantity':
                            $title = "Set Stock quantity. If this is a variable product this <br> value will be used to control stock for all variations, unless you define stock <br>at variation level. <br> Note: if to set count of products in Stock quantity, Manage stock option automatically set as TRUE!";
                            break;
                        case 'stock_status':
                            $title = 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend. Note: Does Not work if the product Manage stock option is not activated!';
                            break;
                        case 'date_on_sale_from':
                            $title = 'The sale will start at 00:00:00 of "From" date and end at 23:59:59 of "To" date.';
                            break;
                        case 'tax_status':
                            $title = 'Define whether or not the entire product <br> is taxable, or just the cost of shipping it.';
                            break;
                        case 'tax_class':
                            $title = 'Choose a tax class for this product. Tax <br> classes are used to apply different tax rates specific <br> to certain types of product.';
                            break;
                        case 'sku':
                            $title = 'SKU refers to a Stock-keeping unit, a unique <br> identifier  for each distinct product and <br> service that can be purchased.';
                            break;
                        case 'backorders':
                            $title = 'If managing stock, this controls whether or not <br> backorders are allowed. If enabled, stock quantity can go below 0.';
                            break;
                        case 'shipping_class':
                            $title = 'Shipping classes are used by certain shipping <br> methods to group similar products.';
                            break;
                        case 'upsell_ids':
                            $title = 'Upsells are products which you recommend <br> instead of the currently viewed product, for example <br>, products that are more profitable or better quality or more expensive.';
                            break;
                        case 'cross_sell_ids':
                            $title = 'Cross-sells are products which you promote <br> in the cart, based on the current product.';
                            break;
                        case 'purchase_note':
                            $title = 'Enter an optional note to send the customer <br> after purchase.';
                            break;
                        case 'download_limit':
                            $title = 'Leave blank for unlimited re-downloads.';
                            break;
                        case 'download_expiry':
                            $title = 'Enter the number of days before a download <br> link expires, or leave blank.';
                            break;
                        case 'sold_individually':
                            $title = 'Enable this to only allow one of this <br> item to be bought in a single order';
                            break;
                        case 'product_url':
                            $title = 'Enter the external URL to the product.';
                            break;
                        case 'button_text':
                            $title = 'This text will be shown on the button <br> linking to the external product.';
                            break;
                        case 'catalog_visibility':
                            $title = 'This setting determines which shop <br> pages products will be listed on';
                            break;
                        case 'virtual':
                            $title = 'Virtual products are intangible and are not shipped.';
                            break;
                        case 'downloadable':
                            $title = 'Downloadable products give access to a file upon purchase.';
                            break;
                        default:
                            $title = "";
                    }

                    $sortable_icon = '';
                    if (isset($column['sortable']) && $column['sortable'] === true) {
                        if ($column_name == \wcbef\classes\helpers\Session::get('wcbef_sort_by')) {
                            if (\wcbef\classes\helpers\Session::get('wcbef_sort_type') == 'asc') {
                                $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                            } else {
                                $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                            }
                        } else {
                            $img =  WCBEF_IMAGES_URL . "/sortable.png";
                            $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
                        }
                    }

                    ?>

                    <th data-column-name="<?php echo esc_attr($column_name); ?>" <?php echo (!empty($column['sortable'])) ? 'class="wcbef-sortable-column"' : ''; ?>><?php echo (strlen($column['title']) > 12) ? esc_html(mb_substr($column['title'], 0, 12)) . '.' : esc_html($column['title']); ?><?php echo (!empty($title)) ? "<span class='wcbef-column-title dashicons dashicons-info' title='" . esc_attr($title) . "'></span>" : "" ?> <span class="wcbef-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($products_loading)) : ?>
            <tr>
                <td colspan="8" class="wcbef-text-alert"><?php esc_html_e('Loading ...', 'woocommerce-bulk-edit-free'); ?></td>
            </tr>
        <?php elseif (!empty($products) && count($products->posts) > 0) :
            foreach ($products->posts as $post_id) {
                $product = wc_get_product(intval($post_id));
                if ($product->get_parent_id() == 0) {
                    include "product_item.php";
                    if ($product->get_type() == 'variable' && !empty($variations)) {
                        if (is_array($variations) && array_key_exists($product->get_id(), $variations)) {
                            $children = $variations[$product->get_id()];
                        } elseif ($variations = 'children' && !empty($product_children = $product->get_children())) {
                            $children = $product_children;
                        } else {
                            $children = false;
                        }
                        if (!empty($children) && is_array($children)) {
                            foreach ($children as $child) {
                                $product = wc_get_product(intval($child));
                                include "product_item.php";
                            }
                        }
                    }
                }
            } else : ?>
            <tr>
                <td colspan="8" class="wcbef-text-alert"><?php esc_html_e('No Data Available!', 'woocommerce-bulk-edit-free'); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th <?php echo (wcbef\classes\helpers\Session::has('sticky_first_columns') && wcbef\classes\helpers\Session::get('sticky_first_columns') == 'yes') ? 'class="wcbef-td-sticky wcbef-td-sticky-id"' : ''; ?>>ID</th>
            <th <?php echo (wcbef\classes\helpers\Session::has('sticky_first_columns') && wcbef\classes\helpers\Session::get('sticky_first_columns') == 'yes') ? 'class="wcbef-td-sticky wcbef-td-sticky-title"' : ''; ?>>Product Title</th>
            <?php if (!empty($columns)) : ?>
                <?php foreach ($columns as $column) : ?>
                    <th><?php echo (strlen($column['title']) > 12) ? esc_html(mb_substr($column['title'], 0, 12)) . '.' : esc_html($column['title']); ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    </tfoot>
</table>