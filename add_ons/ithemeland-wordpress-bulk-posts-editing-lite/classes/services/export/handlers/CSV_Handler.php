<?php

namespace wpbel\classes\services\export\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Post;
use wpbel\classes\services\export\Export_Interface;

class CSV_Handler implements Export_Interface
{
    private $post_repository;

    public function __construct()
    {
        $this->post_repository = new Post();
    }

    public function export($data)
    {
        $file_name = "wpbel-post-export-" . time() . '.csv';
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");
        $file = fopen('php://output', 'w');
        fwrite($file, chr(239) . chr(187) . chr(191)); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite

        if (empty($data['post_ids'])) {
            return false;
        }

        $header = [];
        $header[] = 'Post ID';
        $header[] = 'Post Title';
        if (!empty($data['columns'])) {
            foreach ($data['columns'] as $field => $column) {
                if (isset($column['field_type'])) {
                    switch ($column['field_type']) {
                        case 'custom_field':
                            $header[] = "Meta: {$field}";
                            break;
                        case 'taxonomy':
                            $header[] = "Taxonomy: {$field}";
                            break;
                        default:
                            break;
                    }
                } else {
                    $header[] = $column['label'];
                }
            }
        }

        fputcsv($file, $header);
        foreach ($data['post_ids'] as $post_id) {
            $output = [];
            $post_object = $this->post_repository->get_post(intval($post_id));
            if (!($post_object instanceof \WP_Post)) {
                return false;
            }
            $post = $this->post_repository->get_post_fields($post_object);
            $output[] = $post['id'];
            $output[] = $post['post_title'];
            if (!empty($data['columns'])) {
                foreach ($data['columns'] as $field => $column_item) {
                    if (isset($post[$field])) {
                        if (isset($column_item['field_type'])) {
                            switch ($column_item['field_type']) {
                                case 'custom_field':
                                    $output[] = (isset($post['custom_field'][$field])) ? $post['custom_field'][$field][0] : '';
                                    break;
                                case 'taxonomy':
                                    $output[] = implode(', ', wp_get_post_terms($post['id'], $field, ['fields' => 'ids']));
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            switch ($field) {
                                case "_thumbnail_id":
                                    $image = wp_get_attachment_image_src($post[$field]['id'], 'original');
                                    $value = isset($image[0]) ? $image[0] : '';
                                    break;
                                default:
                                    $value = (is_array($post[$field])) ? implode(',', $post[$field]) : $post[$field];
                                    break;
                            }
                            $output[] = $value;
                        }
                    }
                }
            }
            fputcsv($file, $output);
        }

        die();
    }
}
