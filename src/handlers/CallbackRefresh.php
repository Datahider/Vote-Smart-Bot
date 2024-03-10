<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\telle\Bot;
use losthost\BotView\BotView;

class CallbackRefresh extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        if (preg_match("/^refresh_\d+$/", $callback_query->getData())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        
        $m = [];
        preg_match("/^refresh_(\d+)$/", $callback_query->getData(), $m);
        
        showPoll($m[1], null, $callback_query->getMessage()->getMessageId());

        try { Bot::$api->answerCallbackQuery($callback_query->getId()); } catch (\Exception $e) {}
        return true;
    }
}
