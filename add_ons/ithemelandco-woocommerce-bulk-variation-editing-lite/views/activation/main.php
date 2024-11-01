<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

$industries = iwbvel\classes\helpers\Industry_Helper::get_industries();
?>

<div id="iwbvel-body">
    <div class="iwbvel-dashboard-body">
        <div id="iwbvel-activation">
            <?php if (isset($is_active) && $is_active === true && $activation_skipped !== true) : ?>
                <div class="iwbvel-wrap">
                    <div class="iwbvel-tab-middle-content">
                        <div id="iwbvel-activation-info">
                            <strong><?php esc_html_e("Congratulations, Your plugin is activated successfully. Let's Go!", 'ithemelandco-woocommerce-bulk-variation-editing-lite') ?></strong>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="iwbvel-wrap iwbvel-activation-form">
                    <div class="iwbvel-tab-middle-content">
                        <?php if (!empty($flush_message) && is_array($flush_message)) : ?>
                            <div class="iwbvel-alert <?php echo ($flush_message['message'] == "Success !") ? "iwbvel-alert-success" : "iwbvel-alert-danger"; ?>">
                                <span><?php echo esc_html($flush_message['message']); ?></span>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="iwbvel-activation-form">
                            <h3 class="iwbvel-activation-top-alert">Fill the below form to get the latest updates' news and <strong style="text-decoration: underline;">Special Offers(Discount)</strong>, Otherwise, Skip it!</h3>
                            <input type="hidden" name="action" value="iwbvel_activation_plugin">
                            <?php wp_nonce_field('iwbvel_activation_plugin'); ?>
                            <div class="iwbvel-activation-field">
                                <label for="iwbvel-activation-email"><?php esc_html_e('Email', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> </label>
                                <input type="email" name="email" placeholder="Email ..." id="iwbvel-activation-email">
                            </div>
                            <div class="iwbvel-activation-field">
                                <label for="iwbvel-activation-industry"><?php esc_html_e('What is your industry?', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?> </label>
                                <select name="industry" id="iwbvel-activation-industry">
                                    <option value=""><?php esc_html_e('Select', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></option>
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
                            <input type="hidden" name="activation_type" id="iwbvel-activation-type" value="">
                            <button type="button" id="iwbvel-activation-activate" class="iwbvel-button iwbvel-button-lg iwbvel-button-blue" value="1"><?php esc_html_e('Activate', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                            <button type="button" id="iwbvel-activation-skip" class="iwbvel-button iwbvel-button-lg iwbvel-button-gray" style="float: left;" value="skip"><?php esc_html_e('Skip', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>