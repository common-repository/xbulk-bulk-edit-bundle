<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-float-side-modal" id="iwbvel-float-side-modal-filter-profiles">
    <div class="iwbvel-float-side-modal-container">
        <div class="iwbvel-float-side-modal-box">
            <div class="iwbvel-float-side-modal-content">
                <div class="iwbvel-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Profiles', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></h2>
                    <button type="button" class="iwbvel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="iwbvel-icon-x"></i>
                    </button>
                </div>
                <div class="iwbvel-float-side-modal-body">
                    <div class="iwbvel-wrap">
                        <div class="iwbvel-filter-profiles-items iwbvel-pb30">
                            <div class="iwbvel-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Profile Name', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Date Modified', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Use Always', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Actions', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
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