<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div id="wpbel-loading" class="wpbel-loading">
    <?php esc_html_e('Loading ...', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>
</div>

<?php
if (!empty($flush_message)) {
    include WPBEL_VIEWS_DIR . "alerts/flush_message.php";
}
?>

<div id="wpbel-main">
    <div id="wpbel-header">
        <div class="wpbel-plugin-title">
            <span class="wpbel-plugin-name"><img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'wpbel_icon_original.svg'); ?>" alt=""><?php echo esc_html($title); ?></span>
        </div>
        <ul class="wpbel-header-left">
            <li title="Help">
                <a href="<?php echo (!empty($doc_link)) ? esc_url($doc_link) : '#'; ?>">
                    <i class="wpbel-icon-book"></i>
                </a>
            </li>
            <li id="wpbel-full-screen" title="Full screen">
                <i class="wpbel-icon-enlarge"></i>
            </li>
            <li class="wpbel-get-pro-button" title="Get Pro">
                <a target="_blank" href="<?php echo esc_url(WPBEL_UPGRADE_URL); ?>">
                    <i class="wpbel-icon-star-full"></i> Get Pro
                </a>
            </li>
        </ul>
    </div>