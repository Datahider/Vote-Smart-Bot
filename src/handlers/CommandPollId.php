<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use losthost\patephon\data\poll;
use losthost\BotView\BotView;

class CommandPollId extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if (Bot::$chat->id == Bot::$user->id && $message->getText() 
                && preg_match("/^\/\d+$/", $message->getText())) {
            return true;
        }
        
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $m = [];
        
        if (preg_match("/^\/(\d+)$/", $message->getText(), $m)) {
            $poll = new poll(['id' => $m[1], 'admin' => Bot::$user->id], true);
            if (!$poll->isNew()) {
                showPoll($m[1], null);
            } else {
                $view = new BotView(Bot::$api, Bot::$user->id, Bot::$language_code);
                $view->show('err_not_allowed');
            }
        }
        
        return true;
    }
}
