<?php

namespace App\System;

class Input
{
    public static function getInteger($name) {
        return (int) $_REQUEST[$name];
    }

    public static function getFloat($name) {
        return (float) $_REQUEST[$name];
    }

    public static function getString($name, $applyCensorship = false) {
        return Utils::sanitizeString($_REQUEST[$name], $applyCensorship);
    }

    public static function getBoolean($name)
    {
        if (is_numeric($_REQUEST[$name])) {
            $_REQUEST[$name] = (int) $_REQUEST[$name];
            if ($_REQUEST[$name] === 1) {
                return true;
            }
            return false;
        }

        return (bool) $_REQUEST[$name];
    }
}