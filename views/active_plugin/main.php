<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$industries = wbebl\classes\helpers\Industry_Helper::get_industries();
?>

<div id="wbebl-body">
    <div class="wbebl-dashboard-body">
        <div id="wbebl-activation">
            <?php if (isset($is_active) && $is_active === true && $activation_skipped !== true) : ?>
                <div class="wbebl-wrap">
                    <div class="wbebl-tab-middle-content">
                        <div id="wbebl-activation-info">
                            <strong><?php esc_html_e("Congratulations, Your plugin is activated successfully. Let's Go!", 'xbulk-bulk-edit-bundle') ?></strong>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="wbebl-wrap wbebl-activation-form">
                    <div class="wbebl-tab-middle-content">
                        <?php if (!empty($flush_message) && is_array($flush_message)) : ?>
                            <div class="wbebl-alert <?php echo ($flush_message['message'] == "Success !") ? "wbebl-alert-success" : "wbebl-alert-danger"; ?>">
                                <span><?php echo esc_html($flush_message['message']); ?></span>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wbebl-activation-form">
                            <h3 class="wbebl-activation-top-alert">Fill the below form to get the latest updates' news and <strong style="text-decoration: underline;">Special Offers(Discount)</strong>, Otherwise, Skip it!</h3>
                            <input type="hidden" name="action" value="wbebl_activation_plugin">
                            <div class="wbebl-activation-field">
                                <label for="wbebl-activation-email"><?php esc_html_e('Email', 'xbulk-bulk-edit-bundle'); ?> </label>
                                <input type="email" name="email" placeholder="Email ..." id="wbebl-activation-email" required>
                            </div>
                            <div class="wbebl-activation-field">
                                <label for="wbebl-activation-industry"><?php esc_html_e('What is your industry?', 'xbulk-bulk-edit-bundle'); ?> </label>
                                <select name="industry" id="wbebl-activation-industry" required>
                                    <option value=""><?php esc_html_e('Select', 'xbulk-bulk-edit-bundle'); ?></option>
                                    <?php
                                    if (!empty($industries)) :
                                        foreach ($industries as $industry_key => $industry_label) :
                                    ?>
                                            <option value="<?php echo esc_attr($industry_key); ?>"><?php echo esc_attr($industry_label); ?></option>
                                    <?php
                                        endforeach;
                                    endif
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="activation_type" id="wbebl-activation-type" value="">
                            <button type="button" id="wbebl-activation-activate" class="wbebl-button wbebl-button-lg wbebl-button-blue" value="1"><?php esc_html_e('Activate', 'xbulk-bulk-edit-bundle'); ?></button>
                            <button type="button" id="wbebl-activation-skip" class="wbebl-button wbebl-button-lg wbebl-button-gray" style="float: left;" value="skip"><?php esc_html_e('Skip', 'xbulk-bulk-edit-bundle'); ?></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>