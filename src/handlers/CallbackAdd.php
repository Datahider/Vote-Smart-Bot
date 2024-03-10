<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\polabrain\data\poll;
use losthost\telle\Bot;
use losthost\BotView\BotView;
use losthost\polabrain\handlers\PriorityPollItems;

class CallbackAdd extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        if (preg_match("/^add_\d+$/", $callback_query->getData())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        $data = $callback_query->getData();
        $m = null;
        
        preg_match("/^add_(\d+)$/", $data, $m);
        $poll = new poll(['id' => $m[1]]);
        $view = new BotView(Bot::$api, Bot::$chat->id, Bot::$language_code);

        if ($poll->stage == 'ideas' || $poll->admin == Bot::$user->id) {
            $message_id = $view->show('tpl_add_items', 'kbd_cancel', ['poll' => $poll]);
            PriorityPollItems::setPriority(['message_id' => $message_id, 'poll_id' => $poll->id, 'poll_message_id' => $callback_query->getMessage()->getMessageId()]);
        } else {
            if ($poll->stage == 'created') {
                $view->show('err_ideas_not_started', null, ['poll' => $poll]);
            } else {
                $view->show('err_ideas_finished', null, ['poll' => $poll]);
            }
        }
        
        try { Bot::$api->answerCallbackQuery($callback_query->getId()); } catch (\Exception $e) {}
        return true;
    }
}
