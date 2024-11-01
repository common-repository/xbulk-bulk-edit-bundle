<?php

namespace wbebl\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wbebl\classes\helpers\Others;

class WBEBL_Verification
{
    public static function is_active()
    {
        if (Others::isAllowedDomain()) {
            return 'yes';
        }

        $is_active = get_option('wbebl_is_active', 'no');
        return ($is_active == 'yes' || $is_active == 'skipped');
    }

    public static function skipped()
    {
        $skipped = get_option('wbebl_is_active', 'no');
        return $skipped == 'skipped';
    }
}
