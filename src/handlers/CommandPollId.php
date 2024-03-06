<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;

class CommandPollId extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if (Bot::$chat->id == Bot::$user->id && $message->getText() 
                && (preg_match("/^\/\d+$/", $message->getText())
                    || preg_match("/\/start\s+\d+$/i", $message->getText()))) {
            return true;
        }
        
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $m = [];
        
        if (preg_match("/^\/(\d+)$/", $message->getText(), $m)
                || preg_match("/\/start\s+(\d+)$/i", $message->getText(), $m)) {
            showPoll($m[1], null);
        }
        
        return true;
    }
}
