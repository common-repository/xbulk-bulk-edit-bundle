<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!isset($current_page) || !isset($max_num_pages) || $max_num_pages == 1) {
    return;
}

$current_page = intval($current_page);
$max_num_pages = intval($max_num_pages);

$prev = max(1, $current_page - 1);
$next = min($max_num_pages, $current_page + 1);
$max_display = 3;
$pagination_html = "";
if (isset($max_num_pages)) {
    $pagination_html .= "<button type='button' data-index='" . esc_attr($prev) . "' class='iwbvel-variations-pagination-item'><</button>";
    if ($current_page < $max_display) {
        for ($i = 1; $i <= min($max_display, $max_num_pages); $i++) {
            $current = ($i == $current_page) ? 'current' : '';
            $pagination_html .= "<button type='button' data-index='" . esc_attr($i) . "' class='iwbvel-variations-pagination-item " . esc_attr($current) . "'>" . esc_html($i) . "</button>";
        }
        if ($max_num_pages > ($max_display + 1)) {
            $pagination_html .= "<span>...</span>";
        }
        if ($max_num_pages > $max_display) {
            $pagination_html .= "<button type='button' data-index='" . esc_attr($max_num_pages) . "' class='iwbvel-variations-pagination-item'>" . esc_html($max_num_pages) . "</button>";
        }
    } elseif ($current_page == $max_display) {
        $max_num = ($max_display < $max_num_pages) ? $max_display + 1 : $max_display;
        for ($i = 1; $i <= $max_num; $i++) {
            $current = ($i == $current_page) ? 'current' : '';
            $pagination_html .= "<button type='button' data-index='" . esc_attr($i) . "' class='iwbvel-variations-pagination-item " . esc_attr($current) . "'>" . esc_html($i) . "</button>";
        }
        if ($max_num_pages > ($current_page + 1)) {
            $pagination_html .= "<span>...</span>";
            $pagination_html .= "<button type='button' data-index='" . esc_attr($max_num_pages) . "' class='iwbvel-variations-pagination-item'>" . esc_html($max_num_pages) . "</button>";
        }
    } else {
        $pagination_html .= "<button type='button' data-index='1' class='iwbvel-variations-pagination-item'>1</button>";
        if ($max_num_pages > $max_display) {
            $pagination_html .= "<span>...</span>";
        }
        for ($i = $current_page - 1; $i <= min($current_page + 1, $max_num_pages); $i++) {
            $current = ($i == $current_page) ? 'current' : '';
            $pagination_html .= "<button type='button' data-index='" . esc_attr($i) . "' class='iwbvel-variations-pagination-item " . esc_attr($current) . "'>" . esc_html($i) . "</button>";
        }
        if ($current_page + 1 < $max_num_pages) {
            $pagination_html .= "<span>...</span>";
            $pagination_html .= "<button type='button' data-index='" . esc_attr($max_num_pages) . "' class='iwbvel-variations-pagination-item'>" . esc_html($max_num_pages) . "</button>";
        }
    }
    $pagination_html .= "<button type='button' data-index='" . esc_attr($next) . "' class='iwbvel-variations-pagination-item'>></button>";
}
$pagination_html .= "";

echo wp_kses($pagination_html, iwbvel\classes\helpers\Sanitizer::allowed_html());
