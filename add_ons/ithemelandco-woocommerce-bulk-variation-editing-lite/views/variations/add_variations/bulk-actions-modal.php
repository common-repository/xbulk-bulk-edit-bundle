<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

use iwbvel\classes\helpers\Operator;

$edit_number_operator = Operator::edit_number();
$regular_price_operator = $edit_number_operator + Operator::edit_regular_price();
$sale_price_operator = $edit_number_operator + Operator::edit_sale_price();
$edit_text_operator = Operator::edit_text();

?>

<div class="iwbvel-modal iwbvel-modal-in-float-side" id="iwbvel-variations-bulk-actions-modal">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-lg">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Bulk actions', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-tabs">
                            <div class="iwbvel-tabs-navigation">
                                <nav class="iwbvel-tabs-navbar">
                                    <ul class="iwbvel-tabs-list" data-content-id="iwbvel-variations-bulk-actions">
                                        <li><button type="button" class="iwbvel-tab-item selected" data-content="general"><?php esc_html_e('General', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                        <li><button type="button" class="iwbvel-tab-item" data-content="shipping"><?php esc_html_e('Shipping', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                        <li><button type="button" class="iwbvel-tab-item" data-content="manage_stock"><?php esc_html_e('Manage stock', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                        <li><button type="button" class="iwbvel-tab-item" data-content="downloadable"><?php esc_html_e('Downloadable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="iwbvel-tabs-contents iwbvel-mt15" id="iwbvel-variations-bulk-actions" style="position: relative;">
                                <div class="iwbvel-tab-content-item selected" data-content="general">
                                    <?php include IWBVEL_VIEWS_DIR . "variations/add_variations/bulk_actions/general.php"; ?>
                                </div>
                                <div class="iwbvel-tab-content-item" data-content="manage_stock">
                                    <?php include IWBVEL_VIEWS_DIR . "variations/add_variations/bulk_actions/manage-stock.php"; ?>
                                </div>
                                <div class="iwbvel-tab-content-item" data-content="shipping">
                                    <?php include IWBVEL_VIEWS_DIR . "variations/add_variations/bulk_actions/shipping.php"; ?>
                                </div>
                                <div class="iwbvel-tab-content-item" data-content="downloadable">
                                    <?php include IWBVEL_VIEWS_DIR . "variations/add_variations/bulk_actions/downloadable.php"; ?>
                                </div>
                                <div class="iwbvel-variation-bulk-actions-loading">
                                    <p><img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'loading-2.gif'); ?>" width="36"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue iwbvel-variations-bulk-action-do-bulk-button" data-toggle="modal-close">
                        <?php esc_html_e('Do Bulk', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                    <button type="button" class="iwbvel-button iwbvel-button-gray" data-toggle="modal-close">
                        <?php esc_html_e('Cancel', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>