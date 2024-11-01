<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div id="wbebl-body">
    <div class="wbebl-wrap">
        <div class="wbebl-header">
            <h2>
                <img src="<?php echo esc_url(WBEBL_IMAGES_URL . "wbebl_icon_original_black.svg"); ?>" alt="">
                <span>Welcome to</span>
                <strong>X-Bulk Bundle</strong>

            </h2>
            <span class="wbebl-header-sub"><?php echo esc_html__("Version", 'xbulk-bulk-edit-bundle') . esc_html(WBEBL_VERSION); ?></span>
            <span class="wbebl-header-sub-icon">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                    <g>
                        <path d="M32,0C14.4,0,0,14.4,0,32s14.4,32,32,32s32-14.4,32-32S49.6,0,32,0z M32,58.7c-14.7,0-26.7-12-26.7-26.7S17.3,5.3,32,5.3s26.7,12,26.7,26.7S46.7,58.7,32,58.7z" />
                        <path d="M40.8,22.1l-12.3,12l-5.6-5.3c-1.1-1.1-2.7-1.1-3.7,0s-1.1,2.7,0,3.7l6.7,6.4c0.8,0.8,1.6,1.1,2.4,1.1c0.8,0,1.9-0.3,2.4-1.1l13.6-13.1c1.1-1.1,1.1-2.7,0-3.7C43.5,21.1,41.9,21.1,40.8,22.1z" />
                    </g>
                </svg>
                Activated
            </span>
        </div>
    </div>
    <div class="wbebl-dashboard-body">
        <div class="wbebl-wrap">
            <div class="wbebl-boxes">
                <div class="wbebl-box-3">
                    <div class="wbebl-box-image">
                        <img src="<?php echo esc_url(WBEBL_IMAGES_URL . "support.svg"); ?>" alt="">
                    </div>
                    <div class="wbebl-box-text">
                        <strong><?php esc_html_e('Need Some Help', 'xbulk-bulk-edit-bundle'); ?></strong>
                        <span>We would love to be of any assistance</span>
                    </div>
                    <div class="wbebl-box-footer">
                        <a href="https://support.ithemelandco.com" class="wbebl-btn-green"><?php esc_html_e('Send Ticket', 'xbulk-bulk-edit-bundle'); ?></a>
                    </div>
                </div>
                <div class="wbebl-box-3">
                    <div class="wbebl-box-image">
                        <img src="<?php echo esc_url(WBEBL_IMAGES_URL . "documentation.svg"); ?>" alt="">
                    </div>
                    <div class="wbebl-box-text">
                        <strong><?php esc_html_e('Documentation', 'xbulk-bulk-edit-bundle'); ?></strong>
                        <span>We would love to be of any assistance</span>
                    </div>
                    <div class="wbebl-box-footer">
                        <a href="https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/xbulk/documentation.pdf" class="wbebl-btn-orange"><?php esc_html_e('Start Reading', 'xbulk-bulk-edit-bundle'); ?></a>
                    </div>
                </div>
                <div class="wbebl-box-3">
                    <div class="wbebl-box-image">
                        <img src="<?php echo esc_url(WBEBL_IMAGES_URL . "subscription.svg"); ?>" alt="">
                    </div>
                    <div class="wbebl-box-text">
                        <strong><?php esc_html_e('Subscription', 'xbulk-bulk-edit-bundle'); ?></strong>
                        <span>We would love to be of any assistance</span>
                    </div>
                    <div class="wbebl-box-footer">
                        <a href="javascript:;" class="wbebl-btn-dark"><?php esc_html_e('Coming Soon', 'xbulk-bulk-edit-bundle'); ?></a>
                    </div>
                </div>
            </div>
            <div class="wbebl-dashboard-change-log">
                <div class="wbebl-dashboard-change-log-header">
                    <div class="wbebl-dashboard-change-log-header-left">
                        <strong><?php esc_html_e("Changelog", 'xbulk-bulk-edit-bundle') ?></strong>
                    </div>
                    <div class="wbebl-dashboard-change-log-header-right">
                        <span>Follow US </span>
                        <?php if (!empty($social_networks)) : ?>
                            <ul>
                                <?php foreach ($social_networks as $social_network) : ?>
                                    <li><a href="<?php echo esc_url($social_network['link']); ?>"><img src="<?php echo esc_url($social_network['icon']); ?>" alt=""></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="wbebl-dashboard-change-log-body">
                    <li>
                        <div class="wbebl-dashboard-log-title">
                            <strong>Version 1.0.0</strong>
                            <span>(2021.10.01)</span>
                        </div>
                        <ul>
                            <li>Released</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>