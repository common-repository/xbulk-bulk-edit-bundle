<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-variations-attach-attribute">
    <div class="iwbvel-alert iwbvel-alert-danger">
        <span class="iwbvel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
        <a href="<?php echo esc_url(IWBVEL_UPGRADE_URL); ?>"><?php echo esc_html(IWBVEL_UPGRADE_TEXT); ?></a>
    </div>
    <div class="iwbvel-variations-attach-attribute-selector-container">
        <label for="iwbvel-variations-attach-attribute-selector"><?php esc_html_e('Select attribute', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></label>
        <select id="iwbvel-variations-attach-attribute-selector" disabled="disabled">
            <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></option>
        </select>
    </div>
</div>