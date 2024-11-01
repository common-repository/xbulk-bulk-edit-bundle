<?php

namespace wbebl\classes\repositories\option;

use wbebl\classes\repositories\option\Option_Main;

class Option extends Option_Main
{
    public function __construct()
    {
        $this->update_option_name = "_wbebl_options_update_key";
        $this->update_key = "wbebl-1000";
    }
}
