<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-modal iwbvel-modal-in-float-side" id="iwbvel-variations-all-variations-modal">
    <div class="iwbvel-modal-container">
        <div class="iwbvel-modal-box iwbvel-modal-box-sm-iv">
            <div class="iwbvel-modal-content">
                <div class="iwbvel-modal-title">
                    <h2><?php esc_html_e('Possible combinations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-modal-close" data-toggle="modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-modal-body-content">
                            <div class="iwbvel-alert iwbvel-alert-danger">
                                <span class="iwbvel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                <a href="<?php echo esc_url(IWBVEL_UPGRADE_URL); ?>"><?php echo esc_html(IWBVEL_UPGRADE_TEXT); ?></a>
                            </div>

                            <div class="iwbvel-variations-individual-variation-loading">
                                <p><img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'loading-2.gif'); ?>" width="34"></p>
                            </div>
                            <div id="iwbvel-variations-all-variations-items"></div>
                        </div>
                    </div>
                </div>
                <div class="iwbvel-modal-footer">
                    <button type="button" class="iwbvel-button iwbvel-button-blue" disabled="disabled">
                        <?php esc_html_e('Generate Variations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                    <button type="button" class="iwbvel-button iwbvel-button-gray" data-toggle="modal-close">
                        <?php esc_html_e('Cancel', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>