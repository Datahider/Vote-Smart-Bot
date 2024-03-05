<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\patephon\data\poll;
use losthost\patephon\data\poll_item;
use losthost\DB\DBList;

class CommandMyPolls extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText() && preg_match("/^\/mypolls$/i", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        $polls = new DBList(poll::class, 'admin = :admin ORDER BY id', ['admin' => Bot::$user->id]);
        
        $view = new BotView(Bot::$api, Bot::$chat->id, Bot::$language_code);
        $view->show('cmd_my_polls', null, ['polls' => $polls->asArray()]);

        return true;
    }
}
