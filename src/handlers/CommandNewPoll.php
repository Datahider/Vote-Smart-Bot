<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\polabrain\handlers\PriorityPollTitle;
use losthost\BotView\BotView;
use losthost\telle\Bot;

class CommandNewPoll extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if (Bot::$chat->id == Bot::$user->id && $message->getText() && preg_match("/\/newpoll/i", $message->getText())) {
            return true;
        }
        if (Bot::$chat->id == Bot::$user->id && $message->getText() && preg_match("/\/start newpoll/i", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        if (!canProcessCommand()) {
            return true;
        }
        
        $view = new BotView(Bot::$api, Bot::$chat->id);
        $view->show('cmd_new_poll', 'kbd_cancel');
        PriorityPollTitle::setPriority(null);
        
        updateLastCommand();
        return true;
    }
}
