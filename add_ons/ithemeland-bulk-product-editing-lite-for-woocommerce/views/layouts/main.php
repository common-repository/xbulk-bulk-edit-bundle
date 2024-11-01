<?php

use wcbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php include WCBEL_VIEWS_DIR . "layouts/header.php"; ?>

<div id="wcbel-body">
    <div class="wcbel-tabs wcbel-tabs-main">
        <div class="wcbel-tabs-navigation">
            <nav class="wcbel-tabs-navbar">
                <ul class="wcbel-tabs-list" data-type="url" data-content-id="wcbel-main-tabs-contents">
                    <?php echo wp_kses(apply_filters('wcbel_top_navigation_buttons', ''), Sanitizer::allowed_html_tags()); ?>
                </ul>
            </nav>

            <div class="wcbel-top-nav-filters-per-page">
                <select id="wcbel-quick-per-page" title="The number of products per page">
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

            <div class="wcbel-items-pagination"></div>
        </div>

        <div class="wcbel-tabs-contents" id="wcbel-main-tabs-contents">
            <div class="wcbel-wrap">
                <div class="wcbel-tab-middle-content">
                    <div class="wcbel-table" id="wcbel-items-table">
                        <p style="width: 100%; text-align: center; padding: 10px 0;"><img src="<?php echo esc_url(WCBEL_IMAGES_URL . 'loading.gif'); ?>" width="30" height="30"></p>
                    </div>
                    <div class="wcbel-items-count"></div>
                </div>
            </div>
        </div>

        <div class="wcbel-created-by">
            <a href="https://ithemelandco.com" target="_blank">Created by iThemelandCo</a>
        </div>
    </div>
</div>

<?php include_once  WCBEL_VIEWS_DIR . "layouts/footer.php"; ?>