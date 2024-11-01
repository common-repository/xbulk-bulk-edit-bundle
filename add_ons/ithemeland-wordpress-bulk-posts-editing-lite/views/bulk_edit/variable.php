<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select class="wpbel-bulk-edit-form-variable" title="<?php esc_html_e('Select Variable', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?>" data-field="variable">
    <option value=""><?php esc_html_e('Variable', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
    <option value="title"><?php esc_html_e('Title', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
    <option value="id"><?php esc_html_e('ID', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
    <option value="menu_order"><?php esc_html_e('Menu Order', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
    <option value="parent_id"><?php esc_html_e('Parent ID', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
    <option value="parent_title"><?php esc_html_e('Parent Title', 'ithemeland-wordpress-bulk-posts-editing-lite'); ?></option>
</select>