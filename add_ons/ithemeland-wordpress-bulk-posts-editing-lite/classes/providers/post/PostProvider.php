<?php

namespace wpbel\classes\providers\post;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\providers\column\PostColumnProvider;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Post;

class PostProvider
{
    private static $instance = null;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function get_items($items, $columns)
    {
        $output['items'] = '';
        $output['includes'] = [];

        $post_Repository = new Post();

        if (!empty($items)) {
            $column_provider = PostColumnProvider::get_instance();
            $show_id_column = Column::SHOW_ID_COLUMN;
            $next_static_columns = Column::get_static_columns();

            foreach ($items as $post_id) {
                $item = $post_Repository->get_post(intval($post_id));
                $result = $column_provider->get_item_columns($item, $columns);

                if (is_array($result) && isset($result['items'])) {
                    $output['items'] .= $result['items'];
                    if (!empty($result['includes'])) {
                        $output['includes'][] = $result['includes'];
                    }
                } else {
                    $output['items'] .= $result;
                }
            }
        }

        return $output;
    }
}
