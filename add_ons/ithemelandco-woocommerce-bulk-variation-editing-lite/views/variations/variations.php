<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" data-confirm-message="Discard your changes. Are you sure?" id="iwbvel-float-side-modal-variations-bulk-edit">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <input type="hidden" id="filter-form-changed" value="">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Variations Bulk Edit', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-tabs">
                            <div class="iwbvel-tabs-navigation">
                                <nav class="iwbvel-tabs-navbar">
                                    <ul class="iwbvel-tabs-list" data-content-id="iwbvel-variations-bulk-edit-tabs">
                                        <li><button type="button" class="selected iwbvel-tab-item" data-content="add-variations"><?php esc_html_e('Add Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                        <li><button type="button" class="iwbvel-tab-item" data-content="attach-variations"><?php esc_html_e('Attaching', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                        <li><button type="button" class="iwbvel-tab-item" data-content="swap-variations"><?php esc_html_e('Swap', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                        <li><button type="button" class="iwbvel-tab-item" data-content="delete-variations"><?php esc_html_e('Delete Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                        <li><button type="button" class="iwbvel-tab-item iwbvel-variation-view-product-tab-title" data-content="view-product"><?php esc_html_e('View product', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="iwbvel-tabs-contents iwbvel-variations-bulk-edit" id="iwbvel-variations-bulk-edit-tabs" style="padding-top: 10px;">
                                <div class="selected iwbvel-tab-content-item iwbvel-variations-bulk-edit-add-variations" data-content="add-variations">
                                    <?php include_once "add_variations/main.php"; ?>
                                </div>

                                <div class="iwbvel-tab-content-item" data-content="swap-variations">
                                    <?php include_once "swap_variations/main.php"; ?>
                                </div>

                                <div class="iwbvel-tab-content-item" data-content="attach-variations">
                                    <?php include_once "attach_variations/main.php"; ?>
                                </div>

                                <div class="iwbvel-tab-content-item" data-content="delete-variations">
                                    <?php include_once "delete_variations/main.php"; ?>
                                </div>

                                <div class="iwbvel-tab-content-item" data-content="view-product">
                                    <?php include_once "view_product/main.php"; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>