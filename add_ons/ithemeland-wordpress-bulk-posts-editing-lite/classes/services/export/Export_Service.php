<?php

namespace wpbel\classes\services\export;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Search;
use wpbel\classes\services\export\handlers\CSV_Handler;
use wpbel\classes\services\export\handlers\XML_Handler;

class Export_Service
{
    private $search_repository;
    private $post_repository;
    private $column_repository;

    public function __construct()
    {
        $this->column_repository = new Column();
        $this->post_repository = new Post();
        $this->search_repository = new Search();
    }

    public function export($data)
    {
        $handler = $this->get_handler($data['export_type']);
        if (empty($handler) || !class_exists($handler)) {
            return false;
        }

        $last_filter_data = isset($this->search_repository->get_current_data()['last_filter_data']) ? $this->search_repository->get_current_data()['last_filter_data'] : null;

        switch ($data['posts']) {
            case 'all':
                $args = \wpbel\classes\helpers\Post_Helper::set_filter_data_items($last_filter_data, [
                    'posts_per_page' => '-1',
                    'post_type' => [$GLOBALS['wpbel_common']['active_post_type']],
                    'fields' => 'ids',
                ]);
                $posts = $this->post_repository->get_posts($args);
                $post_ids = $posts->posts;
                break;
            case 'selected':
                $post_ids = isset($data['item_ids']) ? $data['item_ids'] : [];
                break;
        }

        switch ($data['fields']) {
            case 'all':
                $columns = $this->column_repository->get_columns();
                break;
            case 'visible':
                $columns = $this->column_repository->get_active_columns()['fields'];
                break;
        }

        $handler_object = new $handler();
        return $handler_object->export([
            'post_ids' => $post_ids,
            'columns' => $columns
        ]);
    }

    private function get_handler($handler)
    {
        $handlers = $this->get_handlers();
        return (!empty($handlers[$handler])) ? $handlers[$handler] : '';
    }

    private function get_handlers()
    {
        return [
            'csv' => CSV_Handler::class,
            'xml' => XML_Handler::class,
        ];
    }
}
