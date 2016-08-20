<?php

namespace App\Models;



class Item
{
    const RAW_FOOD = 1;
    const RAW_WEAPON = 2;
    const RAW_HOUSE = 3;
    const FOOD = 4;
    const GUN = 5;
    const TANK = 6;

    const TYPE_RAW = 1;
    const TYPE_FOOD = 2;
    const TYPE_WEAPON = 3;

    public static function getList ()
    {
        return [
            [
                "id" => 1,
                "name" => "Raw food",
                "description" => "Can be converted to food",
                "type" => self::TYPE_RAW,
                "canBeSold" => true,
                "expires" => null,
            ],
            [
                "id" => 2,
                "name" => "Raw weapon",
                "description" => "Can be converted to weapon",
                "type" => self::TYPE_RAW,
                "canBeSold" => true,
                "expires" => null,
            ],
            [
                "id" => 3,
                "name" => "Raw house",
                "description" => "Can be converted to house",
                "type" => self::TYPE_RAW,
                "canBeSold" => true,
                "expires" => null,
            ],
            [
                "id" => 4,
                "name" => "Food",
                "description" => "Eat to gain energy",
                "type" => self::TYPE_FOOD,
                "canBeSold" => true,
                "expires" => null,
            ],
            [
                "id" => 5,
                "name" => "Gun",
                "description" => "Kills people",
                "type" => self::TYPE_WEAPON,
                "canBeSold" => true,
                "expires" => null,
            ]
        ];
    }

    public static function find ($id) {
        return self::where(["id" => $id])[0];
    }

    /**
     * Simple wrapper of Eloquent Model as i may move this to db
     * @param array $query
     * @return array
     */
    public static function where (array $query)
    {
        $items = self::getList();
        $list = [];

        foreach ($items as $item)
        {
            foreach ($query as $k => $v) {
                if ($item[$k] != $v) {
                    continue 2;
                }
            }
            $list[] = $item;
        }

        return $list;
    }
}