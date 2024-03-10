<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\polabrain\data\poll_item;
use losthost\polabrain\data\poll;

class CommandStartSelect extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if ($message->getText() && preg_match("/\/start s_\d+_\d+$/", $message->getText())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        
        if (!canProcessCommand()) {
            return true;
        }
        
        $m = [];
        preg_match("/\/start s_(\d+)_(\d+)$/", $message->getText(), $m);
        $message_id = $m[1];
        $poll_item = new poll_item(['id' => $m[2]]);
        $poll = new poll(['id' => $poll_item->poll]);
        
        if ($poll->stage == 'voting' || $poll->admin == Bot::$user->id) {
            showPoll($poll->id, $poll_item->id, $message_id);
        } else {
            $view = new BotView(Bot::$api, Bot::$user->id, Bot::$language_code);
            if ($poll->stage == 'end') {
                $view->show('err_voting_finished', null, ['poll' => $poll]);
            } else {
                $view->show('err_voting_not_started', null, ['poll' => $poll]);
            }
        }
        
        Bot::$api->deleteMessage(Bot::$chat->id, $message->getMessageId());
        updateLastCommand();
        return true;
        
    }
}
