<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

class User_Repository
{
    private static $instance;

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

    public function get_users()
    {
        return get_users([
            'role__in' => array('author', 'editor', 'administrator', 'contributor')
        ]);
    }
}
