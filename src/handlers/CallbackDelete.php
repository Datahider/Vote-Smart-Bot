<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\polabrain\data\poll;

class CallbackDelete extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        if (preg_match("/^delete_\d+$/", $callback_query->getData())
                || preg_match("/^confirm_delete_\d+$/", $callback_query->getData())
                || preg_match("/^cancel_delete_\d+$/", $callback_query->getData())) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
    
        $view = new BotView(Bot::$api, Bot::$chat->id, Bot::$language_code);
 
        $m = [];
        if (preg_match("/^delete_(\d+)$/", $callback_query->getData(), $m)) {
            $view->show('tpl_delete_confirm', 'kbd_delete_confirm', ['poll' => new poll(['id' => $m[1], 'admin' => Bot::$user->id])], $callback_query->getMessage()->getMessageId());
        } elseif (preg_match("/^confirm_delete_(\d+)$/", $callback_query->getData(), $m)) {
            $poll = new poll(['id' => $m[1], 'admin' => Bot::$user->id]);
            $title = $poll->title;
            
            $poll->delete();
            $view->show('tpl_deleted', null, ['title' => $title], $callback_query->getMessage()->getMessageId());
        } elseif (preg_match("/^cancel_delete_(\d+)$/", $callback_query->getData(), $m)) {
            showPoll($m[1], null, $callback_query->getMessage()->getMessageId());
        } 
        
        try { Bot::$api->answerCallbackQuery($callback_query->getId()); } catch (\Exception $e) {}
        return true;
    }
}
