<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbel-float-side-modal" id="wpbel-float-side-modal-filter-profiles">
    <div class="wpbel-float-side-modal-container">
        <div class="wpbel-float-side-modal-box">
            <div class="wpbel-float-side-modal-content">
                <div class="wpbel-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Profiles', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbel-icon-x"></i>
                    </button>
                </div>
                <div class="wpbel-float-side-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-filter-profiles-items wpbel-pb30">
                            <div class="wpbel-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Profile Name', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Date Modified', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Use Always', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Actions', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></th>
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