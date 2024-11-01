<?php

use wbebl\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

function core_required_error()
{
    echo wp_kses('<div class="notice notice-error"><p>' . esc_html__('Woo Bulk Editor Error: "iThemeland bulk bundle core" is inactive !') . '</p></div>', Sanitizer::allowed_html_tags());
}

add_action('admin_notices', 'core_required_error');
