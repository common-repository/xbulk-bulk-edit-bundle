<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-variation-bulk-edit">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Variation Bulk Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-tabs">
                            <div class="wcbel-tabs-navigation">
                                <nav class="wcbel-tabs-navbar">
                                    <ul class="wcbel-tabs-list" data-content-id="wcbel-variation-bulk-edit-tabs">
                                        <li>
                                            <button class="wcbel-tab-item selected" data-content="set-variation" type="button"><?php esc_html_e('Set Variation', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button>
                                        </li>
                                        <li><button class="wcbel-tab-item" data-content="delete-variation" type="button"><?php esc_html_e('Delete Variation', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button></li>
                                        <li><button class="wcbel-tab-item" data-content="attach-variation" type="button"><?php esc_html_e('Attach Variation', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></button></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wcbel-tabs-contents" id="wcbel-variation-bulk-edit-tabs">
                                <div class="selected wcbel-tab-content-item" data-content="set-variation">
                                    <div class="wcbel-alert wcbel-alert-danger" style="margin-top: 10px;">
                                        <span class="wcbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                        <a href="<?php echo esc_url(WCBEL_UPGRADE_URL); ?>"><?php echo esc_html(WCBEL_UPGRADE_TEXT); ?></a>
                                    </div>

                                    <div class="wcbel-variation-bulk-edit-left">
                                        <div class="wcbel-variation-bulk-edit-product-variations">
                                            <label for="wcbel-variation-bulk-edit-attributes"><?php esc_html_e('Product Attributes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></label>
                                            <select id="wcbel-variation-bulk-edit-attributes" class="wcbel-select2" disabled>
                                                <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></option>
                                            </select>
                                        </div>
                                        <div class="wcbel-variation-bulk-edit-attributes">
                                            <span class="wcbel-variation-bulk-edit-attributes-title"><?php esc_html_e('Select Attributes', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                            <div id="wcbel-variation-bulk-edit-attributes-added">

                                            </div>
                                        </div>
                                        <div class="wcbel-variation-bulk-edit-create">
                                            <div class="wcbel-variation-bulk-edit-create-mode">
                                                <div class="wcbel-pb20"><span><?php esc_html_e('How To Create Variations ?', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span></div>
                                                <label class="wcbel-variation-bulk-edit-create-mode">
                                                    <input type="radio" name="create_variation_mode" checked="checked" data-mode="all_combination">
                                                    <?php esc_html_e('All Combinations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                                </label>
                                                <label>
                                                    <input type="radio" name="create_variation_mode" data-mode="individual_combination">
                                                    <?php esc_html_e('Individual Combination', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                                </label>
                                            </div>
                                            <div id="wcbel-variation-bulk-edit-individual" style="display: none">
                                                <div class="wcbel-variation-bulk-edit-individual-items">

                                                </div>
                                                <button type="button" disabled="disabled" class="wcbel-button wcbel-button-blue wcbel-button-md wcbel-mt20">
                                                    <i class="lni lni-shuffle"></i>
                                                    <?php esc_html_e('Add', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                                </button>
                                            </div>
                                            <button type="button" disabled="disabled" class="wcbel-button wcbel-button-blue wcbel-button-md wcbel-mt20">
                                                <i class="lni lni-shuffle"></i>
                                                <?php esc_html_e('Generate', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="wcbel-variation-bulk-edit-right">
                                        <div class="wcbel-variation-bulk-edit-right-title">
                                            <span><?php esc_html_e('Current Variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
                                        </div>
                                        <div class="wcbel-variation-bulk-edit-current-variations">
                                            <div class="wcbel-variation-bulk-edit-current-items">

                                            </div>
                                            <div class="wcbel-variation-bulk-edit-right-footer">
                                                <button type="button" disabled="disabled" class="wcbel-button wcbel-button-md wcbel-button-blue">
                                                    <i class="lni lni-shuffle"></i>
                                                    <?php esc_html_e('Do Bulk Variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wcbel-tab-content-item" data-content="delete-variation">
                                    <div class="wcbel-alert wcbel-alert-danger">
                                        <span class="wcbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                        <a href="<?php echo esc_url(WCBEL_UPGRADE_URL); ?>"><?php echo esc_html(WCBEL_UPGRADE_TEXT); ?></a>
                                    </div>
                                </div>
                                <div class="wcbel-tab-content-item" data-content="attach-variation">
                                    <div class="wcbel-alert wcbel-alert-danger">
                                        <span class="wcbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                        <a href="<?php echo esc_url(WCBEL_UPGRADE_URL); ?>"><?php echo esc_html(WCBEL_UPGRADE_TEXT); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>