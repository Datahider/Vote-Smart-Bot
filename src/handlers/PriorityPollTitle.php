<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\patephon\data\poll;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\passg\Pass;

class PriorityPollTitle extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText()) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        if (preg_match("/^\//", $message->getText())) {
            self::unsetPriority();
            return false;
        }
        
        $poll = new poll([
            'title' => $message->getText(), 
            'admin' => Bot::$user->id, 
            'secret' => Pass::generate(32),
            'language_code' => Bot::$language_code,
            'max_rating' => 5,
            'is_free_votes' => true,
            'stage' => 'created',
            'is_open' => true,
            'can_block' => true,
        ], true);
        $poll->write();
        
        showPoll($poll->id, null);
        self::unsetPriority();
        return true;
    }
}
