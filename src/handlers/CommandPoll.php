<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;

class CommandPoll extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if (Bot::$chat->id == Bot::$user->id) {
            return false;
        }
        
        $text = $message->getText();
        if ($text && preg_match("/^\/poll(\s*\d+)?$/i", $text)) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
    
        $m = null;
        $text = $message->getText();
        
        if (preg_match("/\/poll$/i", $text)) {
            
        } elseif (preg_match("/\/poll\s*(\d+)$/i", $test, $m)) {
            
        }
    }
}
