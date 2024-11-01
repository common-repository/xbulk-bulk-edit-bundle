<?php include_once "filter_form.php"; ?>
<div class="wccbef-wrap">
    <div class="wccbef-tab-middle-content wccbef-mt64">
        <?php include_once "top_navigation.php"; ?>
        <div class="wccbef-table" id="wccbef-items-table">
            <?php include_once WCCBEF_VIEWS_DIR . "data_table/items.php"; ?>
        </div>
        <div class="external-scroll_wrapper">
            <div class="external-scroll_x">
                <div class="scroll-element_outer">
                    <div class="scroll-element_size"></div>
                    <div class="scroll-element_track"></div>
                    <div class="scroll-bar"></div>
                </div>
            </div>
        </div>
        <div class="wccbef-items-pagination wccbef-mt-10">
            <?php include 'pagination.php'; ?>
        </div>
        <div class="wccbef-items-count wccbef-mt-10">

        </div>
    </div>
</div>
<input type="hidden" id="wccbef-last-modal-opened" value="">
<?php include_once "bulk_edit_form.php"; ?>
<?php include_once "columns_modals/products.php"; ?>
<?php include_once "columns_modals/categories.php"; ?>
<?php include_once "columns_modals/used_in.php"; ?>
<?php include_once "columns_modals/used_by.php"; ?>
<?php include_once WCCBEF_VIEWS_DIR . "modals/text_editor.php"; ?>
<?php include_once WCCBEF_VIEWS_DIR . "modals/numeric_calculator.php"; ?>
<?php include_once WCCBEF_VIEWS_DIR . "modals/duplicate_item.php"; ?>
<?php include_once WCCBEF_VIEWS_DIR . "modals/new_item.php"; ?>
<?php include_once WCCBEF_VIEWS_DIR . "modals/filter_profiles.php"; ?>
<?php include_once WCCBEF_VIEWS_DIR . "modals/column_profiles.php"; ?>