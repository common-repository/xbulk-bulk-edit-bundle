<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<li>
    <button type="button" title="<?php esc_attr_e('Filter', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-filter">
        <i class="wcbel-icon-filter1"></i>
    </button>
</li>

<li class="wcbel-quick-filter">
    <button type="button" title="<?php esc_attr_e('Quick Search', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        <i class="wcbel-icon-search1"></i>
    </button>
    <?php include_once WCBEL_VIEWS_DIR . "bulk_edit/filter_bar.php"; ?>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Bulk Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-bulk-edit">
        <i class="wcbel-icon-edit"></i>
    </button>
</li>

<li class="wcbel-has-sub-tab">
    <button type="button" title="<?php esc_attr_e('Variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        <i class="wcbel-icon-tree"></i>
    </button>

    <ul class="wcbel-sub-tab">
        <li>
            <button type="button" class="wcbel-bulk-edit-variations" data-toggle="float-side-modal-after-confirm" data-target="#wcbel-float-side-modal-variation-bulk-edit">
                <i class="wcbel-icon-tree"></i>
                <span><?php esc_html_e('Manage Variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
        <li>
            <button type="button" class="wcbel-bulk-edit-show-variations-button">
                <i class="wcbel-icon-eye1"></i>
                <span><?php esc_html_e('Show Variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
            <input type="hidden" id="wcbel-bulk-edit-show-variations">
        </li>
        <li>
            <button type="button" class="wcbel-bulk-edit-select-all-variations-button">
                <i class="wcbel-icon-check-square"></i>
                <span><?php esc_html_e('Select All Variations', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
            <input type="hidden" id="wcbel-bulk-edit-select-all-variations">
        </li>
    </ul>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Bind Edit', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" class="wcbel-bind-edit-switch">
        <span class="default-icon">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 201.000000 199.000000" preserveAspectRatio="xMidYMid meet">
                <g transform="translate(0.000000,199.000000) scale(0.100000,-0.100000)" fill="#444" stroke="none">
                    <path d="M1420 1970 c-74 -13 -156 -49 -217 -95 -32 -24 -157 -144 -279 -267
                        -190 -193 -225 -233 -256 -295 -80 -161 -72 -347 23 -494 64 -100 129 -134
                        194 -103 70 33 86 115 34 180 -74 92 -93 172 -64 270 16 57 26 68 253 297 244
                        245 278 271 363 283 117 16 249 -71 283 -185 20 -68 20 -94 0 -161 -13 -45
                        -31 -69 -121 -161 l-105 -108 21 -27 c24 -30 51 -113 51 -156 0 -57 49 -27
                        187 115 117 121 151 170 182 258 80 235 -27 494 -249 602 -102 50 -198 65
                        -300 47z"></path>
                    <path d="M1132 1264 c-68 -35 -82 -116 -31 -180 74 -92 93 -172 64 -270 -16
                        -57 -26 -68 -253 -297 -244 -245 -278 -271 -363 -283 -117 -16 -249 71 -283
                        185 -20 68 -20 94 0 161 13 45 31 69 121 161 l105 108 -21 27 c-24 30 -51 113
                        -51 156 0 57 -49 27 -187 -115 -117 -121 -151 -170 -182 -258 -43 -127 -35
                        -254 26 -379 135 -277 493 -362 740 -175 32 24 157 144 279 267 196 198 225
                        231 257 298 84 171 68 372 -40 514 -67 87 -120 110 -181 80z"></path>
                </g>
            </svg>
        </span>
        <span class="active-icon">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 229.000000 229.000000" preserveAspectRatio="xMidYMid meet">
                <g transform="translate(0.000000,229.000000) scale(0.100000,-0.100000)" fill="#28a745">
                    <path d="M1515 2245 c-27 -8 -83 -31 -125 -51 -68 -33 -98 -59 -326 -288 -229
                        -229 -254 -258 -287 -326 -49 -100 -67 -177 -67 -280 0 -103 18 -180 66 -279
                        41 -84 140 -191 217 -236 l49 -28 55 54 c62 61 92 116 93 171 0 30 -5 41 -27
                        55 -50 30 -94 77 -121 128 -23 43 -27 63 -27 135 0 124 15 146 274 403 244
                        241 260 252 386 252 164 -1 288 -128 288 -295 0 -107 -29 -158 -181 -313
                        l-128 -132 19 -70 c14 -55 17 -97 15 -192 -3 -107 -2 -120 12 -113 8 4 114
                        109 235 232 225 228 265 280 307 394 30 82 37 253 13 344 -53 206 -227 382
                        -431 434 -75 19 -239 20 -309 1z"></path>
                    <path d="M1198 1463 c-90 -92 -116 -185 -60 -220 49 -30 93 -77 120 -128 23
                        -43 27 -63 27 -135 0 -124 -15 -147 -269 -397 -244 -242 -268 -258 -386 -258
                        -174 0 -294 121 -294 295 0 114 20 150 175 306 l131 132 -17 73 c-18 77 -24
                        230 -11 287 4 17 4 32 -1 32 -5 0 -112 -105 -239 -232 -299 -304 -334 -362
                        -342 -573 -7 -187 45 -322 173 -451 115 -115 258 -174 425 -174 103 0 180 18
                        280 66 69 34 97 58 326 288 230 229 254 257 288 326 48 100 66 177 66 280 0
                        203 -98 390 -261 499 -34 23 -64 41 -68 41 -4 0 -32 -26 -63 -57z"></path>
                </g>
            </svg>
        </span>
    </button>

    <input type="checkbox" style="display: none;" id="wcbel-bind-edit">
</li>

<li>
    <button type="button" class="wcbel-top-nav-duplicate-button" title="<?php esc_attr_e('Duplicate', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        <i class="wcbel-icon-copy"></i>
    </button>
</li>

<li>
    <button type="button" class="wcbel-reload-table" title="<?php esc_attr_e('Reload', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        <i class="wcbel-icon-refresh-cw"></i>
    </button>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('New', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" class="wcbel-new-item-button" data-toggle="modal" data-target="#wcbel-modal-new-item">
        <i class="wcbel-icon-plus-circle" style="width: 19px; height: 19px; font-size: 19px;"></i>
    </button>
</li>

<li class="wcbel-has-sub-tab">
    <button type="button" class="wcbel-tab-icon-red" title="<?php esc_attr_e('Delete', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        <i class="wcbel-icon-trash-2"></i>
    </button>

    <ul class="wcbel-sub-tab">
        <li>
            <button type="button" class="wcbel-bulk-edit-delete-action" data-delete-type="trash">
                <i class="wcbel-icon-trash-2"></i>
                <span><?php esc_html_e('Move to trash', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
        <li>
            <button type="button" class="wcbel-bulk-edit-delete-action" data-delete-type="permanently">
                <i class="wcbel-icon-delete"></i>
                <span><?php esc_html_e('Permanently', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
        <li>
            <button type="button" class="wcbel-bulk-edit-delete-action" data-delete-type="all">
                <i class="wcbel-icon-x-square"></i>
                <span><?php esc_html_e('Delete All Products', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
    </ul>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Column Profile', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-column-profiles">
        <i class="wcbel-icon-table2"></i>
    </button>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Filter Profile', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-filter-profiles">
        <i class="wcbel-icon-insert-template"></i>
    </button>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Column Manager', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-column-manager">
        <i class="wcbel-icon-columns"></i>
    </button>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Meta fields', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-meta-fields">
        <i class="wcbel-icon-list"></i>
    </button>
</li>

<li class="wcbel-has-sub-tab">
    <button type="button" class="wcbel-tab-item" title="<?php esc_attr_e('History', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        <i class="wcbel-icon-clock"></i>
    </button>

    <ul class="wcbel-sub-tab">
        <li>
            <button type="button" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-history">
                <i class="wcbel-icon-clock"></i>
                <span><?php esc_html_e('History', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
        <li>
            <button type="button" id="wcbel-bulk-edit-undo">
                <i class="wcbel-icon-rotate-ccw"></i>
                <span><?php esc_html_e('Undo', 'ithemeland-bulk-product-editing-lite-for-woocommerce') ?></span>
            </button>
        </li>
        <li>
            <button type="button" id="wcbel-bulk-edit-redo">
                <i class="wcbel-icon-rotate-cw"></i>
                <span><?php esc_html_e('Redo', 'ithemeland-bulk-product-editing-lite-for-woocommerce') ?></span>
            </button>
        </li>
    </ul>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Import/Export', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-import-export">
        <i class="wcbel-icon-repeat"></i>
    </button>
</li>

<li>
    <button type="button" title="<?php esc_attr_e('Settings', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>" data-toggle="float-side-modal" data-target="#wcbel-float-side-modal-settings">
        <i class="wcbel-icon-settings"></i>
    </button>
</li>

<li style="display: none;">
    <button type="button" class="wcbel-tab-icon-red wcbel-reset-filter-form" title="<?php esc_attr_e('Reset Filter', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>">
        <i class="wcbel-icon-ungroup"></i>
    </button>
</li>

<li style="display: none;" class="wcbel-has-sub-tab">
    <button type="button" class="wcbel-tab-icon-red wcbel-trash-options">
        <i class="wcbel-icon-trash"></i>
    </button>

    <ul class="wcbel-sub-tab">
        <li>
            <button type="button" class="wcbel-trash-option-restore-selected-items">
                <i class="wcbel-icon-rotate-ccw"></i>
                <span><?php esc_html_e('Restore Selected Items', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
        <li>
            <button type="button" class="wcbel-trash-option-restore-all">
                <i class="wcbel-icon-rotate-ccw"></i>
                <span><?php esc_html_e('Restore All', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
        <li>
            <button type="button" class="wcbel-trash-option-delete-selected-items">
                <i class="wcbel-icon-x-square"></i>
                <span><?php esc_html_e('Delete Permanently', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
        <li>
            <button type="button" class="wcbel-trash-option-delete-all">
                <i class="wcbel-icon-trash-2"></i>
                <span><?php esc_html_e('Empty Trash', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></span>
            </button>
        </li>
    </ul>
</li>