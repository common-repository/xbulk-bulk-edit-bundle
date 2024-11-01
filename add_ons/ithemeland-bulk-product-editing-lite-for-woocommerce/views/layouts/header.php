<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div id="wcbel-loading" class="wcbel-loading">
    <?php esc_html_e('Loading ...', 'ithemeland-bulk-product-editing-lite-for-woocommerce'); ?>
</div>

<?php
if (!empty($flush_message)) {
    include WCBEL_VIEWS_DIR . "alerts/flush_message.php";
}
?>

<div id="wcbel-main">
    <div id="wcbel-header">
        <div class="wcbel-plugin-title">
            <span class="wcbel-plugin-name"><img src="<?php echo esc_url(WCBEL_IMAGES_URL . 'wcbel_icon_original.svg'); ?>" alt=""><?php echo esc_html($title); ?></span>
        </div>
        <ul class="wcbel-header-left">
            <li title="Help">
                <a href="<?php echo (!empty($doc_link)) ? esc_url($doc_link) : '#'; ?>">
                    <i class="wcbel-icon-book"></i>
                </a>
            </li>
            <li id="wcbel-full-screen" title="Full screen">
                <i class="wcbel-icon-enlarge"></i>
            </li>
            <li class="wcbel-get-pro-button" title="Get Pro">
                <a target="_blank" href="<?php echo esc_url(WCBEL_UPGRADE_URL); ?>">
                    <i class="wcbel-icon-star-full"></i> Get Pro
                </a>
            </li>
        </ul>
    </div>