<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use Ramsey\Uuid\Uuid;

class CommandStart extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText() && preg_match("/^\/start$/i", $message->getText())) {
            return true;
        }
        
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {

        if (!canProcessCommand()) {
            return true;
        }
        
        $view = new BotView(Bot::$api, Bot::$chat->id, Bot::$language_code);
        $view->show('cmd_start');

        updateLastCommand();
        return true;
    }
    
}
