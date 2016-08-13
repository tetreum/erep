<?php

namespace App\Controllers;

use App\Models\Item;
use App\Models\ItemOffer;
use App\System\App;
use App\System\AppException;
use App\System\Controller;

class Market extends Controller
{
    public function sell ()
    {
        $item = (int)$_POST["item"];
        $quantity = (int)$_POST["quantity"];
        $quality = (int)$_POST["quality"];
        $price = (float)$_POST["price"];
        $uid = App::user()->getUid();

        if ($price < 0.01 || $item < 1 || $quantity < 1 || $quality < 0) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $query = [
            "uid" => $uid,
            "item" => $item,
            "quality" => $quality,
        ];

        // check if user has the item
        $item = Item::where($query)->first();

        if (!$item || $item->quantity < $quantity) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $query["quantity"] = $quantity;
        $query["price"] = $price;
        $query["country"] = App::user()->getLocation()["id"];

        $success = ItemOffer::create($query);

        if ($success) {
            // remove it from his inventory
            $item->quantity -= $quantity;
            $item->save();

            return true;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }
}