<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\patephon\data\poll;
use losthost\telle\Bot;

class CallbackToggle extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        if (preg_match("/^toggle_/", $callback_query->getData())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        $data = $callback_query->getData();
        $m = [];
        $err = null;
        
        if (preg_match("/^toggle_free_votes_(\d+)$/", $data, $m)) {
            $poll = new poll(['id' => $m[1]]);
            if ($poll->is_free_votes && $poll->itemCount() == 0) {
                $err = __("Add items first.");
            } else {
                $poll->is_free_votes = !$poll->is_free_votes;
                $poll->write();
                showPoll($poll->id, null, $callback_query->getMessage()->getMessageId());
            }
        }
        
        try { Bot::$api->answerCallbackQuery($callback_query->getId(), $err, true); } catch (\Exception $e) {}
        return true;
    }
    
}
