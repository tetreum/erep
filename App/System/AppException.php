<?php

namespace App\System;

class AppException extends \Exception
{
    const INVALID_DATA = 1;
    const INVALID_LOGIN = 2;
    const NOT_FOUND = 3;
    const MISSING_PARAMS = 4;
    const ACTION_FAILED = 5;
    const NO_ENOUGH_MONEY = 6;
    const NO_ENOUGH_RESOURCES = 7;

    private static $description = [
        self::INVALID_DATA => "invalid data",
        self::INVALID_LOGIN => "invalid login",
        self::NOT_FOUND => "entity not found",
        self::MISSING_PARAMS => "missing required parameters",
        self::ACTION_FAILED => "action could not be done",
        self::NO_ENOUGH_MONEY => "no enough money",
        self::NO_ENOUGH_RESOURCES => "no enough resources",
    ];

    public function __construct($constant, $code = 0, \Exception $previous = null) {

        parent::__construct(self::$description[$constant], $constant, $previous);
    }
}
