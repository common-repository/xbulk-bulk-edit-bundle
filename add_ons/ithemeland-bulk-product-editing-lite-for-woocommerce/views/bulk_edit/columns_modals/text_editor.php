<div class="wcbef-modal" id="wcbef-modal-text-editor">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-lg">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Content Edit', 'woocommerce-bulk-edit-free'); ?> - <span id="wcbef-modal-text-editor-product-title" class="wcbef-modal-product-title"></span></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <?php wp_editor("", 'wcbef-text-editor'); ?>
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" data-field="" data-product-id="" data-content-type="textarea" id="wcbef-text-editor-apply" class="wcbef-button wcbef-button-blue wcbef-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'woocommerce-bulk-edit-free'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>