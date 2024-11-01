<?php

use iwbvel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

include IWBVEL_VIEWS_DIR . "layouts/header.php";
?>

<div id="iwbvel-body">
    <div class="iwbvel-tabs iwbvel-tabs-main">
        <div class="iwbvel-tabs-navigation">
            <nav class="iwbvel-tabs-navbar">
                <ul class="iwbvel-tabs-list" data-type="url" data-content-id="iwbvel-main-tabs-contents">
                    <?php echo wp_kses(apply_filters('iwbvel_top_navigation_buttons', ''), Sanitizer::allowed_html()); ?>
                </ul>
            </nav>

            <div class="iwbvel-top-nav-filters-per-page">
                <select id="iwbvel-quick-per-page" title="The number of products per page">
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

            <div class="iwbvel-items-pagination"></div>
        </div>

        <div class="iwbvel-tabs-contents" id="iwbvel-main-tabs-contents">
            <div class="iwbvel-wrap">
                <div class="iwbvel-tab-middle-content">
                    <div class="iwbvel-table" id="iwbvel-items-table">
                        <p style="width: 100%; text-align: center; padding: 10px 0;"><img src="<?php echo esc_url(IWBVEL_IMAGES_URL . 'loading.gif'); ?>" width="30" height="30"></p>
                    </div>
                    <div class="iwbvel-items-count"></div>
                </div>
            </div>
        </div>

        <div class="iwbvel-created-by">
            <a href="https://ithemelandco.com" target="_blank">Created by iThemelandCo</a>
        </div>
    </div>
</div>

<?php include_once  IWBVEL_VIEWS_DIR . "layouts/footer.php"; ?>