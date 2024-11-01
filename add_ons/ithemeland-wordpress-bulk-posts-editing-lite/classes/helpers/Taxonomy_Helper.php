<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\lib\WpbelPostsTaxonomyWalker;

class Taxonomy_Helper
{
    public static function get_post_taxonomy_list($taxonomy = 'categories', array $checked = [], $args = [])
    {
        $defaults = array(
            'taxonomy' => esc_sql($taxonomy),
            'hide_empty' => false,
            'show_option_none' => '',
            'echo' => 0,
            'depth' => 0,
            'wrap_class' => 'wpbel-post-taxonomy-list',
            'level_class' => '',
            'parent_title_format' => '%s',
        );
        $args = wp_parse_args($args, $defaults);
        if (!taxonomy_exists($args['taxonomy'])) {
            return false;
        }
        $categories = get_categories($args);
        $output = "<ul class='" . sanitize_text_field($args['wrap_class']) . "'>";
        if (empty($categories)) {
            if (!empty($args['show_option_none'])) {
                $output .= "<li>" . sanitize_text_field($args['show_option_none']) . "</li>";
            }
        } else {
            $walker = new WpbelPostsTaxonomyWalker($checked);
            $output .= $walker->walk($categories, $args['depth'], $args);
        }
        $output .= "</ul>";
        return $output;
    }

    public static function isAllowed($taxonomy_name)
    {
        return (in_array($taxonomy_name, [
            'category',
            'post_tag'
        ]));
    }
}
