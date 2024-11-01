<?php

namespace wcbef\classes\helpers;

class Session
{
    public static function set($session, $value)
    {
        $_SESSION[$session] = $value;
    }

    public static function get($session)
    {
        return (isset($_SESSION[$session])) ? $_SESSION[$session] : null;
    }

    public static function has($session)
    {
        return isset($_SESSION[$session]);
    }

    public static function get_flush($session)
    {
        if (self::has($session)) {
            $session_value = $_SESSION[$session];
            unset($_SESSION[$session]);
            return $session_value;
        }
        return null;
    }
}