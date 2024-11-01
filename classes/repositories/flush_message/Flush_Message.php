<?php

namespace wbebl\classes\repositories\flush_message;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Flush_Message
{
    private $flush_message_option_name;

    public function __construct()
    {
        $this->flush_message_option_name = "wbebl_flush_message";
    }

    public function set(array $data)
    {
        return update_option($this->flush_message_option_name, $data);
    }

    public function get()
    {
        $flush_message = get_option($this->flush_message_option_name);
        $this->delete();
        return $flush_message;
    }

    public function delete()
    {
        return delete_option($this->flush_message_option_name);
    }
}
