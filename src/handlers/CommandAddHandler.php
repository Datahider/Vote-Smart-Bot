<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\patephon\handlers\PriorityNamesHandler;
use losthost\BotView\BotView;
use losthost\telle\Bot;

class CommandAddHandler extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText && preg_match("/^\/add(\s.*)?$/i", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
     
        $view = new BotView(Bot::$api, Bot::$chat->id);
        
        if (Bot::$user->id <> Bot::param('bot_admin', 203645978)) {
            $view->show('cmd_add_forbidden');
        } elseif (preg_match("/^\/add\s+f$/", $message->getText())) {
            PriorityNamesHandler::setPriority('f');
            $view->show('cmd_add_female');
        } elseif (preg_match("/^\/add\s+m$/", $message->getText())) {
            PriorityNamesHandler::setPriority('m');
            $view->show('cmd_add_male');
        } else {
            $view->show('cmd_add_use_gender');
        }
        
        return true;
    }
}
