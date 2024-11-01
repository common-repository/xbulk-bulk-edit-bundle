<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<input type="hidden" id="iwbvel-last-modal-opened" value="">

<div class='iwbvel-thumbnail-hover-box'></div>
</div>

<?php
include_once IWBVEL_VIEWS_DIR . "bulk_edit/bulk_edit_form.php";
include_once IWBVEL_VIEWS_DIR . "bulk_edit/filter_form.php";
include_once IWBVEL_VIEWS_DIR . "bulk_edit/columns_modals/select_products.php";
include_once IWBVEL_VIEWS_DIR . "bulk_edit/columns_modals/gallery.php";
include_once IWBVEL_VIEWS_DIR . "bulk_edit/columns_modals/new_product_taxonomy.php";
include_once IWBVEL_VIEWS_DIR . "bulk_edit/columns_modals/new_product_attribute.php";
include_once IWBVEL_VIEWS_DIR . "bulk_edit/columns_modals/select_files.php";
include_once IWBVEL_VIEWS_DIR . "variations/variations.php";
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/bulk-actions-modal.php";
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/individual-variation-modal.php";
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/new-attribute-term-modal.php";
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/regular-price-calculator-modal.php";
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/sale-price-calculator-modal.php";
include_once IWBVEL_VIEWS_DIR . "column_manager/main.php";
include_once IWBVEL_VIEWS_DIR . "import_export/main.php";
include_once IWBVEL_VIEWS_DIR . "meta_field/main.php";
include_once IWBVEL_VIEWS_DIR . "settings/main.php";
include_once IWBVEL_VIEWS_DIR . "history/main.php";
include_once IWBVEL_VIEWS_DIR . "modals/text_editor.php";
include_once IWBVEL_VIEWS_DIR . "modals/image.php";
include_once IWBVEL_VIEWS_DIR . "modals/file.php";
include_once IWBVEL_VIEWS_DIR . "modals/numeric_calculator.php";
include_once IWBVEL_VIEWS_DIR . "modals/duplicate_item.php";
include_once IWBVEL_VIEWS_DIR . "modals/new_item.php";
include_once IWBVEL_VIEWS_DIR . "modals/filter_profiles.php";
include_once IWBVEL_VIEWS_DIR . "modals/column_profiles.php";

$footer_files = apply_filters('iwbvel_footer_view_files', []);

if (!empty($footer_files) && is_array($footer_files)) {
    foreach ($footer_files as $file_item) {
        if (file_exists($file_item)) {
            include $file_item;
        }
    }
}
