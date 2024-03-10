<?php

namespace losthost\polabrain\handlers;

use losthost\telle\abst\AbstractHandlerInlineQuery;
use losthost\telle\Bot;
use TelegramBot\Api\Types\Inline\QueryResult\Article;
use TelegramBot\Api\Types\Inline\InputMessageContent\Text;
use losthost\DB\DBList;
use losthost\polabrain\data\poll;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

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
        $bot_username = Bot::param("bot_username", null);
        
        while ($poll = $poll_list->next()) {
            $results[] = new Article(
                $poll->id, 
                $poll->title, 
                getPollDescription($poll), 
                null, null, null, new 
                Text(getPollSharingText($poll), 'HTML'), 
                new InlineKeyboardMarkup([
                    [['text' => __('Take a part'), 'url' => "t.me/$bot_username?start=". $poll->getLink()]]
                ])
            );
        }
            
        Bot::$api->answerInlineQuery($inline_query->getId(), $results, 10, true, '', __('Create poll/brainstorm'), 'newpoll');
        return true;
    }
}
