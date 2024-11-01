<?php

use wpbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php include WPBEL_VIEWS_DIR . "layouts/header.php"; ?>

<div id="wpbel-body">
    <div class="wpbel-tabs wpbel-tabs-main">
        <div class="wpbel-tabs-navigation">
            <nav class="wpbel-tabs-navbar">
                <ul class="wpbel-tabs-list" data-type="url" data-content-id="wpbel-main-tabs-contents">
                    <?php echo wp_kses(apply_filters('wpbel_top_navigation_buttons', ''), Sanitizer::allowed_html_tags()); ?>
                </ul>
            </nav>

            <div class="wpbel-top-nav-filters-per-page">
                <select id="wpbel-quick-per-page" title="The number of products per page">
                    <?php
                    if (!empty($count_per_page_items)) :
                        foreach ($count_per_page_items as $count_per_page_item) :
                    ?>
                            <option value="<?php echo intval($count_per_page_item); ?>" <?php if (isset($current_settings['count_per_page']) && $current_settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                                <?php echo esc_html($count_per_page_item); ?>
                            </option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="wpbel-items-pagination"></div>
        </div>

        <div class="wpbel-tabs-contents" id="wpbel-main-tabs-contents">
            <div class="wpbel-wrap">
                <div class="wpbel-tab-middle-content">
                    <div class="wpbel-table" id="wpbel-items-table">
                        <p style="width: 100%; text-align: center; padding: 10px 0;"><img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading.gif'); ?>" width="30" height="30"></p>
                    </div>
                    <div class="wpbel-items-count"></div>
                </div>
            </div>
        </div>

        <div class="wpbel-created-by">
            <a href="https://ithemelandco.com" target="_blank">Created by iThemelandCo</a>
        </div>
    </div>
</div>

<?php include_once  WPBEL_VIEWS_DIR . "layouts/footer.php"; ?>