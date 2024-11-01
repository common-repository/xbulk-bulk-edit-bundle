<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php $industries = wcbel\classes\helpers\Industry_Helper::get_industries(); ?>

<div id="wcbel-body">
    <div class="wcbel-dashboard-body">
        <div id="wcbel-activation">
            <?php if (isset($is_active) && $is_active === true && $activation_skipped !== true) : ?>
                <div class="wcbel-wrap">
                    <div class="wcbel-tab-middle-content">
                        <div id="wcbel-activation-info">
                            <strong><?php esc_html_e("Congratulations, Your plugin is activated successfully. Let's Go!", 'ithemeland-woocommerce-bulk-coupons-editing-lite') ?></strong>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="wcbel-wrap wcbel-activation-form">
                    <div class="wcbel-tab-middle-content">
                        <?php if (!empty($flush_message) && is_array($flush_message)) : ?>
                            <div class="wcbel-alert <?php echo ($flush_message['message'] == "Success !") ? "wcbel-alert-success" : "wcbel-alert-danger"; ?>">
                                <span><?php echo esc_html($flush_message['message']); ?></span>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wcbel-activation-form">
                            <?php wp_nonce_field('wcbel_post_nonce'); ?>
                            <h3 class="wcbel-activation-top-alert">Fill the below form to get the latest updates' news and <strong style="text-decoration: underline;">Special Offers(Discount)</strong>, Otherwise, Skip it!</h3>
                            <input type="hidden" name="action" value="wcbel_activation_plugin">
                            <div class="wcbel-activation-field">
                                <label for="wcbel-activation-email"><?php esc_html_e('Email', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?> </label>
                                <input type="email" name="email" placeholder="Email ..." id="wcbel-activation-email">
                            </div>
                            <div class="wcbel-activation-field">
                                <label for="wcbel-activation-industry"><?php esc_html_e('What is your industry?', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?> </label>
                                <select name="industry" id="wcbel-activation-industry">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?></option>
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
                            <input type="hidden" name="activation_type" id="wcbel-activation-type" value="">
                            <button type="button" id="wcbel-activation-activate" class="wcbel-button wcbel-button-lg wcbel-button-blue" value="1"><?php esc_html_e('Activate', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?></button>
                            <button type="button" id="wcbel-activation-skip" class="wcbel-button wcbel-button-lg wcbel-button-gray" style="float: left;" value="skip"><?php esc_html_e('Skip', 'ithemeland-woocommerce-bulk-coupons-editing-lite'); ?></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>