<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="iwbvel-alert iwbvel-alert-danger">
    <span class="iwbvel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
    <a href="<?php echo esc_url(IWBVEL_UPGRADE_URL); ?>"><?php echo esc_html(IWBVEL_UPGRADE_TEXT); ?></a>
</div>

<div class="iwbvel-variations-delete-type-container">
    <label for="iwbvel-variations-delete-type-selector"><?php esc_html_e('Delete type', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></label>
    <select id="iwbvel-variations-delete-type-selector" disabled="disabled">
        <option value="all"><?php esc_html_e('Delete all products variations', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></option>
        <option value="attributes"><?php esc_html_e('Delete the products variations according to the combination of the attributes', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></option>
    </select>
</div>

<div class="iwbvel-variations-delete-attribute-items">
    <div class="iwbvel-variations-delete-attribute-selector-container">
        <label for="iwbvel-variations-delete-attribute-selector"><?php esc_html_e('Select attribute', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></label>
        <select id="iwbvel-variations-delete-attribute-selector" disabled="disabled">
            <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></option>
        </select>
    </div>
</div>

<div class="iwbvel-variations-delete-container">
    <button type="button" class="itbbc-button itbbc-button-blue" disabled="disabled"><i class="itbbc-icon-check"></i> <?php esc_html_e('Submit', 'ithemeland-woocommerce-bulk-variations-editing-pro'); ?></button>
</div>