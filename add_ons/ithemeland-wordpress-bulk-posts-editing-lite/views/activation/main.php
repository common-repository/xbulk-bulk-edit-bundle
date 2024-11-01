<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php $industries = wpbel\classes\helpers\Industry_Helper::get_industries(); ?>

<div id="wpbel-body">
    <div class="wpbel-dashboard-body">
        <div id="wpbel-activation">
            <?php if (isset($is_active) && $is_active === true && $activation_skipped !== true) : ?>
                <div class="wpbel-wrap">
                    <div class="wpbel-tab-middle-content">
                        <div id="wpbel-activation-info">
                            <strong><?php esc_html_e("Congratulations, Your plugin is activated successfully. Let's Go!", 'ithemeland-wordpress-bulk-posts-editing-lite') ?></strong>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="wpbel-wrap wpbel-activation-form">
                    <div class="wpbel-tab-middle-content">
                        <?php if (!empty($flush_message) && is_array($flush_message)) : ?>
                            <div class="wpbel-alert <?php echo ($flush_message['message'] == "Success !") ? "wpbel-alert-success" : "wpbel-alert-danger"; ?>">
                                <span><?php echo esc_html($flush_message['message']); ?></span>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbel-activation-form">
                            <?php wp_nonce_field('wpbel_post_nonce'); ?>
                            <h3 class="wpbel-activation-top-alert">Fill the below form to get the latest updates' news and <strong style="text-decoration: underline;">Special Offers(Discount)</strong>, Otherwise, Skip it!</h3>
                            <input type="hidden" name="action" value="wpbel_activation_plugin">
                            <div class="wpbel-activation-field">
                                <label for="wpbel-activation-email"><?php esc_html_e('Email', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> </label>
                                <input type="email" name="email" placeholder="Email ..." id="wpbel-activation-email">
                            </div>
                            <div class="wpbel-activation-field">
                                <label for="wpbel-activation-industry"><?php esc_html_e('What is your industry?', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?> </label>
                                <select name="industry" id="wpbel-activation-industry">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
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
                            <input type="hidden" name="activation_type" id="wpbel-activation-type" value="">
                            <button type="button" id="wpbel-activation-activate" class="wpbel-button wpbel-button-lg wpbel-button-blue" value="1"><?php esc_html_e('Activate', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button>
                            <button type="button" id="wpbel-activation-skip" class="wpbel-button wpbel-button-lg wpbel-button-gray" style="float: left;" value="skip"><?php esc_html_e('Skip', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>