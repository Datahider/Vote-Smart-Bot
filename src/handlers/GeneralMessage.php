<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\telle\Bot;
use losthost\BotView\BotView;
use losthost\patephon\data\user;
use losthost\patephon\data\poll;
use losthost\patephon\service\MessageDeleter;
use losthost\patephon\data\poll_item;

class GeneralMessage extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        if (Bot::$user->id <> Bot::$chat->id || !$message->getText()) {
            return false;
        }
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        $user = new user(['tg_user' => Bot::$user->id], true);
        $poll = new poll(['id' => $user->last_poll], true);
        
        $view = new BotView(Bot::$api, Bot::$user->id, Bot::$language_code);
        if ($poll->stage == 'ideas' || $poll->admin == $user->tg_user) {
            $this->addIdeas($user, $poll, $message);
        } else {
            $view->show('err_no_last_poll');
        }
        
        return true;
    }
    
    protected function addIdeas($user, $poll, $message) {

        $new_ideas = [];
        
        foreach (preg_split("/[\n]/", $message->getText(), -1, PREG_SPLIT_NO_EMPTY) as $title) {
            $item = new poll_item(['poll' => $poll->id, 'title' => $title], true);
            if ($item->isNew()) {
                $item->write();
                $new_ideas[] = $item;
            }
        }
        
        Bot::$api->deleteMessage(Bot::$chat->id, $message->getMessageId());
        showPoll($poll->id, null, $user->last_poll_message);
        $this->showAdded($poll, $new_ideas);
        
        queueInlineUpdates($poll->id);
    }
    
    protected function showAdded($poll, $new_ideas) {
        
        $view = new BotView(Bot::$api, Bot::$user->id, Bot::$language_code);
        $message_id = $view->show('tpl_added_ideas', null, ['poll' => $poll, 'ideas' => $new_ideas]);
        Bot::runAt(date_create_immutable("+3 sec"), MessageDeleter::class, Bot::$user->id. " $message_id");
    }
}
