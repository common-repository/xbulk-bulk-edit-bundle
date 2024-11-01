<?php

use iwbvel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<table id="iwbvel-variations-table">
    <thead>
        <tr>
            <th><input type="checkbox" title="<?php esc_attr_e('Select All', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>" class="iwbvel-variations-table-select-all-button"></th>
            <th style="min-width: 35px;"><?php esc_html_e('ID', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
            <th> </th>
            <th style="text-align: left;"><?php esc_html_e('Combinations', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
            <th><?php esc_html_e('Regular price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
            <th><?php esc_html_e('Sale price', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
            <th><?php esc_html_e('Stock QTY', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?></th>
            <th title="<?php esc_attr_e('Enable', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-check-circle"></i></th>
            <th title="<?php esc_attr_e('Default', 'ithemelandco-woocommerce-bulk-variation-editing-lite'); ?>"><i class="iwbvel-icon-pocket"></i></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($variations)) {
            foreach ($variations as $variation) {
                include "variations-table-row.php";
            }
        } else {
            echo wp_kses('<td colspan="100%">No data available</td>', Sanitizer::allowed_html());
        }
        ?>
    </tbody>
</table>

<?php
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/variation-attributes-edit-modal.php";
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/variation-thumbnail-modal.php";
include_once IWBVEL_VIEWS_DIR . "variations/add_variations/all-variations-modal.php";
?>