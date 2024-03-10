<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\patephon\data\poll;
use losthost\BotView\BotView;
use losthost\telle\Bot;

class CommandStartLink extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText() && preg_match("/^\/start\s+\d+-\w+$/i", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $m = [];
        preg_match("/^\/start\s+(\d+)-(\w+)$/i", $message->getText(), $m);
        
        $poll = new poll(['id' => $m[1], 'secret' => $m[2]], true);
        if ($poll->isNew()) {
            $view = new BotView(Bot::$api, Bot::$user->id, Bot::$language_code);
            $view->show('err_not_allowed');
        } else {
            showPoll($poll->id, null);
        }
        
        return true;
    }
}
