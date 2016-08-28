<?php

namespace App\Controllers;

use App\Models\ChatVote;
use App\System\App;
use App\System\AppException;
use App\System\Controller;
use App\System\Input;
use App\Models\Chat as ChatModel;

class Chat extends Controller
{
    public function deleteMessage ()
    {
        $id = Input::getInteger("id");

        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $message = ChatModel::find($id);

        if (!$message || $message->sender != App::user()->getUid()) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $message->delete();

        return true;
    }

    public function writeMessage ()
    {
        $channelId = Input::getInteger("channelId");
        $channelType = Input::getInteger("channelType");
        $message = Input::getString("message", true);

        if (!in_array($channelType, ChatModel::$validTypes) || strlen($message) < 10) {
            throw new AppException(AppException::INVALID_DATA);
        }

        if ($channelType == ChatModel::CHANNEL_TYPE_REPLY) {
            if ($channelId < 1) {
                throw new AppException(AppException::INVALID_DATA);
            }
        } else {
            $channelId = $this->getChannelId($channelType);
        }

        $success = ChatModel::create([
            "channel_id" => $channelId,
            "channel_type" => $channelType,
            "sender" => App::user()->getUid(),
            "message" => $message
        ]);

        if ($success) {
            return $success->toArray();
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    private function getChannelId ($channelType)
    {
        // ensure that he can see this channel
        switch ($channelType)
        {
            case ChatModel::CHANNEL_TYPE_COUNTRY:
                return App::user()->getLocation()["country"]["id"];
            case ChatModel::CHANNEL_TYPE_WORLD:
                return 1;
            case ChatModel::CHANNEL_TYPE_POLITICAL_PARTY:
                $myParty = App::user()->getPoliticalParty();

                if (!$myParty) {
                    throw new AppException(AppException::INVALID_DATA);
                }

                return $myParty->id;
            case ChatModel::CHANNEL_TYPE_MILITIA:
                $myMilitia = App::user()->getMilitia();

                if (!$myMilitia) {
                    throw new AppException(AppException::INVALID_DATA);
                }
                return $myMilitia->id;
            default:
                throw new AppException(AppException::INVALID_DATA);
                break;
        }
    }

    public function showMessages ()
    {
        $channelId = Input::getInteger("channelId");
        $channelType = Input::getInteger("channelType");

        if (!in_array($channelType, ChatModel::$validTypes)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        if ($channelType == ChatModel::CHANNEL_TYPE_REPLY) {
            if ($channelId < 1) {
                throw new AppException(AppException::INVALID_DATA);
            }
        } else {
            $channelId = $this->getChannelId($channelType);
        }

        $messages = ChatModel::with("sender")->where([
            "channel_id" => $channelId,
            "channel_type" => $channelType
        ])->orderBy('id', 'DESC')->get();

        if (!$messages) {
            $messages = [];
        } else {
            $messages = $messages->toArray();
        }

        return $messages;
    }
    public function vote ()
    {
        $id = Input::getInteger("id");
        $uid = App::user()->getUid();

        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $message = ChatModel::find($id);

        if (!$message) {
            throw new AppException(AppException::INVALID_DATA);
        }

        // check if he has already voted
        $vote = ChatVote::where([
            "message" => $id,
            "voter" => $uid
        ])->first();

        if ($vote) {
            throw new AppException(AppException::INVALID_DATA);
        }

        ChatVote::create([
            "message" => $id,
            "voter" => $uid,
        ]);

        $message->likes++;
        $message->save();

        return true;
    }
}