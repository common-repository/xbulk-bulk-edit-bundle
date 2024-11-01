<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php include_once "filter_form.php"; ?>
<div class="wpbel-wrap">
    <div class="wpbel-tab-middle-content wpbel-mt64">
        <?php include_once "top_navigation.php"; ?>
        <div class="wpbel-table" id="wpbel-items-table">
            <?php include_once WPBEL_VIEWS_DIR . "data_table/items.php"; ?>
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
        <div class="wpbel-items-pagination wpbel-mt-10">
            <?php include 'pagination.php'; ?>
        </div>
        <div class="wpbel-items-count wpbel-mt-10">

        </div>
    </div>
</div>
