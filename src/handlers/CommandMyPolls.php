<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\polabrain\data\poll;
use losthost\polabrain\data\poll_item;
use losthost\DB\DBList;

class CommandMyPolls extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if (Bot::$chat->id == Bot::$user->id && $message->getText() && preg_match("/^\/mypolls$/i", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        if (!canProcessCommand()) {
            return true;
        }
        
        $polls = new DBList(poll::class, 'admin = :admin ORDER BY id', ['admin' => Bot::$user->id]);
        
        $view = new BotView(Bot::$api, Bot::$chat->id, Bot::$language_code);
        $view->show('cmd_my_polls', null, ['polls' => $polls->asArray()]);
        
        updateLastCommand();
        return true;
    }
}
