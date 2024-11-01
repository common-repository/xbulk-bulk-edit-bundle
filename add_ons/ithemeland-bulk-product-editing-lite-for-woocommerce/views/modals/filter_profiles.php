<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wcbel-float-side-modal" id="wcbel-float-side-modal-filter-profiles">
    <div class="wcbel-float-side-modal-container">
        <div class="wcbel-float-side-modal-box">
            <div class="wcbel-float-side-modal-content">
                <div class="wcbel-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Profiles', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></h2>
                    <button type="button" class="wcbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wcbel-icon-x"></i>
                    </button>
                </div>
                <div class="wcbel-float-side-modal-body">
                    <div class="wcbel-wrap">
                        <div class="wcbel-filter-profiles-items wcbel-pb30">
                            <div class="wcbel-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Profile Name', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                            <th><?php esc_html_e('Date Modified', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                            <th><?php esc_html_e('Use Always', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                            <th><?php esc_html_e('Actions', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($filters_preset)) : ?>
                                            <?php foreach ($filters_preset as $filter_item) : ?>
                                                <?php include "filter_profile_item.php"; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>