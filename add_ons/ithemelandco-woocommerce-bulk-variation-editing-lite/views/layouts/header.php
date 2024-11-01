<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div id="iwbvel-loading" class="iwbvel-loading">
    <?php esc_html_e('Loading ...', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>
</div>

<?php
if (!empty($flush_message)) {
    include IWBVEL_VIEWS_DIR . "alerts/flush_message.php";
}
?>

<div id="iwbvel-main">
    <div id="iwbvel-header">
        <div class="iwbvel-plugin-title">
            <span class="iwbvel-plugin-name"><img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'iwbvel_icon_original.svg'); ?>" alt=""><?php echo esc_html($title); ?></span>
        </div>
        <ul class="iwbvel-header-left">
            <li title="Help">
                <a href="<?php echo (!empty($doc_link)) ? esc_url($doc_link) : '#'; ?>">
                    <i class="iwbvel-icon-book"></i>
                </a>
            </li>
            <li id="iwbvel-full-screen" title="Full screen">
                <i class="iwbvel-icon-enlarge"></i>
            </li>
            <li class="iwbvel-get-pro-button" title="Get Pro">
                <a target="_blank" href="<?php echo esc_url(IWBVEL_UPGRADE_URL); ?>">
                    <i class="iwbvel-icon-star-full"></i> Get Pro
                </a>
            </li>
        </ul>
    </div>