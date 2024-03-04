<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\BotView\BotView;
use losthost\telle\Bot;

class PriorityNamesHandler extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if (preg_match("/^\//", $message->getText())) {
            self::unsetPriority();
            return false;
        }
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $text = $message->getText();
        
        $names = preg_split("/[, \n]/", $text, -1, PREG_SPLIT_NO_EMPTY);
        
        $count = 0;
        foreach ($names as $key => $name) {
            $name_to_add = mb_strtoupper(mb_substr($name, 0, 1)). mb_substr($name, 1);
            if (addName($name_to_add, Bot::$session->data)) {
                $count++;
            } 
        }
        
        
        $view = new BotView(Bot::$api, Bot::$chat->id);
        $view->show('priority_names', null, ['count' => $count, 'gender' => Bot::$session->data]);
        
        self::unsetPriority();
        return false;
    }
}
