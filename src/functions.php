<?php

use losthost\patephon\data\name;
use losthost\DB\DBView;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\patephon\data\poll;

function addName($name, $gender) {
    
    $name = new name(['name' => $name, 'gender' => $gender], true);
    if ($name->isNew()) {
        $name->write();
        return true;
    }
    return false;
    
}

function showPoll($poll_id, $item_id, $message_id=null) {
    
    $view = new BotView(Bot::$api, Bot::$chat->id, Bot::$language_code);
    $poll = new poll(['id' => $poll_id], true);

    if ($poll->isNew()) {
        $view->show('err_no_poll');
    } elseif ($poll->admin == Bot::$user->id) {
        $poll_results = getPollResults($poll);
        if (!$message_id) {
            $message_id = $view->show('tpl_poll_settings', 'kbd_poll_settings', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => 0]);
        }
        $view->show('tpl_poll_settings', 'kbd_poll_settings', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => $message_id], $message_id);
    } elseif ($poll->isVoteAllowed(Bot::$user->id)) {
        $poll_results = getPollResults($poll);
        if (!$message_id) {
            $message_id = $view->show('tpl_poll_vote', 'kbd_poll_vote', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => 0]);
        }    
        $view->show('tpl_poll_vote', 'kbd_poll_vote', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => $message_id], $message_id);
    } else {
        $view->show('err_not_allowed');
    }
}

function getPollResults($poll) {

    $results = new DBView(<<<FIN
        SELECT 
            CASE
                WHEN poll_vote.vote = 4 THEN '🤘'
                WHEN poll_vote.vote = 3 THEN '👍'
                WHEN poll_vote.vote = 2 THEN '👌'
                WHEN poll_vote.vote = 1 THEN '🤔'
                WHEN poll_vote.vote < 0 THEN '⛔️'
                ELSE '▫️'
            END AS icon,
            poll_item.title AS title,
            poll_item.id AS id,
            SUM(IFNULL(sum_vote.vote, 0)) AS rating
        FROM 
            [poll_item] as poll_item
            LEFT JOIN [poll_vote] AS poll_vote ON poll_vote.poll_item = poll_item.id AND poll_vote.tg_user = :tg_user
            LEFT JOIN [poll_vote] AS sum_vote ON sum_vote.poll_item = poll_item.id
        WHERE 
            poll_item.poll = :poll
        GROUP BY
            icon, title, id
        ORDER BY 
            rating DESC
        FIN, ['poll' => $poll->id, 'tg_user' => Bot::$user->id]); 

    $poll_results = [];
    while ($results->next()) {
        $poll_results[] = [
            'icon' => $results->icon,
            'title' => $results->title,
            'id' => $results->id,
            'rating' => $results->rating,
        ];
    }

    return $poll_results;
}