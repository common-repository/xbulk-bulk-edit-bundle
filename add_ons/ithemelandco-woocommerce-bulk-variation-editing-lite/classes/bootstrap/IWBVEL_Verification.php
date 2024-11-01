<?php

namespace iwbvel\classes\bootstrap;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use iwbvel\classes\helpers\Others;

class IWBVEL_Verification
{
    public static function is_active()
    {
        if (Others::isAllowedDomain()) {
            return 'yes';
        }

        $is_active = get_option('iwbvel_is_active', 'no');
        return ($is_active == 'yes' || $is_active == 'skipped');
    }

    public static function skipped()
    {
        $skipped = get_option('iwbvel_is_active', 'no');
        return $skipped == 'skipped';
    }
}
