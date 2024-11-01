<?php

namespace wcbel\classes\services\product_update;

defined('ABSPATH') || exit(); // Exit if accessed directly

interface Update_Interface
{
    public static function get_instance();

    public function set_update_data($update_data);

    public function perform();
}
