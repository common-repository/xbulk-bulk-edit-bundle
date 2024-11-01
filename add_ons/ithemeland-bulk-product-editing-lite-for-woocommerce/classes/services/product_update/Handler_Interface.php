<?php

namespace wcbel\classes\services\product_update;

defined('ABSPATH') || exit(); // Exit if accessed directly

interface Handler_Interface
{
    public static function get_instance();

    public function update($product_ids, $update_data);
}
