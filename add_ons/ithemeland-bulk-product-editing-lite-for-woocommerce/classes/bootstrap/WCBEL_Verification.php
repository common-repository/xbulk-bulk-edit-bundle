<?php

namespace wcbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wcbel\classes\helpers\Others;

class WCBEL_Verification
{
    public static function is_active()
    {
        if (Others::isAllowedDomain()) {
            return 'yes';
        }

        $is_active = get_option('wcbel_is_active', 'no');
        return ($is_active == 'yes' || $is_active == 'skipped');
    }

    public static function skipped()
    {
        $skipped = get_option('wcbel_is_active', 'no');
        return $skipped == 'skipped';
    }
}
