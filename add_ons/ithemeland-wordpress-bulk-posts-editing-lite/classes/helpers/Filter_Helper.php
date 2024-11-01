<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Search;

class Filter_Helper
{
    public static function get_active_filter_data()
    {
        $search_repository = new Search();
        $data = $search_repository->get_current_data();
        if (!empty($data) && isset($data['last_filter_data'])) {
            $filter_data = $data['last_filter_data'];
        } else {
            $preset = $search_repository->get_preset($search_repository->get_use_always());
            if (!isset($preset['filter_data'])) {
                $preset['filter_data'] = [];
            }
            $filter_data = $preset['filter_data'];
        }

        return $filter_data;
    }
}
