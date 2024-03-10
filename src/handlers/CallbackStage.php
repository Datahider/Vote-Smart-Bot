<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\polabrain\data\poll;
use losthost\telle\Bot;

class CallbackStage extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        if (preg_match("/^stage_/", $callback_query->getData())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        $data = $callback_query->getData();
        $m = [];
        
        if (preg_match("/^stage_next_(\d+)/", $data, $m)) {
            $poll = new poll(['id' => $m[1]]);
            if ($poll->stage == 'created' && $poll->is_free_votes) {
                $poll->stage = 'ideas';
            } elseif ($poll->stage == 'created' || $poll->stage == 'ideas') {
                $poll->stage = 'voting';
            } else {
                $poll->stage = 'end';
            }
            $poll->write();
            showPoll($poll->id, null, $callback_query->getMessage()->getMessageId());
            queueInlineUpdates($poll->id);
        } elseif (preg_match("/^stage_prev_(\d+)/", $data, $m)) {
            $poll = new poll(['id' => $m[1]]);
            if ($poll->stage == 'voting' && $poll->is_free_votes) {
                $poll->stage = 'ideas';
            } elseif ($poll->stage == 'voting' || $poll->stage == 'ideas') {
                $poll->stage = 'created';
            } else {
                $poll->stage = 'voting';
            }
            $poll->write();
            showPoll($poll->id, null, $callback_query->getMessage()->getMessageId());
            queueInlineUpdates($poll->id);
        }

        try { Bot::$api->answerCallbackQuery($callback_query->getId()); } catch (\Exception $e) {}
        return true;
    }
}
