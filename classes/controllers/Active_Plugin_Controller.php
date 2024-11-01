<?php

namespace wbebl\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wbebl\classes\bootstrap\WBEBL_Verification;

class Active_Plugin_Controller
{
    public function index()
    {
        $is_active = WBEBL_Verification::is_active();
        $activation_skipped = WBEBL_Verification::skipped();

        include_once WBEBL_VIEWS_DIR . "active_plugin/main.php";
    }
}
