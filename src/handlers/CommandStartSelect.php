<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\patephon\data\poll_item;
use losthost\patephon\data\poll;

class CommandStartSelect extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText() && preg_match("/\/start select_\d+_\d+$/", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $m = [];
        preg_match("/\/start select_(\d+)_(\d+)$/", $message->getText(), $m);
        $message_id = $m[1];
        $poll_item = new poll_item(['id' => $m[2]]);
        $poll = new poll(['id' => $poll_item->poll]);
        
        showPoll($poll->id, $poll_item->id, $message_id);
        
        Bot::$api->deleteMessage(Bot::$chat->id, $message->getMessageId());
        return true;
        
    }
}
