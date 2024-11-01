<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

use wbebl\classes\helpers\Sanitizer;

?>

<div id="wbebl-body">
    <div class="wbebl-wrap">
        <div class="wbebl-header">
            <h2>
                <img src="<?php echo esc_url(WBEBL_IMAGES_URL . "wbebl_icon_original_black.svg"); ?>" alt="">
                <?php esc_html_e("Available Add-Ons", 'xbulk-bulk-edit-bundle'); ?>
            </h2>
            <span class="wbebl-header-sub-icon">
                <?php
                echo (!empty($sub_systems) && is_array($sub_systems)) ? '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve"><g><path d="M32,0C14.4,0,0,14.4,0,32s14.4,32,32,32s32-14.4,32-32S49.6,0,32,0z M32,58.7c-14.7,0-26.7-12-26.7-26.7S17.3,5.3,32,5.3s26.7,12,26.7,26.7S46.7,58.7,32,58.7z" /><path d="M40.8,22.1l-12.3,12l-5.6-5.3c-1.1-1.1-2.7-1.1-3.7,0s-1.1,2.7,0,3.7l6.7,6.4c0.8,0.8,1.6,1.1,2.4,1.1c0.8,0,1.9-0.3,2.4-1.1l13.6-13.1c1.1-1.1,1.1-2.7,0-3.7C43.5,21.1,41.9,21.1,40.8,22.1z" /></g></svg> ' . count($sub_systems) . ' Add-Ons Available' : '';
                ?>
            </span>
        </div>
    </div>
    <div class="wbebl-add-ons-body">
        <div class="wbebl-wrap">
            <div class="wbebl-boxes">
                <?php if (!empty($sub_systems)) : ?>
                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="wbebl_add_ons_requests">
                        <?php foreach ($sub_systems as $sub_system) : ?>
                            <?php $inactive_box = (!wbebl\classes\helpers\Plugin::is_installed($sub_system['plugin'])) ? 'inactive-box' : ''; ?>
                            <div class="wbebl-box-4 <?php echo esc_attr($inactive_box); ?>">
                                <?php
                                if (!empty($add_ons_presenter)) {
                                    echo wp_kses($add_ons_presenter->get_add_on_status_icon($sub_system), Sanitizer::allowed_html_tags());
                                }
                                ?>
                                <div class="wbebl-box-image">
                                    <img src="<?php echo esc_url($sub_system['image_link']); ?>" alt="">
                                </div>
                                <div class="wbebl-box-name">
                                    <strong><a href="<?php echo (!empty($sub_system['landing_page'])) ?  esc_url($sub_system['landing_page']) : ''; ?>"><?php esc_html($sub_system['label']) ?></a></strong>
                                </div>
                                <div class="wbebl-box-footer">
                                    <div class="wbebl-box-footer-left">
                                        <div>
                                            <?php echo wp_kses(\wbebl\classes\helpers\Plugin::get_status($sub_system['plugin']), Sanitizer::allowed_html_tags()); ?>
                                        </div>
                                    </div>
                                    <div class="wbebl-box-footer-right">
                                        <?php echo wp_kses(\wbebl\classes\helpers\Plugin::get_action_button($sub_system), Sanitizer::allowed_html_tags()); ?>
                                    </div>
                                </div>
                                <div class="wbebl-box-license">
                                    <input type="text" placeholder="<?php esc_attr_e('Purchase Key ...'); ?>">
                                    <button type="button" class="wbebl-button wbebl-button-green">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                                            <g>
                                                <path d="M32,0C14.4,0,0,14.4,0,32s14.4,32,32,32s32-14.4,32-32S49.6,0,32,0z M32,58.7c-14.7,0-26.7-12-26.7-26.7S17.3,5.3,32,5.3s26.7,12,26.7,26.7S46.7,58.7,32,58.7z" />
                                                <path d="M40.8,22.1l-12.3,12l-5.6-5.3c-1.1-1.1-2.7-1.1-3.7,0s-1.1,2.7,0,3.7l6.7,6.4c0.8,0.8,1.6,1.1,2.4,1.1c0.8,0,1.9-0.3,2.4-1.1l13.6-13.1c1.1-1.1,1.1-2.7,0-3.7C43.5,21.1,41.9,21.1,40.8,22.1z" />
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>