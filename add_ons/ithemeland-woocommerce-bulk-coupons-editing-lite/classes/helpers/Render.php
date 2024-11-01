<?php

namespace wccbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Render
{
    public static function html($file_dir, $data = [])
    {
        if (file_exists($file_dir)) {
            extract($data);
            ob_start();
            include $file_dir;
            return ob_get_clean();
        }
        return false;
    }
}