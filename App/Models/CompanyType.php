<?php

namespace App\Models;

class CompanyType
{
    const PRIMARY_SECTOR = 1;
    const SECONDARY_SECTOR = 2;

    const PRODUCT_RAW_FOOD = 1;
    const PRODUCT_RAW_WEAPON = 2;
    const PRODUCT_RAW_HOUSE = 3;
    const PRODUCT_FOOD = 4;
    const PRODUCT_GUN = 5;
    const PRODUCT_WEAPON = 6;

    const CURRENCY_LOCAL = "local";
    const CURRENCY_GOLD = "gold";

    // Array keys ARE IMPORTANT, DO NOT EDIT THEM OR EDIT COMPANY ID TOO
    public static $types = [
        1 => [
            "id" => 1,
            "name" => "Raw food Factory",
            "sector" => self::PRIMARY_SECTOR,
            "product" => self::PRODUCT_RAW_FOOD,
            "qualities" => [
                1 => [
                    "workers" => 0,
                    "product_amount" => 30,
                    "price" => 500,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                2 => [
                    "workers" => 0,
                    "product_amount" => 70,
                    "price" => 1000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                3 => [
                    "workers" => 1,
                    "product_amount" => 120,
                    "price" => 10,
                    "currency" => self::CURRENCY_GOLD,
                ],
                4 => [
                    "workers" => 1,
                    "product_amount" => 170,
                    "price" => 3000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                5 => [
                    "workers" => 4,
                    "product_amount" => 250,
                    "price" => 35,
                    "currency" => self::CURRENCY_GOLD,
                ]
            ]
        ],
        2 => [
            "id" => 2,
            "name" => "Raw weapon Factory",
            "sector" => self::PRIMARY_SECTOR,
            "product" => self::PRODUCT_RAW_WEAPON,
            "qualities" => [
                1 => [
                    "workers" => 0,
                    "product_amount" => 30,
                    "price" => 500,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                2 => [
                    "workers" => 0,
                    "product_amount" => 70,
                    "price" => 1000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                3 => [
                    "workers" => 1,
                    "product_amount" => 120,
                    "price" => 10,
                    "currency" => self::CURRENCY_GOLD,
                ],
                4 => [
                    "workers" => 1,
                    "product_amount" => 170,
                    "price" => 3000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                5 => [
                    "workers" => 4,
                    "product_amount" => 250,
                    "price" => 35,
                    "currency" => self::CURRENCY_GOLD,
                ]
            ]
        ],
        3 => [
            "id" => 3,
            "name" => "Raw house Factory",
            "sector" => self::PRIMARY_SECTOR,
            "product" => self::PRODUCT_RAW_HOUSE,
            "qualities" => [
                1 => [
                    "workers" => 0,
                    "product_amount" => 30,
                    "price" => 500,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                2 => [
                    "workers" => 0,
                    "product_amount" => 70,
                    "price" => 1000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                3 => [
                    "workers" => 1,
                    "product_amount" => 120,
                    "price" => 10,
                    "currency" => self::CURRENCY_GOLD,
                ],
                4 => [
                    "workers" => 1,
                    "product_amount" => 170,
                    "price" => 3000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                5 => [
                    "workers" => 4,
                    "product_amount" => 250,
                    "price" => 35,
                    "currency" => self::CURRENCY_GOLD,
                ]
            ]
        ],
        4 => [
            "id" => 4,
            "name" => "Food Factory",
            "sector" => self::SECONDARY_SECTOR,
            "product" => self::PRODUCT_FOOD,
            "qualities" => [
                1 => [
                    "workers" => 0,
                    "product_amount" => 30,
                    "price" => 500,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                2 => [
                    "workers" => 0,
                    "product_amount" => 70,
                    "price" => 1000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                3 => [
                    "workers" => 1,
                    "product_amount" => 120,
                    "price" => 10,
                    "currency" => self::CURRENCY_GOLD,
                ],
                4 => [
                    "workers" => 1,
                    "product_amount" => 170,
                    "price" => 3000,
                    "currency" => self::CURRENCY_LOCAL,
                ],
                5 => [
                    "workers" => 4,
                    "product_amount" => 250,
                    "price" => 35,
                    "currency" => self::CURRENCY_GOLD,
                ]
            ]
        ],
    ];

    public static function getInfo ($id, $quality)
    {
        $mainInfo = self::$types[$id];
        unset($mainInfo["qualities"]);

        return array_merge($mainInfo, self::$types[$id]["qualities"][$quality]);
    }
}