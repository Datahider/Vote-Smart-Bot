<?php

namespace losthost\polabrain\handlers;

use losthost\telle\Bot;
use losthost\BotView\BotView;
use losthost\telle\abst\AbstractHandlerMessage;
use losthost\polabrain\data\poll_item;

class PriorityPollItems extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText()) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        $poll_id = Bot::$session->data['poll_id'];
        $message_id = Bot::$session->data['message_id'];
        $poll_message_id = Bot::$session->data['poll_message_id'];
        
        foreach (preg_split("/[\n]/", $message->getText(), -1, PREG_SPLIT_NO_EMPTY) as $title) {
            $item = new poll_item(['poll' => $poll_id, 'title' => $title], true);
            if ($item->isNew()) {
                $item->write();
            }
        }
        
        Bot::$api->deleteMessage(Bot::$chat->id, $message->getMessageId());
        Bot::$api->deleteMessage(Bot::$chat->id, $message_id);
        showPoll($poll_id, null, $poll_message_id);
        
        self::unsetPriority();
        queueInlineUpdates($poll_id);
        return true;
    }
}
