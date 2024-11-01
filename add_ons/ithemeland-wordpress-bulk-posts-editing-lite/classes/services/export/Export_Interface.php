<?php

namespace wpbel\classes\services\export;

defined('ABSPATH') || exit(); // Exit if accessed directly

interface Export_Interface
{
    public function export($data);
}
