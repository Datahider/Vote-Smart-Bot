<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\patephon\handlers\PriorityPollTitle;
use losthost\BotView\BotView;
use losthost\telle\Bot;

class CommandNewPoll extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText() && preg_match("/\/newpoll/i", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $view = new BotView(Bot::$api, Bot::$chat->id);
        $view->show('cmd_new_poll', 'kbd_cancel');
        PriorityPollTitle::setPriority(null);
        
        return true;
    }
}
