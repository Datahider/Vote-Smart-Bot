<?php

namespace losthost\patephon\handlers;

use losthost\telle\abst\AbstractHandlerCallback;
use losthost\patephon\data\poll_item;
use losthost\patephon\data\poll;
use losthost\patephon\data\poll_vote;
use losthost\telle\Bot;
use losthost\DB\DBView;

class CallbackVote extends AbstractHandlerCallback {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        $data = $callback_query->getData();
        
        if (preg_match("/^vote_\d_\d+$/", $data)
                || preg_match("/^vote_never_\d+$/", $data)) {
            return true;
        }
        return false;
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {

        $data = $callback_query->getData();
        $m = [];
        
        if (preg_match("/^vote_(\d)_(\d+)$/", $data, $m)) {
            $poll_item = new poll_item(['id' => $m[2]]);
            $poll = new poll(['id' => $poll_item->poll]);
            $vote = $m[1];
        } elseif (preg_match("/^vote_never_(\d+)$/", $data, $m)) {
            $poll_item = new poll_item(['id' => $m[1]]);
            $poll = new poll(['id' => $poll_item->poll]);
            $vote = -1000000000;
        }
        
        if ($poll->admin == Bot::$user->id || $poll->isVoteAllowed(Bot::$user->id)) {
            $poll_vote = new poll_vote(['tg_user' => Bot::$user->id, 'poll_item' => $poll_item->id], true);
            $poll_vote->vote = $vote;
            $poll_vote->write();
            
            $this->normalizeVotes($poll, $poll_vote);
            
            showPoll($poll->id, $poll_item->id, $callback_query->getMessage()->getMessageId());
            queueInlineUpdates($poll->id);
        }
        
        try { Bot::$api->answerCallbackQuery($callback_query->getId()); } catch (\Exception $e) {}
        return true;
    }
    
    protected function normalizeVotes($poll, $poll_vote) {
        
        if ($poll_vote->vote == 0) {
            return;
        }
        
        $same_vote = new DBView(<<<FIN
            SELECT 
                poll_vote.id
            FROM 
                [poll_vote] AS poll_vote
                LEFT JOIN [poll_item] AS poll_item ON poll_item.id = poll_vote.poll_item
                LEFT JOIN [poll] AS poll ON poll.id = poll_item.poll
            WHERE
                poll.id = :poll_id
                AND poll_vote.vote = :vote
                AND poll_vote.id <> :vote_id
                AND poll_vote.tg_user = :tg_user
            FIN, ['poll_id' => $poll->id, 'vote' => $poll_vote->vote, 'vote_id' => $poll_vote->id, 'tg_user' => $poll_vote->tg_user]);
                
        while ($same_vote->next()) {
            $modify = new poll_vote(['id' => $same_vote->id]);
            if ($modify->vote < 0) {
                $modify->vote = 0;
            } else {
                $modify->vote -= 1;
            }
            $modify->write();
            $this->normalizeVotes($poll, $modify);
         }
        
    }
}
