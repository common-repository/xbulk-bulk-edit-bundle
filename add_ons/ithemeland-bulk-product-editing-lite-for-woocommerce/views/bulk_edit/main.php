<?php include_once "filter_form.php"; ?>
<div class="wcbef-wrap">
    <div class="wcbef-tab-middle-content wcbef-mt64">
        <?php include_once "top_navigation.php"; ?>
        <div class="wcbef-table" id="wcbef-items-table"></div>
        <div class="external-scroll_wrapper">
            <div class="external-scroll_x">
                <div class="scroll-element_outer">
                    <div class="scroll-element_size"></div>
                    <div class="scroll-element_track"></div>
                    <div class="scroll-bar"></div>
                </div>
            </div>
        </div>
        <div class="wcbef-items-pagination wcbef-mt-10"></div>
        <div class="wcbef-items-count wcbef-mt-10"></div>
    </div>
</div>
<input type="hidden" id="wcbef-last-modal-opened" value="">

<?php
include_once WCBEF_VIEWS_DIR . "bulk_edit/bulk_edit_form.php";
include_once WCBEF_VIEWS_DIR . "bulk_edit/variations.php";
include_once WCBEF_VIEWS_DIR . "bulk_edit/columns_modals/select_products.php";
include_once WCBEF_VIEWS_DIR . "bulk_edit/columns_modals/gallery.php";
include_once WCBEF_VIEWS_DIR . "bulk_edit/columns_modals/new_product_taxonomy.php";
include_once WCBEF_VIEWS_DIR . "bulk_edit/columns_modals/new_product_attribute.php";
include_once WCBEF_VIEWS_DIR . "bulk_edit/columns_modals/select_files.php";
include_once WCBEF_VIEWS_DIR . "modals/text_editor.php";
include_once WCBEF_VIEWS_DIR . "modals/image.php";
include_once WCBEF_VIEWS_DIR . "modals/file.php";
include_once WCBEF_VIEWS_DIR . "modals/numeric_calculator.php";
include_once WCBEF_VIEWS_DIR . "modals/duplicate_item.php";
include_once WCBEF_VIEWS_DIR . "modals/new_item.php";
include_once WCBEF_VIEWS_DIR . "modals/filter_profiles.php";
include_once WCBEF_VIEWS_DIR . "modals/column_profiles.php";
