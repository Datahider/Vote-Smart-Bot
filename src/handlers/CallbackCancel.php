<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\BotView\BotView;
use losthost\telle\Bot;

class CallbackCancel extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        if ($callback_query->getData() == 'cancel') {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        $view = new BotView(Bot::$api, Bot::$chat->id, Bot::$language_code);
        $view->show('tpl_canceled', null, [], $callback_query->getMessage()->getMessageId());
        self::unsetPriority();
        
        try { Bot::$api->answerCallbackQuery($callback_query->getId()); } catch (\Exception $e) {}
        return true;
    }
}
