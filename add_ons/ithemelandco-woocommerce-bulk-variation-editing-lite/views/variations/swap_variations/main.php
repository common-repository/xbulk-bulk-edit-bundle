<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-alert iwbvel-alert-danger">
    <span class="iwbvel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
    <a href="<?php echo esc_url(IWBVEL_UPGRADE_URL); ?>"><?php echo esc_html(IWBVEL_UPGRADE_TEXT); ?></a>
</div>

<p class="iwbvel-variations-swap-section-label"><?php esc_html_e('From', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>: </p>

<div class="iwbvel-variations-swap-from-attribute">
    <div class="iwbvel-variations-swap-from-attribute-selector-container">
        <label for="iwbvel-variations-swap-from-attribute-selector"><?php esc_html_e('Select attribute', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></label>
        <select id="iwbvel-variations-swap-from-attribute-selector" disabled="disabled">
            <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></option>
        </select>
    </div>
</div>

<p class="iwbvel-variations-swap-section-label"><?php esc_html_e('Replace with', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?>: </p>


<div class="iwbvel-variations-swap-container">
    <button type="button" class="itbbc-button itbbc-button-blue" disabled="disabled"><i class="itbbc-icon-check"></i> <?php esc_html_e('Swap', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></button>
</div>