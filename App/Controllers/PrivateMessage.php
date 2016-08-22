<?php

namespace App\Controllers;

use App\System\App;
use App\System\AppException;
use App\System\Controller;
use App\System\Input;
use App\Models\PrivateMessage as PrivateMessageModel;

class PrivateMessage extends Controller
{
    public function sendMessage ()
    {
        $receiver = Input::getInteger("uid");
        $message = Input::getString("message", true);

        if ($receiver < 1 || strlen($message) < 10) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $created = PrivateMessageModel::create([
            "sender" => App::user()->getUid(),
            "receiver" => $receiver,
            "message" => $message
        ]);

        if ($created) {
            return $receiver;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    public function showInbox ()
    {
        $messages = PrivateMessageModel::where([
            "receiver" => App::user()->getUid()
        ])->get()->toArray();
    }

    public function showConversation ()
    {
        $user = Input::getInteger("uid");

        $messages = PrivateMessageModel::where([
            "receiver" => App::user()->getUid()
        ])
        ->orWhere(function ($query) {
            $query->where('sender', App::user()->getUid());
        })
        ->get();

        // set them as read
        PrivateMessageModel::where([
            "receiver" => App::user()->getUid(),
            "sender" => $user
        ])->update([
            "read" => 1
        ]);
    }
}