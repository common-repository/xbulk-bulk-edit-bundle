<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<input type="hidden" id="wpbel-last-modal-opened" value="">

<div class='wpbel-thumbnail-hover-box'></div>
</div>

<?php
include_once WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/filter_form.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modals/new_post_taxonomy.php";
include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modals/select_post.php";
include_once WPBEL_VIEWS_DIR . "column_manager/main.php";
include_once WPBEL_VIEWS_DIR . "column_manager/edit_preset.php";
include_once WPBEL_VIEWS_DIR . "import_export/main.php";
include_once WPBEL_VIEWS_DIR . "meta_field/main.php";
include_once WPBEL_VIEWS_DIR . "settings/main.php";
include_once WPBEL_VIEWS_DIR . "modals/text_editor.php";
include_once WPBEL_VIEWS_DIR . "modals/numeric_calculator.php";
include_once WPBEL_VIEWS_DIR . "modals/duplicate_item.php";
include_once WPBEL_VIEWS_DIR . "modals/new_item.php";
include_once WPBEL_VIEWS_DIR . "modals/image.php";
include_once WPBEL_VIEWS_DIR . "modals/filter_profiles.php";
include_once WPBEL_VIEWS_DIR . "modals/column_profiles.php";
include_once WPBEL_VIEWS_DIR . "history/main.php";