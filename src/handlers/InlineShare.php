<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerInlineQuery;
use losthost\telle\Bot;
use TelegramBot\Api\Types\Inline\QueryResult\Article;
use TelegramBot\Api\Types\Inline\InputMessageContent\Text;
use losthost\DB\DBList;
use losthost\patephon\data\poll;

class InlineShare extends AbstractHandlerInlineQuery {
    
    protected function check(\TelegramBot\Api\Types\Inline\InlineQuery &$inline_query): bool {
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Inline\InlineQuery &$inline_query): bool {

        $query = $inline_query->getQuery();
        if ($query) {
            $query = "%$query%";
        } else {
            $query = '%';
        }
        
        $poll_list = new DBList(poll::class, 'admin = :tg_user AND title LIKE :search ORDER BY id DESC LIMIT 3', [
            'tg_user' => Bot::$user->id,
            'search' => $query
        ]);
        
        $results = [];
        while ($poll = $poll_list->next()) {
            $results[] = new Article($poll->id, $poll->title, $poll->id, null, null, null, new Text("Приглашаю вас принять участие в голосовании на тему <a href='t.me/votesmart_bot?start=$poll->id'>$poll->title</a>", 'HTML'));
        }
            
        Bot::$api->answerInlineQuery($inline_query->getId(), $results);
        return true;
    }
}
