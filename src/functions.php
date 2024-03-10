<?php

use losthost\polabrain\data\name;
use losthost\DB\DBView;
use losthost\BotView\BotView;
use losthost\telle\Bot;
use losthost\polabrain\data\poll;
use losthost\templateHelper\Template;
use losthost\DB\DB;
use losthost\telle\model\DBPendingJob;
use losthost\polabrain\service\InlineUpdater;
use losthost\polabrain\data\user;

function __($string, $language_code=null) {
    if (is_null($language_code)) { 
        $language_code = Bot::$language_code == 'ru' ? 'default' : Bot::$language_code;
    } elseif ($language_code == 'ru') {
        $language_code = 'default';
    }
    
    include "src/templates/$language_code/__.php";
    if (!empty($translations[$string])) {
        return $translations[$string];
    }
    return $string;
}

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
        $poll_results = getPollResults($poll, Bot::$user->id);
        if (!$message_id) {
            $message_id = $view->show('tpl_poll_settings', 'kbd_poll_settings', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => 0]);
            $user = new user(['tg_user' => Bot::$user->id], true);
            if ($user->tg_user) {
                $user->last_poll = $poll->id;
                $user->last_poll_message = $message_id;
                $user->write();
            }
        }
        $view->show('tpl_poll_settings', 'kbd_poll_settings', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => $message_id], $message_id);
    } elseif ($poll->isVoteAllowed(Bot::$user->id)) {
        $poll_results = getPollResults($poll, Bot::$user->id);
        if (!$message_id) {
            $message_id = $view->show('tpl_poll_vote', 'kbd_poll_vote', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => 0]);
            $user = new user(['tg_user' => Bot::$user->id], true);
            if ($user->tg_user) {
                $user->last_poll = $poll->id;
                $user->last_poll_message = $message_id;
                $user->write();
            }
        }    
        $view->show('tpl_poll_vote', 'kbd_poll_vote', ['poll' => $poll, 'poll_results' => $poll_results, 'selected' => $item_id, 'message_id' => $message_id], $message_id);
    } else {
        $view->show('err_not_allowed');
    }
}

function getPollResults($poll, $user_id) {

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
        FIN, ['poll' => $poll->id, 'tg_user' => $user_id]); 

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

function getPollDescription($poll) {
    $tpl = new Template('func_poll_description.php', Bot::$language_code);
    $tpl->setTemplateDir('src/templates');
    $tpl->assign('poll', $poll);
    return $tpl->process();
}

function getPollSharingText($poll) {
    $tpl = new Template('func_poll_sharing_text.php', $poll->language_code);
    $tpl->setTemplateDir('src/templates');
    $tpl->assign('poll', $poll);
    $tpl->assign('poll_results', getPollResults($poll, 0));
    return $tpl->process();
}

function queueInlineUpdates($poll_id) {
    $sql = "UPDATE [inline_message] SET needs_update = 1 WHERE poll = ?";
    $sth = DB::prepare($sql);
    $sth->execute([$poll_id]);
    Bot::runAt(date_create_immutable(), InlineUpdater::class, '', false);
}

function canProcessCommand() {
    $user = new user(['tg_user' => Bot::$user->id], true);
    error_log($user->last_command_timestamp. '; '. time(). ';');
    if ($user->isNew() || is_null($user->last_command_timestamp) || $user->last_command_timestamp < time()-1) {
        error_log('Can');
        return true;
    }
    error_log('Can not');
    return false;
}

function updateLastCommand() {
    $user = new user(['tg_user' => Bot::$user->id], true);
    $user->last_command_timestamp = time();
    $user->write();
}
